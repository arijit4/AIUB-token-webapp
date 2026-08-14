<?php
function appendToDB($data): void
{
    $currentData = [];
    $file = "../accounts.json";

    if (file_exists($file)) {
        $jsonString = file_get_contents($file);

        $currentData = json_decode($jsonString, true);

        if (!is_array($currentData)) {
            $currentData = [];
        }
    }

    $currentData[] = $data;
    $updatedJsonString = json_encode($currentData, JSON_PRETTY_PRINT);
    file_put_contents($file, $updatedJsonString, LOCK_EX);
}

$name = $_POST["name"];
$id = trim($_POST["id"]);
$pass = $_POST["pass"];

$errors = 0;

$student_account_pattern = '/^\d{2}-\d{5}-[1-3]$/';
$teacher_account_pattern = '/^\d{4}-\d{4}-[1-3]$/';

if (!preg_match($student_account_pattern, $id) && !preg_match($teacher_account_pattern, $id)) {
    $errors++;
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

    header("location: ../dashboard/adminDashboard.php");
} else {
    header("location: ./createAccount.php");
}