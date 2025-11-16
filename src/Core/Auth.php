<?php

declare(strict_types=1);

namespace Core;

class Auth
{
    public static function isLogin(): bool
    {
        // Hàm check login

        $token = getSession('token_login');

        if ($token === false) {
            return false;
        }

        $model = new Model();
        $row = $model->getOne("SELECT * FROM token_login WHERE token = '$token'");

        if ($row) {
            return true;
        }

        removeSession('token_login');
        return false;
    }
}
