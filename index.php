<?php

// Load composer autoloader
require_once __DIR__ . '/vendor/autoload.php';

use Core\Router;

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Load configs
require_once __DIR__ . '/src/configs/app.php';

// error reporting (based on .env)
if ($_ENV['APP_DEBUG'] === 'true') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Load file .env
$env = parse_ini_file(__DIR__ . '/.env');
foreach ($env as $key => $value) putenv("$key=$value");

// Session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Init router  
$router = new Router();

// Load Routes
require_once __DIR__ . '/routes/web.php';

// Parse request
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Normalize URL
$basePath = APP_BASE_PATH ?? '';
if ($basePath && strpos($requestUri, $basePath) === 0) {
    $requestUri = substr($requestUri, strlen($basePath));
}
$requestUri = '/' . ltrim($requestUri, '/');

// Dispatch
try {
    $router->dispatch($_SERVER['REQUEST_METHOD'], $requestUri);
} catch (Exception $e) {
    // Log error
    error_log($e->getMessage());

    // Show error page
    if ($_ENV['APP_DEBUG'] === 'true') {
        echo "<pre>{$e->getMessage()}</pre>";
    } else {
        echo "Something went wrong. Please try again later.";
    }
}
