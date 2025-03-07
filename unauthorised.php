<?php 
session_start();
include("config.php");
?>

<!DOCTYPE html>
<html>
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <!-- Meta Tags -->
    <link rel="shortcut icon" href="images/favicon.ico">
    
    <!--	Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
    
    <!--	Css Links -->
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
    
    <!--	Title -->
    <title>Accès non autorisé - Omnes Immobilier</title>
</head>
<body>
    <div id="page-wrapper">
        <div class="row"> 
            <!--	Header start -->
            <?php include("include/header.php");?>
            <!--	Header end -->
            
            <!--	Banner start -->
            <div class="banner-full-row page-banner" style="background-image:url('images/breadcrumb.jpg');">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6">
                            <h2 class="page-name float-left text-white text-uppercase mt-1 mb-0"><b>Accès non autorisé</b></h2>
                        </div>
                        <div class="col-md-6">
                            <nav aria-label="breadcrumb" class="float-left float-md-right">
                                <ol class="breadcrumb bg-transparent m-0 p-0">
                                    <li class="breadcrumb-item text-white"><a href="index.php">Page d'accueil</a></li>
                                    <li class="breadcrumb-item active">Accès non autorisé</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <!--	Banner end -->
            
            <!-- Error section -->
            <div class="full-row">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="error-404 text-center">
                                <h1 class="text-danger">403</h1>
                                <h3 class="mb-4">Accès non autorisé</h3>
                                <p class="mb-4">Vous n'avez pas les permissions nécessaires pour accéder à cette page.</p>
                                
                                <div class="row justify-content-center">
                                    <div class="col-md-4">
                                        <a href="index.php" class="btn btn-primary w-100">Retour à l'accueil</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Error section end -->
            
            <!--	Footer start -->
            <?php include("include/footer.php");?>
            <!--	Footer end -->
            
            <!-- Scroll to top --> 
            <a href="#" class="bg-secondary text-white hover-text-secondary" id="scroll"><i class="fas fa-angle-up"></i></a> 
            <!-- End Scroll To top --> 
        </div>
    </div>
    
    <!--	Js Link -->
    <script src="js/jquery.min.js"></script>
    <script src="js/popper.min.js"></script> 
    <script src="js/bootstrap.min.js"></script>
    <script src="js/custom.js"></script>
</body>
</html>