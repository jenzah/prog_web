<?php 
ini_set('session.cache_limiter','public');
session_cache_limiter(false);
session_start();
include("config.php");
?>

<!DOCTYPE html>
<html>
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  
  <!-- Meta Tags -->
  <link rel="shortcut icon" href="images/favicon.ico">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">

  <!-- CSS Links -->
  <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="css/bootstrap-slider.css">
  <link rel="stylesheet" type="text/css" href="css/jquery-ui.css">
  <link rel="stylesheet" type="text/css" href="css/layerslider.css">
  <link rel="stylesheet" type="text/css" href="css/color.css" id="color-change">
  <link rel="stylesheet" type="text/css" href="css/owl.carousel.min.css">
  <link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">
  <link rel="stylesheet" type="text/css" href="fonts/flaticon/flaticon.css">
  <link rel="stylesheet" type="text/css" href="css/style.css">

  <!-- Title -->
  <title>Omnes Immobilier</title>

  <style>
      .property-card { transition: transform 0.3s ease-in-out; }
      .property-card:hover { transform: scale(1.05); }

      .property-slider .item { padding: 15px; }

      .owl-nav {
          position: absolute;
          top: 50%;
          width: 100%;
          display: flex;
          justify-content: space-between;
          transform: translateY(-50%);
      }

      .owl-prev, .owl-next {
          background: rgba(0, 0, 0, 0.5) !important;
          color: #fff !important;
          border-radius: 50%;
          padding: 10px;
      }

      .owl-prev:hover, .owl-next:hover {
          background: #007bff !important;
      }
  </style>
</head>

<body>
<div id="page-wrapper">
    <div class="row"> 
        <?php include("include/header.php");?>

        <!-- Banner -->
        <div class="overlay-black w-100 slider-banner1 position-relative" style="background-image: url('images/banner/04.jpg'); background-size: cover; background-position: center;">
            <div class="container h-100">
                <div class="row h-100 align-items-center">
                    <div class="col-lg-12">
                        <div class="text-white">
                            <h1 class="mb-4"><span class="text-light-primary">Trouvez</span><br>la maison de vos rêves</h1>
                            <form method="post" id="propertySearchForm">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="city" placeholder="Ville / Commune" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <select class="form-control" name="propertyType">
                                            <option value="">Type de bien</option>
                                            <option value="résidentiel">Résidentiel</option>
                                            <option value="commercial">Commercial</option>
                                            <option value="terrain">Terrain</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-primary w-100">Rechercher</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Carrousel des Propriétés Récentes -->
        <div class="full-row">
            <div class="container">
                <div class="row mb-3">
                    <div class="col-md-12 text-center">
                        <h2 class="text-secondary double-down-line mb-4">Propriétés récentes</h2>
                    </div>
                </div>

                <div class="owl-carousel property-slider">
                    <?php 
                    $query = mysqli_query($con, "SELECT * FROM property ORDER BY date DESC LIMIT 9");
                    while ($row = mysqli_fetch_array($query)) { ?>
                        <div class="item">
                            <a href="propertydetail.php?pid=<?php echo $row['pid']; ?>" class="property-card-link">
                                <div class="property-card">
                                    <div class="featured-thumb hover-zoomer">
                                        <div class="overlay-black overflow-hidden position-relative">
                                            <img src="images/property/<?php echo $row['pimage1']; ?>" alt="property-image">
                                            <div class="featured bg-primary text-white">New</div>
                                            <div class="sale bg-secondary text-white"><?php echo ucfirst($row['status']); ?></div>
                                            <div class="price text-primary"><b>€<?php echo number_format($row['price'], 0, ',', ' '); ?></b></div>
                                        </div>
                                        <div class="featured-thumb-data shadow-one p-3">
                                            <h5 class="text-secondary hover-text-primary mb-2"><?php echo $row['title']; ?></h5>
                                            <span class="location"><i class="fas fa-map-marker-alt text-primary"></i> <?php echo $row['location'] . ', ' . $row['city']; ?></span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>

       
<!-- Événements de la semaine -->
<div class="full-row bg-light">
    <div class="container">
        <h2 class="text-secondary double-down-line mb-4 text-center">Événements de la semaine</h2>
        <div class="owl-carousel events-slider">
            <div class="item bg-white p-4 shadow-sm rounded">
                <h5 class="text-primary">Journée portes ouvertes</h5>
                <p class="text-muted"><i class="fas fa-calendar-alt"></i> Samedi 15 Mars 2025</p>
                <p>Venez découvrir nos nouvelles propriétés lors de notre journée portes ouvertes à Paris.</p>
            </div>
            <div class="item bg-white p-4 shadow-sm rounded">
                <h5 class="text-primary">Séminaire sur la gestion immobilière</h5>
                <p class="text-muted"><i class="fas fa-calendar-alt"></i> Lundi 18 Mars 2025</p>
                <p>Un expert en immobilier partagera les meilleures pratiques pour investir en 2025.</p>
            </div>
            <div class="item bg-white p-4 shadow-sm rounded">
                <h5 class="text-primary">Visite d’une maison à vendre</h5>
                <p class="text-muted"><i class="fas fa-calendar-alt"></i> Dimanche 17 Mars 2025</p>
                <p>Rejoignez-nous pour une visite exclusive d'une maison exceptionnelle à Neuilly-sur-Seine.</p>
            </div>
        </div>
    </div>
</div>

<!-- Bulletin Immobilier -->
<div class="full-row">
    <div class="container">
        <h2 class="text-secondary double-down-line mb-4 text-center">Bulletin immobilier de la semaine</h2>
        <div class="row">
            <div class="col-md-4">
                <div class="bg-white p-4 shadow-sm rounded">
                    <h5 class="text-primary">Tendance du marché immobilier</h5>
                    <p class="text-muted"><i class="fas fa-calendar-alt"></i> 08 Mars 2025</p>
                    <p>Les prix de l'immobilier à Paris continuent d'augmenter, selon une récente étude.</p>
                    <a href="#" class="text-primary">Lire plus...</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bg-white p-4 shadow-sm rounded">
                    <h5 class="text-primary">Nouvelles réglementations locatives</h5>
                    <p class="text-muted"><i class="fas fa-calendar-alt"></i> 07 Mars 2025</p>
                    <p>Découvrez les nouvelles lois sur la location qui entreront en vigueur dès l'été 2025.</p>
                    <a href="#" class="text-primary">Lire plus...</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bg-white p-4 shadow-sm rounded">
                    <h5 class="text-primary">Top 5 des quartiers en vogue</h5>
                    <p class="text-muted"><i class="fas fa-calendar-alt"></i> 06 Mars 2025</p>
                    <p>Découvrez les quartiers les plus prisés pour investir en Île-de-France cette année.</p>
                    <a href="#" class="text-primary">Lire plus...</a>
                </div>
            </div>
        </div>
    </div>
</div>
 <!-- Section Google Maps -->
 <div class="full-row">
    <div class="container">
        <h2 class="text-secondary double-down-line mb-4 text-center">Notre Localisation</h2>
      
        <iframe 
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d41914.17561251856!2d2.287592715893688!3d48.8588446474787!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e66fdf8b57f75d%3A0x3b1c37c4e2e19b12!2sParis!5e0!3m2!1sfr!2sfr!4v1710098765432!5m2!1sfr!2sfr" 
            width="100%" height="500" frameborder="0" style="border:0;" allowfullscreen="">
        </iframe>
        </div>
</div>


        <!-- Section Contactez-nous -->
        <div class="full-row bg-light">
    <div class="container">
        <h2 class="text-secondary double-down-line mb-4 text-center">Contactez-nous</h2>
        <div class="row">
            <!-- Coordonnées -->
            <div class="col-lg-4">
                <div class="bg-primary text-white p-4 rounded">
                    <h4>Nos Coordonnées</h4>
                    <p><i class="fa fa-map-marker"></i> 12 Rue de Paris, 75001 Paris, France</p>
                    <p><i class="fa fa-phone"></i> +33 1 23 45 67 89</p>
                    <p><i class="fa fa-envelope"></i> contact@omnes-immobilier.com</p>
                    <p><i class="fa fa-clock"></i> Lun - Ven: 9h - 18h</p>
                </div>
            </div>

            <!-- Formulaire de Contact -->
            <div class="col-lg-8">
                <div class="bg-white p-4 rounded shadow">
                    <h4 class="mb-4">Envoyez-nous un message</h4>

                    <!-- Affichage des messages de succès ou d'erreur -->
                    <?php if(!empty($msg)) echo "<div class='alert alert-success'>$msg</div>"; ?>
                    <?php if(!empty($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

                    <form action="#" method="post">
                        <div class="row">
                            <div class="form-group col-lg-6">
                                <label><i class="fa fa-user"></i> Votre Nom *</label>
                                <input type="text" name="name" class="form-control" placeholder="Entrez votre nom" required>
                            </div>
                            <div class="form-group col-lg-6">
                                <label><i class="fa fa-envelope"></i> Votre Email *</label>
                                <input type="email" name="email" class="form-control" placeholder="Entrez votre email" required>
                            </div>
                            <div class="form-group col-lg-6">
                                <label><i class="fa fa-phone"></i> Votre Téléphone *</label>
                                <input type="text" name="phone" class="form-control" placeholder="Entrez votre téléphone" required maxlength="10">
                            </div>
                            <div class="form-group col-lg-6">
                                <label><i class="fa fa-tag"></i> Objet *</label>
                                <input type="text" name="subject" class="form-control" placeholder="Objet de votre message" required>
                            </div>
                            <div class="col-lg-12">
                                <label><i class="fa fa-comments"></i> Votre Message *</label>
                                <div class="form-group">
                                    <textarea name="message" class="form-control" rows="5" placeholder="Écrivez votre message ici..." required></textarea>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <button type="submit" name="send" class="btn btn-primary w-100">Envoyer le Message</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div> <!-- Fin de la row -->
    </div> <!-- Fin de la container -->
</div> <!-- Fin de la full-row -->

<!-- Footer -->
<?php include("include/footer.php");?>

    </div>
</div>

<!-- jQuery & Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
<script src="js/bootstrap.min.js"></script>

<script>
$(document).ready(function() {
    $(".property-slider").owlCarousel({
        loop: true,
        margin: 15,
        nav: true,
        dots: false,
        autoplay: true,
        autoplayTimeout: 3000,
        navText: ["<i class='fas fa-chevron-left'></i>", "<i class='fas fa-chevron-right'></i>"],
        responsive: { 0: { items:1 }, 600: { items:2 }, 1000: { items:3 } }
    });
});
</script>
<script>
$(document).ready(function() {
    $(".events-slider").owlCarousel({
        loop: true,
        margin: 15,
        nav: true,
        dots: false,
        autoplay: true,
        autoplayTimeout: 4000,
        navText: ["<i class='fas fa-chevron-left'></i>", "<i class='fas fa-chevron-right'></i>"],
        responsive: { 0: { items:1 }, 600: { items:2 }, 1000: { items:3 } }
    });
});
</script>

</body>
</html>
