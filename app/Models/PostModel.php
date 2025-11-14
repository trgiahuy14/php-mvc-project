<?php

declare(strict_types=1);
if (!defined('APP_KEY')) die('Access denied');

final class PostModel extends CoreModel
{
    /** Get all posts (no paging) */
    public function getAllPosts(): array
    {
        return $this->getAll('SELECT * FROM posts ORDER BY id DESC');
    }

    /** Get posts (paging + keyword) */
    public function getPosts(int $limit, int $offset, string $keyword = ''): array
    {
        $sql = "SELECT * FROM posts";
        $params = [];

        // search filter
        if ($keyword !== '') {
            $sql .= " WHERE title LIKE :kw";
            $params[':kw'] = '%' . $keyword . '%';
        }

        // paging
        $limit = max(0, $limit);
        $offset = max(0, $offset);
        $sql .= " ORDER BY id DESC LIMIT {$offset}, {$limit}";

        return $this->getAll($sql, $params);
    }

    // Get post by id
    public function getPostById(int $postId): ?array
    {
        return $this->getOne(
            'SELECT * FROM posts WHERE id = :id LIMIT 1',
            [':id' => $postId]
        );
    }

    /** Count all posts */
    public function countPosts(): int
    {
        return $this->getScalar('SELECT COUNT(id) FROM posts');
    }

    /** Count posts with keyword */
    public function countPostsByKeyword(string $keyword = ''): int
    {
        $sql = "SELECT COUNT(id) FROM posts";
        $params = [];

        if ($keyword !== '') {
            $sql .= " WHERE title LIKE :kw";
            $params[':kw'] = '%' . $keyword . '%';
        }

        return $this->getScalar($sql, $params);
    }

    // Insert new post
    public function createPost(array $postData): bool
    {
        return $this->insert('posts', $postData);
    }

    // Update post by id
    public function updatePost(int $postId, array $postData): bool
    {
        return $this->update(
            'posts',
            $postData,
            'id = :id',
            [':id' => $postId]
        );
    }


    // Delete post by id
    public function deletePost(int $postId): bool
    {
        return $this->delete(
            'posts',
            'id = :id',
            [':id' => $postId]
        );
    }
}
