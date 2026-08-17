<?php

session_start();

function getUsersFromDB(): array
{
    $file = __DIR__ . '/../accounts.json';

    if (!file_exists($file)) {
        return [];
    }

    $jsonString = file_get_contents($file);
    $users = json_decode($jsonString, true);

    if (!is_array($users)) {
        return [];
    }

    return array_values(array_map(function ($user) {
        if (!is_array($user)) {
            return [];
        }

        return [
            'name' => $user['name'] ?? '',
            'id' => strtolower(trim($user['id'] ?? '')),
            'password' => $user['password'] ?? ($user['pass'] ?? ''),
            'role' => $user['role'] ?? ''
        ];
    }, $users));

}

$users = getUsersFromDB();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id = strtolower(trim($_POST['id']));
    $password = trim($_POST['password']);

    foreach ($users as $user) {

        if ($user['id'] == $id && $user['password'] == $password) {
            $_SESSION['id'] = $user['id'];
            $_SESSION['id'] = $user['id'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] == 'admin') {
                header("Location: ../pages/admin/dashboard.php");
            } else if ($user['role'] == 'student') {
                header("Location: ../pages/student/dashboard.php");
            } else if ($user['role'] == 'teacher') {
                header("Location: ../pages/teacher/dashboard.php");
            } else if ($user['role'] == 'supervisor') {
                header("Location: ../pages/supervisor/dashboard.php");
            }

            exit();
        }
    }

    $_SESSION['error_message'] = "Invalid id or password";

    header("Location: ../../login/login.php");
    exit();
} else {
    header("Location: ../../login/login.php");
}

?>