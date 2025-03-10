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

// Paiements en attente
$pendingPaymentsQuery = mysqli_query($con, "
    SELECT p.*, pr.title AS property_title, pr.location, pr.city, p.service_fee
    FROM payment p
    JOIN property pr ON p.property_id = pr.pid
    WHERE p.user_id = '$userId' AND p.payment_status = 'pending'
    ORDER BY p.payment_date DESC
");

// Paiements complétés
$completedPaymentsQuery = mysqli_query($con, "
    SELECT p.*, pr.title AS property_title, pr.location, pr.city, p.service_fee
    FROM payment p
    JOIN property pr ON p.property_id = pr.pid
    WHERE p.user_id = '$userId' AND p.payment_status = 'completed'
    ORDER BY p.payment_date DESC
");

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Omnes Immobilier - Mes Paiements</title>

    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        .auto-table { width: 100%; }
        .auto-table th.actions, .auto-table td.actions { width: 1%; white-space: nowrap; }
        .no-payments { text-align: center; padding: 20px; font-style: italic; color: #6c757d; }
    </style>
</head>

<body>
<div id="page-wrapper">
    <div class="row"> 
        <?php include("include/header.php");?>

        <div class="banner-full-row page-banner" style="background-image:url('images/breadcrumb.jpg');">
            <div class="container">
                <h2 class="page-name text-white text-uppercase text-center"><b>Mes Paiements</b></h2>
            </div>
        </div>

        <div class="full-row">
            <div class="container">
                <!-- Paiements en attente -->
                <div class="row mb-5">
                    <div class="col-lg-12">
                        <h2 class="text-secondary text-center">Paiements en attente</h2>
                    </div>
                </div>

                <div id="pending-payments">
                <?php if(mysqli_num_rows($pendingPaymentsQuery) > 0) { ?>
                <div class="table-responsive">
                    <table class="table table-bordered auto-table">
                        <thead class="bg-warning text-white">
                            <tr>
                                <th>Propriété</th>
                                <th>Lieu</th>
                                <th>Frais de service</th>
                                <th>Date</th>
                                <th>Statut</th>
                                <th class="actions">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php while($payment = mysqli_fetch_array($pendingPaymentsQuery)) { ?>
                            <tr id="payment-<?php echo $payment['payment_id']; ?>">
                                <td><?php echo $payment['property_title']; ?></td>
                                <td><?php echo $payment['location'] . ', ' . $payment['city']; ?></td>
                                <td>€<?php echo number_format($payment['service_fee'], 2); ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($payment['payment_date'])); ?></td>
                                <td><span class="badge badge-warning"><?php echo ucfirst($payment['payment_status']); ?></span></td>
                                <td class="actions text-center">
                                    <a href="confirm_payment.php?payment_id=<?php echo $payment['payment_id']; ?>">
                                        <i class="fas fa-credit-card text-primary" title="Payer"></i>
                                    </a>
                                    <a href="#" class="cancel-payment" data-id="<?php echo $payment['payment_id']; ?>">
                                        <i class="fas fa-times-circle text-danger" title="Annuler"></i>
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
                        <h2 class="text-secondary text-center">Paiements complétés</h2>
                    </div>
                </div>

                <?php if(mysqli_num_rows($completedPaymentsQuery) > 0) { ?>
                <div class="table-responsive">
                    <table class="table table-bordered auto-table">
                        <thead class="bg-success text-white">
                            <tr>
                                <th>Propriété</th>
                                <th>Lieu</th>
                                <th>Frais de service</th>
                                <th>Date</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php while($payment = mysqli_fetch_array($completedPaymentsQuery)) { ?>
                            <tr>
                                <td><?php echo $payment['property_title']; ?></td>
                                <td><?php echo $payment['location'] . ', ' . $payment['city']; ?></td>
                                <td>€<?php echo number_format($payment['service_fee'], 2); ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($payment['payment_date'])); ?></td>
                                <td><span class="badge badge-success"><?php echo ucfirst($payment['payment_status']); ?></span></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<script>
$(document).on("click", ".cancel-payment", function() {
    var paymentId = $(this).data("id");
    if (confirm("Voulez-vous annuler ce paiement ?")) {
        $.get("cancel_payment.php", { payment_id: paymentId }, function(response) {
            var result = JSON.parse(response);
            if (result.status === "success") {
                $("#payment-" + paymentId).fadeOut();
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
