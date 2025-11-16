<?php
$oldData = getSessionFlash('oldData');
$errorsArr = getSessionFlash('errors');
?>

<form method="POST" action="" enctype="multipart/form-data">
    <div class="d-flex flex-row align-items-center justify-content-center justify-content-lg-start">
        <h2 class="fw-normal mb-5 me-3">Đăng ký tài khoản</h2>
    </div>
    <!-- Register form -->
    <!-- Full name -->
    <div data-mdb-input-init class="form-outline mb-4">
        <input
            name="fullname"
            type="Text"
            class="form-control form-control-lg"
            placeholder="Họ tên"
            value="<?= !empty($oldData) ? oldData($oldData, 'fullname') : null; ?>" />
        <?= !empty($errorsArr) ? formError($errorsArr, 'fullname') : null ?>
    </div>

    <!-- Email -->
    <div data-mdb-input-init class="form-outline mb-4">
        <input
            name="email"
            type="text"
            class="form-control form-control-lg"
            placeholder="Địa chỉ email"
            value="<?= !empty($oldData) ? oldData($oldData, 'email') : null; ?>" />
        <?= !empty($errorsArr) ? formError($errorsArr, 'email') : null ?>
    </div>

    <!-- Phone number -->
    <div data-mdb-input-init class="form-outline mb-4">
        <input
            name="phone"
            type="text"
            placeholder="Nhập số điện thoại"
            class="form-control form-control-lg"
            value="<?= !empty($oldData) ? oldData($oldData, 'phone') : null; ?>" />
        <?= !empty($errorsArr) ? formError($errorsArr, 'phone') : null ?>
    </div>


    <!-- Password -->
    <div data-mdb-input-init class="form-outline mb-3">
        <input
            name="password"
            id="form3Example4"
            type="password"
            class="form-control form-control-lg"
            placeholder="Nhập mật khẩu" />
        <?= !empty($errorsArr) ? formError($errorsArr, 'password') : null ?>
    </div>

    <!-- Confirm password -->
    <div data-mdb-input-init class="form-outline mb-3">
        <input
            name="confirm_pass"
            id="form3Example4"
            type="password"
            class="form-control form-control-lg"
            placeholder="Nhập lại mật khẩu" />
        <?= !empty($errorsArr) ? formError($errorsArr, 'confirm_pass') : null ?>
    </div>

    <div class="text-center text-lg-start mt-4 pt-2">

        <button type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-lg"
            style="padding-left: 2.5rem; padding-right: 2.5rem;">Đăng ký</button>

        <p class="small fw-bold mt-2 pt-1 mb-0">Bạn đã có tài khoản? <a href="<?= BASE_URL ?>/login"
                class="link-danger">Đăng nhập</a></p>
    </div>
</form>