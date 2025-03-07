<?php 
session_start();
include("config.php");
$error="";
$msg="";
if(isset($_REQUEST['login']))
{
	$email=$_REQUEST['email'];
	$pass=$_REQUEST['pass'];
	
	
	if(!empty($email) && !empty($pass))
	{
		$sql = "SELECT * FROM user where uemail='$email' && upass='$pass'";
		$result=mysqli_query($con, $sql);
		$row=mysqli_fetch_array($result);
		   if($row){
			   
				$_SESSION['uid']=$row['uid'];
				$_SESSION['uemail']=$email;
				$_SESSION['utype']=$row['utype'];
				header("location:index.php");
				
		   }
		   else{
			   $error = "<p class='alert alert-warning'>Login failed.</p> ";
		   }
	}else{
		$error = "<p class='alert alert-warning'>Please fill out all the fields.</p>";
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
	                        <h2 class="page-name float-left text-white text-uppercase mt-1 mb-0"><b>Login</b></h2>
	                    </div>
	                    <div class="col-md-6">
	                        <nav aria-label="breadcrumb" class="float-left float-md-right">
	                            <ol class="breadcrumb bg-transparent m-0 p-0">
	                                <li class="breadcrumb-item text-white"><a href="home.php">Page d'accueil</a></li>
	                                <li class="breadcrumb-item active">Login</li>
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
									<p class="account-subtitle">Accès à notre tableau de bord</p>
									<?php echo $error; ?><?php echo $msg; ?>
									<!-- Form -->
									<form method="post">
										<div class="form-group">
											<input type="email"  name="email" class="form-control" placeholder="Votre email">
										</div>
										<div class="form-group">
											<input type="password" name="pass"  class="form-control" placeholder="Votre mot de passe">
										</div>
										
											<button class="btn btn-primary d-block mx-auto" name="login" value="Login" type="submit">Connexion</button>
										
									</form>
									
									<div class="login-or">
										<span class="or-line"></span>
										<span class="span-or">or</span>
									</div>
									
									<div class="text-center dont-have">Vous n'avez pas de compte ? <a href="register.php">Inscription</a></div>
									
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