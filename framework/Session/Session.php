<?php
/**
 * Created by PhpStorm.
 * User: igortokman
 * Date: 17.03.16
 * Time: 21:21
 */

namespace Framework\Session;


use Framework\DI\Service;

class Session
{
    /**
     * Stores the single instance
     * @var null
     */
    private static $instance = null;

    /**
     * Message container
     * @var array
     */
    public $messages = array();

    /**
     * Start new or resume existing session
     * Session constructor.
     *
     */
    private function __construct()
    {
        session_start();
        $token = $this->get('token');
        if(!isset($token))
            $this->set('token', Service::get('security')->generateToken());
    }

    private function __clone()
    {
    }

    /**
     * Gets an instance of the Session
     * @return Session|null
     */
    public static function getInstance(){
        if(is_null(self::$instance))
            self::$instance = new self();

        return self::$instance;
    }

    /**
     * Adds new message into global session array
     * @param $type
     * @param $message
     */
    public function addFlash($type, $message){
        $_SESSION['messages'][$type][] = $message;
    }

    /**
     * Destroys all data registered to a current session
     */
    public function destroy(){
        session_destroy();
    }

    /**
     * Fetches the variable from session array
     * @param $name
     * @return null
     */
    public function get($name){
        return isset($_SESSION[$name])? $_SESSION[$name] : null;
    }

    /**
     * Sets the variable into session array
     * @param $name
     * @param $value
     */
    public function set($name, $value){
        $_SESSION[$name] = $value;
    }

    /**
     * Gets the variable by name from session array
     * @param $name
     * @return null
     */
    function __get($name)
    {
        return isset($_SESSION[$name])? $_SESSION[$name] : null;
    }


    /**
     * Deletes variable from session container
     * @param $name
     */
    public function del($name){
        if(isset($_SESSION[$name]))
            unset($_SESSION[$name]);
    }
}