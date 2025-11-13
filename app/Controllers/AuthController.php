<?php

final class AuthController extends BaseController
{
    private UserModel $userModel;
    private TokenModel $tokenModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->tokenModel = new TokenModel();
    }

    /** Render login page */
    public function showLogin(): void
    {
        $this->renderView('layouts-part/login');
    }

    /** Handle login POST */
    public function login(): void
    {
        if (!isPost()) {
            return;
        }

        $input = filterData();
        $errors = [];

        // Normalize input
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
            setSessionFlash('msg', 'Vui lòng kiểm tra lại dữ liệu nhập vào');
            setSessionFlash('msg_type', 'danger');
            setSessionFlash('oldData', ['email' => $email]);
            setSessionFlash('errors', $errors);
            redirect('/login');
        }

        // Query user by email
        $user = $this->userModel->getUserByEmail($email);
        if (empty($user)) {
            setSessionFlash('msg', 'Email không tồn tại');
            setSessionFlash('msg_type', 'danger');
            redirect('/login');
        }

        // Verify password
        $isPasswordValid = password_verify($password, $user['password']);
        if (!$isPasswordValid) {
            setSessionFlash('msg', 'Mật khẩu hoặc email không chính xác');
            setSessionFlash('msg_type', 'danger');
            redirect('/login');
        }

        // Prevent multi-login for the same user
        $countRow = $this->tokenModel->countByUserId((int)$user['id']);

        if ($countRow > 0) {
            setSessionFlash('msg', 'Tài khoản đang được đăng nhập ở một nơi khác');
            setSessionFlash('msg_type', 'danger');
            redirect('/login');
        }

        // Issue token & persist
        $token = bin2hex(random_bytes(32));
        $insertData = [
            'token' => $token,
            'created_at' => date('Y-m-d H:i:s'),
            'user_id' => $user['id'],
        ];

        $inserted = $this->tokenModel->createToken($insertData);

        if (!$inserted) {
            setSessionFlash('msg', 'Lỗi hệ thống, vui lòng thử lại sau!');
            setSessionFlash('msg_type', 'danger');
            redirect('/login');
        }

        setSession('token_login', $token);

        // Redirect to dashboard
        redirect('/dashboard');
    }

    /** Show register page */
    public function showRegister(): void
    {
        $this->renderView('layouts-part/register');
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
            setSessionFlash('msg', 'Vui lòng kiểm tra lại dữ liệu nhập vào');
            setSessionFlash('msg_type', 'danger');

            $oldData = ['fullname' => $fullname, 'email' => $email, 'phone' => $phone];
            setSessionFlash('oldData', $oldData);
            setSessionFlash('errors', $errors);
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
            'created_at' => date('Y-m-d H:i:s')
        ];

        $inserted = $this->userModel->createUser($insertData);

        if (!$inserted) {
            setSessionFlash('msg', 'Đăng ký không thành công, vui lòng thử lại sau.');
            setSessionFlash('msg_type', 'danger');
            redirect('/register');
        }

        // Prepare active mail
        $activeLink = BASE_URL . '/active?token=' . $activationToken;
        $subject = 'Kích hoạt tài khoản';

        // Mail content
        $content = '<div style="font-family: Arial, sans-serif; background:#f6f9fc; padding:24px;">';
        $content .= '  <div style="max-width:600px; margin:auto; background:#ffffff; border-radius:10px; ';
        $content .= '              padding:28px; box-shadow:0 2px 8px rgba(0,0,0,0.05); border:1px solid #e5e7eb;">';

        $content .= '    <h2 style="text-align:center; color:#2563eb; margin-bottom:20px;">Kích hoạt tài khoản VietNews</h2>';
        $content .= '    <p style="color:#374151;">Xin chào <b>' . $fullname . '</b>,</p>';
        $content .= '    <p style="color:#374151;">Cảm ơn bạn đã đăng ký tài khoản trên hệ thống <b>VietNews</b>.</p>';
        $content .= '    <p style="color:#374151;">Để hoàn tất việc đăng ký, vui lòng nhấn vào nút bên dưới để kích hoạt tài khoản của bạn:</p>';

        $content .= '    <div style="text-align:center; margin:30px 0;">';
        $content .= '      <a href="' . $activeLink . '" style="background-color:#2563eb; color:#fff; text-decoration:none; ';
        $content .= '         padding:12px 24px; border-radius:8px; font-weight:bold; display:inline-block;">Kích hoạt tài khoản</a>';
        $content .= '    </div>';

        $content .= '    <p style="color:#374151;">Nếu bạn không thực hiện đăng ký này, vui lòng bỏ qua email này. ';
        $content .= 'Liên kết sẽ tự động hết hạn sau một khoảng thời gian ngắn để đảm bảo an toàn.</p>';

        $content .= '    <br><p>Trân trọng,</p>';
        $content .= '    <p><b>Đội ngũ VietNews</b></p>';
        $content .= '  </div>';

        $content .= '  <div style="text-align:center; color:#6b7280; font-size:12px; margin-top:18px;">';
        $content .= '    <p style="margin:0;">Email này được gửi tự động, vui lòng không trả lời.</p>';
        $content .= '  </div>';
        $content .= '</div>';

        // Send mail
        sendMail($email, $subject, $content);

        setSessionFlash('msg', 'Đăng ký thành công, vui lòng kiểm tra email để kích hoạt tài khoản.');
        setSessionFlash('msg_type', 'success');

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
            $this->renderView('layouts-part/active', ['status' => 'invalid']);
            return;
        }

        // Validate token format - to prenvent DoS
        if (!preg_match('/^[0-9a-f]{64}$/i', $token)) {
            $this->renderView('layouts-part/active', ['status' => 'invalid']);
            return;
        }

        // Find user by activation token
        $user = $this->userModel->getUserByActivationToken($token);

        // Token not found in db
        if (empty($user)) {
            $this->renderView('layouts-part/active', ['status' => 'invalid']);
            return;
        }

        // Update user (by ID)
        $dataUpdate = [
            'status' => 1,
            'active_token' => null,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $updated = $this->userModel->updateUser((int)$user['id'], $dataUpdate);

        // Update failed
        if (!$updated) {
            $this->renderView('layouts-part/active', ['status' => 'error']);
            return;
        }

        // Update success
        $this->renderView('layouts-part/active', ['status' => 'success']);
    }

    /** Show forgot-password page */
    public function showForgot(): void
    {
        $this->renderView('layouts-part/forgot');
    }

    /** Handle forgot-password POST */
    public function forgot(): void
    {
        if (isPost()) {
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
            setSessionFlash('msg', 'Vui lòng kiểm tra lại dữ liệu nhập vào');
            setSessionFlash('msg_type', 'danger');
            setSessionFlash('oldData', ['email' => $email]);
            setSessionFlash('errors', $errors);
            redirect('/forgot');
        }

        // Find user by email
        $user = $this->userModel->getUserByEmail($email);

        if ($user === null) {
            // Do not reveal whether email exists
            setSessionFlash('msg', 'Nếu email tồn tại, chúng tôi đã gửi hướng dẫn đặt lại mật khẩu.');
            setSessionFlash('msg_type', 'success');
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
            setSessionFlash('msg', 'Đã có lỗi xảy ra, vui lòng thử lại sau.');
            setSessionFlash('msg_type', 'danger');
            redirect('/forgot');
        }

        // Prepare reset mail
        $resetLink = BASE_URL . '/reset?token=' . $resetToken;
        $subject = 'Yêu cầu đặt lại mật khẩu';

        // Mail content
        $content = '<div style="font-family: Arial, sans-serif; background:#f6f9fc; padding:24px;">';
        $content .= '  <div style="max-width:600px; margin:auto; background:#ffffff; border-radius:10px;';
        $content .= '              padding:28px; box-shadow:0 2px 8px rgba(0,0,0,0.05); border:1px solid #e5e7eb;">';

        $content .= '    <h2 style="text-align:center; color:#2563eb; margin-bottom:20px;">Yêu cầu đặt lại mật khẩu</h2>';
        $content .= '    <p style="color:#374151;">Xin chào <b>' . $user['fullname'] . '</b>,</p>';
        $content .= '    <p style="color:#374151;">Bạn vừa gửi yêu cầu đặt lại mật khẩu cho tài khoản trên hệ thống <b>VietNews</b>.</p>';
        $content .= '    <p style="color:#374151;">Để đặt lại mật khẩu, vui lòng nhấn vào nút bên dưới:</p>';

        $content .= '    <div style="text-align:center; margin:30px 0;">';
        $content .= '      <a href="' . $resetLink . '" style="background-color:#2563eb; color:#fff; text-decoration:none;';
        $content .= '         padding:12px 24px; border-radius:8px; font-weight:bold; display:inline-block;">Đặt lại mật khẩu</a>';
        $content .= '    </div>';

        $content .= '    <p style="color:#374151;">Nếu bạn không yêu cầu thay đổi mật khẩu, hãy bỏ qua email này.';
        $content .= ' Liên kết sẽ tự động hết hạn sau một khoảng thời gian ngắn để đảm bảo an toàn.</p>';

        $content .= '    <br><p>Trân trọng,</p>';
        $content .= '    <p><b>Đội ngũ VietNews</b></p>';
        $content .= '  </div>';

        $content .= '  <div style="text-align:center; color:#6b7280; font-size:12px; margin-top:18px;">';
        $content .= '    <p style="margin:0;">Email này được gửi tự động, vui lòng không trả lời.</p>';
        $content .= '  </div>';
        $content .= '</div>';

        // Send mail
        sendMail($email, $subject, $content);

        setSessionFlash('msg', 'Nếu email tồn tại, chúng tôi đã gửi hướng dẫn đặt lại mật khẩu.');
        setSessionFlash('msg_type', 'success');

        redirect('/forgot');
    }

    /** Show reset-password page */
    public function showReset(): void
    {
        $this->renderView('layouts-part/reset');
    }

    /** Handle reset-password POST */
    public function reset(): void
    {
        // Get token from query string and normalize
        $query = filterData('get');
        $token = trim($query['token'] ?? '');

        // If no token
        if ($token === '') {
            setSessionFlash('msg', 'Liên kết đã hết hạn hoặc không tồn tại');
            setSessionFlash('msg_type', 'danger');
            redirect('/forgot');
        }

        // Token format check
        if (!preg_match('/^[0-9a-f]{64}$/i', $token)) {
            setSessionFlash('msg', 'Liên kết không hợp lệ');
            setSessionFlash('msg_type', 'danger');
            redirect('/forgot');
        }

        // Find user by forget_token
        $user = $this->userModel->getUserByForgetToken($token);

        if ($user === null) {
            setSessionFlash('msg', 'Liên kết đã hết hạn hoặc không tồn tại');
            setSessionFlash('msg_type', 'danger');
            redirect('/forgot');
        }

        // If form not submitted, just render reset page
        if (!isPost()) {
            $this->renderView('layouts-part/reset', ['token' => $token]);
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
            setSessionFlash('msg', 'Vui lòng kiểm tra lại dữ liệu nhập vào');
            setSessionFlash('msg_type', 'danger');
            setSessionFlash('oldData', ['token' => $token]);
            setSessionFlash('errors', $errors);

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
            setSessionFlash('msg', 'Đã có lỗi xảy ra, vui lòng thử lại sau');
            setSessionFlash('msg_type', 'danger');
            redirect('/reset?token=' . urlencode($token));
        }

        // Prepare notification mail 
        $emailTo = $user['email'];
        $subject = 'Mật khẩu của bạn đã được thay đổi';

        // Mail content
        $content = '<div style="font-family: Arial, sans-serif; background:#f6f9fc; padding:24px;">';
        $content .= '  <div style="max-width:600px; margin:auto; background:#ffffff; border-radius:10px;';
        $content .= '              padding:28px; box-shadow:0 2px 8px rgba(0,0,0,0.05); border:1px solid #e5e7eb;">';

        $content .= '    <h2 style="text-align:center; color:#2563eb !important; margin-bottom:20px;">Đổi mật khẩu thành công</h2>';

        $content .= '    <p style="color:#374151 !important; margin:0 0 12px;">';
        $content .= '      Xin chào <span style="color:#374151 !important;"><b>' . $user['fullname'] . '</b></span>,';
        $content .= '    </p>';

        $content .= '    <p style="color:#374151 !important; margin:0 0 12px;">';
        $content .= '      Mật khẩu tài khoản của bạn trên hệ thống <span style="color:#374151 !important;"><b>VietNews</b></span> đã được thay đổi thành công.';
        $content .= '    </p>';

        $content .= '    <p style="color:#374151 !important; margin:0 0 24px;">';
        $content .= '      Nếu bạn không thực hiện thay đổi này, vui lòng đặt lại mật khẩu ngay bằng cách chọn “Quên mật khẩu” tại trang đăng nhập để đảm bảo an toàn tài khoản.';
        $content .= '    </p>';

        # Bulletproof button (ngăn việc client đổi màu)
        $content .= '    <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" style="margin:30px auto;">';
        $content .= '      <tr>';
        $content .= '        <td bgcolor="#2563eb" style="border-radius:8px;">';
        $content .= '          <a href="' . BASE_URL . '/login"';
        $content .= '             style="display:inline-block; padding:12px 24px; border-radius:8px; ';
        $content .= '                    background:#2563eb !important; border:1px solid #2563eb !important; ';
        $content .= '                    color:#ffffff !important; text-decoration:none !important; font-weight:700;">';
        $content .= '            <span style="color:#ffffff !important; text-decoration:none !important;">Đăng nhập ngay</span>';
        $content .= '          </a>';
        $content .= '        </td>';
        $content .= '      </tr>';
        $content .= '    </table>';

        $content .= '    <p style="color:#374151 !important; margin:0 0 12px;">Cảm ơn bạn đã sử dụng hệ thống <span style="color:#374151 !important;"><b>VietNews</b></span>.</p>';
        $content .= '    <br>';
        $content .= '    <p style="color:#374151 !important; margin:0 0 4px;">Trân trọng,</p>';
        $content .= '    <p style="color:#374151 !important; margin:0;"><b>Đội ngũ VietNews</b></p>';

        $content .= '  </div>';

        $content .= '  <div style="text-align:center; color:#6b7280 !important; font-size:12px; margin-top:18px;">';
        $content .= '    <p style="margin:0; color:#6b7280 !important;">Email này được gửi tự động, vui lòng không trả lời.</p>';
        $content .= '  </div>';
        $content .= '</div>';

        // Send mail
        sendMail($user['email'], $subject, $content);

        setSessionFlash('msg', 'Cập nhật mật khẩu thành công.');
        setSessionFlash('msg_type', 'success');

        redirect('/login');
    }

    /** Handle user logout */
    public function logout(): void
    {
        $token = getSession('token_login');

        // Remove token record in database
        if ($token !== false) {
            $this->tokenModel->deleteByToken($token);
        }

        // Clear session token
        removeSession('token_login');

        // Redirect to login page
        redirect('/login');
    }
}
