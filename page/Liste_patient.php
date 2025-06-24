<?php 

require_once("identifier.php");

require_once("connexion.php");

$name = isset($_GET['name']) ? $_GET['name'] : "";

$size = isset($_GET['size']) ? intval($_GET['size']) : 3;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $size;
$reqliste = "SELECT * FROM patient where Nom_patient like ?";
$reqcount = "SELECT COUNT(*) countP FROM patient WHERE Nom_patient LIKE ?";
$stmtCount = $pdo->prepare($reqcount);
$stmtCount->execute(["%$name%"]);
$tabcount = $stmtCount->fetch();
$nbrliste = $tabcount['countP'];
$reste = $nbrliste % $size;

$nbrPage = ($nbrliste > 0) ? ceil($nbrliste / $size) : 1;

$stmtListe = $pdo->prepare($reqliste);
$stmtListe->execute(["%$name%"]);

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
            <form method="get" action="Liste_patient.php" class="form-inline" id="searchForm">
              <div class="form-group">
                <input type="text" name="name" placeholder="Saisissez votre nom"
                class="form-control" value="<?php echo $name?>" id="searchInput" autocomplete="off">
              </div>
              &nbsp;&nbsp;
              <a  class="text-success" href="ajouter_patient.php"><i class="fa fa-plus  text-success" aria-hidden="true"></i>  Ajouter un patient</a>
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
                        <?php while($patient=$stmtListe->fetch()){ ?>
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
                          <a href="#" class="edit-patient-link"
                             data-url="modifpatient.php?idU=<?php echo $patient['Numero_urap'] ?>"
                             data-name="<?php echo $patient['Nom_patient'] ?>"
                             data-prenom="<?php echo $patient['Prenom_patient'] ?>"
                             data-urap="<?php echo $patient['Numero_urap'] ?>"
                             data-contact="<?php echo $patient['Contact_patient'] ?>"
                             data-profession="<?php echo $patient['Profession'] ?>">
                             <i class="fa fa-edit text-success" aria-hidden="true"></i>
                          </a>
                           &nbsp;
                          <a href="#" class="delete-patient-link" 
                             data-url="supprimpatient.php?idU=<?php echo $patient["Numero_urap"] ?>"
                             data-name="<?php echo $patient["Nom_patient"] ?>"
                             data-prenom="<?php echo $patient["Prenom_patient"] ?>"
                             data-urap="<?php echo $patient["Numero_urap"] ?>"
                             data-contact="<?php echo $patient["Contact_patient"] ?>"
                             data-profession="<?php echo $patient["Profession"] ?>">
                             <i class="fa fa-trash text-danger" aria-hidden="true"></i>
                          </a>
                          &nbsp;
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
                          <li class="page-item <?php if($i==$page)echo 'active'; ?>">
                            <a class="page-link" href="Liste_patient.php?page=<?php echo $i; ?>&name=<?php echo urlencode($name); ?>&size=<?php echo $size; ?>">
                              <?php  echo $i;?>
                            </a>
                          </li>
                        <?php } ?>
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

    <!-- Modal de confirmation de suppression -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header bg-danger text-white">
            <h5 class="modal-title" id="confirmDeleteLabel">Confirmer la suppression</h5>
            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <p class="text-center mb-0"><i class="fa fa-exclamation-triangle fa-2x text-danger mb-2"></i></p>
            <p class="text-center font-weight-bold text-danger">Attention ! Cette action est irréversible.</p>
            <div class="border rounded bg-light p-3 mb-2">
              <div><b>Nom :</b> <span id="modalNom"></span></div>
              <div><b>Prénom :</b> <span id="modalPrenom"></span></div>
              <div><b>N° URAP :</b> <span id="modalUrap"></span></div>
              <div><b>Contact :</b> <span id="modalContact"></span></div>
              <div><b>Profession :</b> <span id="modalProfession"></span></div>
            </div>
            <p class="text-center">Voulez-vous vraiment supprimer ce patient ?</p>
          </div>
          <div class="modal-footer justify-content-center">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
            <a href="#" id="confirmDeleteBtn" class="btn btn-danger">Supprimer</a>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal de confirmation de modification patient -->
    <div class="modal fade" id="confirmEditModal" tabindex="-1" role="dialog" aria-labelledby="confirmEditLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title" id="confirmEditLabel">Confirmer la modification</h5>
            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Fermer">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <p class="text-center mb-0"><i class="fa fa-edit fa-2x text-primary mb-2"></i></p>
            <div class="border rounded bg-light p-3 mb-2">
              <div><b>Nom :</b> <span id="modalEditNom"></span></div>
              <div><b>Prénom :</b> <span id="modalEditPrenom"></span></div>
              <div><b>N° URAP :</b> <span id="modalEditUrap"></span></div>
              <div><b>Contact :</b> <span id="modalEditContact"></span></div>
              <div><b>Profession :</b> <span id="modalEditProfession"></span></div>
            </div>
            <p class="text-center">Voulez-vous vraiment modifier ce patient ?</p>
          </div>
          <div class="modal-footer justify-content-center">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Annuler</button>
            <a href="#" id="confirmEditBtn" class="btn btn-primary">Modifier</a>
          </div>
        </div>
      </div>
    </div>

    <script>
    // Recherche automatique à la saisie
    let timer = null;
    document.getElementById('searchInput').addEventListener('input', function() {
      clearTimeout(timer);
      timer = setTimeout(function() {
        document.getElementById('searchForm').submit();
      }, 400);
    });

    // Confirmation stylisée de suppression
    let deleteLinks = document.querySelectorAll('.delete-patient-link');
    let confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    let modalNom = document.getElementById('modalNom');
    let modalPrenom = document.getElementById('modalPrenom');
    let modalUrap = document.getElementById('modalUrap');
    let modalContact = document.getElementById('modalContact');
    let modalProfession = document.getElementById('modalProfession');
    let deleteUrl = '';
    deleteLinks.forEach(function(link) {
      link.addEventListener('click', function(e) {
        e.preventDefault();
        deleteUrl = this.getAttribute('data-url');
        modalNom.textContent = this.getAttribute('data-name') || '';
        modalPrenom.textContent = this.getAttribute('data-prenom') || '';
        modalUrap.textContent = this.getAttribute('data-urap') || '';
        modalContact.textContent = this.getAttribute('data-contact') || '';
        modalProfession.textContent = this.getAttribute('data-profession') || '';
        $('#confirmDeleteModal').modal('show');
      });
    });
    confirmDeleteBtn.addEventListener('click', function(e) {
      window.location.href = deleteUrl;
    });

    // Modal de modification patient
    document.querySelectorAll('.edit-patient-link').forEach(function(link) {
      link.addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('modalEditNom').textContent = this.dataset.name;
        document.getElementById('modalEditPrenom').textContent = this.dataset.prenom;
        document.getElementById('modalEditUrap').textContent = this.dataset.urap;
        document.getElementById('modalEditContact').textContent = this.dataset.contact;
        document.getElementById('modalEditProfession').textContent = this.dataset.profession;
        document.getElementById('confirmEditBtn').setAttribute('href', this.dataset.url);
        $('#confirmEditModal').modal('show');
      });
    });
    </script>

</body>

</html>