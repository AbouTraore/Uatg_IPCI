<?php 
require_once("identifier.php");
require_once("connexion.php");


$N_Urap=isset($_GET['Numero_urap'])?$_GET['Numero_urap']:0;
$reqpatient="SELECT * FROM patient where Numero_urap=$N_Urap";
$resultat=$pdo->query($reqpatient);
$patient=$resultat->fetch();
$N_Urap=$patient['Numero_urap'];
$Nom=$patient['Nom_patient']; 
$Prenom=$patient['Prenom_patient']; 
$Age=$patient['Age'];  
$datenaiss=$patient['Date_naissance'];
$SexeP=strtoupper($patient['Sexe_patient']); 
$contact=$patient['Contact_patient'];
$SituaM=$patient['Situation_matrimoniale'];
$Lieu_résidence=$patient['Lieu_résidence']; 
$Precise=$patient['Precise']; 
$Type_log=$patient['Type_logement'];
$NiveauE=$patient['Niveau_etude'];
$Profession=$patient['Profession']; 



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
  <title>Nouveau Utilisateur</title>
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="../css/ruang-admin.min.css" rel="stylesheet">
  <style>
      :root {
          --primary-bg: #ffffff; /* Background blanc */
          --header-bg: #0047ab; /* Bleu foncé */
          --text-color: #0047ab; /* Texte bleu foncé */
          --input-bg: #ffffff; /* Fond d'input blanc */
          --input-text: #333333; /* Texte sombre */
          --button-bg: #0047ab; /* Fond du bouton bleu foncé */
          --button-text: #ffffff; /* Texte du bouton blanc */
          --button-hover: #1e90ff; /* Bleu clair au survol */
          --border-radius: 8px;
          --box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      }

      body {
          font-family: Arial, sans-serif;
          background-color: var(--primary-bg);
          color: var(--text-color);
      }

      .container-login {
          max-width: 1400px; /* Largeur augmentée */
          margin: auto;
          padding: 20px;
      }

      .card {
          border-radius: var(--border-radius);
          box-shadow: var(--box-shadow);
      }

      .login-form h3 {
          color: var(--text-color);
      }

      .form-control {
          border: 1px solid #0047ab; /* Bordure bleue */
      }

      .btn {
          background-color: var(--button-bg);
          color: var(--button-text);
      }

      .btn:hover {
          background-color: var(--button-hover);
      }

      .form-group {
          margin-bottom: 20px; /* Espacement entre les champs */
      }

      .header {
          background-color: var(--header-bg);
          color: var(--button-text);
          padding: 10px;
          text-align: center;
          border-radius: var(--border-radius) var(--border-radius) 0 0; /* Arrondir le haut */
      }
  </style>
</head>

<body>
  <!-- Register Content -->
  <div class="container-login">
    <div class="row justify-content-center">
      <div class="col-xl-10 col-lg-12 col-md-9">
        <div class="card shadow-sm my-5">
          <div class="header">
            <h3 class="text-gray-900 mb-4">Information du patient</h3>
          </div>
          <div class="card-body p-0">
            <div class="row">
              <div class="col-lg-12">
                <div class="login-form">
                  <form method="POST" action="Insert_patient.php" class="form border rounded p-4 bg-white shadow-sm">
                    <div class="form-row">
                      <div class="form-group col-md-4">
                        <label><h5>N° Urap :</h5></label>
                        <input type="text" name="N_Urap" id="N_Urap" class="form-control" value="<?php echo $N_Urap ?>" required placeholder="Taper le numero Urap" autocomplete="off">
                      </div>
                      <div class="form-group col-md-4">
                        <label><h5>Nom :</h5></label>
                        <input type="text" name="Nom" id="Nom" class="form-control" value="<?php echo $Nom ?>" required placeholder="Taper votre nom" autocomplete="off">
                      </div>
                      <div class="form-group col-md-4">
                        <label><h5>Prénom :</h5></label>
                        <input type="text" name="Prenom" id="prenom" class="form-control" value="<?php echo $Prenom ?>" required placeholder="Taper votre prénom" autocomplete="off">
                      </div>
                    </div>
                    <div class="form-row">
                      <div class="form-group col-md-4">
                        <label><h5>Age :</h5></label>
                        <input type="text" name="Age" id="Age" class="form-control" value="<?php echo $Age ?>" required placeholder="Taper votre âge" autocomplete="off">
                      </div>
                      <div class="form-group col-md-4">
                        <label><h5>Sexe :</h5></label>
                        <select name="SexeP"  id="SexeP" class="form-control">
                          <option value="Masculin"<?php if($SexeP=="Masculin") echo "selected" ?>>Masculin</option>
                          <option value="Féminin"<?php if($SexeP=="Féminin") echo "selected" ?>>Féminin</option>
                        </select>
                      </div>
                      <div class="form-group col-md-4">
                        <label><h5>Date de naissance :</h5></label>
                        <input type="date" name="datenaiss" id="datenaiss" value="<?php echo $datenaiss ?>" class="form-control" required autocomplete="off">
                      </div>
                    </div>
                    <div class="form-row">
                      <div class="form-group col-md-6">
                        <label><h5>Contact :</h5></label>
                        <input type="text" name="contact" id="contact" class="form-control" value="<?php echo $contact ?>" required placeholder="Taper votre contact" autocomplete="off">
                      </div>
                      <div class="form-group col-md-6">
                        <label><h5>Adresse :</h5></label>
                        <input type="text" name="Adresse" id="Adresse" class="form-control"  required placeholder="Taper votre adresse" autocomplete="off">
                      </div>
                    </div>
                    <div class="form-row">
                      <div class="form-group col-md-4">
                        <label><h5>Situation Matrimoniale :</h5></label>
                        <select id="SituaM" name="SituaM"  class="form-control">
                          <option value="Marié"<?php if($SituaM=="Marié") echo "selected" ?>>Marié</option>
                          <option value="Célibataire"<?php if($SituaM=="Célibataire") echo "selected" ?>>Célibataire</option>
                          <option value="Divorcé"<?php if($SituaM=="Divorcé") echo "selected" ?>>Divorcé</option>
                          <option value="Veuve"<?php if($SituaM=="Veuve") echo "selected" ?>>Veuve</option>
                        </select>
                      </div>
                      <div class="form-group col-md-4">
                        <label><h5>Lieu de Résidence :</h5></label>
                        <select name="reside" id="reside"  class="form-control">
                          <option value="Abidjan"<?php if($Type_log=="Abidjan") echo "selected" ?>>Abidjan</option>
                          <option value="Hors Abidjan"<?php if($Type_log=="Hors Abidjan") echo "selected" ?>>Hors Abidjan</option>
                        </select>
                      </div>
                      <div class="form-group col-md-4">
                        <label><h5>Précisez le lieu de Résidence :</h5></label>
                        <input type="text" name="Precise" id="Precise" class="form-control" value="<?php echo $Precise ?> " placeholder="Précisez le lieu de Résidence" autocomplete="off">
                      </div>
                    </div>
                    <div class="form-row">
                      <div class="form-group col-md-4">
                        <label><h5>Type de logement :</h5></label>
                        <select id="Type_log" name="Type_log" value="<?php echo $Type_log ?>" class="form-control">
                          <option value="Baraquement"<?php if($Type_log=="Baraquement") echo "selected" ?>>Baraquement</option>
                          <option value="Cour commune"<?php if($Type_log=="Cour commune") echo "selected" ?>>Cour commune</option>
                          <option value="Studio"<?php if($Type_log=="Studio") echo "selected" ?>>Studio</option>
                          <option value="Villa"<?php if($Type_log=="Villa") echo "selected" ?>>Villa</option>
                          <option value="Autre"<?php if($Type_log=="Autre") echo "selected" ?>>Autre</option>
                        </select>
                      </div>
                      <div class="form-group col-md-4">
                        <label><h5>Niveau d'étude :</h5></label>
                        <select id="NiveauE" name="NiveauE"  class="form-control">
                          <option value="Aucun"<?php if($NiveauE=="Aucun") echo "selected" ?>>Aucun</option>
                          <option value="Primaire"<?php if($NiveauE=="Primaire") echo "selected" ?>>Primaire</option>
                          <option value="Secondaire"<?php if($NiveauE=="Secondaire") echo "selected" ?>>Secondaire</option>
                          <option value="Universitaire"<?php if($NiveauE=="Universitaire") echo "selected" ?>>Universitaire</option>
                        </select>
                      </div>
                      <div class="form-group col-md-4">
                        <label><h5>Profession :</h5></label>
                        <select id="Profession" name="Profession"  class="form-control">
                          <option value="Aucun"<?php if($Profession=="Aucun") echo "selected" ?>>Aucun</option>
                          <option value="Etudiant"<?php if($Profession=="Etudiant") echo "selected" ?>>Etudiant</option>
                          <option value="Eleve"<?php if($Profession=="Eleve") echo "selected" ?>>Eleve</option>
                          <option value="Corps habillé"<?php if($Profession=="Corps habillé") echo "selected" ?>>Corps habillé</option>
                          <option value="Cadre superieur"<?php if($Profession=="Cadre superieur") echo "selected" ?>>Cadre supérieur</option>
                          <option value="Cadre moyen"<?php if($Profession=="Cadre moyen") echo "selected" ?>>Cadre moyen</option>
                          <option value="Secteur informel"<?php if($Profession=="Secteur informel") echo "selected" ?>>Secteur informel</option>
                          <option value="Sans profession" <?php if($Profession=="Sans profession") echo "selected" ?>>Sans profession</option>
                          <option value="Retraité"<?php if($Profession=="Retraité") echo "selected" ?>>Retraité</option>
                        </select>
                      </div>
                    </div>
                    <div class="form-group mt-5">
                      <input type="submit" class="btn btn-success btn-block" value="Modifier">
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

  <!-- JS Scripts -->
  <script src="../vendor/jquery/jquery.min.js"></script>
  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../js/ruang-admin.min.js"></script>

  <!-- Script personnalisé pour activer/désactiver le champ "Precise" -->
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const selectReside = document.getElementById("reside");
      const inputPrecise = document.getElementById("Precise");

      function togglePreciseField() {
        if (selectReside.value === "Abidjan") {
          inputPrecise.disabled = true;
          inputPrecise.value = "";
          inputPrecise.classList.add("bg-second");
        } else {
          inputPrecise.disabled = false;
          inputPrecise.classList.remove("bg-light");
        }
      }

      togglePreciseField(); // Au chargement
      selectReside.addEventListener("change", togglePreciseField);
    });
  </script>
</body>

</html>