<!-- Footer Section -->
<footer class="bg-dark text-white pt-5 pb-4 mt-5">
    <div class="container">
        <div class="row">
            <!-- About Section -->
            <div class="col-lg-4 col-md-6 mb-4">
                <h5 class="mb-3">
                    <i class="fas fa-code me-2 text-primary"></i>DevBlog<span class="text-primary">.vn</span>
                </h5>
                <p class="text-white-50">
                    Blog chia sẻ kiến thức lập trình, kinh nghiệm thực tế và xu hướng công nghệ mới nhất.
                    Nơi developer Việt kết nối và cùng nhau phát triển.
                </p>
                <div class="social-links mt-3">
                    <a href="#" class="btn btn-outline-primary btn-sm me-2" title="GitHub">
                        <i class="fab fa-github"></i>
                    </a>
                    <a href="#" class="btn btn-outline-primary btn-sm me-2" title="Facebook">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="btn btn-outline-primary btn-sm me-2" title="Twitter">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="btn btn-outline-primary btn-sm" title="YouTube">
                        <i class="fab fa-youtube"></i>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="col-lg-2 col-md-6 mb-4">
                <h5 class="text-primary mb-3">Khám phá</h5>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="<?= BASE_URL ?>" class="text-white-50 text-decoration-none hover-link">
                            <i class="fas fa-chevron-right me-2"></i>Trang chủ
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="<?= BASE_URL ?>/authors" class="text-white-50 text-decoration-none hover-link">
                            <i class="fas fa-chevron-right me-2"></i>Tác giả
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="<?= BASE_URL ?>/about" class="text-white-50 text-decoration-none hover-link">
                            <i class="fas fa-chevron-right me-2"></i>Giới thiệu
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="<?= BASE_URL ?>/login" class="text-white-50 text-decoration-none hover-link">
                            <i class="fas fa-chevron-right me-2"></i>Viết bài
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Categories -->
            <div class="col-lg-3 col-md-6 mb-4">
                <h5 class="text-primary mb-3">Chủ đề hot</h5>
                <ul class="list-unstyled">
                    <?php if (!empty($categories)): ?>
                        <?php foreach (array_slice($categories, 0, 6) as $category): ?>
                            <li class="mb-2">
                                <a href="<?= BASE_URL ?>/category?id=<?= $category['id'] ?>" class="text-white-50 text-decoration-none hover-link">
                                    <i class="fas fa-tag me-2 text-primary"></i><?= htmlspecialchars($category['name'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li class="text-white-50">Đang cập nhật...</li>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- Newsletter -->
            <div class="col-lg-3 col-md-6 mb-4">
                <h5 class="text-primary mb-3">Newsletter</h5>
                <p class="text-white-50 small">Đăng ký để nhận bài viết mới nhất về lập trình mỗi tuần</p>
                <div class="input-group mb-3">
                    <input type="email" class="form-control" placeholder="Email của bạn">
                    <button class="btn btn-primary" type="button">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
                <small class="text-white-50">
                    <i class="fas fa-shield-alt me-1"></i>Chúng tôi tôn trọng quyền riêng tư của bạn
                </small>
            </div>
        </div>

        <!-- Copyright -->
        <div class="row mt-4 pt-3 border-top border-secondary">
            <div class="col-md-6 text-center text-md-start">
                <p class="mb-0 text-white-50">
                    &copy; <?= date('Y') ?> DevBlog.vn - Made with <i class="fas fa-heart text-danger"></i> by Vietnamese Developers
                </p>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <a href="#" class="text-white-50 text-decoration-none me-3 hover-link">Privacy Policy</a>
                <a href="#" class="text-white-50 text-decoration-none me-3 hover-link">Terms of Use</a>
                <a href="#" class="text-white-50 text-decoration-none hover-link">RSS</a>
            </div>
        </div>
    </div>
</footer>

<!-- Back to Top Button -->
<a href="#" class="btn btn-primary btn-lg back-to-top">
    <i class="fas fa-arrow-up"></i>
</a>

<style>
    .back-to-top {
        position: fixed;
        bottom: 30px;
        right: 30px;
        display: none;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        text-align: center;
        line-height: 50px;
        z-index: 9999;
    }

    .back-to-top:hover {
        transform: translateY(-5px);
    }

    footer a:hover {
        color: var(--bs-primary) !important;
    }
</style>

<script>
    // Back to top button
    const backToTop = document.querySelector('.back-to-top');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 300) {
            backToTop.style.display = 'block';
        } else {
            backToTop.style.display = 'none';
        }
    });

    backToTop.addEventListener('click', (e) => {
        e.preventDefault();
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
</script>