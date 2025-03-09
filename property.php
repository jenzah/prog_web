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
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Meta Tags -->
    <link rel="shortcut icon" href="images/favicon.ico">

    <!--	Fonts   -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">

    <!--	Css Links  -->
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap-slider.css">
    <link rel="stylesheet" type="text/css" href="css/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="css/layerslider.css">
    <link rel="stylesheet" type="text/css" href="css/color.css" id="color-change">
    <link rel="stylesheet" type="text/css" href="css/owl.carousel.min.css">
    <link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="fonts/flaticon/flaticon.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">

    <!--	Title   -->
    <title>Propriétés - Omnes Immobilier</title>
    </head>
    
    <body>
        <div id="page-wrapper">
            <div class="row"> 
                <!--	Header start  -->
        		<?php include("include/header.php");?>
                <!--	Header end  -->

                <!--	Banner start  --->
                <div class="banner-full-row page-banner" style="background-image:url('images/breadcrumb.jpg');">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-6">
                                <h2 class="page-name float-left text-white text-uppercase mt-1 mb-0"><b>Propriétés</b></h2>
                            </div>
                            <div class="col-md-6">
                                <nav aria-label="breadcrumb" class="float-left float-md-right">
                                    <ol class="breadcrumb bg-transparent m-0 p-0">
                                        <li class="breadcrumb-item text-white"><a href="home.php">Accueil</a></li>
                                        <li class="breadcrumb-item active">Propriétés</li>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
                 <!--	Banner end  --->

                <!--	Property grid start  -->
                <div class="full-row">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <form method="get" action="" id="propertySearchForm">
                                    <div class="row">
                                        <div class="col-md-6 col-lg-6">
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="keyword" placeholder="Recherche par mot-clé"
                                                    value="<?php echo isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : ''; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-lg-2">
                                            <div class="form-group">
                                                <button type="submit" name="filter" class="btn btn-primary w-100">Rechercher</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
    
                            <!-- Affichage des propriétés -->
                            <div class="col-md-12">
                                <div class="row">
                                    <?php
                                    // Initialisation des conditions de filtrage
                                    $where_conditions = [];
    
                                    // Vérification si un mot-clé est entré
                                    if(isset($_GET['keyword']) && !empty($_GET['keyword'])) {
                                        $keyword = mysqli_real_escape_string($con, $_GET['keyword']);
                                        $where_conditions[] = "(user.uname LIKE '%$keyword%'
                                                                OR user.ufirstname LIKE '%$keyword%'
                                                                OR property.title LIKE '%$keyword%'
                                                                OR property.status LIKE '%$keyword%'
                                                                OR property.location LIKE '%$keyword%' 
                                                                OR property.city LIKE '%$keyword%')";
                                    }
                                
                                    // Construction de la clause WHERE uniquement si un filtre est actif
                                    $where_clause = !empty($where_conditions) ? "AND " . implode(" AND ", $where_conditions) : "";
                                
                                    // Exécution de la requête SQL avec filtre
                                    $where_clause = !empty($where_conditions) ? "AND " . implode(" AND ", $where_conditions) : "";
                                     $query = mysqli_query($con, "SELECT property.*, user.uname, user.ufirstname, user.utype, user.uimage 
                                    FROM `property` 
                                    JOIN `user` ON property.agentid = user.uid
                                     WHERE (property.status = 'À vendre' OR property.status = 'À louer') 
                                     $where_clause
                                     ORDER BY property.date DESC");
    
                                
                                    // Vérifier si la requête fonctionne
                                    if (!$query) {
                                        die("<p class='alert alert-danger'>Erreur SQL: " . mysqli_error($con) . "</p>");
                                    }
                                
                                    // Vérifier si des résultats existent
                                    if(mysqli_num_rows($query) > 0) {
                                        while($row = mysqli_fetch_array($query)) {
                                    ?>
                                    <div class="col-md-4">
                                        <div class="featured-thumb hover-zoomer mb-4">
                                            <div class="overlay-black overflow-hidden position-relative"> 
                                                <img src="images/property/<?php echo $row['pimage1'];?>" alt="Image de propriété">
                                                <div class="sale bg-secondary text-white"><?php echo $row['status'];?></div>
                                                <div class="price text-light-primary">€<?php echo $row['price'];?> 
                                                    <span class="text-white"><?php echo $row['area'];?> m²</span>
                                                </div>
                                            </div>
                                            <div class="featured-thumb-data shadow-one">
                                                <div class="p-4">
                                                    <h5 class="text-secondary hover-text-primary mb-2">
                                                        <a href="propertydetail.php?pid=<?php echo $row['pid'];?>"><?php echo $row['title'];?></a>
                                                    </h5>
                                                    <span class="location"><i class="fas fa-map-marker-alt text-primary"></i> 
                                                        <?php echo $row['location'];?>, <?php echo $row['city'];?>
                                                    </span>
                                                </div>
                                                <div class="px-4 pb-4 d-inline-block w-100">
                                                    <div class="float-left">
                                                        <i class="fas fa-user text-primary mr-1"></i>Agent : <?php echo $row['uname'];?>, <?php echo $row['ufirstname'];?>
                                                    </div>
                                                    <div class="float-right">
                                                        <i class="far fa-calendar-alt text-primary mr-1"></i>
                                                        <?php
                                                            $postDate = new DateTime($row['date']);
                                                            $currentDate = new DateTime(date('Y-m-d H:i:s'));
                                                            $interval = $postDate->diff($currentDate);
                                                                                            
                                                            if($interval->y > 0) {
                                                                echo 'il y a ' . $interval->y . ($interval->y == 1 ? ' an' : ' ans');
                                                            } elseif($interval->m > 0) {
                                                                echo 'il y a ' . $interval->m . ($interval->m == 1 ? ' mois' : ' mois');
                                                            } else {
                                                                echo 'il y a ' . $interval->d . ($interval->d == 1 ? ' jour' : ' jours');
                                                            }
                                                        ?>
                                                    </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } } else { ?>
                                        <!-- Message si aucun résultat -->
                                        <p class="text-center w-100 alert alert-warning">Aucune propriété trouvée.</p>
                                    <?php } ?>
                                </div>
                            </div>              
                        </div>
                    </div>
                </div>
                <!--	Property grid end  -->
                                                
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
        <script src="js/greensock.js"></script> 
        <script src="js/layerslider.transitions.js"></script> 
        <script src="js/layerslider.kreaturamedia.jquery.js"></script> 
        <!--jQuery Layer Slider --> 
        <script src="js/popper.min.js"></script> 
        <script src="js/bootstrap.min.js"></script> 
        <script src="js/owl.carousel.min.js"></script> 
        <script src="js/tmpl.js"></script> 
        <script src="js/jquery.dependClass-0.1.js"></script> 
        <script src="js/draggable-0.1.js"></script> 
        <script src="js/jquery.slider.js"></script> 
        <script src="js/wow.js"></script> 
                                                
        <script src="js/custom.js"></script>
    </body>
</html>
