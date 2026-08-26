<?php
include_once "../db/db_connection.php";

class tokens
{
    private $conn;

    public function __construct()
    {
        $dbcon = new DBConnection();
        $this->conn = $dbcon->connect();
    }

    public function generateToken(int $user_id, int $room_id): int|false
    {
        $already_exists = $this->token_already_exists($user_id);
        if (!$already_exists) {
            $stmt = $this->conn->prepare("INSERT INTO token (user_id, room_id) VALUES (?, ?)");
            $stmt->bind_param("ii", $user_id, $room_id);

            if ($stmt->execute()) {
                return (int)$this->conn->insert_id;
            }
        }
        return false;
    }
    public function token_already_exists(int $user_id): bool
    {
        $stmt = $this->conn->prepare("SELECT user_id FROM token WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $token = $stmt->get_result()->fetch_assoc();
        return (bool)$token;
    }

}