<?php

// Save session value
function setSession($key, $value)
{
    if (!empty(session_id())) {
        $_SESSION[$key] = $value;
        return true;
    }
    return false;
}

// Get session value
function getSession($key = '')
{
    if (empty($key)) {
        return $_SESSION;
    }
    return $_SESSION[$key] ?? false;
}

// Remove session value or destroy all
function removeSession($key = '')
{
    if (empty($key)) {
        session_destroy();
    }
    if (isset($key)) {
        unset($_SESSION[$key]);
    }
    return true;
}

// Set flash session 
function setSessionFlash($key, $value)
{
    return setSession($key . 'Flash', $value);
}

// Flash session - get once and auto remove
function getSessionFlash($key)
{
    $key .= 'Flash';
    $value = getSession($key);
    removeSession($key);
    return $value;
}
