<?php

class HomeController extends Controller
{
    private $postModel;

    public function __construct()
    {
        parent::__construct();
        $this->postModel = new PostModel();
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
