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
use Framework\Response\Response;
use Framework\Router\Router;

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
        Registry::setConfigArr(include('../app/config/config.php'));
        $router = new Router(Registry::getConfig('routes'));
        Service::set('router', $router);
        $route =  $router->parseRoute($_SERVER['REQUEST_URI']);

        try{
            $this->dispatch($route);
        }
        catch(HttpNotFoundException $e){
            // Render 404 or just show msg
        }
        catch(\Exception $e){
            // Render 500 layout or just show msg
        }
    }

    //Creates an instance of controller and calls an action of that controller
    private function dispatch($route){
        //Checks if route is empty
        if(!empty($route)) {
            
            //Checks if controller class exists
            if(class_exists($route['controller'])) {
                $controllerReflection = new \ReflectionClass($route['controller']);
                $action = $route['action'] . 'Action';

                //Check if controller has action
                if ($controllerReflection->hasMethod($action)) {
                    $controller = $controllerReflection->newInstance();
                    $actionReflection = $controllerReflection->getMethod($action);

                    //Checks if action has the appropriate number of parameters
                    if ($actionReflection->getNumberOfParameters() <= count($route['params']))
                        $response = $actionReflection->invokeArgs($controller, $route['params']);
                    else
                        throw new \Exception("Does not match the number of parameters");

                    //Checks if the object $response is of the same class or a descendant of the class Response
                    if ($response instanceof Response) {

                    }
                    else
                        throw new WrongResponseTypeException("Controller returns wrong type of Response");
                }
                else
                    throw new \Exception("Controller does not have this action");
            }
            else
                throw new \Exception("Class of controller does not found");
        }
        else
            throw new HttpNotFoundException("Route does not found");
    }
}