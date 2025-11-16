<?php if (($status ?? 'invalid') === 'success'): ?>
    <h2 class="fw-normal mb-5">Kích hoạt tài khoản thành công</h2>
    <div class="text-center mt-4">
        <a href="<?= BASE_URL ?>/login" class="link-danger" style="font-size: 20px">
            Đăng nhập ngay
        </a>
    </div>

<?php elseif ($status === 'error'): ?>
    <h2 class="fw-normal mb-5">
        Lỗi hệ thống! Không thể kích hoạt tài khoản.
    </h2>
    <div class="text-center mt-4">
        <a href="<?= BASE_URL ?>/login" class="link-danger" style="font-size: 20px">
            Quay trở lại
        </a>
    </div>

<?php else: ?>
    <h2 class="fw-normal mb-5">
        Đường link kích hoạt đã hết hạn hoặc không tồn tại.
    </h2>
    <div class="text-center mt-4">
        <a href="<?= BASE_URL ?>/login" class="link-danger" style="font-size: 20px">
            Quay trở lại
        </a>
    </div>

<?php endif; ?>