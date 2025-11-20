<?php

use Core\Session;

$msg = Session::getFlash('msg');
$msg_type = Session::getFlash('msg_type');
$oldData = Session::getFlash('oldData');
$errorsArr = Session::getFlash('errors');
?>

<div class="container add-category">
    <div class="d-flex justify-content-between align-items-center mt-3 mb-1">
        <h2 class="mb-0">Thêm danh mục</h2>
        <a href="<?= BASE_URL ?>/categories" class="btn btn-secondary">
            <i class="fa fa-arrow-left me-1"></i> Quay lại
        </a>
    </div>

    <hr>

    <?php
    if (!empty($msg) && !empty($msg_type)) {
        getMsg($msg, $msg_type);
    }
    ?>

    <!-- Category creation form -->
    <form action="" method="post">
        <div class="row">

            <!-- Name -->
            <div class="col-12 mb-3">
                <label for="name" class="form-label">
                    Tên danh mục <span class="text-danger">*</span>
                </label>
                <input
                    id="name"
                    name="name"
                    type="text"
                    class="form-control <?= !empty($errorsArr['name']) ? 'is-invalid' : '' ?>"
                    placeholder="Nhập tên danh mục (tối thiểu 3 ký tự)"
                    value="<?= !empty($oldData) ? htmlspecialchars(oldData($oldData, 'name')) : '' ?>"
                    required>
                <?= !empty($errorsArr) ? formError($errorsArr, 'name') : '' ?>
            </div>

            <!-- Description -->
            <div class="col-12 mb-3">
                <label for="description" class="form-label">
                    Mô tả
                </label>
                <textarea
                    id="description"
                    name="description"
                    class="form-control <?= !empty($errorsArr['description']) ? 'is-invalid' : '' ?>"
                    rows="6"
                    placeholder="Nhập mô tả danh mục (tùy chọn)"><?= !empty($oldData) ? htmlspecialchars(oldData($oldData, 'description')) : '' ?></textarea>
                <?= !empty($errorsArr) ? formError($errorsArr, 'description') : '' ?>
            </div>

        </div>

        <!-- Action buttons -->
        <div class="mt-4 mb-5">
            <button type="submit" class="btn btn-success">
                <i class="fa fa-check me-1"></i> Thêm danh mục
            </button>
            <a href="<?= BASE_URL ?>/categories" class="btn btn-outline-secondary">
                <i class="fa fa-times me-1"></i> Hủy
            </a>
        </div>
    </form>
</div>

<script>
    const form = document.querySelector("form");
    let formChanged = false;

    // Track form changes
    form.addEventListener("input", () => {
        formChanged = true;
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
    .add-category .form-label {
        font-weight: 500;
        color: #495057;
    }

    .add-category .text-danger {
        color: #dc3545 !important;
    }

    .is-invalid {
        border-color: #dc3545 !important;
    }
</style>