<?php
if (!defined('APP_KEY')) die('Access denied');
class Auth
{
    public static function isLogin(): bool
    {
        // Hàm check login

        $token = getSession('token_login');

        if ($token === false) {
            return false;
        }

        $model = new CoreModel();
        $row = $model->getOne("SELECT * FROM token_login WHERE token = '$token'");

        if ($row) {
            return true;
        }

        removeSession('token_login');
        return false;
    }
}
