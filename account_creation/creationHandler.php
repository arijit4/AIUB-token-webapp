<?php
$name = $_POST["name"];
$id = $_POST["id"];
$pass = $_POST["pass"];

$errors = 0;
if ($id != strlen("24-57775-2")) {
    $errors++;
}
if (strlen($pass) === 0 || $pass === "123") {
    $errors++;
}
if ($errors === 0) {
    header("location: ../dashboard/adminDashboard.php");
} else {
    header("location: ./createAccount.php");
}