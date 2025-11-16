<?php

declare(strict_types=1);

namespace Core;

use App\Middlewares\AuthMiddleware;

class Controller
{

    protected View $view;

    public function __construct()
    {
        $this->view = new View();
    }

    /**
     * Require user to be logged in
     */
    protected function requireLogin(): void
    {
        AuthMiddleware::requireAuth();
    }
}
