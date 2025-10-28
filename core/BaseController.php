<?php

class BaseController
{
    protected function renderView($view, $data = [])
    {
        extract($data);

        require_once './app/Views/' . $view . '.php';
    }
}
