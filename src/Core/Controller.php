<?php

declare(strict_types=1);

namespace Core;

class Controller
{

    protected View $view;

    public function __construct()
    {
        $this->view = new View();
    }

    protected function requireLogin(): void
    {
        if (!Auth::isLogin()) {
            redirect('/login');
            exit;
        }
    }
}
