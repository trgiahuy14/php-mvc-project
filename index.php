<?php
session_start();
// Load file .env
$env = parse_ini_file(__DIR__ . '/.env');
foreach ($env as $key => $value) {
    putenv("$key=$value");
}

// Require Configs
foreach (glob(__DIR__ . '/configs/*.php') as $filename) {
    require_once $filename;
}

// Require Core
foreach (glob(__DIR__ . '/core/*.php') as $filename) {
    require_once $filename;
}

$cor = new CoreModel();

$getInfo = $cor->getUserInfo();

setSession('getInfo', $getInfo);


// Require PHPmailer
foreach (glob(__DIR__ . '/core/mailer/*.php') as $filename) {
    require_once $filename;
}

// Require Models
foreach (glob(__DIR__ . '/app/Models/*.php') as $filename) {
    require_once $filename;
}

// Require Controllers
foreach (glob(__DIR__ . '/app/Controllers/*.php') as $filename) {
    require_once $filename;
}

foreach (glob(__DIR__ . '/app/Controllers/clients/*.php') as $filename) {
    require_once $filename;
}

// Require Routers
$router = new Router();

foreach (glob(__DIR__ . '/routers/*.php') as $filename) {
    require_once $filename;
}
$projectName = '/php-mvc-project';
// Delete project name from URL
$requestUrl = str_replace($projectName, '', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$methodRes = $_SERVER['REQUEST_METHOD'];

$router->xulyPath($methodRes, $requestUrl);
