<?php

declare(strict_types=1);

namespace App\Models;

use Core\Model;

final class Category extends Model
{
    /** Get all category (no paging) */
    public function getAllCategories(): array
    {
        return $this->getAll('SELECT * FROM categories ORDER BY id DESC');
    }

    /** Get categories (paging + keyword) */
    public function getCategories(int $limit, int $offset, string $keyword = ''): array
    {
        $sql = "SELECT * FROM categories";
        $params = [];

        // search filter
        if ($keyword !== '') {
            $sql .= " WHERE name LIKE :kw";
            $params[':kw'] = '%' . $keyword . '%';
        }

        // paging
        $limit = max(0, $limit);
        $offset = max(0, $offset);
        $sql .= " ORDER BY id DESC LIMIT {$offset}, {$limit}";

        return $this->getAll($sql, $params);
    }

    // Get category by id
    public function getCategoryById(int $categoryId): ?array
    {
        return $this->getOne(
            'SELECT * FROM categories WHERE id = :id LIMIT 1',
            [':id' => $categoryId]
        );
    }

    /** Count all categories */
    public function countCategories(): int
    {
        return $this->getScalar('SELECT COUNT(id) FROM categories');
    }

    /** Count categories with keyword */
    public function countCategoriesByKeyword(string $keyword = ''): int
    {
        $sql = "SELECT COUNT(id) FROM categories";
        $params = [];

        if ($keyword !== '') {
            $sql .= " WHERE name LIKE :kw";
            $params[':kw'] = '%' . $keyword . '%';
        }

        return $this->getScalar($sql, $params);
    }

    // Insert new category
    public function createCategory(array $categoryData): bool
    {
        return $this->insert('categories', $categoryData);
    }

    // Update category by id
    public function updateCategory(int $categoryId, array $categoryData): bool
    {
        return $this->update(
            'categories',
            $categoryData,
            'id = :id',
            [':id' => $categoryId]
        );
    }

    // Delete category by id
    public function deleteCategory(int $categoryId): bool
    {
        return $this->delete(
            'categories',
            'id = :id',
            [':id' => $categoryId]
        );
    }
}
