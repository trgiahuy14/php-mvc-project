<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use Core\Controller;
use App\Middlewares\AuthMiddleware;
use App\Middlewares\RoleMiddleware;
use App\Models\Post;
use App\Models\Category;
use Core\Session;

final class PostController extends Controller
{
    private Post $postModel;
    private Category $categoryModel;

    public function __construct()
    {
        parent::__construct();
        AuthMiddleware::requireAuth();
        $this->postModel = new Post();
        $this->categoryModel = new Category();
    }

    /** 
     * List posts with search + pagination
     * 
     * @return void
     */
    public function index(): void
    {
        // Get search keyword from query string
        $input = filterData('get');
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
            'headerData' => ['title' => 'Quản lý bài viết - DevBlog CMS'],
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
        $categories = $this->categoryModel->getAllCategories();
        $data = [
            'headerData' => ['title' => 'Thêm bài viết - DevBlog CMS'],
            'categories' => $categories,
        ];
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
        $category_id = (int)($input['category_id'] ?? 0);


        // --- VALIDATION ---

        // Validate title
        if ($title === '') {
            $errors['title']['required'] = 'Tiêu đề bắt buộc phải nhập';
        } elseif (mb_strlen($title) < 10) {
            $errors['title']['length'] = 'TTiêu đề phải có ít nhất 10 ký tự';
        } elseif (mb_strlen($title) > 255) {
            $errors['title']['max'] = 'Tiêu đề không được vượt quá 255 ký tự';
        }

        // Validate content
        if ($content === '') {
            $errors['content']['required'] = 'Nội dung bắt buộc phải nhập';
        } elseif (mb_strlen($content) < 50) {
            $errors['content']['min'] = 'Nội dung phải có ít nhất 50 ký tự';
        }

        // Validate category
        if ($category_id <= 0) {
            $errors['category_id']['required'] = 'Vui lòng chọn danh mục';
        }


        // If validation fails 
        if (!empty($errors)) {
            Session::flash('msg', 'Vui lòng kiểm tra lại dữ liệu nhập vào');
            Session::flash('msg_type', 'danger');
            $oldData = ['title' => $title, 'content' => $content, 'category_id' => $category_id];
            Session::flash('oldData', $oldData);
            Session::flash('errors', $errors);
            redirect('/posts/add');
        }

        // --- PREPARE INSERT DATA ---
        $postData = [
            'title'        => $title,
            'content'      => $content,
            'author_id' => (int)Session::get('user_id'),
            'category_id' => $category_id,
            'views' => 0,
            'comment_count' => 0,
            'created_at'   => date('Y-m-d H:i:s'),
        ];

        // Insert into DB
        $inserted = $this->postModel->createPost($postData);

        if (!$inserted) {
            Session::flash('msg', 'Thêm bài viết thất bại');
            Session::flash('msg_type', 'danger');
            redirect('/posts/add');
        }

        Session::flash('msg', 'Thêm bài viết thành công.');
        Session::flash('msg_type', 'success');

        redirect('/posts');
    }

    /** Show edit page */
    public function showEdit()
    {
        // Get id from query string
        $input = filterData('get');
        $postId = (int) ($input['id'] ?? 0);

        if ($postId <= 0) {
            Session::flash('msg', 'ID bài viết không tồn tại');
            Session::flash('msg_type', 'danger');
            redirect('/posts');
        }
        // Get post data
        $post = $this->postModel->getPostById($postId);

        if (!$post) {
            Session::flash('msg', 'Bài viết không tồn tại');
            Session::flash('msg_type', 'danger');
            return redirect('/posts');
        }

        // Check permission: Admin, Editor, or post owner can edit
        if (!RoleMiddleware::canEdit($post['author_id'])) {
            Session::flash('msg', 'Bạn không có quyền sửa bài viết này');
            Session::flash('msg_type', 'danger');
            redirect('/posts');
        }

        // Load categories
        $categories = $this->categoryModel->getAllCategories();
        $data = [
            'headerData' => ['title' => 'Chỉnh sửa bài viết - DevBlog CMS'],
            'post' => $post,
            'categories' => $categories,
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
        $postId = (int)($input['id']);

        // Check if post exists
        $post = $this->postModel->getPostById($postId);
        if (!$post) {
            Session::flash('msg', 'Bài viết không tồn tại');
            Session::flash('msg_type', 'danger');
            redirect('/posts');
        }

        // Check permission: Admin, Editor, or post owner can edit
        if (!RoleMiddleware::canEdit($post['author_id'])) {
            Session::flash('msg', 'Bạn không có quyền sửa bài viết này');
            Session::flash('msg_type', 'danger');
            redirect('/posts');
        }

        $errors = [];

        // Normalize input
        $title    = trim($input['title'] ?? '');
        $content  = trim($input['content'] ?? '');
        $category_id = (int)($_POST['category_id'] ?? 0);

        // --- VALIDATION ---

        // Validate title
        if ($title === '') {
            $errors['title']['required'] = 'Tiêu đề bắt buộc phải nhập';
        } elseif (mb_strlen($title) < 5) {
            $errors['title']['length'] = 'Tiêu đề phải lớn hơn 5 ký tự';
        }

        if ($title === '') {
            $errors['title']['required'] = 'Tiêu đề không được để trống';
        } elseif (mb_strlen($title) < 10) {
            $errors['title']['min'] = 'Tiêu đề phải có ít nhất 10 ký tự';
        } elseif (mb_strlen($title) > 255) {
            $errors['title']['max'] = 'Tiêu đề không được vượt quá 255 ký tự';
        }

        if ($content === '') {
            $errors['content']['required'] = 'Nội dung không được để trống';
        } elseif (mb_strlen($content) < 50) {
            $errors['content']['min'] = 'Nội dung phải có ít nhất 50 ký tự';
        }

        if ($category_id <= 0) {
            $errors['category_id']['required'] = 'Vui lòng chọn danh mục';
        }

        // If validation fails
        if (!empty($errors)) {
            Session::flash('msg', 'Vui lòng kiểm tra lại dữ liệu nhập vào');
            Session::flash('msg_type', 'danger');
            Session::flash('oldData', $input);
            Session::flash('errors', $errors);
            redirect('/posts/edit?id=' . (int)$postId ?? 0);
        }

        // Update data
        $postData = [
            'title' => $title,
            'content' => $content,
            'category_id' => $category_id,
        ];

        $updated = $this->postModel->updatePost($postId, $postData);

        if (!$updated) {
            Session::flash('msg', 'Chỉnh sửa bài viết thất bại');
            Session::flash('msg_type', 'danger');
            redirect('/posts/edit?id=' . $postId);
        }

        Session::flash('msg', 'Chỉnh sửa bài viết thành công.');
        Session::flash('msg_type', 'success');
        redirect('/posts');
    }

    /** Handle delete post*/
    public function delete()
    {
        // Get id from query string
        $input = filterData('get');
        $postId = (int)($input['id'] ?? 0);

        // Validate post ID 
        if ($postId <= 0) {
            Session::flash('msg', 'Bài viết không tồn tại');
            Session::flash('msg_type', 'danger');
            redirect('/posts');
        }

        // Check if post exists
        $post = $this->postModel->getPostById($postId);

        if (empty($post)) {
            Session::flash('msg', 'Bài viết không tồn tại.');
            Session::flash('msg_type', 'danger');
            redirect('/posts');
        }

        // Check permission: Only admin, editor, user who owns the post can delete
        if (!RoleMiddleware::canDelete($post['author_id'])) {
            Session::flash('msg', 'Bạn không có quyền xóa bài viết này');
            Session::flash('msg_type', 'danger');
            redirect('/posts');
        }

        // Delete post
        $deleted = $this->postModel->deletePost($postId);

        if (!$deleted) {
            Session::flash('msg', 'Xóa bài viết thất bại, vui lòng thử lại.');
            Session::flash('msg_type', 'danger');
            redirect('/posts');
        }

        Session::flash('msg', 'Xóa bài viết thành công.');
        Session::flash('msg_type', 'success');
        redirect('/posts');
    }
}
