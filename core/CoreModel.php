<?php

class CoreModel
{
    private $conn;
    public function __construct()
    {
        $this->conn = Database::connectDPO();
    }

    // Truy vấn nhiều dòng dữ liệu
    function getAll($sql)
    {
        $stm = $this->conn->prepare($sql);
        $stm->execute();
        $result = $stm->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    // Đếm số dòng trả về
    function getRows($sql)
    {
        $stm = $this->conn->prepare($sql);
        $stm->execute();
        return $stm->rowCount();
    }

    // Truy vấn 1 dòng dữ liệu
    function getOne($sql)
    {
        $stm = $this->conn->prepare($sql);
        $stm->execute();
        $result = $stm->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    // Insert dữ liệu
    function insert($table, $data)
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
    function update($table, $data, $condition = '')
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
    function delete($table, $condition = '')
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

    function lastID()
    {
        return $this->conn->lastInsertID();
    }
}
