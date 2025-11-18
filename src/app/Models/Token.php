<?php

declare(strict_types=1);

namespace App\Models;

use Core\Model;

final class Token extends Model
{
    // Count tokens by user_id
    public function countByUserId(int $userId): int
    {
        return $this->getScalar(
            'SELECT COUNT(*) FROM token_login WHERE user_id = :user_id',
            ['user_id' => $userId]
        );
    }

    // Find token record
    public function findToken(string $token): ?array
    {
        return $this->getOne('SELECT * FROM token_login WHERE token = :token LIMIT 1', ['token' => $token]);
    }

    // Insert token record
    public function createToken(array $data): bool
    {
        return $this->insert('token_login', $data);
    }

    // Delete token record
    public function deleteToken(string $token): bool
    {
        return $this->delete('token_login', 'token = :token', ['token' => $token]);
    }

    // Delete token by user id
    public function deleteTokensByUserId(int $userId): bool
    {
        return $this->delete('token_login', 'user_id = :user_id', ['user_id' => $userId]);
    }
}
