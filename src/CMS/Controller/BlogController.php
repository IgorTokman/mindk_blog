<?php
/**
 * Created by PhpStorm.
 * User: igortokman
 * Date: 22.03.16
 * Time: 12:50
 */

namespace CMS\Controller;


use Blog\Model\Post;
use Framework\Controller\Controller;
use Framework\DI\Registry;
use Framework\DI\Service;
use Framework\Exception\DatabaseException;
use Framework\Validation\Validator;

class BlogController extends Controller
{
    /**
     * Performs the deleting an appropriate post
     * @param $id
     * @return \Framework\Response\ResponseRedirect
     */
    public function removeAction($id)
    {
        $post = Post::find($id);
        $post->delete();
        return $this->redirect($this->generateRoute('profile'), 'You have successfully removed your article "' . $post->title . '"');
    }

    /**
     * Displays the edit window
     * @param $id
     * @return \Framework\Response\Response
     */
    public function start_editAction($id)
    {
        if (Service::get('security')->isAuthenticated())
            if(Post::find($id)->user_id === Service::get('session')->get('user')->id)
                return $this->render('start_edit.html', array('post' => Post::find($id)));
            else
                return $this->redirect($this->generateRoute('profile'), '"' . Post::find($id)->title . '".' . ' It is not your article');

        Service::get('session')->set('returnUrl', $this->generateRoute(Registry::getConfig('route')['_name'], array('id' => $id)));
        return $this->redirect($this->generateRoute('login'), 'You need to login');
    }

    /**
     * Performs the editing an appropriate post if it possible otherwise shows error messages
     * @param $id
     * @return \Framework\Response\Response|\Framework\Response\ResponseRedirect
     */
    public function editAction($id)
    {
        $errors = array();

        if ($this->getRequest()->isPost()) {
            try {
                $post = Post::find($id);
                $post->title = $this->getRequest()->post('title');
                $post->content = $this->getRequest()->post('content');

                //Verifies if the table record meets the requirement
                $validator = new Validator($post);
                if ($validator->isValid()) {
                    $post->save();
                    return $this->redirect($this->generateRoute('profile'), 'You have successfully edited your article "' . $post->title . '"');
                } else {
                    $errors = $validator->getErrors();
                }

            } catch (DatabaseException $e) {
                $errors = array($e->getMessage());
            }
        }

        //Displays error messages on the page
        return $this->render(
            'start_edit.html',
            array('errors' => isset($errors)? $errors : null, 'post' => Post::find($id))
        );
    }
}
