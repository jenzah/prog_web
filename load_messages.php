<?php
session_start();
include("config.php");

if (!isset($_SESSION['uid']) || !isset($_POST['room_id'])) {
    die('Accès non autorisé');
}

$room_id = intval($_POST['room_id']);
$user_id = intval($_SESSION['uid']);

$messages = mysqli_query($con, "
    SELECT chat_messages.*, user.uname, user.utype 
    FROM chat_messages 
    JOIN user ON chat_messages.user_id = user.uid 
    WHERE chat_messages.room_id = $room_id 
    ORDER BY chat_messages.sent_at ASC
");

$output = '';
while ($msg = mysqli_fetch_assoc($messages)) {
    $nomExpediteur = ($msg['user_id'] == $user_id) ? "Vous" : htmlspecialchars($msg['uname']);
    $output .= "<p><strong>" . $nomExpediteur . ":</strong> " . htmlspecialchars($msg['message']) . "</p>";
}
echo $output;

?>