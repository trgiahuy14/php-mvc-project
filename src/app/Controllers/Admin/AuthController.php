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
    private Token $tokenModel;
    private MailService $mailService;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = new User();
        $this->tokenModel = new Token();
        $this->mailService = new MailService();
    }

    /** Render login page */
    public function showLogin(): void
    {
        $data = [
            'title' => 'Đăng nhập'
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
        } elseif (!validateEmail(trim($input['email']))) { {
                $errors['email']['isEmail'] = 'Email không đúng định dạng';
            }
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

        // Check if user is active
        if (!isset($user['is_active']) || $user['is_active'] === 0) {
            Session::Flash('msg', 'Tài khoản chưa được kích hoạt');
            Session::Flash('msg_type', 'danger');
            redirect('/login');
        }

        // Issue token & persist
        $token = bin2hex(random_bytes(32));
        $insertData = [
            'token' => $token,
            'created_at' => date('Y-m-d H:i:s'),
            'user_id' => $user['id'],
            'expires_at' =>  date('Y-m-d H:i:s', strtotime('+1 day')),
        ];

        $inserted = $this->tokenModel->createToken($insertData);

        // Insert failed
        if (!$inserted) {
            Session::flash('msg', 'Lỗi hệ thống, vui lòng thử lại sau!');
            Session::flash('msg_type', 'danger');
            redirect('/login');
        }

        // Success
        Session::set('token_login', $token);

        redirect('/dashboard');
    }

    /** Show register page */
    public function showRegister(): void
    {
        $data = ['title' => 'Đăng ký tài khoản'];
        $this->view->render('admin/auth/register', 'auth');
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
        $fullname   = trim($input['fullname'] ?? '');
        $email      = trim($input['email'] ?? '');
        $phone      = (string)($input['phone'] ?? '');
        $password   = (string)($input['password'] ?? '');
        $confirmPass = (string)($input['confirm_pass'] ?? '');

        // Validate fullname
        if ($fullname === '') {
            $errors['fullname']['required'] = 'Họ tên bắt buộc phải nhập';
        } elseif (strlen($fullname) < 5) {
            $errors['fullname']['min'] = 'Họ tên phải lớn hơn 5 ký tự';
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
                $errors['email']['exists'] = 'Email đã tồn tại';
            }
        }

        // Validate phone
        if ($phone === '') {
            $errors['phone']['required'] = 'Số điện thoại bắt buộc phải nhập';
        } elseif (!isPhone($phone)) {
            $errors['phone']['isPhone'] = 'Số điện thoại không đúng định dạng';
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
                'fullname' => $fullname,
                'email' => $email,
                'phone' => $phone
            ];

            Session::Flash('oldData', $oldData);
            Session::Flash('errors', $errors);
            redirect('/register');
        }


        // Generate activation token
        $activationToken = bin2hex(random_bytes(32));

        $insertData = [
            'fullname' => $fullname,
            'email' => $email,
            'phone' => $phone,
            'avatar' => "/public/assets/img/user-avt-default.jpg",
            'password' => password_hash($input['password'], PASSWORD_DEFAULT),
            'active_token' => $activationToken,
            'created_at' => date('Y-m-d H:i:s'),
        ];

        $inserted = $this->userModel->createUser($insertData);

        if (!$inserted) {
            Session::flash('msg', 'Đăng ký không thành công, vui lòng thử lại sau.');
            Session::flash('msg_type', 'danger');
            redirect('/register');
        }

        // Send verification email
        $activeLink = BASE_URL . '/active?token=' . $activationToken;

        try {
            $this->mailService->sendVerificationEmail($email, $fullname, $activeLink);

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
            $this->view->render('admin/auth/active', 'auth', ['status' => 'invalid']);
            return;
        }

        // Validate token format - to prenvent DoS
        if (!preg_match('/^[0-9a-f]{64}$/i', $token)) {
            $this->view->render('admin/auth/active', 'auth', ['status' => 'invalid']);
            return;
        }

        // Find user by activation token
        $user = $this->userModel->getUserByActivationToken($token);

        // Token not found in db
        if (empty($user)) {
            $this->view->render('admin/auth/active', 'auth', ['status' => 'invalid']);
            return;
        }

        // Update user (by ID)
        $dataUpdate = [
            'is_active' => 1,
            'active_token' => null,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $updated = $this->userModel->updateUser((int)$user['id'], $dataUpdate);

        // Update failed
        if (!$updated) {
            $this->view->render('admin/auth/active', 'auth', ['status' => 'error']);
            return;
        }

        // Update success
        $this->view->render('admin/auth/active', 'auth', ['status' => 'success']);
    }

    /** Show forgot-password page */
    public function showForgot(): void
    {
        $this->view->render('admin/auth/forgot', 'auth');
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
            'forget_token' => $resetToken,
            'updated_at' => date('Y-m-d H:i:s'),
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
        $this->view->render('admin/auth/reset', 'auth');
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
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        $updateData = [
            'password'     => $hashed,
            'forget_token' => null,
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
        if (AuthMiddleware::isAuthenticated()) {
            AuthMiddleware::logout();

            Session::Flash('msg', 'Đăng xuất thành công');
            Session::Flash('msg_type', 'success');

            // Redirect to login page
            redirect('/login');
        } else {
            redirect('/login');
        }
    }
}
