<?php

use Core\Session;

$msg = Session::getFlash('msg');
$msg_type = Session::getFlash('msg_type');
$oldData = Session::getFlash('oldData');
$errorsArr = Session::getFlash('errors');

// Use old data if available, otherwise use post data
$title = !empty($oldData) ? oldData($oldData, 'title') : ($post['title'] ?? '');
$content = !empty($oldData) ? oldData($oldData, 'content') : ($post['content'] ?? '');
$category_id = !empty($oldData) ? oldData($oldData, 'category_id') : ($post['category_id'] ?? '');
?>

<div class="container edit-post">
    <div class="d-flex justify-content-between align-items-center mt-3 mb-1">
        <h2 class="mb-0">Chỉnh sửa bài viết</h2>
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

    <!-- Post edit form -->
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
                    value="<?= htmlspecialchars($title) ?>"
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
                    required><?= htmlspecialchars($content) ?></textarea>
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
                                <?= ($category_id == $category['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($category['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                <?= !empty($errorsArr) ? formError($errorsArr, 'category_id') : '' ?>
            </div>

            <!-- Post info -->
            <div class="col-12 mb-3">
                <div class="alert alert-info">
                    <strong><i class="fa fa-info-circle"></i> Thông tin bài viết:</strong><br>
                    Tác giả: <strong><?= htmlspecialchars($post['author_name'] ?? 'N/A') ?></strong><br>
                    Lượt xem: <strong><?= number_format($post['views']) ?></strong><br>
                    Bình luận: <strong><?= number_format($post['comment_count']) ?></strong><br>
                    Ngày tạo: <strong><?= date('d-m-Y H:i', strtotime($post['created_at'])) ?></strong><br>
                    Cập nhật: <strong><?= date('d-m-Y H:i', strtotime($post['updated_at'])) ?></strong>
                </div>
            </div>

        </div>

        <!-- Action buttons -->
        <div class="mt-4 mb-5">
            <button type="submit" class="btn btn-success">
                <i class="fa fa-check me-1"></i> Cập nhật
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
    .edit-post .form-label {
        font-weight: 500;
        color: #495057;
    }

    .edit-post .text-danger {
        color: #dc3545 !important;
    }

    .is-invalid {
        border-color: #dc3545 !important;
    }

    .alert-info {
        background-color: #d1ecf1;
        border-color: #bee5eb;
        color: #0c5460;
    }
</style>