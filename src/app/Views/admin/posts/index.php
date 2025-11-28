<?php

use Core\Session;

$msg = Session::getFlash('msg');
$msg_type = Session::getFlash('msg_type');
?>

<div class="container posts-list">
    <div class="d-flex justify-content-between align-items-center mt-3 mb-1">
        <h2 class="mb-0">Danh sách bài viết</h2>
        <a href="<?= BASE_URL ?>/posts/add" class="btn btn-success">
            <i class="fa fa-plus me-1"></i> Thêm bài viết
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
                        placeholder="Tìm kiếm theo tiêu đề bài viết..."
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

    <!-- Posts table -->
    <div class="card">
        <div class="card-body">
            <?php if (!empty($posts)): ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">STT</th>
                                <th width="30%">Tiêu đề</th>
                                <th width="12%">Danh mục</th>
                                <th width="12%">Tác giả</th>
                                <th width="8%" class="text-center">Lượt xem</th>
                                <th width="8%" class="text-center">Bình luận</th>
                                <th width="12%">Ngày tạo</th>
                                <th width="13%" class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($posts as $index => $post): ?>
                                <tr>
                                    <td><?= $offset + $index + 1 ?></td>
                                    <td>
                                        <a href="<?= BASE_URL ?>/posts/edit?id=<?= $post['id'] ?>"
                                            class="post-title-link"
                                            title="Chỉnh sửa bài viết">
                                            <strong><?= htmlspecialchars(mb_substr($post['title'], 0, 60)) ?></strong>
                                            <?= mb_strlen($post['title']) > 60 ? '...' : '' ?>
                                        </a>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">
                                            <?= htmlspecialchars($post['category_name'] ?? 'Chưa có') ?>
                                        </span>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            <i class="fa fa-user me-1"></i>
                                            <?= htmlspecialchars($post['author_name'] ?? 'N/A') ?>
                                        </small>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-info text-dark">
                                            <i class="fa fa-eye me-1"></i>
                                            <?= number_format($post['views'] ?? 0) ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary">
                                            <i class="fa fa-comment me-1"></i>
                                            <?= number_format($post['comment_count'] ?? 0) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            <i class="fa fa-calendar me-1"></i>
                                            <?= date('d/m/Y H:i', strtotime($post['created_at'])) ?>
                                        </small>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="<?= BASE_URL ?>/posts/detail?id=<?= $post['id'] ?>"
                                                class="btn btn-info"
                                                title="Xem chi tiết">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <?php if (canEdit($post['author_id'])): ?>
                                            <a href="<?= BASE_URL ?>/posts/edit?id=<?= $post['id'] ?>"
                                                class="btn btn-warning"
                                                title="Sửa">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <?php endif; ?>
                                            <?php if (canDelete($post['author_id'])): ?>
                                            <a href="<?= BASE_URL ?>/posts/delete?id=<?= $post['id'] ?>"
                                                class="btn btn-danger btn-delete"
                                                title="Xóa"
                                                data-title="<?= htmlspecialchars($post['title']) ?>">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                            <?php endif; ?>
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
                        Hiển thị <?= count($posts) ?> / <?= $total ?> bài viết
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
                        Không tìm thấy bài viết nào phù hợp với từ khóa "<?= htmlspecialchars($keyword) ?>"
                    <?php else: ?>
                        Chưa có bài viết nào. Hãy thêm bài viết mới!
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
            const postTitle = this.getAttribute('data-title');
            const truncatedTitle = postTitle.length > 50 ? postTitle.substring(0, 50) + '...' : postTitle;

            if (!confirm(`Bạn có chắc chắn muốn xóa bài viết:\n\n"${truncatedTitle}"\n\nHành động này không thể hoàn tác!`)) {
                e.preventDefault();
            }
        });
    });

    // Highlight search results
    const keyword = "<?= htmlspecialchars($keyword ?? '') ?>";
    if (keyword) {
        const titleLinks = document.querySelectorAll('.post-title-link strong');
        titleLinks.forEach(element => {
            const text = element.textContent;
            const regex = new RegExp(`(${keyword})`, 'gi');
            if (regex.test(text)) {
                element.innerHTML = text.replace(regex, '<mark>$1</mark>');
            }
        });
    }
</script>

<style>
    .posts-list .table th {
        font-weight: 600;
        color: #495057;
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
    }

    .posts-list .btn-group-sm .btn {
        padding: 0.25rem 0.5rem;
    }

    .posts-list .badge {
        font-size: 0.8rem;
        padding: 0.35em 0.65em;
        font-weight: 500;
    }

    /* Modern Pagination Styling */
    .posts-list .pagination {
        margin-bottom: 0;
        gap: 0.25rem;
    }

    .posts-list .page-item {
        margin: 0 2px;
    }

    .posts-list .page-link {
        color: #495057;
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        padding: 0.4rem 0.75rem;
        font-weight: 500;
        transition: all 0.2s ease;
        min-width: 38px;
        text-align: center;
    }

    .posts-list .page-link:hover {
        background-color: #f8f9fa;
        border-color: #adb5bd;
        color: #212529;
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
    }

    .posts-list .page-item.active .page-link {
        background-color: #0d6efd;
        border-color: #0d6efd;
        color: #fff;
        box-shadow: 0 2px 8px rgba(13, 110, 253, 0.3);
    }

    .posts-list .page-item.disabled .page-link {
        background-color: #f8f9fa;
        border-color: #dee2e6;
        color: #6c757d;
        cursor: not-allowed;
        opacity: 0.6;
    }

    .posts-list .pagination .page-link i {
        font-size: 0.875rem;
    }

    .posts-list mark {
        background-color: #fff3cd;
        padding: 0.1em 0.3em;
        border-radius: 3px;
    }

    .posts-list tbody tr:hover {
        background-color: #f8f9fa;
    }

    .posts-list .btn-info {
        color: #fff;
        background-color: #0dcaf0;
        border-color: #0dcaf0;
    }

    .posts-list .btn-info:hover {
        background-color: #0aa8cc;
        border-color: #0aa8cc;
    }

    .posts-list .post-title-link {
        color: #212529;
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .posts-list .post-title-link:hover {
        color: #0d6efd;
        text-decoration: underline;
    }

    .posts-list .post-title-link strong {
        font-weight: 600;
    }

    .posts-list .card {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        border: 1px solid #dee2e6;
    }
</style>