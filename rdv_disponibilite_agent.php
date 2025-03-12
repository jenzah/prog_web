<?php 
ini_set('session.cache_limiter','public');
session_cache_limiter(false);
session_start();
include("config.php");

// Verify agent is logged in
if(!isset($_SESSION['uid']) || !$_SESSION['isAgent']) {
    header("location:login.php");
    exit();
}

$agent_id = $_SESSION['uid']; // Agent's own ID

function getAgentDetail($con, $agent_id) {
    $query = mysqli_query($con, "SELECT * FROM user WHERE uid = $agent_id AND utype = 'agent'");
    return mysqli_fetch_assoc($query);
}

function getWeeklySchedule($con, $agent_id) {
    // Récupérer les plages horaires de l'agent
    $schedule_query = mysqli_query($con, "SELECT * FROM agent_schedules WHERE agent_id = $agent_id ORDER BY FIELD(day_of_week, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday')");
    $schedules = [];
    while ($row = mysqli_fetch_assoc($schedule_query)) {
        $schedules[$row['day_of_week']] = $row;
    }
    return $schedules;
}

function getAppointments($con, $agent_id) {
    // Récupérer les rendez-vous existants pour la semaine en cours
    $date_debut = date('Y-m-d', strtotime('monday this week'));
    $date_fin = date('Y-m-d', strtotime('saturday this week'));
    $rdv_query = mysqli_query($con, "SELECT aid, client_id, rdv_date, rdv_time FROM appointments 
                                    WHERE agent_id = $agent_id 
                                    AND rdv_date BETWEEN '$date_debut' AND '$date_fin'
                                    AND rdv_status != 'annulé'");
    $rendez_vous = [];
    while ($row = mysqli_fetch_assoc($rdv_query)) {
        $date = $row['rdv_date'];
        $time = $row['rdv_time'];
        $rendez_vous[$date][$time] = [
            'aid' => $row['aid'],
            'client_id' => $row['client_id']
        ];
    }
    return $rendez_vous;
}

function getClientName($con, $client_id) {
    $query = mysqli_query($con, "SELECT ufirstname, uname FROM user WHERE uid = $client_id");
    $client = mysqli_fetch_assoc($query);
    if ($client) {
        return $client['ufirstname'] . ' ' . $client['uname'];
    }
    return "Client inconnu";
}

function getWorkdayBoundaries($schedules) {
    $earliest_start = "23:59:59"; // Start with latest possible time
    $latest_end = "00:00:00";     // Start with earliest possible time
    
    foreach ($schedules as $schedule) {
        // Only consider working days
        if ($schedule['is_working_day'] == 1) {
            if ($schedule['workday_start'] < $earliest_start) {
                $earliest_start = $schedule['workday_start'];
            }
            if ($schedule['workday_end'] > $latest_end) {
                $latest_end = $schedule['workday_end'];
            }
        }
    }

    // If no working days found, set defaults
    if ($earliest_start == "23:59:59") {
        $earliest_start = "09:00:00";
    }
    if ($latest_end == "00:00:00") {
        $latest_end = "18:00:00";
    }
    
    return ['earliest' => $earliest_start,
            'latest' => $latest_end];
}

function generateTimeSlots($earliest, $latest) {
    $horaires = [];
    $start_hour = intval(substr($earliest, 0, 2));
    $end_hour = intval(substr($latest, 0, 2)) - 1; // End one hour before
    
    for ($h = $start_hour; $h <= $end_hour; $h++) {
        $horaires[] = sprintf("%02d:00:00", $h);
    }
    return $horaires;
}

function getCurrentWeekDates() {
    $dates = [];
    for ($i = 0; $i < 6; $i++) {
        $dates[] = date('Y-m-d', strtotime("monday this week +$i days"));
    }
    return $dates;
}

// Compter les propriétés gérées par l'agent
function getAgentPropertiesCount($con, $agent_id) {
    $properties_query = mysqli_query($con, "SELECT COUNT(*) as count FROM property WHERE agentid = $agent_id");
    return mysqli_fetch_assoc($properties_query)['count'];
}

// Récupérer les spécialités de l'agent
$agent = getAgentDetail($con, $agent_id);
$specialty = $agent['specialty'] ?? "Non spécifié";

$jours = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
$jours_fr = ["Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi"];

$schedules = getWeeklySchedule($con, $agent_id);
$rendez_vous = getAppointments($con, $agent_id);
$boundaries = getWorkdayBoundaries($schedules);
$horaires = generateTimeSlots($boundaries['earliest'], $boundaries['latest']);
$dates = getCurrentWeekDates();
$properties_count = getAgentPropertiesCount($con, $agent_id);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Mon Agenda - <?= htmlspecialchars($agent['uname']) ?></title>

<!-- Meta Tags -->
<link rel="shortcut icon" href="images/favicon.ico">

<!--	Fonts
	========================================================-->
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">

<!-- Bootstrap & styles -->
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

<style>
    /* Custom calendar styles */
    .client-calendar .dispo { 
        cursor: pointer;
        transition: all 0.3s;
    }
    .client-calendar .dispo:hover {
        background-color: #6c8f8d;
    }
    .staff-calendar .dispo {
        cursor: default;
    }

    .staff-calendar .dispo:hover {
        /* Override any hover effects */
        background-color: white !important;
    }
    .passe {
        background-color: #b4b4b4;
        color: white; 
        cursor: default;
    }
    .indispo { 
        background-color: #b4b4b4; 
        color: white; 
    }
    .legende { 
        display: flex; 
        gap: 20px; 
        margin-bottom: 20px; 
        justify-content: center;
    }
    .legende-item { 
        display: flex; 
        align-items: center; 
        gap: 5px; 
    }
    .legende-color { 
        width: 20px; 
        height: 20px;
        border-radius: 3px; 
    }
    .rdv-user {
        background-color: #4F6C6B !important; /* Green background */
        color: white !important;
    }
    .rdv-clickable {
        cursor: pointer !important; /* Change cursor to indicate clickable */
    }
    .rdv-clickable:hover {
        background-color: #314b4a !important; /* Darker green on hover */
    }
    .date-subheader {
        display: block;
        font-size: 80%;
        font-weight: normal;
    }
    .horaire-cell {
        font-weight: bold;
    }
    .rdv-info {
        font-size: 10px;
        display: block;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 100px;
    }
</style>
</head>

<body>
    <?php include("include/header.php"); ?>
    
    <!-- Page Title -->
    <div class="banner-full-row page-banner" style="background-image:url('images/breadcrumb.jpg');">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h2 class="page-name text-white text-uppercase"><b>Mon Agenda</b></h2>
                </div>
                <div class="col-md-6">
                    <nav aria-label="breadcrumb" class="float-md-right">
                        <ol class="breadcrumb bg-transparent m-0 p-0">
                            <li class="breadcrumb-item text-white"><a href="index.php">Accueil</a></li>
                            <li class="breadcrumb-item active">Agenda</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    
    <div class="full-row">
        <div class="container">
            <div class="row mb-4">
                <div class="col-lg-12">
                    <h2 class="text-secondary double-down-line text-center">Mon Calendrier</h2>
                    <p class="text-center">Semaine du <?= date('d/m/Y', strtotime('monday this week')) ?> au <?= date('d/m/Y', strtotime('saturday this week')) ?></p>
                </div>
            </div>
            
            <div class="row">
                <!-- Colonne de gauche - Profil de l'agent -->
                <div class="col-lg-3">
                    <div class="agent-info-sidebar p-3">
                        <div class="text-center mb-4">
                            <img src="images/profile_pic/<?= $agent['uimage'] ?>" class="agent-photo" alt="Photo de l'agent" style="width: 150px; height: 150px; object-fit: cover; border-radius: 50%;">
                            <h4 class="text-secondary mt-3"><?= htmlspecialchars($agent['ufirstname']) ?> <?= htmlspecialchars(strtoupper($agent['uname'])) ?></h4>
                            <p class="text-muted">Agent Immobilier</p>
                        </div>
                        
                        <div class="agent-stat mb-2">
                            <strong><i class="fa fa-briefcase"></i> Spécialité:</strong> 
                            <span class="float-right"><?= htmlspecialchars($specialty) ?></span>
                        </div>
                        
                        <div class="agent-stat mb-2">
                            <strong><i class="fa fa-envelope"></i> Email:</strong>
                            <span class="float-right" style="max-width: 65%; font-size: 13px;"><?= htmlspecialchars($agent['uemail']) ?></span>
                        </div>
                        
                        <div class="agent-stat mb-2">
                            <strong><i class="fa fa-phone"></i> Téléphone:</strong> 
                            <span class="float-right"><?= htmlspecialchars($agent['uphone']) ?></span>
                        </div>
                        
                        <div class="agent-stat mb-2">
                            <strong><i class="fa fa-building"></i> Propriétés:</strong> 
                            <span class="float-right"><?= $properties_count ?></span>
                        </div>
                    </div>
                </div>
                
                <!-- Colonne de droite - Calendrier -->
                <div class="col-lg-9 col-md-8">
                    <div class="table-responsive">
                        <table class="table table-bordered auto-table text-center content-center staff-calendar">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th>Heure</th>
                                    <?php foreach ($jours_fr as $index => $jour) { 
                                        $date = $dates[$index];
                                        $date_display = date('d/m', strtotime($date));
                                    ?>
                                        <th><?= $jour ?><span class="date-subheader"><?= $date_display ?></span></th>
                                    <?php } ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($horaires as $heure) { 
                                // Formater l'heure pour l'affichage
                                $heure_affichage = substr($heure, 0, 5);
                                ?>
                                    <tr>
                                        <td class="horaire-cell"><?= $heure_affichage ?></td>
                                        
                                        <?php foreach ($jours as $index => $jour) { 
                                            $date_rdv = $dates[$index];
                                            $est_jour_travail = isset($schedules[$jour]) && $schedules[$jour]['is_working_day'] == 1;
                                            $heure_debut = isset($schedules[$jour]) ? $schedules[$jour]['workday_start'] : '09:00:00';
                                            $heure_fin = isset($schedules[$jour]) ? $schedules[$jour]['workday_end'] : '18:00:00';

                                            $est_dans_plage_horaire = $heure >= $heure_debut && $heure <= $heure_fin;
                                            $est_reserve = isset($rendez_vous[$date_rdv][$heure]);
                                            
                                            // Pour un agent, on n'a pas besoin de vérifier si le RDV est passé
                                            $est_dans_le_passe = false; // Désactiver la vérification du passé

                                            // Déterminer la classe et le texte de la cellule
                                            if ($est_reserve) {
                                                // Récupérer les informations du RDV
                                                $rdv_info = $rendez_vous[$date_rdv][$heure];
                                                $rdv_id = $rdv_info['aid'];
                                                $client_id = $rdv_info['client_id'];
                                                $client_name = getClientName($con, $client_id);
                                                
                                                $classe = "rdv-user rdv-clickable";
                                                $texte = "RDV<span class='rdv-info'>" . htmlspecialchars($client_name) . "</span>";
                                                
                                                echo '<td class="' . $classe . '" data-rdv-id="' . $rdv_id . '" data-jour="' . $jour . '" data-date="' . $date_rdv . '" data-heure="' . $heure . '">' . $texte . '</td>';
                                            } elseif ($est_dans_le_passe) {
                                                // Past time slots
                                                $classe = "passe";
                                                $texte = "✗";
                                                echo '<td class="' . $classe . '" data-jour="' . $jour . '" data-date="' . $date_rdv . '" data-heure="' . $heure . '">' . $texte . '</td>';
                                            } elseif ($est_jour_travail && $est_dans_plage_horaire) {
                                                $classe = "dispo";
                                                $texte = "✓";
                                                echo '<td class="' . $classe . '" data-jour="' . $jour . '" data-date="' . $date_rdv . '" data-heure="' . $heure . '">' . $texte . '</td>';
                                            } else {
                                                $classe = "indispo";
                                                $texte = "✗";
                                                echo '<td class="' . $classe . '" data-jour="' . $jour . '" data-date="' . $date_rdv . '" data-heure="' . $heure . '">' . $texte . '</td>';
                                            }
                                        } ?>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="legende">
                        <div class="legende-item">
                            <div class="legende-color dispo"></div>
                            <span>✓ Disponible</span>
                        </div>
                        <div class="legende-item">
                            <div class="legende-color indispo"></div>
                            <span>Indisponible</span>
                        </div>
                        <div class="legende-item">
                            <div class="legende-color rdv-user"></div>
                            <span>Rendez-vous</span>
                        </div>
                        <!-- Légende du passé supprimée car non utilisée pour l'agent -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php include("include/footer.php"); ?>
    
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    
    <script>
    $(document).ready(function() {
        // Gestionnaire pour les RDV
        $('.rdv-clickable').click(function() {
            const rdvId = $(this).data('rdv-id');
            window.location.href = 'rdv_details.php?id=' + rdvId;
        });
    });
    </script>
</body>
</html>