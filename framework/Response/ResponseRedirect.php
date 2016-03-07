<?php
/**
 * Created by PhpStorm.
 * User: IgorTokman
 * Date: 20.02.2016
 * Time: 18:07
 */

namespace Framework\Response;


class ResponseRedirect extends Response
{
    /**
     * Redirection to another route
     * ResponseRedirect constructor.
     * @param string $route
     */
    public function __construct($route)
    {
        $this->setResponseCode(301);
        $this->setHeaders('Location', $route);
    }
}