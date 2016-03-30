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

    /**
     * Gets an instance of the EventManager
     * @return EventManager
     */
    public static function getInstance(){
        if(is_null(self::$instance))
            self::$instance = new self();

        return self::$instance;
    }

    /**
     * Attaches a listener to the events manager
     * @param $eventName
     * @param array $listener
     */
    protected function attach($eventName, $listener = array()){
        $this->listeners[$eventName][] = $listener;
    }

    /**
     * Registers the new event in the array listeners
     * @param $eventName
     * @return $this
     */
    public function registerEvent($eventName){
        if(!array_key_exists($eventName, $this->listeners))
            $this->listeners[$eventName] = array();

        return $this;
    }

    /**
     * Fetches the container listeners
     * @return array
     */
    public function getListeners(){
        return $this->listeners;
    }

    /**
     * Fires an event in the events manager causing the listeners to be notified about it
     * @param $eventName
     * @param null $additional
     */
    public function trigger($eventName, $additional = null)
    {
        if (array_key_exists($eventName, $this->listeners)) {
            foreach ($this->listeners[$eventName] as $listener)
                $listener['method']->invokeArgs($listener['class'], array($eventName, $additional));

        }
    }

    /**
     * Checks the class comments to add a new listener
     * @param $class
     * @return $this
     */
    public function addListener($class)
    {
        $class = new ReflectionClass($class);
        $methods = $class->getMethods();

        foreach($methods as $method) {
            $doc = $method->getDocComment();

            //Parses the PhpDoc
            if(preg_match_all('/@event\s+(\w+)/is', $doc, $matches))
                foreach ($matches[1] as $match)
                    //Saves the reflection method and class into listeners container
                    if (array_key_exists($match, $this->listeners))
                        $this->attach($match, array('method' => $method, 'class' => $class->newInstance()));
        }

        return $this;
    }
}