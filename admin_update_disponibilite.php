<?php
session_start();
include("config.php");

// Activer l'affichage des erreurs (utile pour le debug, à désactiver en production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Vérifier si l'utilisateur est un admin
if (!isset($_SESSION['uid']) || $_SESSION['utype'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Vérifier si on reçoit bien les données
if (!isset($_POST['agent_id']) || !isset($_POST['disponibilites'])) {
    die("Erreur : Données manquantes.");
}

// Récupérer l'ID de l'agent
$agent_id = intval($_POST['agent_id']);
$disponibilites = $_POST['disponibilites']; // Tableau des jours et horaires

// Vérifier si l'agent existe bien dans la base
$check_agent = mysqli_query($con, "SELECT uid FROM user WHERE uid = $agent_id AND utype = 'agent'");
if (mysqli_num_rows($check_agent) == 0) {
    die("Erreur : L'agent n'existe pas.");
}

// Lister les jours de la semaine pour s'assurer qu'ils sont tous traités
$jours = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];

// Initialiser la liste des erreurs SQL
$errors = [];

// Insérer ou mettre à jour les disponibilités
foreach ($jours as $jour) {
    $is_working_day = isset($disponibilites[$jour]['is_working_day']) ? 1 : 0;
    $workday_start = $is_working_day ? $disponibilites[$jour]['start'] : '09:00:00';
    $workday_end = $is_working_day ? $disponibilites[$jour]['end'] : '17:00:00';

    // Vérifier si un enregistrement existe déjà pour ce jour
    $check_exist = mysqli_query($con, "SELECT * FROM agent_schedules WHERE agent_id = $agent_id AND day_of_week = '$jour'");

    if (mysqli_num_rows($check_exist) > 0) {
        // Mise à jour si l'entrée existe
        $query_update = "UPDATE agent_schedules 
                         SET workday_start = '$workday_start', workday_end = '$workday_end', is_working_day = $is_working_day 
                         WHERE agent_id = $agent_id AND day_of_week = '$jour'";
        if (!mysqli_query($con, $query_update)) {
            $errors[] = "Erreur lors de la mise à jour de $jour : " . mysqli_error($con);
        }
    } else {
        // Insérer si l'entrée n'existe pas
        $query_insert = "INSERT INTO agent_schedules (agent_id, day_of_week, workday_start, workday_end, is_working_day)
                         VALUES ($agent_id, '$jour', '$workday_start', '$workday_end', $is_working_day)";
        if (!mysqli_query($con, $query_insert)) {
            $errors[] = "Erreur lors de l'insertion de $jour : " . mysqli_error($con);
        }
    }
}

// Vérifier si des erreurs sont survenues
if (!empty($errors)) {
    foreach ($errors as $error) {
        echo "<p style='color:red;'>$error</p>";
    }
} else {
    header("Location: admin_disponibilite.php?agent_id=$agent_id&update=success");
    exit();
}
?>