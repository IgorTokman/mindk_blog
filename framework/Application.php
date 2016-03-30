<?php
/**
 * Created by PhpStorm.
 * User: IgorTokman
 * Date: 07.02.2016
 * Time: 16:24
 */

namespace Framework;

use Framework\Exception\SecurityException;
use Framework\EventManager\EventManager;
use Framework\Model\ActiveRecord;
use Framework\DI\Registry;
use Framework\DI\Service;
use Framework\Exception\HttpNotFoundException;
use Framework\Helper\Helper;
use Framework\Model\Article;
use Framework\Model\Connection;
use Framework\Renderer\Renderer;
use Framework\Request\Request;
use Framework\Response\Response;
use Framework\Response\ResponseRedirect;
use Framework\Router\Router;
use Blog\Model\Post;
use Framework\Security\Security;
use Framework\Session\Session;
use Framework\Logger\Logger;

class Application
{
    /**
     * Application constructor.
     */
    public function __construct($configPath)
    {
        //Registration all configuration vars in the config container
        Registry::setConfigsArr(include($configPath));
        Service::set('router', new Router(Registry::getConfig('routes')));
        Service::set('dbConnection', Connection::get(Registry::getConfig('pdo')));
        Service::set('request', new Request());
        Service::set('security', new Security());
        Service::set('session', Session::getInstance());
        Service::set('renderer', new Renderer(Registry::getConfig('main_layout')));
        Service::set('eventManager', EventManager::getInstance());
        //Registers the events
        Service::get('eventManager')
            ->registerEvent('applicationInit')
            ->registerEvent('parseRoute')
            ->registerEvent('dispatchAction')
            ->registerEvent('controllerRender')
            ->registerEvent('controllerGenerateRoute')
            ->registerEvent('controllerRedirect')
            ->registerEvent('sendAction')
            ->registerEvent('renderAction');
        //Attaches a new listener
        Service::get('eventManager')
            ->addListener('Framework\Logger\Logger');

        //Sets the error display mode
        Helper::errorReporting();
        //Launches the appropriate event
        Service::get('eventManager')->trigger('applicationInit', "The start of application");
    }

    public function run()
    {
        $router = Service::get('router');
        $route =  $router->parseRoute($_SERVER['REQUEST_URI']);

        try{
            //Checks if route is empty
            if(!empty($route)) {

                //Verifies user role if it needs
                if(array_key_exists('security', $route))
                    if(is_null($user = Service::get('session')->get('user')) || !in_array($user->role, $route['security']))
                        throw new SecurityException("Access is denied");

                //Returns Response object
                $response = Helper::dispatch($route['controller'], $route['action'], $route['params']);
            }
            else
                throw new HttpNotFoundException("Route does not found", 404);
        }
        catch(SecurityException $e){
            Service::get('session')->set('returnUrl', Registry::getConfig('route')['pattern']);
            $response = new ResponseRedirect(Service::get('router')->buildRoute('login'));
        }
        catch(HttpNotFoundException $e){
            $response = new Response(Service::get('renderer')->render(Registry::getConfig('error_400'),
                array('code' => $e->getCode(), 'message' => $e->getMessage())));
        }
        catch(\Exception $e){
            $response = new Response(Service::get('renderer')->render(Registry::getConfig('error_500'),
                array('code' => $e->getCode(), 'message' => $e->getMessage())));
        }

        $response->send();
    }
}

