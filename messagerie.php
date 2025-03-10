<?php
session_start();
include("config.php");

if (!isset($_SESSION['uid']) || (!isset($_GET['agent_id']) && !isset($_GET['room_id']))) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['uid']; // ID de l'utilisateur connecté

// Si on vient depuis "Mes conversations"
if (isset($_GET['room_id'])) {
    $room_id = intval($_GET['room_id']);
} else {
    // Création ou récupération d'une salle de chat avec l'agent
    $agent_id = intval($_GET['agent_id']);
    $query = "SELECT room_id FROM chat_participants WHERE user_id IN ($user_id, $agent_id) 
              GROUP BY room_id HAVING COUNT(DISTINCT user_id) = 2 LIMIT 1";
    $result = mysqli_query($con, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $room_id = $row['room_id'];
    } else {
        // Créer une nouvelle salle de chat
        mysqli_query($con, "INSERT INTO chat_rooms () VALUES ()");
        $room_id = mysqli_insert_id($con);
        mysqli_query($con, "INSERT INTO chat_participants (room_id, user_id) VALUES ($room_id, $user_id)");
        mysqli_query($con, "INSERT INTO chat_participants (room_id, user_id) VALUES ($room_id, $agent_id)");
    }
}

// Récupérer les messages
$messages = mysqli_query($con, "SELECT * FROM chat_messages WHERE room_id = $room_id ORDER BY sent_at ASC");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Messagerie</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>

<div class="container mt-4">
    <h4>Messagerie</h4>
    <a href="conversations.php" class="btn btn-secondary mb-3">Retour aux conversations</a>

    <div id="chatbox" class="border p-3 mb-3" style="height: 300px; overflow-y: auto;">
        <?php
        // Récupération des messages avec le nom de l'utilisateur
        $messages = mysqli_query($con, "
            SELECT chat_messages.*, user.uname, user.utype 
            FROM chat_messages 
            JOIN user ON chat_messages.user_id = user.uid 
            WHERE chat_messages.room_id = $room_id 
            ORDER BY chat_messages.sent_at ASC
        ");

        while ($msg = mysqli_fetch_assoc($messages)) {
            $nomExpediteur = ($msg['user_id'] == $user_id) ? "Vous" : htmlspecialchars($msg['uname']);
            echo "<p><strong>" . $nomExpediteur . ":</strong> " . htmlspecialchars($msg['message']) . "</p>";
        }
        ?>
    </div>


    <form id="chatForm" class="d-flex">
        <input type="hidden" name="room_id" value="<?php echo $room_id; ?>">
        <input type="text" name="message" id="message" class="form-control mr-2" placeholder="Tapez votre message..." required>
        <button type="submit" class="btn btn-primary">Envoyer</button>
    </form>
</div>

<script>
    $(document).ready(function() {
        $("#chatForm").submit(function(event) {
            event.preventDefault();
            var message = $("#message").val();
            var room_id = $("input[name=room_id]").val();

            if (message.trim() !== "") {
                $.post("send_message.php", { room_id: room_id, message: message }, function(data) {
                    $("#chatbox").append("<p><strong>Vous:</strong> " + message + " <span class='text-muted'>(Maintenant)</span></p>");
                    $("#message").val("");
                });
            }
        });

        setInterval(function() {
            var room_id = $("input[name=room_id]").val();
            $.post("load_messages.php", { room_id: room_id }, function(data) {
                $("#chatbox").html(data);
            });
        }, 2000);
    });
</script>

</body>
</html>