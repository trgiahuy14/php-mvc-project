<?php

class Posts extends CoreModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAllPosts($condition = "")
    {
        return $this->getAll("SELECT * FROM posts $condition");
    }

    public function getOnePost($condition)
    {
        return $this->getOne("SELECT * FROM posts WHERE $condition");
    }

    public function getRowPosts()
    {
        return $this->getRows("SELECT * FROM posts");
    }

    public function getScalarPosts($condition = "")
    {
        $sql = "SELECT COUNT(id) FROM posts $condition";
        return $this->getScalar($sql);
    }

    public function insertPost($data)
    {
        return $this->insert('posts', $data);
    }

    public function updatePost($data, $condition)
    {
        return $this->update('posts', $data, $condition);
    }

    public function deletePost($condition)
    {
        return $this->delete('posts', $condition);
    }
}
