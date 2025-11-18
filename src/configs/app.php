<?php

declare(strict_types=1);

// Database configuration
define('DB_HOST', $_ENV['DB_HOST'] ?? 'localhost');
define('DB_PORT', $_ENV['DB_PORT'] ?? '3306');
define('DB_NAME', $_ENV['DB_NAME'] ?? 'php_mvc_db');
define('DB_USER', $_ENV['DB_USER'] ?? 'root');
define('DB_PASS', $_ENV['DB_PASS'] ?? '');
define('DB_DRIVER', $_ENV['DB_DRIVER'] ?? 'mysql');
define('DB_CHARSET', $_ENV['DB_CHARSET'] ?? 'utf8mb4');

// Application
define('APP_NAME', $_ENV['APP_NAME'] ?? 'VietNews CMS');
define('APP_DEBUG', filter_var($_ENV['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOLEAN));

// URLs
define('BASE_URL', $_ENV['BASE_URL']);          // http://localhost/vietnews-cms-php
define('PUBLIC_URL', BASE_URL);                 //  /public
define('APP_BASE_PATH', $_ENV['APP_BASE_PATH']); //  /vietnews-cms-php

// Path configuration
// define('ROOT_PATH', dirname(__DIR__, 2));  // Project root
define('APP_PATH', ROOT_PATH . '/src/app');
define('CORE_PATH', ROOT_PATH . '/src/Core');
define('STORAGE_PATH', ROOT_PATH . '/storage');
define('VIEW_PATH', APP_PATH . '/Views');
define('PUBLIC_PATH', ROOT_PATH . '/public');

// Set timezone
date_default_timezone_set($_ENV['APP_TIMEZONE'] ?? 'Asia/Ho_Chi_Minh');
