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
        $already_exists = $this->user_already_exists($uni_id);
        if (!$already_exists) {
            $stmt = $this->conn->prepare("INSERT INTO user (uni_id, fullname, password, role) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $uni_id, $fullname, $password, $role);
            return $stmt->execute();
        }
        return false;
    }

    public function user_already_exists($uni_id): bool
    {
        $stmt = $this->conn->prepare("SELECT uni_id FROM user WHERE uni_id = ?");
        $stmt->bind_param("s", $uni_id);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        if ($user) return true;
        else return false;
    }
}
