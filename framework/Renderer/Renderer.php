<?php
/**
 * Created by PhpStorm.
 * User: IgorTokman
 * Date: 05.03.2016
 * Time: 13:14
 */

namespace Framework\Renderer;


use Framework\DI\Registry;
use Framework\DI\Service;
use Framework\Exception\FileException;
use Framework\Helper\Helper;

class Renderer
{
    /**
     * @var string Main wrapper template file location
     */
    protected $main_template = '';

    /**
     * Class instance constructor
     * @param $main_template_file
     */
    public function __construct($main_template_file = null){
        $this->main_template = $main_template_file;
    }

    /**
     * Render main template with specified content
     * @param $content
     * @return html/text
     */
    public function renderMain($content){
        return $this->render($this->main_template, compact('content'), false);
    }

    /**
     * Render specified template file with data provided
     * @param $template_path Template file path (full)
     * @param array $data Data array
     * @param bool|true $wrap To be wrapped with main template if true
     * @return bool|html
     * @throws FileException if template file does not exist
     */
    public function render($template_path, $data = array(), $wrap = true){

        $data['include'] = function($controllerName, $actionName, $params)
                                {Helper::dispatch($controllerName, $actionName, $params);};

        $data['getRoute'] = function($route_name, $params = array())
                                {return Service::get('router')->buildRoute($route_name, $params);};

        $data['generateToken'] = function(){echo '<input type="hidden" name="token" value="' . Service::get('session')->get('token') .'"/>';};

        $data['route'] = Registry::getConfig('route');

        $data['user'] = Service::get('session')->get('user');

        if(!$wrap) {
            //Gets messages from session message container to display them in view
            $flush = Service::get('session')->get('messages') ? Service::get('session')->get('messages') : array();
            Service::get('session')->del('messages');
        }
        extract($data);

        //Checks if template file exists
        if(file_exists($template_path)){
            ob_start();
            include($template_path);
            $content = ob_get_contents();
                ob_end_clean();
        }
        else
            throw new FileException("File " . $template_path . " does not found");

        if($wrap){
            $content = $this->renderMain($content);
        }
        return $content;
    }
}
