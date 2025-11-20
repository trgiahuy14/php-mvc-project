<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use Core\Controller;
use App\Middlewares\AuthMiddleware;
use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use Core\Session;

final class DashboardController extends Controller
{
    private Post $postModel;
    private User $userModel;
    private Category $categoryModel;

    public function __construct()
    {
        parent::__construct();
        AuthMiddleware::requireAuth();
        $this->postModel = new Post();
        $this->userModel = new User();
        $this->categoryModel = new Category();
    }

    public function index(): void
    {
        // Get statistics
        $totalPosts = $this->postModel->countPosts();
        $totalUsers = $this->userModel->countUsers();
        $totalCategories = $this->categoryModel->countCategories();

        // Get recent posts
        $recentPosts = $this->postModel->getPosts(5, 0);

        // Get recent users
        $recentUsers = $this->userModel->getUsers(5, 0);

        // Get current user info
        $currentUserId = (int)Session::get('user_id');
        $currentUser = $this->userModel->getUserById($currentUserId);

        $data = [
            'headerData' => ['title' => 'Dashboard - DevBlog CMS'],
            'totalPosts' => $totalPosts,
            'totalUsers' => $totalUsers,
            'totalCategories' => $totalCategories,
            'recentPosts' => $recentPosts,
            'recentUsers' => $recentUsers,
            'currentUser' => $currentUser
        ];

        // Render dashboard view
        $this->view->render('admin/dashboard/index', 'admin', $data);
    }
}
