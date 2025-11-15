<?php

declare(strict_types=1);

if (!defined('APP_KEY')) die('Access denied');

// Database configuration
const DB_HOST     = 'localhost';
const DB_NAME     = 'php_mvc_db';
const DB_USER     = 'root';
const DB_PASSWORD = '';
const DB_DRIVER   = 'mysql';

// Application URL
define("APP_BASE_PATH", "/vietnews-cms-php");

// Base URL (e.g. http://localhost/vietnews-cms-php)
define("BASE_URL", "http://" . $_SERVER['HTTP_HOST'] . APP_BASE_PATH);

// Public asset URL
define("PUBLIC_URL", BASE_URL . "/public");
