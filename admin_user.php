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

// Supprimer un utilisateur si "delete_id" est présent dans l'URL
if (isset($_GET['delete_id'])) {
    $user_id = (int) $_GET['delete_id'];

    // Vérifier si l'utilisateur existe
    $checkQuery = mysqli_query($con, "SELECT * FROM user WHERE uid='$user_id' AND utype='user'");
    if (mysqli_num_rows($checkQuery) > 0) {
        // Suppression
        $deleteQuery = mysqli_query($con, "DELETE FROM user WHERE uid='$user_id'");
        if ($deleteQuery) {
            echo "<script>alert('Utilisateur supprimé avec succès.'); window.location='admin_user.php';</script>";
        } else {
            echo "<script>alert('Erreur lors de la suppression.');</script>";
        }
    } else {
        echo "<script>alert('Utilisateur introuvable.');</script>";
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
<title>Omnes Immobilier - Utilisateurs</title>

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
                        <h2 class="page-name text-white text-uppercase"><b>Liste des Utilisateurs</b></h2>
                    </div>
                    <div class="col-md-6">
                        <nav aria-label="breadcrumb" class="float-md-right">
                            <ol class="breadcrumb bg-transparent m-0 p-0">
                                <li class="breadcrumb-item text-white"><a href="home.php">Accueil</a></li>
                                <li class="breadcrumb-item active">Liste des Utilisateurs</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des utilisateurs -->
        <div class="full-row">
            <div class="container">
                <div class="row mb-5">
                    <div class="col-lg-12">
                        <h2 class="text-secondary text-center double-down-line">Utilisateurs Inscrits</h2>
                    </div>
                </div>

                <table class="table table-bordered">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th>Photo</th>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Téléphone</th>
                            <th>Actions</th> <!-- Nouvelle colonne pour les boutons Supprimer -->
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                    $query = mysqli_query($con, "SELECT * FROM user WHERE utype='user'");
                    while($row = mysqli_fetch_array($query)) {
                    ?>
                        <tr>
                            <td>
                                <div class="dashboard-user-image-container">
                                    <img src="images/profile_pic/<?php echo $row['uimage']; ?>" >
                                </div></td>
                            <td><?php echo $row['uname']; ?></td>
                            <td><?php echo $row['uemail']; ?></td>
                            <td><?php echo $row['uphone']; ?></td>
                            <td>
                                <!-- Bouton Supprimer -->
                                <a href="admin_user.php?delete_id=<?php echo $row['uid']; ?>" 
                                   onclick="return confirm('Voulez-vous vraiment supprimer cet utilisateur ?');">
                                    <img src="images/admin/supprimer.png" class="img-action" style="width: 23px !important; height: 23px !important;" title="Supprimer">
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>            
            </div>
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
