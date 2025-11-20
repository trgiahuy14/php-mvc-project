<?php

use Core\Session;

$oldData = Session::getFlash('oldData');
$errorsArr = Session::getFlash('errors');
?>

<div class="auth-header">
    <h3 class="auth-title">Quên mật khẩu</h3>
    <p class="auth-subtitle">Nhập email của bạn, chúng tôi sẽ gửi link đặt lại mật khẩu</p>
</div>

<form method="POST" action="" class="auth-form" enctype="multipart/form-data">
    <!-- Email input -->
    <div class="form-group">
        <label for="email" class="form-label">
            <i class="bi bi-envelope"></i> Email
        </label>
        <input
            name="email"
            id="email"
            type="email"
            class="form-control"
            placeholder="Nhập địa chỉ email"
            value="<?= !empty($oldData) ? oldData($oldData, 'email') : ''; ?>"
            autocomplete="email"
            required />
        <?= !empty($errorsArr) ? formError($errorsArr, 'email') : null ?>
    </div>

    <button type="submit" class="btn btn-primary btn-auth">
        <i class="bi bi-send"></i>
        Gửi link khôi phục
    </button>

    <div class="auth-footer-links">
        <p>
            <a href="<?= BASE_URL ?>/login" class="auth-link">
                <i class="bi bi-arrow-left"></i> Quay lại đăng nhập
            </a>
        </p>
    </div>
</form>