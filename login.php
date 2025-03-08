<?php 
session_start();
include("config.php");
$error="";
$msg="";
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_REQUEST['login']))
{
	$email=$_REQUEST['email'];
	$password=$_REQUEST['password'];
	
	
	if(!empty($email) && !empty($password))
	{
		$sql = "SELECT * FROM user where uemail='$email' && upass='$password'";
		$result=mysqli_query($con, $sql);
		$user=mysqli_fetch_array($result);
		
		if($user){
			   
			$_SESSION['uid']=$user['uid'];
			$_SESSION['uemail']=$email;
			$_SESSION['utype']=$user['utype'];
			
			$isAdmin = ($user['utype'] == 'admin');
			$isAgent = ($user['utype'] == 'agent');
			
			// Store these in session for use across the site
			$_SESSION['isAdmin'] = $isAdmin;
			$_SESSION['isAgent'] = $isAgent;
			
			// Redirect based on user type
			if($isAdmin || $isAgent) {
				header("location:about.php");
			} else {
				header("location:index.php");
			}
				
		}else{
			$error = "<p class='alert alert-warning'>Connexion échouée. Email ou mot de passe invalide.</p> ";
		}
	}else{
		$error = "<p class='alert alert-warning'>Veuillez remplir tous les champs.</p>";
	}
}
?>


<!DOCTYPE html>
<html>
<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<!--meta http-equiv="X-UA-Compatible" content="IE=edge"-->
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	
	<!-- Meta Tags -->
	<!-- meta http-equiv="X-UA-Compatible" content="IE=edge" -->
	<link rel="shortcut icon" href="images/favicon.ico">
	
	<!--	Fonts   -->
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
	
	<!--	Css Links   -->
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
	
	<!--	Title   -->
	<title>Omnes Immobilier</title>
</head>

<body>
	<div id="page-wrapper">
	    <div class="row"> 
	        <!--	Header start  -->
			<?php include("include/header.php");?>
	        <!--	Header end  -->
	
	        <!--	Banner start   --->
	        <div class="banner-full-row page-banner" style="background-image:url('images/breadcrumb.jpg');">
	            <div class="container">
	                <div class="row">
	                    <div class="col-md-6">
	                        <h2 class="page-name float-left text-white text-uppercase mt-1 mb-0"><b>Connexion</b></h2>
	                    </div>
	                    <div class="col-md-6">
	                        <nav aria-label="breadcrumb" class="float-left float-md-right">
	                            <ol class="breadcrumb bg-transparent m-0 p-0">
	                                <li class="breadcrumb-item text-white"><a href="home.php">Accueil</a></li>
	                                <li class="breadcrumb-item active">Connexion</li>
	                            </ol>
	                        </nav>
	                    </div>
	                </div>
	            </div>
	        </div>
	        <!--   Banner end   --->
			 
			<!--   Login start  -->
	        <div class="page-wrappers login-body full-row bg-gray">
	            <div class="login-wrapper">
	            	<div class="container">
	                	<div class="loginbox">
	                        <div class="login-right">
								<div class="login-right-wrap">
									<h1>Connexion</h1>
									<p class="account-subtitle">Accès à notre dashboard</p>
									<?php echo $error; ?><?php echo $msg; ?>
									<!-- Form -->
									<form method="post">
										<div class="form-group">
											<input type="email" name="email" class="form-control" placeholder="Votre email" required>
										</div>
										<div class="form-group">
											<input type="password" name="password" class="form-control" placeholder="Votre mot de passe" required>
										</div>
										<button class="btn btn-primary d-block mx-auto" name="login" type="submit">Connexion</button>
									</form>
									
									<div class="login-or">
										<span class="or-line"></span>
										<span class="span-or">ou</span>
									</div>
									<div class="text-center dont-have">Pas encore de compte ? <a href="register.php">Inscrivez-vous</a></div>
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