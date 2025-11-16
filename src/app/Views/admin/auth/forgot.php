<?php
$oldData = getSessionFlash('oldData');
$errorsArr = getSessionFlash('errors');
?>

<form method="POST" action="" enctype="multipart/form-data">
    <div class="d-flex flex-row align-items-center justify-content-center justify-content-lg-start">
        <h2 class="fw-normal mb-4 me-3">Quên mật khẩu</h2>
    </div>

    <!-- Email input -->
    <div data-mdb-input-init class="form-outline mb-4">
        <input
            name="email"
            id="form3Example3"
            type="text"
            class="form-control form-control-lg"
            placeholder="Địa chỉ email"
            value="<?= !empty($oldData) ? oldData($oldData, 'email') : null ?>" />
        <?= !empty($errorsArr) ? formError($errorsArr, 'email') : null ?>
    </div>

    <div class="text-center text-lg-start mt-4 pt-2">
        <button type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-lg"
            style="padding-left: 2.5rem; padding-right: 2.5rem;">Gửi</button>
        <p class="small fw-bold mt-2 pt-1 mb-0">Quay về trang <a href="<?= BASE_URL ?>/login"
                class="link-danger">Đăng nhập</a></p>
    </div>
</form>