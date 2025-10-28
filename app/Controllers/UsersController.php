<?php

class UsersController extends BaseController
{
    public function index()
    {
        $user = new Users();

        $userDetail = $user->getAllUsers();

        ob_start();
        $this->renderView('layouts-part/users', $userDetail);

        $data = ['content' => ob_get_clean()];

        $this->renderView('layouts/main-layout', $data);
    }

    public function index2()
    {
        // $user = new Users();

        $data = ["content" => "Index 2 content"];
        $this->renderView('layouts/main-layout', $data);
    }
}
