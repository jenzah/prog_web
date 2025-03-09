<?php 
ini_set('session.cache_limiter','public');
session_cache_limiter(false);
session_start();
include("config.php");

if(!isset($_SESSION['uid'])) {
    header("location:login.php");
}

// Annuler un rendez-vous si "cancel_id" est présent dans l'URL
if (isset($_GET['cancel_id'])) {
    $appointmentId = (int) $_GET['cancel_id'];

    // Vérifier si le rendez-vous existe
    $checkQuery = mysqli_query($con, "SELECT * FROM appointments WHERE id='$appointmentId'");
    if (mysqli_num_apps($checkQuery) > 0) {
        // Annulation (mise à jour du statut ou suppression selon votre logique)
        $cancelQuery = mysqli_query($con, "DELETE FROM appointments WHERE id='$appointmentId'");
        if ($cancelQuery) {
            echo "<script>alert('Rendez-vous annulé avec succès.'); window.location='appointments.php';</script>";
        } else {
            echo "<script>alert('Erreur lors de l\'annulation.');</script>";
        }
    } else {
        echo "<script>alert('Rendez-vous introuvable.');</script>";
    }
}

// Récupérer la date actuelle pour séparer les rendez-vous passés et à venir
$currentDateTime = date('Y-m-d H:i:s');

// Récupérer les rendez-vous à venir
$upcomingQuery = mysqli_query($con, "
    SELECT a.*, p.title as property_title, p.location, p.city, 
           agent.uname as agent_name,
           client.uname as client_name
    FROM appointments a
    LEFT JOIN property p ON a.property_id = p.pid
    LEFT JOIN user agent ON a.agent_id = agent.uid
    LEFT JOIN user client ON a.client_id = client.uid
    WHERE a.date_time > '$currentDateTime'
    ORDER BY a.date_time ASC
");

// Récupérer les rendez-vous passés
$pastQuery = mysqli_query($con, "
    SELECT a.*, p.title as property_title, p.location, p.city, 
           agent.uname as agent_name,
           client.uname as client_name
    FROM appointments a
    LEFT JOIN property p ON a.property_id = p.pid
    LEFT JOIN user agent ON a.agent_id = agent.uid
    LEFT JOIN user client ON a.client_id = client.uid
    WHERE a.date_time <= '$currentDateTime'
    ORDER BY a.date_time DESC
");
?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Meta Tags -->
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
    <title>Omnes Immobilier - Rendez-vous</title>

    <!-- Styles -->
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">

    <style>
        .dashboard-property-image-container {
            width: 100px;
            height: 70px;
            overflow: hidden;
        }

        .dashboard-property-image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .img-action {
            margin: 0 5px;
            cursor: pointer;
        }

        .no-appointments {
            text-align: center;
            padding: 20px;
            font-style: italic;
            color: #6c757d;
        }
    </style>

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
                        <h2 class="page-name text-white text-uppercase"><b>Mes RDVs</b></h2>
                    </div>
                    <div class="col-md-6">
                        <nav aria-label="breadcrumb" class="float-md-right">
                            <ol class="breadcrumb bg-transparent m-0 p-0">
                                <?php if(!$_SESSION['isAgent'] && !$_SESSION['isAdmin']) { ?>
                                <li class="breadcrumb-item text-white"><a href="home.php">Mon compte</a></li>
                                <?php } else { ?>
                                <li class="breadcrumb-item text-white"><a href="home.php">Mon agenda</a></li>
                                <?php } ?>
                                <li class="breadcrumb-item active">Rendez-vous</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des rendez-vous -->
        <div class="full-row">
            <div class="container">
                <div class="row mb-5">
                    <div class="col-lg-12">
                        <h2 class="text-secondary text-center double-down-line">RDV à venir</h2>
                    </div>
                </div>

                <?php if(mysqli_num_rows($upcomingQuery) > 0) { ?>
                <table class="table table-bordered">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th>Propriété</th>
                            <th>Lieu</th>
                            <th>Date et heure</th>
                            <th>Agent</th>
                            <th>Client</th>
                            <th>Paiement</th>
                            <th>Commentaires</th>
                            <th>Annuler le RDV</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                    
                    while($app = mysqli_fetch_array($upcomingQuery)) {
                        // Formater la date et l'heure
                        $dateTime = new DateTime($app['date_time']);
                        $formattedDateTime = $dateTime->format('d/m/Y à H:i');
                    ?>
                        <tr>
                            <td><?php echo $app['property_title']; ?></td>
                            <td><?php echo $app['place']; ?></td>
                            <td><?php echo $formattedDateTime; ?></td>
                            <td><?php echo $app['agent_name']; ?></td>
                            <td><?php echo $app['client_name']; ?></td>
                            <td class="text-center">
                                <?php if($app['is_paid']) { ?>
                                <img src="images/admin/checkmark.png" alt="Payé" title="Payé" style="width: 30px; height: 30px;">
                                <?php } else { ?>
                                <span class="rdv-badge rdv-unpaid"><?php echo number_format($app['price'], 0, ',', ' ') . ' €'; ?></span>
                                <?php } ?>
                            </td>
                            <td><?php echo $app['comments']; ?></td>
                            <td>
                                <!-- Bouton Annuler -->
                                <a href="appointments.php?cancel_id=<?php echo $app['aid']; ?>" onclick="return confirm('Voulez-vous vraiment annuler ce rendez-vous ?');">
                                    <img src="images/admin/delete.png" class="img-action" style="width: 30px !important; height: 30px !important;" title="Annuler">
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
                <?php } else { ?>
                <div class="no-appointments">
                    <p>Aucun RDV à venir</p>
                </div>
                <?php } ?>
                
                <div class="row mt-5 mb-5">
                    <div class="col-lg-12">
                        <h2 class="text-secondary text-center double-down-line">Rendez-vous passés</h2>
                    </div>
                </div>
                
                <?php if(mysqli_num_apps($pastQuery) > 0) { ?>
                <table class="table table-bordered">
                    <thead class="bg-secondary text-white">
                        <tr>
                            <th>Propriété</th>
                            <th>Lieu</th>
                            <th>Date et heure</th>
                            <th>Agent</th>
                            <th>Client</th>
                            <th>Paiement</th>
                            <th>Commentaires</th>
                            <!-- No Actions column for past appointments -->
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                    while($app = mysqli_fetch_array($pastQuery)) {
                        // Formater la date et l'heure
                        $dateTime = new DateTime($app['date_time']);
                        $formattedDateTime = $dateTime->format('d/m/Y à H:i');
                    ?>
                        <tr>
                            <td><?php echo $app['property_title']; ?></td>
                            <td><?php echo $app['place']; ?></td>
                            <td><?php echo $formattedDateTime; ?></td>
                            <td><?php echo $app['agent_name']; ?></td>
                            <td><?php echo $app['client_name']; ?></td>
                            <td>
                                <?php if($app['is_paid']) { ?>
                                <span class="rdv-badge rdv-paid">Payé</span>
                                <?php } else { ?>
                                <span class="rdv-badge rdv-unpaid"><?php echo number_format($app['price'], 0, ',', ' ') . ' €'; ?></span>
                                <?php } ?>
                            </td>
                            <td><?php echo $app['comments']; ?></td>
                            <!-- No Actions column for past appointments -->
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
                <?php } else { ?>
                <div class="no-appointments">
                    <p>Aucun rendez-vous passé</p>
                </div>
                <?php } ?>
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