<?php
if (!defined('_TRGIAHUY')) {
    die('Truy cập không hợp lệ');
}

$data = ['title' => 'Quên mật khẩu'];
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
                <img src="<?php echo _HOST_URL_PUBLIC; ?>/assets/image/auth-page.jpg"
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
                        <h2 class="fw-normal mb-4 me-3">Quên mật khẩu</h2>
                    </div>

                    <!-- Email input -->
                    <div data-mdb-input-init class="form-outline mb-4">
                        <input type="text" name="email" value="<?php
                                                                if (!empty($oldData)) {
                                                                    echo oldData($oldData, 'email');
                                                                }  ?>"
                            id="form3Example3" class="form-control form-control-lg"
                            placeholder="Địa chỉ email" />
                        <?php if (!empty($errorsArr)) {
                            echo formError($errorsArr, 'email');
                        } ?>
                    </div>

                    <div class="text-center text-lg-start mt-4 pt-2">
                        <button type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-lg"
                            style="padding-left: 2.5rem; padding-right: 2.5rem;">Gửi</button>
                        <p class="small fw-bold mt-2 pt-1 mb-0">Quay về trang <a href="<?php echo _HOST_URL ?>/login"
                                class="link-danger">Đăng nhập</a></p>
                    </div>

                </form>
            </div>
        </div>
    </div>


</section>

<?php

layout('footer');
