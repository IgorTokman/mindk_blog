<?php
/**
 * Created by PhpStorm.
 * User: igortokman
 * Date: 30.03.16
 * Time: 18:49
 */

namespace Framework\EventManager;


use ReflectionClass;
use Framework\Logger\Logger;

class EventManager
{
    protected static $instance = null;
    protected $listeners = array();

    public static function getInstance(){
        if(is_null(self::$instance))
            self::$instance = new self();

        return self::$instance;
    }

    public function attach($eventName, $listener = array()){
        $this->listeners[$eventName][] = $listener;
        //array_push($this->listeners[$eventName], $listener);
    }

    public function registerEvent($eventName){
        if(!array_key_exists($eventName, $this->listeners))
            $this->listeners[$eventName] = array();
        return $this;
    }

    public function getListeners(){
        return $this->listeners;
    }

    public function trigger($eventName, $additional = null)
    {
        if (array_key_exists($eventName, $this->listeners)) {
            foreach ($this->listeners[$eventName] as $listener)
                $listener['method']->invokeArgs($listener['class'], array($eventName, $additional));

        }
    }
    public function addListener($class)
    {
        $class = new ReflectionClass($class);
        $methods = $class->getMethods();

        foreach($methods as $method) {
            $doc = $method->getDocComment();

            //Разбираем PHPdoc
            if(preg_match_all('/@event\s+(\w+)/is', $doc, $matches))
                foreach ($matches[1] as $match)
                    if (array_key_exists($match, $this->listeners))
                        $this->attach($match, array('method' => $method, 'class' => $class->newInstance()));
        }

        return $this;
    }
}