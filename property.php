<?php 
    ini_set('session.cache_limiter','public');
    session_cache_limiter(false);
    session_start();
    include("config.php");
    ///code	
?>


<!DOCTYPE html>
<html>
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <!--meta http-equiv="X-UA-Compatible" content="IE=edge"-->
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Meta Tags -->
        <!--meta http-equiv="X-UA-Compatible" content="IE=edge"-->
        <!--meta name="description" content="Homex template"-->
        <!--meta name="keywords" content=""-->
        <!--meta name="author" content="Unicoder"-->
        <link rel="shortcut icon" href="images/favicon.ico">

        <!--	Fonts   -->
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">

        <!--	Css Links  -->
        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="css/bootstrap-slider.css">
        <link rel="stylesheet" type="text/css" href="css/jquery-ui.css">
        <link rel="stylesheet" type="text/css" href="css/layerslider.css">
        <link rel="stylesheet" type="text/css" href="css/color.css" id="color-change">
        <link rel="stylesheet" type="text/css" href="css/owl.carousel.min.css">
        <link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="fonts/flaticon/flaticon.css">
        <link rel="stylesheet" type="text/css" href="css/style.css">

        <!--	Title   -->
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
                                <h2 class="page-name float-left text-white text-uppercase mt-1 mb-0"><b>Propriétés</b></h2>
                            </div>
                            <div class="col-md-6">
                                <nav aria-label="breadcrumb" class="float-left float-md-right">
                                    <ol class="breadcrumb bg-transparent m-0 p-0">
                                        <li class="breadcrumb-item text-white"><a href="home.php">Page d'accueil</a></li>
                                        <li class="breadcrumb-item active">Propriétés</li>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
                 <!--	Banner end  --->

                <!--	Property grid start  -->
                <div class="full-row">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <p>Here is where the search function is going to go</p>
                                <form method="get" action="" id="propertySearchForm">
                                    <div class="row">
                                        <!-- <div class="col-md-6 col-lg-3">
                                            <?php include("include/filterproperty.php");?>
                                        </div>
                                        <div class="col-md-6 col-lg-3">
                                            <?php include("include/filteragent.php");?>
                                        </div>
                                        <div class="col-md-6 col-lg-3">
                                            <?php include("include/filterlocation.php");?>
                                        </div> -->
                                        <div class="col-md-6 col-lg-6">
                                            <div class="form-group">
                                                <!-- <label for="searchKeyword">Recherche par mot-clé</label> -->
                                                <input type="text" class="form-control" id="searchKeyword" name="keyword" 
                                                    placeholder="Recherche"
                                                    value="<?php echo isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : ''; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-lg-2">
                                            <div class="form-group">
                                                <button type="submit" name="filter" class="btn btn-primary w-100">Rechercher</button>
                                                <p>search button pressed, the value is:</p>
                                                <?php
                                                    if(isset($_GET["keyword"])) {
                                                        echo "true";
                                                        echo $_GET['keyword'];
                                                    } else {
                                                        echo "false";
                                                    }
                                                    ?>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <!--  Showing properties  -->
		        			<div class="col-md-12">
                                <div class="row">
                                <?php
                                    // Build the query with filters
                                    // $where_conditions = array("(property.status = 'À vendre' OR property.status = 'À louer')");

                                    // if(isset($_GET['type']) && !empty($_GET['type'])) {
                                    //     $type = mysqli_real_escape_string($con, $_GET['type']);
                                    //     $where_conditions[] = "property.type = '$type'";
                                    // }

                                    // if(isset($_GET['agent']) && !empty($_GET['agent'])) {
                                    //     $agent = mysqli_real_escape_string($con, $_GET['agent']);
                                    //     $where_conditions[] = "property.agentid = '$agent'";
                                    // }

                                    // if(isset($_GET['city']) && !empty($_GET['city'])) {
                                    //     $city = mysqli_real_escape_string($con, $_GET['city']);
                                    //     $where_conditions[] = "property.city = '$city'";
                                    // }
                                    // if(isset($_GET['status']) && !empty($_GET['status'])) {
                                    //     $status = mysqli_real_escape_string($con, $_GET['status']);
                                    //     $where_conditions[] = "property.status = '$status'";
                                    // }
                                    if(isset($_GET["filter"]) && $_GET["filter"]) {
                                        if(isset($_GET['keyword']) && !empty($_GET['keyword'])) {
                                            $keyword = mysqli_real_escape_string($con, $_GET['keyword']);
                                            $where_conditions = array("(user.uname LIKE '%$keyword%' OR property.title LIKE '%$keyword%' OR property.location LIKE '%$keyword%' OR property.city LIKE '%$keyword%')");
                                        }

                                        if(!empty($where_conditions)) {
                                            $where_clause = implode(" AND ", $where_conditions);
                                            $query = mysqli_query($con, "SELECT property.*, user.uname, user.utype, user.uimage FROM `property`, `user` 
                                                                   WHERE property.agentid = user.uid
                                                                   AND (property.status = 'À vendre' OR property.status = 'À louer')
                                                                   AND $where_clause
                                                                   ORDER BY property.date DESC");
                                        }

                                        // $property_count = mysqli_num_rows($query);
                                    
                                    }else{
                                        $query = mysqli_query($con, "SELECT property.*, user.uname, user.utype, user.uimage FROM `property`, `user` 
                                                                WHERE property.agentid = user.uid 
                                                                AND (property.status = 'À vendre' OR property.status = 'À louer')");
                                    }

                                    while($row = mysqli_fetch_array($query)) {
                                    ?>

                                    <div class="col-md-4">
                                        <div class="featured-thumb hover-zoomer mb-4">
                                            <div class="overlay-black overflow-hidden position-relative"> <img src="admin/property/<?php echo $row['pimage1'];?>">

                                                <div class="sale bg-secondary text-white"><?php echo $row['status'];?></div>
                                                <div class="price text-light-primary text-capitalize">€<?php echo $row['price'];?> <span class="text-white"><?php echo $row['area'];?> m2</span></div>

                                            </div>
                                            <div class="featured-thumb-data shadow-one">
                                                <div class="p-4">
                                                    <h5 class="text-secondary hover-text-primary mb-2 text-capitalize"><a href="propertydetail.php?pid=<?php echo $row['pid'];?>"><?php echo $row['title'];?></a></h5>
                                                    <span class="location text-capitalize"><i class="fas fa-map-marker-alt text-primary"></i> <?php echo $row['location'];?>, <?php echo $row['city'];?></span> </div>
                                                <div class="px-4 pb-4 d-inline-block w-100">
                                                    <div class="float-left text-capitalize"><i class="fas fa-user text-primary mr-1"></i>Agent : <?php echo $row['uname'];?></div>
                                                    <!-- <div class="float-right"><i class="far fa-calendar-alt text-primary mr-1"></i> 6 Months Ago</div> -->
                                                    <div class="float-right">
                                                        <i class="far fa-calendar-alt text-primary mr-1"></i>
                                                        <?php
                                                            $postDate = new DateTime($row['date']);
                                                            $currentDate = new DateTime(date('Y-m-d H:i:s'));
                                                            $interval = $postDate->diff($currentDate);
                                                                                            
                                                            if($interval->y > 0) {
                                                                echo $interval->y . ($interval->y == 1 ? ' year' : ' years') . ' ago';
                                                            } elseif($interval->m > 0) {
                                                                echo $interval->m . ($interval->m == 1 ? ' month' : ' months') . ' ago';
                                                            } else {
                                                                echo $interval->d . ($interval->d == 1 ? ' day' : ' days') . ' ago';
                                                            }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>              
                        </div>
                    </div>
                </div>
                <!--	Property grid end  -->
                                                
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