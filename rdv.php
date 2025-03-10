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

$specialty_query = mysqli_query($con, "SELECT DISTINCT propertyType FROM property WHERE agentid = $agent_id");
$specialties = [];
while ($row = mysqli_fetch_assoc($specialty_query)) {
    $specialties[] = $row['propertyType'];
}
$specialty_display = !empty($specialties) ? implode(", ", $specialties) : "Non spécifié";

$dispo_query = mysqli_query($con, "SELECT * FROM agent_disponibilite WHERE agent_id = $agent_id");
$dispo = mysqli_fetch_assoc($dispo_query);

$jours = ["Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi"];
$horaires = [];
for ($h = 9; $h <= 17; $h++) {
    $horaires[] = sprintf("%02d:00", $h);
    $horaires[] = sprintf("%02d:30", $h);
}
$horaires[] = "18:00";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Disponibilité - <?= htmlspecialchars($agent['uname']) ?></title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 8px; text-align: center; border: 1px solid #ddd; }
        th { background-color: #3D73D7; color: white; }
        .dispo { background-color: #3CAC85; color: white; cursor: pointer; }
        .indispo { background-color: #D25A58; color: white; }
    </style>
</head>
<body>
<h2>Disponibilité de <?= htmlspecialchars($agent['uname']) ?></h2>
<table>
    <tr>
        <th>Heure</th>
        <?php foreach ($jours as $jour) { echo "<th>$jour</th>"; } ?>
    </tr>
    <?php foreach ($horaires as $heure) { ?>
        <tr>
            <td><?= $heure ?></td>
            <?php foreach ($jours as $jour) { 
                $colonne = strtolower($jour) . "_" . str_replace(':', '', $heure);
                $classe = isset($dispo[$colonne]) && $dispo[$colonne] == 1 ? "dispo" : "indispo";
                echo "<td class='$classe'>" . ($classe == "dispo" ? "✔" : "❌") . "</td>";
            } ?>
        </tr>
    <?php } ?>
</table>
</body>
</html>