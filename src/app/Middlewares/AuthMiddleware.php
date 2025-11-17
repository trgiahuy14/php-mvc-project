<?php

declare(strict_types=1);

namespace App\Middlewares;

use App\Models\Token;
use App\Models\User;
use Core\Session;
use Exception;

class AuthMiddleware
{
    /**
     * Load authenticated user from session token
     */
    public static function loadCurrentUser(): void
    {
        // Skip if already loaded and not null
        if (Session::get('current_user')) {
            return;
        }

        $token = Session::get('current_user');
        $currentUser = null;

        if ($token) {
            try {
                $currentUser = self::validateTokenAndGetUser($token);
            } catch (Exception $e) {
                // Log error
                error_log("Auth error: " . $e->getMessage());
                // Clear invalid token
                self::clearAuth();
            }
        }

        Session::set('current_user', $currentUser);
    }

    /**
     * Validate token and get user
     */
    private static function validateTokenAndGetUser(string $token): ?array
    {
        $modelToken = new Token();
        $rowToken = $modelToken->findToken($token);

        if (!$rowToken) {
            return null;
        }

        // Check if token is expired
        if (self::isTokenExpired($rowToken)) {

            $modelToken->deleteToken($token);
            return null;
        }

        // Get user
        $modelUser = new User();
        $user = $modelUser->getUserById((int)$rowToken['user_id']);

        return $user;
    }

    /**
     * Check if token is expired
     */
    private static function isTokenExpired(array $tokenData): bool
    {
        if (!isset($tokenData['expires_at']) || empty($tokenData['expires_at'])) {
            return false;
        }

        return strtotime($tokenData['expires_at']) < time();
    }


    /**
     * Require user to be logged in
     */
    public static function requireAuth(): void
    {
        self::loadCurrentUser();

        $currentUser = Session::get('current_user');

        if (!$currentUser) {
            self::clearAuth();
            redirect('/login');
        }
    }

    // /**
    //  * Get current authenticated user
    //  */
    // public static function getCurrentUser(): ?array
    // {
    //     self::loadCurrentUser();
    //     return $_SESSION[self::USER_SESSION_KEY] ?? null;
    // }

    /**
     * Check if user is authenticated
     */
    public static function isAuthenticated(): bool
    {
        self::loadCurrentUser();
        return !empty(Session::get('current_user'));
    }

    /**
     * Clear authentication session
     */
    public static function clearAuth(): void
    {
        Session::delete('token_login');
        Session::delete('current_user');
    }

    /**
     * Logout user
     */
    public static function logout(): void
    {
        $token = Session::get('token_login');

        if ($token) {
            $modelToken = new Token();
            $modelToken->deleteToken($token);
        }

        self::clearAuth();
        session_destroy();
    }
}
