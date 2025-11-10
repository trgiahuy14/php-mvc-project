<?php
if (!defined('APP_KEY')) die('Access denied');
class BaseController
{
    protected function requireLogin(): void
    {
        if (!Auth::isLogin()) {
            redirect('/login');
            exit;
        }
    }
    protected function renderView($view, $data = [])
    {
        extract($data); // Convert array to variable(s)
        require_once './app/Views/' . $view . '.php';
    }
}
