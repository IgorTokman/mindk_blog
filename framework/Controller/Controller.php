<?php
/**
 * Created by PhpStorm.
 * User: IgorTokman
 * Date: 05.03.2016
 * Time: 0:23
 */

namespace Framework\Controller;


use Framework\DI\Registry;
use Framework\DI\Service;
use Framework\Helper\Helper;
use Framework\Renderer\Renderer;
use Framework\Response\Response;
use Framework\Response\ResponseRedirect;

abstract class Controller
{
    /**
     * Rendering method
     * @param $layout file name
     * @param array $data
     * @return Response
     */
    public function render($layout, $data = array()){

        Service::get('eventManager')->trigger('controllerRender', "Rendering method. Controller \"" . get_class($this) ."\". Layout \"". $layout . "\"");

        // The full path to the view for the appropriate controller
        $fullpath = realpath(Helper::getViewPath(get_class($this)) . $layout . '.php');

        $renderer = Service::get('renderer');
        $content = $renderer->render($fullpath, $data);

        return new Response($content);
    }

    /**
     * Creates URL by route name and optional array of parameters
     * @param $route_name
     * @param array $params Optional array of parameters to use in URL
     * @return mixed The URL to the route
     */
    public function generateRoute($route_name, $params = array()){
        Service::get('eventManager')->trigger('controllerGenerateRoute', "Creates URL by route name \"" . $route_name ."\" and optional array of parameters");

        return Service::get('router')->buildRoute($route_name, $params);
    }

    /**
     * Redirection to another page
     * @param $url
     * @param null $message
     * @return ResponseRedirect
     */
    public function redirect($url, $message = null){
        Service::get('eventManager')->trigger('controllerRedirect', "Redirection to another page. URL \"" . $url . "\"");

        if(!is_null($message))
            Service::get('session')->addFlash('info', $message);

        return new ResponseRedirect($url);
    }

    /**
     * Gets the Request object
     * @return Request object from service container
     */
    public function getRequest(){
        return Service::get('request');
    }
}
