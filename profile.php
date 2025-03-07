<?php 
    ini_set('session.cache_limiter','public');
    session_cache_limiter(false);
    session_start();
    include("config.php");
    if(!isset($_SESSION['uemail']))
    {
    	header("location:login.php");
    }
    
    ////// code
    $error='';
    $msg='';
    if(isset($_POST['insert']))
    {
    	$name=$_POST['name'];
    	$phone=$_POST['phone'];
    
    	$content=$_POST['content'];
    
    	$uid=$_SESSION['uid'];
    
    	if(!empty($name) && !empty($phone) && !empty($content))
    	{
        
    		$sql="INSERT INTO feedback (uid,fdescription,status) VALUES ('$uid','$content','0')";
    		   $result=mysqli_query($con, $sql);
    		   if($result){
    			   $msg = "<p class='alert alert-success'>Feedback Send Successfully</p> ";
    		   }
    		   else{
    			   $error = "<p class='alert alert-warning'>Feedback Not Send Successfully</p> ";
    		   }
    	}else{
    		$error = "<p class='alert alert-warning'>Please Fill all the fields</p>";
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
                                $row=mysqli_fetch_array($query);
                                if($row) {
                            ?>
                            <div class="user-info text-center"> 
                                <img src="admin/user/<?php echo $row['6'];?>" alt="userimage" class="img-fluid mb-4" style="max-width: 200px; height: auto;">
                                <h4 class="text-capitalize mb-3"><?php echo $row['uname'];?></h4>
                                <div class="bg-light-primary py-2 px-3 d-inline-block rounded mb-4">
                                    <span class="text-capitalize"><?php echo $row['status'];?></span>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                        
                        <!-- Right Column - User Information -->
                        <div class="col-lg-7 col-md-12">
                            <div class="profile-details p-4 bg-light rounded">
                                <h4 class="mb-4">Information</h4>
                                <?php 
                                    if($row) {
                                ?>
                                <div class="font-18">
                                    <div class="row mb-3">
                                        <div class="col-md-4 font-weight-bold">Name:</div>
                                        <div class="col-md-8 text-capitalize"><?php echo $row['1'];?></div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4 font-weight-bold">Email:</div>
                                        <div class="col-md-8"><?php echo $row['2'];?></div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4 font-weight-bold">Phone:</div>
                                        <div class="col-md-8"><?php echo $row['3'];?></div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4 font-weight-bold">Role:</div>
                                        <div class="col-md-8 text-capitalize"><?php echo $row['5'];?></div>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <a href="edit-profile.php" class="btn btn-primary">Edit Profile</a>
                                </div>
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