<?php
/**
 * Created by PhpStorm.
 * User: IgorTokman
 * Date: 07.02.2016
 * Time: 16:24
 */

namespace Framework;

use Framework\DI\Registry;
use Framework\DI\Service;
use Framework\Exception\HttpNotFoundException;
use Framework\Exception\WrongResponseTypeException;
use Framework\Helper\Helper;
use Framework\Response\Response;
use Framework\Router\Router;
use Framework\Renderer\Renderer;

class Application
{
    /**
     * Application constructor.
     */
    public function __construct()
    {

    }

    public function run()
    {
        //Registration all configuration vars in the config container
        Registry::setConfigsArr(include('../app/config/config.php'));
        $router = new Router(Registry::getConfig('routes'));
        Service::set('router', $router);
        $route =  $router->parseRoute($_SERVER['REQUEST_URI']);

        try{
            //Checks if route is empty
            if(!empty($route)) {
                //Returns Response object
                Helper::dispatch($route['controller'], $route['action'], $route['params']);
            }
            else
                throw new HttpNotFoundException("Route does not found");
        }
        catch(HttpNotFoundException $e){
            // Render 404 or just show msg
        }
        catch(\Exception $e){
            // Render 500 layout or just show msg
        }
    }
}