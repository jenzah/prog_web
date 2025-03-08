<?php 
include("config.php");
$error="";
$msg="";
if(isset($_REQUEST['register'])) {
	$name=$_REQUEST['name'];
	$firstname=$_REQUEST['firstname'];
	$email=$_REQUEST['email'];
	$phone=$_REQUEST['phone'];
	$password=$_REQUEST['pass'];
	$utype="client";
	
	// Check if image is uploaded
    if(isset($_FILES['uimage']) && $_FILES['uimage']['error'] == 0) {
        $uimage = $_FILES['uimage']['name'];
        $temp_name = $_FILES['uimage']['tmp_name'];
        $imagePath = "images/profile_pic/".$uimage;
    } else {
        // No image or error uploading, use default
        $uimage = "default.png";
        $imagePath = "images/profile_pic/default.png";
    }
	
	
	$query = "SELECT * FROM user where uemail='$email'";
	$res=mysqli_query($con, $query);
	$user=mysqli_num_rows($res);
	
	if($user == 1) {
		$error = "<p class='alert alert-warning'>Un compte avec cet email existe déjà.</p> ";
	
	}else{
		if(!empty($name) && !empty($firstname) && !empty($email) && !empty($phone) && !empty($password)) {
			$sql="INSERT INTO user (uname, firstname, uemail, uphone, upass, utype, uimage) VALUES ('$name', '$firstname', '$email', '$phone', '$password', '$utype', '$uimage')";
			$result=mysqli_query($con, $sql);
			
			// Move uploaded file if it exists
			if(isset($_FILES['uimage']) && $_FILES['uimage']['error'] == 0) {
				move_uploaded_file($temp_name,"images/profile_pic/$uimage");
			}

			if($result) {
			   $msg = "<p class='alert alert-success'>Inscription réussie.</p> ";
			}else{
			   $error = "<p class='alert alert-warning'>Inscription échouée.</p> ";
			}
		}else{
			$error = "<p class='alert alert-warning'>Veuillez remplir tous les champs.</p>";
		}
	}
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
	
	        <!--	Banner start  --->
	        <div class="banner-full-row page-banner" style="background-image:url('images/breadcrumb.jpg');">
	            <div class="container">
	                <div class="row">
	                    <div class="col-md-6">
	                        <h2 class="page-name float-left text-white text-uppercase mt-1 mb-0"><b>Inscription</b></h2>
	                    </div>
	                    <div class="col-md-6">
	                        <nav aria-label="breadcrumb" class="float-left float-md-right">
	                            <ol class="breadcrumb bg-transparent m-0 p-0">
	                                <li class="breadcrumb-item text-white"><a href="home.php">Page d'accueil</a></li>
	                                <li class="breadcrumb-item active">Inscription</li>
	                            </ol>
	                        </nav>
	                    </div>
	                </div>
	            </div>
	        </div>
	         <!--	Banner end  --->



	        <div class="page-wrappers login-body full-row bg-gray">
	            <div class="login-wrapper">
	            	<div class="container">
	                	<div class="loginbox">
	                        <div class="login-right">
								<div class="login-right-wrap">
									<h1>Inscription</h1>
									<p class="account-subtitle">Accès à notre dashboard</p>
									<?php echo $error; ?><?php echo $msg; ?>
									<!-- Form -->
									<form method="post" enctype="multipart/form-data">
										<div class="form-group">
											<input type="text"  name="name" class="form-control" placeholder="Nom*">
										</div>
										<div class="form-group">
											<input type="text"  name="firstname" class="form-control" placeholder="Prénom*">
										</div>
										<div class="form-group">
											<input type="email"  name="email" class="form-control" placeholder="E-mail*">
										</div>
										<div class="form-group">
											<input type="text"  name="phone" class="form-control" placeholder="Téléphone*" maxlength="10">
										</div>
										<div class="form-group">
											<input type="text" name="pass"  class="form-control" placeholder="Mot de passe*">
										</div>

										<div class="form-group">
											<label class="col-form-label"><b>Ajouter une photo de profil</b></label>
											<input class="form-control" name="uimage" type="file">
										</div>

										<button class="btn btn-primary d-block mx-auto" name="register" value="Register" type="submit">Inscription</button>

									</form>

									<div class="login-or">
										<span class="or-line"></span>
										<span class="span-or">ou</span>
									</div>

									<div class="text-center dont-have">Vous avez déjà un compte ? <a href="login.php">Se connecter</a></div>

								</div>
	                        </div>
	                    </div>
	                </div>
	            </div>
	        </div>
		<!--	login  -->
	
	
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