<?php
if (!defined('_TRGIAHUY')) {
    die('Truy cập không hợp lệ');
}


try {
    if (class_exists('PDO')) {
        $options = array(
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8", // Hỗ trợ tiếng việt
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Đẩy lỗi vào exception
        );
        $dsn = _DRIVER . ':host=' . _HOST . ';dbname=' . _DB;
        $conn = new PDO($dsn, _USER, _PASS, $options);
    }
} catch (Exception $ex) {
    echo 'Lỗi kết nối' . $ex->getMessage();
}
