<?php 
ini_set('session.cache_limiter','public');
session_cache_limiter(false);
session_start();
include("config.php");

if(!isset($_SESSION['uid'])) {
    header("location:login.php");
}

if (!isset($_GET['id'])) {
    header("location:appointments.php");
    exit();
}

$agent_id = intval($_GET['id']); 
$query = mysqli_query($con, "SELECT * FROM user WHERE uid = $agent_id AND utype = 'agent'");
$agent = mysqli_fetch_assoc($query);

if (!$agent) {
    echo "Agent introuvable.";
    exit();
}

// Récupérer les spécialités de l'agent
$specialty = $agent['specialty'] ?? "Non spécifié";

// Récupérer les plages horaires de l'agent
$schedule_query = mysqli_query($con, "SELECT * FROM agent_schedules WHERE agent_id = $agent_id ORDER BY FIELD(day_of_week, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday')");
$schedules = [];
while ($row = mysqli_fetch_assoc($schedule_query)) {
    $schedules[$row['day_of_week']] = $row;
}

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

$jours = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
$jours_fr = ["Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi"];

// Générer les horaires par intervalles de 30 minutes
$horaires = [];
for ($h = 9; $h <= 17; $h++) {
    $horaires[] = sprintf("%02d:00:00", $h);
    $horaires[] = sprintf("%02d:30:00", $h);
}
$horaires[] = "18:00:00";

// Dates de la semaine en cours
$dates = [];
for ($i = 0; $i < 6; $i++) {
    $dates[] = date('Y-m-d', strtotime("monday this week +$i days"));
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Disponibilité - <?= htmlspecialchars($agent['uname']) ?></title>
    
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
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 20px;
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
            <div class="row">
                <div class="col-lg-12">
                    <div class="agent-info d-flex align-items-center">
                        <img src="images/profile_pic/<?= $agent['uimage'] ?>" class="agent-photo" alt="Photo de l'agent">
                        <div>
                            <h3 class="text-secondary"><?= htmlspecialchars($agent['uname'] . ' ' . $agent['ufirstname']) ?></h3>
                            <p class="mb-1"><strong>Spécialité:</strong> <?= htmlspecialchars($specialty) ?></p>
                            <p class="mb-1"><strong>Email:</strong> <?= htmlspecialchars($agent['uemail']) ?></p>
                            <p class="mb-0"><strong>Téléphone:</strong> <?= htmlspecialchars($agent['uphone']) ?></p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row mb-4">
                <div class="col-lg-12">
                    <h4 class="text-secondary double-down-line text-center">Calendrier de Disponibilité</h4>
                    <p class="text-center mb-4">Semaine du <?= date('d/m/Y', strtotime('monday this week')) ?> au <?= date('d/m/Y', strtotime('saturday this week')) ?></p>
                </div>
            </div>
            
            <div class="row">
                <div class="col-lg-12">
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
                </div>
            </div>
            
            <?php if(isset($_SESSION['uid'])) { ?>
            <div class="row mt-4">
                <div class="col-lg-12 text-center">
                    <a href="agent_profile.php?id=<?= $agent_id ?>" class="btn btn-primary">Voir le profil complet</a>
                </div>
            </div>
            <?php } else { ?>
            <div class="row mt-4">
                <div class="col-lg-12 text-center">
                    <div class="alert alert-info">
                        Connectez-vous pour prendre rendez-vous avec cet agent.
                        <a href="login.php" class="btn btn-primary btn-sm ml-3">Se connecter</a>
                    </div>
                </div>
            </div>
            <?php } ?>
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
                    window.location.href = `prendre_rdv.php?agent_id=<?= $agent_id ?>&date=${date}&heure=${heure}`;
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