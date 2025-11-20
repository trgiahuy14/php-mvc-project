<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use Core\Controller;
use App\Middlewares\AuthMiddleware;
use App\Models\User;
use App\Services\FileUploadService;
use Core\Session;

final class UserController extends Controller
{
    private User $userModel;
    protected array $currentUser;
    private FileUploadService $uploadService;

    public function __construct()
    {
        parent::__construct();
        AuthMiddleware::requireAuth();
        $this->userModel = new User();
        $this->uploadService = new FileUploadService();
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
            'headerData' => ['title' => 'Quản lý người dùng - DevBlog CMS'],
            'users'       => $users,
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
        $data = ['headerData' => ['title' => 'Chỉnh sửa người dùng - DevBlog CMS']];
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
        $userData = $this->userModel->getUserById($userId);

        if (!$userData) {
            Session::flash('msg', 'Người dùng không tồn tại');
            Session::flash('msg_type', 'danger');
            return redirect('/users');
        }

        $data = [
            'headerData' => ['title' => 'Cập nhật người dùng - DevBlog CMS'],
            'userData' => $userData,
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

        $input = filterData('post');
        $errors = [];

        $userId = (int) ($input['id'] ?? 0);

        // --- Normalize input ---
        $username = trim($input['username'] ?? '');
        $email   = trim($input['email'] ?? '');
        $fullname   = trim($input['fullname'] ?? '');
        $phone = trim($input['phone'] ?? '');
        $role = $input['role'] ?? '';
        $status = $input['status'] ?? '';
        $bio = trim($input['bio'] ?? '');
        $password = $input['password'] ?? '';
        $password_confirm = trim($input['password_confirm'] ?? '');

        // --- VALIDATION ---

        // Validate username
        if ($username === '') {
            $errors['username']['required'] = 'Username không được để trống';
        } elseif (strlen($username) < 3) {
            $errors['username']['min'] = 'Username phải có ít nhất 3 ký tự';
        } else {
            // Check duplicate username (exclude current user)
            $existingUser = $this->userModel->getUserByUsername($username);
            if ($existingUser && $existingUser['id'] != $userId) {
                $errors['username']['unique'] = 'Username đã tồn tại';
            }
        }

        // Validate email
        if ($email === '') {
            $errors['email']['required'] = 'Email không được để trống';
        } elseif (!validateEmail($email)) {
            $errors['email']['format'] = 'Email không hợp lệ';
        } else {
            // Check duplicate email (exclude current user)
            $existingUser = $this->userModel->getUserByEmail($email);
            if ($existingUser && $existingUser['id'] != $userId) {
                $errors['email']['unique'] = 'Email đã tồn tại';
            }
        }

        // Validate fullname
        if ($fullname !== '') {
            if (strlen($fullname) < 2) {
                $errors['fullname']['min'] = 'Họ tên phải có ít nhất 2 ký tự';
            } elseif (strlen($fullname) > 100) {
                $errors['fullname']['max'] = 'Họ tên không được vượt quá 100 ký tự';
            } elseif (!preg_match('/^[\p{L}\s]+$/u', $fullname)) {
                $errors['fullname']['format'] = 'Họ tên chỉ được chứa chữ cái và khoảng trắng';
            }
        }

        // Validate phone
        if ($phone !== '') {
            $phoneClean = preg_replace('/[\s\-\(\)]/', '', $phone);
            if (!preg_match('/^[0-9+]{10,15}$/', $phoneClean)) {
                $errors['phone']['format'] = 'Số điện thoại không hợp lệ (10-15 số)';
            } elseif (strlen($phone) > 20) {
                $errors['phone']['max'] = 'Số điện thoại không được vượt quá 20 ký tự';
            }
        }

        // Validate role
        if (empty($role)) {
            $errors['role']['required'] = 'Vui lòng chọn vai trò';
        } elseif (!in_array($role, ['admin', 'editor', 'author'])) {
            $errors['role']['invalid'] = 'Vai trò không hợp lệ';
        }

        // Validate status
        if (empty($status)) {
            $errors['status']['required'] = 'Vui lòng chọn trạng thái';
        } elseif (!in_array($status, ['active', 'inactive', 'banned'])) {
            $errors['status']['invalid'] = 'Trạng thái không hợp lệ';
        }

        // Validate bio
        if ($bio !== '' && strlen($bio) > 500) {
            $errors['bio']['max'] = 'Giới thiệu không được vượt quá 500 ký tự';
        }

        // Validate password if provided
        if ($password !== '') {
            if (strlen($password) < 6) {
                $errors['password']['min'] = 'Mật khẩu phải có ít nhất 6 ký tự';
            } elseif (strlen($password) > 255) {
                $errors['password']['max'] = 'Mật khẩu không được vượt quá 255 ký tự';
            } elseif ($password_confirm === '') {
                $errors['password_confirm']['required'] = 'Vui lòng xác nhận mật khẩu';
            } elseif ($password !== $password_confirm) {
                $errors['password_confirm']['mismatch'] = 'Mật khẩu xác nhận không khớp';
            }
        }

        // If validation failed
        if (!empty($errors)) {
            Session::flash('msg', 'Vui lòng kiểm tra lại dữ liệu nhập vào');
            Session::flash('msg_type', 'danger');
            Session::flash('oldData', $input);
            Session::flash('errors', $errors);
            redirect('/users/edit?id=' . $userId);
        }

        // Prepare update data
        $updateData = [
            'username' => $username,
            'email' => $email,
            'fullname' => $fullname,
            'phone' => $phone,
            'role' => $role,
            'status' => $status,
            'bio' => $bio ?? '',
        ];

        // Add password if provided
        if ($password !== '') {
            $updateData['password'] = password_hash($password, PASSWORD_BCRYPT);
        }

        // Handle avatar upload
        $userData = $this->userModel->getUserById($userId);

        if (!empty($_FILES['avatar']['name'])) {
            $result = $this->uploadService->uploadAvatar(
                $_FILES['avatar'],
                $userData['avatar'] // old file path
            );

            if ($result['success']) {
                $updateData['avatar'] = $result['path'];
            } else {
                $errors['avatar'] = $result['error'];
            }
        }

        $updated = $this->userModel->updateUser($userId, $updateData);

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

        // Delete avatar file
        if (!empty($user['avatar'])) {
            $this->uploadService->deleteFile($user['avatar']);
        }

        // Delete user from database
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
