<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use Core\Controller;
use App\Models\User;
use App\Models\Token;
use App\Services\MailService;
use Core\Session;
use App\Middlewares\AuthMiddleware;
use Exception;


final class AuthController extends Controller
{
    private User $userModel;
    private MailService $mailService;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = new User();
        $this->mailService = new MailService();
    }

    /** Render login page */
    public function showLogin(): void
    {
        $data = [
            'headerData' => ['title' => 'Đăng nhập - DevBlog CMS']
        ];
        $this->view->render('admin/auth/login', 'auth', $data);
    }

    /** Handle login POST */
    public function login(): void
    {
        if (!isPost()) {
            return;
        }

        $input = filterData();
        $errors = [];

        // Get anh normalize input
        $email   = trim($input['email'] ?? '');
        $password = (string)($input['password'] ?? '');

        // Validate Email
        if ($email === '') {
            $errors['email']['required'] = 'Email bắt buộc phải nhập';
        } elseif (!validateEmail(trim($input['email']))) {
            $errors['email']['isEmail'] = 'Email không đúng định dạng';
        }

        // Validate password
        if ($password === '') {
            $errors['password']['required'] = 'Mật khẩu bắt buộc phải nhập';
        } elseif (strlen(trim($password)) < 6) {
            $errors['password']['length'] = 'Mật khẩu phải dài hơn 6 ký tự';
        }

        if (!empty($errors)) {
            Session::Flash('msg', 'Vui lòng kiểm tra thông tin nhập vào');
            Session::Flash('msg_type', 'danger');
            Session::Flash('oldData', ['email' => $email]);
            Session::Flash('errors', $errors);
            redirect('/login');
        }

        // Find user by email
        $user = $this->userModel->getUserByEmail($email);

        // Check user exists and password correct
        if (!$user || !password_verify($password, $user['password'])) {
            Session::Flash('msg', 'Mật khẩu hoặc email không chính xác');
            Session::Flash('msg_type', 'danger');
            redirect('/login');
        }

        // Check if email is activated
        if (!isset($user['email_verified_at']) || $user['email_verified_at'] === null) {
            Session::Flash('msg', 'Tài khoản chưa được kích hoạt');
            Session::Flash('msg_type', 'danger');
            redirect('/login');
        }

        $user = $this->userModel->getUserByEmail($email);

        AuthMiddleware::login($user);

        redirect('/dashboard');
    }

    /** Show register page */
    public function showRegister(): void
    {
        $data = ['headerData' => ['title' => 'Đăng ký tài khoản - DevBlog CMS']];
        $this->view->render('admin/auth/register', 'auth', $data);
    }

    /** Handle register POST */
    public function register(): void
    {
        if (!isPost()) {
            return;
        }

        $input = filterData();
        $errors = [];

        // Normalize inputs
        $username   = trim($input['username'] ?? '');
        $email      = trim($input['email'] ?? '');
        $password   = (string)($input['password'] ?? '');
        $confirmPass = (string)($input['confirm_pass'] ?? '');

        // Validate username
        if ($username === '') {
            $errors['username']['required'] = 'Tên đăng nhập bắt buộc phải nhập';
        } elseif (strlen($username) < 3) {
            $errors['username']['min'] = 'Tên đăng nhập phải lớn hơn 3 ký tự';
        } elseif (strlen($username) > 50) {
            $errors['username']['max'] = 'Tên đăng nhập không được vượt quá 50 ký tự';
        } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
            $errors['username']['format'] = 'Tên đăng nhập chỉ được chứa chữ cái, số và dấu gạch dưới';
        } else {
            // Check duplicated username
            $existingUser = $this->userModel->getUserByUsername($username);
            if ($existingUser !== null) {
                $errors['username']['exists'] = 'Tên đăng nhập đã được sử dụng';
            }
        }

        // Validate Email
        if ($email === '') {
            $errors['email']['required'] = 'Email bắt buộc phải nhập';
        } elseif (!validateEmail($email)) {
            $errors['email']['invalid'] = 'Email không đúng định dạng';
        } else {
            // Check duplicated email
            $user = $this->userModel->getUserByEmail($email);
            if ($user !== null) {
                $errors['email']['exists'] = 'Email đã được sử dụng';
            }
        }

        // Validate password
        if ($password === '') {
            $errors['password']['required'] = 'Mật khẩu bắt buộc phải nhập';
        } elseif (strlen(trim($password)) < 6) {
            $errors['password']['length'] = 'Mật khẩu phải dài hơn 6 ký tự';
        }

        // Validate confirm password
        if (empty($input['confirm_pass'])) {
            $errors['confirm_pass']['required'] = 'Vui lòng nhập lại mật khẩu';
        } elseif (trim($confirmPass) !== trim($password)) {
            $errors['confirm_pass']['notMatch'] = 'Mật khẩu nhập lại không khớp';
        }

        // If validation failed
        if (!empty($errors)) {
            Session::Flash('msg', 'Vui lòng kiểm tra lại dữ liệu nhập vào');
            Session::Flash('msg_type', 'danger');

            $oldData = [
                'username' => $username,
                'email' => $email,
            ];

            Session::Flash('oldData', $oldData);
            Session::Flash('errors', $errors);
            redirect('/register');
        }

        // Generate activation token
        $verification_token = bin2hex(random_bytes(32));

        $insertData = [
            'username' => $username,
            'email' => $email,
            'avatar' => 'default-avatar.jpg',
            'password' => password_hash($input['password'], PASSWORD_DEFAULT),
            'verification_token' => $verification_token,
            'verification_token_expires' => date('Y-m-d H:i:s', strtotime('+10 minutes')),
            'created_at' => date('Y-m-d H:i:s'),
        ];

        $inserted = $this->userModel->createUser($insertData);

        if (!$inserted) {
            Session::flash('msg', 'Đăng ký không thành công, vui lòng thử lại sau.');
            Session::flash('msg_type', 'danger');
            redirect('/register');
        }

        // Send verification email
        $activeLink = BASE_URL . '/active?token=' . $verification_token;

        try {
            $this->mailService->sendVerificationEmail($email, $username, $activeLink);

            Session::Flash('msg', 'Đăng ký thành công! Vui lòng kiểm tra email để kích hoạt tài khoản.');
            Session::Flash('msg_type', 'success');
        } catch (Exception $e) {
            error_log("Failed to send verification email: " . $e->getMessage());

            Session::Flash('msg', 'Đăng ký thành công! Nhưng không thể gửi email kích hoạt. Vui lòng liên hệ admin.');
            Session::Flash('msg_type', 'warning');
        }

        redirect('/register');
    }

    /** Show activation page and verify token */
    public function active(): void
    {
        // Get token from URL query (?token=xxxx)
        $query = filterData('get');
        $token = trim($query['token'] ?? '');

        // Token missing or invalid format
        if ($token === '') {
            $this->view->render('admin/auth/active', 'auth', [
                'headerData' => ['title' => 'Kích hoạt tài khoản - DevBlog CMS'],
                'status' => 'invalid'
            ]);
            return;
        }

        // Validate token format - to prenvent DoS
        if (!preg_match('/^[0-9a-f]{64}$/i', $token)) {
            $this->view->render('admin/auth/active', 'auth', [
                'headerData' => ['title' => 'Kích hoạt tài khoản - DevBlog CMS'],
                'status' => 'invalid'
            ]);
            return;
        }

        // Find user by activation token
        $user = $this->userModel->getUserByActivationToken($token);

        // Token not found in db
        if (empty($user)) {
            $this->view->render('admin/auth/active', 'auth', [
                'headerData' => ['title' => 'Kích hoạt tài khoản - DevBlog CMS'],
                'status' => 'invalid'
            ]);
            return;
        }

        // Update user (by ID)
        $dataUpdate = [
            'email_verified_at' => date('Y-m-d H:i:s'),
            'verification_token' => null,
            'verification_token_expires' => null
        ];

        $updated = $this->userModel->updateUser((int)$user['id'], $dataUpdate);

        // Update failed
        if (!$updated) {
            $this->view->render('admin/auth/active', 'auth', [
                'headerData' => ['title' => 'Kích hoạt tài khoản - DevBlog CMS'],
                'status' => 'error'
            ]);
            return;
        }

        // Update success
        $this->view->render('admin/auth/active', 'auth', [
            'headerData' => ['title' => 'Kích hoạt tài khoản - DevBlog CMS'],
            'status' => 'success'
        ]);
    }

    /** Show forgot-password page */
    public function showForgot(): void
    {
        $data = ['headerData' => ['title' => 'Quên mật khẩu - DevBlog CMS']];
        $this->view->render('admin/auth/forgot', 'auth', $data);
    }

    /** Handle forgot-password POST */
    public function forgot(): void
    {
        if (!isPost()) {
            return;
        }

        // Normalize input
        $input = filterData();
        $email = trim($input['email'] ?? '');

        $errors = [];

        // Validate Email
        if ($email === '') {
            $errors['email']['required'] = 'Email bắt buộc phải nhập';
        } elseif (!validateEmail($email)) {
            $errors['email']['invalid'] = 'Email không đúng định dạng';
        }

        // If validation failed
        if (!empty($errors)) {
            Session::Flash('msg', 'Vui lòng kiểm tra lại dữ liệu nhập vào');
            Session::Flash('msg_type', 'danger');
            Session::Flash('oldData', ['email' => $email]);
            Session::Flash('errors', $errors);
            redirect('/forgot');
        }

        // Find user by email
        $user = $this->userModel->getUserByEmail($email);

        if ($user === null) {
            // Do not reveal whether email exists
            Session::Flash('msg', 'Nếu email tồn tại, chúng tôi đã gửi hướng dẫn đặt lại mật khẩu.');
            Session::Flash('msg_type', 'success');
            redirect('/forgot');
        }

        // Create reset token
        $resetToken = bin2hex(random_bytes(32));

        // Update forget_token into users table
        $updateData = [
            'reset_token' => $resetToken,
            'reset_token_expires' => date('Y-m-d H:i:s', strtotime('+5 minutes'))
        ];

        $updated = $this->userModel->updateUser((int)$user['id'], $updateData);

        if (!$updated) {
            Session::Flash('msg', 'Đã có lỗi xảy ra, vui lòng thử lại sau.');
            Session::Flash('msg_type', 'danger');
            redirect('/forgot');
        }

        // Send mail with reset link
        $resetLink = BASE_URL . '/reset?token=' . $resetToken;
        $sent = $this->mailService->sendPasswordResetEmail($email, $user['fullname'], $resetLink);

        Session::Flash('msg', 'Nếu email tồn tại, chúng tôi đã gửi hướng dẫn đặt lại mật khẩu.');
        Session::Flash('msg_type', 'success');

        redirect('/forgot');
    }

    /** Show reset-password page */
    public function showReset(): void
    {
        $data = ['headerData' => ['title' => 'Đặt lại mật khẩu - DevBlog CMS']];
        $this->view->render('admin/auth/reset', 'auth', $data);
    }

    /** Handle reset-password POST */
    public function reset(): void
    {
        // Get token from query string and normalize
        $query = filterData('get');
        $token = trim($query['token'] ?? '');

        // If no token
        if ($token === '') {
            Session::Flash('msg', 'Liên kết đã hết hạn hoặc không tồn tại');
            Session::Flash('msg_type', 'danger');
            redirect('/forgot');
        }

        // Token format check
        if (!preg_match('/^[0-9a-f]{64}$/i', $token)) {
            Session::Flash('msg', 'Liên kết không hợp lệ');
            Session::Flash('msg_type', 'danger');
            redirect('/forgot');
        }

        // Find user by forget_token
        $user = $this->userModel->getUserByForgetToken($token);

        if ($user === null) {
            Session::Flash('msg', 'Liên kết đã hết hạn hoặc không tồn tại');
            Session::Flash('msg_type', 'danger');
            redirect('/forgot');
        }

        // If form not submitted, just render reset page
        if (!isPost()) {
            $this->view->render('admin/auth/reset', 'auth', ['token' => $token]);
            return;
        }

        // Handle POST
        $input = filterData();
        $password = trim($input['password'] ?? '');
        $confirmPass  = trim($input['confirm_pass'] ?? '');

        $errors = [];

        // Validate password
        if ($password === '') {
            $errors['password']['required'] = 'Mật khẩu bắt buộc phải nhập';
        } elseif (strlen($password) < 6) {
            $errors['password']['length'] = 'Mật khẩu phải dài hơn 6 ký tự';
        }

        // Validate confirm password
        if ($confirmPass === '') {
            $errors['confirm_pass']['required'] = 'Vui lòng nhập lại mật khẩu';
        } elseif ($confirmPass !== $password) {
            $errors['confirm_pass']['notMatch'] = 'Mật khẩu nhập lại không khớp';
        }

        // If validation failed
        if (!empty($errors)) {
            Session::Flash('msg', 'Vui lòng kiểm tra lại dữ liệu nhập vào');
            Session::Flash('msg_type', 'danger');
            Session::Flash('oldData', ['token' => $token]);
            Session::Flash('errors', $errors);

            redirect('/reset?token=' . urlencode($token));
        }

        // Validation success
        $updateData = [
            'password'     => password_hash($password, PASSWORD_DEFAULT),
            'reset_token' => null,
            'updated_at'   => date('Y-m-d H:i:s'),
        ];

        $updated = $this->userModel->updateUser((int)$user['id'], $updateData);

        // Update failed
        if (!$updated) {
            Session::Flash('msg', 'Đã có lỗi xảy ra, vui lòng thử lại sau');
            Session::Flash('msg_type', 'danger');
            redirect('/reset?token=' . urlencode($token));
        }

        // Send confirmation mail
        $sendMail = $this->mailService->sendPasswordChangedEmail($user['email'], $user['fullname']);

        Session::Flash('msg', 'Cập nhật mật khẩu thành công.');
        Session::Flash('msg_type', 'success');

        redirect('/login');
    }

    /** Handle user logout */
    public function logout(): void
    {
        AuthMiddleware::logout();
        redirect('/login');
    }
}
