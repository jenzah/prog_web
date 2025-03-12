<?php
session_start();
include("config.php");

// Vérifier si l'utilisateur est un admin
if (!isset($_SESSION['uid']) || $_SESSION['utype'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Récupérer la liste des agents
$agents = mysqli_query($con, "SELECT uid, uname, ufirstname, uimage, uemail, uphone, specialty FROM user WHERE utype = 'agent'");

// Récupérer les disponibilités d'un agent (si un agent est sélectionné)
$agent_id = isset($_GET['agent_id']) ? intval($_GET['agent_id']) : null;
$disponibilites_existantes = [];

if ($agent_id) {
    $result = mysqli_query($con, "SELECT * FROM agent_schedules WHERE agent_id = $agent_id");
    while ($row = mysqli_fetch_assoc($result)) {
        $disponibilites_existantes[$row['day_of_week']] = $row;
    }
}

$jours = ["Monday" => "Lundi", "Tuesday" => "Mardi", "Wednesday" => "Mercredi", "Thursday" => "Jeudi", "Friday" => "Vendredi", "Saturday" => "Samedi"];
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

<!-- CSS Bootstrap & Style -->
<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="css/style.css">

<style>
    .agent-list {
        max-height: 500px;
        overflow-y: auto;
        border-right: 1px solid #ddd;
        padding-right: 10px;
    }
    .agent-item {
        display: flex;
        align-items: center;
        cursor: pointer;
        padding: 10px;
        border-radius: 8px;
        transition: background 0.3s;
    }
    .agent-item:hover {
        background: #f8f9fa;
    }
    .agent-photo {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 10px;
    }
    .profile-card {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        text-align: center;
        margin-bottom: 20px;
    }
    .profile-card img {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        object-fit: cover;
    }
    .schedule-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    .schedule-table th, .schedule-table td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: center;
    }
    .schedule-table th {
        background-color: #3D73D7;
        color: white;
    }
    .dispo { background: #3CAC85; color: white; font-weight: bold; }
    .indispo { background: #D25A58; color: white; font-weight: bold; }
    .btn-update {
    background-color: var(--theme-dark-primary-color); /* Utilisation de la couleur Bootstrap */
    color: white;
    padding: 10px 15px;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    width: 100%;
    margin-top: 15px;
    transition: background 0.3s ease-in-out; /* Animation fluide */
}

.btn-update:hover {
    background-color:var(--theme-dark-primary-color); !important; /* Bleu plus foncé au survol */
    color: white;
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
                    <h2 class="page-name text-white text-uppercase"><b>Agenda Agents</b></h2>
                </div>
                <div class="col-md-6">
                    <nav aria-label="breadcrumb" class="float-md-right">
                        <ol class="breadcrumb bg-transparent m-0 p-0">
                            <li class="breadcrumb-item text-white"><a href="home.php">Accueil</a></li>
                            <li class="breadcrumb-item active">Agenda Agents</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="full-row">
            <div class="container">

    <div class="row mb-5">
                    <div class="col-lg-12">
                        <h2 class="text-secondary text-center double-down-line">Disponibilités agents</h2>
                    </div>
                </div>

<div class="container">
    <div class="row">
        <!-- Liste des agents -->
        <div class="col-md-3 agent-list">
            <h4>Agents Disponibles</h4>
            <?php while ($agent = mysqli_fetch_assoc($agents)) { ?>
                <div class="agent-item" onclick="selectAgent(<?= $agent['uid'] ?>)">
                    <img src="images/profile_pic/<?= $agent['uimage'] ?>" class="agent-photo" alt="<?= htmlspecialchars($agent['uname']) ?>">
                    <span><?= htmlspecialchars($agent['ufirstname'] . " " . strtoupper($agent['uname'])) ?></span>
                </div>
            <?php } ?>
        </div>

        <!-- Profil de l'agent + Disponibilités -->
<div class="col-md-9">
    <?php if ($agent_id) { ?>
        <div class="profile-card">
            <?php
            $agent_details = null;
            if ($agent_id) {
                $agent_query = mysqli_query($con, "SELECT uname, ufirstname,specialty,uimage FROM user WHERE uid = $agent_id AND utype = 'agent'");
                $agent_details = mysqli_fetch_assoc($agent_query);
            }
            ?>

            <?php if (!empty($agent_details['uimage'])) : ?>
                <img src="images/profile_pic/<?= htmlspecialchars($agent_details['uimage']) ?>" alt="Photo de l'agent">
            <?php endif; ?>
          

            <h4><?= htmlspecialchars($agent_details['ufirstname'] ?? '') . " " . htmlspecialchars(strtoupper($agent_details['uname'] ?? '')) ?></h4>
            <p class="text-muted">Agent Immobilier - <?= htmlspecialchars($agent_details['specialty'] ?? 'Non spécifiée') ?></p>
        </div>
   



                <form method="POST" action="admin_update_disponibilite.php">
                    <input type="hidden" name="agent_id" value="<?= $agent_id ?>">

                    <table class="table schedule-table ">
                        <thead>
                            <tr>
                                <th class="bg-primary">Jour</th>
                                <th class="bg-primary">Disponible</th>
                                <th class="bg-primary">Heure de début</th>
                                <th class="bg-primary">Heure de fin</th>
                            </tr>
                        </thead>
                        <tbody>
    <?php foreach ($jours as $key => $jour) {
        // Si le jour n'existe pas en BDD, on met par défaut 09:00 - 18:00 et activé
        $dispo = $disponibilites_existantes[$key] ?? ['is_working_day' => 1, 'workday_start' => '09:00:00', 'workday_end' => '17:00:00'];
    ?>
        <tr>
            <td><strong><?= $jour ?></strong></td>
            <td>
                <input type="checkbox" name="disponibilites[<?= $key ?>][is_working_day]" value="1" 
                    <?= $dispo['is_working_day'] ? 'checked' : '' ?>
                    onclick="toggleRow(this, '<?= $key ?>')">
            </td>
            <td>
                <input type="time" class="form-control" name="disponibilites[<?= $key ?>][start]" 
                    value="<?= $dispo['workday_start'] ?>" id="<?= $key ?>_start" 
                    <?= !$dispo['is_working_day'] ? 'disabled' : '' ?>>
            </td>
            <td>
                <input type="time" class="form-control" name="disponibilites[<?= $key ?>][end]" 
                    value="<?= $dispo['workday_end'] ?>" id="<?= $key ?>_end" 
                    <?= !$dispo['is_working_day'] ? 'disabled' : '' ?>>
            </td>
        </tr>
    <?php } ?>
</tbody>

                    </table>

                    <button type="submit" class="btn btn-update bg-primary">Mettre à jour</button>
                </form>
            <?php } ?>
        </div>
    </div>
    </div>
    </div>
</div>
    </div>

<script>
    function selectAgent(agentId) {
        window.location.href = "admin_disponibilite.php?agent_id=" + agentId;
    }
</script>
<script>
    function toggleRow(checkbox, day) {
        var startInput = document.getElementById(day + "_start");
        var endInput = document.getElementById(day + "_end");

        if (checkbox.checked) {
            // Si l'utilisateur coche la case, activer les champs et mettre les horaires par défaut
            startInput.disabled = false;
            endInput.disabled = false;

            if (startInput.value === "09:00" || startInput.value === "") {
                startInput.value = "09:00";
            }
            if (endInput.value === "17:00" || endInput.value === "") {
                endInput.value = "17:00";
            }
        } else {
            // Si l'utilisateur décoche, désactiver les champs et réinitialiser à 00:00
            startInput.disabled = true;
            endInput.disabled = true;
            startInput.value = "09:00";
            endInput.value = "17:00";
        }
    }
</script>

<?php include("include/footer.php"); ?>
    
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>