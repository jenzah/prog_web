<?php
session_start();
include("config.php");

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['uid'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['uid']; // ID de l'utilisateur connecté

// Vérifier si une `room_id` est déjà fournie (cas où l'utilisateur clique sur une conversation existante)
if (isset($_GET['room_id'])) {
    $room_id = intval($_GET['room_id']);
} 
// Sinon, on vient depuis "Messagerie" d'une propriété (avec `agent_id`)
elseif (isset($_GET['agent_id'])) {
    $agent_id = intval($_GET['agent_id']);

    // Vérifier si une conversation existe déjà entre cet utilisateur et cet agent
    $query = "
        SELECT cr.room_id FROM chat_rooms cr
        JOIN chat_participants cp1 ON cr.room_id = cp1.room_id
        JOIN chat_participants cp2 ON cr.room_id = cp2.room_id
        WHERE (cp1.user_id = $user_id AND cp2.user_id = $agent_id) 
        OR (cp1.user_id = $agent_id AND cp2.user_id = $user_id)
        LIMIT 1";
    
    $result = mysqli_query($con, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        // Conversation existante trouvée
        $row = mysqli_fetch_assoc($result);
        $room_id = $row['room_id'];
    } else {
        // Aucune conversation existante, donc on en crée une nouvelle
        mysqli_query($con, "INSERT INTO chat_rooms () VALUES ()");
        $room_id = mysqli_insert_id($con);

        // Ajouter les participants (l'agent et l'utilisateur)
        mysqli_query($con, "INSERT INTO chat_participants (room_id, user_id) VALUES ($room_id, $user_id)");
        mysqli_query($con, "INSERT INTO chat_participants (room_id, user_id) VALUES ($room_id, $agent_id)");
    }

    // Rediriger vers la bonne URL avec `room_id`
    header("Location: messagerie.php?room_id=$room_id");
    exit();
} 
// Aucun `room_id` ou `agent_id` trouvé → Erreur
else {
    die("<h3 style='color:red;'>Erreur : impossible d'ouvrir la messagerie.</h3>");
}

// Récupérer les messages de la salle de chat
$messages = mysqli_query($con, "
    SELECT chat_messages.*, user.uname 
    FROM chat_messages 
    JOIN user ON chat_messages.user_id = user.uid 
    WHERE chat_messages.room_id = $room_id 
    ORDER BY chat_messages.sent_at ASC
");
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>
<?php include("include/header.php"); ?>
    
    <!-- Page Title -->
    <div class="banner-full-row page-banner" style="background-image:url('images/breadcrumb.jpg');">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h2 class="page-name text-white text-uppercase"><b>Messagerie</b></h2>
                </div>
                <div class="col-md-6">
                    <nav aria-label="breadcrumb" class="float-md-right">
                        <ol class="breadcrumb bg-transparent m-0 p-0">
                            <li class="breadcrumb-item text-white"><a href="home.php">Accueil</a></li>
                            <li class="breadcrumb-item text-white"><a href="agents.php">Mon compte</a></li>
                            <li class="breadcrumb-item active">Messagerie</li>
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
                        <h2 class="text-secondary text-center double-down-line">Messagerie</h2>
                    </div>
                </div>

<div class="container mt-4">
    <h4>Messagerie</h4>
    <a href="conversations.php" class="btn btn-secondary mb-3">Retour aux conversations</a>

    <div id="chatbox" class="border p-3 mb-3" style="height: 300px; overflow-y: auto;">
        <?php while ($msg = mysqli_fetch_assoc($messages)) { ?>
            <p><strong><?php echo ($msg['user_id'] == $user_id) ? "Vous" : htmlspecialchars($msg['uname']); ?>:</strong> 
                <?php echo htmlspecialchars($msg['message']); ?>
            </p>
        <?php } ?>
    </div>

    <form id="chatForm" class="d-flex">
        <input type="hidden" name="room_id" value="<?php echo $room_id; ?>">
        <input type="text" name="message" id="message" class="form-control mr-2" placeholder="Tapez votre message..." required>
        <button type="submit" class="btn btn-primary">Envoyer</button>
    </form>
</div>
</div>
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
<?php include("include/footer.php"); ?>
    
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>