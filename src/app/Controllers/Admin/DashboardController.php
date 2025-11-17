<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use Core\Controller;
use App\Middlewares\AuthMiddleware;

final class DashboardController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        AuthMiddleware::requireAuth();
    }

    public function index(): void
    {
        $data = [
            'title' => 'Dashboard'
        ];
        $this->view->render('admin/dashboard/index', 'admin', $data);
    }
}
