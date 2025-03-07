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
        <!--meta http-equiv="X-UA-Compatible" content="IE=edge"-->
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Meta Tags -->
        <!--meta http-equiv="X-UA-Compatible" content="IE=edge"-->
        <link rel="shortcut icon" href="images/favicon.ico">

        <!--	Fonts  -->
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

        <!-- Title -->
        <title>Omnes Immobilier</title>
    </head>
    
    <body>
        <div id="page-wrapper">
            <div class="row"> 
                <!--	Header start  -->
        		<?php include("include/header.php");?>
                <!--	Header end  -->

                <!--	Banner start   -->
                <div class="overlay-black w-100 slider-banner1 position-relative" style="background-image: url('images/banner/04.jpg'); background-size: cover; background-position: center center; background-repeat: no-repeat;">
                    <div class="container h-100">
                        <div class="row h-100 align-items-center">
                            <div class="col-lg-12">
                                <div class="text-white">
                                    <h1 class="mb-4"><span class="text-light-primary">Trouvez</span><br>
                                        la maison de vos rêves</h1>
                                    <form method="post" action="propertysearchresults.php" id="propertySearchForm">
                                        <div class="row">
                                            <div class="col-md-6 col-lg-3">
        		                                <?php include("include/filterproperty.php");?>
                                            </div>
                                            <div class="col-md-6 col-lg-2">
        		                                <?php include("include/filteragent.php");?>
                                            </div>
                                            <div class="col-md-8 col-lg-5">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" name="city" placeholder="Ville / Commune" required>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-lg-2">
                                                <div class="form-group">
                                                    <button type="submit" name="filter" class="btn btn-primary w-100">Rechercher</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--    Banner end   -->
                
                <!--	Recent Properties  -->
                <div class="full-row">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <h2 class="text-secondary double-down-line text-center mb-4">Propriétés récentes</h2>
                            </div>
                            <div class="col-md-12">
                                <div class="tab-content mt-4" id="pills-tabContent">
                                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home">
                                        <div class="row">

        									<?php $query=mysqli_query($con,"SELECT property.*, user.uname,user.utype,user.uimage FROM `property`,`user` WHERE property.uid=user.uid ORDER BY date DESC LIMIT 9");
        										while($row=mysqli_fetch_array($query))
        										{
        									?>

                                            <div class="col-md-6 col-lg-4">
                                                <div class="featured-thumb hover-zoomer mb-4">
                                                    <div class="overlay-black overflow-hidden position-relative"> <img src="admin/property/<?php echo $row['18'];?>" alt="pimage">
                                                        <div class="featured bg-primary text-white">New</div>
                                                        <div class="sale bg-secondary text-white text-capitalize">For <?php echo $row['5'];?></div>
                                                        <div class="price text-primary"><b>$<?php echo $row['13'];?> </b><span class="text-white"><?php echo $row['12'];?> Sqft</span></div>
                                                    </div>
                                                    <div class="featured-thumb-data shadow-one">
                                                        <div class="p-3">
                                                            <h5 class="text-secondary hover-text-primary mb-2 text-capitalize"><a href="propertydetail.php?pid=<?php echo $row['0'];?>"><?php echo $row['1'];?></a></h5>
                                                            <span class="location text-capitalize"><i class="fas fa-map-marker-alt text-primary"></i> <?php echo $row['14'];?></span> </div>
                                                        <div class="bg-gray quantity px-4 pt-4">
                                                            <ul>
                                                                <li><span><?php echo $row['12'];?></span> Sqft</li>
                                                                <li><span><?php echo $row['6'];?></span> Beds</li>
                                                                <li><span><?php echo $row['7'];?></span> Baths</li>
                                                                <li><span><?php echo $row['9'];?></span> Kitchen</li>
                                                                <li><span><?php echo $row['8'];?></span> Balcony</li>

                                                            </ul>
                                                        </div>
                                                        <div class="p-4 d-inline-block w-100">
                                                            <div class="float-left text-capitalize"><i class="fas fa-user text-primary mr-1"></i>By : <?php echo $row['uname'];?></div>
                                                            <div class="float-right"><i class="far fa-calendar-alt text-primary mr-1"></i> 6 Months Ago</div>
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
                    </div>
                </div>
        		<!--	Recent Properties end  -->  
        		                                
                <!--	Achievement
                ============================================================-->
                <div class="full-row overlay-secondary" style="background-image: url('images/counterbg.jpg'); background-size: cover; background-position: center center; background-repeat: no-repeat;">
                    <div class="container">
                        <div class="fact-counter">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="count wow text-center  mb-sm-50" data-wow-duration="300ms"> <i class="flaticon-house flat-large text-white" aria-hidden="true"></i>
        								<?php
        										$query=mysqli_query($con,"SELECT count(pid) FROM property");
        											while($row=mysqli_fetch_array($query))
        												{
        										?>
                                        <div class="count-num text-primary my-4" data-speed="3000" data-stop="<?php 
        												$total = $row[0];
        												echo $total;?>">0</div>
        								<?php } ?>
                                        <div class="text-white h5">Property Available</div>
                                    </div>
                                </div>
        						<div class="col-md-3">
                                    <div class="count wow text-center  mb-sm-50" data-wow-duration="300ms"> <i class="flaticon-house flat-large text-white" aria-hidden="true"></i>
        								<?php
        										$query=mysqli_query($con,"SELECT count(pid) FROM property where stype='sale'");
        											while($row=mysqli_fetch_array($query))
        												{
        										?>
                                        <div class="count-num text-primary my-4" data-speed="3000" data-stop="<?php 
        												$total = $row[0];
        												echo $total;?>">0</div>
        								<?php } ?>
                                        <div class="text-white h5">Sale Property Available</div>
                                    </div>
                                </div>
        						<div class="col-md-3">
                                    <div class="count wow text-center  mb-sm-50" data-wow-duration="300ms"> <i class="flaticon-house flat-large text-white" aria-hidden="true"></i>
        								<?php
        										$query=mysqli_query($con,"SELECT count(pid) FROM property where stype='rent'");
        											while($row=mysqli_fetch_array($query))
        												{
        										?>
                                        <div class="count-num text-primary my-4" data-speed="3000" data-stop="<?php 
        												$total = $row[0];
        												echo $total;?>">0</div>
        								<?php } ?>
                                        <div class="text-white h5">Rent Property Available</div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="count wow text-center  mb-sm-50" data-wow-duration="300ms"> <i class="flaticon-man flat-large text-white" aria-hidden="true"></i>
                                        <?php
        										$query=mysqli_query($con,"SELECT count(uid) FROM user");
        											while($row=mysqli_fetch_array($query))
        												{
        										?>
                                        <div class="count-num text-primary my-4" data-speed="3000" data-stop="<?php 
        												$total = $row[0];
        												echo $total;?>">0</div>
        								<?php } ?>
                                        <div class="text-white h5">Registered Users</div>
                                    </div>
                                </div>
                                                        
                            </div>
                        </div>
                    </div>
                </div>
                                                        
                <!--	Popular Place -->
                <div class="full-row bg-gray">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-12">
                                <h2 class="text-secondary double-down-line text-center mb-5">Popular Places</h2></div>
                        </div>
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-md-6 col-lg-3 pb-1">
                                    <div class="overflow-hidden position-relative overlay-secondary hover-zoomer mx-n13 z-index-9"> <img src="images/thumbnail4/1.jpg" alt="">
                                        <div class="text-white xy-center z-index-9 position-absolute text-center w-100">
        									<?php
        										$query=mysqli_query($con,"SELECT count(state), property.* FROM property where state='gujarat'");
        											while($row=mysqli_fetch_array($query))
        												{
        										?>
                                            <h4 class="hover-text-primary text-capitalize"><a href="stateproperty.php?id=<?php echo $row['17']?>"><?php echo $row['state'];?></a></h4>
                                            <span><?php 
        												$total = $row[0];
        												echo $total;?> Properties Listed</span> </div>
        									<?php } ?>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-3 pb-1">
                                    <div class="overflow-hidden position-relative overlay-secondary hover-zoomer mx-n13 z-index-9"> <img src="images/thumbnail4/2.jpg" alt="">
                                        <div class="text-white xy-center z-index-9 position-absolute text-center w-100">
        									<?php
        										$query=mysqli_query($con,"SELECT count(state), property.* FROM property where state='mumbai'");
        											while($row=mysqli_fetch_array($query))
        												{
        										?>
                                            <h4 class="hover-text-primary text-capitalize"><a href="stateproperty.php?id=<?php echo $row['17']?>"><?php echo $row['state'];?></a></h4>
                                            <span><?php 
        												$total = $row[0];
        												echo $total;?> Properties Listed</span> </div>
        									<?php } ?>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-3 pb-1">
                                    <div class="overflow-hidden position-relative overlay-secondary hover-zoomer mx-n13 z-index-9"> <img src="images/thumbnail4/3.jpg" alt="">
                                        <div class="text-white xy-center z-index-9 position-absolute text-center w-100">
                                            <?php
        										$query=mysqli_query($con,"SELECT count(state), property.* FROM property where state='banglore'");
        											while($row=mysqli_fetch_array($query))
        												{
        										?>
                                            <h4 class="hover-text-primary text-capitalize"><a href="stateproperty.php?id=<?php echo $row['17']?>"><?php echo $row['state'];?></a></h4>
                                            <span><?php 
        												$total = $row[0];
        												echo $total;?> Properties Listed</span> </div>
        									<?php } ?>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-3 pb-1">
                                    <div class="overflow-hidden position-relative overlay-secondary hover-zoomer mx-n13 z-index-9"> <img src="images/thumbnail4/4.jpg" alt="">
                                        <div class="text-white xy-center z-index-9 position-absolute text-center w-100">
                                            <?php
        										$query=mysqli_query($con,"SELECT count(state), property.* FROM property where state='rajasthan'");
        											while($row=mysqli_fetch_array($query))
        												{
        										?>
                                            <h4 class="hover-text-primary text-capitalize"><a href="stateproperty.php?id=<?php echo $row['17']?>"><?php echo $row['state'];?></a></h4>
                                            <span><?php 
        												$total = $row[0];
        												echo $total;?> Properties Listed</span> </div>
        									<?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--	Popular Places -->
                                                        
        		<!--	Testonomial -->
        		<div class="full-row">
                    <div class="container">
                        <div class="row">
        					<div class="col-lg-12">
        						<div class="content-sidebar p-4">
        							<div class="mb-3 col-lg-12">
        								<h4 class="double-down-line-left text-secondary position-relative pb-4 mb-4">Testimonial</h4>
        									<div class="recent-review owl-carousel owl-dots-gray owl-dots-hover-primary">
                                                        
        										<?php

        												$query=mysqli_query($con,"select feedback.*, user.* from feedback,user where feedback.uid=user.uid and feedback.status='1'");
        												while($row=mysqli_fetch_array($query))
        													{
        										?>
        										<div class="item">
        											<div class="p-4 bg-primary down-angle-white position-relative">
        												<p class="text-white"><i class="fas fa-quote-left mr-2 text-white"></i><?php echo $row['2']; ?>. <i class="fas fa-quote-right mr-2 text-white"></i></p>
        											</div>
        											<div class="p-2 mt-4">
        												<span class="text-primary d-table text-capitalize"><?php echo $row['uname']; ?></span> <span class="text-capitalize"><?php echo $row['utype']; ?></span>
        											</div>
        										</div>
        										<?php }  ?>
                                                            
        									</div>
        							</div>
        						 </div>
        					</div>
        				</div>
        			</div>
        		</div>
        		<!--	Testonomial -->
                                                            
                                                            
                <!--	Footer   start-->
        		<?php include("include/footer.php");?>
        		<!--	Footer   start-->
                                                            
                                                            
                <!-- Scroll to top --> 
                <a href="#" class="bg-primary text-white hover-text-secondary" id="scroll"><i class="fas fa-angle-up"></i></a> 
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
        <script src="js/YouTubePopUp.jquery.js"></script> 
        <script src="js/validate.js"></script> 
        <script src="js/jquery.cookie.js"></script> 
        <script src="js/custom.js"></script>
    </body>

</html>