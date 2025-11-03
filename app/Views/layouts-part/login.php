<?php
if (!defined('_TRGIAHUY')) {
    die('Truy cập không hợp lệ');
}
$data = ['title' => 'Đăng nhập hệ thống'];
layout('header-auth', $data);

$msg = getSessionFlash('msg');
$msg_type = getSessionFlash('msg_type');
$oldData = getSessionFlash('oldData');
$errorsArr = getSessionFlash('errors');
?>

<section class="vh-100">
    <div class="container-fluid h-custom">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-md-9 col-lg-6 col-xl-5">
                <img src="<?php echo _HOST_URL_PUBLIC; ?>/assets/image/login-page.jpg"
                    class="img-fluid" alt="Sample image">
            </div>
            <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
                <?php
                if (!empty($msg) && !empty($msg_type)) {
                    getMsg($msg, $msg_type);
                }
                ?>
                <form method="POST" action="" enctype="multipart/form-data">
                    <div class="d-flex flex-row align-items-center justify-content-center justify-content-lg-start">
                        <h2 class="fw-normal mb-5 me-3">Đăng nhập hệ thống</h2>

                    </div>

                    <!-- Email input -->
                    <div data-mdb-input-init class="form-outline mb-4">
                        <input type="text" name="email" id="form3Example3"
                            value="<?php
                                    if (!empty($oldData)) {
                                        echo oldData($oldData, 'email');
                                    }  ?>"
                            class="form-control form-control-lg"
                            placeholder="Địa chỉ email" />
                        <?php if (!empty($errorsArr)) {
                            echo formError($errorsArr, 'email');
                        } ?>
                    </div>

                    <!-- Password input -->
                    <div data-mdb-input-init class="form-outline mb-3">
                        <input type="password" name="password" id="form3Example4" class="form-control form-control-lg"
                            placeholder="Nhập mật khẩu" />
                        <?php
                        if (!empty($errorsArr)) {
                            echo formError($errorsArr, 'password');
                        } ?>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">

                        <a href="<?php echo _HOST_URL; ?>?module=auth&action=forgot" class="text-body">Quên mật khẩu?</a>
                    </div>

                    <div class="text-center text-lg-start mt-4 pt-2">
                        <button type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-lg"
                            style="padding-left: 2.5rem; padding-right: 2.5rem;">Đăng nhập</button>
                        <p class="small fw-bold mt-2 pt-1 mb-0">Chưa có tài khoản? <a href="<?php echo _HOST_URL ?>?module=auth&action=register"
                                class="link-danger">Đăng ký</a></p>
                    </div>

                </form>
            </div>
        </div>
    </div>


</section>

<?php

layout('footer');
