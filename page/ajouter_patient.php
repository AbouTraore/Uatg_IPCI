<?php 
// Tu peux ajouter du code PHP ici si besoin plus tard
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
          --primary-bg: #ffffff;
          --header-bg: #0047ab;
          --text-color: #0047ab;
          --input-bg: #ffffff;
          --input-text: #333333;
          --button-bg: #0047ab;
          --button-text: #ffffff;
          --button-hover: #1e90ff;
          --border-radius: 8px;
          --box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      }

      body {
          font-family: Arial, sans-serif;
          background-color: var(--primary-bg);
          color: var(--text-color);
      }

      .container-login {
          max-width: 1400px;
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
          border: 1px solid #0047ab;
      }

      .btn {
          background-color: var(--button-bg);
          color: var(--button-text);
      }

      .btn:hover {
          background-color: var(--button-hover);
      }

      .form-group {
          margin-bottom: 20px;
      }

      .header {
          background-color: var(--header-bg);
          color: var(--button-text);
          padding: 10px;
          text-align: center;
          border-radius: var(--border-radius) var(--border-radius) 0 0;
      }
  </style>
</head>

<body>
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
                        <input type="text" name="N_Urap" id="N_Urap" class="form-control" required placeholder="Taper le numero Urap" autocomplete="off">
                      </div>
                      <div class="form-group col-md-4">
                        <label><h5>Nom :</h5></label>
                        <input type="text" name="Nom" id="Nom" class="form-control" required placeholder="Taper votre nom" autocomplete="off">
                      </div>
                      <div class="form-group col-md-4">
                        <label><h5>Prénom :</h5></label>
                        <input type="text" name="Prenom" id="prenom" class="form-control" required placeholder="Taper votre prénom" autocomplete="off">
                      </div>
                    </div>
                    <div class="form-row">
                      <div class="form-group col-md-4">
                        <label><h5>Date de naissance :</h5></label>
                        <input type="date" name="datenaiss" id="datenaiss" class="form-control" required autocomplete="off">
                      </div>
                      <div class="form-group col-md-4">
                        <label><h5>Âge :</h5></label>
                        <input type="text" name="Age" id="Age" class="form-control" readonly placeholder="l'âge du patient">
                      </div>
                      <div class="form-group col-md-4">
                        <label><h5>Sexe :</h5></label>
                        <select name="SexeP" id="SexeP" class="form-control">
                          <option value="Masculin">Masculin</option>
                          <option value="Féminin">Féminin</option>
                        </select>
                      </div>
                    </div>
                    <div class="form-row">
                      <div class="form-group col-md-6">
                        <label><h5>Contact :</h5></label>
                        <input type="text" name="contact" id="contact" class="form-control" required placeholder="Taper votre contact" autocomplete="off">
                      </div>
                      <div class="form-group col-md-6">
                        <label><h5>Adresse :</h5></label>
                        <input type="text" name="Adresse" id="Adresse" class="form-control" required placeholder="Taper votre adresse" autocomplete="off">
                      </div>
                    </div>
                    <div class="form-row">
                      <div class="form-group col-md-4">
                        <label><h5>Situation Matrimoniale :</h5></label>
                        <select id="SituaM" name="SituaM" class="form-control">
                          <option value="Marié">Marié</option>
                          <option value="Célibataire">Célibataire</option>
                          <option value="Divorcé">Divorcé</option>
                          <option value="Veuve">Veuve</option>
                        </select>
                      </div>
                      <div class="form-group col-md-4">
                        <label><h5>Lieu de Résidence :</h5></label>
                        <select name="reside" id="reside" class="form-control">
                          <option value="Abidjan">Abidjan</option>
                          <option value="Hors Abidjan">Hors Abidjan</option>
                        </select>
                      </div>
                      <div class="form-group col-md-4">
                        <label><h5>Précisez le lieu de Résidence :</h5></label>
                        <input type="text" name="Precise" id="Precise" class="form-control" placeholder="Précisez le lieu de Résidence" autocomplete="off">
                      </div>
                    </div>
                    <div class="form-row">
                      <div class="form-group col-md-4">
                        <label><h5>Type de logement :</h5></label>
                        <select id="Type_log" name="Type_log" class="form-control">
                          <option value="Baraquement">Baraquement</option>
                          <option value="Cour commune">Cour commune</option>
                          <option value="Studio">Studio</option>
                          <option value="Villa">Villa</option>
                          <option value="Autre">Autre</option>
                        </select>
                      </div>
                      <div class="form-group col-md-4">
                        <label><h5>Niveau d'étude :</h5></label>
                        <select id="NiveauE" name="NiveauE" class="form-control">
                          <option value="Aucun">Aucun</option>
                          <option value="Primaire">Primaire</option>
                          <option value="Secondaire">Secondaire</option>
                          <option value="Universitaire">Universitaire</option>
                        </select>
                      </div>
                      <div class="form-group col-md-4">
                        <label><h5>Profession :</h5></label>
                        <select id="Profession" name="Profession" class="form-control">
                          <option value="Aucun">Aucun</option>
                          <option value="Etudiant">Etudiant</option>
                          <option value="Eleve">Eleve</option>
                          <option value="Corps habillé">Corps habillé</option>
                          <option value="Cadre superieur">Cadre supérieur</option>
                          <option value="Cadre moyen">Cadre moyen</option>
                          <option value="Secteur informel">Secteur informel</option>
                          <option value="Sans profession">Sans profession</option>
                          <option value="Retraité">Retraité</option>
                        </select>
                      </div>
                    </div>
                    <div class="form-group mt-5">
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

  <!-- JS Scripts -->
  <script src="../vendor/jquery/jquery.min.js"></script>
  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../js/ruang-admin.min.js"></script>

  <!-- Script pour activer/désactiver le champ "Precise" -->
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

      togglePreciseField();
      selectReside.addEventListener("change", togglePreciseField);
    });

    // Script de calcul de l’âge en fonction de la date de naissance
    document.addEventListener("DOMContentLoaded", function () {
      const dateInput = document.getElementById("datenaiss");
      const ageInput = document.getElementById("Age");

      dateInput.addEventListener("input", function () {
        const birthDate = new Date(this.value);
        const today = new Date();
        let age = today.getFullYear() - birthDate.getFullYear();
        const monthDiff = today.getMonth() - birthDate.getMonth();
        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
          age--;
        }
        ageInput.value = isNaN(age) || age < 0 ? "" : age;
      });
    });
  </script>
</body>
</html>
