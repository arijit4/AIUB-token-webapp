<?php

session_start();

$users = [
    ["username" => "1", "password" => "123", "role" => "admin"],
    ["username" => "2", "password" => "456", "role" => "student"],
    ["username" => "3", "password" => "789", "role" => "teacher"],
    ["username" => "4", "password" => "1234", "role" => "supervisor"]
];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $username = strtolower(trim($_POST['username']));
    $password = trim($_POST['password']);

    foreach ($users as $user) {

        if ($user['username'] == $username && $user['password'] == $password) {

            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] == 'admin') {
                header("Location: adminHome.php");
            } else if ($user['role'] == 'student') {
                header("Location: dashboard.php");
            } else if ($user['role'] == 'teacher') {
                header("Location: teacherHome.php");
            } else if ($user['role'] == 'supervisor') {
                header("Location: supervisorHome.php");
            }

            exit();
        }
    }

    $_SESSION['error_message'] = "Invalid username or password";

    header("Location: login.php");
    exit();
} else {
    header("Location: login.php");
}

?>