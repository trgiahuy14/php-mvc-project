<?php

declare(strict_types=1);

namespace App\Middlewares;

use App\Models\User;
use Core\Session;

class AuthMiddleware
{
    /**
     * Require user to be logged in
     */
    public static function requireAuth(): bool
    {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Vui lòng đăng nhập để tiếp tục';
            redirect('/login');
            exit;
        }

        return true;
    }

    /**
     * Set user sessions after login successful
     */
    public static function login(array $user): void
    {
        Session::set('user_id', $user['id']);
        Session::set('username', $user['username']);
        Session::set('avatar', $user['avatar']);
        Session::set('role', $user['role']);
    }

    /**
     * Get current user ID
     */
    public static function userId(): ?int
    {
        return Session::get('user_id');
    }

    /**
     * Get current user data
     */
    public static function userData(): ?array
    {
        if (!Session::get('user_id')) {
            return null;
        }

        $userModel = new User();
        return $userModel->getUserById(self::userId());
    }

    /**
     * Logout and destroy session
     */
    public static function logout(): void
    {
        self::requireAuth();
        session_destroy();
    }
}
