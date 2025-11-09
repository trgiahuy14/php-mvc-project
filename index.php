<?php
define("APP_KEY", true); // mark app bootstrap point

session_start();

// Load file .env 
$env = parse_ini_file(__DIR__ . '/.env');
foreach ($env as $key => $value) putenv("$key=$value");

// Load configs, core, models, controller.
foreach (glob(__DIR__ . '/configs/*.php') as $f) require_once $f;
foreach (glob(__DIR__ . '/core/*.php') as $f) require_once $f;
foreach (glob(__DIR__ . '/core/mailer/*.php') as $f) require_once $f;
foreach (glob(__DIR__ . '/app/Models/*.php') as $f) require_once $f;
foreach (glob(__DIR__ . '/app/Controllers/*.php') as $f) require_once $f;
foreach (glob(__DIR__ . '/app/Controllers/clients/*.php') as $f) require_once $f;

// Init core
$cor = new CoreModel();
$getInfo = $cor->getUserInfo();
setSession('getInfo', $getInfo);

// Init router
$router = new Router();

// Load routes in web.php
require_once __DIR__ . '/routers/web.php';

// Request handle
$requestUrl = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestUrl = str_replace(APP_BASE_PATH, '', $requestUrl);

$router->xulyPath($_SERVER['REQUEST_METHOD'], $requestUrl);
