<?php
/**
 * Created by PhpStorm.
 * User: IgorTokman
 * Date: 07.02.2016
 * Time: 16:24
 */

namespace Framework;

use Framework\Model\ActiveRecord;
use Framework\DI\Registry;
use Framework\DI\Service;
use Framework\Exception\HttpNotFoundException;
use Framework\Helper\Helper;
use Framework\Model\Article;
use Framework\Model\Connection;
use Framework\Request\Request;
use Framework\Router\Router;
use Blog\Model\Post;

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

        //Sets the error display mode
        Helper::errorReporting();
    }

    public function run()
    {
        $router = Service::get('router');
        $route =  $router->parseRoute($_SERVER['REQUEST_URI']);

        try{
            //Checks if route is empty
            if(!empty($route)) {
                //Returns Response object
                $response = Helper::dispatch($route['controller'], $route['action'], $route['params']);
                $response->send();
            }
            else
                throw new HttpNotFoundException("Route does not found");
        }
        catch(HttpNotFoundException $e){
            echo $e->getMessage();
            // Render 404 or just show msg
        }
        catch(\Exception $e){
            echo $e->getMessage();
            // Render 500 layout or just show msg
        }
    }
}