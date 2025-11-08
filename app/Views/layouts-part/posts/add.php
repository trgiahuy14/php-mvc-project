<?php
if (!defined('_TRGIAHUY')) {
    die('Truy cập không hợp lệ');
}
$data = ['title' => 'Thêm mới khóa học'];
layout('header', $data);
layout('sidebar');

$msg = getSessionFlash('msg');
$msg_type = getSessionFlash('msg_type');
$oldData = getSessionFlash('oldData');
$errorsArr = getSessionFlash('errors');
?>

<div class="container add-user">
    <h2>Thêm mới khóa học</h2>
    <hr>
    <?php
    if (!empty($msg) && !empty($msg_type)) {
        getMsg($msg, $msg_type);
    }
    ?>
    <form action="" method="post" enctype="multipart/form-data">
        <div class="row">

            <!-- Post title -->
            <div class="col-12 pb-3">
                <label for="title">Tiêu đề bài viết</label>
                <input id="title" name="title" type="text" class="form-control"
                    value="<?php if (!empty($oldData)) {
                                echo oldData($oldData, 'title');
                            }  ?>"
                    placeholder="Nhập tiêu đề">
                <?php
                if (!empty($errorsArr)) {
                    echo formError($errorsArr, 'title');
                } ?>
            </div>

            <!-- Post content -->
            <div class="col-12 pb-3">
                <label for="content">Nội dung bài viết</label>
                <textarea id="content" name="content" type="text" class="form-control"
                    value="<?php if (!empty($oldData)) {
                                echo oldData($oldData, 'content');
                            } ?>"
                    placeholder="Nội dung"></textarea>
                <?php
                if (!empty($errorsArr)) {
                    echo formError($errorsArr, 'content');
                } ?>
            </div>

            <!-- Post tags-->
            <div class="col-12 pb-3">
                <label for="tags">Tags</label>
                <textarea id="tags" name="tags" type="text" class="form-control"
                    value="<?php if (!empty($oldData)) {
                                echo oldData($oldData, 'tags');
                            }  ?>"
                    placeholder="Nhập thẻ"></textarea>
            </div>

            <!-- Minutes read -->
            <div class="col-3 pb-3">
                <label for="minutes_read">Thời gian đọc</label>
                <input id="minutes_read" name="minutes_read" type="text" class="form-control"
                    value="<?php if (!empty($oldData)) {
                                echo oldData($oldData, 'minutes_read');
                            }  ?>"
                    placeholder="Thời gian đọc">
            </div>


            <!-- Views -->
            <div class="col-3 pb-3">
                <label for="views">Views</label>
                <input id="views" name="views" type="text" class="form-control"
                    value="<?php if (!empty($oldData)) {
                                echo oldData($oldData, 'views');
                            }  ?>"
                    placeholder="Nhập số lượt xem">
            </div>

            <!-- Comments -->
            <div class="col-3 pb-3">
                <label for="comments">Bình luận</label>
                <input id="comments" name="comments" type="text" class="form-control"
                    value="<?php if (!empty($oldData)) {
                                echo oldData($oldData, 'comments');
                            }  ?>"
                    placeholder="Nhập số lượt bình luận">
            </div>

            <!-- Shares -->
            <div class="col-3 pb-3">
                <label for="shares">Lượt chia sẻ</label>
                <input id="shares" name="shares" type="text" class="form-control"
                    value="<?php if (!empty($oldData)) {
                                echo oldData($oldData, 'shares');
                            }  ?>"
                    placeholder="Nhập số lượt chia sẻ">
            </div>


        </div>
        <button type="submit" class="btn btn-success">Xác nhận</button>
    </form>
</div>

<?php layout('footer') ?>