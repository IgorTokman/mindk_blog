<?php
/**
 * Created by PhpStorm.
 * User: IgorTokman
 * Date: 07.02.2016
 * Time: 16:24
 */

namespace Framework;

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
        $router = new Router(include('../app/config/routes.php'));
        $route =  $router->parseRoute($_SERVER['REQUEST_URI']);
    }
}