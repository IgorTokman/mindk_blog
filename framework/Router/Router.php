<?php
/**
 * Created by PhpStorm.
 * User: IgorTokman
 * Date: 07.02.2016
 * Time: 16:21
 */

namespace Framework\Router;

use Framework\DI\Registry;
use Framework\DI\Service;

class Router
{
    /**
     * Array that holds all routes
     * @var array
     */
    private static $map = array();

    /**
     * Router constructor.
     * @param array $routing_map
     */
    public function __construct($routing_map = array())
    {
        self::$map = $routing_map;
    }

    /**
     * Parses URL
     * Generates array of suitable route and optional parameters
     * @param $url
     * @return null | array of appropriate route and params
     */
    public function parseRoute($url)
    {
        $route_found = null;

        foreach(self::$map as $routeName => $route)
        {
            $pattern = $route['pattern'];

            // Checks if pattern has parameters
            if (strpos($pattern, '{'))
                list($param_names, $pattern) = $this->prepare($route);

            $pattern = '~^' . $pattern . '$~';

            if (preg_match($pattern, $url, $params)) {
                if(isset($route['_requirements']['_method']) && $route['_requirements']['_method'] === 'POST'
                    && !Service::get('request')->isPost())
                    continue;

                $route_found = $route;
                $route_found['params'] = array();
                $route_found['_name'] = $routeName;

                //Gets associative array of params
                if (count($params) > 1) {
                    $params = array_map('urldecode', array_slice($params, 1));
                    $params = array_combine($param_names, $params);
                    $route_found['params'] = $params;
                }

                Registry::setConfig('route', $route_found);
                break;
            }
        }
        //Launches the appropriate event
        Service::get('eventManager')->trigger('parseRoute', "Parsed URL \"" . $url ."\". Generated the array of suitable route \"" . $routeName . "\" and optional parameters");

        return $route_found;
    }

    /**
     * Generates array of matching pattern and array of param names
     * @param array $route
     * @return array
     */
    private function prepare($route = array())
    {
        // Pattern for searching params
        $regexp = '~\{(\w+)\}~';
        $pattern = $route['pattern'];

        preg_match_all($regexp, $pattern, $matches);

        // Replaces param name with matching value in route pattern
        foreach($matches[1] as $key => $param)
        {
            if (array_key_exists($param, $route['_requirements']))
                $replace_exp = '(' . $route['_requirements'][$param] . ')';
            else
                $replace_exp = '(\w+)';

            $pattern = str_replace($matches[0][$key], $replace_exp, $pattern);
        }

       return  array($matches[1], $pattern);
    }

    /**
     * Creates URL by route name and optional array of parameters
     * @param $route_name
     * @param array $params Optional array of parameters to use in URL
     * @return mixed The URL to the route
     */
    public function buildRoute($route_name, $params = array())
    {
        // Checks if route exists
        if (array_key_exists($route_name, self::$map))
            $url = self::$map[$route_name]['pattern'];

        // Replaces matching values of parameter array in route URL
        if (!empty($params))
            foreach($params as $key => $value)
                $url = str_replace('{'. $key .'}', $value, $url);

        return $url;
    }
}