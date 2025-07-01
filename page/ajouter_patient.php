<?php 
// Tu peux ajouter du code PHP ici si besoin plus tard
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Ajouter Patient</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <style>
      :root {
      --primary: #0047ab;
      --primary-light: #1e90ff;
      --primary-dark: #003380;
      --secondary: #f8fafc;
      --accent: #10b981;
      --danger: #ef4444;
      --warning: #f59e0b;
      --gray-50: #f9fafb;
      --gray-100: #f3f4f6;
      --gray-200: #e5e7eb;
      --gray-300: #d1d5db;
      --gray-400: #9ca3af;
      --gray-500: #6b7280;
      --gray-600: #4b5563;
      --gray-700: #374151;
      --gray-800: #1f2937;
      --gray-900: #111827;
      --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
    }
    body {
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      min-height: 100vh;
      padding: 20px;
      color: var(--gray-800);
    }
    .container {
      max-width: 1000px;
      margin: 0 auto;
      background: rgba(255, 255, 255, 0.97);
      border-radius: 24px;
      box-shadow: var(--shadow-xl);
      overflow: hidden;
      padding: 0 0 48px 0;
    }
    .header {
      background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
      color: white;
      padding: 32px 32px 24px 32px;
      text-align: center;
      position: relative;
    }
    .header h1 {
      font-size: 2rem;
      font-weight: 700;
      margin: 0;
    }
    .header p {
      font-size: 1.1rem;
      opacity: 0.9;
      margin-top: 8px;
    }
    .btn-retour {
      position: absolute;
      left: 24px;
      top: 32px;
      background: var(--gray-100);
      border: none;
      border-radius: 50px;
      padding: 10px 18px;
      font-size: 1.1em;
      box-shadow: 0 2px 8px rgba(0,0,0,0.07);
      color: var(--primary);
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: 8px;
      transition: background 0.2s;
      z-index: 2;
    }
    .content-area {
      padding: 48px 48px 32px 48px;
      background: white;
    }
    .form-section {
      margin-bottom: 32px;
    }
    .section-title {
      font-size: 1.25rem;
      font-weight: 600;
      color: var(--gray-800);
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .form-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 20px;
    }
    .form-field {
      position: relative;
    }
    .form-label {
      display: block;
      font-size: 0.875rem;
      font-weight: 500;
      color: var(--gray-700);
      margin-bottom: 6px;
    }
    .form-input {
      width: 100%;
      padding: 12px 16px;
      border: 2px solid var(--gray-200);
      border-radius: 12px;
      font-size: 14px;
      transition: all 0.2s ease;
      background: white;
    }
    .form-input:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(0, 71, 171, 0.1);
    }
    .form-input:disabled {
      background: var(--gray-50);
      color: var(--gray-500);
    }
    .form-select {
      width: 100%;
      padding: 12px 16px;
      border: 2px solid var(--gray-200);
      border-radius: 12px;
      font-size: 14px;
      background: white;
      cursor: pointer;
      transition: all 0.2s ease;
    }
    .form-select:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(0, 71, 171, 0.1);
    }
    .actions {
      display: flex;
      gap: 16px;
      justify-content: center;
      flex-wrap: wrap;
      margin-top: 32px;
    }
    .btn {
      padding: 14px 28px;
      border: none;
      border-radius: 12px;
      font-size: 14px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.2s ease;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      min-width: 120px;
      justify-content: center;
    }
    .btn-primary {
      background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
      color: white;
      box-shadow: var(--shadow-xl);
    }
    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: var(--shadow-xl);
    }
    .btn-secondary {
      background: white;
      color: var(--gray-700);
      border: 2px solid var(--gray-200);
    }
    .btn-secondary:hover {
      background: var(--gray-50);
      border-color: var(--gray-300);
      transform: translateY(-1px);
    }
    @media (max-width: 768px) {
      .header {
        padding: 24px 20px;
      }
      .header h1 {
        font-size: 2rem;
      }
      .content-area {
          padding: 20px;
      }
      .form-grid {
        grid-template-columns: 1fr;
      }
      .actions {
        flex-direction: column;
      }
      .btn {
        width: 100%;
      }
    }
    @media (max-width: 1100px) {
      .container {
        max-width: 98vw;
      }
      .content-area {
        padding: 32px 10px 24px 10px;
      }
      }
  </style>
</head>

<body>
  <div class="container">
          <div class="header">
      <button onclick="window.history.back()" class="btn-retour"><i class="fas fa-arrow-left"></i> Retour</button>
      <h1><i class="fas fa-user-plus"></i> Ajouter Patient</h1>
      <p>Remplissez le formulaire pour ajouter un nouveau patient</p>
          </div>
    <div class="content-area">
      <div id="formMessage" style="display:none;max-width:600px;margin:32px auto 0 auto;padding:16px 24px;border-radius:12px;font-weight:600;font-size:1.05rem;text-align:center;transition:opacity 0.3s;opacity:0;"></div>
      <form method="POST" action="Insert_patient.php" id="ajoutPatientForm">
        <div class="form-section">
          <h2 class="section-title"><i class="fas fa-user"></i> Informations personnelles</h2>
          <div class="form-grid">
            <div class="form-field">
              <label class="form-label">N° Urap</label>
              <input type="text" class="form-input" id="N_Urap" name="N_Urap" required placeholder="Taper le numéro Urap">
                      </div>
            <div class="form-field">
              <label class="form-label">Nom</label>
              <input type="text" class="form-input" id="Nom" name="Nom" required placeholder="Taper le nom">
                      </div>
            <div class="form-field">
              <label class="form-label">Prénom</label>
              <input type="text" class="form-input" id="Prenom" name="Prenom" required placeholder="Taper le prénom">
                      </div>
            <div class="form-field">
              <label class="form-label">Date de naissance</label>
              <input type="date" class="form-input" id="datenaiss" name="datenaiss" required>
                    </div>
            <div class="form-field">
              <label class="form-label">Âge</label>
              <input type="text" class="form-input" id="Age" name="Age" required readonly placeholder="L'âge du patient">
                      </div>
            <div class="form-field">
              <label class="form-label">Sexe</label>
              <select class="form-select" id="SexeP" name="SexeP">
                          <option value="Masculin">Masculin</option>
                          <option value="Féminin">Féminin</option>
                        </select>
                      </div>
                    </div>
                      </div>
        <div class="form-section">
          <h2 class="section-title"><i class="fas fa-address-card"></i> Contact et adresse</h2>
          <div class="form-grid">
            <div class="form-field">
              <label class="form-label">Contact</label>
              <input type="tel" class="form-input" id="contact" name="contact" required placeholder="Taper le contact">
                      </div>
            <div class="form-field">
              <label class="form-label">Adresse</label>
              <input type="text" class="form-input" id="Adresse" name="Adresse" required placeholder="Taper l'adresse">
                    </div>
            <div class="form-field">
              <label class="form-label">Lieu de résidence</label>
              <select class="form-select" id="reside" name="reside">
                          <option value="Abidjan">Abidjan</option>
                          <option value="Hors Abidjan">Hors Abidjan</option>
                        </select>
                      </div>
            <div class="form-field">
              <label class="form-label">Précisez le lieu</label>
              <input type="text" class="form-input" id="Precise" name="Precise" placeholder="Précisez le lieu de résidence">
            </div>
                      </div>
                    </div>
        <div class="form-section">
          <h2 class="section-title"><i class="fas fa-info-circle"></i> Informations sociales</h2>
          <div class="form-grid">
            <div class="form-field">
              <label class="form-label">Situation matrimoniale</label>
              <select class="form-select" id="SituaM" name="SituaM">
                <option value="Célibataire">Célibataire</option>
                <option value="Marié">Marié(e)</option>
                <option value="Divorcé">Divorcé(e)</option>
                <option value="Veuve">Veuf/Veuve</option>
              </select>
            </div>
            <div class="form-field">
              <label class="form-label">Type de logement</label>
              <select class="form-select" id="Type_log" name="Type_log">
                <option value="Studio">Studio</option>
                          <option value="Cour commune">Cour commune</option>
                          <option value="Villa">Villa</option>
                <option value="Baraquement">Baraquement</option>
                          <option value="Autre">Autre</option>
                        </select>
                      </div>
            <div class="form-field">
              <label class="form-label">Niveau d'étude</label>
              <select class="form-select" id="NiveauE" name="NiveauE">
                          <option value="Aucun">Aucun</option>
                          <option value="Primaire">Primaire</option>
                          <option value="Secondaire">Secondaire</option>
                          <option value="Universitaire">Universitaire</option>
                        </select>
                      </div>
            <div class="form-field">
              <label class="form-label">Profession</label>
              <select class="form-select" id="Profession" name="Profession">
                          <option value="Aucun">Aucun</option>
                <option value="Etudiant">Étudiant</option>
                <option value="Eleve">Élève</option>
                          <option value="Corps habillé">Corps habillé</option>
                          <option value="Cadre superieur">Cadre supérieur</option>
                          <option value="Cadre moyen">Cadre moyen</option>
                          <option value="Secteur informel">Secteur informel</option>
                          <option value="Sans profession">Sans profession</option>
                          <option value="Retraité">Retraité</option>
                        </select>
                      </div>
                    </div>
                    </div>
        <div class="actions">
          <a href="Liste_patient.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Retour</a>
          <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Créer</button>
        </div>
      </form>
    </div>
  </div>
  <div id="saveMessage"></div>
  <script>
    // Calcul automatique de l'âge
    document.addEventListener('DOMContentLoaded', function() {
      const dateInput = document.getElementById('datenaiss');
      const ageInput = document.getElementById('Age');
      function calculateAge() {
        if (dateInput.value) {
          const birthDate = new Date(dateInput.value);
        const today = new Date();
        let age = today.getFullYear() - birthDate.getFullYear();
        const monthDiff = today.getMonth() - birthDate.getMonth();
        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
          age--;
        }
          ageInput.value = age;
        } else {
          ageInput.value = '';
        }
      }
      dateInput.addEventListener('change', calculateAge);
      dateInput.addEventListener('input', calculateAge);
    });
    // Validation et message stylisé
    document.addEventListener('DOMContentLoaded', function() {
      const form = document.getElementById('ajoutPatientForm');
      const msgDiv = document.getElementById('formMessage');
      form.addEventListener('submit', function(e) {
        let error = '';
        if (!form.N_Urap.value.trim() || !form.Nom.value.trim() || !form.Prenom.value.trim() || !form.datenaiss.value.trim() || !form.SexeP.value.trim() || !form.contact.value.trim()) {
          error = 'Veuillez remplir tous les champs obligatoires.';
        }
        if (error) {
          e.preventDefault();
          msgDiv.innerHTML = '<i class="fas fa-exclamation-triangle" style="color:#ef4444;margin-right:10px;"></i>' + error;
          msgDiv.style.background = '#ffeaea';
          msgDiv.style.color = '#b91c1c';
          msgDiv.style.border = '1.5px solid #fca5a5';
          msgDiv.style.display = 'block';
          msgDiv.style.opacity = '1';
          setTimeout(() => { msgDiv.style.opacity = '0'; }, 3000);
          return false;
        }
      });
    });
  </script>
</body>
</html>
