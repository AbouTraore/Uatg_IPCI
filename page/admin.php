<?php 

require_once("identifier.php");

require_once("connexion.php");

$login = isset($_GET['login']) ? $_GET['login'] : "";

$size = isset($_GET['size']) ? $_GET['size'] : 3;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $size;
$reqadmin = "SELECT * FROM user where Login_user like '%$login%'";
$reqcount = "SELECT COUNT(*) countU FROM user";

$resultatadmin = $pdo->query($reqadmin);
$resultatcount = $pdo->query($reqcount);
$tabcount = $resultatcount->fetch();
$nbradmin = $tabcount['countU'];
$reste = $nbradmin % $size;

if ($reste === 0) {
    $nbrPage = $nbradmin / $size;
} else {
    $nbrPage = floor($nbradmin / $size) + 1;
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
            <h1 class="h3 mb-0 text-gray-800">Liste des users</h1>
            <div>
            <form method="get" action="user.php" class="form-inline">
              <div class="form-group">
                <input type="text" name="login" placeholder="login"
                class="form-control" value="<?php echo $login?>">
              </div>
              &nbsp &nbsp;
            <button type="submit" class="btn btn-info"><i class="fa fa-search"></i>
            chercher...
            </button>
            &nbsp &nbsp;
            <a  class="text-success"href="newutilisateur.php"><i class="fa fa-plus  text-success" aria-hidden="true"></i>  Ajouter utilisateur</a>
          </form>
          </div>
          </div>
          <!-- Row -->
          <div class="row">
            <!-- Datatables -->
            <div class="col-lg-12">
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary"><?php echo $nbradmin?> users enregristrés</h6>
                </div>
                <div class="table-responsive p-3">
                  <table class="table align-items-center table-flush" id="dataTable">
                    <thead class="thead">
                    <tr>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Login</th>
                        <th>Role</th>
                        <th>Contact</th>
                        <th>Email</th>
                        <th>Actions</th>
                      </tr>
                    </thead >
                    <thead>
                        <?php while($user=$resultatadmin->fetch()){ ?>
                      <tr class="<?php echo $user["Etat_user"]==1?'text-primary ':'text-gray-500' ?>">
                        <td><?php echo $user["Nom_user"] ?></td>
                        <td><?php echo $user["Prenom_user"] ?></td>
                        <td><?php echo $user["Login_user"] ?></td>
                        <td><?php echo $user["Type_user"] ?></td>
                        <td><?php echo $user["Contact_user"] ?></td>
                        <td><?php echo $user["Email_user"] ?></td>
                        <td>
                          <a onclick="return confirm('etes vous sur de vouloir modifier cet Utilisateur')" href="modifieruser.php?idU=<?php echo $user["Id_user"] ?>"><i class="fa fa-edit text-warning" aria-hidden="true"></a></i>
                           &nbsp;
                          <a onclick="return confirm('etes vous sur de vouloir supprimer cet user')" href="supprimeruser.php?idU=<?php echo $user["Id_user"] ?>"><i class="fa fa-trash text-gray-800" aria-hidden="true"></a></i>
                          &nbsp;
                          <a href="Activeruser.php?idU=<?php echo $user["Id_user"] ?>&etat=<?php echo $user["Etat_user"] ?>">
                          <?php 
                              if($user["Etat_user"]==1)
                                 echo'<i class="fa fa-times  text-gray-400" aria-hidden="true"></i>';
                             else
                                echo'<i class="fa fa-check text-primary" aria-hidden="true"></i>';
                          ?>
                          </a>
                        </td>
                      </tr>
                      <?php } ?>
						       </thead>
                    <tfoot>
                    <tr>
                        <th>Nom</th>
                        <th >Prénom</th>
                        <th>Login</th>
                        <th>Role</th>
                        <th>Contact</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                    <tbody>
                      
                    </tbody>
                  </table>
                  <nav aria-label="Page navigation example">
                        <ul class="pagination">
                        <?php  for( $i=1;$i<=$nbrPage;$i++ ){?>
                          <li class="page-item <?php if($i==$page)echo"page-item active"?>">
                            <a class="page-link" href="user.php?page=<?php echo $i; ?>&login=<?php echo $login; ?> ">
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