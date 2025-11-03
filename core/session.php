<?php
if (!defined('_TRGIAHUY')) {
    die('Truy cập không hợp lệ');
}

// Set sessions
function setSession($key, $value)
{
    if (!empty(session_id())) {
        $_SESSION[$key] = $value;
        return true;
    } else
        return false;
}

// Lấy dữ liệu từ Session
function getSession($key = '')
{
    if (empty($key)) {
        return $_SESSION;
    } else {
        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        }
    }
    return false;
}

// Xóa dữ liệu từ Session
function removeSession($key = '')
{
    if (empty($key)) {
        session_destroy();
        return true;
    } else {
        if (isset($key)) {
            unset($_SESSION[$key]);
        }
        return true;
    }
    return false;
}

// Tạo session Flash
function setSessionFlash($key, $value)
{
    $key = $key . 'Flash';
    $rel = setSession($key, $value);
    return $rel;
}

// Lấy session Flash
function getSessionFlash($key)
{
    $key = $key . 'Flash';
    $rel = getSession($key);
    removeSession($key);

    return $rel;
}
