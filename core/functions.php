<?php
if (!defined('APP_KEY')) die('Access denied');

// Inclue layout helpers
function layout($layoutName, $role = 'client', $data = [])
{
    $path = dirname(__DIR__) . "/app/Views/partials/{$role}/" . $layoutName . '.php';
    if (file_exists($path)) {
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

// Send mail
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function sendMail($emailTo, $subject, $content)
{

    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_OFF;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = getenv('MAIL_USER');                     //SMTP username
        $mail->Password   = getenv('MAIL_PASS');                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom(getenv('MAIL_USER'), 'VietNews');
        $mail->addAddress($emailTo);     //Add a recipient


        //Content
        $mail->CharSet = 'UTF-8';
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $content;

        return $mail->send();
    } catch (Exception $e) {
        echo "Gửi thất bại. Mailer Error: {$mail->ErrorInfo}";
    }
}

// HTTP method helpers
function isPost(): bool
{
    return $_SERVER['REQUEST_METHOD'] == 'POST';
}

function isGet(): bool
{
    return $_SERVER['REQUEST_METHOD'] == 'GET';
}

// Sanitize request data and return data
function filterData($method = '')
{
    $out    = [];
    $method = strtolower((string)$method);

    // GET
    if (($method === '' || $method === 'get') && !empty($_GET)) {
        foreach ($_GET as $key => $value) {
            $key = strip_tags($key);
            $out[$key] = is_array($value)
                ? filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY) // lọc từng phần tử trong mảng
                : filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS); // lọc giá trị đơn
        }
    }

    // POST
    if (($method === '' || $method === 'post') && !empty($_POST)) {
        foreach ($_POST as $key => $value) {
            $key = strip_tags($key);
            $out[$key] = is_array($value)
                ? filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY) // lọc từng phần tử trong mảng
                : filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS); // lọc giá trị đơn
        }
    }

    return $out;
}

// Validators
function validateEmail($email)
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
function isPhone($phone)
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

// Flash / alerts
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

// Redirect helper
function redirect($path, $isFullUrl = false)
{
    $target = $isFullUrl ? $path : BASE_URL . $path;
    header("Location: $target");
    exit;
}

// Convert number to shorthand format: 1K, 1.5M, 1B...
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

/** Remove a key from query string params */
function cleanQuery(string $removeKey): string
{
    unset($_GET[$removeKey]);
    $qs = http_build_query($_GET);
    return $qs === '' ? '' : '&' . $qs;
}
