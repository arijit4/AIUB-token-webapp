<?php
require_once "../model/tokens.php";
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
    $token_model = new tokens();
    $room_id = new rooms();
    $room_id = $room_id->get_first_empty_room();
    if($room_id === false) {
        $_SESSION['error_message'] = 'No empty rooms available.';
        header("Location: ../view/token_view.php");
        exit();
    }else{
        $token_id = $token_model->generateToken((int)$_SESSION['id'], (int)$room_id);
    }
//    $token_id = $token_model->generateToken((int)$_SESSION['id'],);

    if ($token_id !== false) {
        $_SESSION['token_id'] = $token_id;
        header("Location: ../view/token_view.php?token_id=" . $token_id);
    } else {
        $_SESSION['error_message'] = 'Token generation failed.';
        header("Location: ../view/token_view.php");
    }
    exit();
}


