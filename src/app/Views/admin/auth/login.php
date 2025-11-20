<?php

use Core\Session;

$oldData = Session::getFlash('oldData');
$errorsArr = Session::getFlash('errors');
?>

<div class="auth-header">
    <h3 class="auth-title">Đăng nhập</h3>
    <p class="auth-subtitle">Đăng nhập vào tài khoản của bạn để tiếp tục</p>
</div>

<form method="POST" class="auth-form" enctype="multipart/form-data">
    <!-- Email input -->
    <div class="form-group">
        <label for="email" class="form-label">
            <i class="bi bi-envelope"></i> Email
        </label>
        <input
            name="email"
            id="email"
            type="text"
            class="form-control"
            placeholder="Nhập địa chỉ email"
            value="<?= !empty($oldData) ? oldData($oldData, 'email') : ''; ?>"
            autocomplete="email"
            required />
        <?= !empty($errorsArr) ? formError($errorsArr, 'email') : null ?>
    </div>

    <!-- Password input -->
    <div class="form-group">
        <label for="password" class="form-label">
            <i class="bi bi-lock"></i> Mật khẩu
        </label>
        <div class="password-input-wrapper">
            <input
                name="password"
                id="password"
                type="password"
                class="form-control"
                placeholder="Nhập mật khẩu"
                autocomplete="current-password"
                required />
            <button type="button" class="password-toggle" onclick="togglePassword('password')">
                <i class="bi bi-eye" id="password-icon"></i>
            </button>
        </div>
        <?= !empty($errorsArr) ? formError($errorsArr, 'password') : null ?>
    </div>

    <div class="form-options">
        <a href="<?= BASE_URL ?>/forgot" class="forgot-link">
            Quên mật khẩu?
        </a>
    </div>

    <button type="submit" class="btn btn-primary btn-auth">
        <i class="bi bi-box-arrow-in-right"></i>
        Đăng nhập
    </button>

    <div class="auth-footer-links">
        <p>Chưa có tài khoản? <a href="<?= BASE_URL ?>/register" class="auth-link">Đăng ký ngay</a></p>
    </div>
</form>

<script>
    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        const icon = document.getElementById(fieldId + '-icon');
        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            field.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    }
</script>