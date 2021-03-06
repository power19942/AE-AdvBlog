<?php

namespace App\Controllers\Blog;

use System\Controller;

class PostController extends Controller
{

    /**
     * Display Post Page
     *
     * @param int $id
     * @return mixed
     */
    public function index($id)
    {
        if (!$id) {
            return $this->url->redirect('/');
        }
        $post = $this->load->model('Posts')->getPostWithComments($id);
        if (!$post || $post->category_status !== 'enabled') {
            return $this->url->redirect('/404');
        }
        $this->load->model('Posts')->addView($id);
        $this->html->setTitle($post->title);
        $data['user'] = $this->load->model('Login')->isLogged();
        $data['ugid'] = $this->load->model('Login')->user()->ugid;
        $data['post'] = $post;
        $data['ads']  = $this->load->model('Ads')->enabled();
        $view         = $this->view->render('blog/post', $data);
        return $this->blogLayout->render($view);
    }

    /**
     * Add New Comment to the given post
     *
     * @param int $id
     * @return mixed
     */
    public function addComment($id)
    {
        // first we will check if there is no comment or the post does not exist
        // then we will redirect him to not found page
        $comment    = $this->request->post('comment');
        $postsModel = $this->load->model('Posts');
        $loginModel = $this->load->model('Login');
        $post       = $postsModel->get($id);
        if (!$post OR $post[0]->status == 'disabled' OR ! $comment OR ! $loginModel->isLogged()) {
            return $this->url->redirect('/404');
        }
        $user = $loginModel->user();
        $postsModel->addNewComment($id, $comment, $user->id);
        return $this->url->redirect('/post' . '/' . $id . '#comments');
    }

    /**
     * Load the post box view for the given post
     *
     * @param \stdClass $post
     * @return string
     */
    public function box($post)
    {
        return $this->view->render('blog/post-box', compact('post'));
    }

}
