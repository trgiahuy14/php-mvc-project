<?php

declare(strict_types=1);

// Database
define('DB_HOST', $_ENV['DB_HOST']);
define('DB_NAME', $_ENV['DB_NAME']);
define('DB_USER', $_ENV['DB_USER']);
define('DB_PASS', $_ENV['DB_PASS']);
define('DB_DRIVER', 'mysql');

// URL
define('BASE_URL', $_ENV['BASE_URL']);
define('PUBLIC_URL', BASE_URL . '/public');
