<?php

declare(strict_types=1);

if (!defined('APP_KEY')) die('Access denied');

/** Database connection */
class Database
{
    private static ?PDO $pdoInstance = null;
    public static function connectPdo(): PDO
    {
        // Reuse previous PDO connection
        if (self::$pdoInstance !== null) {
            return self::$pdoInstance;
        }

        try {
            // Build DSN string for PDO
            $dsn = DB_DRIVER . ':host=' . DB_HOST . ';dbname=' . DB_NAME .  ';charset=utf8mb4';

            // Default PDO configuration
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // throw exception on DB error
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // fetch associative arrays
            ];

            // Create new database connection
            self::$pdoInstance = new PDO(
                $dsn,
                DB_USER,
                DB_PASSWORD,
                $options
            );
        } catch (Exception $ex) {
            throw new Exception("Database connection failed: " . $ex->getMessage());
        }

        return self::$pdoInstance;
    }
}
