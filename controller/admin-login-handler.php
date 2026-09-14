<?php
session_start();
require_once "../model/users.php";
require_once "../model/rooms.php";

// Only admin can access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

$usersModel = new Users();
$roomsModel = new Rooms();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // ========== ROOM ACTIONS ==========
    if ($action === 'create_room') {
        $name = trim($_POST['name']);
        $capacity = (int)$_POST['capacity'];
        $supervisor_id = (int)$_POST['supervisor_id'];

        if ($roomsModel->create_room($name, $capacity, $supervisor_id)) {
            $_SESSION['success_message'] = "Room created successfully!";
        } else {
            $_SESSION['error_message'] = "Failed to create room. Supervisor may already be assigned.";
        }
        header("Location: ../view/admin_dashboard.php?tab=rooms");
        exit();
    }

    if ($action === 'update_room') {
        $id = (int)$_POST['room_id'];
        $name = trim($_POST['name']);
        $capacity = (int)$_POST['capacity'];
        $supervisor_id = (int)$_POST['supervisor_id'];

        if ($roomsModel->update_room($id, $name, $capacity, $supervisor_id)) {
            $_SESSION['success_message'] = "Room updated successfully!";
        } else {
            $_SESSION['error_message'] = "Failed to update room.";
        }
        header("Location: ../view/admin_dashboard.php?tab=rooms");
        exit();
    }

    if ($action === 'delete_room') {
        $id = (int)$_POST['room_id'];
        if ($roomsModel->delete_room($id)) {
            $_SESSION['success_message'] = "Room deleted successfully!";
        } else {
            $_SESSION['error_message'] = "Cannot delete room. It may have waiting tokens.";
        }
        header("Location: ../view/admin_dashboard.php?tab=rooms");
        exit();
    }

    // ========== USER ACTIONS ==========
    if ($action === 'update_user') {
        $id = (int)$_POST['user_id'];
        $fullname = trim($_POST['fullname']);
        $uni_id = trim($_POST['uni_id']);
        $role = $_POST['role'];
        $password = trim($_POST['password'] ?? '');

        if ($usersModel->update_user_full($id, $fullname, $uni_id, $role, $password ?: null)) {
            $_SESSION['success_message'] = "User updated successfully!";
        } else {
            $_SESSION['error_message'] = "Failed to update user.";
        }
        header("Location: ../view/admin_dashboard.php?tab=users");
        exit();
    }

    if ($action === 'delete_user') {
        $id = (int)$_POST['user_id'];
        if ($usersModel->delete_user($id)) {
            $_SESSION['success_message'] = "User deleted successfully!";
        } else {
            $_SESSION['error_message'] = "Cannot delete user. User may be assigned as supervisor of a room.";
        }
        header("Location: ../view/admin_dashboard.php?tab=users");
        exit();
    }

    // ========== REGISTRATION PROCESS (Start / Stop system) ==========
    // Simple system status using a file or you can later add a settings table
    if ($action === 'start_registration') {
        file_put_contents("../system_status.txt", "OPEN");
        $_SESSION['success_message'] = "Registration process STARTED. Students can now take tokens.";
        header("Location: ../view/admin_dashboard.php?tab=process");
        exit();
    }

    if ($action === 'stop_registration') {
        file_put_contents("../system_status.txt", "CLOSED");
        $_SESSION['success_message'] = "Registration process STOPPED. Students cannot take new tokens.";
        header("Location: ../view/admin_dashboard.php?tab=process");
        exit();
    }
}

header("Location: ../view/admin_dashboard.php");
exit();
