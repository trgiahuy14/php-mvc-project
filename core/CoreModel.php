<?php
if (!defined('APP_KEY')) die('Access denied');

class CoreModel
{
    private $conn;
    public function __construct()
    {
        $this->conn = Database::connectDPO();
    }

    public function getUserInfo()
    {
        $token = getSession('token_login');
        if (!empty($token)) {
            $checkTokenLogin = $this->getOne("SELECT * FROM token_login WHERE token = '$token'");
            if (!empty($checkTokenLogin)) {
                $user_id = $checkTokenLogin['user_id'];
                $getUserDetail = $this->getOne("SELECT fullname, avatar FROM users WHERE id = '$user_id'");

                if (!empty($getUserDetail)) {
                    return $getUserDetail;
                }
            }
        }
        return false;
    }

    // Truy vấn nhiều dòng dữ liệu
    public function getAll($sql)
    {
        $stm = $this->conn->prepare($sql);
        $stm->execute();
        $result = $stm->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    // Đếm số dòng trả về
    public function getRows($sql)
    {
        $stm = $this->conn->prepare($sql);
        $stm->execute();
        return $stm->rowCount();
    }

    // Scalar
    public function getScalar($sql)
    {
        $stm = $this->conn->prepare($sql);
        $stm->execute();
        return (int)$stm->fetchColumn();
    }

    // Truy vấn 1 dòng dữ liệu
    public function getOne($sql)
    {
        $stm = $this->conn->prepare($sql);
        $stm->execute();
        $result = $stm->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    // Insert dữ liệu
    public function insert($table, $data)
    {
        $keys = array_keys($data);
        $column = implode(',', $keys); # phân tách keys
        $placeh = ':' . implode(',:', $keys);

        $sql = "INSERT INTO $table ($column) VALUES ($placeh)";

        $stm = $this->conn->prepare($sql);
        $rel = $stm->execute($data);

        return $rel;
    }

    // Update  dữ liệu
    public function update($table, $data, $condition = '')
    {
        $update = '';
        foreach ($data as $key => $value) {
            $update .= $key . '=:' . $key . ',';
        }
        $update = trim($update, ',');

        if (!empty($condition)) {
            $sql = "UPDATE $table SET $update WHERE $condition";
        } else {
            $sql = "UPDATE $table SET $update";
        }

        $stm = $this->conn->prepare($sql);
        $rel = $stm->execute($data);

        return $rel;
    }

    // Delete dữ liệu
    public function delete($table, $condition = '')
    {
        if (!empty($condition)) {
            $sql = "DELETE FROM $table WHERE $condition";
        } else {
            $sql = "DELETE FROM $table";
        }

        $stm = $this->conn->prepare($sql);
        $rel = $stm->execute();

        return $rel;
    }

    public function lastID()
    {
        return $this->conn->lastInsertID();
    }
}
