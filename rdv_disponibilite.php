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

// week parameter to handle navigation
$week_offset = isset($_GET['week']) ? intval($_GET['week']) : 0;

function getAgentDetail($con, $agent_id) {
    $query = mysqli_query($con, "SELECT * FROM user WHERE uid = $agent_id AND utype = 'agent'");
    return mysqli_fetch_assoc($query);
}

function getSchedule($con, $agent_id) {
    // Récupérer les plages horaires de l'agent
    $schedule_query = mysqli_query($con, "SELECT * FROM agent_schedules WHERE agent_id = $agent_id ORDER BY FIELD(day_of_week, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday')");
    $schedules = [];
    while ($row = mysqli_fetch_assoc($schedule_query)) {
        $schedules[$row['day_of_week']] = $row;
    }
    return $schedules;
}

function getAppointments($con, $agent_id, $week_offset = 0) {
    // Récupérer les rendez-vous existants pour la semaine spécifiée
    $date_debut = date('Y-m-d', strtotime("monday this week $week_offset weeks"));
    $date_fin = date('Y-m-d', strtotime("saturday this week $week_offset weeks"));
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

function getCurrentWeekDates($week_offset = 0) {
    $dates = [];
    for ($i = 0; $i < 6; $i++) {
        $dates[] = date('Y-m-d', strtotime("monday this week $week_offset weeks +$i days"));
    }
    return $dates;
}

// Compter les propriétés gérées par l'agent
function getAgentPropertiesCount($con, $agent_id) {
    $properties_query = mysqli_query($con, "SELECT COUNT(*) as count FROM property WHERE agentid = $agent_id");
    return mysqli_fetch_assoc($properties_query)['count'];
}

$agent_id = intval($_GET['agent_id']); 

$jours = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
$jours_fr = ["Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi"];

$agent = getAgentDetail($con, $agent_id);
$schedules = getSchedule($con, $agent_id);
$rendez_vous = getAppointments($con, $agent_id, $week_offset);
$boundaries = getWorkdayBoundaries($schedules);
$horaires = generateTimeSlots($boundaries['earliest'], $boundaries['latest']);
$dates = getCurrentWeekDates($week_offset);
$properties_count = getAgentPropertiesCount($con, $agent_id);

// Récupérer les spécialités de l'agent
$specialty = $agent['specialty'] ?? "Non spécifié";

// Get week start and end dates for display
$week_start = date('d/m/Y', strtotime("monday this week $week_offset weeks"));
$week_end = date('d/m/Y', strtotime("saturday this week $week_offset weeks"));

// Calculate previous and next week offsets
$prev_week = $week_offset - 1;
$next_week = $week_offset + 1;
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
                            <li class="breadcrumb-item text-white"><a href="liste_agents.php">Agents</a></li>
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
                    <h2 class="text-secondary text-center">Calendrier de Disponibilité</h2>
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
                        
                        <?php if (!$_SESSION['isAgent'] && !$_SESSION['isAdmin']) { ?>
                        <!-- Boutons actions -->
                        <div class="mt-4 d-flex flex-column align-items-center">
                            <!-- Bouton "Télécharger le CV" -->
                            <?php if (!empty($row['cv']) && file_exists("images/cv/" . $agent['cv'])) { ?>
                                <a href="images/cv/<?php echo $agent['cv']; ?>" download class="btn btn-secondary btn-block w-75" style="font-size: 13px;">
                                    <i class="fa fa-file-text"></i> Télécharger CV
                                </a>
                            <?php } else { ?>
                                <button class="btn btn-secondary btn-block w-75 disabled" disabled style="font-size: 13px;">
                                    <i class="fa fa-file-text"></i> CV non disponible
                                </button>
                            <?php } ?>
                            
                            <!-- Bouton "Messagerie" -->
                            <a href="messagerie.php?agent_id=<?php echo htmlspecialchars($agent['uid']); ?>" class="btn btn-secondary btn-block w-75">
                                <i class="fa fa-comments"></i> Messagerie
                            </a>
                        </div>
                        <?php }?>
                    </div>
                </div>
                
                <!-- Colonne de droite - Calendrier -->
                <div class="col-lg-9 col-md-8">
                    <!-- Week Navigation Bar -->
                    <div class="week-navigation">
                        <a href="?agent_id=<?= $agent_id ?>&week=<?= $prev_week ?>" class="week-nav-btn prev">
                            <i class="fa fa-chevron-left"></i>
                        </a>
                        <div class="week-title">Semaine du <?= $week_start ?> au <?= $week_end ?></div>
                        <a href="?agent_id=<?= $agent_id ?>&week=<?= $next_week ?>" class="week-nav-btn next">
                            <i class="fa fa-chevron-right"></i>
                        </a>
                    </div>
                    
                    <?php if (empty($schedules)) { ?>
                    <div class="alert alert-info text-center">
                        <h4><i class="fa fa-info-circle"></i> Agent non disponible</h4>
                        <p>Cet agent n'a pas encore configuré son calendrier de disponibilité.</p>
                        <p>Veuillez contacter l'agent directement par email ou téléphone pour organiser un rendez-vous.</p>
                    </div>
                    <?php } else { ?>
                    <div class="table-responsive">
                        <table class="table table-bordered auto-table text-center content-center <?php echo (!$_SESSION['isAgent'] && !$_SESSION['isAdmin']) ? 'client-calendar' : 'staff-calendar'; ?>">
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
                                            
                                            // Check if time slot is in the past
                                            $appointment_time = new DateTime($date_rdv . ' ' . $heure);
                                            $current_time = new DateTime();
                                            $est_dans_le_passe = $appointment_time < $current_time;

                                            // Déterminer la classe et le texte de la cellule
                                            if ($est_reserve) {
                                                // Vérifier si le RDV appartient à l'utilisateur connecté
                                                $rdv_query = mysqli_query($con, "SELECT aid FROM appointments 
                                                                               WHERE agent_id = $agent_id 
                                                                               AND rdv_date = '$date_rdv' 
                                                                               AND rdv_time = '$heure' 
                                                                               AND client_id = " . $_SESSION['uid']);

                                                if (mysqli_num_rows($rdv_query) > 0) {
                                                    // C'est le RDV de l'utilisateur connecté
                                                    $rdv_row = mysqli_fetch_assoc($rdv_query);

                                                    // Utilisez la bonne colonne 'aid' comme dans votre requête
                                                    $rdv_id = $rdv_row['aid'];

                                                    // Définir la classe et le texte
                                                    $classe = "rdv-user rdv-clickable";
                                                    $texte = "RDV";

                                                    // Ajouter des attributs data pour JavaScript
                                                    echo '<td class="' . $classe . '" data-rdv-id="' . $rdv_id . '" data-jour="' . $jour . '" data-date="' . $date_rdv . '" data-heure="' . $heure . '">' . $texte . '</td>';
                                                } else {
                                                    // C'est le RDV de quelqu'un d'autre
                                                    $classe = "indispo";
                                                    $texte = "✗";
                                                    echo '<td class="' . $classe . '" data-jour="' . $jour . '" data-date="' . $date_rdv . '" data-heure="' . $heure . '">' . $texte . '</td>';
                                                }
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

                        <?php if (!$_SESSION['isAgent'] && !$_SESSION['isAdmin']) { ?>
                        <div class="legende-item">
                            <div class="legende-color rdv-user"></div>
                            <span>Mes rendez-vous</span>
                        </div>
                        <?php } ?>
                    </div>
                    <?php } ?>

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
            <?php if(isset($_SESSION['uid'])) { 
                if(!$_SESSION['isAgent'] && !$_SESSION['isAdmin']) { ?>
                    const jour = $(this).data('jour');
                    const date = $(this).data('date');
                    const heure = $(this).data('heure');

                    // Check if the appointment time is in the past
                    const now = new Date();
                    const appointmentTime = new Date(date + 'T' + heure);

                    if (appointmentTime < now) {
                        alert('Impossible de prendre un rendez-vous dans le passé.');
                        return false;
                    }

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
            <?php }
            } else { ?>
                alert('Veuillez vous connecter pour prendre rendez-vous.');
                window.location.href = 'login.php';
            <?php } ?>
        });
        // Gestionnaire pour les RDV de l'utilisateur
        $('.rdv-clickable').click(function() {
        const rdvId = $(this).data('rdv-id');
        window.location.href = 'rdv_details.php?id=' + rdvId;
    });
    });
    </script>
</body>
</html>