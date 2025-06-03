<?php 

require_once("identifier.php");

require_once("connexion.php");

$name = isset($_GET['name']) ? $_GET['name'] : "";

$size = isset($_GET['size']) ? $_GET['size'] : 3;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $size;
$reqliste = "SELECT * FROM patient where Nom_patient like '%$name%'";
$reqcount = "SELECT COUNT(*) countP FROM patient";
$resultatliste = $pdo->query($reqliste);
$resultatcount = $pdo->query($reqcount);
$tabcount = $resultatcount->fetch();
$nbrliste = $tabcount['countP'];
$reste = $nbrliste % $size;

if ($reste === 0) {
    $nbrPage = $nbrliste / $size;
} else {
    $nbrPage = floor($nbrliste / $size) + 1;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="../img/logo/logo.jpg" rel="icon">
    <title>UATG - Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">
      <?php
           require_once("menu_gauche.php");
       ?>

        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
               <?php
                require_once("menu_haut.php")
               ?>

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                   
                    <!-- Content Row -->
                    <div class="row">

                    <div class="container-fluid" id="container-wrapper">
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Liste des patients</h1>
            <div>
            <form method="get" action="Liste_patient.php" class="form-inline">
              <div class="form-group">
                <input type="text" name="name" placeholder="Saisissez votre nom"
                class="form-control" value="<?php echo $name?>">
              </div>
              &nbsp &nbsp;
            <button type="submit" class="btn btn-info"><i class="fa fa-search"></i>
            chercher...
            </button>
            &nbsp &nbsp;
            <a  class="text-success"href="patient.php"><i class="fa fa-plus  text-success" aria-hidden="true"></i>  Ajouter un patient</a>
          </form>
          </div>
          </div>
          <!-- Row -->
          <div class="row">
            <!-- Datatables -->
            <div class="col-lg-12">
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-success"><?php echo $nbrliste?> patients enregristrés</h6>
                </div>
                <div class="table-responsive p-3">
                  <table class="table align-items-center table-flush" id="dataTable">
                    <thead class="thead">
                    <tr>
                        <th>Numero urap</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Age</th>
                        <th>Sexe</th>
                        <th>Contact</th>
                        <th>Lieu residence</th>
                        <th>Precise</th>
                        <th>Profession</th>
                        <th>Actions</th>
                      </tr>
                    </thead >
                    <thead>
                        <?php while($patient=$resultatliste->fetch()){ ?>
                        <td><?php echo $patient["Numero_urap"] ?></td>
                        <td><?php echo $patient["Nom_patient"] ?></td>
                        <td><?php echo $patient["Prenom_patient"] ?></td>
                        <td><?php echo $patient["Age"] ?></td>
                        <td><?php echo $patient["Sexe_patient"] ?></td>
                        <td><?php echo $patient["Contact_patient"] ?></td>
                        <td><?php echo $patient["Lieu_résidence"] ?></td>
                        <td><?php echo $patient["Precise"] ?></td>
                        <td><?php echo $patient["Profession"] ?></td>
                        <td>
                          <a onclick="return confirm('etes vous sur de vouloir modifier cet Utilisateur')" href="modifierpatient.php?idU=<?php echo $patient["Numero_urap"] ?>"><i class="fa fa-edit text-success" aria-hidden="true"></a></i>
                           &nbsp;
                          <a onclick="return confirm('etes vous sur de vouloir supprimer cet user')" href="supprimerpatient.php?idU=<?php echo $patient["Numero_urap"] ?>"><i class="fa fa-trash text-danger" aria-hidden="true"></a></i>
                          &nbsp;
                          </a>
                        </td>
                      </tr>
                      <?php } ?>
						       </thead>
                    <tfoot>
                    <tr>
                    <th>Numero urap</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Age</th>
                        <th>Sexe</th>
                        <th>Contact</th>
                        <th>Lieu residence</th>
                        <th>Precise</th>
                        <th>Profession</th>
                        <th>Actions</th>
                    </tr>
                    <tbody>
                      
                    </tbody>
                  </table>
                  <nav aria-label="Page navigation example">
                        <ul class="pagination">
                        <?php  for( $i=1;$i<=$nbrPage;$i++ ){?>
                          <li class="page-item <?php if($i==$page)echo"page-item active"?>">
                            <a class="page-link" href="Liste_patient.php?page=<?php echo $i; ?>&name=<?php echo $name; ?> ">
                              <?php  echo $i;?>
                            </a>
                          <?php } ?>
                      </li>
                        </ul>
                 </nav>
                </div>
              </div>
            </div>
            
  <!-- Scroll to top -->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

                    <!-- Content Row -->
                    <div class="row">

                        <!-- Content Column -->
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

    <!-- Logout Modal-->
    

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

</body>

</html>