<?php 
ini_set('session.cache_limiter','public');
session_cache_limiter(false);
session_start();
include("../config.php");

if(!isset($_SESSION['uid'])) {
    header("location:../login.php");
}

// The page requires admin role
if(!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] !== true) {
    header("Location:../unauthorised.php");
    exit();
}

// Supprimer une propriété si "delete_id" est présent dans l'URL
if (isset($_GET['delete_id'])) {
    $pid = (int) $_GET['delete_id'];

    // Vérifier si la propriété existe
    $checkQuery = mysqli_query($con, "SELECT * FROM property WHERE pid='$pid'");
    if (mysqli_num_rows($checkQuery) > 0) {
        // Suppression
        $deleteQuery = mysqli_query($con, "DELETE FROM property WHERE pid='$pid'");
        if ($deleteQuery) {
            echo "<script>alert('Propriété supprimée avec succès.'); window.location='admin.php';</script>";
        } else {
            echo "<script>alert('Erreur lors de la suppression.');</script>";
        }
    } else {
        echo "<script>alert('Propriété introuvable.');</script>";
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

<!--	Fonts
	========================================================-->
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">

<!--	Css Link
	========================================================-->
<link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="../css/bootstrap-slider.css">
<link rel="stylesheet" type="text/css" href="../css/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="../css/layerslider.css">
<link rel="stylesheet" type="text/css" href="../css/color.css">
<link rel="stylesheet" type="text/css" href="../css/owl.carousel.min.css">
<link rel="stylesheet" type="text/css" href="../css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="../fonts/flaticon/flaticon.css">
<link rel="stylesheet" type="text/css" href="../css/style.css">
<link rel="stylesheet" type="text/css" href="../css/login.css">
<title>Omnes Immobilier - Propriétés</title>

<!-- Styles -->
<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="css/style.css">

</head>

<body>
<div id="page-wrapper">
    <div class="row"> 
        <!-- Header -->
        <?php include("../include/header.php");?>

        <!-- Page Title -->
        <div class="banner-full-row page-banner" style="background-image:url('../images/breadcrumb.jpg');">
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

        <!-- Liste des propriétés -->
        <div class="full-row bg-gray">
            <div class="container">

                <!-- Bouton Ajouter une Propriété -->
                <div class="row mb-3">
                    <div class="col-lg-12 text-right">
                        <a href="add_property.php">
                        <img src="ajouter.png" class="database-icon" title="Ajouter une propriété" style="width: 30px !important; height: 30px !important;">
                        </a>
                    </div>
                </div>

                <div class="row mb-5">
                    <div class="col-lg-12">
                        <h2 class="text-secondary text-center">Propriétés Disponibles</h2>
                    </div>
                </div>
                <table class="table table-bordered">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th>Image</th>
                            <th>Titre</th>
                            <th>Type</th>
                            <th>Superficie</th>
                            <th>Chambres</th>
                            <th>Salles de bain</th>
                            <th>Prix</th>
                            <th>Localisation</th>
                            <th>Statut</th>
                            <th>Actions</th> <!-- Nouvelle colonne pour les boutons Modifier/Supprimer -->
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                    $query = mysqli_query($con, "SELECT * FROM `property`");
                    while($row = mysqli_fetch_array($query)) {
                    ?>
                        <tr>
                            <td><img src="images/property/<?php echo $row['pimage1']; ?>" width="100"></td>
                            <td><?php echo $row['title']; ?></td>
                            <td><?php echo ucfirst($row['propertyType']); ?></td>
                            <td><?php echo $row['area']; ?> m²</td>
                            <td><?php echo $row['nbRooms']; ?></td>
                            <td><?php echo $row['nbBathrooms']; ?></td>
                            <td><?php echo number_format($row['price'], 0, ',', ' '); ?> €</td>
                            <td><?php echo $row['location'] . ', ' . $row['city'] . ' (' . $row['department'] . ')'; ?></td>
                            <td><?php echo ucfirst($row['status']); ?></td>
                            <td>
    <!-- Bouton Modifier -->
    <a href="edit_property.php?id=<?php echo $row['pid']; ?>">
        <img src="modifier.png" class="img-action" style="width: 23px !important; height: 23px !important;" title="Modifier" >
    </a>

    <!-- Bouton Supprimer -->
    <a href="admin.php?delete_id=<?php echo $row['pid']; ?>" onclick="return confirm('Voulez-vous vraiment supprimer cette propriété ?');">
        <img src="supprimer.png" class="img-action" style="width: 23px !important; height: 23px !important;" title="Supprimer">
    </a>
</td>

                        </tr>
                    <?php } ?>
                    </tbody>
                </table>            
            </div>
        </div>

        <!-- Footer -->
        <?php include("../include/footer.php");?>
    </div>
</div>

<!-- Scripts -->
<script src="../js/jquery.min.js"></script> 
<script src="../js/bootstrap.min.js"></script> 
</body>
</html>
