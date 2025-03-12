<?php 
ini_set('session.cache_limiter','public');
session_cache_limiter(false);
session_start();
include("config.php");

if(!isset($_SESSION['uid'])) {
    header("location:login.php");
}

// The page requires admin role
if(empty($_SESSION['isAdmin'])) {
    header("Location:unauthorised.php");
    exit();
}

// Récupérer les types de propriétés depuis la base de données
$propertyTypesQuery = mysqli_query($con, "SELECT DISTINCT propertyType FROM property");
$propertyTypes = [];
while ($row = mysqli_fetch_assoc($propertyTypesQuery)) {
    $propertyTypes[] = $row['propertyType'];
}

// Supprimer une propriété si "delete_id" est présent dans l'URL
if (isset($_GET['delete_id'])) {
    $pid = (int) $_GET['delete_id'];

    // Vérifier si la propriété existe
    $checkQuery = mysqli_query($con, "SELECT * FROM property WHERE pid='$pid'");
    if (mysqli_num_rows($checkQuery) > 0) {
        // Suppression
        $deleteQuery = mysqli_query($con, "DELETE FROM property WHERE pid='$pid'");
        if ($deleteQuery) {
            echo "<script>alert('Propriété supprimée avec succès.'); window.location='admin_property.php';</script>";
        } else {
            echo "<script>alert('Erreur lors de la suppression.');</script>";
        }
    } else {
        echo "<script>alert('Propriété introuvable.');</script>";
    }
}	
// Construction de la requête SQL avec filtres
$sql = "SELECT * FROM property";
$conditions = [];

if (!empty($_GET['propertyType'])) {
    $propertyType = mysqli_real_escape_string($con, $_GET['propertyType']);
    $conditions[] = "propertyType = '$propertyType'";
}

if (!empty($_GET['status'])) {
    $status = mysqli_real_escape_string($con, $_GET['status']);
    $conditions[] = "status = '$status'";
}

if (!empty($_GET['minPrice'])) {
    $minPrice = (int)$_GET['minPrice'];
    $conditions[] = "price >= $minPrice";
}

if (!empty($_GET['maxPrice'])) {
    $maxPrice = (int)$_GET['maxPrice'];
    $conditions[] = "price <= $maxPrice";
}

if (!empty($_GET['city'])) {
    $city = mysqli_real_escape_string($con, $_GET['city']);
    $conditions[] = "city LIKE '%$city%'";
}

if (!empty($conditions)) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}

// Exécuter la requête SQL
$query = mysqli_query($con, $sql);						
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
<title>Omnes Immobilier - Propriétés</title>

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
                        <h2 class="page-name text-white text-uppercase"><b>Gestion Propriétés</b></h2>
                    </div>
                    <div class="col-md-6">
                        <nav aria-label="breadcrumb" class="float-md-right">
                            <ol class="breadcrumb bg-transparent m-0 p-0">
                                <li class="breadcrumb-item text-white"><a href="index.php">Accueil</a></li>
                                <li class="breadcrumb-item active">Gestion Propriétés</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des propriétés -->
        <div class="full-row">
            <div class="container">
                <!-- Bouton Ajouter une Propriété -->
                <div class="row mb-3">
                    <div class="col-lg-12 text-right">
                        <a href="admin_add_property.php">
                        <img src="images/admin/ajouter.png" class="database-icon" title="Ajouter une propriété" style="width: 30px !important; height: 30px !important;">
                        </a>
                    </div>
                </div>

                <div class="row mb-5">
                    <div class="col-lg-12">
                        <h2 class="text-secondary text-center double-down-line">Propriétés Disponibles</h2>
                    </div>
                </div>

                <!-- Filtres -->
                <div class="container">
                    <form method="GET" action="admin_property.php" class="mb-4">
                        <div class="row">
                            <div class="col-md-2">
                                <label>Type</label>
                                <select name="propertyType" class="form-control">
                                    <option value="">Tous</option> <!-- Ajout de l'option "Tous" -->
                                    <?php foreach ($propertyTypes as $type) { ?>
                                        <option value="<?php echo $type; ?>" <?php if (!empty($_GET['propertyType']) && $_GET['propertyType'] == $type) echo "selected"; ?>>
                                            <?php echo ucfirst($type); ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                                    
                            <div class="col-md-2">
                                <label>Statut</label>
                                <select name="status" class="form-control">
                                    <option value="">Tous</option>
                                    <option value="A vendre">À vendre</option>
                                    <option value="A louer">À louer</option>
                                    <option value="Loué">Loué</option>
                                    <option value="Vendu">Vendu</option>
                                </select>
                            </div>
                                    
                            <div class="col-md-2">
                                <label>Prix Min (€)</label>
                                <input type="number" name="minPrice" class="form-control" placeholder="Min">
                            </div>
                                    
                            <div class="col-md-2">
                                <label>Prix Max (€)</label>
                                <input type="number" name="maxPrice" class="form-control" placeholder="Max">
                            </div>
                                    
                            <div class="col-md-2">
                                <label>Ville</label>
                                <input type="text" name="city" class="form-control" placeholder="Ville">
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
                            <th>Image</th>
                            <th>Titre</th>
                            <th>Type</th>
                            <th>Superficie</th>
                            <th>Chambres</th>
                            <th>Salles de bain</th>
                            <th>Prix</th>
                            <th>Localisation</th>
                            <th>Statut</th>
                            <th>Actions</th> <!-- Nouvelle colonne pour les boutons Modifier/Supprimer -->
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                    while($row = mysqli_fetch_array($query)) {
                    ?>
                        <tr>
                            <td><div class="dashboard-property-image-container">
                                <img src="images/property/<?php echo $row['pimage1']; ?>">
                            </div></td>
                            <td><?php echo $row['title']; ?></td>
                            <td><?php echo ucfirst($row['propertyType']); ?></td>
                            <td><?php echo $row['area']; ?> m²</td>
                            <td><?php echo $row['nbRooms']; ?></td>
                            <td><?php echo $row['nbBathrooms']; ?></td>
                            <td><?php echo number_format($row['price'], 0, ',', ' '); ?> €</td>
                            <td><?php echo $row['location'] . ', ' . $row['city'] . ' (' . $row['department'] . ')'; ?></td>
                            <td><?php echo ucfirst($row['status']); ?></td>
                            <td>
    <!-- Bouton Modifier -->
    <a href="admin_edit_property.php?id=<?php echo $row['pid']; ?>">
        <img src="images/admin/modifier.png" class="img-action" style="width: 23px !important; height: 23px !important;" title="Modifier" >
    </a>

    <!-- Bouton Supprimer -->
    <a href="admin_property.php?delete_id=<?php echo $row['pid']; ?>" onclick="return confirm('Voulez-vous vraiment supprimer cette propriété ?');">
        <img src="images/admin/supprimer.png" class="img-action" style="width: 23px !important; height: 23px !important;" title="Supprimer">
    </a>
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
