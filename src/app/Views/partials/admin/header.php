<?php

use Core\Session;

$username = Session::get('username');
$fullname = Session::get('fullname');
$avatar = Session::get('avatar');
$role = Session::get('role');
?>

<!doctype html>
<html lang="vi">
<!--begin::Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?= $headerData['title'] ?? APP_NAME ?></title>
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="<?= BASE_URL ?>/assets/img/favicon.svg">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= BASE_URL ?>/assets/img/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= BASE_URL ?>/assets/img/favicon-16x16.png">
    <!--begin::Primary Meta Tags-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="title" content="<?= $headerData['title'] ?? APP_NAME ?>" />
    <meta name="author" content="huydev14" />
    <meta name="description" content="DEV-BLOG CMS - Hệ thống quản lý nội dung chuyên nghiệp" />
    <!--end::Primary Meta Tags-->
    <!--begin::Fonts-->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
        integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q="
        crossorigin="anonymous" />
    <!--end::Fonts-->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css"
        integrity="sha256-tZHrRjVqNSRyWg2wbppGnT833E/Ys0DHWGwT04GiqQg="
        crossorigin="anonymous" />
    <!--end::Third Party Plugin(OverlayScrollbars)-->
    <!--begin::Third Party Plugin(Bootstrap Icons)-->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        integrity="sha256-9kPW/n5nn53j4WMRYAxe9c1rCY96Oogo/MKSVdKzPmI="
        crossorigin="anonymous" />
    <!--end::Third Party Plugin(Bootstrap Icons)-->
    <!--begin::Required Plugin(AdminLTE)-->
    <link rel="stylesheet" href="<?= PUBLIC_URL; ?>/assets/css/adminlte.css?ver<?= rand() ?>" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <!--end::Required Plugin(AdminLTE)-->

    <style>
        /* User Management */
        .add-user {
            padding: 25px 50px;
        }

        /* Form Validation */
        .error {
            padding: 5px;
            font-style: italic;
            color: #dc3545;
        }

        /* Header Improvements */
        .app-header {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            backdrop-filter: blur(10px);
            background-color: rgba(255, 255, 255, 0.95) !important;
        }

        .app-header .navbar-nav {
            display: flex !important;
            align-items: center !important;
            gap: 0.5rem;
        }

        .app-header .nav-item {
            display: flex !important;
            align-items: center !important;
        }

        .app-header .nav-link {
            border-radius: 0.5rem;
            padding: 0.5rem 0.75rem !important;
            transition: all 0.2s ease;
            color: #6c757d;
            display: flex !important;
            align-items: center !important;
            justify-content: center;
        }

        .app-header .nav-link:hover {
            background-color: rgba(13, 110, 253, 0.1);
            color: #0d6efd;
        }

        .app-header .nav-link i {
            font-size: 1.25rem;
            line-height: 1;
        }

        /* User Menu Enhancements */
        .user-menu {
            display: flex !important;
            align-items: center !important;
        }

        .user-menu .nav-link {
            display: flex !important;
            align-items: center !important;
            gap: 0.75rem !important;
            padding: 0.5rem 1rem !important;
            border-radius: 2rem !important;
            background-color: #f8f9fa !important;
            transition: all 0.2s ease;
        }

        .user-menu .nav-link:hover {
            background-color: #e9ecef;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .user-menu .user-image {
            width: 36px !important;
            height: 36px !important;
            border: 2px solid #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            object-fit: cover !important;
            object-position: center !important;
        }

        .user-header img {
            width: 90px !important;
            height: 90px !important;
            object-fit: cover !important;
            object-position: center !important;
        }

        .user-menu .d-none.d-md-inline {
            font-weight: 500;
            color: #495057;
            line-height: 1;
        }

        /* User Dropdown */
        .user-menu .dropdown-menu {
            min-width: 280px;
            border: none;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            border-radius: 0.75rem;
            margin-top: 0.5rem;
            overflow: hidden;
        }

        .user-menu .user-header {
            padding: 2rem 1.5rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            text-align: center;
        }

        .user-menu .user-header img {
            width: 80px;
            height: 80px;
            border: 4px solid rgba(255, 255, 255, 0.3);
            margin-bottom: 1rem;
        }

        .user-menu .user-header p {
            margin: 0;
            color: #fff;
            font-size: 1rem;
            font-weight: 600;
        }

        .user-menu .user-header small {
            display: inline-block;
            margin-top: 0.25rem;
            padding: 0.25rem 0.75rem;
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 1rem;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .user-menu .user-body {
            padding: 1rem;
        }

        .user-menu .user-body .btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            border-radius: 0.5rem;
            font-weight: 500;
            transition: all 0.2s;
        }

        .user-menu .user-body .btn:hover {
            background-color: #0d6efd;
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(13, 110, 253, 0.3);
        }

        .user-menu .user-body .btn::before {
            content: "\F4DA";
            font-family: "bootstrap-icons";
        }

        .user-menu .user-footer {
            padding: 1rem;
            background-color: #f8f9fa;
            border-top: 1px solid #dee2e6;
        }

        .user-menu .user-footer .btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            border: 1px solid #dc3545;
            color: #dc3545;
            font-weight: 500;
            border-radius: 0.5rem;
            transition: all 0.2s;
        }

        .user-menu .user-footer .btn:hover {
            background-color: #dc3545;
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);
        }

        .user-menu .user-footer .btn::before {
            content: "\F1C1";
            font-family: "bootstrap-icons";
        }

        /* Footer Improvements */
        .app-footer {
            background: linear-gradient(to right, #f8f9fa, #ffffff);
            border-top: 1px solid #dee2e6;
            padding: 1.5rem 1rem;
            box-shadow: 0 -2px 4px rgba(0, 0, 0, 0.05);
        }

        .app-footer strong {
            color: #495057;
            font-weight: 600;
        }

        .app-footer .footer-link {
            color: #0d6efd;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s;
            position: relative;
        }

        .app-footer .footer-link::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background-color: #0d6efd;
            transition: width 0.2s;
        }

        .app-footer .footer-link:hover {
            color: #0a58ca;
        }

        .app-footer .footer-link:hover::after {
            width: 100%;
        }

        .app-footer .footer-social {
            display: inline-flex;
            gap: 0.75rem;
            margin-left: 1rem;
        }

        .app-footer .footer-social a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: #e9ecef;
            color: #495057;
            text-decoration: none;
            transition: all 0.2s;
        }

        .app-footer .footer-social a:hover {
            background-color: #0d6efd;
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 2px 8px rgba(13, 110, 253, 0.3);
        }

        /* Fullscreen Button */
        .nav-item .nav-link[data-lte-toggle="fullscreen"] {
            position: relative;
        }

        .nav-item .nav-link[data-lte-toggle="fullscreen"] i {
            font-size: 1.25rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .app-footer {
                text-align: center;
            }

            .app-footer .footer-social {
                margin-left: 0;
                margin-top: 0.5rem;
            }

            .user-menu .user-image {
                width: 32px;
                height: 32px;
            }
        }
    </style>
</head>
<!--end::Head-->
<!--begin::Body-->

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <!--begin::App Wrapper-->
    <div class="app-wrapper">
        <!--begin::Header-->
        <nav class="app-header navbar navbar-expand bg-body" style="height: 60px;">
            <!--begin::Container-->
            <div class="container-fluid" style="height: 100%;">
                <!--begin::Start Navbar Links-->
                <ul class="navbar-nav" style="height: 100%;">
                    <li class="nav-item" style="height: 100%;">
                        <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button" style="height: 100%; display: flex; align-items: center;">
                            <i class="bi bi-list"></i>
                        </a>
                    </li>
                </ul>
                <!--end::Start Navbar Links-->

                <!--begin::End Navbar Links-->
                <ul class="navbar-nav ms-auto" style="height: 100%;">
                    <!--begin::Notifications-->
                    <li class="nav-item" style="height: 100%; display: flex; align-items: center;">
                        <a class="nav-link" href="#" title="Thông báo" style="display: flex; align-items: center;">
                            <i class="bi bi-bell"></i>
                            <span class="badge badge-warning navbar-badge">3</span>
                        </a>
                    </li>
                    <!--end::Notifications-->

                    <!--begin::Fullscreen Toggle-->
                    <li class="nav-item" style="height: 100%; display: flex; align-items: center;">
                        <a class="nav-link" href="#" data-lte-toggle="fullscreen" title="Toàn màn hình" style="display: flex; align-items: center;">
                            <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
                            <i data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display: none"></i>
                        </a>
                    </li>
                    <!--end::Fullscreen Toggle-->

                    <!--begin::User Menu Dropdown-->
                    <li class="nav-item dropdown user-menu" style="height: 100%; display: flex; align-items: center;">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" style="display: flex; align-items: center; gap: 0.75rem; padding: 0.5rem 1rem; border-radius: 2rem; background-color: #f8f9fa; height: auto;">
                            <img
                                src="<?= PUBLIC_URL ?>/assets/img/<?= htmlspecialchars($avatar) ?>"
                                class="user-image rounded-circle shadow"
                                alt="User Image"
                                style="width: 36px; height: 36px; object-fit: cover; object-position: center;" />
                            <span class="d-none d-md-inline" style="line-height: 1; font-weight: 500;"><?= $fullname ? htmlspecialchars($fullname) : htmlspecialchars($username) ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                            <!--begin::User Image-->
                            <li class="user-header">
                                <img
                                    src="<?= PUBLIC_URL . '/assets/img/' . htmlspecialchars($avatar) ?>"
                                    class="rounded-circle shadow"
                                    alt="User Image" />
                                <p>
                                    @<?= htmlspecialchars($username) ?>
                                    <small><?= htmlspecialchars($role) ?></small>
                                </p>
                            </li>
                            <!--end::User Image-->

                            <!--begin::Menu Body-->
                            <li class="user-body">
                                <a href="<?= BASE_URL ?>/profile" class="btn btn-default btn-flat">
                                    Hồ sơ cá nhân
                                </a>
                            </li>
                            <!--end::Menu Body-->

                            <!--begin::Menu Footer-->
                            <li class="user-footer">
                                <a href="<?= BASE_URL ?>/logout" class="btn btn-default btn-flat">
                                    Đăng xuất
                                </a>
                            </li>
                            <!--end::Menu Footer-->
                        </ul>
                    </li>
                    <!--end::User Menu Dropdown-->
                </ul>
                <!--end::End Navbar Links-->
            </div>
            <!--end::Container-->
        </nav>
        <!--end::Header-->