<?php
$oldData = getSessionFlash('oldData');
$errorsArr = getSessionFlash('errors');
?>

<form method="POST" enctype="multipart/form-data">
    <div class="d-flex flex-row align-items-center justify-content-center justify-content-lg-start">
        <h2 class="fw-normal mb-5 me-3">Đăng nhập hệ thống</h2>
    </div>

    <!-- Email input -->
    <div data-mdb-input-init class="form-outline mb-4">
        <input
            name="email"
            id="form3Example3"
            type="text"
            class="form-control form-control-lg"
            placeholder="Địa chỉ email"
            value="<?= !empty($oldData) ? oldData($oldData, 'phone') : null; ?>" />
        <?= !empty($errorsArr) ? formError($errorsArr, 'email') : null ?>
    </div>

    <!-- Password input -->
    <div data-mdb-input-init class="form-outline mb-3">
        <input
            name="password"
            id="form3Example4"
            type="password"
            class="form-control form-control-lg"
            placeholder="Nhập mật khẩu" />
        <?= !empty($errorsArr) ? formError($errorsArr, 'password') : null ?>
    </div>

    <div class="d-flex justify-content-between align-items-center">

        <a href="<?= BASE_URL ?>/forgot" class="text-body">Quên mật khẩu?</a>
    </div>

    <div class="text-center text-lg-start mt-4 pt-2">

        <button type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-lg"
            style="padding-left: 2.5rem; padding-right: 2.5rem;">Đăng nhập</button>

        <p class="small fw-bold mt-2 pt-1 mb-0">Chưa có tài khoản? <a href="<?= BASE_URL ?>/register"
                class="link-danger">Đăng ký</a></p>
    </div>
</form>