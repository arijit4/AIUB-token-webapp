<?php

session_start();
/*
function getUsersFromDB(): array
{
    // read from accounts.json temporarily
    // fetch from the db when available.
}

$users = getUsersFromDB();
 * */
$users = [
    ["id" => "1", "password" => "123", "role" => "admin"],
    ["id" => "2", "password" => "456", "role" => "student"],
    ["id" => "3", "password" => "789", "role" => "teacher"],
    ["id" => "4", "password" => "1234", "role" => "supervisor"]
];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    unset($_SESSION['error_message']);

    $id = strtolower(trim($_POST['id']));
    $password = trim($_POST['password']);

    foreach ($users as $user) {

        if ($user['id'] == $id && $user['password'] == $password) {

            $_SESSION['id'] = $user['id'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] == 'admin') {
                header("Location: ../view/admin_dashboard.php");
            } else if ($user['role'] == 'student') {
                header("Location: ../view/student_dashboard.php");
            } else if ($user['role'] == 'teacher') {
                header("Location: ../view/teacher_dashboard.php");
            } else if ($user['role'] == 'supervisor') {
                header("Location: ../view/supervisor_dashboard.php");
            }

            exit();
        }
    }

    $_SESSION['error_message'] = "Invalid id or password";

    header("Location: ../view/login.php");
    exit();
} else {
    header("Location: ../view/login.php");
}

?>