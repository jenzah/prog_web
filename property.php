<?php 
ini_set('session.cache_limiter','public');
session_cache_limiter(false);
session_start();
include("config.php");
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="images/favicon.ico">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <title>Propriétés - Omnes Immobilier</title>
</head>

<body>
    <div id="page-wrapper">
        <div class="row"> 
            <?php include("include/header.php"); ?>

            <div class="banner-full-row page-banner" style="background-image:url('images/breadcrumb.jpg');">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6">
                            <h2 class="page-name text-white text-uppercase"><b>Propriétés</b></h2>
                        </div>
                        <div class="col-md-6">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb bg-transparent">
                                    <li class="breadcrumb-item text-white"><a href="home.php">Accueil</a></li>
                                    <li class="breadcrumb-item active">Propriétés</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

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
                                                            OR property.title LIKE '%$keyword%' 
                                                            OR property.location LIKE '%$keyword%' 
                                                            OR property.city LIKE '%$keyword%')";
                                }

                                // Construction de la clause WHERE uniquement si un filtre est actif
                                $where_clause = !empty($where_conditions) ? "AND " . implode(" AND ", $where_conditions) : "";

                                // Exécution de la requête SQL avec filtre
                                $where_clause = !empty($where_conditions) ? "AND " . implode(" AND ", $where_conditions) : "";
                                 $query = mysqli_query($con, "SELECT property.*, user.uname, user.utype, user.uimage 
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
                                                <img src="admin/property/<?php echo $row['pimage1'];?>" alt="Image de propriété">
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
                                                        <i class="fas fa-user text-primary mr-1"></i>Agent : <?php echo $row['uname'];?>
                                                    </div>
                                                    <div class="float-right">
                                                        <i class="far fa-calendar-alt text-primary mr-1"></i>
                                                        <?php
                                                            $postDate = new DateTime($row['date']);
                                                            $currentDate = new DateTime(date('Y-m-d H:i:s'));
                                                            $interval = $postDate->diff($currentDate);
                                                                                                    
                                                            if($interval->y > 0) {
                                                                echo $interval->y . ($interval->y == 1 ? ' an' : ' ans') . ' ago';
                                                            } elseif($interval->m > 0) {
                                                                echo $interval->m . ($interval->m == 1 ? ' mois' : ' mois') . ' ago';
                                                            } else {
                                                                echo $interval->d . ($interval->d == 1 ? ' jour' : ' jours') . ' ago';
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

            <?php include("include/footer.php"); ?>
        </div>
    </div>

    <script src="js/jquery.min.js"></script> 
    <script src="js/bootstrap.min.js"></script> 
</body>
</html>
