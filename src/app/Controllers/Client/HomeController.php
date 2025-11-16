<?php

namespace App\Controllers;

use Core\Controller;

class HomeController extends Controller
{
    private $postModel;

    public function __construct()
    {
        parent::__construct();
        // $this->postModel = new Post();
    }


    public function index()
    {
        // $rel = $this->postModel->getAllPosts();
        $data = [
            'title' => 'test'
        ];
        $this->view->render('client');
    }
}
