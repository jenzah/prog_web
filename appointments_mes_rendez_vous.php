<?php
include("config.php"); // Connexion à la BDD
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['uid'])) {
    header("Location: login.php?redirect=" . urlencode($_SERVER['REQUEST_URI']));
    exit();
}

$user_id = $_SESSION['uid'];
$user_type = $_SESSION['utype'] ?? 'client';

// Traitement des actions
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $rdv_id = intval($_GET['id']);
    
    if ($action === 'annuler') {
        mysqli_query($con, "UPDATE rendez_vous SET statut = 'annulé' WHERE id = $rdv_id AND (client_id = $user_id OR agent_id = $user_id)");
    }
}

// Récupération des rendez-vous
if ($user_type === 'agent') {
    // Pour un agent, afficher tous les rendez-vous où il est l'agent
    $query = mysqli_query($con, "
        SELECT r.*, 
               c.uname as client_name, 
               DATE_FORMAT(r.date_rdv, '%d/%m/%Y') as date_formatee,
               DATE_FORMAT(r.heure_debut, '%H:%i') as heure_debut_formatee,
               DATE_FORMAT(r.heure_fin, '%H:%i') as heure_fin_formatee
        FROM rendez_vous r
        JOIN user c ON r.client_id = c.uid
        WHERE r.agent_id = $user_id
        ORDER BY r.date_rdv DESC, r.heure_debut ASC
    ");
} else {
    // Pour un client, afficher tous ses rendez-vous
    $query = mysqli_query($con, "
        SELECT r.*, 
               a.uname as agent_name, 
               DATE_FORMAT(r.date_rdv, '%d/%m/%Y') as date_formatee,
               DATE_FORMAT(r.heure_debut, '%H:%i') as heure_debut_formatee,
               DATE_FORMAT(r.heure_fin, '%H:%i') as heure_fin_formatee
        FROM rendez_vous r
        JOIN user a ON r.agent_id = a.uid
        WHERE r.client_id = $user_id
        ORDER BY r.date_rdv DESC, r.heure_debut ASC
    ");
}

$rendez_vous = [];
while ($row = mysqli_fetch_assoc($query)) {
    $rendez_vous[] = $row;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes rendez-vous</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 1000px; margin: 0 auto; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #3D73D7; color: white; }
        tr:hover { background-color: #f5f5f5; }
        .status { padding: 5px 10px; border-radius: 4px; display: inline-block; }
        .confirmed { background-color: #3CAC85; color: white; }
        .pending { background-color: #F5A623; color: white; }
        .cancelled { background-color: #D25A58; color: white; }
        .actions { display: flex; gap: 10px; }
        .btn { padding: 5px 10px; border-radius: 4px; text-decoration: none; color: white; }
        .btn-cancel { background-color: #D25A58; }
        .btn-view { background-color: #3D73D7; }
        .empty { text-align: center; padding: 20px; color: #666; }
    </style>
</head>
<body>
    <h2>Mes rendez-vous</h2>
    
    <?php if (empty($rendez_vous)): ?>
        <p class="empty">Vous n'avez aucun rendez-vous pour le moment.</p>
    <?php else: ?>
        <table>
            <tr>
                <th>Date</th>
                <th>Heure</th>
                <?php if ($user_type === 'agent'): ?>
                    <th>Client</th>
                <?php else: ?>
                    <th>Agent</th>
                <?php endif; ?>
                <th>Motif</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($rendez_vous as $rdv): ?>
                <tr>
                    <td><?= htmlspecialchars($rdv['date_formatee']) ?></td>
                    <td><?= htmlspecialchars($rdv['heure_debut_formatee']) ?> - <?= htmlspecialchars($rdv['heure_fin_formatee']) ?></td>
                    <?php if ($user_type === 'agent'): ?>
                        <td><?= htmlspecialchars($rdv['client_name']) ?></td>
                    <?php else: ?>
                        <td><?= htmlspecialchars($rdv['agent_name']) ?></td>
                    <?php endif; ?>
                    <td><?= htmlspecialchars($rdv['motif']) ?></td>
                    <td>
                        <?php 
                        $status_class = '';
                        switch ($rdv['statut']) {
                            case 'confirmé':
                                $status_class = 'confirmed';
                                break;
                            case 'en_attente':
                                $status_class = 'pending';
                                break;
                            case 'annulé':
                                $status_class = 'cancelled';
                                break;
                        }
                        ?>
                        <span class="status <?= $status_class ?>"><?= htmlspecialchars(ucfirst($rdv['statut'])) ?></span>
                    </td>
                    <td class="actions">
                        <?php if ($rdv['statut'] !== 'annulé'): ?>
                            <a href="?action=annuler&id=<?= $rdv['id'] ?>" class="btn btn-cancel" onclick="return confirm('Êtes-vous sûr de vouloir annuler ce rendez-vous?')">Annuler</a>
                        <?php endif; ?>
                        <a href="detail_rdv.php?id=<?= $rdv['id'] ?>" class="btn btn-view">Détails</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
    
    <?php if ($user_type === 'client'): ?>
        <p style="margin-top: 20px;">
            <a href="recherche_agent.php" style="display: inline-block; padding: 10px 15px; background-color: #3D73D7; color: white; text-decoration: none; border-radius: 4px;">Prendre un nouveau rendez-vous</a>
        </p>
    <?php endif; ?>
</body>
</html>

