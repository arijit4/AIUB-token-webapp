<?php
require_once "../model/user.php";
session_start();
function appendToDB($data): void
{
//    $currentData = [];
//    $file = "../accounts.json";
//
//    if (file_exists($file)) {
//        $jsonString = file_get_contents($file);
//
//        $currentData = json_decode($jsonString, true);
//
//        if (!is_array($currentData)) {
//            $currentData = [];
//        }
//    }
//
//    $currentData[] = $data;
//    $updatedJsonString = json_encode($currentData, JSON_PRETTY_PRINT);
//    file_put_contents($file, $updatedJsonString, LOCK_EX);
    $user = new User();
    $user->create_user($data['name'], $data['id'], $data['pass'], $data['role']);
}

$name = $_POST["name"];
$id = trim($_POST["id"]);
$pass = $_POST["pass"];

$errors = 0;

$student_account_pattern = '/^\d{2}-\d{5}-[1-3]$/';
$teacher_account_pattern = '/^\d{4}-\d{4}-[1-3]$/';

if ($id == "") {
    $errors++;
    $_SESSION["error_id"] = "Invalid ID or Password";
}
if (!preg_match($student_account_pattern, $id) && !preg_match($teacher_account_pattern, $id)) {
    $errors++;
    $_SESSION["error_id"] = "Invalid ID or Password";
}
if ($errors == 0) {
    $newData = [
        'name' => $name,
        'id' => $id,
        'pass' => $pass,
        'role' => 'student'
    ];
    if (preg_match($teacher_account_pattern, $id)) {
        $newData['role'] = 'teacher';
    }

    appendToDB($newData);
    header("location: ../view/" . $newData['role'] . "_dashboard.php");
    exit();
} else {
    header("location: ../view/create-account.php");
    exit();
}