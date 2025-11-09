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
                        name="keyword" placeholder="Nhập thông tin tìm kiếm...">
                </div>
                <!-- Search button -->
                <div class="col-2">
                    <button class="btn btn-primary" type="submit">Tìm kiếm</button>
                </div>
            </div>
        </form>

        <!-- Posts listing table -->
        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th scope="col">STT</th>
                    <th scope="col">Tiêu đề</th>
                    <th scope="col">Nội dung</th>
                    <th scope="col">Ngày viết</th>
                    <th scope="col">Sửa</th>
                    <th scope="col">Xóa</th>
                </tr>
            </thead>
            <tbody>
                <?php $count = $offset + 1; ?>
                <?php foreach ($postDetail as $key => $item): ?>
                    <tr>
                        <th scope="row"><?= $count++  ?></th>
                        <td><?= $item['title']; ?></td>
                        <td><?= $item['content']; ?></td>
                        <td><?= $item['created_at']; ?></td>
                        <td><a href="<?= BASE_URL; ?>/posts/edit?id=<?= $item['id']; ?>" class="btn btn-warning"><i class="fa-solid fa-pencil"></i></a></td>
                        <td><a href="<?= BASE_URL; ?>/posts/delete?id=<?= $item['id']; ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa không')" class=" btn btn-danger"><i class="fa-solid fa-trash"></i></a></td>
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