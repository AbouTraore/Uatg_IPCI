<?php
// Inclusion des fichiers de sécurité et de connexion à la base de données
require_once("identifier.php");
require_once("connexion.php");

// Compteur patients : récupère le nombre total de patients inscrits
$nbPatients = 0;
try {
    $stmt = $pdo->query("SELECT COUNT(*) FROM patient");
    $nbPatients = $stmt->fetchColumn();
} catch (Exception $e) { $nbPatients = 0; }
// Compteur utilisateurs : récupère le nombre total d'utilisateurs
$nbUsers = 0;
try {
    $stmt = $pdo->query("SELECT COUNT(*) FROM user");
    $nbUsers = $stmt->fetchColumn();
} catch (Exception $e) { $nbUsers = 0; }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Métadonnées et liens vers les feuilles de style -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="../img/logo/logo.jpg" rel="icon">
    <title>UATG - Dashboard</title>

    <!-- Polices et styles principaux du template -->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">

    <style>
    /* Animations et styles personnalisés pour le dashboard */
    /* Animation apparition des cartes (fade-in + slide-up) */
    .card.dashboard-animated {
        opacity: 0;
        transform: translateY(30px);
        animation: fadeSlideUp 0.7s cubic-bezier(.4,1.4,.6,1) forwards;
    }
    @keyframes fadeSlideUp {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    /* Effet d'élévation et d'ombre au survol des cartes */
    .card.dashboard-animated:hover {
        box-shadow: 0 8px 32px rgba(16,185,129,0.18), 0 2px 8px rgba(0,0,0,0.10);
        transform: translateY(-6px) scale(1.03);
        transition: box-shadow 0.2s, transform 0.2s;
    }
    /* Animation de pulse sur les icônes des cartes */
    .dashboard-animated .dashboard-icon {
        animation: pulseIcon 1.6s infinite;
    }
    @keyframes pulseIcon {
        0%,100% { transform: scale(1); filter: brightness(1); }
        50% { transform: scale(1.18); filter: brightness(1.25); }
    }
    /* Animation de rebond sur les chiffres des compteurs */
    .dashboard-animated .dashboard-count {
        display: inline-block;
        animation: bounceCount 0.7s cubic-bezier(.4,1.4,.6,1);
    }
    @keyframes bounceCount {
        0% { transform: scale(1); }
        30% { transform: scale(1.25); }
        60% { transform: scale(0.95); }
        100% { transform: scale(1); }
    }
    /* Animation de remplissage progressif des barres de progression */
    .progress-bar.animated-bar {
        width: 0 !important;
        transition: width 1.2s cubic-bezier(.4,1.4,.6,1);
    }
    </style>
</head>

<body id="page-top">

    <!-- Wrapper principal de la page -->
    <div id="wrapper">
      <?php
           // Inclusion du menu latéral gauche
           require_once("menu_gauche.php");
       ?>

        <!-- Content Wrapper (zone principale) -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content (zone centrale) -->
            <div id="content">

                <!-- Barre de navigation supérieure -->
               <?php
                require_once("menu_haut.php")
               ?>

                <!-- Contenu principal de la page -->
                <div class="container-fluid">

                    <!-- Ligne de cartes statistiques -->
                    <div class="row">
                        <!-- Carte : Nombre de patients inscrits -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2 dashboard-animated">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Nombre de patients inscrits</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800 dashboard-count" id="patientsCount">0</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-users fa-2x text-info dashboard-icon"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Carte : Nombre d'utilisateurs -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2 dashboard-animated">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Nombre d'utilisateurs</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800 dashboard-count" id="usersCount">0</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-user-shield fa-2x text-primary dashboard-icon"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Carte : Nombre de visites (exemple statique) -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2 dashboard-animated">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Nomber de visite</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">400 </div>
                                        </div>
                                        <div class="col">
                                                    <div class="progress progress-sm mr-2">
                                                <div class="progress-bar bg-primary animated-bar" role="progressbar"
                                                            style="width: 60%" aria-valuenow="50" aria-valuemin="0"
                                                            aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                        <div class="col-auto">
                                            <i class="fas fa-calendar fa-2x text-primary dashboard-icon"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Carte : Nombre d'examens (exemple statique) -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2 dashboard-animated">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Nomber d'examens </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">200</div>
                                        </div>
                                        <div class="col">
                                                    <div class="progress progress-sm mr-2">
                                                <div class="progress-bar bg-success animated-bar" role="progressbar"
                                                            style="width: 30%" aria-valuenow="50" aria-valuemin="0"
                                                            aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                        <div class="col-auto">
                                            <i class="fas fa-clipboard-list fa-2x text-success dashboard-icon"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Carte : Total d'échantillons (exemple statique) -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2 dashboard-animated">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total d'échantillons</div>
                                            <div class="row no-gutters align-items-center">
                                                <div class="col-auto">
                                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">50</div>
                                                </div>
                                                <div class="col">
                                                    <div class="progress progress-sm mr-2">
                                                        <div class="progress-bar bg-info animated-bar" role="progressbar"
                                                            style="width: 50%" aria-valuenow="50" aria-valuemin="0"
                                                            aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-microscope fa-2x text-info dashboard-icon"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Carte : En cours d'analyse (exemple statique) -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2 dashboard-animated">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">En cours d'analyse </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">18</div>
                                        </div>
                                        <div class="col">
                                                    <div class="progress progress-sm mr-2">
                                                <div class="progress-bar bg-warning animated-bar" role="progressbar"
                                                            style="width: 80%" aria-valuenow="50" aria-valuemin="0"
                                                            aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                        <div class="col-auto">
                                            <i class="fas fa-flask fa-2x text-warning dashboard-icon"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Autres sections du dashboard (graphiques, etc.) -->
                    <div class="row">
                        <!-- Exemple de graphique area -->
                        <div class="col-xl-8 col-lg-7">
                            <div class="card shadow mb-4">
                                <!-- En-tête de la carte -->
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary"></h6>
                                    <div class="dropdown no-arrow">
                                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                            aria-labelledby="dropdownMenuLink">
                                            <div class="dropdown-header">Dropdown Header:</div>
                                            <a class="dropdown-item" href="#">Action</a>
                                            <a class="dropdown-item" href="#">Another action</a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="#">Something else here</a>
                                        </div>
                                    </div>
                                </div>
                                <!-- Corps de la carte (graphique) -->
                                <div class="card-body">
                                    <div class="chart-area">
                                        <canvas id="myAreaChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Exemple de graphique pie -->
                        <div class="col-xl-4 col-lg-5">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Revenue Sources</h6>
                                    <div class="dropdown no-arrow">
                                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                            aria-labelledby="dropdownMenuLink">
                                            <div class="dropdown-header">Dropdown Header:</div>
                                            <a class="dropdown-item" href="#">Action</a>
                                            <a class="dropdown-item" href="#">Another action</a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="#">Something else here</a>
                                        </div>
                                    </div>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="chart-pie pt-4 pb-2">
                                        <canvas id="myPieChart"></canvas>
                                    </div>
                                    <div class="mt-4 text-center small">
                                        <span class="mr-2">
                                            <i class="fas fa-circle text-primary"></i> Direct
                                        </span>
                                        <span class="mr-2">
                                            <i class="fas fa-circle text-success"></i> Social
                                        </span>
                                        <span class="mr-2">
                                            <i class="fas fa-circle text-info"></i> Referral
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; IPCI  2025</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Bootstrap core JavaScript-->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="../vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="../js/demo/chart-area-demo.js"></script>
    <script src="../js/demo/chart-pie-demo.js"></script>

    <script>
    // Animation compteur pour les cartes statistiques
    // Fait défiler le chiffre de 0 à la valeur réelle sur 7 secondes (modifiable)
    function animateCounter(id, endValue, duration = 7000) {
        const el = document.getElementById(id);
        if (!el) return;
        let start = 0;
        const increment = Math.ceil(endValue / (duration / 16));
        function update() {
            start += increment;
            if (start >= endValue) {
                el.textContent = endValue;
            } else {
                el.textContent = start;
                requestAnimationFrame(update);
            }
        }
        update();
    }
    document.addEventListener('DOMContentLoaded', function() {
        // Lance l'animation des compteurs patients et utilisateurs
        animateCounter('patientsCount', <?php echo $nbPatients; ?>, 7000);
        animateCounter('usersCount', <?php echo $nbUsers; ?>, 7000);
        // Apparition animée des cartes avec délai progressif
        document.querySelectorAll('.dashboard-animated').forEach(function(card, i) {
            card.style.animationDelay = (i * 0.12) + 's';
        });
        // Animation barre de progression (remplissage progressif)
        document.querySelectorAll('.animated-bar').forEach(function(bar) {
            const targetWidth = bar.getAttribute('style').match(/width:\s*([0-9]+)%/);
            if (targetWidth) {
                setTimeout(function() {
                    bar.style.width = targetWidth[1] + '%';
                }, 400);
            }
        });
    });
    </script>

</body>

</html>