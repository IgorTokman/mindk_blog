<?php
/**
 * Created by PhpStorm.
 * User: IgorTokman
 * Date: 04.03.2016
 * Time: 23:49
 */

namespace Framework\Helper;


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
}