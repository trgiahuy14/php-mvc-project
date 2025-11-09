<?php
if (!defined('APP_KEY')) die('Access denied');

// Database config
const DB_HOST     = 'localhost';
const DB_NAME     = 'php_mvc_db';
const DB_USER     = 'root';
const DB_PASSWORD = '';
const DB_DRIVER   = 'mysql';

// App URL
define("APP_BASE_PATH", "/php-mvc-project");
define("BASE_URL", "http://" . $_SERVER['HTTP_HOST'] . APP_BASE_PATH);
define("PUBLIC_URL", BASE_URL . "/public");
