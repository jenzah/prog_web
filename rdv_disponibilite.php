<?php 
ini_set('session.cache_limiter','public');
session_cache_limiter(false);
session_start();
include("config.php");

if(!isset($_SESSION['uid'])) {
    header("location:login.php");
}

if (!isset($_GET['agent_id'])) {
    header("location:rdv_dashboard.php");
    exit();
}

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
    $rdv_query = mysqli_query($con, "SELECT rdv_date, rdv_time FROM appointments 
                                    WHERE agent_id = $agent_id 
                                    AND rdv_date BETWEEN '$date_debut' AND '$date_fin'
                                    AND rdv_status != 'annulé'");
    $rendez_vous = [];
    while ($row = mysqli_fetch_assoc($rdv_query)) {
        $date = $row['rdv_date'];
        $time = $row['rdv_time'];
        $rendez_vous[$date][$time] = true;
    }
    return $rendez_vous;
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

$agent_id = intval($_GET['agent_id']); 

// Récupérer les spécialités de l'agent
$specialty = $agent['specialty'] ?? "Non spécifié";

$jours = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
$jours_fr = ["Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi"];

$agent = getAgentDetail($con, $agent_id);
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
<title>Disponibilité - <?= htmlspecialchars($agent['uname']) ?></title>

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
    .schedule-table th, .schedule-table td { 
        text-align: center; 
        padding: 10px; 
        border: 1px solid #dee2e6;
    }
    .schedule-table th { 
        background-color: #3D73D7; 
        color: white; 
        font-weight: 600;
    }
    .dispo { 
        background-color: #3CAC85; 
        color: white; 
        cursor: pointer;
        transition: all 0.3s;
    }
    .dispo:hover {
        background-color: #2e8b67;
        transform: scale(1.05);
    }
    .indispo { 
        background-color: #D25A58; 
        color: white; 
    }
    .rdv { 
        background-color: #F5A623; 
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
    .agent-info {
        background-color: #f8f9fa;
        border-radius: 5px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    .agent-photo {
        width: 160px;
        height: 160px;
        border-radius: 50%;
        object-fit: cover;
        margin-bottom: 15px;
    }
    .horaire-cell {
        font-weight: 500;
        background-color: #f8f9fa;
    }
    .date-subheader {
        font-size: 12px;
        display: block;
        color: rgba(255,255,255,0.7);
    }
    .agent-contact-btn {
        margin-bottom: 10px;
        width: 100%;
    }
    .agent-info-sidebar {
        background-color: #f8f9fa;
        border-radius: 5px;
        padding: 20px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        height: 100%;
    }
    .agent-stat {
        margin-bottom: 10px;
        padding-bottom: 10px;
        border-bottom: 1px solid #eee;
    }
    .agent-stat:last-child {
        border-bottom: none;
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
                    <h2 class="page-name text-white text-uppercase"><b>Disponibilité de l'agent</b></h2>
                </div>
                <div class="col-md-6">
                    <nav aria-label="breadcrumb" class="float-md-right">
                        <ol class="breadcrumb bg-transparent m-0 p-0">
                            <li class="breadcrumb-item text-white"><a href="home.php">Accueil</a></li>
                            <li class="breadcrumb-item text-white"><a href="agents.php">Agents</a></li>
                            <li class="breadcrumb-item active">Disponibilité</li>
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
                    <h2 class="text-secondary double-down-line text-center">Calendrier de Disponibilité</h2>
                    <p class="text-center">Semaine du <?= date('d/m/Y', strtotime('monday this week')) ?> au <?= date('d/m/Y', strtotime('saturday this week')) ?></p>
                </div>
            </div>
            
            <div class="row">
                <!-- Colonne de gauche - Profil de l'agent -->
                <div class="col-lg-3">
                    <div class="agent-info-sidebar p-3">
                        <div class="text-center mb-4">
                            <img src="images/profile_pic/<?= $agent['uimage'] ?>" class="agent-photo" alt="Photo de l'agent">
                            <h4 class="text-secondary"><?= htmlspecialchars($agent['ufirstname']) ?> <?= htmlspecialchars(strtoupper($agent['uname'])) ?></h4>
                            <p class="text-muted mb-3">Agent Immobilier</p>
                        </div>
                        
                        <div class="agent-stat">
                            <strong><i class="fa fa-briefcase"></i> Spécialité:</strong> 
                            <span class="float-right"><?= htmlspecialchars($specialty) ?></span>
                        </div>
                        
                        <div class="agent-stat">
                            <strong><i class="fa fa-envelope"></i> Email:</strong>
                            <div class="float-right text-truncate" style="max-width: 65%; font-size: 13px;">
                                <a href="mailto:<?php echo htmlspecialchars($agent['uemail']); ?>"><?php echo htmlspecialchars($agent['uemail']); ?></a>
                            </div>
                        </div>
                        
                        <div class="agent-stat">
                            <strong><i class="fa fa-phone"></i> Téléphone:</strong> 
                            <span class="float-right"><?= htmlspecialchars($agent['uphone']) ?></span>
                        </div>
                        
                        <div class="agent-stat">
                            <strong><i class="fa fa-building"></i> Propriétés:</strong> 
                            <span class="float-right"><?= $properties_count ?></span>
                        </div>
                        
                        <!-- Boutons actions -->
                        <div class="mt-4 d-flex flex-column align-items-center">
                            <!-- Bouton "Prendre Rendez-vous" -->
                            <a href="rdv_disponibilite.php?agent_id=<?php echo htmlspecialchars($row['agentid']); ?>" class="btn btn-primary btn-block w-75" style="font-size: 13px;">
                                <i class="fa fa-calendar"></i> Prendre RDV
                            </a>

                            <!-- Bouton "Télécharger le CV" -->
                            <?php if (!empty($row['cv']) && file_exists("images/cv/" . $row['cv'])) { ?>
                                <a href="images/cv/<?php echo $row['cv']; ?>" download class="btn btn-secondary btn-block w-75" style="font-size: 13px;">
                                    <i class="fa fa-file-text"></i> Télécharger CV
                                </a>
                            <?php } else { ?>
                                <button class="btn btn-secondary btn-block w-75 disabled" disabled style="font-size: 13px;">
                                    <i class="fa fa-file-text"></i> CV non disponible
                                </button>
                            <?php } ?>
                            
                            <!-- Bouton "Messagerie" -->
                            <a href="messagerie.php?agent_id=<?php echo htmlspecialchars($row['agentid']); ?>" class="btn btn-secondary btn-block w-75">
                                <i class="fa fa-comments"></i> Messagerie
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Colonne de droite - Calendrier -->
                <div class="col-lg-9 col-md-8">
                    <div class="legende">
                        <div class="legende-item">
                            <div class="legende-color dispo"></div>
                            <span>Disponible</span>
                        </div>
                        <div class="legende-item">
                            <div class="legende-color indispo"></div>
                            <span>Indisponible</span>
                        </div>
                        <div class="legende-item">
                            <div class="legende-color rdv"></div>
                            <span>Rendez-vous pris</span>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table schedule-table">
                            <thead>
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
                                            
                                            // Déterminer la classe et le texte de la cellule
                                            if ($est_reserve) {
                                                $classe = "rdv";
                                                $texte = "RDV";
                                            } elseif ($est_jour_travail && $est_dans_plage_horaire) {
                                                $classe = "dispo";
                                                $texte = "✓";
                                            } else {
                                                $classe = "indispo";
                                                $texte = "✗";
                                            }
                                        ?>
                                            <td class="<?= $classe ?>" data-jour="<?= $jour ?>" data-date="<?= $date_rdv ?>" data-heure="<?= $heure ?>">
                                                <?= $texte ?>
                                            </td>
                                        <?php } ?>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <?php if(!isset($_SESSION['uid'])) { ?>
                    <div class="mt-4">
                        <div class="alert alert-info text-center">
                            Connectez-vous pour prendre rendez-vous avec cet agent.
                            <a href="login.php" class="btn btn-primary btn-sm ml-3">Se connecter</a>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    
    <?php include("include/footer.php"); ?>
    
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    
    <script>
    // Script pour permettre la prise de rendez-vous en cliquant sur une cellule disponible
    $(document).ready(function() {
        $('.dispo').click(function() {
            <?php if(isset($_SESSION['uid'])) { ?>
                const jour = $(this).data('jour');
                const date = $(this).data('date');
                const heure = $(this).data('heure');
                const jour_fr = {
                    'Monday': 'Lundi',
                    'Tuesday': 'Mardi',
                    'Wednesday': 'Mercredi',
                    'Thursday': 'Jeudi',
                    'Friday': 'Vendredi',
                    'Saturday': 'Samedi'
                }[jour];
                
                const date_fr = new Date(date).toLocaleDateString('fr-FR');
                
                if (confirm(`Voulez-vous prendre rendez-vous le ${jour_fr} ${date_fr} à ${heure.substring(0, 5)} ?`)) {
                    // Rediriger vers la page de prise de rendez-vous
                    window.location.href = `rdv_confirmation.php?agent_id=<?= $agent_id ?>&date=${date}&heure=${heure}`;
                }
            <?php } else { ?>
                alert('Veuillez vous connecter pour prendre rendez-vous.');
                window.location.href = 'login.php';
            <?php } ?>
        });
    });
    </script>
</body>
</html>