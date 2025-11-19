<?php

use Core\Session;

$msg = Session::getFlash('msg');
$msg_type = Session::getFlash('msg_type');
?>

<div class="container grid-user">
    <div class="container-fluid">
        <a href="<?= BASE_URL ?>/users/add" class="btn btn-success mb-3"><i class="fa-solid fa-plus"></i>Thêm mới</a>
        <?php
        if (!empty($msg) && !empty($msg_type)) {
            getMsg($msg, $msg_type);
        }
        ?>

        <form class="mb-3" method="get">
            <div class="row">
                <div class="col-7">
                    <!-- Search input -->
                    <input class="form-control" type="text" value="<?= htmlspecialchars($keyword ?? '') ?>"
                        name="keyword" placeholder="Nhập tên hoặc email để tìm kiếm...">
                </div>
                <!-- Search button -->
                <div class="col-2">
                    <button class="btn btn-primary" type="submit">Tìm kiếm</button>
                </div>
            </div>
        </form>

        <!-- Users listing table -->
        <table class="table table-striped table-hover align-middle">
            <thead>
                <tr>
                    <th style="width:60px" class="text-center">STT</th>
                    <th style="width:240px">Người dùng</th>
                    <th style="width:220px">Email</th>
                    <th style="width:120px">Vai trò</th>
                    <th style="width:120px" class="text-center">Trạng thái</th>
                    <th style="width:100px" class="text-center">Số bài</th>
                    <th style="width:140px" class="text-center">Ngày tạo</th>
                    <th style="width:110px" class="text-center">Action</th>
                </tr>
            </thead>

            <tbody>
                <!-- If no data exists -->
                <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="8" class="text-center">Không có người dùng nào.</td>
                    </tr>

                    <!-- Show data -->
                <?php else: ?>
                    <?php $count = $offset + 1;
                    foreach ($users as $item): ?>
                        <tr>
                            <th class="text-center" scope="row"><?= $count++ ?></th>

                            <!-- User (Avatar + Name) -->
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="<?= htmlspecialchars($item['avatar'] ?? '/assets/images/default-avatar.png') ?>"
                                        alt="Avatar"
                                        class="rounded-circle me-2"
                                        style="width: 36px; height: 36px; object-fit: cover;">
                                    <div>
                                        <a href="<?= BASE_URL ?>/users/edit?id=<?= $item['id'] ?>"
                                            class="link-dark text-decoration-none fw-semibold">
                                            <?= htmlspecialchars($item['fullname'] ?? $item['username']) ?>
                                        </a>
                                        <div class="text-muted small">@<?= htmlspecialchars($item['username']) ?></div>
                                    </div>
                                </div>
                            </td>

                            <!-- Email -->
                            <td class="text-truncate" style="max-width:200px"
                                title="<?= htmlspecialchars($item['email']) ?>">
                                <?= htmlspecialchars($item['email']) ?>
                                <?php if (!empty($item['email_verified_at'])): ?>
                                    <i class="fa-solid fa-circle-check text-success" title="Email đã xác thực"></i>
                                <?php endif; ?>
                            </td>

                            <!-- Role -->
                            <td>
                                <?php if ($item['role'] === 'admin'): ?>
                                    <span class="badge bg-danger">Quản trị viên</span>
                                <?php else: ?>
                                    <span class="badge bg-primary">Người viết bài</span>
                                <?php endif; ?>
                            </td>

                            <!-- Status -->
                            <td class="text-center">
                                <?php
                                $statusColors = [
                                    'active' => 'success',
                                    'inactive' => 'secondary',
                                    'banned' => 'danger'
                                ];
                                $statusNames = [
                                    'active' => 'Hoạt động',
                                    'inactive' => 'Tạm ngưng',
                                    'banned' => 'Bị khóa'
                                ];
                                $status = $item['status'] ?? 'active';
                                $statusColor = $statusColors[$status] ?? 'secondary';
                                $statusName = $statusNames[$status] ?? ucfirst($status);
                                ?>
                                <span class="badge bg-<?= $statusColor ?>"><?= $statusName ?></span>
                            </td>

                            <!-- Post count -->
                            <td class="text-center">
                                <?php if (!empty($item['post_count']) && $item['post_count'] > 0): ?>
                                    <a href="<?= BASE_URL ?>/posts?author_id=<?= $item['id'] ?>"
                                        class="link-primary text-decoration-none">
                                        <?= shortNumber($item['post_count']) ?>
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted">0</span>
                                <?php endif; ?>
                            </td>

                            <!-- Created date -->
                            <td class="text-center text-nowrap"><?= date('d-m-Y H:i', strtotime($item['created_at'])) ?></td>

                            <!-- Actions -->
                            <td class="text-center">
                                <a href="<?= BASE_URL ?>/users/edit?id=<?= $item['id'] ?>"
                                    class="btn btn-warning btn-sm">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <?php if ($item['id'] != $currentUser['id']): ?>
                                    <a href="<?= BASE_URL ?>/users/delete?id=<?= $item['id'] ?>"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Bạn có chắc chắn muốn xoá người dùng này không?')">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                <?php else: ?>
                                    <button class="btn btn-danger btn-sm" disabled title="Không thể xóa chính mình">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>


        <!-- Pagination -->
        <?php
        $window = 2;
        $start = max(1, $page - $window);
        $end = min($maxPage, $page + $window);
        ?>

        <nav aria-label="Page navigation example">
            <ul class="pagination">

                <!-- Prev -->
                <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= max(1, $page - 1) ?><?= $queryString ?>">Trước</a>
                </li>

                <!-- First -->
                <?php if ($start > 1): ?>
                    <li class="page-item"><a class="page-link" href="?page=1<?= $queryString ?>">1</a></li>
                    <?php if ($start > 2): ?>
                        <li class="page-item disabled"><span class="page-link">...</span></li>
                    <?php endif; ?>
                <?php endif; ?>

                <!-- Pages -->
                <?php for ($i = $start; $i <= $end; $i++): ?>
                    <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?><?= $queryString ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>

                <!-- Last -->
                <?php if ($end < $maxPage): ?>
                    <?php if ($end < $maxPage - 1): ?>
                        <li class="page-item disabled"><span class="page-link">...</span></li>
                    <?php endif; ?>
                    <li class="page-item"><a class="page-link" href="?page=<?= $maxPage ?><?= $queryString ?>"><?= $maxPage ?></a></li>
                <?php endif; ?>

                <!-- Next -->
                <li class="page-item <?= $page >= $maxPage ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= min($maxPage, $page + 1) ?><?= $queryString ?>">Sau</a>
                </li>
            </ul>
        </nav>
        <!-- End Pagination -->
    </div>
</div>