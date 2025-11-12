<?php

class AuthController extends BaseController
{
    private $userModel;

    // Xử lý đăng nhập
    public function __construct()
    {
        $this->userModel = new Users();
    }

    /** Render login page */
    public function showLogin()
    {
        $this->renderView('layouts-part/login');
    }

    /** Handle login POST */
    public function login()
    {
        if (!isPost()) {
            return;
        }

        $input = filterData();
        $errors = [];

        // --- Validate Email ---
        $emailInput = trim($input['email'] ?? '');
        if ($emailInput === '') {
            $errors['email']['required'] = 'Email bắt buộc phải nhập';
        } elseif (!validateEmail(trim($input['email']))) { {
                $errors['email']['isEmail'] = 'Email không đúng định dạng';
            }
        }

        // --- Validate password ---
        $passwordInput = (string)($input['password'] ?? '');
        if ($passwordInput === '') {
            $errors['password']['required'] = 'Mật khẩu bắt buộc phải nhập';
        } elseif (strlen(trim($passwordInput)) < 6) {
            $errors['password']['length'] = 'Mật khẩu phải dài hơn 6 ký tự';
        }

        if (!empty($errors)) {
            setSessionFlash('msg', 'Vui lòng kiểm tra lại dữ liệu nhập vào');
            setSessionFlash('msg_type', 'danger');
            setSessionFlash('oldData', $input);
            setSessionFlash('errors', $errors);
            redirect('/login');
        }


        // --- Query user by email ---
        $user = $this->userModel->getOneUser("email = '$emailInput'");
        if (empty($user)) {
            setSessionFlash('msg', 'Email không tồn tại');
            setSessionFlash('msg_type', 'danger');
            redirect('/login');
        }

        // --- Verify password ---
        $isPasswordValid = password_verify($passwordInput, $user['password']);
        if (!$isPasswordValid) {
            setSessionFlash('msg', 'Mật khẩu hoặc email không chính xác');
            setSessionFlash('msg_type', 'danger');
            redirect('/login');
        }

        // --- Prevent multi-login for the same user ---
        $countRow = $this->userModel->getOneToken("id=" . $user['id']);
        echo $countRow;

        if ($countRow > 0) {
            setSessionFlash('msg', 'Tài khoản đang được đăng nhập ở một nơi khác');
            setSessionFlash('msg_type', 'danger');
            redirect('/login');
        }

        // --- Issue token & persist ---
        $token = bin2hex(random_bytes(32));
        $insertData = [
            'token' => $token,
            'created_at' => date('Y-m-d H:i:s'),
            'user_id' => $user['id'],
        ];

        $inserted = $this->userModel->insertTokenLogin($insertData);

        if (!$inserted) {
            setSessionFlash('msg', 'Lỗi hệ thống, vui lòng thử lại sau!');
            setSessionFlash('msg_type', 'danger');
            redirect('/login');
        }

        setSession('token_login', $token);

        // --- Redirect to dashboard ---
        redirect('/dashboard');
    }

    public function showRegister()
    {
        $this->renderView('layouts-part/register');
    }

    public function register()
    {
        // Validate
        if (!empty(isPost())) {
            $filter = filterData();
            $errors = [];

            // Validate Fullname
            if (empty(trim($filter['fullname']))) {
                $errors['fullname']['required'] = 'Họ tên bắt buộc phải nhập';
            } else {
                if (strlen(trim($filter['fullname'])) < 5) {
                    $errors['fullname']['length'] = 'Họ tên phải lớn hơn 5 ký tự';
                }
            }

            // Validate Email
            if (empty(trim($filter['email']))) {
                $errors['email']['required'] = 'Email bắt buộc phải nhập';
            } else {
                if (!validateEmail(trim($filter['email']))) {
                    $errors['email']['isEmail'] = 'Email không đúng định dạng';
                } else {
                    $email = $filter['email'];
                    $checkEmail = $this->coreModel->getRows("SELECT * FROM users WHERE email = '$email'");
                    if ($checkEmail > 0) {
                        $errors['email']['check'] = 'Email đã tồn tại';
                    }
                }
            }

            // Validate phone
            if (empty($filter['phone'])) {
                $errors['phone']['required'] = 'Số điện thoại bắt buộc phải nhập';
            } else {
                if (!isPhone($filter['phone'])) {
                    $errors['phone']['isPhone'] = 'Số điện thoại không đúng định dạng';
                }
            }

            // Validate password
            if (empty($filter['password'])) {
                $errors['password']['required'] = 'Mật khẩu bắt buộc phải nhập';
            } else {
                if (strlen(trim($filter['password'])) < 6) {
                    $errors['password']['length'] = 'Mật khẩu phải dài hơn 6 ký tự';
                }
            }

            // Validate confirm password
            if (empty($filter['confirm_pass'])) {
                $errors['confirm_pass']['required'] = 'Vui lòng nhập lại mật khẩu';
            } else {
                if (trim($filter['confirm_pass']) !== trim($filter['password'])) {
                    $errors['confirm_pass']['like'] = 'Mật khẩu nhập lại không khớp';
                }
            }

            if (empty($errors)) {
                $msg = 'Đăng ký thành công';
                $msg_type = 'success';

                $activeToken = sha1(uniqid() . time()); // Tạo mã cho token

                $data = [
                    'fullname' => $filter['fullname'],
                    'email' => $filter['email'],
                    'phone' => $filter['phone'],
                    'avatar' => "/public/assets/img/user-avt-default.jpg",
                    'password' => password_hash($filter['password'], PASSWORD_DEFAULT),
                    'active_token' => $activeToken,
                    'created_at' => date('Y:m:d H:i:s')
                ];

                $insertStatus = $this->coreModel->insert('users', $data);

                // For debug
                // try {
                //   $insertStatus = insert('users', $data);
                // } catch (Throwable $e) {
                //   echo '<pre>SQL ERROR: ' . $e->getMessage() . '</pre>';
                //   // nếu cần: echo $e->getTraceAsString();
                //   exit;
                // }


                if ($insertStatus) {

                    // Send active email
                    $emailTo = $filter['email'];
                    $subject = 'Kích hoạt tài khoản – Courses Manager';
                    $activeLink = BASE_URL . '/active?token=' . $activeToken;

                    $content = '<div style="font-family: Arial, sans-serif; background:#f6f9fc; padding:24px;">';
                    $content .= '  <div style="max-width:600px; margin:auto; background:#ffffff; border-radius:10px; ';
                    $content .= '              padding:28px; box-shadow:0 2px 8px rgba(0,0,0,0.05); border:1px solid #e5e7eb;">';

                    $content .= '    <h2 style="text-align:center; color:#2563eb; margin-bottom:20px;">Kích hoạt tài khoản Courses Manager</h2>';
                    $content .= '    <p style="color:#374151;">Xin chào <b>' . $filter['fullname'] . '</b>,</p>';
                    $content .= '    <p style="color:#374151;">Cảm ơn bạn đã đăng ký tài khoản trên hệ thống <b>Courses Manager</b>.</p>';
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

                    sendMail($emailTo, $subject, $content);

                    setSessionFlash('msg', 'Đăng ký thành công, vui lòng kiểm tra email để kích hoạt tài khoản.');
                    setSessionFlash('msg_type', 'success');
                } else {
                    setSessionFlash('msg', 'Đăng ký không thành công, vui lòng thử lại sau.');
                    setSessionFlash('msg_type', 'danger');
                }
            } else {
                setSessionFlash('msg', 'Vui lòng kiểm tra lại dữ liệu nhập vào');
                setSessionFlash('msg_type', 'danger');

                setSessionFlash('oldData', $filter);
                setSessionFlash('errors', $errors);
            }
            redirect('/register');
        }
    }

    public function active()
    {
        $data = ['coreModel' => $this->coreModel];
        $this->renderView('layouts-part/active', $data);
    }

    public function showForgot()
    {
        $this->renderView('layouts-part/forgot');
    }

    public function forgot()
    {
        if (isPost()) {
            $filter = filterData();
            $errors = [];
            // Validate Email
            if (empty(trim($filter['email']))) {
                $errors['email']['required'] = 'Email bắt buộc phải nhập';
            } else {
                if (!validateEmail(trim($filter['email']))) {
                    $errors['email']['isEmail'] = 'Email không đúng định dạng';
                }
            }

            if (empty($errors)) {
                // Xử lý và gửi mail
                if (!empty($filter['email'])) {
                    $email = $filter['email'];

                    $checkEmail = $this->coreModel->getOne("SELECT * FROM users WHERE email ='$email'");
                    if (!empty($checkEmail)) {
                        // Update forget_token into user table
                        $forget_token = sha1(uniqid() . time());
                        $data = [
                            'forget_token' => $forget_token
                        ];
                        $condition = "id=" . $checkEmail['id'];
                        $updateStatus = $this->coreModel->update('users', $data, $condition);
                        if ($updateStatus) {
                            // Send forgot mail
                            $emailTo = $email;
                            $subject = 'Yêu cầu đặt lại mật khẩu';
                            $resetLink = BASE_URL . '/reset?token=' . $forget_token;

                            $content = '<div style="font-family: Arial, sans-serif; background:#f6f9fc; padding:24px;">';
                            $content .= '  <div style="max-width:600px; margin:auto; background:#ffffff; border-radius:10px;';
                            $content .= '              padding:28px; box-shadow:0 2px 8px rgba(0,0,0,0.05); border:1px solid #e5e7eb;">';

                            $content .= '    <h2 style="text-align:center; color:#2563eb; margin-bottom:20px;">Yêu cầu đặt lại mật khẩu</h2>';
                            $content .= '    <p style="color:#374151;">Xin chào <b>' . $checkEmail['fullname'] . '</b>,</p>';
                            $content .= '    <p style="color:#374151;">Bạn vừa gửi yêu cầu đặt lại mật khẩu cho tài khoản trên hệ thống <b>Courses Manager</b>.</p>';
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


                            sendMail($emailTo, $subject, $content);

                            setSessionFlash('msg', 'Gửi yêu cầu thành công, vui lòng kiểm tra email.');
                            setSessionFlash('msg_type', 'success');
                        } else {
                            setSessionFlash('msg', 'Đã có lỗi xảy ra, vui lòng thử lạo sau.');
                            setSessionFlash('msg_type', 'danger');
                        }
                    }
                }
            } else {
                setSessionFlash('msg', 'Vui lòng kiểm tra lại dữ liệu nhập vào');
                setSessionFlash('msg_type', 'danger');
                setSessionFlash('oldData', $filter);
                setSessionFlash('errors', $errors);
            }
            redirect("/forgot");
        }
    }

    public function showReset()
    {
        $this->renderView('layouts-part/reset');
    }


    public function reset()
    {
        $filterGet = filterData('get');
        if (!empty($filterGet['token'])) {
            $tokenReset = $filterGet['token'];
        }
        if (!empty($tokenReset)) {
            // Check token
            $checkToken = $this->coreModel->getOne("SELECT * FROM users WHERE forget_token = '$tokenReset'");
            if (!empty($checkToken)) {
                if (isPost()) {
                    $filter = filterData();
                    $errors = [];

                    // Validate password
                    if (empty($filter['password'])) {
                        $errors['password']['required'] = 'Mật khẩu bắt buộc phải nhập';
                    } else {
                        if (strlen(trim($filter['password'])) < 6) {
                            $errors['password']['length'] = 'Mật khẩu phải dài hơn 6 ký tự';
                        }
                    }

                    // Validate confirm password
                    if (empty($filter['confirm_pass'])) {
                        $errors['confirm_pass']['required'] = 'Vui lòng nhập lại mật khẩu';
                    } else {
                        if (trim($filter['confirm_pass']) !== trim($filter['password'])) {
                            $errors['confirm_pass']['like'] = 'Mật khẩu nhập lại không khớp';
                        }
                    }

                    if (empty($errors)) {
                        $password = password_hash($filter['password'], PASSWORD_DEFAULT);
                        $dataUpdate = [
                            'password' => $password,
                            'forget_token' => null,
                            'updated_at' => date('Y-m-d H:i:s')
                        ];

                        $condition = "id=" . $checkToken['id'];

                        $updateStatus = $this->coreModel->update('users', $dataUpdate, $condition);

                        if ($updateStatus) {
                            // Send mail
                            $emailTo = $checkToken['email'];
                            $subject = 'Mật khẩu của bạn đã được thay đổi';

                            $content = '<div style="font-family: Arial, sans-serif; background:#f6f9fc; padding:24px;">';
                            $content .= '  <div style="max-width:600px; margin:auto; background:#ffffff; border-radius:10px;';
                            $content .= '              padding:28px; box-shadow:0 2px 8px rgba(0,0,0,0.05); border:1px solid #e5e7eb;">';

                            $content .= '    <h2 style="text-align:center; color:#2563eb !important; margin-bottom:20px;">Đổi mật khẩu thành công</h2>';

                            $content .= '    <p style="color:#374151 !important; margin:0 0 12px;">';
                            $content .= '      Xin chào <span style="color:#374151 !important;"><b>' . $checkToken['fullname'] . '</b></span>,';
                            $content .= '    </p>';

                            $content .= '    <p style="color:#374151 !important; margin:0 0 12px;">';
                            $content .= '      Mật khẩu tài khoản của bạn trên hệ thống <span style="color:#374151 !important;"><b>Courses Manager</b></span> đã được thay đổi thành công.';
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

                            $content .= '    <p style="color:#374151 !important; margin:0 0 12px;">Cảm ơn bạn đã sử dụng hệ thống <span style="color:#374151 !important;"><b>Courses Manager</b></span>.</p>';
                            $content .= '    <br>';
                            $content .= '    <p style="color:#374151 !important; margin:0 0 4px;">Trân trọng,</p>';
                            $content .= '    <p style="color:#374151 !important; margin:0;"><b>Đội ngũ VietNews</b></p>';

                            $content .= '  </div>';

                            $content .= '  <div style="text-align:center; color:#6b7280 !important; font-size:12px; margin-top:18px;">';
                            $content .= '    <p style="margin:0; color:#6b7280 !important;">Email này được gửi tự động, vui lòng không trả lời.</p>';
                            $content .= '  </div>';
                            $content .= '</div>';


                            // Gửi mail
                            sendMail($emailTo, $subject, $content);

                            setSessionFlash('msg', 'Cập nhật mật khẩu thành công.');
                            setSessionFlash('msg_type', 'success');
                        } else {
                            setSessionFlash('msg', 'Đã có lỗi xảy ra, vui lòng thử lại sau');
                            setSessionFlash('msg_type', 'danger');
                        }
                    } else {
                        setSessionFlash('msg', 'Vui lòng kiểm tra lại dữ liệu nhập vào');
                        setSessionFlash('msg_type', 'danger');
                        setSessionFlash('oldData', $filter);
                        setSessionFlash('errors', $errors);
                    }
                }
            } else {
                getMsg('Liên kết đã hết hạn hoặc không tồn tại', 'danger');
            }
        } else {
            getMsg('Liên kết đã hết hạn hoặc không tồn tại', 'danger');
        }
        redirect('/reset?token=' . $tokenReset);
    }

    public function logout(): void
    {
        $token = getSession('token_login');

        if ($token !== false) {
            $this->coreModel->delete('token_login', "token = '$token'");
        }

        removeSession('token_login');

        redirect('/login');
    }
}

// Need: add invalid page if token doesn't exist