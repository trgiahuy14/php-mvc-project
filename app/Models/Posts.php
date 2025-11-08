<?php

class Posts extends CoreModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAllPosts()
    {
        return $this->getAll("SELECT * FROM posts");
    }

    public function getRowPosts()
    {
        return $this->getRows("SELECT * FROM posts");
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
