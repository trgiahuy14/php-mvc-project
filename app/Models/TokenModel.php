<?php

declare(strict_types=1);

if (!defined('APP_KEY')) {
    die('Access denied');
}

final class TokenModel extends CoreModel
{
    // Count tokens by user_id
    public function countByUserId(int $userId): int
    {
        return $this->getScalar(
            'SELECT COUNT(*) FROM token_login WHERE user_id = :user_id',
            [':user_id' => $userId]
        );
    }

    // Insert token record
    public function createToken(array $data): bool
    {
        return $this->insert('token_login', $data);
    }

    // Find token record
    public function findByToken(string $token): ?array
    {
        return $this->getOne('SELECT * FROM token_login WHERE token = :token LIMIT 1', [':token' => $token]);
    }

    // Delete token record
    public function deleteByToken(string $token): bool
    {
        return $this->delete('token_login', 'token = :token', [':token' => $token]);
    }
}
