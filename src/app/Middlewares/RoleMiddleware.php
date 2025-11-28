<?php

declare(strict_types=1);

namespace App\Middlewares;

use Core\Session;

class RoleMiddleware
{
    // Permissions mapping for each role
    // * for all actions: 'users.*' = users.view, users.create, users.edit, users.delete
    private const PERMISSIONS = [
        'admin' => [
            'users.*',
            'posts.*',
            'categories.*',
            'comments.*',
            'settings.*',
            'dashboard.view'
        ],
        'editor' => [
            'posts.*',
            'categories.view',
            'categories.create',
            'categories.edit',
            'comments.*',
            'dashboard.view'
        ],
        'author' => [
            'posts.view',
            'posts.create',
            'posts.edit_own',
            'comments.view',
            'dashboard.view'
        ]
    ];

    /** Require user to have specific role(s) */
    public static function requireRole(string|array $roles): bool
    {
        AuthMiddleware::requireAuth();

        $userRole = Session::get('role');
        $allowedRoles = is_array($roles) ? $roles : [$roles];

        if (!in_array($userRole, $allowedRoles)) {
            Session::set('error', 'Bạn không có quyền truy cập chức năng này');
            redirect('/admin/dashboard');
        }

        return true;
    }

    /** Require user to be admin */
    public static function requireAdmin(): bool
    {
        return self::requireRole('admin');
    }

    /** Require user to be admin or editor */
    public static function requireEditor(): bool
    {
        return self::requireRole(['admin', 'editor']);
    }

    /** Check if user has specific permission */
    public static function hasPermission(string $permission): bool
    {
        $userRole = Session::get('role');

        if (!$userRole) {
            return false;
        }

        $rolePermissions = self::PERMISSIONS[$userRole] ?? [];

        // Check exact match or wildcard match
        foreach ($rolePermissions as $rolePermission) {
            if ($rolePermission === $permission) {
                return true;
            }

            // Wildcard match: 'users.*' matches 'users.create'
            if (str_ends_with($rolePermission, '.*')) {
                $prefix = rtrim($rolePermission, '.*');
                if (str_starts_with($permission, $prefix)) {
                    return true;
                }
            }
        }

        return false;
    }

    /** Check if user has all permissions */
    public static function hasAllPermissions(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if (!self::hasPermission($permission)) {
                return false;
            }
        }

        return true;
    }

    /** Check if user has any of the permissions */
    public static function hasAnyPermission(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if (self::hasPermission($permission)) {
                return true;
            }
        }

        return false;
    }

    /** Check if current user is admin */
    public static function isAdmin(): bool
    {
        return Session::get('role') === 'admin';
    }

    /** Check if current user is editor */
    public static function isEditor(): bool
    {
        return Session::get('role') === 'editor';
    }

    /** Check if current user is author */
    public static function isAuthor(): bool
    {
        return Session::get('role') === 'author';
    }

    /** 
     * Check if user can edit resource
     * Admin & Editor can edit all, Author can only edit own resources
     */
    public static function canEdit(int $resourceOwnerId): bool
    {
        if (self::isAdmin() || self::isEditor()) {
            return true;
        }

        if (self::isAuthor()) {
            $currentUserId = Session::get('user_id');
            return $currentUserId === $resourceOwnerId;
        }

        return false;
    }

    /** 
     * Check if user can delete resource
     * Only Admin & Editor can delete
     */
    public static function canDelete(int $resourceOwnerId = null): bool
    {
        if (self::isAdmin()) {
            return true;
        }

        if (self::isEditor() && self::hasPermission('posts.delete')) {
            return true;
        }

        return false;
    }

    /** Get all permissions for current user role */
    public static function getUserPermissions(): array
    {
        $userRole = Session::get('role');

        if (!$userRole) {
            return [];
        }

        return self::PERMISSIONS[$userRole] ?? [];
    }

    /** Deny access and redirect to dashboard */
    public static function denyAccess(string $message = 'Bạn không có quyền truy cập'): void
    {
        Session::set('error', $message);
        redirect('/admin/dashboard');
        exit;
    }
}
