<?php 
ini_set('session.cache_limiter','public');
session_cache_limiter(false);
session_start();
include("config.php");

// Vérifier si l'utilisateur est connecté
if(!isset($_SESSION['uid'])) {
    header("location:login.php");
    exit();
}

// Vérifier si les paramètres nécessaires sont présents
if (!isset($_GET['agent_id']) || !isset($_GET['date']) || !isset($_GET['heure'])) {
    header("location:agents.php");
    exit();
}

// Récupérer et sécuriser les données de l'URL
$agent_id = intval($_GET['agent_id']);
$date_rdv = mysqli_real_escape_string($con, $_GET['date']);
$heure_rdv = mysqli_real_escape_string($con, $_GET['heure']);

// Formater l'heure pour l'affichage
$heure_affichage = substr($heure_rdv, 0, 5);

// Formater la date pour l'affichage
$timestamp = strtotime($date_rdv);
$jour_semaine = date('l', $timestamp);
$jour_semaine_fr = [
    'Monday' => 'Lundi',
    'Tuesday' => 'Mardi',
    'Wednesday' => 'Mercredi',
    'Thursday' => 'Jeudi',
    'Friday' => 'Vendredi',
    'Saturday' => 'Samedi',
    'Sunday' => 'Dimanche'
][$jour_semaine];
$date_affichage = $jour_semaine_fr . ' ' . date('d/m/Y', $timestamp);

// Récupérer les informations de l'agent
$query = mysqli_query($con, "SELECT * FROM user WHERE uid = $agent_id AND utype = 'agent'");
$agent = mysqli_fetch_assoc($query);

// Récupérer les propriétés gérées par l'agent
$properties_query = mysqli_query($con, "SELECT * FROM property WHERE agentid = $agent_id");
$properties = [];
while ($property = mysqli_fetch_assoc($properties_query)) {
    $properties[] = $property;
}

// Traitement du formulaire
$message = '';
if(isset($_POST['confirm'])) {
    $user_id = $_SESSION['uid'];
    $motivation = mysqli_real_escape_string($con, $_POST['motivation']);
    $property_id = isset($_POST['property_id']) && !empty($_POST['property_id']) ? intval($_POST['property_id']) : NULL;
    $comment = mysqli_real_escape_string($con, $_POST['comment']);
    
    // Vérifier que le créneau est toujours disponible
    $check_query = mysqli_query($con, "SELECT * FROM appointments 
                                      WHERE agent_id = $agent_id 
                                      AND rdv_date = '$date_rdv' 
                                      AND rdv_time = '$heure_rdv'
                                      AND rdv_status != 'annulé'");
    
    // Define prices based on agent specialty for standard consultations/visits
    $specialtyPrices = [
        'résidentiel' => 80.00,    // Standard residential property visit
        'terrain' => 60.00,        // Land plot consultation
        'appartement' => 70.00,    // Apartment viewing
        'commercial' => 120.00     // Commercial property consultation (higher due to complexity)
    ];

    // Get the agent's specialty from the database
    $agentSpecialty = $agent['specialty'] ?? 'résidentiel';

    // Set the appointment price based on specialty
    $appointmentPrice = $specialtyPrices[$agentSpecialty] ?? 75.00; // Default price if specialty not found

    // Additional price adjustment based on visit motivation
    if ($motivation == "Visite d'un bien") {
        // Standard price - no adjustment
    } elseif ($motivation == "Estimation immobilière") {
        $appointmentPrice += 30.00; // Add premium for property valuation
    } elseif ($motivation == "Consultation générale") {
        $appointmentPrice -= 20.00; // Discount for general consultation
    }

    if(mysqli_num_rows($check_query) > 0) {
        $message = '<div class="alert alert-danger">Ce créneau n\'est plus disponible. Veuillez en choisir un autre.</div>';
    } else {
        // Insérer le rendez-vous dans la base de données
        $insert_query = mysqli_query($con, 
                "INSERT INTO appointments (client_id, agent_id, property_id, rdv_date, rdv_time, rdv_price, rdv_motivation, rdv_comments, rdv_status, rdv_created_at) 
                                        VALUES ('$user_id', '$agent_id', " . ($property_id === NULL ? "NULL" : "'$property_id'") . ", '$date_rdv', '$heure_rdv', '$appointmentPrice', '$motivation', '$comment', 'confirmé', NOW())");
        
        if($insert_query) {
            // Envoyer un email de confirmation (à implémenter)
            
            // Rediriger vers la page des rendez-vous
            header("Location: rdv_dashboard.php?success=1");
            exit();
        } else {
            $message = '<div class="alert alert-danger">Une erreur est survenue. Veuillez réessayer.</div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Prendre un rendez-vous - Omnes Immobilier</title>
    
    <!-- Meta Tags -->
    <link rel="shortcut icon" href="images/favicon.ico">
    
    <!--	Fonts -->
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
    
    <style>
        .rdv-confirmation {
            max-width: 700px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            padding: 30px;
        }
        .rdv-header {
            text-align: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        .rdv-details {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        .rdv-details p {
            margin-bottom: 10px;
            font-size: 16px;
        }
        .form-section {
            margin-bottom: 25px;
        }
        .form-section h4 {
            margin-bottom: 15px;
            color: var(--theme-secondary-color);
        }
        .radio-options {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-bottom: 20px;
        }
        .radio-option {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .radio-option input[type="radio"] {
            margin-top: 3px;
        }
        .form-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }
        @media (max-width: 767px) {
            .form-actions {
                flex-direction: column;
                gap: 15px;
            }
            .form-actions .btn {
                width: 100%;
            }
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
                    <h2 class="page-name text-white text-uppercase"><b>Prendre un rendez-vous</b></h2>
                </div>
                <div class="col-md-6">
                    <nav aria-label="breadcrumb" class="float-md-right">
                        <ol class="breadcrumb bg-transparent m-0 p-0">
                            <li class="breadcrumb-item text-white"><a href="home.php">Agents</a></li>
                            <li class="breadcrumb-item active">Prendre un rendez-vous</li>
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
                    <?php echo $message; ?>
                    
                    <div class="rdv-confirmation">
                        <div class="text-secondary rdv-header">
                            <h2>Confirmation de votre rendez-vous</h2>
                        </div>
                        
                        <div class="rdv-details">
                            <p><strong>Agent:</strong> <?= htmlspecialchars($agent['uname']) ?> <?= htmlspecialchars($agent['ufirstname']) ?></p>
                            <p><strong>Date:</strong> <?= $date_affichage ?></p>
                            <p><strong>Heure:</strong> <?= $heure_affichage ?></p>
                        </div>
                        
                        <form method="post" action="">
                            <div class="form-section">
                                <h4>Motif du rendez-vous</h4>
                                <div class="radio-options">
                                    <div class="radio-option">
                                        <input type="radio" id="motivation-visit" name="motivation" value="Visite d'un bien">
                                        <label for="motivation-visit">Visite d'un bien</label>
                                    </div>
                                    <div class="radio-option">
                                        <input type="radio" id="motivation-estimation" name="motivation" value="Estimation immobilière">
                                        <label for="motivation-estimation">Estimation immobilière</label>
                                    </div>
                                    <div class="radio-option">
                                        <input type="radio" id="motivation-consultation" name="motivation" value="Consultation générale" required>
                                        <label for="motivation-consultation">Consultation générale</label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-section">
                                <h4>Propriété concernée (optionnel)</h4>
                                <select name="property_id" class="form-control">
                                    <option value="">-- Sélectionnez une propriété --</option>
                                    <?php foreach($properties as $property): ?>
                                        <option value="<?= $property['pid'] ?>">
                                            <?= htmlspecialchars($property['title']) ?> - 
                                            <?= htmlspecialchars($property['location']) ?>, 
                                            <?= htmlspecialchars($property['city']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="form-section">
                                <h4>Commentaires supplémentaires</h4>
                                <textarea name="comment" class="form-control" rows="4" placeholder="Ajoutez des détails ou des questions pour l'agent..."></textarea>
                            </div>
                            
                            <div class="form-actions">
                                <a href="app_booking.php?agent_id=<?= $agent_id ?>" class="btn btn-danger">Annuler</a>
                                <button type="submit" name="confirm" class="btn btn-primary">Confirmer le rendez-vous</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php include("include/footer.php"); ?>
    
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>