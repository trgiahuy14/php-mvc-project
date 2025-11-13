<?php

declare(strict_types=1);
if (!defined('APP_KEY')) die('Access denied');

final class UserModel extends CoreModel
{
    // Get all users
    public function getAllUsers(): array
    {
        return $this->getAll("SELECT * FROM users");
    }

    // Get user by id
    public function getUserById(int $userId): ?array
    {
        return $this->getOne(
            "SELECT * FROM users WHERE id = :id LIMIT 1",
            [':id' => $userId]
        );
    }

    // Get user by email
    public function getUserByEmail(string $email): ?array
    {
        return $this->getOne(
            'SELECT * FROM users WHERE email = :email LIMIT 1',
            [':email' => $email]
        );
    }

    // Get user by activation token
    public function getUserByActivationToken(string $token): ?array
    {
        return $this->getOne(
            'SELECT * FROM users WHERE active_token = :token LIMIT 1',
            [':token' => $token]
        );
    }

    // Get user by forget_token
    public function getUserByForgetToken(string $token): ?array
    {
        return $this->getOne(
            'SELECT * FROM users WHERE forget_token = :token LIMIT 1',
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
}
