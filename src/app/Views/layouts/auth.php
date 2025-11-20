<?php

use Core\Session;

$msg = Session::getFlash('msg');
$msg_type = Session::getFlash('msg_type');

admin('header-auth', ['headerData' => $headerData]);
?>

<body class="login-page">
    <div class="login-box">
        <!-- Logo -->
        <div class="login-logo">
            <a href="<?= BASE_URL ?>">
                <i class="bi bi-code-square"></i>
                <b>DevBlog</b> CMS
            </a>
        </div>

        <!-- Card -->
        <div class="card">
            <div class="card-body login-card-body">
                <?php
                if (!empty($msg) && !empty($msg_type)) {
                    getMsg($msg, $msg_type);
                }

                echo $content;
                ?>
            </div>
        </div>

        <!-- Footer -->
        <div class="login-footer">
            <p class="mb-1">&copy; <?= date('Y'); ?> DEV-BLOG CMS | Phiên bản 1.0.0</p>
            <p class="footer-credits">Được phát triển bởi <a href="https://github.com/huydev14" target="_blank">huydev14</a> <a href="https://github.com/huydev14" target="_blank"><i class="bi bi-github"></i></a> <a href="https://facebook.com" target="_blank"><i class="bi bi-facebook"></i></a></p>
        </div>
    </div>