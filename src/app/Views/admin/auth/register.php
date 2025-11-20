<?php

use Core\Session;

$oldData = Session::getFlash('oldData');
$errorsArr = Session::getFlash('errors');
?>

<div class="auth-header">
    <h3 class="auth-title">Đăng ký tài khoản</h3>
    <p class="auth-subtitle">Tạo tài khoản mới để bắt đầu sử dụng hệ thống</p>
</div>

<form method="POST" action="" class="auth-form" enctype="multipart/form-data">
    <!-- Username -->
    <div class="form-group">
        <label for="username" class="form-label">
            <i class="bi bi-person"></i> Tên người dùng
        </label>
        <input
            name="username"
            id="username"
            type="text"
            class="form-control"
            placeholder="Chọn tên người dùng của bạn"
            value="<?= !empty($oldData) ? oldData($oldData, 'username') : ''; ?>"
            autocomplete="username"
            required />
        <?= !empty($errorsArr) ? formError($errorsArr, 'username') : null ?>
    </div>

    <!-- Email -->
    <div class="form-group">
        <label for="email" class="form-label">
            <i class="bi bi-envelope"></i> Email
        </label>
        <input
            name="email"
            id="email"
            type="email"
            class="form-control"
            placeholder="Nhập địa chỉ email của bạn"
            value="<?= !empty($oldData) ? oldData($oldData, 'email') : ''; ?>"
            autocomplete="email"
            required />
        <?= !empty($errorsArr) ? formError($errorsArr, 'email') : null ?>
    </div>

    <!-- Password -->
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
                placeholder="Tạo mật khẩu mạnh"
                autocomplete="new-password"
                required />
            <button type="button" class="password-toggle" onclick="togglePassword('password')">
                <i class="bi bi-eye" id="password-icon"></i>
            </button>
        </div>
        <small class="form-text">Ít nhất 8 ký tự, bao gồm chữ hoa, chữ thường và số</small>
        <?= !empty($errorsArr) ? formError($errorsArr, 'password') : null ?>
    </div>

    <!-- Confirm password -->
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

    <div class="form-check mb-4">
        <input class="form-check-input" type="checkbox" id="terms" required>
        <label class="form-check-label" for="terms">
            Tôi đồng ý với <a href="#" class="auth-link">Điều khoản dịch vụ</a> và <a href="#" class="auth-link">Chính sách bảo mật</a>
        </label>
    </div>

    <button type="submit" class="btn btn-primary btn-auth">
        <i class="bi bi-person-plus"></i>
        Đăng ký
    </button>

    <div class="auth-footer-links">
        <p>Đã có tài khoản? <a href="<?= BASE_URL ?>/login" class="auth-link">Đăng nhập</a></p>
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