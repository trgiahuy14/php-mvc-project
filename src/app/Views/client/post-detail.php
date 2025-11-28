<div class="container mt-4 mb-5">
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Post Header -->
            <article class="post-detail">
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb" class="mb-3">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/">Trang chủ</a></li>
                        <li class="breadcrumb-item">
                            <a href="<?= BASE_URL ?>/category?id=<?= $post['category_id'] ?>">
                                <?= htmlspecialchars($post['category_name'] ?? 'Uncategorized', ENT_QUOTES, 'UTF-8') ?>
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            <?= htmlspecialchars(mb_substr($post['title'], 0, 50), ENT_QUOTES, 'UTF-8') ?>...
                        </li>
                    </ol>
                </nav>

                <!-- Post Title -->
                <h1 class="post-title mb-3"><?= htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8') ?></h1>

                <!-- Post Meta -->
                <div class="post-meta d-flex align-items-center mb-4 pb-3 border-bottom">
                    <img src="<?= PUBLIC_URL ?>/assets/img/<?= htmlspecialchars($post['author_avatar'] ?? 'default-avatar.jpg', ENT_QUOTES, 'UTF-8') ?>"
                        class="rounded-circle me-3" width="50" height="50" alt="Author">
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center mb-1">
                            <strong class="me-2"><?= htmlspecialchars($post['author_name'] ?? 'Anonymous', ENT_QUOTES, 'UTF-8') ?></strong>
                            <span class="badge bg-primary"><?= htmlspecialchars($post['category_name'] ?? 'Tech', ENT_QUOTES, 'UTF-8') ?></span>
                        </div>
                        <small class="text-muted">
                            <i class="fas fa-calendar me-1"></i>
                            <?= date('d/m/Y H:i', strtotime($post['created_at'])) ?>
                            <span class="mx-2">•</span>
                            <i class="fas fa-eye me-1"></i>
                            <?= number_format($post['views'] ?? 0) ?> lượt xem
                        </small>
                    </div>
                </div>

                <!-- Featured Image -->
                <?php if (!empty($post['thumbnail'])): ?>
                    <div class="post-thumbnail mb-4">
                        <img src="<?= PUBLIC_URL ?>/uploads/<?= htmlspecialchars($post['thumbnail'], ENT_QUOTES, 'UTF-8') ?>"
                            class="img-fluid rounded shadow-sm"
                            alt="<?= htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8') ?>"
                            style="width: 100%; height: auto; max-height: 500px; object-fit: cover;">
                    </div>
                <?php endif; ?>

                <!-- Post Content -->
                <div class="post-content mb-5">
                    <?= $post['content'] ?>
                </div>

                <!-- Post Footer -->
                <div class="post-footer border-top pt-4 mb-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong class="me-2">Chia sẻ:</strong>
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode(BASE_URL . '/post?id=' . $post['id']) ?>"
                                target="_blank" class="btn btn-sm btn-outline-primary me-2">
                                <i class="fab fa-facebook-f"></i> Facebook
                            </a>
                            <a href="https://twitter.com/intent/tweet?url=<?= urlencode(BASE_URL . '/post?id=' . $post['id']) ?>&text=<?= urlencode($post['title']) ?>"
                                target="_blank" class="btn btn-sm btn-outline-info me-2">
                                <i class="fab fa-twitter"></i> Twitter
                            </a>
                            <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?= urlencode(BASE_URL . '/post?id=' . $post['id']) ?>&title=<?= urlencode($post['title']) ?>"
                                target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="fab fa-linkedin-in"></i> LinkedIn
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Author Info -->
                <div class="author-info card mb-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <img src="<?= PUBLIC_URL ?>/assets/img/<?= htmlspecialchars($post['author_avatar'] ?? 'default-avatar.jpg', ENT_QUOTES, 'UTF-8') ?>"
                                class="rounded-circle me-3" width="80" height="80" alt="Author">
                            <div>
                                <h5 class="mb-1"><?= htmlspecialchars($post['author_name'] ?? 'Anonymous', ENT_QUOTES, 'UTF-8') ?></h5>
                                <p class="text-muted mb-0">
                                    <small>Tác giả chuyên viết về lập trình và công nghệ</small>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Related Posts -->
                <?php if (!empty($relatedPosts)): ?>
                    <div class="related-posts">
                        <h3 class="border-bottom pb-3 mb-4">
                            <i class="fas fa-newspaper me-2"></i>Bài viết liên quan
                        </h3>
                        <div class="row g-3">
                            <?php foreach ($relatedPosts as $relatedPost): ?>
                                <div class="col-md-4">
                                    <div class="card h-100 border-0 shadow-sm">
                                        <?php
                                        $relatedThumbnail = !empty($relatedPost['thumbnail'])
                                            ? PUBLIC_URL . '/uploads/' . htmlspecialchars($relatedPost['thumbnail'], ENT_QUOTES, 'UTF-8')
                                            : PUBLIC_URL . '/assets/img/code-banner.jpg';
                                        ?>
                                        <img src="<?= $relatedThumbnail ?>"
                                            class="card-img-top"
                                            alt="<?= htmlspecialchars($relatedPost['title'], ENT_QUOTES, 'UTF-8') ?>"
                                            style="height: 150px; object-fit: cover;">
                                        <div class="card-body">
                                            <h6 class="card-title">
                                                <a href="<?= BASE_URL ?>/post?id=<?= $relatedPost['id'] ?>"
                                                    class="text-dark text-decoration-none">
                                                    <?= htmlspecialchars($relatedPost['title'], ENT_QUOTES, 'UTF-8') ?>
                                                </a>
                                            </h6>
                                            <small class="text-muted">
                                                <i class="fas fa-eye me-1"></i><?= number_format($relatedPost['views'] ?? 0) ?>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </article>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Trending Posts -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="fas fa-fire me-2"></i>Bài viết trending</h5>
                </div>
                <div class="list-group list-group-flush">
                    <?php if (!empty($trendingPosts)): ?>
                        <?php foreach (array_slice($trendingPosts, 0, 5) as $index => $trendingPost): ?>
                            <a href="<?= BASE_URL ?>/post?id=<?= $trendingPost['id'] ?>"
                                class="list-group-item list-group-item-action">
                                <div class="d-flex">
                                    <span class="badge bg-danger me-2">#<?= $index + 1 ?></span>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 small"><?= htmlspecialchars(mb_substr($trendingPost['title'] ?? '', 0, 60), ENT_QUOTES, 'UTF-8') ?></h6>
                                        <small class="text-muted">
                                            <i class="fas fa-eye me-1"></i><?= number_format($trendingPost['views'] ?? 0) ?>
                                        </small>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Categories -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-folder me-2"></i>Danh mục</h5>
                </div>
                <div class="list-group list-group-flush">
                    <?php if (!empty($categories)): ?>
                        <?php foreach (array_slice($categories, 0, 8) as $category): ?>
                            <a href="<?= BASE_URL ?>/category?id=<?= $category['id'] ?>"
                                class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <span><?= htmlspecialchars($category['name'] ?? '', ENT_QUOTES, 'UTF-8') ?></span>
                                <span class="badge bg-primary rounded-pill"><?= $category['post_count'] ?? 0 ?></span>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .post-title {
        font-size: 2.5rem;
        font-weight: 700;
        line-height: 1.2;
        color: #2c3e50;
    }

    .post-meta {
        font-size: 0.9rem;
    }

    .post-content {
        font-size: 1.1rem;
        line-height: 1.8;
        color: #333;
    }

    .post-content h2 {
        margin-top: 2rem;
        margin-bottom: 1rem;
        font-weight: 600;
        color: #2c3e50;
    }

    .post-content h3 {
        margin-top: 1.5rem;
        margin-bottom: 0.75rem;
        font-weight: 600;
        color: #34495e;
    }

    .post-content p {
        margin-bottom: 1.5rem;
    }

    .post-content ul,
    .post-content ol {
        margin-bottom: 1.5rem;
        padding-left: 2rem;
    }

    .post-content li {
        margin-bottom: 0.5rem;
    }

    .post-content code {
        background-color: #f8f9fa;
        padding: 0.2rem 0.4rem;
        border-radius: 3px;
        font-size: 0.9em;
        color: #e83e8c;
    }

    .post-content pre {
        background-color: #f8f9fa;
        padding: 1rem;
        border-radius: 5px;
        overflow-x: auto;
        margin-bottom: 1.5rem;
    }

    .post-content img {
        max-width: 100%;
        height: auto;
        border-radius: 5px;
        margin: 1.5rem 0;
    }

    .post-content blockquote {
        border-left: 4px solid #3498db;
        padding-left: 1rem;
        margin: 1.5rem 0;
        font-style: italic;
        color: #7f8c8d;
    }

    .author-info {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .author-info h5 {
        color: white;
    }

    .author-info .text-muted {
        color: rgba(255, 255, 255, 0.8) !important;
    }

    .card:hover {
        transform: translateY(-2px);
        transition: transform 0.3s ease;
    }

    .breadcrumb {
        background-color: transparent;
        padding: 0;
    }

    .breadcrumb-item a {
        color: #3498db;
        text-decoration: none;
    }

    .breadcrumb-item a:hover {
        text-decoration: underline;
    }

    @media (max-width: 768px) {
        .post-title {
            font-size: 1.75rem;
        }

        .post-content {
            font-size: 1rem;
        }
    }
</style>