<?php 
ini_set('session.cache_limiter','public');
session_cache_limiter(false);
session_start();
include("config.php");

if(!isset($_SESSION['uid'])) {
	header("location:login.php");
    exit();
}
?>


<!DOCTYPE html>
<html>
<head>
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

    <!--	Title
    	=========================================================-->
    <title>Omnes Immobilier</title>
</head>
    <body>
    <div id="page-wrapper">
        <div class="row"> 
            <!--	Header start  -->
    		<?php include("include/header.php");?>
            <!--	Header end  -->
            
            <!--	Banner   --->
            <div class="banner-full-row page-banner" style="background-image:url('images/breadcrumb.jpg');">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6">
                            <h2 class="page-name float-left text-white text-uppercase mt-1 mb-0"><b>Profil</b></h2>
                        </div>
                        <div class="col-md-6">
                            <nav aria-label="breadcrumb" class="float-left float-md-right">
                                <ol class="breadcrumb bg-transparent m-0 p-0">
                                    <li class="breadcrumb-item text-white"><a href="home.php">Page d'accueil</a></li>
                                    <li class="breadcrumb-item active">Profil</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <!--	Banner   --->
    
    		<!--	Profile Section   -->
            <div class="full-row">
                <div class="container">
                    <div class="dashboard-personal-info p-5 bg-white">                    
                        <div class="row">
                            <!-- Left Column - Profile Picture -->
                            <div class="col-lg-5 col-md-12">
                                <?php 
                                    $uid=$_SESSION['uid'];
                                    $query=mysqli_query($con,"SELECT * FROM `user` WHERE uid='$uid'");
                                    $user=mysqli_fetch_array($query);
                                    if($user) {
                                ?>
                                <div class="user-info text-center"> 
                                    <img src="images/profile_pic/<?php echo $user['uimage'];?>" alt="userimage" class="profile-image mb-4">
                                </div>
                                <?php } ?>
                            </div>
                                    
                            <!-- Right Column - User Information -->
                            <div class="col-lg-7 col-md-12">
                                <div class="profile-details p-4 bg-light rounded">
                                    <?php if($user) { ?>
                                    <h1 class="text-uppercase"><?php echo $user['uname'];?></h1>
                                    <h1 class="text-capitalise mb-4"><?php echo $user['ufirstname'];?></h1>
                                    
                                    <div class="font-18">
                                        <div class="mb-3"><?php echo $user['uemail'];?></div>
                                        <div class="mb-5">+ 33 0<?php echo $user['uphone'];?></div>
                                    </div>
                                    
                                    <!-- si l'agent, affiche un bouton pour voir son CV -->
                                    <?php if($_SESSION['isAgent']) { ?>
                                    <div class="mt-4">
                                        <a href="edit-profile.php" class="btn btn-primary">Voir mon CV</a>
                                    </div>
                                    <?php } ?>
                                    <?php } else { ?>
                                    <p class="alert alert-warning">No user information found.</p>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>            
                </div>
            </div>
    	    <!--	Profile section end  -->
                                    
            <!--	Footer   start-->
    		<?php include("include/footer.php");?>
    		<!--	Footer   start-->
                                    
            <!-- Scroll to top --> 
            <a href="#" class="bg-secondary text-white hover-text-secondary" id="scroll"><i class="fas fa-angle-up"></i></a> 
            <!-- End Scroll To top --> 
        </div>
    </div>
    <!-- Wrapper End --> 
                                    
    <!--	Js Link
    ============================================================--> 
    <script src="js/jquery.min.js"></script> 
    <!--jQuery Layer Slider --> 
    <script src="js/greensock.js"></script> 
    <script src="js/layerslider.transitions.js"></script> 
    <script src="js/layerslider.kreaturamedia.jquery.js"></script> 
    <!--jQuery Layer Slider --> 
    <script src="js/popper.min.js"></script> 
    <script src="js/bootstrap.min.js"></script> 
    <script src="js/owl.carousel.min.js"></script> 
    <script src="js/tmpl.js"></script> 
    <script src="js/jquery.dependClass-0.1.js"></script> 
    <script src="js/draggable-0.1.js"></script> 
    <script src="js/jquery.slider.js"></script> 
    <script src="js/wow.js"></script> 
    <script src="js/custom.js"></script>
</body>
</html>