<?php
require_once "../model/user.php";
session_start();
function appendToDB($data): bool
{
    $user = new User();
    return $user->create_user($data['name'], $data['id'], $data['pass'], $data['role']);
}

$name = $_POST["name"];
$id = trim($_POST["id"]);
$pass = $_POST["pass"];

$errors = 0;

$student_account_pattern = '/^\d{2}-\d{5}-[1-3]$/';
$teacher_account_pattern = '/^\d{4}-\d{4}-[1-3]$/';

if (empty($name)) {
    $errors++;
    $_SESSION["error_name"] = "Invalid name. Name cannot be empty.";
}
if ($id == "" || (!preg_match($student_account_pattern, $id) && !preg_match($teacher_account_pattern, $id))) {
    $errors++;
    $_SESSION["error_id"] = "Invalid ID or Password";
}
if (empty($pass)) {
    $errors++;
    $_SESSION["error_name"] = "Invalid ID or Password";
}
if ($errors == 0) {
    unset($_SESSION["error_name"]);
    unset($_SESSION["error_id"]);
    unset($_SESSION["error_creation"]);

    $newData = [
        'name' => $name,
        'id' => $id,
        'pass' => $pass,
        'role' => 'student'
    ];
    if (preg_match($teacher_account_pattern, $id)) {
        $newData['role'] = 'teacher';
    }

    if (appendToDB($newData)) {
        header("location: ../view/" . $newData['role'] . "_dashboard.php");
    } else {
        $_SESSION["error_creation"] = "Account creation failed. Please try again.";
        header("location: ../view/create-account.php");
    }
    exit();
} else {
    header("location: ../view/create-account.php");
    exit();
}