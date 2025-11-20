<?php

use Core\Session;

$msg = Session::getFlash('msg');
$msg_type = Session::getFlash('msg_type');
?>

<div class="container users-list">
    <div class="d-flex justify-content-between align-items-center mt-3 mb-1">
        <h2 class="mb-0">Danh sách người dùng</h2>
        <a href="<?= BASE_URL ?>/users/add" class="btn btn-success">
            <i class="fa fa-plus me-1"></i> Thêm người dùng
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
                        placeholder="Tìm kiếm theo username, email hoặc tên..."
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

    <!-- Users table -->
    <div class="card">
        <div class="card-body">
            <?php if (!empty($users)): ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">STT</th>
                                <th width="8%" class="text-center">Avatar</th>
                                <th width="22%">Thông tin</th>
                                <th width="12%">Username</th>
                                <th width="10%" class="text-center">Vai trò</th>
                                <th width="11%" class="text-center">Trạng thái</th>
                                <th width="8%" class="text-center">Bài viết</th>
                                <th width="12%">Đăng nhập cuối</th>
                                <th width="12%" class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $index => $user): ?>
                                <tr>
                                    <td><?= $offset + $index + 1 ?></td>
                                    <td class="text-center">
                                        <?php
                                        $avatarSrc = BASE_URL . '/assets/img/default-avatar.jpg';
                                        if (!empty($user['avatar'])) {
                                            // If avatar is just filename (e.g., 'default-avatar.jpg'), use assets/img path
                                            if (strpos($user['avatar'], '/') === false) {
                                                $avatarSrc = BASE_URL . '/assets/img/' . $user['avatar'];
                                            } else {
                                                // If avatar contains path (e.g., 'uploads/avatars/...'), use it directly
                                                $avatarSrc = BASE_URL . '/' . $user['avatar'];
                                            }
                                        }
                                        ?>
                                        <img
                                            src="<?= $avatarSrc ?>"
                                            alt="Avatar"
                                            class="rounded-circle user-avatar">
                                    </td>
                                    <td>
                                        <strong class="d-block"><?= htmlspecialchars($user['fullname'] ?? '') ?></strong>
                                        <small class="text-muted">
                                            <i class="fa fa-envelope me-1"></i>
                                            <?= htmlspecialchars($user['email'] ?? '') ?>
                                        </small>
                                    </td>
                                    <td>
                                        <code class="bg-light px-2 py-1 rounded">
                                            <?= htmlspecialchars($user['username'] ?? '') ?>
                                        </code>
                                    </td>
                                    <td class="text-center">
                                        <?php
                                        $roleColors = [
                                            'admin' => 'danger',
                                            'editor' => 'warning',
                                            'author' => 'info'
                                        ];
                                        $color = $roleColors[$user['role']] ?? 'secondary';
                                        ?>
                                        <span class="badge bg-<?= $color ?>">
                                            <?= ucfirst($user['role']) ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($user['status'] == 'active'): ?>
                                            <span class="badge bg-success">Hoạt động</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Không hoạt động</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-info text-dark">
                                            <?= $user['post_count'] ?? 0 ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if (!empty($user['last_login_at'])): ?>
                                            <small class="text-muted">
                                                <i class="fa fa-clock me-1"></i>
                                                <?= date('d/m/Y H:i', strtotime($user['last_login_at'])) ?>
                                            </small>
                                        <?php else: ?>
                                            <small class="text-muted fst-italic">Chưa đăng nhập</small>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="<?= BASE_URL ?>/users/view?id=<?= $user['id'] ?>"
                                                class="btn btn-info"
                                                title="Xem chi tiết">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <a href="<?= BASE_URL ?>/users/edit?id=<?= $user['id'] ?>"
                                                class="btn btn-warning"
                                                title="Sửa">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <?php
                                            $currentUserId = Session::get('user')['id'] ?? null;
                                            if ($currentUserId && $user['id'] != $currentUserId):
                                            ?>
                                                <a href="<?= BASE_URL ?>/users/delete?id=<?= $user['id'] ?>"
                                                    class="btn btn-danger btn-delete"
                                                    title="Xóa"
                                                    data-username="<?= htmlspecialchars($user['username']) ?>"
                                                    data-fullname="<?= htmlspecialchars($user['fullname']) ?>">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            <?php elseif ($currentUserId && $user['id'] == $currentUserId): ?>
                                                <button class="btn btn-secondary" disabled title="Không thể xóa chính mình">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            <?php else: ?>
                                                <a href="<?= BASE_URL ?>/users/delete?id=<?= $user['id'] ?>"
                                                    class="btn btn-danger btn-delete"
                                                    title="Xóa"
                                                    data-username="<?= htmlspecialchars($user['username']) ?>"
                                                    data-fullname="<?= htmlspecialchars($user['fullname']) ?>">
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
                        Hiển thị <?= count($users) ?> / <?= $total ?> người dùng
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
                        Không tìm thấy người dùng nào phù hợp với từ khóa "<?= htmlspecialchars($keyword) ?>"
                    <?php else: ?>
                        Chưa có người dùng nào. Hãy thêm người dùng mới!
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
            const username = this.getAttribute('data-username');
            const fullname = this.getAttribute('data-fullname');
            if (!confirm(`Bạn có chắc chắn muốn xóa người dùng "${fullname}" (@${username})?\n\nHành động này không thể hoàn tác!`)) {
                e.preventDefault();
            }
        });
    });
</script>

<style>
    .users-list .user-avatar {
        width: 45px;
        height: 45px;
        object-fit: cover;
        border: 2px solid #e9ecef;
    }

    .users-list .table th {
        font-weight: 600;
        color: #495057;
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
    }

    .users-list .btn-group-sm .btn {
        padding: 0.25rem 0.5rem;
    }

    .users-list .badge {
        font-size: 0.8rem;
        padding: 0.35em 0.65em;
        font-weight: 500;
    }

    /* Modern Pagination Styling */
    .users-list .pagination {
        margin-bottom: 0;
        gap: 0.25rem;
    }

    .users-list .page-item {
        margin: 0 2px;
    }

    .users-list .page-link {
        color: #495057;
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        padding: 0.4rem 0.75rem;
        font-weight: 500;
        transition: all 0.2s ease;
        min-width: 38px;
        text-align: center;
    }

    .users-list .page-link:hover {
        background-color: #f8f9fa;
        border-color: #adb5bd;
        color: #212529;
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
    }

    .users-list .page-item.active .page-link {
        background-color: #0d6efd;
        border-color: #0d6efd;
        color: #fff;
        box-shadow: 0 2px 8px rgba(13, 110, 253, 0.3);
    }

    .users-list .page-item.disabled .page-link {
        background-color: #f8f9fa;
        border-color: #dee2e6;
        color: #6c757d;
        cursor: not-allowed;
        opacity: 0.6;
    }

    .users-list .pagination .page-link i {
        font-size: 0.875rem;
    }

    .users-list .page-item.active .page-link {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }

    .users-list code {
        font-size: 0.875rem;
        color: #d63384;
    }

    .users-list tbody tr:hover {
        background-color: #f8f9fa;
    }

    .users-list .btn-info {
        color: #fff;
        background-color: #0dcaf0;
        border-color: #0dcaf0;
    }

    .users-list .btn-info:hover {
        background-color: #0aa8cc;
        border-color: #0aa8cc;
    }

    .users-list .card {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        border: 1px solid #dee2e6;
    }
</style>