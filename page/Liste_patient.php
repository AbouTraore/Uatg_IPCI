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

    <style>
        /* Liste patients : une ligne par patient, style moderne */
        .patients-list-container {
            width: 100%;
            max-width: 1100px;
            margin: 32px auto 0 auto;
            background: var(--gray-50, #f9fafb);
            border-radius: 18px;
            box-shadow: 0 6px 24px rgba(0,0,0,0.08);
            padding: 24px 0 18px 0;
        }
        .patients-header {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
            padding: 0 24px;
        }
        .patients-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--gray-700, #374151);
        }
        .patients-count {
            background: var(--primary, #0047ab);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }
        .patient-row {
            display: flex;
            align-items: center;
            background: white;
            border: 1.5px solid var(--gray-200, #e5e7eb);
            border-radius: 12px;
            margin: 0 24px 12px 24px;
            padding: 12px 18px;
            transition: box-shadow 0.2s, border 0.2s, background 0.2s;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            gap: 18px;
        }
        .patient-row:hover {
            border-color: var(--primary, #0047ab);
            background: #e0edff;
            box-shadow: 0 4px 16px rgba(0,71,171,0.10);
        }
        .patient-col {
            flex: 1 1 0;
            min-width: 0;
            font-size: 1rem;
            color: #1e293b;
            font-weight: 500;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .patient-col.small {
            flex: 0 0 80px;
            font-size: 0.95em;
            color: #64748b;
        }
        .patient-actions {
            display: flex;
            gap: 10px;
            flex: 0 0 auto;
        }
        .patient-action-btn {
            border: none;
            border-radius: 50%;
            width: 36px;
            height: 36px;
            font-size: 1.1em;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
            transition: background 0.2s, color 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .patient-action-btn.modifier {
            background: #10b981;
            color: #fff;
        }
        .patient-action-btn.modifier:hover {
            background: #059669;
            color: #fff;
        }
        .patient-action-btn.supprimer {
            background: #ef4444;
            color: #fff;
        }
        .patient-action-btn.supprimer:hover {
            background: #b91c1c;
            color: #fff;
        }
        @media (max-width: 900px) {
            .patients-list-container {
                padding: 10px 0;
            }
            .patient-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 6px;
                padding: 12px 8px;
                margin: 0 4px 10px 4px;
            }
            .patients-header {
                padding: 0 8px;
            }
        }
        .user-search-input {
            width: 340px;
            padding: 12px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 1.08em;
            background: #fff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            transition: border 0.2s;
        }
        .user-search-input:focus {
            border-color: #0047ab;
            outline: none;
        }
        .add-user-btn {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 8px 18px;
            font-size: 1em;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(16,185,129,0.13);
            transition: background 0.2s, box-shadow 0.2s, transform 0.2s;
            letter-spacing: 0.3px;
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }
        .add-user-btn:hover {
            background: linear-gradient(135deg, #059669 0%, #10b981 100%);
            box-shadow: 0 8px 32px rgba(16,185,129,0.18);
            transform: translateY(-2px) scale(1.03);
        }
    </style>

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
          <div class="d-sm-flex align-items-center justify-content-between mb-4 flex-wrap" style="gap: 12px;">
            <h1 class="h3 mb-0 text-gray-800">Liste des patients</h1>
            <div style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
                <input type="text" id="patientSearchInput" class="user-search-input" placeholder="Rechercher un patient... (nom, prénom, urap, contact, profession)">
                <a href="ajouter_patient.php" class="add-user-btn"><i class="fas fa-user-plus"></i> Ajouter un patient</a>
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
                  <div class="patients-list-container">
                    <div class="patients-header">
                        <h3 class="patients-title">Patients</h3>
                        <span class="patients-count" id="patientsCount"><?php echo $nbrliste; ?></span>
                    </div>
                    <?php while($patient=$stmtListe->fetch()){ ?>
                        <div class="patient-row">
                            <div class="patient-col small"><?php echo htmlspecialchars($patient["Numero_urap"]); ?></div>
                            <div class="patient-col"><?php echo htmlspecialchars($patient["Nom_patient"]); ?></div>
                            <div class="patient-col"><?php echo htmlspecialchars($patient["Prenom_patient"]); ?></div>
                            <div class="patient-col small"><?php echo htmlspecialchars($patient["Age"]); ?> ans</div>
                            <div class="patient-col small"><?php echo htmlspecialchars($patient["Sexe_patient"]); ?></div>
                            <div class="patient-col"><?php echo htmlspecialchars($patient["Contact_patient"]); ?></div>
                            <div class="patient-col"><?php echo htmlspecialchars($patient["Lieu_résidence"]); ?></div>
                            <div class="patient-col"><?php echo htmlspecialchars($patient["Profession"]); ?></div>
                            <div class="patient-actions">
                                <a href="#" class="patient-action-btn modifier edit-patient-link" title="Modifier"
                                   data-url="modifpatient.php?idU=<?php echo urlencode($patient['Numero_urap']); ?>"
                                   data-name="<?php echo htmlspecialchars($patient['Nom_patient']); ?>"
                                   data-prenom="<?php echo htmlspecialchars($patient['Prenom_patient']); ?>"
                                   data-urap="<?php echo htmlspecialchars($patient['Numero_urap']); ?>"
                                   data-contact="<?php echo htmlspecialchars($patient['Contact_patient']); ?>"
                                   data-profession="<?php echo htmlspecialchars($patient['Profession']); ?>">
                                   <i class="fas fa-pen"></i>
                                </a>
                                <a href="#" class="patient-action-btn supprimer delete-patient-link" title="Supprimer"
                                   data-url="supprimpatient.php?idU=<?php echo $patient["Numero_urap"] ?>"
                                   data-name="<?php echo $patient["Nom_patient"] ?>"
                                   data-prenom="<?php echo $patient["Prenom_patient"] ?>"
                                   data-urap="<?php echo $patient["Numero_urap"] ?>"
                                   data-contact="<?php echo $patient["Contact_patient"] ?>"
                                   data-profession="<?php echo $patient["Profession"] ?>">
                                   <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </div>
                      <?php } ?>
                  </div>
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

    <!-- Modal de confirmation de modification patient (style suppression) -->
    <div class="modal fade" id="confirmEditModal" tabindex="-1" role="dialog" aria-labelledby="confirmEditLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header bg-success text-white">
            <h5 class="modal-title" id="confirmEditLabel">Confirmer la modification</h5>
            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Fermer">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <p class="text-center mb-0"><i class="fa fa-pen fa-2x text-success mb-2"></i></p>
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
            <a href="#" id="confirmEditBtn" class="btn btn-success">Modifier</a>
          </div>
        </div>
      </div>
    </div>

    <script>
    // Recherche automatique côté client sur la liste des patients
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('patientSearchInput');
        const patientsList = document.querySelector('.patients-list-container');
        if (!searchInput || !patientsList) return;
        searchInput.addEventListener('input', function() {
            const term = this.value.toLowerCase();
            patientsList.querySelectorAll('.patient-row').forEach(function(row) {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(term) ? '' : 'none';
            });
        });
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

    // Modal de modification patient (style suppression)
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