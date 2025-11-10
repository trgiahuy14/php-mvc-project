<?php
$data = ['title' => 'Danh sách bài viết'];

layout('header', $data);
layout('sidebar');

$msg = getSessionFlash('msg');
$msg_type = getSessionFlash('msg_type');

// For debug
// echo '<pre>';
// print_r($postDetail);
// echo '</pre>';
?>

<div class="container grid-user">
    <div class="container-fluid">
        <a href="<?= BASE_URL ?>/posts/add" class="btn btn-success mb-3"><i class="fa-solid fa-plus"></i>Thêm mới</a>
        <?php
        if (!empty($msg) && !empty($msg_type)) {
            getMsg($msg, $msg_type);
        }
        ?>
        <!-- Posts search form -->
        <form class="mb-3" method="get">
            <div class="row">
                <div class="col-7">
                    <input class="form-control" type="text" value="<?= (!empty($keyword)) ? $keyword : false ?>"
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
                    <th style="width:280px">Tiêu đề</th>
                    <th style="width:140px">Tác giả</th>
                    <th style="width:180px">Tags</th>
                    <th style="width:100px" class="text-center">Lượt xem</th>
                    <th style="width:100px" class="text-center">Bình luận</th>
                    <th style="width:120px" class="text-center">Lượt chia sẻ</th>
                    <th style="width:120px" class="text-center">Cập nhật</th>
                    <th style="width:110px" class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $count = $offset + 1;
                foreach ($postDetail as $item): ?>
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

                        <!-- Shares -->
                        <td class="text-center"><?= shortNumber($item['shares']) ?></td>

                        <!-- Date modified -->
                        <td class="text-center text-nowrap"><?= date('d-m-Y', strtotime($date)) ?></td>

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
            </tbody>
        </table>


        <!-- Pagination -->
        <nav aria-label="Page navigation example">
            <ul class="pagination">

                <!-- Pagination: Previous button -->
                <?php if ($page > 1): ?>
                    <li class="page-item"><a class="page-link" href="?<?= $queryString; ?>page=<?= $page - 1 ?>">Trước</a></li>
                <?php endif; ?>

                <!-- Pagination: left side ellipsis-->
                <?php
                $start = $page - 1; // Tính vị trí bắt đầu 
                if ($start < 1) {
                    $start = 1;
                }
                ?>
                <?php if ($start > 1): ?>
                    <li class="page-item"><a class="page-link" href="?<?= $queryString; ?>page=<?= $page - 1 ?>">...</a></li>
                <?php endif;
                $end = $page + 1;
                if ($end > $maxPage) {
                    $end = $maxPage;
                }
                ?>

                <!-- Pagination: Page display -->
                <?php for ($i = $start; $i <= $end; $i++): ?>
                    <li class="page-item <?= ($page == $i) ? 'active' : false;  ?>"><a class="page-link"
                            href="?<?= $queryString; ?>page=<?= $i ?>"><?= $i; ?></a></li>
                <?php endfor; ?>

                <!-- Pagination: right side ellipsis -->
                <?php if ($end < $maxPage): ?>
                    <li class="page-item"><a class="page-link" href="?<?= $queryString; ?>page=<?= $page + 1 ?>">...</a></li>
                <?php endif;
                ?>

                <!-- Pagination: After button -->
                <?php if ($page < $maxPage): ?>
                    <li class="page-item"><a class="page-link" href="?<?= $queryString; ?>page=<?= $page + 1 ?>">Sau</a></li>
                <?php endif; ?>
            </ul>
        </nav>
        <!-- End Pagination -->
    </div>
</div>


<?php layout('footer') ?>