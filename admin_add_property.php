<?php 
ini_set('session.cache_limiter','public');
session_cache_limiter(false);
session_start();
include("config.php");

if(!isset($_SESSION['uid'])) {
    header("location:login.php");
	exit();
}

// Check for admin access
if(empty($_SESSION['isAdmin'])) {
    // User is either not logged in or not an admin
    header("Location:unauthorised.php");
    exit();
}

// Messages d'erreur ou de succès
$error = "";
$msg = "";

if (isset($_POST['add'])) {
    // Récupération des champs du formulaire
    $title = mysqli_real_escape_string($con, $_POST['title']);
    $propertyDescription = mysqli_real_escape_string($con, $_POST['propertyDescription']);
    $propertyType = mysqli_real_escape_string($con, $_POST['propertyType']);
    $area = (int)$_POST['area'];
    $nbRooms = (int)$_POST['nbRooms'];
    $nbBathrooms = (int)$_POST['nbBathrooms'];
    $price = (int)$_POST['price'];
    $location = mysqli_real_escape_string($con, $_POST['location']);
    $city = mysqli_real_escape_string($con, $_POST['city']);
    $department = mysqli_real_escape_string($con, $_POST['department']);
    $status = mysqli_real_escape_string($con, $_POST['status']);
    $agentid = (int)$_SESSION['uid']; // Assigne l'ID de l'agent connecté

    // Gestion des images
    $upload_dir = "images/property";  // Dossier de stockage des images
    $pimage1 = $_FILES['pimage1']['name'] ? $upload_dir . basename($_FILES['pimage1']['name']) : "";
    $pimage2 = $_FILES['pimage2']['name'] ? $upload_dir . basename($_FILES['pimage2']['name']) : "";
    $pimage3 = $_FILES['pimage3']['name'] ? $upload_dir . basename($_FILES['pimage3']['name']) : "";

    move_uploaded_file($_FILES['pimage1']['tmp_name'], $pimage1);
    move_uploaded_file($_FILES['pimage2']['tmp_name'], $pimage2);
    move_uploaded_file($_FILES['pimage3']['tmp_name'], $pimage3);

    // Requête d'insertion SQL
    $sql = "INSERT INTO property (agentid, title, propertyDescription, propertyType, area, nbRooms, nbBathrooms, price, location, city, department, pimage1, pimage2, pimage3, status, date) 
            VALUES ('$agentid', '$title', '$propertyDescription', '$propertyType', '$area', '$nbRooms', '$nbBathrooms', '$price', '$location', '$city', '$department', '$pimage1', '$pimage2', '$pimage3', '$status', NOW())";

    $result = mysqli_query($con, $sql);

    if ($result) {
        $msg = "<p class='alert alert-success'>Propriété ajoutée avec succès !</p>";
    } else {
        $error = "<p class='alert alert-danger'>Erreur lors de l'ajout de la propriété.</p>";
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

<!--	Fonts
	========================================================-->
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">

<!--	Css Link
	========================================================-->
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
    <title>Ajouter une propriété</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>

<div id="page-wrapper">
    <div class="row">
        <?php include("include/header.php"); ?>
        <!-- Page Title -->
        <div class="banner-full-row page-banner" style="background-image:url('images/breadcrumb.jpg');">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <h2 class="page-name text-white text-uppercase"><b>Liste des Propriétés</b></h2>
                    </div>
                    <div class="col-md-6">
                        <nav aria-label="breadcrumb" class="float-md-right">
                            <ol class="breadcrumb bg-transparent m-0 p-0">
                                <li class="breadcrumb-item text-white"><a href="home.php">Accueil</a></li>
                                <li class="breadcrumb-item active">Liste des Propriétés</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>


        <div class="container mt-5">
            <h2 class="text-center text-secondary">Ajouter une Propriété</h2>
            <div class="col-md-8 offset-md-2">
                <?php echo $error; ?>
                <?php echo $msg; ?>
                <form method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Titre</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="propertyDescription" class="form-control" required></textarea>
                    </div>

                    <div class="form-group">
                        <label>Type de Propriété</label>
                        <select name="propertyType" class="form-control" required>
                            <option value="appartement">Appartement</option>
                            <option value="maison">Maison</option>
                            <option value="villa">Villa</option>
                            <option value="bureau">Bureau</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Superficie (m²)</label>
                        <input type="number" name="area" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Nombre de Chambres</label>
                        <input type="number" name="nbRooms" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Nombre de Salles de Bain</label>
                        <input type="number" name="nbBathrooms" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Prix (€)</label>
                        <input type="number" name="price" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Adresse</label>
                        <input type="text" name="location" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Ville</label>
                        <input type="text" name="city" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Département</label>
                        <input type="text" name="department" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Statut</label>
                        <select name="status" class="form-control" required>
                            <option value="disponible">A vendre</option>
                            <option value="vendu">A louer</option>
                        </select>
                    </div>

                    <h5 class="text-secondary">Images</h5>
                    <div class="form-group">
                        <label>Image 1</label>
                        <input type="file" name="pimage1" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Image 2</label>
                        <input type="file" name="pimage2" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Image 3</label>
                        <input type="file" name="pimage3" class="form-control">
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
