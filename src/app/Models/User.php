<?php

declare(strict_types=1);

namespace App\Models;

use Core\Model;

final class User extends Model
{
    // Get all posts (no paging)
    public function getAllUsers(): array
    {
        return $this->getAll('SELECT * FROM users ORDER BY id DESC');
    }

    // Get posts (paging + keyword)
    public function getUsers(int $limit, int $offset, string $keyword = ''): array
    {
        $sql = "SELECT * FROM users";
        $params = [];

        // search filter
        if ($keyword !== '') {
            $sql .= " WHERE fullname LIKE :kw";
            $params[':kw'] = '%' . $keyword . '%';
        }

        // paging
        $limit = max(0, $limit);
        $offset = max(0, $offset);
        $sql .= " ORDER BY id DESC LIMIT {$offset}, {$limit}";

        return $this->getAll($sql, $params);
    }

    // Count all users 
    public function countUsers(): int
    {
        return $this->getScalar('SELECT COUNT(id) FROM users');
    }

    // Count users with keyword
    public function countUsersByKeyword(string $keyword = ''): int
    {
        $sql = "SELECT COUNT(id) FROM users";
        $params = [];

        if ($keyword !== '') {
            $sql .= " WHERE fullname LIKE :kw";
            $params[':kw'] = '%' . $keyword . '%';
        }

        return $this->getScalar($sql, $params);
    }

    // Get user by id
    public function getUserById(int $userId): ?array
    {
        return $this->getOne(
            "SELECT * FROM users WHERE id = :id LIMIT 1",
            [':id' => $userId]
        );
    }

    // Get user by Email
    public function getUserByUsername(string $username): ?array
    {
        return $this->getOne(
            'SELECT * FROM users WHERE username = :username LIMIT 1',
            ['username' => $username]
        );
    }

    // Get user by Email
    public function getUserByEmail(string $email): ?array
    {
        return $this->getOne(
            'SELECT * FROM users WHERE email = :email LIMIT 1',
            ['email' => $email]
        );
    }

    // Get user by verification token
    public function getUserByActivationToken(string $token): ?array
    {
        return $this->getOne(
            'SELECT * FROM users WHERE verification_token = :token LIMIT 1',
            [':token' => $token]
        );
    }

    // Get user by reset_token
    public function getUserByForgetToken(string $token): ?array
    {
        return $this->getOne(
            'SELECT * FROM users WHERE reset_token = :token LIMIT 1',
            [':token' => $token]
        );
    }

    // Create user
    public function createUser(array $userData): bool
    {
        return $this->insert('users', $userData);
    }

    // Update user by id
    public function updateUser(int $userId, array $userData): bool
    {
        return $this->update(
            'users',
            $userData,
            'id = :id',
            [':id' => $userId]
        );
    }

    // Delete user by id
    public function deleteUser(int $userId): bool
    {
        return $this->delete(
            'posts',
            'id = :id',
            [':id' => $userId]
        );
    }
}
