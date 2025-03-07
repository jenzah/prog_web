<?php 
ini_set('session.cache_limiter','public');
session_cache_limiter(false);
session_start();
include("config.php");
?>

<!DOCTYPE html>
<html>

    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <!-- meta http-equiv="X-UA-Compatible" content="IE=edge" -->
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Meta Tags -->
        <!-- meta http-equiv="X-UA-Compatible" content="IE=edge" -->
        <!-- meta name="description" content="Homex template"-->
        <!-- meta name="keywords" content=""-->
        <!-- meta name="author" content="Unicoder"-->
        <link rel="shortcut icon" href="images/favicon.ico">

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">

        <!-- Css Link -->
        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="css/bootstrap-slider.css">
        <link rel="stylesheet" type="text/css" href="css/jquery-ui.css">
        <!-- <link rel="stylesheet" type="text/css" href="css/layerslider.css"> -->
        <link rel="stylesheet" type="text/css" href="css/color.css" id="color-change">
        <link rel="stylesheet" type="text/css" href="css/owl.carousel.min.css">
        <link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="fonts/flaticon/flaticon.css">
        <link rel="stylesheet" type="text/css" href="css/style.css">

        <!-- Title -->
        <title>Omnes Immobilier</title>
    </head>

    <body>
        <div id="page-wrapper">
            <div class="row">
                <!--	Header start  -->
        	    <?php include("include/header.php");?>
                <!--	Header end  -->

                <!--	Banner start   --->
                <div class="banner-full-row page-banner" style="background-image:url('images/breadcrumb.jpg');">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-6">
                                <h2 class="page-name text-white text-uppercase"><b>À propos de nous</b></h2>
                            </div>
                            <div class="col-md-6">
                                <nav aria-label="breadcrumb" class="float-left float-md-right">
                                    <ol class="breadcrumb bg-transparent m-0 p-0">
                                        <li class="breadcrumb-item text-white"><a href="home.php">Page d'accueil</a></li>
                                        <li class="breadcrumb-item active">À propos de nous</li>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
                 <!--	Banner end   --->
            
                <!--	About Our Company start   -->
                <div class="full-row">
                    <div class="container">
                        <div class="row about-company">
                            <div class="col-md-12 col-lg-7">
                                <!--	About content   -->
                                <div class="about-content">
                                    <h3 class="double-down-line-left text-secondary position-relative pb-4 mb-4">Bienvenue chez Omnes Immobilier</h3>
                                    <p>Fondée par quatre passionnées de l'immobilier issues d'Omnes Education, Omnes Immobilier se consacre à répondre aux besoins immobiliers de toute la communauté Omnes. Notre plateforme en ligne innovante met en relation les clients avec des agents immobiliers certifiés, offrant une expérience fluide pour la visite des propriétés, la sélection d'agents et la prise de rendez-vous.</p>
                                    <h4 class="text-secondary">Notre Mission</h4>
                                    <p>Chez Omnes Immobilier, nous croyons que trouver votre propriété idéale devrait être un voyage passionnant, et non un processus stressant. Notre mission est de révolutionner l'expérience immobilière pour la communauté Omnes en fournissant :</p>
                                        
                                    <ul class="floral-list">
                                      <li>Une liste transparente et complète des propriétés disponibles</li>
                                      <li>Un accès direct à des agents immobiliers qualifiés et professionnels</li>
                                      <li>Une programmation en ligne pratique pour les visites de propriétés</li>
                                      <li>Des canaux de communication multiples pour l'interaction client-agent</li>
                                    </ul><br>

                                    <h4 class="text-secondary">Nos Valeurs</h4>
                                    <ul class="floral-list-values">
                                      <li><strong>Intégrité</strong> : Nous croyons en des pratiques commerciales honnêtes et transparentes</li>
                                      <li><strong>Innovation</strong> : Nous recherchons constamment de nouvelles façons d'améliorer nos services</li>
                                      <li><strong>Communauté</strong> : Nous priorisons les besoins de la communauté Omnes Education</li>
                                      <li><strong>Excellence</strong> : Nous visons la plus haute qualité dans tout ce que nous faisons</li>
                                    </ul><br>

                                    <h4 class="text-secondary">Rejoignez-nous</h4>
                                    <p>Que vous cherchiez à acheter votre première maison, à vendre une propriété ou à explorer des opportunités immobilières commerciales, notre équipe est là pour vous accompagner à chaque étape. Découvrez la différence Omnes Immobilier dès aujourd'hui !</p>
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-5 mt-5">
                                <!--	About content   -->
                                <div class="about-img"> <img src="images/about/about.jpg" alt="about image"> </div>
                            </div>
                        </div>                    
                    </div>
                </div>
                <!--	About Our Company end    -->        
                
               <!--	Footer   start-->
        		<?php include("include/footer.php");?>
        		<!--	Footer   start-->
                
                <!-- Scroll to top --> 
                <a href="#" class="bg-secondary text-white hover-text-secondary" id="scroll"><i class="fas fa-angle-up"></i></a> 
                <!-- End Scroll To top --> 
            </div>
        </div>
        <!-- Wrapper End --> 
        
        <!--	Js Link
        ============================================================--> 
        <script src="js/jquery.min.js"></script> 
        <!--jQuery Layer Slider --> 
        <!-- <script src="js/greensock.js"></script> 
        <script src="js/layerslider.transitions.js"></script> 
        <script src="js/layerslider.kreaturamedia.jquery.js"></script>  -->
        <!--jQuery Layer Slider --> 
        <script src="js/popper.min.js"></script> 
        <script src="js/bootstrap.min.js"></script> 
        <script src="js/owl.carousel.min.js"></script> 
        <script src="js/tmpl.js"></script> 
        <script src="js/jquery.dependClass-0.1.js"></script> 
        <script src="js/draggable-0.1.js"></script> 
        <script src="js/jquery.slider.js"></script> 
        <script src="js/wow.js"></script> 
        <script src="js/jquery.cookie.js"></script> 
        <script src="js/custom.js"></script>
    </body>

</html>