<?php
/**
 * Created by PhpStorm.
 * User: igortokman
 * Date: 21.03.16
 * Time: 21:04
 */

namespace CMS\Controller;

use Blog\Model\Post;
use Blog\Model\User;
use Framework\Controller\Controller;
use Framework\DI\Registry;
use Framework\DI\Service;
use Framework\Exception\DatabaseException;

class ProfileController extends Controller
{
    public function getAction()
    {
        if (Service::get('security')->isAuthenticated())
            return $this->render('get.html', array('posts' => Post::findByParams(array('user_id' => Service::get('session')->get('user')->id))));

        Service::get('session')->set('returnUrl', Registry::getConfig('route')['pattern']);
        return $this->redirect($this->generateRoute('login'));

    }

    public function updateAction()
    {
        $errors = array();

        if ($this->getRequest()->isPost()) {
            try {
                $user = User::find($this->getRequest()->post('id'));
                $user->role = $this->getRequest()->post('role');
                $user->email = $this->getRequest()->post('email');
                $user->save();
                Service::get('security')->setUser($user);
            } catch (DatabaseException $e) {
                $errors = array($e->getMessage());
            }
        }
        return $this->redirect($this->generateRoute('profile'));
    }
}

