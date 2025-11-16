<?php

/**
 * VietNews CMS - Front Controller
 */

// Load Composer Autoloader
require_once __DIR__ . '/vendor/autoload.php';

use Core\Router;

// Load Environment Variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Error Reporting (based on environment)
if ($_ENV['APP_DEBUG'] === 'true') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Set Timezone
date_default_timezone_set('Asia/Ho_Chi_Minh');

/** Load file .env  */
$env = parse_ini_file(__DIR__ . '/.env');
foreach ($env as $key => $value) putenv("$key=$value");

// Session Configuration
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0); // Set to 1 if using HTTPS
session_start();

// Load Configurations
require_once __DIR__ . '/src/configs/app.php';

/** Init router */
$router = new Router();

// Load Routes
require_once __DIR__ . '/routes/web.php';

/**  Request handle */
$requestUrl = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestUrl = str_replace(APP_BASE_PATH, '', $requestUrl);

// Run Application
try {
    $router->dispatch($_SERVER['REQUEST_METHOD'], $requestUrl);
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

// /** Load current user for header */
// $currentUser = null;
// $token = getSession('token_login');

// if ($token) {
//     $modelToken = new TokenModel();
//     $rowToken   = $modelToken->findByToken($token);

//     if ($rowToken) {
//         $modelUser = new UserModel();
//         $currentUser = $modelUser->getUserById((int)$rowToken['user_id']);
//     }
// }

// setSession('current_user', $currentUser);
