<?php

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
                $dsn = _DRIVER . ':host=' . _HOST . ';dbname=' . _DB;
                self::$conn = new PDO($dsn, _USER, _PASS, $options);
            }
        } catch (Exception $ex) {
            require_once './modules/errors/404.php';
            die();
        }

        return self::$conn;
    }
}
