<?php

class HomeController extends BaseController
{
    public function index()
    {
        $this->renderView('layouts/main-layout');
    }
}
