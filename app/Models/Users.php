<?php

class Users extends CoreModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAllUsers()
    {
        return $this->getAll("SELECT * FROM users");
    }

    public function getOneUser($condition)
    {
        return $this->getOne("SELECT * FROM users WHERE $condition");
    }

    public function getRowsUser($condition)
    {
        return $this->getRows("SELECT * FROM users WHERE $condition");
    }

    public function insertUser($data)
    {
        return $this->insert('users', $data);
    }

    public function updateUser($data)
    {
        return $this->update('users', $data);
    }

    public function getOneToken($condition)
    {
        return $this->getOne("SELECT * FROM token_login WHERE $condition");
    }

    public function insertTokenLogin($data)
    {
        return $this->insert('token_login', $data);
    }
}
