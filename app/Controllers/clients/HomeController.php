<?php

class HomeController extends BaseController
{
    private $postModel;

    public function __construct()
    {
        $this->postModel = new Posts();
    }


    public function index()
    {
        $rel = $this->postModel->getAllPosts();
        $data = [
            'getAllPosts' => $rel
        ];
        $this->renderView('layouts/main-layout', $data);
    }
}
