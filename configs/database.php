<?php
if (!defined('APP_KEY')) die('Access denied');

class Database
{
    private static $conn;
    public static function connectDPO()
    {
        try {
            if (class_exists('PDO')) {
                $options = array(
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8", // Hỗ trợ tiếng việt
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Đẩy lỗi vào exception
                );
                $dsn = DB_DRIVER . ':host=' . DB_HOST . ';dbname=' . DB_NAME;
                self::$conn = new PDO($dsn, DB_USER, DB_PASSWORD, $options);
            }
        } catch (Exception $ex) {
            require_once './modules/errors/404.php';
            die();
        }

        return self::$conn;
    }
}
