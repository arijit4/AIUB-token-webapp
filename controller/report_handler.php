<?php
session_start();
include_once "../model/Tokens.php";

if (!isset($_SESSION['id'])) {
    header("Location: ../view/login.php");
    exit();
}

if (($_SESSION['role'] ?? '') !== 'student') {
    header("Location: ../view/login.php");
    exit();
}

$token_model = new Tokens();
$current_token = null;

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['current_token'])) {
    $current_token = (int)$_POST['current_token'];
} else {
    $waiting_token = $token_model->get_waiting_token_for_user((int)$_SESSION['id']);
    if (!$waiting_token && isset($_SESSION['uni_id'])) {
        $waiting_token = $token_model->get_waiting_token_for_uni_id((string)$_SESSION['uni_id']);
    }
    if ($waiting_token) {
        $current_token = (int)$waiting_token['token_id'];
    }
}

if (!$current_token) {
    $_SESSION['error_message'] = "No active waiting token found to report.";
    header("Location: ../view/student_dashboard.php");
    exit();
}

$updated = $token_model->update_token_status($current_token, 'Missed');

if ($updated) {
    $_SESSION['status_message'] = "Your token has been marked as missed.";
} else {
    $_SESSION['error_message'] = "Failed to report absence. Please try again.";
}

header("Location: ../view/student_dashboard.php");
exit();
