
<?php 

require_once('connexion.php');
require_once('../les_fonctions/fonction.php');

if($_SERVER['REQUEST_METHOD']=='POST'){
    $login=$_POST['login'];
    $nom=$_POST['Nom'];
    $Prenom=$_POST['Prenom'];
    $contact=$_POST['contact'];
    $role=$_POST['Role'];
    $pwd1=$_POST['pwd1'];
    $pwd2=$_POST['pwd2'];
    $email=$_POST['email'];

    $validationErreur=array();


    if(isset($login)){
        $filtredLogin=filter_var($login, FILTER_SANITIZE_SPECIAL_CHARS);

        if(strlen($filtredLogin) < 4){
            $validationErreur[]="<strong>Erreur !!</strong> le login doit contenir au moins 4 caractere";
        }
    }
    if(isset($pwd1)&& isset($pwd2)){
        if(empty($pwd1)){
            $validationErreur[]="<strong>Erreur !!</strong>  le mot de passe ne doit etre vide";
        }

        if(md5($pwd1)!== md5($pwd2)){
            $validationErreur[]="<strong>Erreur !!</strong>  les deux  mot de passe ne sont pas identiques ";
        }
    }
    if(isset($email)){
        $filtredemail=filter_var($email,FILTER_SANITIZE_EMAIL);

        if($filtredemail != true) {
            $validationErreur[]="<strong>Erreur !!</strong>   Email non vide";
        }
    }
    if(empty($validationErreur)) {

      if (rechercher_par_login($login)==0 && rechercher_par_email($email)==0){
        $requete=$pdo->prepare("insert into user(Nom_user,Prenom_user,Email_user,Mdp_user,Type_user,Etat_user,Contact_user,Login_user)values(:pNom,:pPrenom,:pEmail,:Mdp,:pType,:pEtat,:pContact,:pLogin)");
        $requete->execute(array('pNom'=>$nom,
                                 'pPrenom'=>$Prenom,            
                                 'pEmail'=>$email,
                                 'Mdp' =>$pwd1, 
                                 'pType' =>$role,  
                                 'pEtat' =>0,
                                 'pContact'=>$contact, 
                                 'pLogin'=>$login
                                 ));
        $success_msg="Félicitation,votre compte est crée, mais temporairement inactif jusqu'a activation par l'administration";
      }else if(rechercher_par_login($login)>0){
        $validationErreur[]="Désolé le login exite deja";
      }else if(rechercher_par_email($email)>0){
        $validationErreur[]="Désolé le email exite deja";
      }
    }
}



?>

<!DOCTYPE html>
<html lang="fr">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link href="../img/logo/logo.png" rel="icon">
  <title>Nouveau  Utilisateur</title>
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="../css/ruang-admin.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-login">
  <!-- Register Content -->
  <div class="container-login">
    <div class="row justify-content-center">
      <div class="col-xl-6 col-lg-12 col-md-9">
        <div class="card shadow-sm my-5">
          <div class="card-body p-0">
            <div class="row">
              <div class="col-lg-12">
                <div class="login-form">
                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4"><h3>Création d'un nouveau compte utilisateur
                       </h3></h1>
                       <?php 
                  if(isset($validationErreur)&& !empty($validationErreur)){
                    foreach ($validationErreur as $error){
                        echo'<div class="alert alert-danger" >'.$error.'</div>';
                    }
                  }
                  if (isset($success_msg) && !empty($success_msg)) {
                    echo '<div class="alert alert-success">' . $success_msg . '</div>';
            
                    header('refresh:5;url=admin.php');
                }
                  ?>
                  </div>
                      <form method="POST" action="newutilisateur.php" class="form border  rounded p-4 bg-white shadow-sm">
                          <div class="form-row">
                            <div class="form-group col-md-6">
                              <label for="nom"><h5>Nom :</h5></label>
                              <input type="text" name="Nom" id="nom" class="form-control" required placeholder="Taper votre nom" autocomplete="off">
                            </div>
                            <div class="form-group col-md-6">
                              <label for="prenom"><h5>Prénom :</h5></label>
                              <input type="text" name="Prenom" id="prenom" class="form-control" required placeholder="Taper votre prénom" autocomplete="off">
                            </div>
                          </div>

                          <div class="form-row">
                            <div class="form-group col-md-6">
                              <label for="contact"><h5>Contact :</h5></label>
                              <input type="text" name="contact" id="contact" class="form-control" required placeholder="Taper votre contact" autocomplete="off">
                            </div>
                            <div class="form-group col-md-6">
                              <label for="login"><h5>Login :</h5></label>
                              <input type="text" name="login" id="login" class="form-control" required placeholder="Taper votre login" autocomplete="off">
                            </div>
                          </div>

                          <div class="form-group">
                            <label for="email"><h5>Email :</h5></label>
                            <input type="email" name="email" id="email" class="form-control" required placeholder="Taper votre email" autocomplete="off">
                          </div>

                          <div class="form-group">
                            <label for="role"><h5>Rôle :</h5></label>
                            <select name="Role" id="role" class="form-control">
                              <option value="ADMIN">Docteur</option>
                              <option value="TECHNICIEN">Technicien</option>
                            </select>
                          </div>

                          <div class="form-row">
                            <div class="form-group col-md-6">
                              <label for="pwd1"><h5>Mot de passe :</h5></label>
                              <input type="password" name="pwd1" id="pwd1" class="form-control" required minlength="4"
                                placeholder="Taper votre mot de passe" title="Doit contenir au moins 4 caractères" autocomplete="new-password">
                            </div>
                            <div class="form-group col-md-6">
                              <label for="pwd2"><h5>Confirmation du mot de passe :</h5></label>
                              <input type="password" name="pwd2" id="pwd2" class="form-control" required minlength="4"
                                placeholder="Confirmez votre mot de passe" autocomplete="new-password">
                            </div>
                          </div>

                          <div class="form-group mt-4">
                            <input type="submit" class="btn btn-primary btn-block" value="Créer">
                          </div>
                  </form>


                 
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Register Content -->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="js/ruang-admin.min.js"></script>
</body>

</html> 