<?php

use Core\Session;

$oldData = Session::getFlash('oldData');
$errorsArr = Session::getFlash('errors');
?>

<div class="auth-header">
    <h3 class="auth-title">Đặt lại mật khẩu</h3>
    <p class="auth-subtitle">Tạo mật khẩu mới cho tài khoản của bạn</p>
</div>

<form method="POST" class="auth-form" enctype="multipart/form-data">
    <!-- New password -->
    <div class="form-group">
        <label for="password" class="form-label">
            <i class="bi bi-lock"></i> Mật khẩu mới
        </label>
        <div class="password-input-wrapper">
            <input
                name="password"
                id="password"
                type="password"
                class="form-control"
                placeholder="Nhập mật khẩu mới"
                autocomplete="new-password"
                required />
            <button type="button" class="password-toggle" onclick="togglePassword('password')">
                <i class="bi bi-eye" id="password-icon"></i>
            </button>
        </div>
        <small class="form-text">Ít nhất 8 ký tự, bao gồm chữ hoa, chữ thường và số</small>
        <?= !empty($errorsArr) ? formError($errorsArr, 'password') : null ?>
    </div>

    <!-- Confirm new password -->
    <div class="form-group">
        <label for="confirm_pass" class="form-label">
            <i class="bi bi-lock-fill"></i> Xác nhận mật khẩu
        </label>
        <div class="password-input-wrapper">
            <input
                name="confirm_pass"
                id="confirm_pass"
                type="password"
                class="form-control"
                placeholder="Nhập lại mật khẩu"
                autocomplete="new-password"
                required />
            <button type="button" class="password-toggle" onclick="togglePassword('confirm_pass')">
                <i class="bi bi-eye" id="confirm_pass-icon"></i>
            </button>
        </div>
        <?= !empty($errorsArr) ? formError($errorsArr, 'confirm_pass') : null ?>
    </div>

    <button type="submit" class="btn btn-primary btn-auth">
        <i class="bi bi-check-circle"></i>
        Cập nhật mật khẩu
    </button>

    <div class="auth-footer-links">
        <p>
            <a href="<?= BASE_URL ?>/login" class="auth-link">
                <i class="bi bi-arrow-left"></i> Quay lại đăng nhập
            </a>
        </p>
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