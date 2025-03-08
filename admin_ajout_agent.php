<?php 
ini_set('session.cache_limiter','public');
session_cache_limiter(false);
session_start();
include("config.php");

if(!isset($_SESSION['uid'])) {
    header("location:login.php");
    exit();
}

// Vérification si l'utilisateur est un administrateur
if(empty($_SESSION['isAdmin'])) {
    header("Location:unauthorised.php");
    exit();
}

// Messages d'erreur ou de succès
$error = "";
$msg = "";

// Vérifier si le formulaire a été soumis
if (isset($_POST['add'])) {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $firstname = mysqli_real_escape_string($con, $_POST['firstname']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $phone = mysqli_real_escape_string($con, $_POST['phone']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Sécurisation du mot de passe
    $specialty = mysqli_real_escape_string($con, $_POST['specialty']);

    // Dossier de stockage des images
    $upload_dir = "images/profile_pic/";

    // Vérifier si une image est téléchargée
    if(isset($_FILES['uimage']) && $_FILES['uimage']['error'] == 0) {
        $uimage = basename($_FILES['uimage']['name']); // Stocker uniquement le nom du fichier
        move_uploaded_file($_FILES['uimage']['tmp_name'], $upload_dir . $uimage); // Déplacer l'image dans le dossier
    } else {
        $uimage = "default.png"; // Image par défaut si aucune image n'est téléchargée
    }

    // Requête SQL pour ajouter l'agent dans la base de données
    $sql = "INSERT INTO user (uname, ufirstname, uemail, uphone, upass, utype, uimage, specialty) 
            VALUES ('$name', '$firstname', '$email', '$phone', '$password', 'agent', '$uimage', '$specialty')";

    $result = mysqli_query($con, $sql);

    if ($result) {
        $msg = "<p class='alert alert-success'>Agent ajouté avec succès !</p>";
    } else {
        $error = "<p class='alert alert-danger'>Erreur lors de l'ajout de l'agent.</p>";
    }
}
?>


<!DOCTYPE html>
<html lang="fr">
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
        <?php include("include/header.php"); ?>

        <div class="banner-full-row page-banner" style="background-image:url('images/breadcrumb.jpg');">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <h2 class="page-name text-white text-uppercase"><b>Ajouter un Agent</b></h2>
                    </div>
                    <div class="col-md-6">
                        <nav aria-label="breadcrumb" class="float-md-right">
                            <ol class="breadcrumb bg-transparent m-0 p-0">
                                <li class="breadcrumb-item text-white"><a href="admin_dashboard.php">Tableau de Bord</a></li>
                                <li class="breadcrumb-item active">Ajouter un Agent</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <div class="container mt-5">
            <h2 class="text-center text-secondary">Ajouter un Agent Immobilier</h2>
            <div class="col-md-8 offset-md-2">
                <?php echo $error; ?>
                <?php echo $msg; ?>
                <form method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Nom</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Prénom</label>
                        <input type="text" name="firstname" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Téléphone</label>
                        <input type="text" name="phone" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Mot de passe</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Spécialité</label>
                        <input type="text" name="specialty" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Photo de Profil</label>
                        <input type="file" name="uimage" class="form-control">
                    </div>

                    <button type="submit" name="add" class="btn btn-primary btn-block mt-3">Ajouter</button>
                </form>
            </div>
        </div>

        <?php include("include/footer.php"); ?>
    </div>
</div>

<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
