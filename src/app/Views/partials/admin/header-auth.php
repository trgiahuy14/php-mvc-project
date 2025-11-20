<!doctype html>
<html lang="en">
<!--begin::Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?= $headerData['title'] ?? APP_NAME ?></title>
    <!-- Favicons -->
    <link rel="icon" type="image/svg+xml" href="<?= BASE_URL ?>/assets/img/favicon.svg">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= BASE_URL ?>/assets/img/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= BASE_URL ?>/assets/img/favicon-16x16.png">
    <!--begin::Primary Meta Tags-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="title" content="AdminLTE v4 | Dashboard" />
    <meta name="author" content="ColorlibHQ" />

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
    <link rel="stylesheet" href="<?= PUBLIC_URL ?>/assets/css/adminlte.css?ver=<?= rand(); ?>" />

    <!--end::Required Plugin(AdminLTE)-->

    <style>
        body.login-page {
            background: #f4f6f9;
            font-family: 'Source Sans 3', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }

        .login-box {
            width: 100%;
            max-width: 400px;
        }

        .login-logo {
            font-size: 35px;
            text-align: center;
            margin-bottom: 25px;
            font-weight: 300;
        }

        .login-logo a {
            color: #495057;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .login-logo i {
            font-size: 40px;
            color: #007bff;
        }

        .login-logo b {
            font-weight: 600;
        }

        .card {
            background: #fff;
            border: 0;
            border-radius: 8px;
            box-shadow: 0 0 1px rgba(0, 0, 0, .125), 0 1px 3px rgba(0, 0, 0, .2);
            margin-bottom: 1rem;
        }

        .card-body {
            padding: 25px;
        }

        .login-card-body {
            padding: 30px;
        }

        .login-footer {
            text-align: center;
            color: #6c757d;
            font-size: 13px;
            margin-top: 20px;
            line-height: 1.6;
        }

        .login-footer p {
            margin: 0;
        }

        .login-footer .mb-1 {
            margin-bottom: 0.25rem;
        }

        .footer-credits {
            font-size: 12px;
            color: #868e96;
        }

        .footer-credits a {
            color: #007bff;
            text-decoration: none;
            transition: color 0.15s ease-in-out;
        }

        .footer-credits a:hover {
            color: #0056b3;
            text-decoration: underline;
        }

        .footer-credits i {
            font-size: 14px;
            margin-left: 5px;
        }

        .auth-header {
            margin-bottom: 30px;
            text-align: center;
        }

        .auth-title {
            font-size: 24px;
            font-weight: 500;
            color: #212529;
            margin-bottom: 15px;
        }

        .auth-subtitle {
            font-size: 14px;
            color: #6c757d;
            margin-bottom: 20px;
        }

        .auth-form {
            width: 100%;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: #495057;
            margin-bottom: 8px;
        }

        .form-label i {
            margin-right: 6px;
            color: #007bff;
            font-size: 16px;
        }

        .form-control {
            display: block;
            width: 100%;
            padding: 10px 12px;
            font-size: 14px;
            font-weight: 400;
            line-height: 1.5;
            color: #495057;
            background-color: #fff;
            border: 1px solid #ced4da;
            border-radius: 4px;
            transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
        }

        .form-control:focus {
            color: #495057;
            background-color: #fff;
            border-color: #80bdff;
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, .25);
        }

        .form-control::placeholder {
            color: #6c757d;
            opacity: 1;
        }

        .password-input-wrapper {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #718096;
            cursor: pointer;
            padding: 5px;
            transition: color 0.3s ease;
        }

        .password-toggle:hover {
            color: #667eea;
        }

        .form-text {
            display: block;
            font-size: 12px;
            color: #718096;
            margin-top: 5px;
        }

        .form-options {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            margin-bottom: 20px;
        }

        .form-check {
            display: flex;
            align-items: center;
        }

        .form-check-input {
            width: 18px;
            height: 18px;
            margin-right: 8px;
            cursor: pointer;
        }

        .form-check-label {
            font-size: 14px;
            color: #495057;
            cursor: pointer;
            margin: 0;
        }

        .forgot-link {
            font-size: 14px;
            color: #007bff;
            text-decoration: none;
            transition: color 0.15s ease-in-out;
        }

        .forgot-link:hover {
            color: #0056b3;
            text-decoration: underline;
        }

        .btn-auth {
            width: 100%;
            padding: 10px 16px;
            font-size: 16px;
            font-weight: 400;
            line-height: 1.5;
            border: 1px solid transparent;
            border-radius: 4px;
            cursor: pointer;
            transition: all .15s ease-in-out;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-primary.btn-auth {
            color: #fff;
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary.btn-auth:hover {
            background-color: #0069d9;
            border-color: #0062cc;
        }

        .btn-secondary.btn-auth {
            color: #fff;
            background-color: #6c757d;
            border-color: #6c757d;
        }

        .btn-secondary.btn-auth:hover {
            background-color: #5a6268;
            border-color: #545b62;
        }

        .auth-divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 20px 0;
        }

        .auth-divider::before,
        .auth-divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #dee2e6;
        }

        .auth-divider span {
            padding: 0 10px;
            font-size: 13px;
            color: #6c757d;
        }

        .auth-footer-links {
            text-align: center;
            margin-top: 20px;
        }

        .auth-footer-links p {
            font-size: 14px;
            color: #6c757d;
            margin: 0;
        }

        .auth-link {
            color: #007bff;
            text-decoration: none;
            transition: color 0.15s ease-in-out;
        }

        .auth-link:hover {
            color: #0056b3;
            text-decoration: underline;
        }

        /* Activation Result Styles */
        .activation-result {
            text-align: center;
            padding: 30px 20px;
        }

        .activation-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 25px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .activation-result.success .activation-icon {
            background-color: #28a745;
        }

        .activation-result.error .activation-icon {
            background-color: #dc3545;
        }

        .activation-result.warning .activation-icon {
            background-color: #ffc107;
        }

        .activation-icon i {
            font-size: 40px;
            color: white;
        }

        .activation-title {
            font-size: 24px;
            font-weight: 500;
            color: #212529;
            margin-bottom: 15px;
        }

        .activation-message {
            font-size: 14px;
            color: #6c757d;
            line-height: 1.6;
            margin-bottom: 25px;
        }

        .activation-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .activation-buttons .btn-auth {
            width: auto;
            min-width: 180px;
        }

        /* Error Messages */
        .text-danger {
            color: #dc3545;
            font-size: 13px;
            margin-top: 5px;
            display: block;
        }

        .alert {
            position: relative;
            padding: 12px 20px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
            font-size: 14px;
        }

        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }

        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }

        .alert-warning {
            color: #856404;
            background-color: #fff3cd;
            border-color: #ffeeba;
        }

        /* Responsive Design */
        @media (max-width: 576px) {
            .login-box {
                max-width: 100%;
            }

            .login-card-body {
                padding: 20px;
            }

            .form-options {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .activation-buttons {
                flex-direction: column;
            }

            .activation-buttons .btn-auth {
                width: 100%;
            }
        }
    </style>

</head>
<!--end::Head-->