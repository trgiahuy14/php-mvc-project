<?php

declare(strict_types=1);

namespace App\Controllers;

use Core\Controller;

final class DashboardController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->requireLogin();
    }

    public function index(): void
    {
        $data = [
            'title' => 'Dashboard'
        ];
        $this->view->render('admin/dashboard/index', 'admin', $data);
    }
}
