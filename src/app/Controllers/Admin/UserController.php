<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use Core\Controller;
use App\Middlewares\AuthMiddleware;
use App\Models\User;
use Core\Session;

final class UserController extends Controller
{
    private User $userModel;
    protected array $currentUser;

    public function __construct()
    {
        parent::__construct();
        AuthMiddleware::requireAuth();
        $this->userModel = new User();
        $this->currentUser = Session::get('current_user');
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

        // Get keyword
        $keyword = trim((string)($input['keyword'] ?? ''));

        // Pagination config
        $perPage = 10;
        $page = isset($input['page']) ? max(1, (int) $input['page']) : 1;

        // Total rows matching
        $total = $this->userModel->countUsersByKeyword($keyword);
        $maxPage = max(1, (int) ceil($total / $perPage));

        // Clamp page to valid range
        if ($page > $maxPage) {
            $page = $maxPage;
        }

        // Offset calculation
        $offset = ($page - 1) * $perPage;

        // Fetch users for current page
        $users = $this->userModel->getUsers($perPage, $offset, $keyword);

        // Clean query string for pagination links
        $queryString = cleanQuery('page');

        // Prepare view data
        $data = [
            'title' => 'Danh sách người dùng',
            'users'       => $users,
            'currentUser' => $this->currentUser,
            'page'        => $page,
            'maxPage'     => $maxPage,
            'keyword'     => $keyword,
            'queryString' => $queryString,
            'offset'      => $offset,
            'total'       => $total
        ];

        // Render list view
        $this->view->render('admin/users/index', 'admin', $data);
    }

    /** Show add-user page */
    public function showAdd()
    {
        $data = ['title' => 'Thêm người dùng'];
        $this->view->render('admin/users/add', 'admin', $data);
    }

    /** Handle add-user POST request */
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
            Session::flash('msg', 'Vui lòng kiểm tra lại dữ liệu nhập vào');
            Session::flash('msg_type', 'danger');
            Session::flash('oldData', ['title' => $title, 'content' => $content]);
            Session::flash('errors', $errors);
            redirect('/users/add');
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
        $inserted = $this->userModel->createUser($dataInsert);

        if (!$inserted) {
            Session::flash('msg', 'Thêm người dùng thất bại');
            Session::flash('msg_type', 'danger');
            redirect('/users/add');
        }

        Session::flash('msg', 'Thêm người dùng thành công.');
        Session::flash('msg_type', 'success');

        redirect('/users');
    }

    /** Show edit page */
    public function showEdit()
    {
        // Get id from query string
        $input = filterData('get');
        $userId = (int) ($input['id'] ?? 0);

        if ($userId <= 0) {
            Session::flash('msg', 'ID người dùng không hợp lệ');
            Session::flash('msg_type', 'danger');
            redirect('/users');
        }
        // Fetch existing post
        $postData = $this->userModel->getUserById($userId);

        if (!$postData) {
            Session::flash('msg', 'người dùng không tồn tại');
            Session::flash('msg_type', 'danger');
            return redirect('/users');
        }

        $data = [
            'title' => 'Chỉnh sửa người dùng',
            'postData' => $postData,
            'userId'   => $userId
        ];
        $this->view->render('admin/users/edit', 'admin', $data);
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
            Session::flash('msg', 'Vui lòng kiểm tra lại dữ liệu nhập vào');
            Session::flash('msg_type', 'danger');
            Session::flash('oldData', $input);
            Session::flash('errors', $errors);
            redirect('/users/edit?id=' . (int)($input['id'] ?? 0));
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
        $userId = (int)($input['id']);

        $updated = $this->userModel->updateUser($userId, $dataUpdate);

        if (!$updated) {
            Session::flash('msg', 'Chỉnh sửa người dùng thất bại');
            Session::flash('msg_type', 'danger');
            redirect('/users/edit?id=' . $userId);
        }

        Session::flash('msg', 'Chỉnh sửa người dùng thành công.');
        Session::flash('msg_type', 'success');
        redirect('/users');
    }

    /** Handle delete post action */
    public function delete()
    {
        // Get id from query string
        $input = filterData('get');
        $userId = (int)($input['id'] ?? 0);

        // Validate post ID 
        if ($userId <= 0) {
            Session::flash('msg', 'ID người dùng không hợp lệ');
            Session::flash('msg_type', 'danger');
            redirect('/users');
        }

        // Check if post exists
        $user = $this->userModel->getUserById($userId);

        if (empty($user)) {
            Session::flash('msg', 'người dùng không tồn tại.');
            Session::flash('msg_type', 'danger');
            redirect('/users');
        }

        // Delete post
        $deleted = $this->userModel->deleteUser($userId);

        if (!$deleted) {
            Session::flash('msg', 'Xóa người dùng thất bại, vui lòng thử lại.');
            Session::flash('msg_type', 'danger');
            redirect('/users');
        }

        // Delete failed
        Session::flash('msg', 'Xóa người dùng thành công.');
        Session::flash('msg_type', 'success');
        redirect('/users');
    }
}
