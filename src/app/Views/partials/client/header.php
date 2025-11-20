<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?= BASE_URL ?>">
            <i class="fas fa-code me-2"></i>DevBlog<span class="text-primary">.vn</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="<?= BASE_URL ?>">
                        <i class="fas fa-home me-1"></i>Trang chủ
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-folder-open me-1"></i>Chủ đề
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <?php if (!empty($categories)): ?>
                            <?php foreach ($categories as $category): ?>
                                <li>
                                    <a class="dropdown-item" href="<?= BASE_URL ?>/category?id=<?= $category['id'] ?>">
                                        <i class="fas fa-chevron-right me-2 text-primary"></i>
                                        <?= htmlspecialchars($category['name'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                                        <span class="badge bg-secondary ms-2"><?= $category['post_count'] ?? 0 ?></span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li><span class="dropdown-item text-muted">Chưa có chủ đề</span></li>
                        <?php endif; ?>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>/authors">
                        <i class="fas fa-users me-1"></i>Tác giả
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>/about">
                        <i class="fas fa-info-circle me-1"></i>Giới thiệu
                    </a>
                </li>
            </ul>
            <div class="d-flex">
                <form class="d-flex me-2" role="search">
                    <input class="form-control form-control-sm" type="search" placeholder="Tìm kiếm..." aria-label="Search">
                    <button class="btn btn-outline-light btn-sm ms-1" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
                <a class="btn btn-primary btn-sm" href="<?= BASE_URL ?>/login">
                    <i class="fas fa-sign-in-alt me-1"></i>Đăng nhập
                </a>
            </div>
        </div>
    </div>
</nav>