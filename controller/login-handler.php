<?php
require_once "../model/users.php";
session_start();

function verifyLogin($uni_id, $password): bool
{
    $user = new Users();
    return $user->verify_login($uni_id, $password);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    unset($_SESSION['id']);
    unset($_SESSION['name']);
    unset($_SESSION['role']);
    unset($_SESSION['error_message']);

    $uni_id = strtolower(trim($_POST['id']));
    $password = trim($_POST['password']);

    if (verifyLogin($uni_id, $password)) {
        $user_model = new Users();
        $user = $user_model->get_user($uni_id);
        
        $_SESSION['id'] = $user['id'];
        $_SESSION['uni_id'] = $user['uni_id'];
        $_SESSION['name'] = $user['fullname'];
        $_SESSION['role'] = $user['role'];

        header("Location: ../view/" . $user['role'] . "_dashboard.php");
    } else {
        $_SESSION['error_message'] = "Invalid id or password";
        header("Location: ../view/login.php");
    }
    exit();
} else {
    header("Location: ../view/login.php");
}

?>