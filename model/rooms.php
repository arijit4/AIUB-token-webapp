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
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function get_first_empty_room(): false|array|null
    {
        $stmt = $this->conn->prepare("SELECT id, name FROM rooms WHERE capacity > current_load LIMIT 1");
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function get_number_of_tokens_in_each_room(): false|array|null
    {
        $stmt = $this->conn->prepare("
           SELECT
           r.name AS room_name,
           u.fullname AS supervisor_name,
           COUNT(t.token_id) AS token_count
           FROM rooms r
            LEFT JOIN users u ON r.supervisor_id = u.id
            LEFT JOIN token t ON r.id = t.room_id AND t.status = 'Waiting'
           GROUP BY r.id, r.name, u.fullname;
        ");
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function get_room_associated_with_teacher(int $teacher_id): false|array|null
    {
        $stmt = $this->conn->prepare("SELECT * FROM teacher_assignment WHERE user_id = ?");
        $stmt->bind_param("i", $teacher_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function get_room_by_supervisor(int $supervisor_id): false|array|null
    {
        $stmt = $this->conn->prepare("SELECT * FROM rooms WHERE supervisor_id = ?");
        $stmt->bind_param("i", $supervisor_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function get_teachers_in_room(int $room_id): false|array|null
    {
        $stmt = $this->conn->prepare("
            SELECT  u.id,u.fullname, u.uni_id
            FROM teacher_assignment ta
            JOIN users u ON ta.user_id = u.id
            WHERE ta.room_id = ?
        ");
        $stmt->bind_param("i", $room_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    public function assign_teacher_to_room(int $teacher_id, int $room_id): bool
    {
        $check = $this->conn->prepare("SELECT user_id FROM teacher_assignment WHERE user_id = ?");
        $check->bind_param("i", $teacher_id);
        $check->execute();
        if ($check->get_result()->fetch_assoc()) {
            return false;
        }

        $stmt = $this->conn->prepare("INSERT INTO teacher_assignment (user_id, room_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $teacher_id, $room_id);
        return $stmt->execute();
    }
 public function create_room(string $name, int $capacity, int $supervisor_id): bool
{
    $check = $this->conn->prepare("SELECT id FROM rooms WHERE supervisor_id = ?");
    $check->bind_param("i", $supervisor_id);
    $check->execute();
    if ($check->get_result()->fetch_assoc()) {
        return false;
    }

    $stmt = $this->conn->prepare("INSERT INTO rooms (name, capacity, current_load, supervisor_id) VALUES (?, ?, 0, ?)");
    $stmt->bind_param("sii", $name, $capacity, $supervisor_id);
    return $stmt->execute();
}

public function update_room(int $id, string $name, int $capacity, int $supervisor_id): bool
{
    $stmt = $this->conn->prepare("UPDATE rooms SET name = ?, capacity = ?, supervisor_id = ? WHERE id = ?");
    $stmt->bind_param("siii", $name, $capacity, $supervisor_id, $id);
    return $stmt->execute();
}

public function delete_room(int $id): bool
{
    $check = $this->conn->prepare("SELECT token_id FROM token WHERE room_id = ? AND status = 'Waiting'");
    $check->bind_param("i", $id);
    $check->execute();
    if ($check->get_result()->fetch_assoc()) {
        return false;
    }

    $stmt = $this->conn->prepare("DELETE FROM rooms WHERE id = ?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}

public function get_room_by_id(int $id): false|array|null
{
    $stmt = $this->conn->prepare("SELECT * FROM rooms WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

public function get_all_rooms_with_supervisor(): false|array|null
{
    $stmt = $this->conn->prepare("
        SELECT r.*, u.fullname AS supervisor_name, u.uni_id AS supervisor_uni_id
        FROM rooms r
        LEFT JOIN users u ON r.supervisor_id = u.id
        ORDER BY r.id
    ");
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}
}
