<?php

declare(strict_types=1);

namespace Core;


class Model
{
    /** PDO connection */
    protected $pdo;

    public function __construct()
    {
        // Reuse existing PDO instance from Database
        $this->pdo = Database::connectPdo();
    }

    /** Fetch all rows */
    public function getAll(string $sql, array $params = []): array
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return (array) $stmt->fetchAll();
    }

    /** Fetch one row or null */
    public function getOne(string $sql, array $params = []): ?array
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $row = $stmt->fetch();
        return $row === false ? null : $row;
    }

    /** Fetch scalar value (COUNT(*), SUM, ...) */
    public function getScalar(string $sql, array $params = []): int
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }

    /** Count rows from SELECT */
    public function getRowCount(string $sql, array $params = []): int
    {
        $countSql = 'SELECT COUNT(*) FROM (' . $sql . ') AS _t';
        $stmt = $this->pdo->prepare($countSql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }

    /** Insert record */
    public function insert(string $table, array $data): bool
    {
        if (empty($data)) {
            return false;
        }

        $columns = array_keys($data);
        $placeholder = array_map(fn($c) => ':' . $c, $columns);
        // e.g. ['name','email'] => [':name',':email']

        $sql = sprintf(
            'INSERT INTO %s (%s) VALUES (%s)',
            $table,
            implode(',', $columns),
            implode(',',  $placeholder)
        );
        // e.g. INSERT INTO users (name,email) VALUES (:name,:email)

        $params = [];
        foreach ($data as $col => $val) {
            $params[':' . $col] = $val;
        }
        // e.g. [':name' => 'Huy', ':email' => 'huy@gmail.com']

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    /** Update record */
    public function update(string $table, array $data, string $where, array $whereParams = []): bool
    {
        if (empty($data)) {
            return false;
        }

        $sets = [];
        $params = [];

        foreach ($data as $col => $val) {
            $placeholder = ':' . $col;
            // $placeholder =[':name',':email']

            $sets[] = "$col = $placeholder";
            // $sets = [ "name = :name",  "email = :email"]

            $params[$placeholder] = $val;
            // $params = [ ':name'  => 'Huy', ':email' => 'a@gmail.com']
        }

        $sql = sprintf(
            'UPDATE %s SET %s WHERE %s',
            $table,
            implode(', ', $sets),
            $where
        );
        // e.g. UPDATE table SET name = :name, email = :email WHERE ...

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params + $whereParams);
    }

    /** Delete record */
    public function delete(string $table, string $where, array $whereParams = []): bool
    {
        $sql = sprintf('DELETE FROM %s WHERE %s', $table, $where);
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($whereParams);
    }
}
