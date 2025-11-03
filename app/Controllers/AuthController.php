<?php

class AuthController extends BaseController
{
    private $coreModel;
    // Xử lý đăng nhập
    public function __construct()
    {
        $this->coreModel = new CoreModel();
    }

    public function showLogin()
    {
        $this->renderView('layouts-part/login');
    }
    public function login()
    {
        if (isPost()) {
            $filter = filterData();
            $errors = [];
            // Validate Email
            if (empty(trim($filter['email']))) {
                $errors['email']['required'] = 'Email bắt buộc phải nhập';
            } else {
                if (! validateEmail(trim($filter['email']))) {
                    $errors['email']['isEmail'] = 'Email không đúng định dạng';
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

            if (empty($errors)) {
                // Kiểm tra dữ liệu
                $email = $filter['email'];
                $password = $filter['password'];

                // Kiểm tra email
                $checkEmail = $this->coreModel->getOne("SELECT * FROM users WHERE email = '$email' ");

                if (!empty($checkEmail)) {
                    if (!empty($password)) {
                        $checkStatus = password_verify($password, $checkEmail['password']);
                        if ($checkStatus) {
                            // Prevent multiple login
                            $user_id = $checkEmail['id'];
                            $checkAlready = $this->coreModel->getRows("SELECT * FROM token_login WHERE user_id = '$user_id'");
                            if ($checkAlready > 0) {
                                setSessionFlash('msg', 'Tài khoản đang được đăng nhập ở một nơi khác');
                                setSessionFlash('msg_type', 'danger');
                                redirect('?module=auth&action=login');
                            } else {
                                // Tạo token và insert vào table token_login
                                $token = sha1(uniqid() . time());

                                // Gán token lên session
                                setSession('token_login', $token);
                                $data = [
                                    'token' => $token,
                                    'created_at' => date('Y:m:d H:i:s'),
                                    'user_id' => $checkEmail['id'],
                                ];
                                $insertToken = $this->coreModel->insert('token_login', $data);
                                if ($insertToken) {

                                    // Điều hướng
                                    redirect('/dashboard');
                                } else {
                                    setSessionFlash('msg', 'Lỗi hệ thống, vui lòng thử lại sau!');
                                    setSessionFlash('msg_type', 'danger');
                                }
                            }
                        } else {
                            setSessionFlash('msg', 'Mật khẩu hoặc email không chính xác.');
                            setSessionFlash('msg_type', 'danger');
                        }
                    }
                } else {
                    setSessionFlash('msg', 'Email không tồn tại');
                    setSessionFlash('msg_type', 'danger');
                }
            } else {
                setSessionFlash('msg', 'Vui lòng kiểm tra lại dữ liệu nhập vào');
                setSessionFlash('msg_type', 'danger');
                setSessionFlash('oldData', $filter);
                setSessionFlash('errors', $errors);
            }
            redirect('/login');
        }
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
                if (! validateEmail(trim($filter['email']))) {
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
                if (! isPhone($filter['phone'])) {
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
                    'fullname'      => $filter['fullname'],
                    'email'         => $filter['email'],
                    'phone'         => $filter['phone'],
                    'password'      => password_hash($filter['password'], PASSWORD_DEFAULT),
                    'active_token'  => $activeToken,
                    'created_at'    => date('Y:m:d H:i:s')
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
                    $activeLink = _HOST_URL . '/?module=auth&action=active&token=' . $activeToken;

                    $content  = '<div style="font-family: Arial, sans-serif; background:#f6f9fc; padding:24px;">';
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
                    $content .= '    <p><b>Đội ngũ Courses Manager</b></p>';
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
}
