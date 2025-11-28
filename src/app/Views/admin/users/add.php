<?php

use Core\Session;

$msg = Session::getFlash('msg');
$msg_type = Session::getFlash('msg_type');
$oldData = Session::getFlash('oldData');
$errorsArr = Session::getFlash('errors');
?>

<div class="container add-user">
    <div class="d-flex justify-content-between align-items-center mt-3 mb-1">
        <h2 class="mb-0">
            <i class="fa fa-user-plus me-2"></i> Thêm người dùng mới
        </h2>
        <a href="<?= BASE_URL ?>/users" class="btn btn-secondary">
            <i class="fa fa-arrow-left me-1"></i> Quay lại
        </a>
    </div>

    <hr>

    <?php
    if (!empty($msg) && !empty($msg_type)) {
        getMsg($msg, $msg_type);
    }
    ?>

    <!-- User add form -->
    <form action="" method="post" enctype="multipart/form-data">
        <div class="row">

            <!-- Left Column: Avatar Upload -->
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title mb-3">Ảnh đại diện</h5>

                        <!-- Avatar Preview -->
                        <div class="avatar-preview mb-3">
                            <div id="avatarPlaceholder" class="avatar-placeholder rounded-circle mx-auto">
                                <i class="fa fa-user fa-3x"></i>
                            </div>
                        </div>

                        <!-- Avatar Upload -->
                        <div class="mb-3">
                            <label for="avatar" class="btn btn-outline-primary btn-sm w-100">
                                <i class="fa fa-upload me-1"></i> Chọn ảnh
                            </label>
                            <input
                                type="file"
                                id="avatar"
                                name="avatar"
                                class="d-none"
                                accept="image/*">
                            <small class="text-muted d-block mt-2">
                                JPG, PNG, GIF (tối đa 2MB)
                            </small>
                        </div>

                        <hr>
                        <div class="text-muted small">
                            <i class="fa fa-info-circle me-1"></i>
                            Nếu không chọn ảnh, hệ thống sẽ sử dụng avatar mặc định
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: User Information -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Thông tin người dùng</h5>

                        <div class="row">
                            <!-- Username -->
                            <div class="col-md-6 mb-3">
                                <label for="username" class="form-label">
                                    Username <span class="text-danger">*</span>
                                </label>
                                <input
                                    id="username"
                                    name="username"
                                    type="text"
                                    class="form-control <?= !empty($errorsArr['username']) ? 'is-invalid' : '' ?>"
                                    placeholder="Nhập username"
                                    value="<?= !empty($oldData) ? htmlspecialchars(oldData($oldData, 'username') ?? '', ENT_QUOTES, 'UTF-8') : '' ?>"
                                    required>
                                <?= !empty($errorsArr) ? formError($errorsArr, 'username') : '' ?>
                            </div>

                            <!-- Email -->
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">
                                    Email <span class="text-danger">*</span>
                                </label>
                                <input
                                    id="email"
                                    name="email"
                                    type="email"
                                    class="form-control <?= !empty($errorsArr['email']) ? 'is-invalid' : '' ?>"
                                    placeholder="Nhập email"
                                    value="<?= !empty($oldData) ? htmlspecialchars(oldData($oldData, 'email') ?? '', ENT_QUOTES, 'UTF-8') : '' ?>"
                                    required>
                                <?= !empty($errorsArr) ? formError($errorsArr, 'email') : '' ?>
                            </div>

                            <!-- Fullname -->
                            <div class="col-md-6 mb-3">
                                <label for="fullname" class="form-label">
                                    Họ và tên
                                </label>
                                <input
                                    id="fullname"
                                    name="fullname"
                                    type="text"
                                    class="form-control <?= !empty($errorsArr['fullname']) ? 'is-invalid' : '' ?>"
                                    placeholder="Nhập họ và tên"
                                    value="<?= !empty($oldData) ? htmlspecialchars(oldData($oldData, 'fullname') ?? '', ENT_QUOTES, 'UTF-8') : '' ?>">
                                <?= !empty($errorsArr) ? formError($errorsArr, 'fullname') : '' ?>
                            </div>

                            <!-- Phone -->
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">
                                    Số điện thoại
                                </label>
                                <input
                                    id="phone"
                                    name="phone"
                                    type="text"
                                    class="form-control <?= !empty($errorsArr['phone']) ? 'is-invalid' : '' ?>"
                                    placeholder="Nhập số điện thoại"
                                    value="<?= !empty($oldData) ? htmlspecialchars(oldData($oldData, 'phone') ?? '', ENT_QUOTES, 'UTF-8') : '' ?>">
                                <?= !empty($errorsArr) ? formError($errorsArr, 'phone') : '' ?>
                            </div>

                            <!-- Password -->
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">
                                    Mật khẩu <span class="text-danger">*</span>
                                </label>
                                <input
                                    id="password"
                                    name="password"
                                    type="password"
                                    class="form-control <?= !empty($errorsArr['password']) ? 'is-invalid' : '' ?>"
                                    placeholder="Tối thiểu 6 ký tự"
                                    required>
                                <?= !empty($errorsArr) ? formError($errorsArr, 'password') : '' ?>
                            </div>

                            <!-- Confirm Password -->
                            <div class="col-md-6 mb-3">
                                <label for="password_confirm" class="form-label">
                                    Xác nhận mật khẩu <span class="text-danger">*</span>
                                </label>
                                <input
                                    id="password_confirm"
                                    name="password_confirm"
                                    type="password"
                                    class="form-control <?= !empty($errorsArr['password_confirm']) ? 'is-invalid' : '' ?>"
                                    placeholder="Nhập lại mật khẩu"
                                    required>
                                <?= !empty($errorsArr) ? formError($errorsArr, 'password_confirm') : '' ?>
                            </div>

                            <!-- Role -->
                            <div class="col-md-6 mb-3">
                                <label for="role" class="form-label">
                                    Vai trò <span class="text-danger">*</span>
                                </label>
                                <select
                                    id="role"
                                    name="role"
                                    class="form-select <?= !empty($errorsArr['role']) ? 'is-invalid' : '' ?>"
                                    required>
                                    <?php
                                    $currentRole = !empty($oldData) ? oldData($oldData, 'role') : 'author';
                                    ?>
                                    <option value="">-- Chọn vai trò --</option>
                                    <option value="author" <?= $currentRole === 'author' ? 'selected' : '' ?>>Author</option>
                                    <option value="editor" <?= $currentRole === 'editor' ? 'selected' : '' ?>>Editor</option>
                                    <option value="admin" <?= $currentRole === 'admin' ? 'selected' : '' ?>>Admin</option>
                                </select>
                                <?= !empty($errorsArr) ? formError($errorsArr, 'role') : '' ?>
                            </div>

                            <!-- Status -->
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">
                                    Trạng thái <span class="text-danger">*</span>
                                </label>
                                <select
                                    id="status"
                                    name="status"
                                    class="form-select <?= !empty($errorsArr['status']) ? 'is-invalid' : '' ?>"
                                    required>
                                    <?php
                                    $currentStatus = !empty($oldData) ? oldData($oldData, 'status') : 'active';
                                    ?>
                                    <option value="">-- Chọn trạng thái --</option>
                                    <option value="active" <?= $currentStatus === 'active' ? 'selected' : '' ?>>Hoạt động</option>
                                    <option value="inactive" <?= $currentStatus === 'inactive' ? 'selected' : '' ?>>Không hoạt động</option>
                                    <option value="banned" <?= $currentStatus === 'banned' ? 'selected' : '' ?>>Bị cấm</option>
                                </select>
                                <?= !empty($errorsArr) ? formError($errorsArr, 'status') : '' ?>
                            </div>

                            <!-- Bio -->
                            <div class="col-12 mb-3">
                                <label for="bio" class="form-label">
                                    Giới thiệu bản thân
                                </label>
                                <textarea
                                    id="bio"
                                    name="bio"
                                    class="form-control <?= !empty($errorsArr['bio']) ? 'is-invalid' : '' ?>"
                                    rows="4"
                                    placeholder="Viết vài dòng giới thiệu về bản thân..."><?= !empty($oldData) ? htmlspecialchars(oldData($oldData, 'bio') ?? '', ENT_QUOTES, 'UTF-8') : '' ?></textarea>
                                <?= !empty($errorsArr) ? formError($errorsArr, 'bio') : '' ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action buttons -->
        <div class="mt-4 mb-5">
            <button type="submit" class="btn btn-success btn-lg">
                <i class="fa fa-check me-1"></i> Thêm người dùng
            </button>
            <a href="<?= BASE_URL ?>/users" class="btn btn-outline-secondary btn-lg">
                <i class="fa fa-times me-1"></i> Hủy
            </a>
        </div>
    </form>
</div>

<script>
    const form = document.querySelector("form");
    const avatarInput = document.getElementById("avatar");
    const avatarPlaceholder = document.getElementById("avatarPlaceholder");
    let formChanged = false;

    // Track form changes
    form.addEventListener("input", () => {
        formChanged = true;
    });

    // Preview avatar when file selected
    avatarInput.addEventListener("change", function(e) {
        const file = e.target.files[0];
        if (file) {
            // Validate file size (2MB max)
            if (file.size > 2 * 1024 * 1024) {
                alert("Kích thước file không được vượt quá 2MB");
                this.value = "";
                return;
            }

            // Validate file type
            if (!file.type.match('image.*')) {
                alert("Vui lòng chọn file ảnh");
                this.value = "";
                return;
            }

            // Preview image
            const reader = new FileReader();
            reader.onload = function(e) {
                // Replace placeholder with image
                const img = document.createElement('img');
                img.id = 'avatarPreview';
                img.src = e.target.result;
                img.className = 'rounded-circle';
                avatarPlaceholder.replaceWith(img);
            };
            reader.readAsDataURL(file);
            formChanged = true;
        }
    });

    // Prevent accidental navigation
    window.addEventListener('beforeunload', function(e) {
        if (formChanged) {
            e.preventDefault();
            e.returnValue = '';
        }
    });

    // Reset flag when form submitted
    form.addEventListener('submit', function() {
        formChanged = false;
    });
</script>

<style>
    .add-user .form-label {
        font-weight: 500;
        color: #495057;
    }

    .add-user .text-danger {
        color: #dc3545 !important;
    }

    .is-invalid {
        border-color: #dc3545 !important;
    }

    .add-user .avatar-preview #avatarPlaceholder {
        width: 150px;
        height: 150px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 3px solid #f8f9fa;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .add-user .avatar-preview img {
        width: 150px;
        height: 150px;
        object-fit: cover;
        border: 3px solid #f8f9fa;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .add-user .card {
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
        border: none;
    }

    .add-user .card-title {
        color: #495057;
        font-weight: 600;
    }
</style>