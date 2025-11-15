<?php
$data = ['title' => 'Thêm bài viết'];

$msg = getSessionFlash('msg');
$msg_type = getSessionFlash('msg_type');
$oldData = getSessionFlash('oldData');
$errorsArr = getSessionFlash('errors');
?>

<div class="container add-user">
    <h2>Thêm bài viết</h2>
    <hr>
    <?php
    if (!empty($msg) && !empty($msg_type)) {
        getMsg($msg, $msg_type);
    }
    ?>
    <!-- Post creation form -->
    <form action="" method="post" enctype="multipart/form-data">
        <div class="row">
            <!-- Post title -->
            <div class="col-12 pb-3">
                <label for="title">Tiêu đề bài viết</label>
                <input
                    id="title"
                    name="title"
                    type="text"
                    class="form-control"
                    placeholder="Nhập tiêu đề"
                    value="<?= !empty($oldData) ? oldData($oldData, 'title') : null ?>">
                <!-- Error -->
                <?= !empty($errorsArr) ? formError($errorsArr, 'title') : null ?>
            </div>

            <!-- Post content -->
            <div class="col-12 pb-3">
                <label for="content">Nội dung bài viết</label>
                <textarea
                    id="content"
                    name="content"
                    class="form-control"
                    placeholder="Nội dung"><?= !empty($oldData) ? oldData($oldData, 'content') : null ?></textarea>
                <!-- Error -->
                <?= !empty($errorsArr) ? formError($errorsArr, 'content') : null ?>
            </div>

            <!-- Post tags-->
            <div class="col-12 pb-3">
                <label for="tags">Tags</label>
                <textarea
                    id="tags"
                    name="tags"
                    class="form-control"
                    placeholder="Nhập thẻ"><?= (!empty($oldData)) ? oldData($oldData, 'minutes_read') : null ?></textarea>
            </div>

            <hr>

            <!-- Minutes read -->
            <div class="col-3 pb-3">
                <label for="minutes_read">Thời gian đọc</label>
                <input
                    id="minutes_read"
                    name="minutes_read"
                    type="text"
                    class="form-control"
                    placeholder="Thời gian đọc"
                    value="<?= (!empty($oldData)) ? oldData($oldData, 'minutes_read') : null ?>">
            </div>


            <!-- Views -->
            <div class="col-3 pb-3">
                <label for="views">Views</label>
                <input
                    id="views"
                    name="views"
                    type="text"
                    class="form-control"
                    placeholder="Nhập số lượt xem"
                    value="<?= (!empty($oldData)) ? oldData($oldData, 'views') : null ?>">
            </div>

            <!-- Comments -->
            <div class="col-3 pb-3">
                <label for="comments">Bình luận</label>
                <input
                    id="comments"
                    name="comments"
                    type="text"
                    class="form-control"
                    placeholder="Nhập số lượt bình luận"
                    value="<?= (!empty($oldData)) ? oldData($oldData, 'views') : null ?>">
            </div>

            <!-- Shares -->
            <div class="col-3 pb-3">
                <label for="shares">Lượt chia sẻ</label>
                <input
                    id="shares"
                    name="shares"
                    type="text"
                    class="form-control"
                    placeholder="Nhập số lượt chia sẻ"
                    value="<?= (!empty($oldData)) ? oldData($oldData, 'views') : null ?>">
            </div>
        </div>
        <button type="submit" class="btn btn-success">Xác nhận</button>
        <button type="button" class="btn btn-secondary" id="btnBack">Quay lại</button>
    </form>
</div>

<script>
    const form = document.querySelector("form");
    const backBtn = document.getElementById("btnBack");

    let formChanged = false;

    // Theo dõi form, nếu người dùng nhập thì gán flag = true
    form.addEventListener("input", () => {
        formChanged = true;
    });

    backBtn.addEventListener("click", () => {
        if (formChanged) {
            const confirmLeave = confirm("Bạn có chắc muốn quay lại không? Dữ liệu chưa lưu sẽ bị mất.");
            if (!confirmLeave) return;
        }
        history.back(); // Quay lại trang trước đó
    });
</script>