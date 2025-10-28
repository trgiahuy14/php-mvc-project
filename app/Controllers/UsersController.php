<?php

class UsersController extends BaseController
{
    public function index()
    {
        $user = new Users();

        $userDetail = $user->getAllUsers();

        $this->renderView('users', $userDetail);
    }
}
