<?php

namespace Core;

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

        // Define public routes that use Client controllers
        $publicRoutes = ['/', '/post', '/category', '/search'];

        // choose controller folder based on URL
        // If URL is in public routes, use Client namespace, otherwise Admin
        $namespace = in_array($url, $publicRoutes)
            ? 'App\\Controllers\\Client\\'
            : 'App\\Controllers\\Admin\\';

        $fqcn = $namespace . $controller; // e.g. App\Controllers\Admin\AuthController

        if (!class_exists($fqcn)) {
            http_response_code(500);
            echo 'Controller class not found: ' . $fqcn;
            return;
        }

        $instance = new $fqcn();

        if (!method_exists($instance, $func)) {
            http_response_code(404);
            echo 'Action not found';
            return;
        }

        $instance->$func();
    }
}
