<?php
$data = ['title' => 'Kích hoạt tài khoản'];
layout('header-auth', $data);
?>

<section class="vh-100">
    <div class="container-fluid h-custom">
        <div class="row d-flex justify-content-center align-items-center h-100">

            <div class="col-md-9 col-lg-6 col-xl-5">
                <img src="<?= PUBLIC_URL ?>/assets/img/auth-page.jpg"
                    class="img-fluid" alt="Activation image">
            </div>

            <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">

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

            </div>
        </div>
    </div>
</section>

<?php layout('footer'); ?>