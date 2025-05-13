<?php 
require_once("identifier.php");
?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link href="../img/logo/logo.jpg" rel="icon">
  <title>Nouvelle Habitude sexuelle</title>
  <!-- Font Awesome -->
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <!-- Bootstrap CSS -->
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <!-- Custom CSS -->
  <link href="../css/ruang-admin.min.css" rel="stylesheet">
</head>

<body class="bg-gradient-login">
  <!-- Register Content -->
  <div class="container-login mt-5"> <!-- Marge supérieure ajoutée -->
    <div class="row justify-content-center">
      <div class="col-xl-5 col-lg-12 col-md-9">
        <div class="card shadow-sm my-5">
          <div class="card-body p-0">
            <div class="row">
              <div class="col-lg-12">
                <div class="login-form">
                  <div class="text-center mb-4">
                    <h1 class="h4 text-gray-900"><hr><h3>Veuillez saisir les données de votre habitude sexuelle </h3></h1><hr>
                  </div>
                  <form method="post" action="insertagence.php" class="form">
                    <!-- Question 1: Quel type de rapport avez-vous ? -->
                    <div class="form-group mb-4"> <!-- Marge inférieure ajoutée -->
                      <label for="rapport">Quel type de rapport avez-vous ?</label>
                      <div class="radio">
                        <label for="hetero"><input type="radio" name="Quel_type_rapport_avez_vous" value="Hétérosexuel" checked /> Hétérosexuel</label><br>
                        <label for="homo"><input type="radio" name="Quel_type_rapport_avez_vous" value="Homosexuel"/> Homosexuel</label><br>
                        <label for="quelquefois"><input type="radio" name="Quel_type_rapport_avez_vous" value="quelque fois" /> Quelque fois</label><br>
                        <label for="bi"><input type="radio" name="Quel_type_rapport_avez_vous" value="Bisexuel"/> Bisexuel</label>
                      </div>
                    </div>

                    <!-- Question 2: Pratiquez-vous la fellation ? -->
                    <div class="form-group mb-4">
                      <label for="fellation">Pratiquez-vous la fellation ?</label>
                      <div class="radio">
                        <label for="jamais"><input type="radio" name="Pratiquez_vous__fellation" value="Jamais" checked /> Jamais</label><br>
                        <label for="rarement"><input type="radio" name="Pratiquez_vous__fellation" value="Rarement"/> Rarement</label><br>
                        <label for="quelquefois"><input type="radio" name="Pratiquez_vous__fellation" value="quelque fois" /> Quelque fois</label><br>
                        <label for="toujours"><input type="radio" name="Pratiquez_vous__fellation" value="Toujours"/> Toujours</label>
                      </div>
                    </div>

                    <!-- Question 3: Avez-vous changé de partenaire ces 2 derniers mois ? -->
                    <div class="form-group mb-4">
                      <label for="partenaire">Avez-vous changé de partenaire ces (2) derniers mois ?</label>
                      <div class="radio">
                        <label for="oui"><input type="radio" name="Avez_vous_changé_partenais_ces_deux_dernier_mois" value="oui" checked /> Oui</label><br>
                        <label for="non"><input type="radio" name="Avez_vous_changé_partenais_ces_deux_dernier_mois" value="Non"/> Non</label>
                      </div>
                    </div>

                    <!-- Question 4: Utilisez-vous un préservatif ? -->
                    <div class="form-group mb-4">
                      <label for="preservatif">Utilisez-vous un préservatif ?</label>
                      <div class="radio">
                        <label for="jamais_preservatif"><input type="radio" name="Utilisez_vous_preservatif" value="Jamais" checked /> Jamais</label><br>
                        <label for="rarement_preservatif"><input type="radio" name="Utilisez_vous_preservatif" value="Rarement"/> Rarement</label><br>
                        <label for="quelquefois_preservatif"><input type="radio" name="Utilisez_vous_preservatif" value="quelque fois" /> Quelque fois</label><br>
                        <label for="toujours_preservatif"><input type="radio" name="Utilisez_vous_preservatif" value="Toujours"/> Toujours</label>
                      </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="form-group">
                      <button type="submit" class="btn btn-primary btn-block">Enregistrer</button>
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

  <!-- Scripts -->
  <script src="../vendor/jquery/jquery.min.js"></script>
  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../js/ruang-admin.min.js"></script>
</body>

</html>
