<?php
ini_set('session.cache_limiter', 'public');
session_cache_limiter(false);
session_start();
include("config.php");

if (!isset($_SESSION['uid'])) {
    header("location: login.php");
    exit();
}

$userId = $_SESSION['uid'];
$paymentId = isset($_GET['appointment_id']) ? intval($_GET['appointment_id']) : 0;

if ($paymentId <= 0) {
    die("<p class='alert alert-danger'>ID de paiement invalide.</p>");
}

$query = mysqli_query($con, "
    SELECT a.*, pr.title AS property_title, pr.location, pr.city,
        agent.uname as agent_name,
        agent.ufirstname as agent_firstname 
    FROM appointments a
    LEFT JOIN property pr ON a.property_id = pr.pid
    LEFT JOIN user agent ON a.agent_id = agent.uid
    WHERE a.aid = '$paymentId' 
    AND a.client_id = '$userId' 
    AND a.is_paid = 0
");

if (!$query || mysqli_num_rows($query) == 0) {
    die("<p class='alert alert-danger'>Ce paiement n'existe pas ou a déjà été traité.</p>");
}

$payment = mysqli_fetch_assoc($query);
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $card_type = mysqli_real_escape_string($con, $_POST['card_type']);
    $card_number = mysqli_real_escape_string($con, $_POST['card_number']);
    $card_name = mysqli_real_escape_string($con, $_POST['card_name']);
    $expiry_month = $_POST['expiry_month'];
    $expiry_year = $_POST['expiry_year'];
    $security_code = $_POST['security_code'];

    $expiry_date = "$expiry_year-$expiry_month";

    if (empty($card_type) || empty($card_number) || empty($card_name) || empty($expiry_month) || empty($expiry_year) || empty($security_code)) {
        $error = "<p class='alert alert-danger'>Veuillez remplir tous les champs.</p>";
    } elseif (!preg_match('/^\d{16}$/', $card_number)) {
        $error = "<p class='alert alert-danger'>Le numéro de carte doit contenir 16 chiffres.</p>";
    } elseif (!preg_match('/^\d{3,4}$/', $security_code)) {
        $error = "<p class='alert alert-danger'>Code de sécurité invalide.</p>";
    } else {
        $current_date = date('Y-m');
        if ($expiry_date < $current_date) {
            $error = "<p class='alert alert-danger'>Votre carte est expirée.</p>";
        } else {
            $card_last4 = substr($card_number, -4);
            $security_code_hash = password_hash($security_code, PASSWORD_BCRYPT);

            $update = mysqli_query($con, "
                UPDATE appointments 
                SET is_paid = 1, rdv_payment_date = NOW()
                WHERE aid = '$paymentId' AND client_id = '$userId'
            ");

            if ($update) {
                echo "<script>
                    alert('Paiement confirmé avec succès !');
                    window.location='payment.php';
                </script>";
                exit(); // Empêche l'exécution du reste du script
            } else {
                $error = "<p class='alert alert-danger'>Erreur lors du paiement : " . mysqli_error($con) . "</p>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<!-- Meta Tags -->
<link rel="shortcut icon" href="images/favicon.ico">

<!--	Fonts
	========================================================-->
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">

<!--	Css Link
	========================================================-->
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
<title>Confirmation du Paiement - Omnes Immobilier</title>

<!-- Styles -->
<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>
    <div id="page-wrapper">
        <div class="row"> 
            <?php include("include/header.php"); ?>

            <div class="banner-full-row page-banner" style="background-image:url('images/breadcrumb.jpg');">
                <div class="container">
                    <h2 class="text-white">Confirmation du Paiement</h2>
                </div>
            </div>

            <div class="full-row">
                <div class="container">
                    <div class="dashboard-personal-info p-5 bg-white">
                        <h4 class="text-uppercase">Détails du paiement</h4>
                        <p><strong>Propriété :</strong> <?php echo $payment['property_title']; ?></p>
                        <p><strong>Lieu :</strong> <?php echo $payment['location'] . ', ' . $payment['city']; ?></p>
                        <p><strong>Frais de service :</strong> €<?php echo number_format($payment['rdv_price'], 2); ?></p>

                        <h4 class="text-uppercase mt-4">Informations de paiement</h4>
                        <?php echo isset($error) ? $error : ''; ?>

                        <form method="POST">
                            <label>Type de Carte :</label>
                            <select name="card_type" class="form-control" required>
                                <option value="Visa">Visa</option>
                                <option value="MasterCard">MasterCard</option>
                                <option value="American Express">American Express</option>
                                <option value="PayPal">PayPal</option>
                            </select>

                            <label>Numéro de la Carte :</label>
                            <input type="text" name="card_number" class="form-control" maxlength="16" required>

                            <label>Nom sur la Carte :</label>
                            <input type="text" name="card_name" class="form-control" required>

                            <div class="row">
                                <div class="col-md-6">
                                    <label>Mois d'Expiration :</label>
                                    <select name="expiry_month" class="form-control" required>
                                        <?php for ($m = 1; $m <= 12; $m++) {
                                            printf('<option value="%02d">%02d</option>', $m, $m);
                                        } ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label>Année d'Expiration :</label>
                                    <select name="expiry_year" class="form-control" required>
                                        <?php 
                                        $currentYear = date('Y');
                                        for ($y = $currentYear; $y <= $currentYear + 10; $y++) {
                                            echo "<option value='$y'>$y</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <label>Code de Sécurité :</label>
                            <input type="text" name="security_code" class="form-control" maxlength="4" required>

                            <button type="submit" class="btn btn-primary mt-3 w-100">
                                <i class="fas fa-credit-card"></i> Confirmer et Payer
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <?php include("include/footer.php"); ?>
        </div>
    </div>

    <script src="js/jquery.min.js"></script> 
    <script src="js/bootstrap.min.js"></script> 
</body>
</html>
