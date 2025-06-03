<?php
require_once("identifier.php");
?>
        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="acceuil.php">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-user-md "></i>
                </div>
                <div class="sidebar-brand-text mx-3">UATG</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="acceuil.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Tableau de bord</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Accueil
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                    <a class="nav-link" href="nouveau_dossier.php">
                        <i class="fas fa-folder text-warning"></i>
                        <span>Nouveau dossier</span></a>
            </li>

            <!-- Nav Item - Utilities Collapse Menu -->
            
            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
        

            <!-- Nav Item - Pages Collapse Menu -->
           <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseForm" aria-expanded="true"
                aria-controls="collapseForm">
                <i class="fas fa-users text-info"></i>
                <span>Patients</span>
                </a>
                <div id="collapseForm" class="collapse" aria-labelledby="headingForm" data-parent="#accordionSidebar">
                <div class="bg-white py-3 collapse-inner rounded">
                    <h6 class="collapse-header">Patients</h6>
                    <a class="collapse-item" href="Patient.php">Liste des Patient</a>
                </div>
                </div>
            </li>

            <!-- Nav Item - Charts -->
            <?php if($_SESSION['user']['Type_user']=='TECHNICIEN') { ?>
                <li class="nav-item">
                    <a class="nav-link" href="admin.php">
                        <i class="fas fa-fw fa-user"></i>
                        <span>Adiminstrateur</span></a>
                </li>
            <?php }?>


            <!-- Nav Item - Tables -->
         
            <!-- Sidebar Message -->
        </ul>