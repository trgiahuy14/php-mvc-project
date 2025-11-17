<?php

use Core\Session;

$oldData = Session::getFlash('oldData');
$errorsArr = Session::getFlash('errors');
?>

<form method="POST" enctype="multipart/form-data">
    <div class="d-flex flex-row align-items-center justify-content-center justify-content-lg-start">
        <h2 class="fw-normal mb-5 me-3">Đặt lại mật khẩu</h2>
    </div>

    <!-- New password -->
    <div data-mdb-input-init class="form-outline mb-3">
        <input
            name="password"
            id="form3Example4"
            type="password"
            class="form-control form-control-lg"
            placeholder="Nhập mật khẩu mới" />
        <?= !empty($errorsArr) ? formError($errorsArr, 'password') : null ?>
    </div>

    <!-- Confirm new password -->
    <div data-mdb-input-init class="form-outline mb-3">
        <input
            name="confirm_pass"
            id="form3Example4"
            type="password"
            class="form-control form-control-lg"
            placeholder="Nhập lại mật khẩu mới" />
        <?= !empty($errorsArr) ? formError($errorsArr, 'confirm_pass') : null ?>
    </div>
    <div class="text-center text-lg-start mt-4 pt-2">
        <button type="submit" data-mdb-button-init data-mdb-ripple-init
            class="btn btn-primary btn-lg"
            style="padding-left: 2.5rem; padding-right: 2.5rem;">Gửi
        </button>

        <p class="small fw-bold mt-2 pt-1 mb-0">Quay về trang
            <a href="<?php echo BASE_URL ?>/login" class="link-danger">Đăng nhập</a>
        </p>
    </div>

</form>