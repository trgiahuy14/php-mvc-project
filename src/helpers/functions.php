<?php

/**
 * Global Helper Functions
 */


// Require layout helpers 
function layout($layoutName, $role = 'client', $data = [])
{
    $path = dirname(__DIR__) . "/app/Views/partials/{$role}/" . $layoutName . '.php';
    if (file_exists($path)) {
        extract($data);
        require_once $path;
    } else {
        die('Layout file not found: ' . $path);
    }
}

function client($layoutName, $data = [])
{
    layout($layoutName, 'client', $data);
}

function admin($layoutName, $data = [])
{
    layout($layoutName, 'admin', $data);
}

/** 
 * HTTP method helpers
 */
function isPost(): bool
{
    return $_SERVER['REQUEST_METHOD'] == 'POST';
}

function isGet(): bool
{
    return $_SERVER['REQUEST_METHOD'] == 'GET';
}

/** 
 * Sanitize request data and return data
 */
function filterData($method = '')
{
    $data    = [];
    $method = strtolower((string)$method);

    // GET
    if (($method === '' || $method === 'get') && !empty($_GET)) {
        foreach ($_GET as $key => $value) {
            $key = strip_tags($key);
            $data[$key] = is_array($value)
                ? filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY) // lọc từng phần tử trong mảng
                : filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS); // lọc giá trị đơn
        }
    }

    // POST
    if (($method === '' || $method === 'post') && !empty($_POST)) {
        foreach ($_POST as $key => $value) {
            $key = strip_tags($key);
            $data[$key] = is_array($value)
                ? filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY) // lọc từng phần tử trong mảng
                : filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS); // lọc giá trị đơn
        }
    }
    return $data;
}

/** 
 * Validators
 */
function validateEmail($email): bool
{
    $checkEmail = false;
    if (!empty($email)) {
        $checkEmail = filter_var($email, FILTER_VALIDATE_EMAIL);
    }
    return $checkEmail;
}

function validateInt($number)
{
    $checkNumber = false;
    if (!empty($number)) {
        $checkNumber = filter_var($number, FILTER_VALIDATE_INT);
    }
    return $checkNumber;
}


// Check Vietnam mobile phone format
function isPhone($phone): bool
{
    //Convert to string
    $phone = (string)$phone;

    // Must start with 0 and have 10 digits
    if ($phone[0] !== '0' || strlen($phone) !== 10) {
        return false;
    }
    // Remove 0 and check int
    $afterZero = substr($phone, 1);

    return validateInt($afterZero);
}

/** 
 * Flash / alerts
 */
function getMsg($msg, $type = 'success')
{
    echo '<div class="anncouce-message alert alert-' . $type . '">';
    echo  $msg;
    echo ' </div>';
}

function formError($errors, $fieldName)
{
    return (!empty($errors[$fieldName]))
        ? '<div class = "error">' . current($errors[$fieldName]) . '</div>'
        : false;
}

function oldData($oldData, $fieldName)
{
    return !empty($oldData[$fieldName]) ? $oldData[$fieldName] : null;
}

/** 
 * Redirect helper
 */
function redirect($path, $isFullUrl = false)
{
    $target = $isFullUrl ? $path : BASE_URL . $path;
    header("Location: $target");
    exit;
}

/** 
 * Convert number to shorthand format: 1K, 1.5M, 1B...
 */
function shortNumber($number)
{
    if ($number >= 1000000000) {
        return round($number / 1000000000, 1) . 'B';
    } elseif ($number >= 1000000) {
        return round($number / 1000000, 1) . 'M';
    } elseif ($number >= 1000) {
        return round($number / 1000, 1) . 'K';
    }

    return $number;
}

/** 
 * Remove a key from query string params
 */
function cleanQuery(string $removeKey): string
{
    unset($_GET[$removeKey]);
    $qs = http_build_query($_GET);
    return $qs === '' ? '' : '&' . $qs;
}

/** 
 * Role & Permission Helpers
 * 
 */

/** Check if user has permission */
function can(string $permission): bool
{
    return \App\Middlewares\RoleMiddleware::hasPermission($permission);
}

/** Check if user has any of the permissions */
function canAny(array $permissions): bool
{
    return \App\Middlewares\RoleMiddleware::hasAnyPermission($permissions);
}

/** Check if user has all permissions */
function canAll(array $permissions): bool
{
    return \App\Middlewares\RoleMiddleware::hasAllPermissions($permissions);
}

/** Check if user is admin */
function isAdmin(): bool
{
    return \App\Middlewares\RoleMiddleware::isAdmin();
}

/** Check if user is editor */
function isEditor(): bool
{
    return \App\Middlewares\RoleMiddleware::isEditor();
}

/** Check if user is author */
function isAuthor(): bool
{
    return \App\Middlewares\RoleMiddleware::isAuthor();
}

/** Get current user role */
function userRole(): ?string
{
    return \Core\Session::get('role');
}

/** Check if user can edit resource */
function canEdit(int $resourceOwnerId): bool
{
    return \App\Middlewares\RoleMiddleware::canEdit($resourceOwnerId);
}

/** Check if user can delete resource */
function canDelete(int $resourceOwnerId = null): bool
{
    return \App\Middlewares\RoleMiddleware::canDelete($resourceOwnerId);
}
