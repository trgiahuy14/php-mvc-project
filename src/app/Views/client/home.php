<!-- Hero Section -->
<div class="bg-dark text-white py-5 mb-5">
    <div class="container py-4">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-3">Blog Lập Trình DevBlog</h1>
                <p class="lead mb-4">Nơi chia sẻ kiến thức, kinh nghiệm và xu hướng công nghệ mới nhất từ cộng đồng developer Việt Nam</p>
                <div class="d-flex gap-2">
                    <span class="badge bg-primary px-3 py-2"><i class="fas fa-code me-1"></i>PHP</span>
                    <span class="badge bg-success px-3 py-2"><i class="fab fa-js me-1"></i>JavaScript</span>
                    <span class="badge bg-info px-3 py-2"><i class="fas fa-database me-1"></i>Database</span>
                    <span class="badge bg-warning text-dark px-3 py-2"><i class="fas fa-server me-1"></i>DevOps</span>
                </div>
            </div>
            <div class="col-lg-4 text-center">
                <div class="bg-white text-dark rounded p-4">
                    <h3 class="text-primary"><i class="fas fa-chart-line"></i></h3>
                    <h4 class="mb-0"><?= number_format($statistics['total_posts'] ?? 0) ?></h4>
                    <small class="text-muted">Bài viết</small>
                    <hr>
                    <h4 class="mb-0"><?= number_format($statistics['total_authors'] ?? 0) ?></h4>
                    <small class="text-muted">Tác giả</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Featured Post -->
<?php if (!empty($featuredPosts)): ?>
    <?php $mainPost = $featuredPosts[0]; ?>
    <div class="container mb-5">
        <div class="row">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="position-relative">
                        <img src="<?= BASE_URL ?>/assets/img/code-banner.jpg" class="card-img-top" alt="Featured" style="height: 400px; object-fit: cover;">
                        <div class="position-absolute top-0 start-0 m-3">
                            <span class="badge bg-danger px-3 py-2"><i class="fas fa-star me-1"></i>BÀI VIẾT NỔI BẬT</span>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <a href="#" class="badge bg-primary text-decoration-none"><?= htmlspecialchars($mainPost['category_name'] ?? 'Tech', ENT_QUOTES, 'UTF-8') ?></a>
                        </div>
                        <h2 class="card-title mb-3">
                            <a href="<?= BASE_URL ?>/post?id=<?= $mainPost['id'] ?>" class="text-dark text-decoration-none">
                                <?= htmlspecialchars($mainPost['title'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                            </a>
                        </h2>
                        <p class="text-muted mb-3"><?= htmlspecialchars(mb_substr(strip_tags($mainPost['content'] ?? ''), 0, 200) . '...', ENT_QUOTES, 'UTF-8') ?></p>
                        <div class="d-flex align-items-center">
                            <img src="<?= BASE_URL ?>/assets/img/<?= htmlspecialchars($mainPost['author_avatar'] ?? 'default-avatar.jpg', ENT_QUOTES, 'UTF-8') ?>"
                                class="rounded-circle me-2" width="40" height="40" alt="Author">
                            <div>
                                <strong class="d-block"><?= htmlspecialchars($mainPost['author_name'] ?? 'Anonymous', ENT_QUOTES, 'UTF-8') ?></strong>
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i><?= date('d/m/Y', strtotime($mainPost['created_at'])) ?>
                                    <i class="fas fa-eye ms-2 me-1"></i><?= number_format($mainPost['views'] ?? 0) ?>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <!-- Trending Posts Sidebar moved here -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0"><i class="fas fa-fire me-2"></i>Trending</h5>
                    </div>
                    <div class="list-group list-group-flush">
                        <?php if (!empty($trendingPosts)): ?>
                            <?php foreach (array_slice($trendingPosts, 0, 3) as $index => $post): ?>
                                <a href="<?= BASE_URL ?>/post?id=<?= $post['id'] ?>" class="list-group-item list-group-item-action">
                                    <div class="d-flex">
                                        <span class="badge bg-danger me-2">#<?= $index + 1 ?></span>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1"><?= htmlspecialchars(mb_substr($post['title'] ?? '', 0, 60), ENT_QUOTES, 'UTF-8') ?></h6>
                                            <small class="text-muted">
                                                <i class="fas fa-eye me-1"></i><?= number_format($post['views'] ?? 0) ?>
                                            </small>
                                        </div>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Categories Widget -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-folder me-2"></i>Chủ đề</h5>
                    </div>
                    <div class="list-group list-group-flush">
                        <?php if (!empty($categories)): ?>
                            <?php foreach (array_slice($categories, 0, 5) as $category): ?>
                                <a href="<?= BASE_URL ?>/category?id=<?= $category['id'] ?>"
                                    class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                    <?= htmlspecialchars($category['name'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                                    <span class="badge bg-primary rounded-pill"><?= $category['post_count'] ?? 0 ?></span>
                                </a>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Latest Posts Section -->
<div class="container mb-5">
    <div class="row mb-4">
        <div class="col-12">
            <h3 class="border-bottom pb-3"><i class="fas fa-clock text-primary me-2"></i>Bài viết mới nhất</h3>
        </div>
    </div>

    <div class="row g-4">
        <?php if (!empty($latestPosts)): ?>
            <?php foreach ($latestPosts as $post): ?>
                <div class="col-lg-4 col-md-6">
                    <article class="card h-100 border-0 shadow-sm hover-lift">
                        <div class="position-relative">
                            <img src="<?= BASE_URL ?>/assets/img/code-bg.jpg"
                                class="card-img-top"
                                alt="<?= htmlspecialchars($post['title'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                style="height: 200px; object-fit: cover;">
                            <span class="position-absolute top-0 start-0 m-2 badge bg-primary">
                                <?= htmlspecialchars($post['category_name'] ?? 'Tech', ENT_QUOTES, 'UTF-8') ?>
                            </span>
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title mb-3">
                                <a href="<?= BASE_URL ?>/post?id=<?= $post['id'] ?>" class="text-dark text-decoration-none">
                                    <?= htmlspecialchars($post['title'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                                </a>
                            </h5>
                            <p class="card-text text-muted small flex-grow-1">
                                <?= htmlspecialchars(mb_substr(strip_tags($post['content'] ?? ''), 0, 100) . '...', ENT_QUOTES, 'UTF-8') ?>
                            </p>
                            <hr class="my-3">
                            <div class="d-flex align-items-center">
                                <img src="<?= BASE_URL ?>/assets/img/<?= htmlspecialchars($post['author_avatar'] ?? 'default-avatar.jpg', ENT_QUOTES, 'UTF-8') ?>"
                                    class="rounded-circle me-2" width="32" height="32" alt="Author">
                                <div class="flex-grow-1">
                                    <small class="d-block fw-bold"><?= htmlspecialchars($post['author_name'] ?? 'Anonymous', ENT_QUOTES, 'UTF-8') ?></small>
                                    <small class="text-muted">
                                        <?= date('d/m/Y', strtotime($post['created_at'])) ?> ·
                                        <i class="fas fa-eye"></i> <?= number_format($post['views'] ?? 0) ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </article>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5">
                <i class="fas fa-code fa-3x text-muted mb-3"></i>
                <p class="text-muted">Chưa có bài viết nào. Hãy là người đầu tiên chia sẻ kiến thức!</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
    .hero-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15) !important;
    }

    .list-group-item-action:hover {
        background-color: rgba(0, 123, 255, 0.05);
    }
</style>