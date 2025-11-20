<?php

use Core\Session;

$msg = Session::getFlash('msg');
$msg_type = Session::getFlash('msg_type');
?>

<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="mt-3 mb-2">
                Dashboard
            </h1>
            <p class="text-muted mb-0">
                <?php
                $displayName = 'Admin';
                if (isset($currentUser) && is_array($currentUser)) {
                    $displayName = $currentUser['fullname'] ?? $currentUser['username'] ?? 'Admin';
                }
                ?>
                Xin chào, <strong><?= htmlspecialchars($displayName, ENT_QUOTES, 'UTF-8') ?></strong>!
                Chào mừng trở lại hệ thống quản trị.
            </p>
        </div>
    </div>

    <?php
    if (!empty($msg) && !empty($msg_type)) {
        getMsg($msg, $msg_type);
    }
    ?>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <!-- Total Posts Card -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 rounded-3 p-3">
                                <i class="fas fa-newspaper fa-2x text-primary"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1 text-uppercase small">Tổng bài viết</h6>
                            <h2 class="mb-0 fw-bold"><?= number_format($totalPosts ?? 0) ?></h2>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-top-0">
                    <a href="<?= BASE_URL ?>/posts" class="text-decoration-none small">
                        Xem tất cả <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Total Users Card -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-success bg-opacity-10 rounded-3 p-3">
                                <i class="fas fa-users fa-2x text-success"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1 text-uppercase small">Người dùng</h6>
                            <h2 class="mb-0 fw-bold"><?= number_format($totalUsers ?? 0) ?></h2>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-top-0">
                    <a href="<?= BASE_URL ?>/users" class="text-decoration-none small">
                        Quản lý <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Total Categories Card -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-warning bg-opacity-10 rounded-3 p-3">
                                <i class="fas fa-folder fa-2x text-warning"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1 text-uppercase small">Danh mục</h6>
                            <h2 class="mb-0 fw-bold"><?= number_format($totalCategories ?? 0) ?></h2>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-top-0">
                    <a href="<?= BASE_URL ?>/categories" class="text-decoration-none small">
                        Xem danh sách <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Views Card -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-info bg-opacity-10 rounded-3 p-3">
                                <i class="fas fa-eye fa-2x text-info"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1 text-uppercase small">Lượt xem</h6>
                            <h2 class="mb-0 fw-bold">
                                <?php
                                $totalViews = 0;
                                foreach ($recentPosts as $post) {
                                    $totalViews += (int)($post['views'] ?? 0);
                                }
                                echo number_format($totalViews);
                                ?>
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-top-0">
                    <span class="text-muted small">
                        <i class="fas fa-chart-line me-1"></i> Thống kê tổng hợp
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Posts & Users Section -->
    <div class="row">
        <div class="col-lg-8 mb-4">
            <!-- Recent Posts -->

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-clock me-2 text-primary"></i>Bài viết gần đây
                        </h5>
                        <a href="<?= BASE_URL ?>/posts/add" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus me-1"></i> Thêm mới
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <?php if (!empty($recentPosts)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0">Tiêu đề</th>
                                        <th class="border-0">Tác giả</th>
                                        <th class="border-0">Danh mục</th>
                                        <th class="border-0">Lượt xem</th>
                                        <th class="border-0">Ngày tạo</th>
                                        <th class="border-0 text-end">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recentPosts as $post): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="me-2">
                                                        <i class="fas fa-file-alt text-primary"></i>
                                                    </div>
                                                    <div>
                                                        <div class="fw-semibold">
                                                            <?= htmlspecialchars(mb_substr($post['title'] ?? '', 0, 50) . (mb_strlen($post['title'] ?? '') > 50 ? '...' : ''), ENT_QUOTES, 'UTF-8') ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">
                                                    <?= htmlspecialchars($post['author_name'] ?? 'N/A', ENT_QUOTES, 'UTF-8') ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">
                                                    <?= htmlspecialchars($post['category_name'] ?? 'N/A', ENT_QUOTES, 'UTF-8') ?>
                                                </span>
                                            </td>
                                            <td>
                                                <i class="fas fa-eye text-muted me-1"></i>
                                                <?= number_format($post['views'] ?? 0) ?>
                                            </td>
                                            <td class="text-muted small">
                                                <?= date('d/m/Y', strtotime($post['created_at'])) ?>
                                            </td>
                                            <td class="text-end">
                                                <a href="<?= BASE_URL ?>/posts/edit?id=<?= $post['id'] ?>"
                                                    class="btn btn-sm btn-outline-primary"
                                                    title="Chỉnh sửa">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Chưa có bài viết nào</p>
                            <a href="<?= BASE_URL ?>/posts/add" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i> Tạo bài viết đầu tiên
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
                <?php if (!empty($recentPosts)): ?>
                    <div class="card-footer bg-white border-top text-center">
                        <a href="<?= BASE_URL ?>/posts" class="text-decoration-none">
                            Xem tất cả bài viết <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Recent Users -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-white border-bottom py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-user-plus me-2 text-success"></i>Người dùng mới
                        </h5>
                        <a href="<?= BASE_URL ?>/users/add" class="btn btn-sm btn-success">
                            <i class="fas fa-plus me-1"></i> Thêm mới
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <?php if (!empty($recentUsers)): ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($recentUsers as $user): ?>
                                <div class="list-group-item list-group-item-action">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <?php if (!empty($user['avatar'])): ?>
                                                <img src="<?= BASE_URL ?>/assets/img/<?= htmlspecialchars($user['avatar'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                                    alt="Avatar"
                                                    class="rounded-circle"
                                                    style="width: 45px; height: 45px; object-fit: cover;">
                                            <?php else: ?>
                                                <div class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center"
                                                    style="width: 45px; height: 45px;">
                                                    <span class="fw-bold text-success">
                                                        <?= strtoupper(substr($user['fullname'] ?? $user['username'], 0, 1)) ?>
                                                    </span>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="mb-0">
                                                        <?= htmlspecialchars($user['fullname'] ?? $user['username'], ENT_QUOTES, 'UTF-8') ?>
                                                    </h6>
                                                    <small class="text-muted">
                                                        <i class="fas fa-envelope me-1"></i>
                                                        <?= htmlspecialchars($user['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                                                    </small>
                                                </div>
                                                <div class="text-end">
                                                    <span class="badge bg-<?= $user['role'] === 'admin' ? 'danger' : ($user['role'] === 'editor' ? 'warning' : 'info') ?>">
                                                        <?= htmlspecialchars(ucfirst($user['role'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
                                                    </span>
                                                    <div class="small text-muted mt-1">
                                                        <i class="fas fa-calendar-alt me-1"></i>
                                                        <?= date('d/m/Y', strtotime($user['created_at'])) ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex-shrink-0 ms-2">
                                            <a href="<?= BASE_URL ?>/users/edit?id=<?= $user['id'] ?>"
                                                class="btn btn-sm btn-outline-success"
                                                title="Chỉnh sửa">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Chưa có người dùng nào</p>
                            <a href="<?= BASE_URL ?>/users/add" class="btn btn-success">
                                <i class="fas fa-plus me-1"></i> Thêm người dùng đầu tiên
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
                <?php if (!empty($recentUsers)): ?>
                    <div class="card-footer bg-white border-top text-center">
                        <a href="<?= BASE_URL ?>/users" class="text-decoration-none">
                            Xem tất cả người dùng <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Quick Actions & Info -->
        <div class="col-lg-4 mb-4">
            <!-- Quick Actions Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-bolt me-2 text-warning"></i>Thao tác nhanh
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="<?= BASE_URL ?>/posts/add" class="btn btn-outline-primary">
                            <i class="fas fa-plus-circle me-2"></i>Tạo bài viết mới
                        </a>
                        <a href="<?= BASE_URL ?>/categories/add" class="btn btn-outline-success">
                            <i class="fas fa-folder-plus me-2"></i>Thêm danh mục
                        </a>
                        <a href="<?= BASE_URL ?>/users/add" class="btn btn-outline-info">
                            <i class="fas fa-user-plus me-2"></i>Thêm người dùng
                        </a>
                        <a href="<?= BASE_URL ?>/posts" class="btn btn-outline-secondary">
                            <i class="fas fa-list me-2"></i>Quản lý bài viết
                        </a>
                    </div>
                </div>
            </div>

            <!-- Account Info Card -->
            <?php
            // Ưu tiên lấy từ Session, nếu không có thì lấy từ $currentUser
            $user = Session::get('userData') ?? $currentUser ?? null;
            ?>
            <?php if ($user): ?>
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="mb-0">
                            <i class="fas fa-user-circle me-2 text-success"></i>Thông tin tài khoản
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <?php
                            $avatarPath = BASE_URL . '/assets/img/default-avatar.jpg';
                            if (!empty($user['avatar'])) {
                                if (strpos($user['avatar'], '/') === false) {
                                    $avatarPath = BASE_URL . '/assets/img/' . $user['avatar'];
                                } else {
                                    $avatarPath = BASE_URL . '/' . $user['avatar'];
                                }
                            }
                            ?>
                            <img src="<?= $avatarPath ?>"
                                alt="Avatar"
                                class="rounded-circle mb-2"
                                style="width: 80px; height: 80px; object-fit: cover; border: 3px solid #e9ecef;">

                            <h6 class="mb-1"><?= htmlspecialchars($user['fullname'] ?? $user['username'] ?? 'Admin', ENT_QUOTES, 'UTF-8') ?></h6>
                            <span class="badge bg-primary"><?= htmlspecialchars(ucfirst($user['role'] ?? 'user'), ENT_QUOTES, 'UTF-8') ?></span>
                        </div>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2 d-flex align-items-center">
                                <i class="fas fa-envelope text-muted me-2" style="width: 20px;"></i>
                                <small class="text-truncate"><?= htmlspecialchars($user['email'] ?? '', ENT_QUOTES, 'UTF-8') ?></small>
                            </li>
                            <?php if (!empty($user['phone'])): ?>
                                <li class="mb-2 d-flex align-items-center">
                                    <i class="fas fa-phone text-muted me-2" style="width: 20px;"></i>
                                    <small><?= htmlspecialchars($user['phone'], ENT_QUOTES, 'UTF-8') ?></small>
                                </li>
                            <?php endif; ?>
                            <li class="mb-2 d-flex align-items-center">
                                <i class="fas fa-calendar text-muted me-2" style="width: 20px;"></i>
                                <small>Tham gia: <?= !empty($user['created_at']) ? date('d/m/Y', strtotime($user['created_at'])) : 'N/A' ?></small>
                            </li>
                            <?php if (!empty($user['last_login_at'])): ?>
                                <li class="d-flex align-items-center">
                                    <i class="fas fa-clock text-muted me-2" style="width: 20px;"></i>
                                    <small>Đăng nhập: <?= date('d/m/Y H:i', strtotime($user['last_login_at'])) ?></small>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                    <div class="card-footer bg-white border-top text-center">
                        <a href="<?= BASE_URL ?>/users/edit?id=<?= $user['id'] ?? '' ?>" class="text-decoration-none">
                            <i class="fas fa-user-edit me-1"></i> Chỉnh sửa hồ sơ
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    .card {
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.1) !important;
    }

    .table tbody tr {
        transition: background-color 0.2s;
    }

    .table tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.02);
    }

    .btn {
        transition: all 0.2s;
    }

    .btn:hover {
        transform: translateY(-1px);
    }
</style>