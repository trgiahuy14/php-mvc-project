<?php

declare(strict_types=1);

if (!defined('APP_KEY')) die('Access denied');

class Controller
{

    protected View $view;

    public function __construct()
    {
        $this->view = new View();
    }

    // protected function requireLogin(): void
    // {
    //     if (!Auth::isLogin()) {
    //         redirect('/login');
    //         exit;
    //     }
    // }
}
