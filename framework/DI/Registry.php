<?php
/**
 * Created by PhpStorm.
 * User: IgorTokman
 * Date: 05.03.2016
 * Time: 11:40
 */

namespace Framework\DI;


class Registry
{
    /**
     * Configuration array
     * @var array
     */
    public static $configs = array();

    /**
     * Returns the config from configuration container
     * @param $configName
     * @return null
     */
    public static function getConfig($configName)
    {
        return array_key_exists($configName, self::$configs) ? self::$configs[$configName] : null;
    }

    /**
     * Registers the config in the configuration container
     * @param $configName
     * @param $config
     */
    public static function setConfig($configName, $config)
    {
        self::$configs[$configName] = $config;
    }

    /**
     * Registers configs from configs array parameter
     * @param array $configs_arr
     */
    public static function setConfigsArr($configs_arr = array()){
        foreach($configs_arr as $configName => $config)
            self::setConfigs($configName, $config);
    }
}