<?php
// Inclusion des fichiers de sécurité et de connexion à la base de données
require_once("identifier.php");
require_once("connexion.php");

// Récupérer l'identifiant du patient à modifier depuis l'URL (GET)
$Numero_urap = isset($_GET['idU']) ? $_GET['idU'] : null;
if (!$Numero_urap) {
    // Si aucun identifiant n'est fourni, afficher un message d'erreur et arrêter le script
    echo '<div class="alert alert-danger">Aucun patient sélectionné.</div>';
    exit();
}

// Préparer et exécuter la requête pour récupérer les informations du patient
$stmt = $pdo->prepare("SELECT * FROM patient WHERE Numero_urap = ?");
$stmt->execute([$Numero_urap]);
$patient = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$patient) {
    // Si le patient n'existe pas, afficher un message d'erreur et arrêter le script
    echo '<div class="alert alert-danger">Patient introuvable.</div>';
    exit();
}

// Préparer les variables pour pré-remplir le formulaire avec les données du patient
$N_Urap = htmlspecialchars($patient['Numero_urap']); // Clé primaire, non modifiable
$Nom = htmlspecialchars($patient['Nom_patient']);
$Prenom = htmlspecialchars($patient['Prenom_patient']);
$Age = htmlspecialchars($patient['Age']);
$SexeP = htmlspecialchars($patient['Sexe_patient']);
$datenaiss = htmlspecialchars($patient['Date_naissance']);
$contact = htmlspecialchars($patient['Contact_patient']);
$SituaM = htmlspecialchars($patient['Situation_matrimoniale']);
$reside = htmlspecialchars($patient['Lieu_résidence']);
$Precise = htmlspecialchars($patient['Precise']);
$Type_log = htmlspecialchars($patient['Type_logement']);
$NiveauE = htmlspecialchars($patient['Niveau_etude']);
$Profession = htmlspecialchars($patient['Profession']);
$Adresse = isset($patient['Adresse']) ? htmlspecialchars($patient['Adresse']) : '';

// Affichage des messages d'alerte Bootstrap si besoin
if (isset($_GET['success']) && $_GET['success'] == '1') {
    echo '<div class="alert alert-success alert-dismissible fade show mt-4" role="alert">
            <strong>Succès !</strong> Les informations du patient ont été modifiées avec succès.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
          </div>';
}
if (isset($_GET['error'])) {
    $msg = htmlspecialchars($_GET['error']);
    echo '<div class="alert alert-danger alert-dismissible fade show mt-4" role="alert">
            <strong>Erreur !</strong> ' . $msg . '
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
          </div>';
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
                  <form method="POST" action="update_patient.php" class="form border rounded p-4 bg-white shadow-sm">
                    <input type="hidden" name="N_Urap" value="<?php echo $N_Urap ?>">
                    <div class="form-row">
                      <div class="form-group col-md-4">
                        <label><h5>N° Urap :</h5></label>
                        <input type="text" name="N_Urap_display" id="N_Urap" class="form-control" value="<?php echo $N_Urap ?>" readonly>
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
                          <option value="Masculin"<?php if($SexeP=="Masculin") echo " selected"; ?>>Masculin</option>
                          <option value="Féminin"<?php if($SexeP=="Féminin") echo " selected"; ?>>Féminin</option>
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
                        <input type="text" name="Adresse" id="Adresse" class="form-control" value="<?php echo $Adresse ?>" required placeholder="Taper votre adresse" autocomplete="off">
                      </div>
                    </div>
                    <div class="form-row">
                      <div class="form-group col-md-4">
                        <label><h5>Situation Matrimoniale :</h5></label>
                        <select id="SituaM" name="SituaM"  class="form-control">
                          <option value="Marié"<?php if($SituaM=="Marié") echo " selected"; ?>>Marié</option>
                          <option value="Célibataire"<?php if($SituaM=="Célibataire") echo " selected"; ?>>Célibataire</option>
                          <option value="Divorcé"<?php if($SituaM=="Divorcé") echo " selected"; ?>>Divorcé</option>
                          <option value="Veuve"<?php if($SituaM=="Veuve") echo " selected"; ?>>Veuve</option>
                        </select>
                      </div>
                      <div class="form-group col-md-4">
                        <label><h5>Lieu de Résidence :</h5></label>
                        <select name="reside" id="reside"  class="form-control">
                          <option value="Abidjan"<?php if($reside=="Abidjan") echo " selected"; ?>>Abidjan</option>
                          <option value="Hors Abidjan"<?php if($reside=="Hors Abidjan") echo " selected"; ?>>Hors Abidjan</option>
                        </select>
                      </div>
                      <div class="form-group col-md-4">
                        <label><h5>Précisez le lieu de Résidence :</h5></label>
                        <input type="text" name="Precise" id="Precise" class="form-control" value="<?php echo $Precise ?>" placeholder="Précisez le lieu de Résidence" autocomplete="off">
                      </div>
                    </div>
                    <div class="form-row">
                      <div class="form-group col-md-4">
                        <label><h5>Type de logement :</h5></label>
                        <select id="Type_log" name="Type_log" class="form-control">
                          <option value="Baraquement"<?php if($Type_log=="Baraquement") echo " selected"; ?>>Baraquement</option>
                          <option value="Cour commune"<?php if($Type_log=="Cour commune") echo " selected"; ?>>Cour commune</option>
                          <option value="Studio"<?php if($Type_log=="Studio") echo " selected"; ?>>Studio</option>
                          <option value="Villa"<?php if($Type_log=="Villa") echo " selected"; ?>>Villa</option>
                          <option value="Autre"<?php if($Type_log=="Autre") echo " selected"; ?>>Autre</option>
                        </select>
                      </div>
                      <div class="form-group col-md-4">
                        <label><h5>Niveau d'étude :</h5></label>
                        <select id="NiveauE" name="NiveauE"  class="form-control">
                          <option value="Aucun"<?php if($NiveauE=="Aucun") echo " selected"; ?>>Aucun</option>
                          <option value="Primaire"<?php if($NiveauE=="Primaire") echo " selected"; ?>>Primaire</option>
                          <option value="Secondaire"<?php if($NiveauE=="Secondaire") echo " selected"; ?>>Secondaire</option>
                          <option value="Universitaire"<?php if($NiveauE=="Universitaire") echo " selected"; ?>>Universitaire</option>
                        </select>
                      </div>
                      <div class="form-group col-md-4">
                        <label><h5>Profession :</h5></label>
                        <select id="Profession" name="Profession"  class="form-control">
                          <option value="Aucun"<?php if($Profession=="Aucun") echo " selected"; ?>>Aucun</option>
                          <option value="Etudiant"<?php if($Profession=="Etudiant") echo " selected"; ?>>Etudiant</option>
                          <option value="Eleve"<?php if($Profession=="Eleve") echo " selected"; ?>>Eleve</option>
                          <option value="Corps habillé"<?php if($Profession=="Corps habillé") echo " selected"; ?>>Corps habillé</option>
                          <option value="Cadre superieur"<?php if($Profession=="Cadre superieur") echo " selected"; ?>>Cadre supérieur</option>
                          <option value="Cadre moyen"<?php if($Profession=="Cadre moyen") echo " selected"; ?>>Cadre moyen</option>
                          <option value="Secteur informel"<?php if($Profession=="Secteur informel") echo " selected"; ?>>Secteur informel</option>
                          <option value="Sans profession"<?php if($Profession=="Sans profession") echo " selected"; ?>>Sans profession</option>
                          <option value="Retraité"<?php if($Profession=="Retraité") echo " selected"; ?>>Retraité</option>
                        </select>
                      </div>
                    </div>
                    <div class="form-group mt-5 d-flex justify-content-between">
                      <a href="Liste_patient.php" class="btn btn-danger" style="width: 48%;">Annuler</a>
                      <input type="submit" class="btn btn-success" style="width: 48%;" value="Modifier">
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