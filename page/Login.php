<?php 
session_start();
$erreurLogin = $_SESSION['erreurLogin'] ?? "";
session_unset();
session_destroy();
?> 

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Se connecter</title>
  <link href="../img/logo/logo.jpg" rel="icon">
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../css/ruang-admin.min.css" rel="stylesheet">

  <style>
    /* Ajout de l'image de fond */
    .bg-image {
      background-image: url('../img/Microscope2.jpg');
      background-size: cover; 
      background-position: center; 
      background-attachment: fixed; 
      height: 100vh; /* La hauteur doit être 100% de la fenêtre */
    }

    /* Conteneur du formulaire, centré */
    .form-container {
      background-color: rgba(0, 0, 0, 0.0); /* Ombre sombre semi-transparente pour mieux voir le formulaire */
      padding: 40px;
      border-radius: 10px;
    }

    
    .card {
      max-width: 500px;
      width: 100%;
    }

    .container {
      padding: 0;
    }
    
   label {
   color: white;
}
.h5 {
      font-family: 'Calisto MT'; /* Change la police de caractères */
      font-size: 36px; /* Agrandit la taille du texte */
      color: black; /* Met le texte en blanc */
      font-weight: bold; /* Rendre le texte en gras */
    }

  </style>
</head>
<body class="bg-image">

  <div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="col-xl-6 col-lg-12 col-md-10">
      <div class="card shadow-lg form-container">
        <div class="card-body">
          <div class="text-center">
            <h1 class="h4 text-white mb-4"><strong>Se connecter</strong></h1>
          </div>
          <form method="POST" action="seconnecter.php">
            <?php if(!empty($erreurLogin)) {?>
              <div class="alert alert-danger  text-center">
                <?php echo $erreurLogin ?>
              </div>
            <?php }?>

            <div class="form-group">
              <label><h5 class="font-weight-bold">Login</h5></label>
              <input type="text" class="form-control" name="login" placeholder="Login" autocomplete="off" required>
            </div>

            <div class="form-group">
              <label><h5><strong>Mot de passe</strong></h5></label>
              <input type="password" class="form-control" name="pwd" placeholder="" autocomplete="off" required>
            </div>
            <hr>
            <button type="submit" class="btn btn-info btn-block">
              <i class="fas fa-sign-in-alt"></i> Se connecter
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
  <script src="../vendor/jquery/jquery.min.js"></script>
  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../js/ruang-admin.min.js"></script>
</body>
</html>
