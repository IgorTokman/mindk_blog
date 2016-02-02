<?php

/**
 * Created by PhpStorm.
 * User: IgorTokman
 * Date: 02.02.2016
 * Time: 12:05
 */
class Loader
{
    /**
     * Stores the single instance
     */
    private static $instance;
    /**
     * Contains an associative array of namespaces and their directory path
     * @var array
     */
    private static $namespaces = array();

    /**
     * Loader constructor.
     * Registers autoloader in the stack SPL
     */
    private function __construct()
    {
        spl_autoload_register([__CLASS__,'load']);
    }

    private function __clone()
    {
    }

    /**
     * Gets an instance of the Loader
     * @return Loader
     */
    public static function getInstance()
    {
        if(is_null(self::$instance))
            self::$instance = new self();

        return self::$instance;
    }

    /**
     * Registration the directory path for namespace
     * @param $namespace
     * @param $path
     */
    public static function addNamespacePath($namespace,$path)
    {
        if(is_dir($path)){
            $namespace = rtrim($namespace,'\\');
            self::$namespaces[$namespace] = $path;
        }
    }

    /**
     * Loads class file by its full name
     * @param $classname
     */
    private static function load($classname)
    {
        /**
         * Separate the full classname into parts to compare namespace to its directory path
         */
        $pathParts = explode('\\',$classname);

        if(!empty($pathParts)){
            $namespace = array_shift($pathParts);

            if(array_key_exists($namespace, self::$namespaces)){
                $path = self::$namespaces[$namespace] . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR,$pathParts) . '.php';

                if(file_exists($path)){
                   include_once($path);
                }
            }
        }
    }

    /**
     * @return associative array of registered namespaces
     */
    private static function getNamespace()
    {
        return self::$namespaces;
    }
}

Loader::getInstance();
Loader::addNamespacePath('Framework\\',__DIR__);