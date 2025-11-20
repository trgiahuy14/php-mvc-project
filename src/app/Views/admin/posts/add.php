<?php

use Core\Session;

$msg = Session::getFlash('msg');
$msg_type = Session::getFlash('msg_type');
$oldData = Session::getFlash('oldData');
$errorsArr = Session::getFlash('errors');
?>

<div class="container add-post">
    <div class="d-flex justify-content-between align-items-center mt-3 mb-1">
        <h2 class="mb-0">Thêm bài viết</h2>
        <a href="<?= BASE_URL ?>/posts" class="btn btn-secondary">
            <i class="fa fa-arrow-left me-1"></i> Quay lại
        </a>
    </div>

    <hr>

    <?php
    if (!empty($msg) && !empty($msg_type)) {
        getMsg($msg, $msg_type);
    }
    ?>

    <!-- Post creation form -->
    <form action="" method="post">
        <div class="row">

            <!-- Title -->
            <div class="col-12 mb-3">
                <label for="title" class="form-label">
                    Tiêu đề bài viết <span class="text-danger">*</span>
                </label>
                <input
                    id="title"
                    name="title"
                    type="text"
                    class="form-control <?= !empty($errorsArr['title']) ? 'is-invalid' : '' ?>"
                    placeholder="Nhập tiêu đề bài viết (tối thiểu 10 ký tự)"
                    value="<?= !empty($oldData) ? htmlspecialchars(oldData($oldData, 'title')) : '' ?>"
                    required>
                <?= !empty($errorsArr) ? formError($errorsArr, 'title') : '' ?>
            </div>

            <!-- Content -->
            <div class="col-12 mb-3">
                <label for="content" class="form-label">
                    Nội dung bài viết <span class="text-danger">*</span>
                </label>
                <textarea
                    id="content"
                    name="content"
                    class="form-control <?= !empty($errorsArr['content']) ? 'is-invalid' : '' ?>"
                    rows="12"
                    placeholder="Nhập nội dung bài viết (tối thiểu 50 ký tự)"
                    required><?= !empty($oldData) ? htmlspecialchars(oldData($oldData, 'content')) : '' ?></textarea>
                <?= !empty($errorsArr) ? formError($errorsArr, 'content') : '' ?>
            </div>

            <!-- Category -->
            <div class="col-12 mb-3">
                <label for="category_id" class="form-label">
                    Danh mục <span class="text-danger">*</span>
                </label>
                <select
                    id="category_id"
                    name="category_id"
                    class="form-select <?= !empty($errorsArr['category_id']) ? 'is-invalid' : '' ?>"
                    required>
                    <option value="">-- Chọn danh mục --</option>
                    <?php if (!empty($categories)): ?>
                        <?php foreach ($categories as $category): ?>
                            <option
                                value="<?= $category['id'] ?>"
                                <?= (!empty($oldData) && oldData($oldData, 'category_id') == $category['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($category['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                <?= !empty($errorsArr) ? formError($errorsArr, 'category_id') : '' ?>
            </div>

        </div>

        <!-- Action buttons -->
        <div class="mt-4 mb-5">
            <button type="submit" class="btn btn-success">
                <i class="fa fa-check me-1"></i> Thêm bài viết
            </button>
            <a href="<?= BASE_URL ?>/posts" class="btn btn-outline-secondary">
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
    .add-post .form-label {
        font-weight: 500;
        color: #495057;
    }

    .add-post .text-danger {
        color: #dc3545 !important;
    }

    .is-invalid {
        border-color: #dc3545 !important;
    }
</style>