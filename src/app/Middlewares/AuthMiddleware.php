<?php

declare(strict_types=1);

namespace App\Middlewares;

use App\Models\Token;
use App\Models\User;

class AuthMiddleware
{
    /**
     * Load authenticated user from session token
     *
     * @return void
     */
    public static function loadCurrentUser(): void
    {
        // Skip if already loaded
        if (isset($_SESSION['current_user'])) {
            return;
        }

        $currentUser = null;
        $token = $_SESSION['token_login'] ?? null;
        if ($token) {
            $modelToken = new Token();
            $rowToken = $modelToken->findByToken($token);
            if ($rowToken) {
                $modelUser = new User();
                $currentUser = $modelUser->getUserById((int)$rowToken['user_id']);
            }
        }
        $_SESSION['current_user'] = $currentUser;
    }

    /**
     * Require user to be logged in
     *
     * @return void
     */
    public static function requireAuth(): void
    {
        $token = getSession('token_login');
        if ($token === false) {
            redirect('/login');
        }
        $modelToken = new Token();
        $row = $modelToken->findByToken($token);

        if (!$row) {
            removeSession('token_login');
            redirect('/login');
        }
    }

    /**
     * Get current authenticated user
     *
     * @return array|null
     */
    public static function getCurrentUser(): ?array
    {
        self::loadCurrentUser();
        return $_SESSION['current_user'] ?? null;
    }
}
