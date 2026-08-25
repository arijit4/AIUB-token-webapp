<?php
require_once "../db/db_connection.php";

class User
{
    private $conn;

    public function __construct()
    {
        $dbcon = new DBConnection();
        $this->conn = $dbcon->connect();
    }

    public function create_user($fullname, $uni_id, $password, $role): bool
    {
        $stmt = $this->conn->prepare("INSERT INTO user (uni_id, fullname, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $fullname, $uni_id, $password, $role);
        return $stmt->execute();
    }

    public function getUserById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM user WHERE id = ?");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function validateUser($id, $password)
    {
        $user = $this->getUserById($id);
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return null;
    }
}
