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
}
