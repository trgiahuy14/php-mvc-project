<?php

// Require PHP files from folder Configs
foreach (glob(__DIR__ . '/configs/*.php') as $filename) {
    require_once $filename;
}

// Require PHP files from folder core
foreach (glob(__DIR__ . '/core/*.php') as $filename) {
    require_once $filename;
}

// Require PHP files from folder Models
foreach (glob(__DIR__ . '/app/Models/*.php') as $filename) {
    require_once $filename;
}

// Require PHP files from folder Controllers
foreach (glob(__DIR__ . '/app/Controllers/*.php') as $filename) {
    require_once $filename;
}

// Router
$router = new Router();
// Require PHP files from folder Routers
foreach (glob(__DIR__ . '/routers/*.php') as $filename) {
    require_once $filename;
}
$projectName = '/php-mvc-project';
// Delete project name from URL
$requestUrl = str_replace($projectName, '', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$methodRes = $_SERVER['REQUEST_METHOD'];

$router->xulyPath($methodRes, $requestUrl);

// $controller = new UsersController();
// $controller->index();
