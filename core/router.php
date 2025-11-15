<?php
if (!defined('APP_KEY')) die('Access denied');

class Router
{
    protected $routers = [];

    // Register routes
    public function get($url, $action)
    {
        $this->routers['GET'][$url] = $action;
    }

    public function post($url, $action)
    {
        $this->routers['POST'][$url] = $action;
    }

    public function getRoute()
    {
        return $this->routers;
    }

    // Dispatch
    public function dispatch($method, $url)
    {
        $url = $url ?: '/'; // If url is empty, return '/'
        $method = strtoupper((string)$method); // normalize method

        if (!isset($this->routers[$method][$url])) {
            http_response_code(404);
            echo '404 ERROR';
            return;
        }

        $action = $this->routers[$method][$url];

        // action must be Controller@Method
        if (strpos($action, '@') === false) {
            http_response_code(500);
            echo 'Invalid route action';
            return;
        }

        [$controller, $func] = explode('@', $action, 2);

        // choose controller folder
        $baseDir = ($controller === 'HomeController')
            ? './app/Controllers/client/'
            : './app/Controllers/admin/';

        $file = $baseDir . $controller . '.php';

        if (!is_file($file)) {
            http_response_code(404);
            echo 'Controller file not found';
            return;
        }

        require_once $file;

        if (!class_exists($controller)) {
            http_response_code(500);
            echo 'Controller class not found';
            return;
        }

        $instance = new $controller();

        if (!method_exists($instance, $func)) {
            http_response_code(404);
            echo 'Action not found';
            return;
        }

        // call controller action
        $instance->$func();
    }
}
