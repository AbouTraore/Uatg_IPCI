<?php
require_once("identifier.php");
?>
        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-success sidebar sidebar-dark accordion" id="accordionSidebar">

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
                    <a class="nav-link" href="Patient.php">
                        <i class="fas fa-clipboard-list"></i>
                        <span>Visite</span></a>
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