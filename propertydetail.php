<?php 
ini_set('session.cache_limiter','public');
session_cache_limiter(false);
session_start();
include("config.php");

// Vérification si un ID de propriété est passé dans l'URL
if (!isset($_GET['pid']) || empty($_GET['pid'])) {
    die("<h3 style='color:red;'>Aucune propriété spécifiée.</h3>");
}

$property_id = intval($_GET['pid']);

// Récupération des détails de la propriété et user
$query = mysqli_query($con, "SELECT property.*, 
                                    user.uname, user.ufirstname, user.uemail, 
                                    user.uphone, user.uimage, user.cv 
                             FROM property 
                             JOIN user ON property.agentid = user.uid 
                             WHERE property.pid = '$property_id'");


if (!$query || mysqli_num_rows($query) == 0) {
    die("<h3 style='color:red;'>Propriété non trouvée.</h3>");
}

$row = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html lang="fr">
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
                                        <li class="breadcrumb-item text-white"><a href="index.php">Accueil</a></li>
                                        <li class="breadcrumb-item active">Propriétés</li>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
                 <!--	Banner end  --->

            <div class="full-row">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-8">
                            <!-- Titre et infos principales -->
                            <h3 class="text-secondary"><?php echo htmlspecialchars($row['title']); ?></h3>
                            <span class="location"><i class="fas fa-map-marker-alt text-primary"></i> <?php echo htmlspecialchars($row['location']); ?>, <?php echo htmlspecialchars($row['city']); ?></span>
                            <h4 class="text-primary mt-3">€<?php echo number_format($row['price'], 0, ',', ' '); ?></h4>
                            
                            <!-- Carrousel d'images -->
                            <div id="propertyCarousel" class="carousel slide mt-4" data-ride="carousel">
                                <div class="carousel-inner">
                                    <?php 
                                    for ($i = 1; $i <= 3; $i++) {
                                        if (!empty($row["pimage$i"])) {
                                            echo '<div class="carousel-item ' . ($i == 1 ? 'active' : '') . '">';
                                            echo '<img src="images/property/' . htmlspecialchars($row["pimage$i"]) . '" class="d-block w-100" alt="Image ' . $i . '">';
                                            echo '</div>';
                                        }
                                    }
                                    ?>
                                </div>
                                <a class="carousel-control-prev" href="#propertyCarousel" role="button" data-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                </a>
                                <a class="carousel-control-next" href="#propertyCarousel" role="button" data-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                </a>
                            </div>

                            <!-- Description -->
                            <h4 class="text-secondary mt-4">Description</h4>
                            <p><?php echo nl2br(htmlspecialchars($row['propertyDescription'])); ?></p>

                            <!-- Détails supplémentaires -->
                            <h5 class="mt-4 text-secondary">Informations principales</h5>
                            <ul>
                                <li><b>Type :</b> <?php echo htmlspecialchars($row['propertyType']); ?></li>
                                <li><b>Surface :</b> <?php echo htmlspecialchars($row['area']); ?> m²</li>
                                <li><b>Nombre de chambres :</b> <?php echo htmlspecialchars($row['nbRooms']); ?></li>
                                <li><b>Nombre de salles de bains :</b> <?php echo htmlspecialchars($row['nbBathrooms']); ?></li>
                                <li><b>Statut :</b> <?php echo htmlspecialchars($row['status']); ?></li>
                            </ul>
                        </div>

                        <!-- Informations sur l'agent -->
                        <div class="col-lg-4 ">
                            <div class="agent-info-sidebar p-3 border rounded">
                                <h4 class="text-secondary text-center mb-4">Agent Responsable</h4>
                                <img src="images/profile_pic/<?php echo htmlspecialchars($row['uimage']); ?>" class="img-fluid rounded-circle mb-3" alt="Agent">
                                
                                <h5 class="text-center mb-4"><?= htmlspecialchars($row['ufirstname']) . " " . htmlspecialchars(strtoupper($row['uname'])); ?></h5>
                                
                                <div class="agent-stat">
                                    <strong><i class="fa fa-envelope"></i> Email:</strong> 
                                    <span class="float-right"><a href="mailto:<?php echo htmlspecialchars($row['uemail']); ?>"><?php echo htmlspecialchars($row['uemail']); ?></a></span>
                                </div>
                                
                                <div class="agent-stat">
                                    <strong><i class="fa fa-phone"></i> Téléphone:</strong> 
                                    <span class="float-right"><?php echo htmlspecialchars($row['uphone']); ?></span>
                                </div>

                                <?php if(isset($_SESSION['isAgent']) && !$_SESSION['isAgent']) { ?>
                                <!-- Boutons actions -->
                                <div class="mt-4 d-flex flex-column align-items-center">
                                    <!-- Bouton "Prendre Rendez-vous" -->
                                    <a href="rdv_disponibilite.php?agent_id=<?php echo htmlspecialchars($row['agentid']); ?>" class="btn btn-primary btn-block w-75">
                                        <i class="fa fa-calendar"></i> Prendre Rendez-vous
                                    </a>

                                    <!-- Bouton "Télécharger le CV" -->
                                    <?php if (!empty($row['cv']) && file_exists("images/cv/" . $row['cv'])) { ?>
                                        <a href="images/cv/<?php echo $row['cv']; ?>" download class="btn btn-secondary btn-block w-75">
                                            <i class="fa fa-file-text"></i> Télécharger CV
                                        </a>
                                    <?php } else { ?>
                                        <button class="btn btn-secondary btn-block w-75 disabled" disabled>
                                            <i class="fa fa-file-text"></i> CV non disponible
                                        </button>
                                    <?php } ?>
                                    
                                    
                                    <!-- Bouton "Messagerie" -->
                                    <a href="messagerie.php?agent_id=<?php echo htmlspecialchars($row['agentid']); ?>" class="btn btn-secondary btn-block w-75">
                                        <i class="fa fa-comments"></i> Messagerie
                                    </a>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
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

