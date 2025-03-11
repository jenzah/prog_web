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

// Check if appointment ID is provided
if(!isset($_GET['id'])) {
    header("location:rdv_dashboard.php");
    exit;
}

$appointmentId = (int) $_GET['id'];

// Annuler un rendez-vous si "cancel" est présent dans l'URL
if (isset($_GET['cancel'])) {
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

// Récupérer les détails du rendez-vous
$sql = "SELECT a.*, 
               p.title as property_title, 
               p.location, 
               p.city, 
               p.propertyDescription, 
               p.pimage1,
               p.nbRooms,
               p.nbBathrooms,
               p.area,
               p.price as property_price,
               agent.uname as agent_name,
               agent.ufirstname as agent_firstname,
               agent.uphone as agent_phone,
               agent.uemail as agent_email,
               client.uname as client_name,
               client.ufirstname as client_firstname,
               client.uphone as client_phone,
               client.uemail as client_email
        FROM appointments a
        LEFT JOIN property p ON a.property_id = p.pid
        LEFT JOIN user agent ON a.agent_id = agent.uid
        LEFT JOIN user client ON a.client_id = client.uid
        WHERE a.aid = '$appointmentId'";

// Vérifier si l'utilisateur a le droit de voir ce rendez-vous
if (!$_SESSION['isAdmin']) {
    $sql .= " AND (a.agent_id = '$userId' OR a.client_id = '$userId')";
}

$query = mysqli_query($con, $sql);

if (mysqli_num_rows($query) == 0) {
    echo "<script>alert('Rendez-vous introuvable ou accès non autorisé.'); window.location='rdv_dashboard.php';</script>";
    exit;
}

$app = mysqli_fetch_assoc($query);

// Formater la date et l'heure
$date = new DateTime($app['rdv_date']);
$formattedDate = $date->format('d/m/Y');

$time = new DateTime($app['rdv_time']);
$formattedTime = $time->format('H:i');

// Check if the appointment is in the past or future
$currentDate = date('Y-m-d');
$currentTime = date('H:i:s');
$isPastAppointment = false;

// Compare appointment date with current date
if ($app['rdv_date'] < $currentDate) {
    $isPastAppointment = true;
} 
// If same date, check if time has passed
elseif ($app['rdv_date'] == $currentDate && $app['rdv_time'] < $currentTime) {
    $isPastAppointment = true;
}
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
<title>Omnes Immobilier - Détail du Rendez-vous</title>

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
                        <h2 class="page-name text-white text-uppercase"><b>Détail du RDV</b></h2>
                    </div>
                    <div class="col-md-6">
                        <nav aria-label="breadcrumb" class="float-md-right">
                            <ol class="breadcrumb bg-transparent m-0 p-0">
                                <li class="breadcrumb-item text-white"><a href="home.php">Accueil</a></li>
                                <li class="breadcrumb-item text-white"><a href="rdv_dashboard.php">Mes RDVs</a></li>
                                <li class="breadcrumb-item active">Détail du RDV</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <!-- Détail du RDV -->
        <div class="full-row">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="property-details-inner">
                           
                            <div class="mb-4">
                                <?php if (!empty($app['pimage1'])) { ?>
                                    <img src="images/property/<?php echo $app['pimage1']; ?>" alt="<?php echo $app['property_title']; ?>" class="property-image">
                                <?php } else { ?>
                                    <div class="alert alert-warning">Aucune image disponible</div>
                                <?php } ?>
                            </div>
                            
                            <h2 class="text-secondary"><?php echo $app['property_title']; ?></h2>
                            
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <p class="property-address "><i class="fas fa-map-marker-alt text-primary"></i> <?php echo $app['location'] . ', ' . $app['city']; ?></p>
                                    <div class="property-details-info">
                                        <ul class="property-details mb-4">
                                            <li><i class="fas fa-bed text-primary"></i> <span><?php echo $app['nbRooms']; ?> pièces</span></li>
                                            <li><i class="fas fa-bath text-primary"></i> <span><?php echo $app['nbBathrooms']; ?> bains</span></li>
                                            <li><i class="fas fa-chart-area text-primary"></i> <span><?php echo $app['area']; ?> m²</span></li>
                                        </ul>
                                    </div>
                                </div>
                                    
                                <div class="col-md-6">
                                    <div class="text-primary text-left h5 my-2 text-md-right"><?php echo number_format($app['property_price'], 0, ',', ' ') . ' €';?></div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <h4 class="text-secondary mb-3">Description</h4>
                                <p><?php echo $app['propertyDescription']; ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-4">
                        <div class="detail-box">
                            <h3 class="text-secondary mb-4">Détails du RDV</h3>
                            
                            <div class="mb-4">
                                <p class="detail-title">Date et heure</p>
                                <p class="detail-value">
                                    <i class="far fa-calendar-alt text-primary"></i> <?php echo $formattedDate; ?><br>
                                    <i class="far fa-clock text-primary"></i> <?php echo $formattedTime; ?>
                                </p>
                            </div>
                            
                            <div class="mb-4">
                                <p class="detail-title">Lieu</p>
                                <p class="detail-value"><i class="fas fa-map-marker-alt text-primary"></i> <?php echo $app['rdv_place']; ?></p>
                            </div>
                            
                            <?php if ($_SESSION['isAdmin'] || $_SESSION['isAgent']) { ?>
                            <div class="mb-4">
                                <p class="detail-title">Client</p>
                                <p class="detail-value">
                                    <i class="fas fa-user text-primary"></i> <?php echo $app['client_firstname'] . ' ' . strtoupper($app['client_name']); ?><br>
                                    <i class="fas fa-phone text-primary"></i> <?php echo $app['client_phone']; ?><br>
                                    <i class="fas fa-envelope text-primary"></i> <?php echo $app['client_email']; ?>
                                </p>
                            </div>
                            <?php } ?>
                            
                            <?php if ($_SESSION['isAdmin'] || !$_SESSION['isAgent']) { ?>
                            <div class="mb-4">
                                <p class="detail-title">Agent immobilier</p>
                                <p class="detail-value">
                                    <i class="fas fa-user-tie text-primary"></i> <?php echo $app['agent_firstname'] . ' ' . strtoupper($app['agent_name']); ?><br>
                                    <i class="fas fa-phone text-primary"></i> <?php echo $app['agent_phone']; ?><br>
                                    <i class="fas fa-envelope text-primary"></i> <?php echo $app['agent_email']; ?>
                                </p>
                            </div>
                            <?php } ?>
                            
                            <?php if (!$_SESSION['isAgent']) { ?>
                            <div class="mb-4">
                                <p class="detail-title">Statut de paiement</p>
                                <p class="detail-value">
                                    <?php if ($app['is_paid']) { ?>
                                    <span class="badge-paid"><i class="fas fa-check-circle"></i> Payé</span>
                                    <?php } else { ?>
                                    <div class="payment-container" style="flex-direction: row; align-items: center; width: fit-content;">
                                        <span class="badge-unpaid"><i class="fas fa-times-circle" ></i> À payer : <?php echo number_format($app['rdv_price'], 0, ',', ' ') . ' €'; ?></span>
                                        <a href="payment.php?appointment_id=<?php echo $app['aid']; ?>" class="payment-link">
                                            <i class="fas fa-credit-card" style="margin-right: 5px;"></i> Payer
                                        </a>
                                    </div>
                                    <?php } ?>
                                </p>
                            </div>
                            <?php } ?>
                            
                            <?php if (!empty($app['rdv_comments'])) { ?>
                            <div class="mb-4">
                                <p class="detail-title">Commentaires</p>
                                <p class="detail-value"><?php echo $app['rdv_comments']; ?></p>
                            </div>
                            <?php } ?>
                            
                            <div class="text-center">
                                <?php if (!$isPastAppointment) { ?>
                                    <a href="appointment-details.php?id=<?php echo $appointmentId; ?>&cancel=1" class="btn cancel-btn" onclick="return confirm('Êtes-vous sûr de vouloir annuler ce rendez-vous ?');">
                                        <i class="fas fa-times-circle"></i> Annuler ce rendez-vous
                                    </a>
                                <?php } else { ?>
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle"></i> Ce RDV est déjà passé et ne peut plus être annulé.
                                    </div>
                                <?php } ?>
                            </div>
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
<script src="js/font-awesome.js"></script>
</body>
</html>