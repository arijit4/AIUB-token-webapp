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

    public function get_all_rooms()
    {
        $stmt = $this->conn->prepare("SELECT * FROM rooms");
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function get_first_empty_room(): false|array|null
    {
        $stmt = $this->conn->prepare("SELECT * FROM rooms WHERE capacity > current_load ORDER BY current_load ASC, id ASC LIMIT 1");
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function get_room_by_id($room_id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM rooms WHERE id = ?");
        $stmt->bind_param("i", $room_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}