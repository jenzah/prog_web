<?php
session_start();
include("config.php");

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['uid'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['uid']; // ID de l'utilisateur connecté

// Récupérer les conversations où l'utilisateur est un participant
$query = "
    SELECT DISTINCT cr.room_id, u.uid, u.uname, u.ufirstname, u.uimage 
    FROM chat_rooms cr
    JOIN chat_participants cp ON cr.room_id = cp.room_id
    JOIN chat_participants cp2 ON cr.room_id = cp2.room_id
    JOIN user u ON u.uid = cp2.user_id
    WHERE cp.user_id = $user_id AND cp2.user_id != $user_id
";

$result = mysqli_query($con, $query);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Conversations</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>

<div class="container mt-4">
    <h3>Mes Conversations</h3>
    <div class="list-group">
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <a href="messagerie.php?room_id=<?php echo $row['room_id']; ?>" class="list-group-item list-group-item-action d-flex align-items-center">
                <img src="images/profile_pic/<?php echo htmlspecialchars($row['uimage']); ?>" class="rounded-circle mr-3" width="40" height="40" alt="Photo">
                <div>
                    <strong><?php echo htmlspecialchars($row['ufirstname']) . " " . htmlspecialchars($row['uname']); ?></strong>
                </div>
            </a>
        <?php } ?>
    </div>
</div>

</body>
</html>