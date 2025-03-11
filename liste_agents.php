<?php 
ini_set('session.cache_limiter','public');
session_cache_limiter(false);
session_start();
include("config.php");



// Initialisation de la requête SQL avec filtres et tri
$sql = "SELECT * FROM user WHERE utype='agent'";
$conditions = [];
$orderBy = "uname ASC"; // Tri par défaut (ordre alphabétique)

// Appliquer le filtrage
if (!empty($_GET['specialty'])) {
    $specialty = mysqli_real_escape_string($con, $_GET['specialty']);
    $conditions[] = "specialty = '$specialty'";
}

// Appliquer le tri
if (!empty($_GET['sort'])) {
    $sort = $_GET['sort'];
    if ($sort == "name_asc") {
        $orderBy = "uname ASC";
    } elseif ($sort == "name_desc") {
        $orderBy = "uname DESC";
    }
}

if (!empty($conditions)) {
    $sql .= " AND " . implode(" AND ", $conditions);
}

$sql .= " ORDER BY $orderBy";

// Exécuter la requête SQL avec filtres et tri
$query = mysqli_query($con, $sql);

// Récupérer les spécialités existantes pour le filtre
$specialtyQuery = mysqli_query($con, "SELECT DISTINCT specialty FROM user WHERE specialty IS NOT NULL AND specialty <> ''");
$specialties = [];
while ($row = mysqli_fetch_assoc($specialtyQuery)) {
    $specialties[] = $row['specialty'];
}
						
?>

<!DOCTYPE html>
<html>

<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Required meta tags -->
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<!-- Meta Tags -->
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<link rel="shortcut icon" href="images/favicon.ico">

<!--	Fonts	-->
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">

<!--	Css Link	-->
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
<title>Omnes Immobilier - Agents</title>

<!-- Styles -->
<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="css/style.css">

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
                        <h2 class="page-name text-white text-uppercase"><b>Nos Agents</b></h2>
                    </div>
                    <div class="col-md-6">
                        <nav aria-label="breadcrumb" class="float-md-right">
                            <ol class="breadcrumb bg-transparent m-0 p-0">
                                <li class="breadcrumb-item text-white"><a href="home.php">Accueil</a></li>
                                <li class="breadcrumb-item active">Nos Agents</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <!-- Liste des agents -->
        <div class="full-row">
            <div class="container">

                
                <div class="row mb-5">
                    <div class="col-lg-12">
                        <h2 class="text-secondary text-center double-down-line">Agents Immobiliers</h2>
                    </div>
                </div>

                <!-- Formulaire de Filtrage et Tri -->
                 <div class="container">
                    <form method="GET" action="admin_agent.php" class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <label>Spécialité</label>
                                <select name="specialty" class="form-control">
                                    <option value="">Toutes</option>
                                    <?php foreach ($specialties as $spec) { ?>
                                        <option value="<?php echo $spec; ?>" <?php if (!empty($_GET['specialty']) && $_GET['specialty'] == $spec) echo 'selected'; ?>>
                                            <?php echo ucfirst($spec); ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                                    
                            <div class="col-md-4">
                                <label>Tri par nom</label>
                                <select name="sort" class="form-control">
                                    <option value="name_asc" <?php if (!empty($_GET['sort']) && $_GET['sort'] == "name_asc") echo 'selected'; ?>>A-Z</option>
                                    <option value="name_desc" <?php if (!empty($_GET['sort']) && $_GET['sort'] == "name_desc") echo 'selected'; ?>>Z-A</option>
                                </select>
                            </div>
                                    
                            <div class="col-md-2 mt-4 text-center">
                                <button type="submit" class="btn btn-primary">Filtrer</button>
                            </div>
                        </div>
                    </form>
                </div>

                <table class="table table-bordered">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th>Photo</th>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Téléphone</th>
                            <th>Spécialité</th>
                            <th>Actions</th> <!-- Nouvelle colonne pour les boutons Supprimer -->
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                    while($row = mysqli_fetch_array($query)) {
                    ?>
                        <tr>
                            <td><div class="dashboard-user-image-container">
                                    <img src="images/profile_pic/<?php echo $row['uimage']; ?>" >
                                </div></td>
                            <td><?php echo $row['uname'] . " " . $row['ufirstname']; ?></td>
                            <td><?php echo $row['uemail']; ?></td>
                            <td><?php echo $row['uphone']; ?></td>
                            <td><?php echo $row['specialty'] ?? 'Non spécifiée'; ?></td>
                            <td>
                                
                                <!-- Télécharger CV -->
                                <?php if (!empty($row['cv']) && file_exists("images/cv/" . $row['cv'])) { ?>
                                    <a href="images/cv/<?php echo $row['cv']; ?>" download class="btn btn-secondary">Télécharger CV</a>
                                <?php } else { ?>
                                    <span class="text-danger">CV non disponible</span>
                                <?php } ?>

                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>            
            </div>
        </div>

        <!-- Footer -->
        <?php include("include/footer.php");?>
    </div>
</div>

<!-- Scripts -->
<script src="js/jquery.min.js"></script> 
<script src="js/bootstrap.min.js"></script> 
</body>
</html>
