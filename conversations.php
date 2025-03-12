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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mes Conversations</title>

    <!-- Meta Tags -->
    <link rel="shortcut icon" href="images/favicon.ico">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">

    <!-- Styles Bootstrap & CSS -->
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
        .conversation-container {
            max-width: 800px;
            margin: auto;
        }
        .conversation-card {
            border-radius: 8px;
            padding: 15px;
            background: #fff;
            display: flex;
            align-items: center;
            box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }
        .conversation-card:hover {
            transform: scale(1.02);
        }
        .conversation-card img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 15px;
        }
        .conversation-card strong {
            font-size: 18px;
        }
        .conversation-list a {
            text-decoration: none;
            color: #333;
        }
        .conversation-list a:hover {
            text-decoration: none;
        }
    </style>
</head>
<body>

<!-- Inclure le Header -->
<?php include("include/header.php"); ?>

<!-- Bannière -->
<div class="banner-full-row page-banner" style="background-image:url('images/breadcrumb.jpg');">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h2 class="page-name text-white text-uppercase"><b>Mes Conversations</b></h2>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb" class="float-md-right">
                    <ol class="breadcrumb bg-transparent m-0 p-0">
                        <li class="breadcrumb-item text-white"><a href="index.php">Accueil</a></li>
                        <li class="breadcrumb-item active">Mes Conversations</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<!-- Section Conversations -->
<div class="full-row">
    <div class="container conversation-container">
        <div class="row mb-5">
            <div class="col-lg-12">
                <h2 class="text-secondary text-center double-down-line">Mes Conversations</h2>
            </div>
        </div>

        <div class="conversation-list">
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <a href="messagerie.php?room_id=<?php echo $row['room_id']; ?>">
                    <div class="conversation-card mb-3">
                        <img src="images/profile_pic/<?php echo htmlspecialchars($row['uimage']); ?>" alt="Photo">
                        <strong><?php echo htmlspecialchars($row['ufirstname']) . " " . htmlspecialchars($row['uname']); ?></strong>
                    </div>
                </a>
            <?php } ?>
        </div>
    </div>
</div>

<!-- Inclure le Footer -->
<?php include("include/footer.php"); ?>

<!-- Scripts -->
<script src="js/jquery.min.js"></script> 
<script src="js/bootstrap.min.js"></script> 

</body>
</html>
