<?php

class BaseController
{
    protected function renderView($view, $data = [])
    {
        extract($data); // Convert array to variable(s)

        require_once './app/Views/' . $view . '.php';
    }
}
