<?php if (($status ?? 'invalid') === 'success'): ?>
    <div class="activation-result success">
        <div class="activation-icon">
            <i class="bi bi-check-circle"></i>
        </div>
        <h3 class="activation-title">Kích hoạt thành công!</h3>
        <p class="activation-message">
            Tài khoản của bạn đã được kích hoạt. Bạn có thể đăng nhập ngay bây giờ.
        </p>
        <a href="<?= BASE_URL ?>/login" class="btn btn-primary btn-auth">
            <i class="bi bi-box-arrow-in-right"></i>
            Đăng nhập
        </a>
    </div>

<?php elseif ($status === 'error'): ?>
    <div class="activation-result error">
        <div class="activation-icon">
            <i class="bi bi-exclamation-triangle"></i>
        </div>
        <h3 class="activation-title">Đã xảy ra lỗi</h3>
        <p class="activation-message">
            Hệ thống không thể kích hoạt tài khoản. Vui lòng thử lại sau.
        </p>
        <a href="<?= BASE_URL ?>/login" class="btn btn-secondary btn-auth">
            <i class="bi bi-arrow-left"></i>
            Quay lại
        </a>
    </div>

<?php else: ?>
    <div class="activation-result warning">
        <div class="activation-icon">
            <i class="bi bi-clock-history"></i>
        </div>
        <h3 class="activation-title">Link không hợp lệ</h3>
        <p class="activation-message">
            Link kích hoạt đã hết hạn hoặc không tồn tại.
        </p>
        <div class="activation-buttons">
            <a href="<?= BASE_URL ?>/login" class="btn btn-secondary btn-auth">
                <i class="bi bi-arrow-left"></i>
                Quay lại
            </a>
            <a href="<?= BASE_URL ?>/register" class="btn btn-primary btn-auth">
                <i class="bi bi-person-plus"></i>
                Đăng ký lại
            </a>
        </div>
    </div>

<?php endif; ?>