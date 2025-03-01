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
								<?php  if(isset($_SESSION['uemail']))
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
                            <nav class="navbar navbar-expand-lg navbar-light p-0"> <a class="navbar-brand position-relative" href="index.php"><img class="nav-logo" src="images/logo/logo.png" height="60px" alt=""></a>
                                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"> <span class="navbar-toggler-icon"></span> </button>
                                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                    <ul class="navbar-nav mr-auto">
										<li class="nav-item"> <a class="nav-link" href="about.php">À propos</a> </li>
																				
										<li class="nav-item"> <a class="nav-link" href="property.php">Propriétés</a> </li>
                                        
										<li class="nav-item"> <a class="nav-link" href="agent.php">Agent</a> </li>
										
										<?php  if(isset($_SESSION['uemail']))
										{ ?>
										<li class="nav-item dropdown">
											<a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Mon compte</a>
											<ul class="dropdown-menu">
												<li class="nav-item"> <a class="nav-link" href="profile.php">Profil</a> </li>
												<li class="nav-item"> <a class="nav-link" href="request.php">Mes RDVs</a> </li>
												<li class="nav-item"> <a class="nav-link" href="feature.php">Paiment</a> </li>
												<li class="nav-item"> <a class="nav-link" href="logout.php">Déconnexion</a> </li>	
											</ul>
                                        </li>
										<?php } else { ?>
										<li class="nav-item"> <a class="nav-link" href="login.php">Connexion/Inscription</a> </li>
										<?php } ?>
										
                                    </ul>
                                    
									
									<a class="btn btn-primary d-none d-xl-block" href="prendre_rdv.php">Prendre RDV</a> 
                                </div>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </header>