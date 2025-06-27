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

    <style>
        /* Style moderne pour la liste des utilisateurs façon patient (ligne par user) */
        .users-list-container {
            width: 100%;
            max-width: 1100px;
            margin: 32px auto 0 auto;
            background: var(--gray-50, #f9fafb);
            border-radius: 18px;
            box-shadow: 0 6px 24px rgba(0,0,0,0.08);
            padding: 24px 0 18px 0;
        }
        .users-header {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
            padding: 0 24px;
        }
        .users-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--gray-700, #374151);
        }
        .users-count {
            background: var(--primary, #0047ab);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }
        .user-row {
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
        .user-row:hover {
            border-color: var(--primary, #0047ab);
            background: #e0edff;
            box-shadow: 0 4px 16px rgba(0,71,171,0.10);
        }
        .user-col {
            flex: 1 1 0;
            min-width: 0;
            font-size: 1rem;
            color: #1e293b;
            font-weight: 500;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .user-col.small {
            flex: 0 0 80px;
            font-size: 0.95em;
            color: #64748b;
        }
        .user-actions {
            display: flex;
            gap: 10px;
            flex: 0 0 auto;
        }
        .patient-action-btn, .user-action-btn {
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
        .patient-action-btn.modifier, .user-action-btn.modifier {
            background: #10b981;
            color: #fff;
        }
        .patient-action-btn.modifier:hover, .user-action-btn.modifier:hover {
            background: #059669;
            color: #fff;
        }
        .patient-action-btn.supprimer, .user-action-btn.supprimer {
            background: #ef4444;
            color: #fff;
        }
        .patient-action-btn.supprimer:hover, .user-action-btn.supprimer:hover {
            background: #b91c1c;
            color: #fff;
        }
        .user-action-btn.activer { background: #10b981; color: #fff; }
        .user-action-btn.activer:hover { background: #059669; color: #fff; }
        .user-action-btn.desactiver { background: #d1d5db; color: #64748b; }
        .user-action-btn.desactiver:hover { background: #9ca3af; color: #374151; }
        @media (max-width: 900px) {
            .users-list-container {
                padding: 10px 0;
            }
            .user-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 6px;
                padding: 12px 8px;
                margin: 0 4px 10px 4px;
            }
            .users-header {
                padding: 0 8px;
            }
        }
        .user-search-container {
            display: flex;
            justify-content: center;
            margin-bottom: 24px;
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
        .add-user-container {
            display: flex;
            justify-content: center;
            margin-bottom: 18px;
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
            <h1 class="h3 mb-0 text-gray-800">Liste des utilisateurs</h1>
            <div style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
                <input type="text" id="userSearchInput" class="user-search-input" placeholder="Rechercher un utilisateur... (nom, prénom, login, email, rôle, contact)">
                <a href="newutilisateur.php" class="add-user-btn"><i class="fas fa-user-plus"></i> Ajouter utilisateur</a>
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
                  <div class="users-list-container" id="usersListContainer">
                    <div class="users-header">
                        <h3 class="users-title">Utilisateurs</h3>
                        <span class="users-count" id="usersCount"><?php echo $nbradmin; ?></span>
                    </div>
                    <?php while($user=$resultatadmin->fetch()){ ?>
                        <div class="user-row <?php echo $user["Etat_user"]==1?'text-primary ':'text-gray-500' ?>">
                            <div class="user-col"><?php echo htmlspecialchars($user["Nom_user"]); ?></div>
                            <div class="user-col"><?php echo htmlspecialchars($user["Prenom_user"]); ?></div>
                            <div class="user-col"><?php echo htmlspecialchars($user["Login_user"]); ?></div>
                            <div class="user-col small"><?php echo htmlspecialchars($user["Type_user"]); ?></div>
                            <div class="user-col"><?php echo htmlspecialchars($user["Contact_user"]); ?></div>
                            <div class="user-col"><?php echo htmlspecialchars($user["Email_user"]); ?></div>
                            <div class="user-actions">
                                <a href="#" class="patient-action-btn modifier edit-user-link" title="Modifier"
                                   data-url="modifieruser.php?idU=<?php echo $user['Id_user']; ?>"
                                   data-nom="<?php echo htmlspecialchars($user['Nom_user']); ?>"
                                   data-prenom="<?php echo htmlspecialchars($user['Prenom_user']); ?>"
                                   data-login="<?php echo htmlspecialchars($user['Login_user']); ?>"
                                   data-email="<?php echo htmlspecialchars($user['Email_user']); ?>"
                                   data-role="<?php echo htmlspecialchars($user['Type_user']); ?>"
                                   data-contact="<?php echo htmlspecialchars($user['Contact_user']); ?>">
                                   <i class="fas fa-pen"></i>
                                </a>
                                <a href="#" class="patient-action-btn supprimer delete-user-link" title="Supprimer"
                                   data-url="supprimeruser.php?idU=<?php echo $user['Id_user']; ?>"
                                   data-nom="<?php echo htmlspecialchars($user['Nom_user']); ?>"
                                   data-prenom="<?php echo htmlspecialchars($user['Prenom_user']); ?>"
                                   data-login="<?php echo htmlspecialchars($user['Login_user']); ?>"
                                   data-email="<?php echo htmlspecialchars($user['Email_user']); ?>"
                                   data-role="<?php echo htmlspecialchars($user['Type_user']); ?>"
                                   data-contact="<?php echo htmlspecialchars($user['Contact_user']); ?>">
                                   <i class="fas fa-trash"></i>
                                </a>
                                <a href="Activeruser.php?idU=<?php echo $user["Id_user"] ?>&etat=<?php echo $user["Etat_user"] ?>" class="user-action-btn <?php echo $user["Etat_user"]==1 ? 'desactiver' : 'activer'; ?>" title="<?php echo $user["Etat_user"]==1 ? 'Désactiver' : 'Activer'; ?>">
                                    <?php 
                                        if($user["Etat_user"]==1)
                                            echo'<i class="fa fa-times" aria-hidden="true"></i>';
                                        else
                                            echo'<i class="fa fa-check" aria-hidden="true"></i>';
                                    ?>
                                </a>
                            </div>
                        </div>
                    <?php } ?>
                  </div>
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

    <script>
    // Recherche automatique côté client sur la liste des utilisateurs
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('userSearchInput');
        const usersList = document.getElementById('usersListContainer');
        if (!searchInput || !usersList) return;
        searchInput.addEventListener('input', function() {
            const term = this.value.toLowerCase();
            usersList.querySelectorAll('.user-row').forEach(function(row) {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(term) ? '' : 'none';
            });
        });
    });
    </script>

    <!-- Modal de confirmation de suppression utilisateur -->
    <div class="modal fade" id="confirmDeleteUserModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteUserLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header bg-danger text-white">
            <h5 class="modal-title" id="confirmDeleteUserLabel">Confirmer la suppression</h5>
            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Fermer">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <p class="text-center mb-0"><i class="fa fa-exclamation-triangle fa-2x text-danger mb-2"></i></p>
            <p class="text-center font-weight-bold text-danger">Attention ! Cette action est irréversible.</p>
            <div class="border rounded bg-light p-3 mb-2">
              <div><b>Nom :</b> <span id="modalUserNom"></span></div>
              <div><b>Prénom :</b> <span id="modalUserPrenom"></span></div>
              <div><b>Login :</b> <span id="modalUserLogin"></span></div>
              <div><b>Email :</b> <span id="modalUserEmail"></span></div>
              <div><b>Rôle :</b> <span id="modalUserRole"></span></div>
              <div><b>Contact :</b> <span id="modalUserContact"></span></div>
            </div>
            <p class="text-center">Voulez-vous vraiment supprimer cet utilisateur ?</p>
          </div>
          <div class="modal-footer justify-content-center">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
            <a href="#" id="confirmDeleteUserBtn" class="btn btn-danger">Supprimer</a>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal de confirmation de modification utilisateur -->
    <div class="modal fade" id="confirmEditUserModal" tabindex="-1" role="dialog" aria-labelledby="confirmEditUserLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header bg-success text-white">
            <h5 class="modal-title" id="confirmEditUserLabel">Confirmer la modification</h5>
            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Fermer">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <p class="text-center mb-0"><i class="fa fa-pen fa-2x text-success mb-2"></i></p>
            <div class="border rounded bg-light p-3 mb-2">
              <div><b>Nom :</b> <span id="modalEditUserNom"></span></div>
              <div><b>Prénom :</b> <span id="modalEditUserPrenom"></span></div>
              <div><b>Login :</b> <span id="modalEditUserLogin"></span></div>
              <div><b>Email :</b> <span id="modalEditUserEmail"></span></div>
              <div><b>Rôle :</b> <span id="modalEditUserRole"></span></div>
              <div><b>Contact :</b> <span id="modalEditUserContact"></span></div>
            </div>
            <p class="text-center">Voulez-vous vraiment modifier cet utilisateur ?</p>
          </div>
          <div class="modal-footer justify-content-center">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Annuler</button>
            <a href="#" id="confirmEditUserBtn" class="btn btn-success">Modifier</a>
          </div>
        </div>
      </div>
    </div>

    <script>
    // Confirmation stylisée de suppression utilisateur
    document.addEventListener('DOMContentLoaded', function() {
        // Suppression
        let deleteLinks = document.querySelectorAll('.delete-user-link');
        let confirmDeleteBtn = document.getElementById('confirmDeleteUserBtn');
        let modalUserNom = document.getElementById('modalUserNom');
        let modalUserPrenom = document.getElementById('modalUserPrenom');
        let modalUserLogin = document.getElementById('modalUserLogin');
        let modalUserEmail = document.getElementById('modalUserEmail');
        let modalUserRole = document.getElementById('modalUserRole');
        let modalUserContact = document.getElementById('modalUserContact');
        let deleteUrl = '';
        deleteLinks.forEach(function(link) {
          link.addEventListener('click', function(e) {
            e.preventDefault();
            deleteUrl = this.getAttribute('data-url');
            modalUserNom.textContent = this.getAttribute('data-nom') || '';
            modalUserPrenom.textContent = this.getAttribute('data-prenom') || '';
            modalUserLogin.textContent = this.getAttribute('data-login') || '';
            modalUserEmail.textContent = this.getAttribute('data-email') || '';
            modalUserRole.textContent = this.getAttribute('data-role') || '';
            modalUserContact.textContent = this.getAttribute('data-contact') || '';
            $('#confirmDeleteUserModal').modal('show');
          });
        });
        confirmDeleteBtn.addEventListener('click', function(e) {
          window.location.href = deleteUrl;
        });

        // Modification
        let editLinks = document.querySelectorAll('.edit-user-link');
        let confirmEditBtn = document.getElementById('confirmEditUserBtn');
        let modalEditUserNom = document.getElementById('modalEditUserNom');
        let modalEditUserPrenom = document.getElementById('modalEditUserPrenom');
        let modalEditUserLogin = document.getElementById('modalEditUserLogin');
        let modalEditUserEmail = document.getElementById('modalEditUserEmail');
        let modalEditUserRole = document.getElementById('modalEditUserRole');
        let modalEditUserContact = document.getElementById('modalEditUserContact');
        let editUrl = '';
        editLinks.forEach(function(link) {
          link.addEventListener('click', function(e) {
            e.preventDefault();
            editUrl = this.getAttribute('data-url');
            modalEditUserNom.textContent = this.getAttribute('data-nom') || '';
            modalEditUserPrenom.textContent = this.getAttribute('data-prenom') || '';
            modalEditUserLogin.textContent = this.getAttribute('data-login') || '';
            modalEditUserEmail.textContent = this.getAttribute('data-email') || '';
            modalEditUserRole.textContent = this.getAttribute('data-role') || '';
            modalEditUserContact.textContent = this.getAttribute('data-contact') || '';
            $('#confirmEditUserModal').modal('show');
          });
        });
        confirmEditBtn.addEventListener('click', function(e) {
          window.location.href = editUrl;
        });
    });
    </script>

</body>

</html>