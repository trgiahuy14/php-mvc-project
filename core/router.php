<?php

class Router
{
    protected $routers = [];
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

    public function xulyPath($method, $url)
    {
        $url = $url ?: '/'; // If url is empty, return '/'

        if (isset($this->routers[$method][$url])) {
            $action = $this->routers[$method][$url];
            [$controller, $funcs] = explode('@', $action);

            if ($controller == 'HomeController') {
                // Client
                require_once './app/Controllers/clients/' . $controller . '.php';
                $controllerMot = new $controller();
                $controllerMot->$funcs();
            } else {
                // Admin
                require_once './app/Controllers/' . $controller . '.php';
                $controllerMot = new $controller();
                $controllerMot->$funcs();
            }
        } else {
            echo '404 ERROR';
        }
    }
}
