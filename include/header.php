<header id="header" class="transparent-header-modern fixed-header-bg-white w-100">
    <div class="top-header bg-header">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <ul class="top-contact list-text-white  d-table">
                        <li><a href="#"><i class="fas fa-phone-alt text-light-primary mr-1"></i>(012) 345 678 102</a></li>
                        <li><a href="#"><i class="fas fa-envelope text-light-primary mr-1"></i>office@example.com</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <div class="top-contact float-right">
                        <ul class="list-text-white d-table">
						<li><i class="fas fa-user text-light-primary mr-1"></i>
						<?php  if(isset($_SESSION['uid']))
						{ ?>
						<a href="logout.php">Déconnexion</a>&nbsp;&nbsp;<?php } else { ?>
						<a href="login.php">Connexion</a>&nbsp;&nbsp;
						| </li>
						<li><i class="fas fa-user text-light-primary mr-1"></i><a href="register.php"> Inscription</li>
						<?php } ?>
						</ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="main-nav secondary-nav hover-primary-nav py-2">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <nav class="navbar navbar-expand-lg navbar-light p-0">
                        <?php
                            // Set logo destination based on user type
                            $logoDestination = "index.php"; // Default for visitors
                            
                            if(isset($_SESSION['uid'])) {
                                if($_SESSION['isAdmin'] || $_SESSION['isAgent']) {
                                    $logoDestination = "about.php";
                                }
                            }
                        ?>
                        <a class="navbar-brand position-relative" href="<?php echo $logoDestination; ?>"><img class="nav-logo" src="images/logo/logo.png" height="60px" alt=""></a>
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"> <span class="navbar-toggler-icon"></span> </button>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav mr-auto">
                                										
								<?php  if(isset($_SESSION['uid'])) { 
                                    // User is logged in
                                    if($_SESSION['isAdmin']) { ?>
                                        <!-- Admin specific menu items -->
                                        <li class="nav-item"> <a class="nav-link" href="admin_property.php">Gestion Propriétés</a> </li> <!-- admin/my_rdv.php -->
                                        <li class="nav-item"> <a class="nav-link" href="admin_user.php">Gestion Utilisateurs</a> </li>
                                        <li class="nav-item"> <a class="nav-link" href="admin_agent.php">Gestion Agents Immobiliers</a> </li>
                                        <li class="nav-item"> <a class="nav-link" href="profile.php">Mon profil</a> </li>
                                    
                                        <?php } elseif($_SESSION['isAgent']) { ?>
                                        <!-- Agent specific menu items -->
                                        <li class="nav-item"> <a class="nav-link" href="property.php">Mes propriétés</a> </li>
                                        <li class="nav-item dropdown">
									        <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Mon agenda</a>
									        <ul class="dropdown-menu">
									        	<li class="nav-item"> <a class="nav-link" href="">Disponibilités</a> </li>
									        	<li class="nav-item"> <a class="nav-link" href="">Calendrier</a> </li>
									        	<li class="nav-item"> <a class="nav-link" href="appointments.php">Mes RDVs</a> </li>	
									        	<li class="nav-item"> <a class="nav-link" href="appdetails.php">RDV details</a> </li>	
									        </ul>
                                        </li>
                                        <li class="nav-item"> <a class="nav-link" href="">Mes conversations</a> </li>
                                        <li class="nav-item dropdown">
									        <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Mon compte</a>
									        <ul class="dropdown-menu">
									        	<li class="nav-item"> <a class="nav-link" href="profile.php">Profil</a> </li>
									        	<li class="nav-item">
    <a class="nav-link" href="admin_edit_cv.php?cv_id=<?php echo $_SESSION['uid']; ?>">Mon CV</a>
</li>

									        	<li class="nav-item"> <a class="nav-link" href="logout.php">Déconnexion</a> </li>	
									        </ul>
                                        </li>

                                        <?php } else { ?>
                                        <!-- Client specific menu item -->
								        <li class="nav-item"> <a class="nav-link" href="about.php">À propos</a> </li>
                                        <li class="nav-item"> <a class="nav-link" href="property.php">Propriétés</a> </li>
								        <li class="nav-item dropdown">
								        	<a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Mon compte</a>
								        	<ul class="dropdown-menu">
								        		<li class="nav-item"> <a class="nav-link" href="profile.php">Profil</a> </li>
								        		<li class="nav-item"> <a class="nav-link" href="appointments.php">Mes RDVs</a> </li>
								        		<li class="nav-item"> <a class="nav-link" href="">Mes conversations</a> </li>
								        		<li class="nav-item"> <a class="nav-link" href="">Paiment</a> </li>
								        		<li class="nav-item"> <a class="nav-link" href="logout.php">Déconnexion</a> </li>	
								        	</ul>
                                        </li>
                                        <?php } ?>
								    <?php } else { ?>
								    <li class="nav-item"> <a class="nav-link" href="about.php">À propos</a> </li>
                                    <li class="nav-item"> <a class="nav-link" href="property.php">Propriétés</a> </li>
								    <li class="nav-item"> <a class="nav-link" href="login.php">Connexion</a> </li>
								<?php } ?>
                            </ul>
							
                            <?php if(isset($_SESSION['uid'])) { 
                                // User is logged in
                                if($_SESSION['isAgent']) { ?>
                                <!-- Agent specific menu items -->
							    <a class="btn btn-primary d-none d-xl-block" href="">Consulter l'agenda</a>

                                <?php } elseif(!$_SESSION['isAdmin']) {?>
                                <!-- Client specific menu items -->
                                <a class="btn btn-primary d-none d-xl-block" href="prendre_rdv.php">Prendre RDV</a>
                                <?php } ?>
                                
                            <?php } else { ?>
							<a class="btn btn-primary d-none d-xl-block" href="prendre_rdv.php">Prendre RDV</a>
                            <?php } ?>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</header>