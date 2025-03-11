<?php 
include("config.php"); // Connexion à la BDD

if (!isset($_GET['id'])) {
    echo "Agent non spécifié.";
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
$specialty_query = mysqli_query($con, "SELECT DISTINCT propertyType FROM property WHERE agentid = $agent_id");
$specialties = [];
while ($row = mysqli_fetch_assoc($specialty_query)) {
    $specialties[] = $row['propertyType'];
}
$specialty_display = !empty($specialties) ? implode(", ", $specialties) : "Non spécifié";

// Récupérer les disponibilités de l'agent
$dispo_query = mysqli_query($con, "SELECT jour_semaine, heure_debut FROM agent_disponibilite2 WHERE agent_id = $agent_id");
$disponibilites = [];
while ($row = mysqli_fetch_assoc($dispo_query)) {
    $disponibilites[$row['jour_semaine']][$row['heure_debut']] = true;
}

// Récupérer les rendez-vous existants pour la semaine en cours
$date_debut = date('Y-m-d', strtotime('monday this week'));
$date_fin = date('Y-m-d', strtotime('saturday this week'));
$rdv_query = mysqli_query($con, "SELECT date_rdv, heure_debut, heure_fin FROM rendez_vous 
                                WHERE agent_id = $agent_id 
                                AND date_rdv BETWEEN '$date_debut' AND '$date_fin'
                                AND statut != 'annulé'");
$rendez_vous = [];
while ($row = mysqli_fetch_assoc($rdv_query)) {
    $jour = strtolower(date('l', strtotime($row['date_rdv'])));
    $jour = str_replace(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'], 
                        ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi'], $jour);
    $rendez_vous[$jour][$row['heure_debut']] = true;
}

$jours = ["lundi", "mardi", "mercredi", "jeudi", "vendredi", "samedi"];
$jours_fr = ["Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi"];

// Générer les horaires par intervalles de 30 minutes
$horaires = [];
for ($h = 9; $h <= 17; $h++) {
    $horaires[] = sprintf("%02d:00:00", $h);
    $horaires[] = sprintf("%02d:30:00", $h);
}
$horaires[] = "18:00:00";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Disponibilité - <?= htmlspecialchars($agent['uname']) ?></title>
    <style>
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { padding: 8px; text-align: center; border: 1px solid #ddd; }
        th { background-color: #3D73D7; color: white; }
        .dispo { background-color: #3CAC85; color: white; cursor: pointer; }
        .indispo { background-color: #D25A58; color: white; }
        .rdv { background-color: #F5A623; color: white; }
        .legende { display: flex; gap: 20px; margin-bottom: 20px; }
        .legende-item { display: flex; align-items: center; gap: 5px; }
        .legende-color { width: 20px; height: 20px; }
    </style>
</head>
<body>
<h2>Disponibilité de <?= htmlspecialchars($agent['uname']) ?></h2>
<p>Spécialités: <?= htmlspecialchars($specialty_display) ?></p>

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

<table>
    <tr>
        <th>Heure</th>
        <?php foreach ($jours_fr as $jour) { echo "<th>$jour</th>"; } ?>
    </tr>
    <?php foreach ($horaires as $heure) { 
        // Formater l'heure pour l'affichage
        $heure_affichage = substr($heure, 0, 5);
    ?>
        <tr>
            <td><?= $heure_affichage ?></td>
            <?php foreach ($jours as $index => $jour) { 
                $est_disponible = isset($disponibilites[$jour][$heure]);
                $est_reserve = isset($rendez_vous[$jour][$heure]);
                
                if ($est_reserve) {
                    $classe = "rdv";
                    $texte = "RDV";
                } elseif ($est_disponible) {
                    $classe = "dispo";
                    $texte = "✔";
                } else {
                    $classe = "indispo";
                    $texte = "❌";
                }
                
                echo "<td class='$classe' data-jour='$jour' data-heure='$heure'>$texte</td>";
            } ?>
        </tr>
    <?php } ?>
</table>

<script>
// Script pour permettre la prise de rendez-vous en cliquant sur une cellule disponible
document.querySelectorAll('.dispo').forEach(cell => {
    cell.addEventListener('click', function() {
        const jour = this.dataset.jour;
        const heure = this.dataset.heure;
        const jour_fr = {
            'lundi': 'Lundi',
            'mardi': 'Mardi',
            'mercredi': 'Mercredi',
            'jeudi': 'Jeudi',
            'vendredi': 'Vendredi',
            'samedi': 'Samedi'
        }[jour];
        
        if (confirm(`Voulez-vous prendre rendez-vous le ${jour_fr} à ${heure.substr(0, 5)} ?`)) {
            // Rediriger vers la page de prise de rendez-vous
            window.location.href = `prendre_rdv.php?agent_id=<?= $agent_id ?>&jour=${jour}&heure=${heure}`;
        }
    });
});
</script>
</body>
</html>

