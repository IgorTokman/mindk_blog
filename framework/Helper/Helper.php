<?php
/**
 * Created by PhpStorm.
 * User: IgorTokman
 * Date: 04.03.2016
 * Time: 23:49
 */

namespace Framework\Helper;


use Framework\Exception\ClassException;
use Framework\Exception\WrongResponseTypeException;
use Framework\Response\Response;

class Helper
{
    /**
     * Returns the full path to the views folder for the appropriate controller
     * @param $className
     * @return string
     */
    public static function getViewPath($className){
        $pathParts = explode('\\', $className);
        $ctrlName = array_pop($pathParts);
        return __DIR__ . '/../../src/Blog/views/' . str_replace('Controller', '', $ctrlName) . '/';
    }

    /**
     * Creates an instance of controller and calls an action of that controller
     * @param $controllerName
     * @param $actionName
     * @param $params
     * @return mixed
     * @throws ClassException
     * @throws WrongResponseTypeException
     */
    public static function dispatch($controllerName, $actionName, $params){
        //Checks if controller class exists
        if(class_exists($controllerName)) {
            $controllerReflection = new \ReflectionClass($controllerName);
            $action = $actionName . 'Action';

            //Check if controller has action
            if ($controllerReflection->hasMethod($action)) {
                $controller = $controllerReflection->newInstance();
                $actionReflection = $controllerReflection->getMethod($action);

                //Checks if action has the appropriate number of parameters
                if ($actionReflection->getNumberOfParameters() <= count($params))
                    $response = $actionReflection->invokeArgs($controller, $params);
                else
                    throw new ClassException("Does not match the number of parameters");

                //Checks if the object $response is of the same class or a descendant of the class Response
                if ($response instanceof Response) {
                    return $response;
                }
                else
                    throw new WrongResponseTypeException("Controller returns wrong type of Response");
            }
            else
                throw new ClassException("Controller does not have this action");
        }
        else
            throw new ClassException("Class of controller does not found");
    }
}