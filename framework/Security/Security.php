<?php
/**
 * Created by PhpStorm.
 * User: igortokman
 * Date: 17.03.16
 * Time: 22:16
 */

namespace Framework\Security;


use Framework\DI\Service;
use Framework\Security\Model\UserInterface;

class Security
{
    /**
     * Security constructor.
     */
    public function __construct()
    {
    }

    /**
     * Checks if the user is authenticated
     * @return true if user is authenticated else false
     */
    public function isAuthenticated(){
        return (!is_null(Service::get('session')->get('user')));
    }

    /**
     * Clears all user variable for session container
     */
    public function clear(){
        Service::get('session')->destroy();
    }

    /**
     * Sets current user into session
     * @param UserInterface $user
     */
    public function setUser(UserInterface $user){
        Service::get('session')->set('user', $user);
    }

    /**
     * Creates a random token
     * @return string
     */
    public function generateToken(){
        return md5(uniqid(rand(),1));
    }

    /**
     * Verifies token from session and form (method Post)
     * @return bool
     */
    public function verifyToken(){
        return (isset($_POST['token']) && !is_null(Service::get('session')->get('token'))
            && ($_POST['token'] === Service::get('session')->get('token')));
    }
}