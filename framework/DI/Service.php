<?php
/**
 * Created by PhpStorm.
 * User: IgorTokman
 * Date: 04.03.2016
 * Time: 22:37
 */

namespace Framework\DI;


class Service
{
    /**
     * Services container
     * @var array
     */
    public static $services = array();

    /**
     * Returns the services registered in the services container
     * @param $serviceName
     * @return sercive  for serviceName or null if it does not exist
     */
    public static function get($serviceName){
        return array_key_exists($serviceName, self::$services) ? self::$services[$serviceName] : null;
    }

    /**
     * Registers a service in the services container
     * @param $serviceName
     * @param $service
     */
    public static function set($serviceName, $service){
        self::$services[$serviceName] = $service;
    }
}