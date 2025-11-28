<!--begin::Sidebar-->
<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <!--begin::Sidebar Brand-->
    <div class="sidebar-brand">
        <!--begin::Brand Link-->
        <a href="<?= BASE_URL ?>" class="brand-link">
            <!--begin::Brand Icon-->
            <i class="bi bi-code-square brand-icon"></i>
            <!--end::Brand Icon-->
            <!--begin::Brand Text-->
            <span class="brand-text"><b>DevBlog</b> CMS</span>
            <!--end::Brand Text-->
        </a>
        <!--end::Brand Link-->
    </div>
    <!--end::Sidebar Brand-->

    <!--begin::Sidebar Wrapper-->
    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <!--begin::Sidebar Menu-->
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">

                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="<?= BASE_URL ?>/dashboard" class="nav-link active">
                        <i class="nav-icon bi bi-speedometer2"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-header">QUẢN LÝ NỘI DUNG</li>

                <!-- Quản lý bài viết -->
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-file-post"></i>
                        <p>
                            Bài viết
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= BASE_URL ?>/posts" class="nav-link">
                                <i class="nav-icon bi bi-list-ul"></i>
                                <p>Danh sách bài viết </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= BASE_URL ?>/posts/add" class="nav-link">
                                <i class="nav-icon bi bi-plus-circle"></i>
                                <p>Tạo bài viết</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Quản lý danh mục (Admin & Editor) -->
                <?php if (can('categories.view')): ?>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-folder"></i>
                        <p>
                            Danh mục
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= BASE_URL ?>/categories" class="nav-link">
                                <i class="nav-icon bi bi-list-ul"></i>
                                <p>Danh sách danh mục</p>
                            </a>
                        </li>
                        <?php if (can('categories.create')): ?>
                        <li class="nav-item">
                            <a href="<?= BASE_URL ?>/categories/add" class="nav-link">
                                <i class="nav-icon bi bi-plus-circle"></i>
                                <p>Tạo mới danh mục</p>
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </li>
                <?php endif; ?>

                <!-- Quản lý bình luận (Admin & Editor) -->
                <?php if (can('comments.view')): ?>
                <li class="nav-item">
                    <a href="<?= BASE_URL ?>/comments" class="nav-link">
                        <i class="nav-icon bi bi-chat-left-text"></i>
                        <p>
                            Bình luận
                            <span class="badge text-bg-danger float-end">New</span>
                        </p>
                    </a>
                </li>
                <?php endif; ?>

                <li class="nav-header">QUẢN TRỊ HỆ THỐNG</li>

                <!-- Quản lý người dùng (Chỉ Admin) -->
                <?php if (can('users.view')): ?>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-people"></i>
                        <p>
                            Người dùng
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= BASE_URL ?>/users" class="nav-link">
                                <i class="nav-icon bi bi-list-ul"></i>
                                <p>Danh sách người dùng</p>
                            </a>
                        </li>
                        <?php if (can('users.create')): ?>
                        <li class="nav-item">
                            <a href="<?= BASE_URL ?>/users/add" class="nav-link">
                                <i class="nav-icon bi bi-plus-circle"></i>
                                <p>Tạo mới người dùng</p>
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </li>
                <?php endif; ?>

                <!-- Cài đặt (Chỉ Admin) -->
                <?php if (can('settings.view')): ?>
                <li class="nav-item">
                    <a href="<?= BASE_URL ?>/settings" class="nav-link">
                        <i class="nav-icon bi bi-gear"></i>
                        <p>Cài đặt</p>
                    </a>
                </li>
                <?php endif; ?>

            </ul>
            <!--end::Sidebar Menu-->
        </nav>
    </div>
    <!--end::Sidebar Wrapper-->
</aside>
<!--end::Sidebar-->

<style>
    /* Custom styles cho sidebar - không ảnh hưởng layout hiện tại */
    .nav-header {
        padding: 0.5rem 1rem;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: rgba(255, 255, 255, 0.5);
        margin-top: 1rem;
    }

    .nav-item .nav-link {
        transition: all 0.3s ease;
    }

    .nav-item .nav-link:hover {
        background-color: rgba(255, 255, 255, 0.1);
    }

    .nav-item .nav-link.active {
        background-color: rgba(255, 255, 255, 0.15);
        border-left: 3px solid #0d6efd;
    }

    .nav-treeview .nav-link {
        padding-left: 2rem;
    }

    .nav-icon {
        margin-right: 0.5rem;
        width: 1.2rem;
        text-align: center;
    }

    .badge {
        font-size: 0.65rem;
        padding: 0.25rem 0.5rem;
    }

    /* Smooth animation cho submenu */
    .nav-treeview {
        transition: all 0.3s ease-in-out;
    }

    /* Brand styling */
    .brand-link {
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 1rem;
    }

    .brand-icon {
        font-size: 32px;
        color: #007bff;
        transition: transform 0.3s ease;
    }

    .brand-link:hover .brand-icon {
        transform: scale(1.1);
    }

    .brand-text {
        font-size: 18px;
        font-weight: 300;
        color: rgba(255, 255, 255, 0.9);
    }

    .brand-text b {
        font-weight: 600;
    }
</style>