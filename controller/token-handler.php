<?php
require_once "../model/Tokens.php";
require_once "../model/rooms.php";
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../view/login.php");
    exit();
}

if (($_SESSION['role'] ?? '') !== 'student') {
    header("Location: ../view/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generate_token'])) {
    $token_model = new Tokens();
    $existing_waiting_token = $token_model->get_waiting_token_for_user((int)$_SESSION['id']);
    if ($existing_waiting_token) {
        $_SESSION['error_message'] = 'You already have an active token.';
        $_SESSION['token_id'] = (int)$existing_waiting_token['token_id'];
        header("Location: ../view/token_view.php?token_id=" . (int)$existing_waiting_token['token_id']);
        exit();
    }

    $room_model = new Rooms();
    $room_data = $room_model->get_first_empty_room();
    if (!$room_data || !isset($room_data['id'])) {
        $_SESSION['error_message'] = 'No empty rooms available.';
        header("Location: ../view/token_view.php");
        exit();
    }

    $token_id = $token_model->generateToken((int)$_SESSION['id'], (int)$room_data['id']);

    if ($token_id !== false) {
        $_SESSION['token_id'] = $token_id;
        header("Location: ../view/token_view.php?token_id=" . $token_id);
    } else {
        $_SESSION['error_message'] = 'Token generation failed.';
        header("Location: ../view/token_view.php");
    }
    exit();
}


