<?php
session_start();
include("config.php");

if (!isset($_SESSION['uid']) || !isset($_POST['room_id'])) {
    die(json_encode(['status' => 'error', 'message' => 'Accès non autorisé']));
}

$user_id = intval($_SESSION['uid']);
$room_id = intval($_POST['room_id']);
$message = trim($_POST['message']);

if (!empty($message)) {
    $stmt = mysqli_prepare($con, "INSERT INTO chat_messages (room_id, user_id, message, sent_at) VALUES (?, ?, ?, NOW())");
    mysqli_stmt_bind_param($stmt, 'iis', $room_id, $user_id, $message);
    mysqli_stmt_execute($stmt);

    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Message vide']);
}
?>