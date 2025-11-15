<?php
$data = ['title' => 'Danh sách bài viết'];

$msg = getSessionFlash('msg');
$msg_type = getSessionFlash('msg_type');
?>

<div class="container grid-user">
    <div class="container-fluid">
        <a href="<?= BASE_URL ?>/posts/add" class="btn btn-success mb-3"><i class="fa-solid fa-plus"></i>Thêm mới</a>
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
                        name="keyword" placeholder="Nhập tiêu đề bài viết để tìm kiếm...">
                </div>
                <!-- Search button -->
                <div class="col-2">
                    <button class="btn btn-primary" type="submit">Tìm kiếm</button>
                </div>
            </div>
        </form>

        <!-- Posts listing table -->
        <table class="table table-striped table-hover align-middle">
            <thead>
                <tr>
                    <th style="width:60px" class="text-center">STT</th>
                    <th style="width:420px">Tiêu đề</th>
                    <th style="width:140px">Tác giả</th>
                    <th style="width:180px">Tags</th>
                    <th style="width:100px" class="text-center">Lượt xem</th>
                    <th style="width:100px" class="text-center">Bình luận</th>
                    <th style="width:120px" class="text-center">Cập nhật</th>
                    <th style="width:110px" class="text-center">Action</th>
                </tr>
            </thead>

            <tbody>
                <!-- If no data exists -->
                <?php if (empty($posts)): ?>
                    <tr>
                        <td colspan="9" class="text-center">Không có bài viết nào.</td>
                    </tr>

                    <!-- Show data -->
                <?php else: ?>
                    <?php $count = $offset + 1;
                    foreach ($posts as $item): ?>
                        <?php $date = $item['updated_at'] ?: $item['created_at']; ?>
                        <tr>
                            <th class="text-center" scope="row"><?= $count++ ?></th>

                            <!-- Title -->
                            <td class="text-truncate" style="max-width:260px"
                                title="<?= htmlspecialchars($item['content'] ?? $item['title']) ?>">
                                <a href="<?= BASE_URL ?>/posts/edit?id=<?= $item['id'] ?>" class="link-dark text-decoration-none">
                                    <?= htmlspecialchars($item['title']) ?>
                                </a>
                            </td>

                            <!-- Author -->
                            <td class="text-nowrap"><?= htmlspecialchars($item['author']) ?></td>

                            <!-- Tags -->
                            <td class="text-truncate" style="max-width:200px"
                                title="<?= htmlspecialchars($item['tags']) ?>">
                                <?= htmlspecialchars($item['tags']) ?>
                            </td>

                            <!-- Views -->
                            <td class="text-center"><?= shortNumber($item['views']) ?></td>

                            <!-- Comments -->
                            <td class="text-center"><?= shortNumber($item['comments']) ?></td>

                            <!-- Date modified -->
                            <td class="text-center text-nowrap"><?= date('d-m-Y H:i', strtotime($date)) ?></td>

                            <td class="text-center">
                                <a href="<?= BASE_URL ?>/posts/edit?id=<?= $item['id'] ?>" class="btn btn-warning btn-sm">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <a href="<?= BASE_URL ?>/posts/delete?id=<?= $item['id'] ?>"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Bạn có chắc chắn muốn xoá không?')">
                                    <i class="fa fa-trash"></i>
                                </a>
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