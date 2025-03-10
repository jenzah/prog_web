<?php
session_start();
include("config.php");

if (!isset($_SESSION['uid'])) {
    echo json_encode(["status" => "error", "message" => "Utilisateur non connecté"]);
    exit();
}

$userId = $_SESSION['uid'];
$paymentId = isset($_GET['payment_id']) ? intval($_GET['payment_id']) : 0;

if ($paymentId > 0) {
    $checkQuery = mysqli_query($con, "SELECT * FROM payment WHERE payment_id='$paymentId' AND user_id='$userId' AND payment_status='pending'");

    if (mysqli_num_rows($checkQuery) > 0) {
        $cancelQuery = mysqli_query($con, "UPDATE payment SET payment_status = 'refunded' WHERE payment_id = '$paymentId'");
        if ($cancelQuery) {
            echo json_encode(["status" => "success", "message" => "Paiement annulé avec succès."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Erreur lors de l'annulation."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Ce paiement n'existe pas ou a déjà été traité."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Paiement invalide."]);
}
exit();
