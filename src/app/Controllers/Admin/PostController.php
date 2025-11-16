<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use Core\Controller;
use App\Models\Post;

final class PostController extends Controller
{
    private Post $postModel;
    protected array $currentUser;

    public function __construct()
    {
        parent::__construct();
        $this->requireLogin();
        $this->postModel = new Post();
        $this->currentUser = (array)getSession('current_user');
    }

    /** 
     * List posts with search + pagination
     * 
     * @return void
     */
    public function list(): void
    {
        // Get search keyword from query string
        $input = filterData('get');

        // Get keyword
        $keyword = trim((string)($input['keyword'] ?? ''));

        // Pagination config
        $perPage = 10;
        $page = isset($input['page']) ? max(1, (int) $input['page']) : 1;

        // Total rows matching
        $total = $this->postModel->countPostsByKeyword($keyword);
        $maxPage = max(1, (int) ceil($total / $perPage));

        // Clamp page to valid range
        if ($page > $maxPage) {
            $page = $maxPage;
        }

        // Offset calculation
        $offset = ($page - 1) * $perPage;

        // Fetch posts for current page
        $posts = $this->postModel->getPosts($perPage, $offset, $keyword);

        // Clean query string for pagination links
        $queryString = cleanQuery('page');

        // Prepare view data
        $data = [
            'title' => 'Danh sách bài viết test',
            'posts'       => $posts,
            'page'        => $page,
            'maxPage'     => $maxPage,
            'keyword'     => $keyword,
            'queryString' => $queryString,
            'offset'      => $offset,
            'total'       => $total
        ];

        // Render list view
        $this->view->render('admin/posts/index', 'admin', $data);
    }

    /** Show add-post page */
    public function showAdd()
    {
        $data = ['title' => 'Thêm bài viết'];
        $this->view->render('admin/posts/add', 'admin', $data);
    }

    /** Handle add-post POST request */
    public function add()
    {
        if (!isPost()) {
            return;
        }

        $input = filterData();
        $errors = [];

        // Normalize input
        $title     = trim($input['title'] ?? '');
        $content   = trim($input['content'] ?? '');
        $tags      = trim($input['tags'] ?? '');
        $minutes   = (int)($input['minutes_read'] ?? 0);
        $views     = (int)($input['views'] ?? 0);
        $comments  = (int)($input['comments'] ?? 0);

        // --- VALIDATION ---

        // Validate title
        if ($title === '') {
            $errors['title']['required'] = 'Tiêu đề bắt buộc phải nhập';
        } elseif (mb_strlen($title) < 5) {
            $errors['title']['length'] = 'Tiêu đề phải lớn hơn 5 ký tự';
        }

        // Validate content
        if ($content === '') {
            $errors['content']['required'] = 'Nội dung bắt buộc phải nhập';
        }

        // If validation fails 
        if (!empty($errors)) {
            setSessionFlash('msg', 'Vui lòng kiểm tra lại dữ liệu nhập vào');
            setSessionFlash('msg_type', 'danger');
            setSessionFlash('oldData', ['title' => $title, 'content' => $content]);
            setSessionFlash('errors', $errors);
            redirect('/posts/add');
        }

        // --- PREPARE INSERT DATA ---
        $dataInsert = [
            'title'        => $title,
            'author'       => $this->currentUser['fullname'],
            'content'      => $content,
            'tags'         => $tags,
            'minutes_read' => $minutes,
            'views'        => $views,
            'comments'     => $comments,
            'created_at'   => date('Y-m-d H:i:s')
        ];

        // Insert into DB
        $inserted = $this->postModel->createPost($dataInsert);

        if (!$inserted) {
            setSessionFlash('msg', 'Thêm bài viết thất bại');
            setSessionFlash('msg_type', 'danger');
            redirect('/posts/add');
        }

        setSessionFlash('msg', 'Thêm bài viết thành công.');
        setSessionFlash('msg_type', 'success');

        redirect('/posts');
    }

    /** Show edit page */
    public function showEdit()
    {
        // Get id from query string
        $input = filterData('get');
        $postId = (int) ($input['id'] ?? 0);

        if ($postId <= 0) {
            setSessionFlash('msg', 'ID bài viết không hợp lệ');
            setSessionFlash('msg_type', 'danger');
            redirect('/posts');
        }
        // Fetch existing post
        $postData = $this->postModel->getPostById($postId);

        if (!$postData) {
            setSessionFlash('msg', 'Bài viết không tồn tại');
            setSessionFlash('msg_type', 'danger');
            return redirect('/posts');
        }

        $data = [
            'title' => 'Chỉnh sửa bài viết',
            'postData' => $postData,
            'postId'   => $postId
        ];
        $this->view->render('admin/posts/edit', 'admin', $data);
    }

    /** Handle edit POST request */
    public function edit()
    {
        if (!isPost()) {
            return;
        }

        $input = filterData();
        $errors = [];

        // --- Normalize input ---
        $title    = trim($input['title'] ?? '');
        $content  = trim($input['content'] ?? '');
        $tags     = trim($input['tags'] ?? '');
        $minutes  = isset($input['minutes_read']) ? (int)$input['minutes_read'] : 0;
        $views    = isset($input['views']) ? (int)$input['views'] : 0;
        $comments = isset($input['comments']) ? (int)$input['comments'] : 0;
        $shares = isset($input['shares']) ? (int)$input['shares'] : 0;

        // --- VALIDATION ---

        // Validate title
        if ($title === '') {
            $errors['title']['required'] = 'Tiêu đề bắt buộc phải nhập';
        } elseif (mb_strlen($title) < 5) {
            $errors['title']['length'] = 'Tiêu đề phải lớn hơn 5 ký tự';
        }

        // Validate content
        if ($content === '') {
            $errors['content']['required'] = 'Nội dung bắt buộc phải nhập';
        }

        // If validation fails
        if (!empty($errors)) {
            setSessionFlash('msg', 'Vui lòng kiểm tra lại dữ liệu nhập vào');
            setSessionFlash('msg_type', 'danger');
            setSessionFlash('oldData', $input);
            setSessionFlash('errors', $errors);
            redirect('/posts/edit?id=' . (int)($input['id'] ?? 0));
        }

        // --- PREPARE UPDATE DATA  ---
        $dataUpdate = [
            'title'        => $title,
            'content'      => $content,
            'tags'         => $tags,
            'minutes_read' => $minutes,
            'views'        => $views,
            'comments'     => $comments,
            'updated_at'   => date('Y-m-d H:i:s')
        ];

        // Update
        $postId = (int)($input['id']);

        $updated = $this->postModel->updatePost($postId, $dataUpdate);

        if (!$updated) {
            setSessionFlash('msg', 'Chỉnh sửa bài viết thất bại');
            setSessionFlash('msg_type', 'danger');
            redirect('/posts/edit?id=' . $postId);
        }

        setSessionFlash('msg', 'Chỉnh sửa bài viết thành công.');
        setSessionFlash('msg_type', 'success');
        redirect('/posts');
    }

    /** Handle delete post action */
    public function delete()
    {
        // Get id from query string
        $input = filterData('get');
        $postId = (int)($input['id'] ?? 0);

        // Validate post ID 
        if ($postId <= 0) {
            setSessionFlash('msg', 'ID bài viết không hợp lệ');
            setSessionFlash('msg_type', 'danger');
            redirect('/posts');
        }

        // Check if post exists
        $post = $this->postModel->getPostById($postId);

        if (empty($post)) {
            setSessionFlash('msg', 'Bài viết không tồn tại.');
            setSessionFlash('msg_type', 'danger');
            redirect('/posts');
        }

        // Delete post
        $deleted = $this->postModel->deletePost($postId);

        if (!$deleted) {
            setSessionFlash('msg', 'Xóa bài viết thất bại, vui lòng thử lại.');
            setSessionFlash('msg_type', 'danger');
            redirect('/posts');
        }

        // Delete failed
        setSessionFlash('msg', 'Xóa bài viết thành công.');
        setSessionFlash('msg_type', 'success');
        redirect('/posts');
    }
}
