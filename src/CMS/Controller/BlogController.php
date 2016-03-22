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
use Framework\Exception\DatabaseException;
use Framework\Validation\Validator;

class BlogController extends Controller
{
    public function removeAction($id)
    {
        $post = Post::find($id);
        $post->delete();
        return $this->redirect($this->generateRoute('profile'));
    }

    public function start_editAction($id)
    {
        return $this->render('start_edit.html', array('post' => Post::find($id)));
    }

    public function editAction($id)
    {
        $errors = array();

        if ($this->getRequest()->isPost()) {
            try {
                $post = Post::find($id);
                $post->title = $this->getRequest()->post('title');
                $post->content = $this->getRequest()->post('content');

                $validator = new Validator($post);
                if ($validator->isValid()) {
                    $post->save();
                    return $this->redirect($this->generateRoute('profile'));
                } else {
                    $error = $validator->getErrors();
                }

            } catch (DatabaseException $e) {
                //echo $e->getMessage();
                $error = array($e->getMessage());
            }
        }
        return $this->render(
            'start_edit.html',
            array('errors' => isset($error)?$error:null, 'post' => Post::find($id))
        );
    }
}
