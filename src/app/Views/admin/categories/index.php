<?php

use Core\Session;

$msg = Session::getFlash('msg');
$msg_type = Session::getFlash('msg_type');
?>

<div class="container categories-list">
    <div class="d-flex justify-content-between align-items-center mt-3 mb-1">
        <h2 class="mb-0">Danh sách danh mục</h2>
        <a href="<?= BASE_URL ?>/categories/add" class="btn btn-success">
            <i class="fa fa-plus me-1"></i> Thêm danh mục
        </a>
    </div>

    <hr>

    <?php
    if (!empty($msg) && !empty($msg_type)) {
        getMsg($msg, $msg_type);
    }
    ?>

    <!-- Search form -->
    <div class="card mb-3">
        <div class="card-body">
            <form action="" method="get" class="row g-3">
                <div class="col-md-10">
                    <input
                        type="text"
                        name="keyword"
                        class="form-control"
                        placeholder="Tìm kiếm theo tên danh mục..."
                        value="<?= htmlspecialchars($keyword ?? '') ?>">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fa fa-search me-1"></i> Tìm kiếm
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Categories table -->
    <div class="card">
        <div class="card-body">
            <?php if (!empty($categories)): ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">STT</th>
                                <th width="20%">Tên danh mục</th>
                                <th width="35%">Mô tả</th>
                                <th width="10%" class="text-center">Số bài viết</th>
                                <th width="15%">Ngày tạo</th>
                                <th width="15%" class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($categories as $index => $category): ?>
                                <tr>
                                    <td><?= $offset + $index + 1 ?></td>
                                    <td>
                                        <strong><?= htmlspecialchars($category['name']) ?></strong>
                                    </td>
                                    <td>
                                        <span class="text-muted">
                                            <?= htmlspecialchars(mb_substr($category['description'], 0, 100)) ?>
                                            <?= mb_strlen($category['description']) > 100 ? '...' : '' ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-info text-dark">
                                            <?= $category['post_count'] ?? 0 ?>
                                        </span>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            <?= date('d/m/Y H:i', strtotime($category['created_at'])) ?>
                                        </small>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="<?= BASE_URL ?>/categories/edit?id=<?= $category['id'] ?>"
                                                class="btn btn-warning"
                                                title="Sửa">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <a href="<?= BASE_URL ?>/categories/delete?id=<?= $category['id'] ?>"
                                                class="btn btn-danger btn-delete"
                                                title="Xóa"
                                                data-name="<?= htmlspecialchars($category['name']) ?>">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination info -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">
                        Hiển thị <?= count($categories) ?> / <?= $total ?> danh mục
                    </div>

                    <!-- Pagination links -->
                    <?php if ($maxPage > 1): ?>
                        <nav>
                            <ul class="pagination pagination-sm mb-0">
                                <!-- Previous button -->
                                <?php if ($page > 1): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?page=<?= $page - 1 ?><?= $queryString ?>">
                                            <i class="fa fa-chevron-left"></i>
                                        </a>
                                    </li>
                                <?php else: ?>
                                    <li class="page-item disabled">
                                        <span class="page-link"><i class="fa fa-chevron-left"></i></span>
                                    </li>
                                <?php endif; ?>

                                <!-- Page numbers -->
                                <?php
                                $start = max(1, $page - 2);
                                $end = min($maxPage, $page + 2);

                                if ($start > 1): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?page=1<?= $queryString ?>">1</a>
                                    </li>
                                    <?php if ($start > 2): ?>
                                        <li class="page-item disabled"><span class="page-link">...</span></li>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <?php for ($i = $start; $i <= $end; $i++): ?>
                                    <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                        <a class="page-link" href="?page=<?= $i ?><?= $queryString ?>">
                                            <?= $i ?>
                                        </a>
                                    </li>
                                <?php endfor; ?>

                                <?php if ($end < $maxPage): ?>
                                    <?php if ($end < $maxPage - 1): ?>
                                        <li class="page-item disabled"><span class="page-link">...</span></li>
                                    <?php endif; ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?page=<?= $maxPage ?><?= $queryString ?>">
                                            <?= $maxPage ?>
                                        </a>
                                    </li>
                                <?php endif; ?>

                                <!-- Next button -->
                                <?php if ($page < $maxPage): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?page=<?= $page + 1 ?><?= $queryString ?>">
                                            <i class="fa fa-chevron-right"></i>
                                        </a>
                                    </li>
                                <?php else: ?>
                                    <li class="page-item disabled">
                                        <span class="page-link"><i class="fa fa-chevron-right"></i></span>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </nav>
                    <?php endif; ?>
                </div>

            <?php else: ?>
                <div class="alert alert-info text-center mb-0">
                    <i class="fa fa-info-circle me-2"></i>
                    <?php if (!empty($keyword)): ?>
                        Không tìm thấy danh mục nào phù hợp với từ khóa "<?= htmlspecialchars($keyword) ?>"
                    <?php else: ?>
                        Chưa có danh mục nào. Hãy thêm danh mục mới!
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    // Confirm delete
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', function(e) {
            const categoryName = this.getAttribute('data-name');
            if (!confirm(`Bạn có chắc chắn muốn xóa danh mục "${categoryName}"?\n\nLưu ý: Các bài viết thuộc danh mục này sẽ không có danh mục.`)) {
                e.preventDefault();
            }
        });
    });
</script>

<style>
    .categories-list .table th {
        font-weight: 600;
        color: #495057;
        background-color: #f8f9fa;
    }

    .categories-list .btn-group-sm .btn {
        padding: 0.25rem 0.5rem;
    }

    .categories-list .badge {
        font-size: 0.875rem;
        padding: 0.35em 0.65em;
    }

    /* Modern Pagination Styling */
    .categories-list .pagination {
        margin-bottom: 0;
        gap: 0.25rem;
    }

    .categories-list .page-item {
        margin: 0 2px;
    }

    .categories-list .page-link {
        color: #495057;
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        padding: 0.4rem 0.75rem;
        font-weight: 500;
        transition: all 0.2s ease;
        min-width: 38px;
        text-align: center;
    }

    .categories-list .page-link:hover {
        background-color: #f8f9fa;
        border-color: #adb5bd;
        color: #212529;
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
    }

    .categories-list .page-item.active .page-link {
        background-color: #0d6efd;
        border-color: #0d6efd;
        color: #fff;
        box-shadow: 0 2px 8px rgba(13, 110, 253, 0.3);
    }

    .categories-list .page-item.disabled .page-link {
        background-color: #f8f9fa;
        border-color: #dee2e6;
        color: #6c757d;
        cursor: not-allowed;
        opacity: 0.6;
    }

    .categories-list .pagination .page-link i {
        font-size: 0.875rem;
    }
</style>