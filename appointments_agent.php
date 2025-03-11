<?php
include("config.php"); // Connexion à la BDD

if (!isset($_GET['id'])) {
    echo "Agent non spécifié.";
    exit();
}

$agent_id = '204'; 

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
$plages = ["Matin", "Après-midi"];

// Vérifier si le CV de l'agent existe
$cv_directory = 'uploads/cv/';
$cv_filename = 'cv_agent_' . $agent_id . '.pdf';
$cv_path = $cv_directory . $cv_filename;
$has_cv = file_exists($cv_path);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fiche Agent - <?= htmlspecialchars(' ' . $agent['uname']) ?></title>
    <link rel="stylesheet" href="style.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: #FFFFFF; /* Fond de page blanc */
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .agent-card {
            width: 90%;
            max-width: 600px;
            background: #F8EDEB; /* Fond beige doux */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            padding: 20px;
            text-align: center;
        }

        .agent-photo {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #AFCBFF; /* Bleu pastel */
        }

        h2 {
            color: #555;
            margin-top: 10px;
        }

        .info {
            font-size: 16px;
            color: #666;
            margin: 5px 0;
        }

        .specialite {
            font-weight: bold;
            color: #AFCBFF; /* Bleu pastel */
        }

        .schedule-table {
            width: 100%;
            margin-top: 15px;
            border-collapse: collapse;
        }

        .schedule-table th, .schedule-table td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }

        .schedule-table th {
            background:rgb(61, 115, 215);
            color: white;
        }

        .absent {
            background:rgb(210, 90, 88); /* Rose poudré */
            color: white;
            font-weight: bold;
        }

        .present {
            background:rgb(60, 172, 133); /* Vert d'eau */
            color: white;
            font-weight: bold;
        }

        .buttons {
            margin-top: 20px;
        }

        .buttons button {
            width: 80%;
            max-width: 250px;
            margin: 10px auto;
            padding: 12px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
            display: block;
            font-weight: bold;
        }

        .btn-primary {
            background:rgb(67, 113, 198); /* Bleu pastel */
            color: white;
        }

        .btn-primary:hover {
            background:rgb(67, 113, 198);
        }

        .btn-secondary {
            background:rgb(60, 172, 133); /* Vert d'eau */
            color: white;
        }

        .btn-secondary:hover {
            background:rgb(60, 172, 133);
        }

        .btn-cv {
            background:rgb(210, 90, 88); /* Rose poudré */
            color: white;
        }

        .btn-cv:hover {
            background: rgb(210, 90, 88);
        }
        
        .btn-disabled {
            background: #6c757d;
            cursor: not-allowed;
        }
        
        /* Style pour la modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.7);
        }
        
        .modal-content {
            position: relative;
            margin: 5% auto;
            width: 90%;
            max-width: 800px;
            height: 80%;
            background: white;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .close-modal {
            position: absolute;
            top: 10px;
            right: 20px;
            font-size: 30px;
            color: white;
            background: rgba(0,0,0,0.5);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            z-index: 1010;
        }
        
        .pdf-container {
            width: 100%;
            height: 100%;
        }
    </style>
</head>

<body>
<div class="agent-card">
    <img src="<?= htmlspecialchars($agent['uimage']) ?>" alt="Photo de <?= htmlspecialchars(' ' . $agent['uname']) ?>" class="agent-photo">
    <h2><?= htmlspecialchars(' ' . $agent['uname']) ?></h2>
    <p class="info"><i class="fas fa-briefcase"></i> <span class="specialite"><?= htmlspecialchars($specialty_display) ?></span></p>
    <p class="info"><i class="fas fa-phone"></i> <?= htmlspecialchars($agent['uphone']) ?></p>
    <p class="info"><i class="fas fa-envelope"></i> <a href="mailto:<?= htmlspecialchars($agent['uemail']) ?>"><?= htmlspecialchars($agent['uemail']) ?></a></p>

    <h3>Disponibilité</h3>
    <table class="schedule-table">
        <tr>
            <th>Jour</th>
            <?php foreach ($plages as $plage) { echo "<th>$plage</th>"; } ?>
        </tr>
        <?php foreach ($jours as $index => $jour) { ?>
            <tr>
                <td><strong><?= $jour ?></strong></td>
                <?php 
                foreach ($plages as $i => $plage) {
                    $colonne = strtolower($jour) . "_" . ($i == 0 ? "matin" : "aprem");
                    $classe = ($dispo[$colonne] == 1) ? "present" : "absent";
                    echo "<td class='$classe'>" . ($dispo[$colonne] == 1 ? "✔ Disponible" : "❌ Indisponible") . "</td>";
                }
                ?>
            </tr>
        <?php } ?>
    </table>

    <div class="buttons">
        <button class="btn-primary" onclick="location.href='rdv.php?id=<?= $agent_id ?>'">📅 Prendre un RDV</button>
        <button class="btn-secondary" onclick="location.href='chat.php?id=<?= $agent_id ?>'">💬 Contacter l'agent</button>
        
        <?php if ($has_cv): ?>
            <button class="btn-cv" onclick="openCVModal()">📄 Voir son CV</button>
        <?php else: ?>
            <button class="btn-cv btn-disabled" disabled title="CV non disponible">📄 CV non disponible</button>
        <?php endif; ?>
    </div>
</div>

<!-- Modal pour afficher le CV -->
<?php if ($has_cv): ?>
<div id="cvModal" class="modal">
    <div class="modal-content">
        <span class="close-modal" onclick="closeCVModal()">&times;</span>
        <iframe class="pdf-container" src="cv.php?id=<?= $agent_id ?>" frameborder="0"></iframe>
    </div>
</div>

<script>
    // Fonctions pour ouvrir et fermer la modal
    function openCVModal() {
        document.getElementById('cvModal').style.display = 'block';
    }
    
    function closeCVModal() {
        document.getElementById('cvModal').style.display = 'none';
    }
    
    // Fermer la modal si on clique en dehors
    window.onclick = function(event) {
        const modal = document.getElementById('cvModal');
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }
</script>
<?php endif; ?>

</body>
</html>

