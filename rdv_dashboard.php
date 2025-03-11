<?php 
ini_set('session.cache_limiter','public');
session_cache_limiter(false);
session_start();
include("config.php");

if(!isset($_SESSION['uid'])) {
    header("location:login.php");
}

// Get current user ID
$userId = $_SESSION['uid'];

// Annuler un rendez-vous si "cancel_id" est présent dans l'URL
if (isset($_GET['cancel_id'])) {
    $appointmentId = (int) $_GET['cancel_id'];

    // Vérifier si le rendez-vous existe et appartient à l'utilisateur courant
    $checkQuery = mysqli_query($con, "SELECT * FROM appointments, user WHERE aid='$appointmentId' AND 
                                      (utype = 'agent' AND agent_id='$userId' OR 
                                       utype = 'client' AND client_id='$userId' OR 
                                       utype = 'admin')");
                                       
    if (mysqli_num_rows($checkQuery) > 0) {
        // Annulation
        $cancelQuery = mysqli_query($con, "DELETE FROM appointments WHERE aid='$appointmentId'");
        if ($cancelQuery) {
            echo "<script>alert('RDV annulé avec succès.'); window.location='rdv_dashboard.php';</script>";
        } else {
            echo "<script>alert('Erreur lors de l\'annulation.');</script>";
        }
    } else {
        echo "<script>alert('RDV introuvable ou non autorisé.');</script>";
    }
}

// Récupérer la date et l'heure actuelles pour séparer les rendez-vous passés et à venir
$currentDate = date('Y-m-d');
$currentTime = date('H:i:s');

// Base SQL query construction
$baseSql = "SELECT a.*, p.title as property_title, p.location, p.city, 
                   agent.uname as agent_name,
                   agent.ufirstname as agent_firstname,
                   client.uname as client_name,
                   client.ufirstname as client_firstname
            FROM appointments a
            LEFT JOIN property p ON a.property_id = p.pid
            LEFT JOIN user agent ON a.agent_id = agent.uid
            LEFT JOIN user client ON a.client_id = client.uid";

// Filter by user type and ID
if ($_SESSION['isAgent']) {
    // For agents, show only their appointments
    $userFilter = " WHERE a.agent_id = '$userId'";
} elseif ($_SESSION['isAdmin']) {
    // Admins can see all appointments
    $userFilter = "";
} else {
    // For clients, show only their appointments
    $userFilter = " WHERE a.client_id = '$userId'";
}

// Récupérer les rendez-vous à venir
$upcomingSql = $baseSql . $userFilter . ($userFilter ? " AND " : " WHERE ") . 
               "(a.rdv_date > '$currentDate' OR 
                (a.rdv_date = '$currentDate' AND a.rdv_time > '$currentTime')) 
                ORDER BY a.rdv_date ASC, a.rdv_time ASC";
$upcomingQuery = mysqli_query($con, $upcomingSql);

// Récupérer les rendez-vous passés
$pastSql = $baseSql . $userFilter . ($userFilter ? " AND " : " WHERE ") . 
           "(a.rdv_date < '$currentDate' OR 
            (a.rdv_date = '$currentDate' AND a.rdv_time <= '$currentTime')) 
            ORDER BY a.rdv_date DESC, a.rdv_time DESC";
$pastQuery = mysqli_query($con, $pastSql);
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
<title>Omnes Immobilier - Mes Rendez-vous</title>

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
                        <h2 class="page-name text-white text-uppercase"><b>
                            <?php echo ($_SESSION['isAgent']) ? 'Mon Agenda' : 'Mes RDVs'; ?>
                        </b></h2>
                    </div>
                    <div class="col-md-6">
                        <nav aria-label="breadcrumb" class="float-md-right">
                            <ol class="breadcrumb bg-transparent m-0 p-0">
                                <li class="breadcrumb-item text-white"><a href="home.php">
                                    <?php echo ($_SESSION['isAgent']) ? 'Mon agenda' : 'Mon compte'; ?>
                                </a></li>
                                <li class="breadcrumb-item active">Mes RDVs</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des rendez-vous -->
        <div class="full-row">
            <div class="container">
                <!-- SECTION RENDEZ-VOUS À VENIR -->
                <div class="row mb-5">
                    <div class="col-lg-12">
                        <h2 class="text-secondary text-center double-down-line">RDVs à venir</h2>
                    </div>
                </div>

                <?php if(mysqli_num_rows($upcomingQuery) > 0) { ?>
                <div class="table-responsive">
                    <table class="table table-bordered auto-table">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th>Propriété</th>
                                <th>Lieu</th>
                                <th>Date</th>
                                <th>Heure</th>
                                <?php if($_SESSION['isAdmin']) { ?>
                                    <th>Agent</th>
                                    <th>Client</th>
                                    <th>Paiement</th>
                                <?php } elseif($_SESSION['isAgent']) { ?>
                                    <th>Client</th>
                                <?php } else { ?>
                                <th>Agent</th>
                                <th>Paiement</th>
                                <?php } ?>
                                <th>Commentaires</th>
                                <th class="actions">Annuler le RDV</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php 
                        while($app = mysqli_fetch_array($upcomingQuery)) {
                            // Formater la date et l'heure
                            $date = new DateTime($app['rdv_date']);
                            $formattedDate = $date->format('d/m/Y');
                            
                            $time = new DateTime($app['rdv_time']);
                            $formattedTime = $time->format('H:i');
                        ?>
                            <tr>
                                <td>
                                    <!-- <div class="property-link">
                                        <span class="property-title"><?php echo $app['property_title']; ?></span>
                                        <a href="rdv_details.php?id=<?php echo $app['aid']; ?>" class="details-link">
                                            <i class="fas fa-eye"></i>Voir détails du RDV
                                        </a>
                                    </div> -->
                                    <div class="property-link">
                                        <span class="property-title">
                                            <?php 
                                            if (empty($app['property_title']) || $app['property_title'] === null) {
                                                echo "<em>Non spécifié</em>";
                                                echo "</span>";
                                            } else {
                                                echo $app['property_title'];
                                                ?>
                                        </span>
                                                <a href="rdv_details.php?id=<?php echo $app['aid']; ?>" class="details-link">
                                                    <i class="fas fa-eye" style="margin-right: 5px;"></i>Voir détails du RDV
                                                </a>
                                            <?php } ?>
                                    </div>
                                </td>
                                
                                <td>
                                    <?php 
                                    if (empty($app['rdv_place']) || $app['rdv_place'] === null) {
                                        echo "<i>Non spécifié</i>";
                                    } else {
                                        echo $app['rdv_place'];
                                    }
                                    ?>
                                </td>
                                <td><?php echo $formattedDate; ?></td>
                                <td><?php echo $formattedTime; ?></td>

                                <!-- Client and agent names -->
                                <?php if($_SESSION['isAdmin']) { ?>
                                    <td><?php echo $app['agent_firstname']; ?> <?php echo strtoupper($app['agent_name']); ?></td>
                                    <td><?php echo $app['client_firstname']; ?> <?php echo strtoupper($app['client_name']); ?></td>
                                <?php } elseif($_SESSION['isAgent']) { ?>
                                    <td><?php echo $app['client_firstname']; ?> <?php echo strtoupper($app['client_name']); ?></td>
                                <?php } else { ?>
                                    <td><?php echo $app['agent_firstname']; ?> <?php echo strtoupper($app['agent_name']); ?></td>
                                <?php } ?>
                                
                                <!-- Payment status -->
                                <?php if(!$_SESSION['isAgent']) { ?>
                                <td class="text-center">
                                    <?php if($app['is_paid']) { ?>
                                    <span class="badge-paid"><i class="fas fa-check-circle"></i> Payé</span>
                                    <?php } else { ?>
                                    <div class="payment-container">
                                        <span class="badge-unpaid"><i class="fas fa-times-circle"></i> <?php echo number_format($app['rdv_price'], 0, ',', ' ') . ' €'; ?></span>
                                        <a href="payment_confirm.php?appointment_id=<?php echo $app['aid']; ?>" class="payment-link">
                                            <i class="fas fa-credit-card" style="margin-right: 5px;"></i> Payer
                                        </a>
                                    </div>
                                    <?php } ?>
                                </td>
                                <?php } ?>
                                <td class="comments"><?php echo $app['rdv_comments']; ?></td>
                                <td class="actions text-center">
                                    <a href="rdv_dashboard.php?cancel_id=<?php echo $app['aid']; ?>" onclick="return confirm('Voulez-vous annuler ce RDV ?');">
                                        <img src="images/admin/delete.png" class="img-action" style="width: 23px !important; height: 23px !important;" title="Annuler">
                                    </a>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
                <?php } else { ?>
                <div class="no-appointments">
                    <p>Aucun RDV à venir</p>
                </div>
                <?php } ?>
                
                <!-- SECTION RENDEZ-VOUS PASSÉS -->
                <div class="row mt-5 mb-5">
                    <div class="col-lg-12">
                        <h2 class="text-secondary text-center double-down-line">RDVs passés</h2>
                    </div>
                </div>
                
                <?php if(mysqli_num_rows($pastQuery) > 0) { ?>
                <div class="table-responsive">
                    <table class="table table-bordered auto-table">
                        <thead class="bg-secondary text-white">
                            <tr>
                                <th>Propriété</th>
                                <th>Lieu</th>
                                <th>Date</th>
                                <th>Heure</th>
                                <?php if($_SESSION['isAdmin']) { ?>
                                    <th>Agent</th>
                                    <th>Client</th>
                                    <th>Paiement</th>
                                <?php } elseif($_SESSION['isAgent']) { ?>
                                    <th>Client</th>
                                <?php } else { ?>
                                <th>Agent</th>
                                <th>Paiement</th>
                                <?php } ?>
                                <th>Commentaires</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php 
                        while($app = mysqli_fetch_array($pastQuery)) {
                            // Formater la date et l'heure
                            $date = new DateTime($app['rdv_date']);
                            $formattedDate = $date->format('d/m/Y');
                            
                            $time = new DateTime($app['rdv_time']);
                            $formattedTime = $time->format('H:i');
                        ?>
                            <tr>
                                <td>
                                    <div class="property-link">
                                        <span class="property-title"><?php echo $app['property_title']; ?></span>
                                        <a href="rdv_details.php?id=<?php echo $app['aid']; ?>" class="details-link">
                                            <i class="fas fa-eye"></i>Voir détails du RDV
                                        </a>
                                    </div>
                                </td>
                                <td><?php echo $app['rdv_place']; ?></td>
                                <td><?php echo $formattedDate; ?></td>
                                <td><?php echo $formattedTime; ?></td>

                                <!-- Client and agent names -->
                                <?php if($_SESSION['isAdmin']) { ?>
                                    <td><?php echo $app['agent_firstname']; ?> <?php echo strtoupper($app['agent_name']); ?></td>
                                    <td><?php echo $app['client_firstname']; ?> <?php echo strtoupper($app['client_name']); ?></td>
                                <?php } elseif($_SESSION['isAgent']) { ?>
                                    <td><?php echo $app['client_firstname']; ?> <?php echo strtoupper($app['client_name']); ?></td>
                                <?php } else { ?>
                                    <td><?php echo $app['agent_firstname']; ?> <?php echo strtoupper($app['agent_name']); ?></td>
                                <?php } ?>
                                
                                <!-- Payment status -->
                                <?php if(!$_SESSION['isAgent']) { ?>
                                <td class="text-center">
                                    <?php if($app['is_paid']) { ?>
                                        <span class="badge-paid"><i class="fas fa-check-circle"></i> Payé</span>
                                    <?php } else { ?>
                                    <div class="payment-container">
                                        <span class="badge-unpaid"><i class="fas fa-times-circle"></i> <?php echo number_format($app['rdv_price'], 0, ',', ' ') . ' €'; ?></span>
                                        <a href="payment.php?appointment_id=<?php echo $app['aid']; ?>" class="payment-link">
                                            <i class="fas fa-credit-card"></i>Payer
                                        </a>
                                    </div>
                                    <?php } ?>
                                </td>
                                <?php } ?>
                                <td class="comments"><?php echo $app['rdv_comments']; ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
                <?php } else { ?>
                <div class="no-appointments">
                    <p>Aucun RDV passé</p>
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