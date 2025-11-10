<?php

class UsersController extends BaseController
{

    public function __construct()
    {
        $this->requireLogin();
    }

    public function index()
    {
        $user = new Users();

        $userDetail = $user->getAllUsers();

        // ob_start();
        // $this->renderView('layouts-part/users', $userDetail);

        // $data = ['content' => ob_get_clean()];

        $this->renderView('layouts/main-layout');
    }

    public function dashboard()
    {
        // Get the info from session that was set in index.php
        $getInfo = getSession('getInfo');

        $data = [
            "content" => "Dashboard",
            "getInfo" => $getInfo
        ];
        $this->renderView('layouts-part/dashboard', $data);
    }
}
