<?php
include_once "../db/db_connection.php";

class Rooms
{
    private $conn;

    public function __construct()
    {
        $dbcon = new DBConnection();
        $this->conn = $dbcon->connect();
    }

    public function get_all_rooms(): false|array|null
    {
        $stmt = $this->conn->prepare("SELECT * FROM rooms");
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function get_first_empty_room(): false|array|null
    {
        $stmt = $this->conn->prepare("SELECT id, name FROM rooms WHERE capacity > current_load LIMIT 1");
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function get_room_associated_with_teacher($teacher_id): false|array|null
    {
        $stmt = $this->conn->prepare("SELECT * FROM rooms WHERE user_id = ?");
        $stmt->bind_param("i", $teacher_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}