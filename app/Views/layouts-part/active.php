<?php
$data = ['title' => 'Kích hoạt tài khoản'];

layout('header-auth', $data);

$filter = filterData('get');

// Xử lý đường link hợp lệ
if (!empty($filter['token'])):
    $token = $filter['token'];
    $checkToken = $coreModel->getOne("SELECT * FROM users WHERE active_token = '$token'");

?>
    <section class="vh-100">
        <div class="container-fluid h-custom">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-md-9 col-lg-6 col-xl-5">
                    <img src="<?= PUBLIC_URL ?>/assets/img/auth-page.jpg"
                        class="img-fluid" alt="Sample image">
                </div>
                <?php
                if (!empty($checkToken)):
                    // Thực hiện update dữ liệu
                    $data = [
                        'status' => 1,
                        'active_token' => null,
                        'updated_at' => date('Y:m:d H:i:s')
                    ];
                    $condition = 'id = ' . $checkToken['id'];
                    $update = $coreModel->update('users', $data, $condition);
                ?>
                    <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
                        <div class="d-flex flex-row align-items-center justify-content-center justify-content-lg-start">
                            <h2 class="fw-normal mb-5 me-3">Kích hoạt tài khoản thành công</h2>
                        </div>

                        <div class="text-center text-lg-start mt-4 pt-2">
                            <a href="<?= BASE_URL ?>/login"
                                class="link-danger" style="font-size: 20px">Đăng nhập ngay</a>
                        <?php
                    else:
                        ?>
                            <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
                                <div class="d-flex flex-row align-items-center justify-content-center justify-content-lg-start">
                                    <h2 class="fw-normal mb-5 me-3">Kích hoạt tài khoản không thành công. Đường link kích
                                        hoạt đã hết hạn hoặc không tồn tại</h2>
                                </div>
                                <div class="text-center text-lg-start mt-4 pt-2">
                                    <a href="<?= BASE_URL ?>/login"
                                        class="link-danger" style="font-size: 20px">Quay trở lại</a>
                                <?php
                            endif;
                                ?>
                                </div>
                            </div>
                        </div>
    </section>


<?php
// Đường link sai, không hợp lệ
else:
?>
    <section class="vh-100">
        <div class="container-fluid h-custom">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-md-9 col-lg-6 col-xl-5">
                    <img src="<?= PUBLIC_URL ?>/assets/img/auth-page.jpg"
                        class="img-fluid" alt="Sample image">
                </div>
                <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
                    <div class="d-flex flex-row align-items-center justify-content-center justify-content-lg-start">
                        <h2 class="fw-normal mb-5 me-3">Đường link kích hoạt đã hết hạn hoặc không tồn tại.</h2>
                    </div>

                    <div class="text-center text-lg-start mt-4 pt-2">
                        <a href="<?= BASE_URL ?>/login"
                            class="link-danger" style="font-size: 20px">Quay trở lại</a>
                    </div>
                </div>
            </div>
    </section>
<?php
endif;

layout('footer');
?>