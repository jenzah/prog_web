<?php 
ini_set('session.cache_limiter', 'public');
session_cache_limiter(false);
session_start();
include("config.php");

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if (!isset($_SESSION['uid'])) {
    header("location: login.php");
    exit();
}

$userId = $_SESSION['uid'];

$userCheckQuery = mysqli_query($con, "SELECT uid FROM user WHERE uid = '$userId'");
if (mysqli_num_rows($userCheckQuery) == 0) {
    die("<p class='alert alert-danger'>Utilisateur non trouvé.</p>");
}

// Paiements en attente (is_paid = 0)
$pendingPaymentsQuery = mysqli_query($con, "
    SELECT a.*, pr.title AS property_title, pr.location, pr.city,
        agent.uname as agent_name,
        agent.ufirstname as agent_firstname
    FROM appointments a
    LEFT JOIN property pr ON a.property_id = pr.pid
    LEFT JOIN user agent ON a.agent_id = agent.uid
    WHERE a.client_id = '$userId' AND a.is_paid = 0
    ORDER BY a.rdv_date DESC
");

// Paiements complétés (is_paid = 1)
$completedPaymentsQuery = mysqli_query($con, "
    SELECT a.*, pr.title AS property_title, pr.location, pr.city,
        agent.uname as agent_name,
        agent.ufirstname as agent_firstname
    FROM appointments a
    LEFT JOIN property pr ON a.property_id = pr.pid
    LEFT JOIN user agent ON a.agent_id = agent.uid
    WHERE a.client_id = '$userId' AND a.is_paid = 1
    ORDER BY a.rdv_payment_date DESC
");

// Annuler un rendez-vous si "cancel_id" est présent dans l'URL
if (isset($_GET['cancel_id'])) {
    $appointmentId = (int) $_GET['cancel_id'];

    // Vérifier si le rendez-vous existe et appartient à l'utilisateur courant
    $checkQuery = mysqli_query($con, "SELECT * FROM appointments, user WHERE aid='$appointmentId' AND 
                                      (utype = 'agent' AND agent_id='$userId' OR 
                                       utype = 'client' AND client_id='$userId' OR 
                                       utype = 'admin')");
                                       
    if (mysqli_num_rows($checkQuery) > 0) {
        // Annulation
        $cancelQuery = mysqli_query($con, "DELETE FROM appointments WHERE aid='$appointmentId'");
        if ($cancelQuery) {
            echo "<script>alert('RDV annulé avec succès.'); window.location='payment.php';</script>";
        } else {
            echo "<script>alert('Erreur lors de l\'annulation.');</script>";
        }
    } else {
        echo "<script>alert('RDV introuvable ou non autorisé.');</script>";
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
<title>Omnes Immobilier - Mes Paiements</title>
</head>

<body>
<div id="page-wrapper">
    <div class="row"> 
        <!-- Header -->
        <?php include("include/header.php");?>

        <!-- Page Title -->
        <div class="banner-full-row page-banner" style="background-image:url('images/breadcrumb.jpg');">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <h2 class="page-name text-white text-uppercase"><b>Détail du RDV</b></h2>
                    </div>
                    <div class="col-md-6">
                        <nav aria-label="breadcrumb" class="float-md-right">
                            <ol class="breadcrumb bg-transparent m-0 p-0">
                                <li class="breadcrumb-item text-white"><a href="home.php">Accueil</a></li>
                                <li class="breadcrumb-item text-white"><a href="rdv_dashboard.php">Mes RDVs</a></li>
                                <li class="breadcrumb-item active">Détail du RDV</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <div class="full-row">
            <div class="container">
                <!-- Paiements en attente -->
                <div class="row mb-5">
                    <div class="col-lg-12">
                        <h2 class="text-secondary text-center double-down-line">Paiements en attente</h2>
                    </div>
                </div>

                <div id="pending-payments">
                <?php if(mysqli_num_rows($pendingPaymentsQuery) > 0) { ?>
                <div class="table-responsive">
                    <table class="table table-bordered auto-table">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th>Propriété</th>
                                <th>Agent</th>
                                <th>Date de RDV</th>
                                <th>Heure</th>
                                <th>Frais de service</th>
                                <th>Statut</th>
                                <th class="actions">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php while($payment = mysqli_fetch_array($pendingPaymentsQuery)) { 
                            // Formater la date et l'heure
                            $date = new DateTime($payment['rdv_date']);
                            $formattedDate = $date->format('d/m/Y');
                            
                            $time = new DateTime($payment['rdv_time']);
                            $formattedTime = $time->format('H:i');
                            ?>
                            <tr id="payment-<?php echo $payment['aid']; ?>">
                                <td><?php echo $payment['property_title']; ?></td>
                                <td><?php echo $payment['agent_firstname']; ?> <?php echo strtoupper($payment['agent_name']); ?></td>
                                <td><?php echo $formattedDate; ?></td>
                                <td><?php echo $formattedTime; ?></td>
                                <td>€<?php echo number_format($payment['rdv_price'], 2); ?></td>
                                <td><span class="badge badge-warning">En attente</span></td>
                                <td class="actions text-center">
                                    <a href="payment_confirm.php?appointment_id=<?php echo $payment['aid']; ?>" class="payment-link">
                                        <i class="fas fa-credit-card text-secondary" title="Payer"></i> Payer
                                    </a>
                                    <a href="payment.php?cancel_id=<?php echo $payment['aid']; ?>" onclick="return confirm('Voulez-vous annuler ce RDV ?')" class="payment-link">
                                        <i class="fas fa-times-circle text-danger" title="Annuler"></i> Annuler
                                    </a>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
                <?php } else { ?>
                <div class="no-payments">Aucun paiement en attente</div>
                <?php } ?>
                </div>

                <!-- Paiements complétés -->
                <div class="row mt-5">
                    <div class="col-lg-12">
                        <h2 class="text-secondary text-center double-down-line">Paiements complétés</h2>
                    </div>
                </div>

                <?php if(mysqli_num_rows($completedPaymentsQuery) > 0) { ?>
                <div class="table-responsive">
                    <table class="table table-bordered auto-table">
                        <thead class="bg-secondary text-white">
                            <tr>
                                <th>Propriété</th>
                                <th>Agent</th>
                                <th>Date de RDV</th>
                                <th>Heure</th>
                                <th>Frais de service</th>
                                <th>Date de paiement</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php while($payment = mysqli_fetch_array($completedPaymentsQuery)) { 
                            // Formater la date et l'heure
                            $date = new DateTime($payment['rdv_date']);
                            $formattedDate = $date->format('d/m/Y');
                            
                            $time = new DateTime($payment['rdv_time']);
                            $formattedTime = $time->format('H:i');
                            ?>
                            <tr>
                                <td><?php echo $payment['property_title']; ?></td>
                                <td><?php echo $payment['agent_firstname']; ?> <?php echo strtoupper($payment['agent_name']); ?></td>
                                <td><?php echo $formattedDate; ?></td>
                                <td><?php echo $formattedTime; ?></td>
                                <td>€<?php echo number_format($payment['rdv_price'], 2); ?></td>
                                <td>
                                <?php 
                                    if ($payment['rdv_payment_date'] !== NULL) {
                                      echo date('d/m/Y H:i', strtotime($payment['rdv_payment_date']));
                                    } else {
                                      // Use the RDV date and time instead
                                      echo $formattedDate . ' ' . $formattedTime;
                                    }
                                ?></td>
                                <td><span class="badge badge-paid">Payé</span></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
                <?php } else { ?>
                <div class="no-payments">Aucun paiement complété</div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<?php include("include/footer.php"); ?>

<script>
$(document).on("click", ".cancel-payment", function() {
    var appointmentId = $(this).data("id");
    if (confirm("Voulez-vous annuler ce paiement ?")) {
        $.get("payment_cancel.php", { appointment_id: appointmentId }, function(response) {
            var result = JSON.parse(response);
            if (result.status === "success") {
                $("#payment-" + appointmentId).fadeOut();
                alert(result.message);
            } else {
                alert(result.message);
            }
        });
    }
});
</script>
</body>
</html>