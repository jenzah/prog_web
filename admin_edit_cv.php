<?php
require('lib/fpdf.php'); // Inclure FPDF

ini_set('session.cache_limiter', 'public');
session_cache_limiter(false);
session_start();
include("config.php");

if (!isset($_SESSION['uid'])) {
    header("Location:unauthorised.php");
    exit();
}

// Vérifie si l'utilisateur est un agent ou un administrateur
if (!isset($_SESSION['isAdmin']) && !isset($_SESSION['isAgent'])) {
    header("Location:unauthorised.php");
    exit();
}

// Si c'est un agent, il ne peut modifier que son propre CV
if ($_SESSION['isAgent'] && $_SESSION['uid'] != $_GET['cv_id']) {
    header("Location:unauthorised.php");
    exit();
}


if (!isset($_GET['cv_id'])) {
    echo "<script>alert('Aucun agent sélectionné.'); window.location='admin_agent.php';</script>";
    exit();
}

$agent_id = (int) $_GET['cv_id'];
mysqli_set_charset($con, "utf8");

$agentQuery = mysqli_query($con, "SELECT * FROM user WHERE uid='$agent_id' AND utype='agent'");
$agent = mysqli_fetch_assoc($agentQuery);

if (!$agent) {
    echo "<script>alert('Agent introuvable.'); window.location='admin_agent.php';</script>";
    exit();
}

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $formations = !empty($_POST['formations']) ? ucfirst(mysqli_real_escape_string($con, $_POST['formations'])) : "Non renseignées";
    $experiences = !empty($_POST['experiences']) ? ucfirst(mysqli_real_escape_string($con, $_POST['experiences'])) : "Non renseignées";

    // Vérifier si le dossier existe, sinon le créer
    $cv_dir = "images/cv/";
    if (!is_dir($cv_dir)) {
        mkdir($cv_dir, 0777, true);
    }

    // Génération du fichier CV (PDF)
    $filename = "CV_" . strtoupper(str_replace(" ", "_", $agent['uname'])) . "_" . strtoupper(str_replace(" ", "_", $agent['ufirstname'])) . ".pdf";
    $filepath = $cv_dir . $filename;

    $pdf = new FPDF();
    $pdf->AddPage();

    // 🎨 Définition des couleurs
    $headerColor = [44, 62, 80]; // Bleu foncé
    $sidebarColor = [54, 69, 79]; // Gris-bleu pour la colonne gauche
    $textColor = [255, 255, 255]; // Texte blanc
    $blackText = [0, 0, 0];

     // 🔹 HEADER (Nom + Spécialité)
     $pdf->SetFillColor($headerColor[0], $headerColor[1], $headerColor[2]);
     $pdf->SetTextColor(255, 255, 255);
     $pdf->SetFont('Arial', 'B', 22);
     $pdf->Cell(190, 15, mb_convert_encoding(strtoupper($agent['uname'] . " " . $agent['ufirstname']), 'ISO-8859-1', 'UTF-8'), 0, 1, 'C', true);
 
     // Ajouter la spécialité sous le nom
     $pdf->SetFont('Arial', 'I', 14);
     $pdf->SetTextColor(220, 220, 220); // Texte gris clair
     $specialty = !empty($agent['specialty']) ? ucfirst($agent['specialty']) : "Spécialité non spécifiée";
     $pdf->Cell(190, 10, mb_convert_encoding($specialty, 'ISO-8859-1', 'UTF-8'), 0, 1, 'C', true);
     $pdf->Ln(10);

    // 🔹 COLONNE GAUCHE (Photo + Coordonnées)
    $pdf->SetFillColor($sidebarColor[0], $sidebarColor[1], $sidebarColor[2]);
    $pdf->Rect(10, 40, 70, 220, 'F'); // Colonne gauche

    // Ajouter la photo de l'agent
    $photo_path = "images/profile_pic/" . $agent['uimage'];
    if (file_exists($photo_path)) {
        $pdf->Image($photo_path, 20, 50, 50, 50);
    } else {
        $pdf->Rect(20, 50, 50, 50, 'D'); // Placeholder si l'image n'existe pas
    }

    // Coordonnées
    $pdf->SetTextColor($textColor[0], $textColor[1], $textColor[2]);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetXY(20, 110);
    $pdf->Cell(60, 10, "Email", 0, 1, 'L');
    $pdf->SetFont('Arial', '', 10);
    $pdf->SetXY(20, 120);
    $pdf->MultiCell(50, 5, mb_convert_encoding($agent['uemail'], 'ISO-8859-1', 'UTF-8'));

    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetXY(20, 140);
    $pdf->Cell(60, 10, "Telephone", 0, 1, 'L');
    $pdf->SetFont('Arial', '', 10);
    $pdf->SetXY(20, 150);
    $pdf->MultiCell(50, 5, mb_convert_encoding($agent['uphone'], 'ISO-8859-1', 'UTF-8'));

    // 🔹 COLONNE DROITE **(Formations & Expériences côte à côte)**
    $pdf->SetXY(90, 50);
    $pdf->SetTextColor($blackText[0], $blackText[1], $blackText[2]);

    // Formations (Alignées à gauche de la colonne droite)
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->SetXY(90, 60);
    $pdf->Cell(50, 10, "Formations", 0, 0, 'L'); // Ne passe pas à la ligne immédiatement
    $pdf->SetFont('Arial', '', 12);
    $pdf->SetXY(90, 70);
    $pdf->MultiCell(50, 8, mb_convert_encoding($formations, 'ISO-8859-1', 'UTF-8'));

    // Expériences (Alignées à droite de la colonne droite)
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->SetXY(140, 60);
    $pdf->Cell(50, 10, "Experiences", 0, 1, 'L'); // Passe à la ligne
    $pdf->SetFont('Arial', '', 12);
    $pdf->SetXY(140, 70);
    $pdf->MultiCell(50, 8, mb_convert_encoding($experiences, 'ISO-8859-1', 'UTF-8'));

    // 🔹 Sauvegarde du fichier
    $pdf->Output($filepath, 'F');

    // 🔹 Mise à jour de la base de données
    $updateQuery = "UPDATE user SET formations='$formations', experiences='$experiences', cv='$filename' WHERE uid='$agent_id'";
    if (mysqli_query($con, $updateQuery)) {
        echo "<script>alert('CV mis à jour avec succès.'); window.location='admin_agent.php';</script>";
    } else {
        echo "<script>alert('Erreur lors de la mise à jour du CV dans la base de données.');</script>";
    }
}
?>







<!DOCTYPE html>
<html>

<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Required meta tags -->
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<!-- Meta Tags -->
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<link rel="shortcut icon" href="images/favicon.ico">

<!--	Fonts	-->
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">

<!--	Css Link	-->
<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="css/bootstrap-slider.css">
<link rel="stylesheet" type="text/css" href="css/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="css/layerslider.css">
<link rel="stylesheet" type="text/css" href="css/color.css">
<link rel="stylesheet" type="text/css" href="css/owl.carousel.min.css">
<link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="fonts/flaticon/flaticon.css">
<link rel="stylesheet" type="text/css" href="css/style.css">
<link rel="stylesheet" type="text/css" href="css/login.css">
<title>Omnes Immobilier - Agents</title>

<!-- Styles -->
<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="css/style.css">

</head>
<body>
<div id="page-wrapper">
    <div class="row"> 
        <!-- Header -->
        <?php include("include/header.php");?>

        <!-- Page Title -->
        <div class="banner-full-row page-banner" style="background-image:url('images/breadcrumb.jpg');">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <h2 class="page-name text-white text-uppercase"><b>Liste des Agents</b></h2>
                    </div>
                    <div class="col-md-6">
                        <nav aria-label="breadcrumb" class="float-md-right">
                            <ol class="breadcrumb bg-transparent m-0 p-0">
                                <li class="breadcrumb-item text-white"><a href="home.php">Accueil</a></li>
                                <li class="breadcrumb-item active">Liste des Agents</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

<div class="container">
<h2 class="mt-4">Modifier le CV</h2>
<form method="POST">
    <div class="form-group">
        <label>Nom</label>
        <input type="text" class="form-control" value="<?php echo $agent['uname']; ?>" readonly>
    </div>
    
    <div class="form-group">
        <label>Prénom</label>
        <input type="text" class="form-control" value="<?php echo $agent['ufirstname']; ?>" readonly>
    </div>

    <div class="form-group">
        <label>Spécialité</label>
        <input type="text" class="form-control" value="<?php echo !empty($agent['specialty']) ? $agent['specialty'] : 'Non spécifiée'; ?>" readonly>
    </div>

    <div class="form-group">
        <label>Email</label>
        <input type="email" class="form-control" value="<?php echo $agent['uemail']; ?>" readonly>
    </div>

    <div class="form-group">
        <label>Téléphone</label>
        <input type="text" class="form-control" value="<?php echo $agent['uphone']; ?>" readonly>
    </div>

    <div class="form-group">
        <label>Formations</label>
        <textarea name="formations" class="form-control"><?php echo $agent['formations'] ?? ''; ?></textarea>
    </div>

    <div class="form-group">
        <label>Expériences</label>
        <textarea name="experiences" class="form-control"><?php echo $agent['experiences'] ?? ''; ?></textarea>
    </div>

    <button type="submit" class="btn btn-primary">Enregistrer</button>
    <a href="admin_agent.php" class="btn btn-secondary">Annuler</a>
</form>

</div>
 <!-- Footer -->
 <?php include("include/footer.php");?>
    </div>
</div>

<!-- Scripts -->
<script src="js/jquery.min.js"></script> 
<script src="js/bootstrap.min.js"></script> 
</body>
</html>
