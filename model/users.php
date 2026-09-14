<?php
require_once "../db/db_connection.php";

class Users
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
            $stmt = $this->conn->prepare("INSERT INTO users (uni_id, fullname, password, role) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $uni_id, $fullname, $password, $role);
            return $stmt->execute();
        }
        return false;
    }

    public function update_user(int $id, string $fullname, string $password): bool
    {
        $stmt = $this->conn->prepare("UPDATE users SET fullname = ?, password = ? WHERE id = ?");
        $stmt->bind_param("ssi", $fullname, $password, $id);
        return $stmt->execute();
    }

    public function verify_login($uni_id, $password): bool
    {
        $stmt = $this->conn->prepare("SELECT password FROM users WHERE uni_id = ?");
        $stmt->bind_param("s", $uni_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return ($password == $row['password']);
        }
        return false;
    }

    public function verify_login_admin($uni_id, $password): bool
    {
        $stmt = $this->conn->prepare("SELECT password FROM users WHERE uni_id = ? AND `role` = ?");
        $role = "admin";
        $stmt->bind_param("ss", $uni_id, $role);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return ($password == $row['password']);
        }
        return false;
    }

    public function verify_login_supervisor($uni_id, $password): bool
    {
        $stmt = $this->conn->prepare("SELECT password FROM users WHERE uni_id = ? AND `role` = ?");
        $role = "supervisor";
        $stmt->bind_param("ss", $uni_id, $role);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return ($password == $row['password']);
        }
        return false;
    }

    public function verify_login_teacher($uni_id, $password): bool
    {
        $stmt = $this->conn->prepare("SELECT password FROM users WHERE uni_id = ? AND `role` = ?");
        $role = "teacher";
        $stmt->bind_param("ss", $uni_id, $role);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return ($password == $row['password']);
        }
        return false;
    }

    public function verify_login_student($uni_id, $password): bool
    {
        $stmt = $this->conn->prepare("SELECT password FROM users WHERE uni_id = ? AND `role` = ?");
        $role = "student";
        $stmt->bind_param("ss", $uni_id, $role);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return ($password == $row['password']);
        }
        return false;
    }

    public function get_user($uni_id): false|array|null
    {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE uni_id = ?");
        $stmt->bind_param("s", $uni_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function user_already_exists($uni_id): bool
    {
        $stmt = $this->conn->prepare("SELECT uni_id FROM users WHERE uni_id = ?");
        $stmt->bind_param("s", $uni_id);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        if ($user) return true;
        else return false;
    }

    public function get_unassigned_teacher(): false|array|null
    {
        $stmt = $this->conn->prepare("
        SELECT u.id,u.uni_id,u.fullname
        FROM users u
        LEFT JOIN teacher_assignment ta ON u.id=ta.user_id
        WHERE u.role='teacher'AND ta.user_id IS NULL
        ");
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
 public function get_all_users(): false|array|null
{
    $stmt = $this->conn->prepare("SELECT id, uni_id, fullname, role, created_at FROM users ORDER BY role, fullname");
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

public function get_user_by_id(int $id): false|array|null
{
    $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

public function update_user_full(int $id, string $fullname, string $uni_id, string $role, string $password = null): bool
{
    if ($password !== null && $password !== '') {
        $stmt = $this->conn->prepare("UPDATE users SET fullname = ?, uni_id = ?, role = ?, password = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $fullname, $uni_id, $role, $password, $id);
    } else {
        $stmt = $this->conn->prepare("UPDATE users SET fullname = ?, uni_id = ?, role = ? WHERE id = ?");
        $stmt->bind_param("sssi", $fullname, $uni_id, $role, $id);
    }
    return $stmt->execute();
}

public function delete_user(int $id): bool
{
    $check = $this->conn->prepare("SELECT id FROM rooms WHERE supervisor_id = ?");
    $check->bind_param("i", $id);
    $check->execute();
    if ($check->get_result()->fetch_assoc()) {
        return false;
    }

    $stmt = $this->conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}

public function get_all_supervisors(): false|array|null
{
    $stmt = $this->conn->prepare("SELECT id, uni_id, fullname FROM users WHERE role = 'supervisor'");
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

public function get_unassigned_supervisors(): false|array|null
{
    $stmt = $this->conn->prepare("
        SELECT u.id, u.uni_id, u.fullname
        FROM users u
        LEFT JOIN rooms r ON u.id = r.supervisor_id
        WHERE u.role = 'supervisor' AND r.supervisor_id IS NULL
    ");
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}
}
