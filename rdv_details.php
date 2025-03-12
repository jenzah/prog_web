<?php 
ini_set('session.cache_limiter','public');
session_cache_limiter(false);
session_start();
include("config.php");

// Vérifier si l'utilisateur est connecté
if(!isset($_SESSION['uid'])) {
    header("location:login.php");
    exit();
}

// Vérifier si l'ID du rendez-vous est fourni
if(!isset($_GET['id'])) {
    header("location:rdv_dashboard.php");
    exit;
}

$appointmentId = (int) $_GET['id'];
$userId = $_SESSION['uid'];

// Définir les variables d'accès utilisateur
$isAdmin = isset($_SESSION['utype']) && $_SESSION['utype'] === 'admin';
$isAgent = isset($_SESSION['utype']) && $_SESSION['utype'] === 'agent';

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
               agent.uid as agent_id,
               agent.uname as agent_name,
               agent.ufirstname as agent_firstname,
               agent.uphone as agent_phone,
               agent.uemail as agent_email,
               agent.uimage as agent_image,
               agent.specialty as agent_specialty,
               agent.cv as agent_cv,
               client.uid as client_id,
               client.uname as client_name,
               client.ufirstname as client_firstname,
               client.uphone as client_phone,
               client.uemail as client_email,
               client.uimage as client_image
        FROM appointments a
        LEFT JOIN property p ON a.property_id = p.pid
        LEFT JOIN user agent ON a.agent_id = agent.uid
        LEFT JOIN user client ON a.client_id = client.uid
        WHERE a.aid = '$appointmentId'";

// Vérifier si l'utilisateur a le droit de voir ce rendez-vous
$sql .= " AND (a.agent_id = '$userId' OR a.client_id = '$userId')";

$query = mysqli_query($con, $sql);

if (mysqli_num_rows($query) == 0) {
    echo "<script>alert('Rendez-vous introuvable ou accès non autorisé.'); window.location='rdv_dashboard.php';</script>";
    exit;
}

$app = mysqli_fetch_assoc($query);

// Déterminer les propriétés en fonction du rôle de l'utilisateur connecté
if ($isAgent) {
    // Si c'est l'agent qui est connecté, montrer les infos du client
    $personToShowId = $app['client_id'];
    $personToShowName = $app['client_name'];
    $personToShowFirstName = $app['client_firstname'];
    $personToShowEmail = $app['client_email'];
    $personToShowPhone = $app['client_phone'];
    $personToShowImage = $app['client_image'];
    $personToShowRole = 'Client';
    
    // Compter le nombre de RDV du client
    $client_appointments_query = mysqli_query($con, "SELECT COUNT(*) as count FROM appointments WHERE client_id = " . $app['client_id']);
    $person_stats = 0;
    if ($count_result = mysqli_fetch_assoc($client_appointments_query)) {
        $person_stats = $count_result['count'];
    }
    $personStatsLabel = "Total des RDVs";
} else {
    // Si c'est un client ou admin qui est connecté, montrer les infos de l'agent
    $personToShowId = $app['agent_id'];
    $personToShowName = $app['agent_name'];
    $personToShowFirstName = $app['agent_firstname'];
    $personToShowEmail = $app['agent_email'];
    $personToShowPhone = $app['agent_phone'];
    $personToShowImage = $app['agent_image'];
    $personToShowRole = 'Agent Immobilier';
    
    // Compter le nombre de propriétés de l'agent
    $properties_count_query = mysqli_query($con, "SELECT COUNT(*) as count FROM property WHERE agentid = " . $app['agent_id']);
    $person_stats = 0;
    if ($properties_count_result = mysqli_fetch_assoc($properties_count_query)) {
        $person_stats = $properties_count_result['count'];
    }
    $personStatsLabel = "Propriétés";
}

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

// Définir l'emplacement par défaut si non spécifié
$rdvPlace = !empty($app['rdv_place']) ? $app['rdv_place'] : "Sur place: 12 Rue de Paris, 75001 Paris";

// Formater la spécialité de l'agent
$specialtyLabels = [
    'résidentiel' => 'Immobilier résidentiel',
    'commercial' => 'Immobilier commercial',
    'terrain' => 'Terrains',
    'appartement' => 'Locations'
];
$agentSpecialty = isset($specialtyLabels[$app['agent_specialty']]) ? $specialtyLabels[$app['agent_specialty']] : 'Général';
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
    
    <!--	Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
    
    <!--	Css Link -->
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
    
    <style>
    /* Styling for appointment details */

    .detail-title {
        font-weight: 600;
        color: #333;
        margin-bottom: 5px;
        font-size: 16px;
    }

    .detail-value {
        margin-bottom: 15px;
        color: #555;
    }
    /* Person sidebar styling */
    .person-info-sidebar {
        background-color: #ffffff;
        border-radius: 5px;
        box-shadow: 0 0 15px rgba(0,0,0,0.1);
        padding: 25px;
        margin-bottom: 30px;
        height: 100%;
    }

    .person-photo {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        object-fit: cover;
        margin-bottom: 15px;
        border: 3px solid #f5f5f5;
    }

    .person-stat {
        padding: 10px 0;
        border-bottom: 1px solid #eee;
        font-size: 14px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .person-stat:last-of-type {
        border-bottom: none;
    }

    .property-image {
        width: 100%;
        border-radius: 5px;
        height: auto;
    }

    .property-section {
        margin-top: 30px;
        background-color: #f9f9f9;
        border-radius: 5px;
        padding: 20px;
    }

    .property-details li {
        display: inline-block;
        margin-right: 20px;
    }

    .no-property-message {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
        margin-top: 20px;
        text-align: center;
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
                            <h2 class="page-name text-white text-uppercase"><b>Détail du RDV</b></h2>
                        </div>
                        <div class="col-md-6">
                            <nav aria-label="breadcrumb" class="float-md-right">
                                <ol class="breadcrumb bg-transparent m-0 p-0">
                                    <li class="breadcrumb-item text-white"><a href="index.php">Accueil</a></li>
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
                        <!-- Colonne de gauche - Profil de la personne (agent ou client selon qui est connecté) -->
                        <div class="col-lg-4">
                            <div class="person-info-sidebar">
                                <div class="text-center mb-4">
                                    <img src="images/profile_pic/<?= !empty($personToShowImage) ? $personToShowImage : 'default.jpg' ?>" class="person-photo" alt="Photo du <?= $personToShowRole ?>">
                                    <h4 class="text-secondary"><?= htmlspecialchars($personToShowFirstName) ?> <?= htmlspecialchars(strtoupper($personToShowName)) ?></h4>
                                    <p class="text-muted mb-3"><?= $personToShowRole ?></p>
                                </div>
                                
                                <?php if (!$isAgent) { // Si c'est un client qui voit le profil d'un agent ?>
                                <div class="person-stat">
                                    <strong><i class="fa fa-briefcase"></i> Spécialité:</strong> 
                                    <span class="float-right"><?= htmlspecialchars($agentSpecialty) ?></span>
                                </div>
                                <?php } ?>
                                
                                <div class="person-stat">
                                    <strong><i class="fa fa-envelope"></i> Email:</strong>
                                    <div class="float-right text-truncate" style="max-width: 65%; font-size: 13px;">
                                        <a href="mailto:<?php echo htmlspecialchars($personToShowEmail); ?>"><?php echo htmlspecialchars($personToShowEmail); ?></a>
                                    </div>
                                </div>
                                
                                <div class="person-stat">
                                    <strong><i class="fa fa-phone"></i> Téléphone:</strong> 
                                    <span class="float-right"><?php echo htmlspecialchars($personToShowPhone); ?></span>
                                </div>
                                
                                <div class="person-stat">
                                    <strong><i class="fa fa-<?= $isAgent ? 'calendar' : 'building' ?>"></i> <?= $personStatsLabel ?>:</strong> 
                                    <span class="float-right"><?= $person_stats ?></span>
                                </div>
                                
                                <!-- Boutons actions - visibles uniquement pour les clients qui consultent un agent -->
                                <?php if (!$isAgent) { ?>
                                <div class="mt-4 d-flex flex-column align-items-center">
                                    <!-- Bouton "Prendre Rendez-vous" -->
                                    <a href="rdv_disponibilite.php?agent_id=<?php echo htmlspecialchars($app['agent_id']); ?>" class="btn btn-primary btn-block w-75 mb-2" style="font-size: 13px;">
                                        <i class="fa fa-calendar"></i> Nouveau RDV
                                    </a>

                                    <!-- Bouton "Télécharger le CV" -->
                                    <?php if (!empty($app['agent_cv']) && file_exists("images/cv/" . $app['agent_cv'])) { ?>
                                        <a href="images/cv/<?php echo $app['agent_cv']; ?>" download class="btn btn-secondary btn-block w-75 mb-2" style="font-size: 13px;">
                                            <i class="fa fa-file-text"></i> Télécharger CV
                                        </a>
                                    <?php } else { ?>
                                        <button class="btn btn-secondary btn-block w-75 mb-2 disabled" disabled style="font-size: 13px;">
                                            <i class="fa fa-file-text"></i> CV non disponible
                                        </button>
                                    <?php } ?>
                                    
                                    <!-- Bouton "Messagerie" -->
                                    <a href="messagerie.php?agent_id=<?php echo htmlspecialchars($app['agent_id']); ?>" class="btn btn-secondary btn-block w-75">
                                        <i class="fa fa-comments"></i> Messagerie
                                    </a>
                                </div>
                                <?php } else { ?>
                                <!-- Boutons pour l'agent qui consulte un client -->
                                <div class="mt-4 d-flex flex-column align-items-center">
                                    <!-- Bouton "Messagerie" pour contacter le client -->
                                    <!-- <a href="messagerie.php?client_id=<?php echo htmlspecialchars($app['client_id']); ?>" class="btn btn-secondary btn-block w-75">
                                        <i class="fa fa-comments"></i> Contacter le client
                                    </a> -->
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                        
                        <!-- Colonne de droite - Détails du RDV -->
                        <div class="col-lg-8">
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
                                    <p class="detail-value"><i class="fas fa-map-marker-alt text-primary"></i> <?php echo $rdvPlace; ?></p>
                                </div>
                                
                                <?php if (!$isAgent) { // Si c'est un client qui consulte ?>
                                <div class="mb-4">
                                    <p class="detail-title">Agent Immobilier</p>
                                    <p class="detail-value">
                                        <i class="fas fa-user text-primary"></i> <?php echo $app['agent_firstname'] . ' ' . strtoupper($app['agent_name']); ?><br>
                                        <i class="fas fa-phone text-primary"></i> <?php echo $app['agent_phone']; ?><br>
                                        <i class="fas fa-envelope text-primary"></i> <?php echo $app['agent_email']; ?>
                                    </p>
                                </div>
                                <?php } else { // Si c'est un agent qui consulte ?>
                                <div class="mb-4">
                                    <p class="detail-title">Client</p>
                                    <p class="detail-value">
                                        <i class="fas fa-user text-primary"></i> <?php echo $app['client_firstname'] . ' ' . strtoupper($app['client_name']); ?><br>
                                        <i class="fas fa-phone text-primary"></i> <?php echo $app['client_phone']; ?><br>
                                        <i class="fas fa-envelope text-primary"></i> <?php echo $app['client_email']; ?>
                                    </p>
                                </div>
                                <?php } ?>
                                
                                <?php if (!empty($app['rdv_motivation'])) { ?>
                                <div class="mb-4">
                                    <p class="detail-title">Motif du rendez-vous</p>
                                    <p class="detail-value"><i class="fa fa-info-circle text-primary"></i> <?php echo $app['rdv_motivation']; ?></p>
                                </div>
                                <?php } ?>
                                
                                <?php if (!$isAgent) { ?>
                                <div class="mb-4">
                                    <p class="detail-title">Statut de paiement</p>
                                    <p class="detail-value">
                                        <?php if (isset($app['is_paid']) && $app['is_paid']) { ?>
                                        <span class="badge-paid"><i class="fas fa-check-circle"></i> Payé</span>
                                        <?php } else { ?>
                                        <div class="payment-container" style="flex-direction: row; align-items: center; width: fit-content;">
                                            <span class="badge-unpaid"><i class="fas fa-times-circle" ></i> À payer : <?php echo number_format($app['rdv_price'], 0, ',', ' ') . ' €'; ?></span>
                                            <a href="payment_confirm.php?appointment_id=<?php echo $app['aid']; ?>" class="payment-link">
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
                                
                                <?php if (!empty($app['property_title'])) { ?>
                                <!-- Information sur la propriété -->
                                <div class="property-section">
                                    <h4 class="text-secondary mb-3">Propriété concernée</h4>
                                    
                                    <div class="mb-4">
                                        <?php if (!empty($app['pimage1'])) { ?>
                                            <img src="images/property/<?php echo $app['pimage1']; ?>" alt="<?php echo $app['property_title']; ?>" class="property-image">
                                        <?php } else { ?>
                                            <div class="alert alert-warning">Aucune image disponible</div>
                                        <?php } ?>
                                    </div>
                                    
                                    <h5 class="text-secondary"><?php echo $app['property_title']; ?></h5>
                                    
                                    <div class="mb-3">
                                        <p class="mb-2"><i class="fas fa-map-marker-alt text-primary"></i> <?php echo $app['location'] . ', ' . $app['city']; ?></p>
                                        <ul class="property-details pl-0">
                                            <li><i class="fas fa-bed text-primary"></i> <span><?php echo $app['nbRooms']; ?> pièces</span></li>
                                            <li><i class="fas fa-bath text-primary"></i> <span><?php echo $app['nbBathrooms']; ?> bains</span></li>
                                            <li><i class="fas fa-chart-area text-primary"></i> <span><?php echo $app['area']; ?> m²</span></li>
                                            <li><i class="fas fa-euro-sign text-primary"></i> <span><?php echo number_format($app['property_price'], 0, ',', ' ') . ' €'; ?></span></li>
                                        </ul>
                                    </div>
                                    
                                    <a href="propertydetail.php?pid=<?php echo $app['property_id']; ?>" class="btn btn-sm btn-primary">
                                        <i class="fa fa-eye"></i> Voir la propriété
                                    </a>
                                </div>
                                <?php } else { ?>
                                <div class="no-property-message">
                                    <i class="fa fa-info-circle fa-2x mb-2"></i>
                                    <p>Aucune propriété spécifique n'est associée à ce rendez-vous.</p>
                                </div>
                                <?php } ?>
                                
                                <div class="text-center mt-4">
                                    <?php if (!$isPastAppointment) { ?>
                                        <a href="rdv_details.php?id=<?php echo $appointmentId; ?>&cancel=1" class="btn cancel-btn" onclick="return confirm('Êtes-vous sûr de vouloir annuler ce rendez-vous ?');">
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