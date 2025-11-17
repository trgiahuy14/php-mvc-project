<?php

declare(strict_types=1);

namespace Core;

class Session
{
    /** Set normal session */
    public static function set(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    /** Get normal session */
    public static function get(string $key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    /** Remove a session key */
    public static function delete(string $key): void
    {
        unset($_SESSION[$key]);
    }

    /** Check exists */
    public static function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    /** Destroy all sessions */
    public static function destroy(): void
    {
        session_destroy();
    }

     // FLASH SESSION
    /** Set flash message (auto remove after read) */
    public static function flash(string $key, $value): void
    {
        $_SESSION['_flash'][$key] = $value;
    }

    /** Get and remove flash */
    public static function getFlash(string $key)
    {
        if (!isset($_SESSION['_flash'][$key])) {
            return null;
        }

        $value = $_SESSION['_flash'][$key];

        // delete after reading
        unset($_SESSION['_flash'][$key]);

        return $value;
    }
}
