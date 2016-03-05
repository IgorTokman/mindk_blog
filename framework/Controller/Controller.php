<?php
/**
 * Created by PhpStorm.
 * User: IgorTokman
 * Date: 05.03.2016
 * Time: 0:23
 */

namespace Framework\Controller;


use Framework\DI\Service;
use Framework\Helper\Helper;
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
        // The full path to the view for the appropriate controller
        $fullpath = realpath(Helper::getViewPath(get_class($this)) . $layout);

        $renderer = new Renderer(Registry::getConfig('main_layout'));
        Service::set('renderer', $renderer);
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
        return Service::get('router')->buildRoute($route_name, $params);
    }

    /**
     * Redirection to another page
     * @param $url
     * @return ResponseRedirect
     */
    public function redirect($url){
        return new ResponseRedirect($url);
    }
}
