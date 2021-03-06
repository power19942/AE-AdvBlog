<?php

namespace App\Models;

use System\Model;

class PostsModel extends Model
{

    /**
     * Table name
     * @var string
     */
    protected $table = 'posts';

    /**
     * Create a new Post record
     *
     * @return void
     */
    public function create()
    {
        // Getting user ID
        $uid = $this->load->model('Login')->user()->id;
        // Getting image
        $img = $this->upImg();
        // Changing image file permissions
        chmod($this->app->file->toPostsImg($img), 0777);
        $this->db
                ->data('uid', $uid)
                ->data('cid', $this->request->post('category'))
                ->data('title', trim($this->request->post('title')))
                ->data('text', $this->request->post('text'))
                ->data('img', $img)
                ->data('tags', $this->request->post('tags'))
                ->data('status', $this->request->post('status'))
                ->data('created', time())
                ->insert($this->table);
    }

    /**
     * Updating post data
     *
     * @param int $id
     * @return void
     */
    public function update($id)
    {
        // Checking for existing post
        $post = $this->db
                ->where('id = ?', $id)
                ->fetch($this->table);
        if (!$post) {
            return;
        }
        $img = $this->upImg();
        if ($img) {
            // Deleting old photo before submitting the new one
            $oldImg     = $post->img;
            $oldImgPath = $this->app->file->toPostsImg($oldImg);
            unlink($oldImgPath);
            // Changing new image file permissions
            $newImgPath = $this->app->file->toPostsImg($img);
            chmod($newImgPath, 0777);
            // Inserting the image filename in database
            $this->db->data('img', $img);
        }
        $this->db
                ->data('title', $this->request->post('title'))
                ->data('text', $this->request->post('text'))
                ->data('tags', $this->request->post('tags'))
                ->data('status', $this->request->post('status'))
                ->data('cid', $this->request->post('category'))
                ->where('id = ?', $id)
                ->update($this->table);
    }

    /**
     * Upload image
     *
     * @return string
     */
    public function upImg()
    {
        $img = $this->request->file('img');
        if (!$img->exists()) {
            return;
        }
        $imgPath = $img->move($this->app->file->to('Public/uploads/img/posts'));
        return $imgPath;
    }

    /**
     * Delete post and its image and comments
     *
     * @param int $id
     * @return void
     */
    public function delete($id)
    {
        $post    = $this->get($id)[0];
        $imgPath = $this->app->file->toPostsImg($post->img);
        unlink($imgPath);
        // Deleting comments :
        $this->db
                ->where('post_id=?', $post->id)
                ->delete('comments');
        parent::delete($id);
    }

    /**
     * Get record by ID
     *
     * @param int $id
     * @return array
     */
    public function get($id)
    {
        $post     = $this->db
                ->where('id = ?', $id)
                ->fetch($this->table);
        $author   = $this->db
                ->select('name')
                ->where('id = ?', $post->uid)
                ->fetch('u');
        $category = $this->db
                ->select('name')
                ->where('id = ?', $post->cid)
                ->fetch('categories');
        return [$post, $author, $category];
    }

    /**
     * Get all posts with their authors and total comments
     *
     * @return array
     */
    public function all()
    {
        return $this->db
                        ->select('posts.*', 'categories.name AS category', 'categories.status AS category_status', ' u.name AS `author`', 'u.status AS `author_status`')
                        ->select('(SELECT COUNT(`id`) FROM comments WHERE comments.post_id = posts.id) AS `total_comments`')
                        ->from('posts')
                        ->joins('LEFT JOIN categories ON posts.cid = categories.id')
                        ->joins('LEFT JOIN u ON posts.uid = u.id')
                        ->orderBy('posts.id', 'DESC')
                        ->fetchAll();
    }

    /**
     * Get Latest Posts
     *
     * @return array
     */
    public function latest()
    {
        return $this->db
                        ->select('posts.*', 'categories.name AS category, u.name AS `author`')
                        ->select('(SELECT COUNT(comments.id) FROM comments WHERE comments.post_id = posts.id) AS total_comments')
                        ->select('(SELECT categories.status FROM categories WHERE categories.id = posts.cid) AS category_status')
                        ->from('posts')
                        ->joins('LEFT JOIN categories ON posts.cid = categories.id')
                        ->joins('LEFT JOIN u ON posts.uid = u.id')
                        ->where('posts.status=?', 'enabled')
                        ->orderBy('posts.id', 'DESC')
                        ->fetchAll();
    }

    /**
     * Get Post With its comments
     *
     * @param int $id
     * @return mixed
     */
    public function getPostWithComments($id)
    {
        $post = $this->db
                ->select('posts.*', 'categories.name AS `category`', 'u.name', 'u.bio', 'u.img AS userImage')
                ->select('(SELECT categories.status FROM categories WHERE categories.id = posts.cid) AS category_status')
                ->from('posts')
                ->joins('LEFT JOIN categories ON posts.cid=categories.id')
                ->joins('LEFT JOIN u ON posts.uid=u.id')
                ->where('posts.id=? AND posts.status=?', $id, 'enabled')
                ->fetch();

        if (!$post) {
            return null;
        }
        // we will get the post comments
        // and each comment we will get for him the user name
        // who created that comment
        $post->comments = $this->db
                ->select('comments.*', 'u.name', 'u.img AS userImage')
                ->from('comments')
                ->joins('LEFT JOIN u ON comments.uid=u.id')
                ->where('comments.post_id=?', $id)
                ->fetchAll();

        return $post;
    }

    /**
     * Get Posts by author ID
     *
     * @param int $id
     * @return array
     */
    public function getPostsByAuthor($id)
    {
        // We Will get the current page
        $currentPage = $this->pagination->page();
        // We Will get the items Per Page
        $limit       = $this->pagination->itemsPerPage();
        // Set our offset
        $offset      = $limit * ($currentPage - 1);
        $posts       = $this->db
                ->select('posts.*', 'categories.name AS category')
                ->select('(SELECT COUNT(comments.id) FROM `comments` WHERE comments.post_id=posts.id) AS total_comments')
                ->from('posts')
                ->joins('LEFT JOIN categories ON posts.cid = categories.id')
                ->where('posts.uid=? AND posts.status=? AND categories.status = ?', $id, 'enabled', 'enabled')
                ->orderBy('posts.id', 'DESC')
                ->limit($limit, $offset)
                ->fetchAll($this->table);
        if (!$posts) {
            return [];
        }
        // Get total posts for pagination
        $totalPosts = $this->db
                ->select('COUNT(id) AS `total`')
                ->from('posts')
                ->where('uid=? AND status=?', $id, 'enabled')
                ->fetch();
        if ($totalPosts) {
            $this->pagination->setTotalItems($totalPosts->total);
        }
        return $posts;
    }

    /**
     * Get Posts by author ID
     *
     * @param int $tag
     * @return array
     */
    public function getPostsByTag($tag)
    {
        // We Will get the current page
        $currentPage = $this->pagination->page();
        // We Will get the items Per Page
        $limit       = $this->pagination->itemsPerPage();
        // Set our offset
        $offset      = $limit * ($currentPage - 1);
        $posts       = $this->db
                ->select('posts.*', 'u.name AS author', 'categories.name AS category')
                ->select('(SELECT COUNT(comments.id) FROM `comments` WHERE comments.post_id=posts.id) AS total_comments')
                ->from('posts')
                ->joins('LEFT JOIN u ON posts.uid = u.id')
                ->joins('LEFT JOIN categories ON posts.cid = categories.id')
                ->where('posts.tags LIKE ? AND posts.status=?', "%$tag%", 'enabled')
                ->orderBy('posts.id', 'DESC')
                ->limit($limit, $offset)
                ->fetchAll($this->table);
        if (!$posts) {
            return [];
        }
        // Get total posts for pagination
        $totalPosts = $this->db
                ->select('COUNT(id) AS `total`')
                ->from('posts')
                ->where('tags LIKE ? AND status=?', "%$tag%", 'enabled')
                ->fetch();
        if ($totalPosts) {
            $this->pagination->setTotalItems($totalPosts->total);
        }
        return $posts;
    }

    /**
     * Get posts that contains one or more of search queries
     *
     * @param string $query
     * @return array
     */
    public function search($query)
    {
        // We Will get the current page
        $currentPage = $this->pagination->page();
        // We Will get the items Per Page
        $limit       = $this->pagination->itemsPerPage();
        // Set our offset
        $offset      = $limit * ($currentPage - 1);
        $posts       = $this->db
                ->select('posts.*', 'u.name AS author', 'categories.name AS category')
                ->select('(SELECT COUNT(comments.id) FROM `comments` WHERE comments.post_id=posts.id) AS total_comments')
                ->from('posts')
                ->joins('LEFT JOIN u ON posts.uid = u.id')
                ->joins('LEFT JOIN categories ON posts.cid = categories.id')
                ->where('posts.title LIKE ? OR posts.text LIKE ? AND posts.status=?', "%$query%", "%$query%", 'enabled')
                ->orderBy('posts.id', 'DESC')
                ->limit($limit, $offset)
                ->fetchAll($this->table);
        if (!$posts) {
            return [];
        }
        // Get total posts for pagination
        $totalPosts = $this->db
                ->select('COUNT(id) AS `total`')
                ->from('posts')
                ->where('posts.title LIKE ? OR posts.text LIKE ? AND posts.status=?', "%$query%", "%$query%", 'enabled')
                ->fetch();
        if ($totalPosts) {
            $this->pagination->setTotalItems($totalPosts->total);
        }
        return $posts;
    }

    /**
     * Add New Comment to the given post
     *
     * @param int $postId
     * @param string $comment
     * @param int $userId
     * @return void
     */
    public function addNewComment($postId, $comment, $userId)
    {
        $this->db
                ->data('uid', $userId)
                ->data('post_id', $postId)
                ->data('comment', $comment)
                ->data('created', time())
                ->data('status', 'enabled')
                ->insert('comments');
    }

    /**
     * View counter
     *
     * @param ing $id
     * @return void
     */
    public function addView($id)
    {
        $views = $this->db
                        ->select('views')
                        ->where('id=?', $id)
                        ->fetch($this->table)->views + 1;
        $this->db
                ->data('views', $views)
                ->where('id=?', $id)
                ->update($this->table);
    }

    /**
     * Get Posts by views
     *
     * @param int $limit
     * @return array
     */
    public function getPostsByViews($limit = 5)
    {
        return $this->db
                        ->select('id, title')
                        ->where('posts.status=?', 'enabled')
                        ->orderBy('posts.views', 'DESC')
                        ->limit($limit)
                        ->fetchAll($this->table);
    }

}
