<?php 
// Système de messages pour afficher les erreurs et succès
$message = '';
$messageType = '';

if (isset($_GET['error'])) {
    $message = urldecode($_GET['error']);
    $messageType = 'error';
} elseif (isset($_GET['success'])) {
    $message = urldecode($_GET['success']);
    $messageType = 'success';
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Ajouter Patient - UATG</title>
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
      --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
      --shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
      --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
      --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
      --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
    }
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    body {
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      min-height: 100vh;
      padding: 20px;
      color: var(--gray-800);
    }
    .container {
      max-width: 1400px;
      margin: 0 auto;
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      border-radius: 24px;
      box-shadow: var(--shadow-xl);
      overflow: hidden;
      animation: slideUp 0.6s ease-out;
    }
    @keyframes slideUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    .header {
      background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
      color: white;
      padding: 32px;
      text-align: center;
      position: relative;
      overflow: hidden;
    }
    .header::before {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
      animation: rotate 20s linear infinite;
    }
    @keyframes rotate {
      from { transform: rotate(0deg); }
      to { transform: rotate(360deg); }
    }
    .header h1 {
      font-size: 2.5rem;
      font-weight: 700;
      letter-spacing: -0.025em;
      position: relative;
      z-index: 1;
    }
    .header p {
      font-size: 1.1rem;
      opacity: 0.9;
      margin-top: 8px;
      position: relative;
      z-index: 1;
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
    .btn-retour:hover {
      background: var(--gray-200);
    }
    .main-content {
      display: flex;
      min-height: 600px;
      height: 100%;
    }
    .sidebar {
      background: linear-gradient(135deg, #f3f8ff 0%, #e5e7eb 100%);
      border-right: 2px solid var(--primary-light);
      padding: 32px 24px 32px 24px;
      overflow-y: auto;
      height: 100%;
      display: flex;
      flex-direction: column;
      min-width: 380px;
      box-shadow: 4px 0 18px -8px #0047ab22;
      border-radius: 24px 0 0 24px;
    }
    .content-area {
      flex: 1;
      padding: 40px 32px;
      background: white;
      border-radius: 0 24px 24px 0;
      box-shadow: 0 2px 12px 0 #0047ab11;
      min-height: 600px;
      display: flex;
      flex-direction: column;
      justify-content: flex-start;
    }
    .search-container {
      position: relative;
      margin-bottom: 28px;
    }
    .search-input {
      width: 100%;
      padding: 12px 16px 12px 44px;
      border: 2px solid var(--primary-light);
      border-radius: 12px;
      font-size: 15px;
      background: #f8fafc;
      transition: all 0.2s;
    }
    .search-input:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 3px #bae6fd55;
    }
    .search-icon {
      position: absolute;
      left: 16px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--primary-light);
    }
    .patients-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 18px;
      padding-bottom: 8px;
      border-bottom: 1.5px solid var(--primary-light);
    }
    .patients-title {
      font-size: 1.1rem;
      font-weight: 700;
      color: var(--primary-dark);
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .patients-count {
      background: var(--primary);
      color: white;
      padding: 4px 14px;
      border-radius: 20px;
      font-size: 13px;
      font-weight: 600;
      box-shadow: 0 1px 4px 0 #0047ab22;
    }
    .patient-card {
      background: white;
      border: 2px solid var(--gray-200);
      border-radius: 16px;
      padding: 16px 16px 8px 16px;
      margin-bottom: 18px;
      cursor: pointer;
      transition: all 0.3s cubic-bezier(.4,2,.6,1);
      position: relative;
      overflow: hidden;
      box-shadow: 0 2px 8px 0 #0047ab0a;
      animation: slideInCard 0.4s ease-out;
    }
    .patient-card.selected {
      border-color: var(--primary);
      background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
      color: white;
      box-shadow: 0 6px 24px 0 #0047ab22;
    }
    .patient-name {
      font-weight: 600;
      font-size: 1rem;
      margin-bottom: 4px;
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .patient-time {
      background: rgba(16, 185, 129, 0.1);
      color: var(--accent);
      padding: 2px 6px;
      border-radius: 8px;
      font-size: 10px;
      font-weight: 500;
    }
    .patient-details {
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-size: 0.95rem;
      opacity: 0.9;
      margin-bottom: 8px;
    }
    .patient-age {
      display: flex;
      align-items: center;
      gap: 4px;
    }
    .patient-actions {
      display: flex;
      justify-content: center;
      align-items: center;
      margin-top: 6px;
    }
    .btn-patient-detail {
      background: linear-gradient(135deg, #e0e7ff 0%, #bae6fd 100%);
      border: none;
      color: var(--primary);
      cursor: pointer;
      font-size: 1.4em;
      width: 38px;
      height: 38px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 2px 8px 0 #0047ab22;
      transition: background 0.2s, color 0.2s, box-shadow 0.2s;
      margin: 0 auto;
    }
    .btn-patient-detail:hover {
      background: linear-gradient(135deg, #10b981 0%, #1e90ff 100%);
      color: white;
      box-shadow: 0 4px 16px 0 #10b98133;
    }
    .form-section {
      margin-bottom: 32px;
      opacity: 0;
      transform: translateY(20px);
      animation: slideInSection 0.6s ease-out forwards;
    }
    .form-section:nth-child(2) { animation-delay: 0.1s; }
    .form-section:nth-child(3) { animation-delay: 0.2s; }
    .form-section:nth-child(4) { animation-delay: 0.3s; }
    @keyframes slideInSection {
      to {
        opacity: 1;
        transform: translateY(0);
      }
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
      appearance: none;
      background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
      background-position: right 12px center;
      background-repeat: no-repeat;
      background-size: 16px;
      padding-right: 48px;
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
      padding-top: 24px;
      border-top: 2px solid var(--gray-200);
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
      box-shadow: var(--shadow);
    }
    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: var(--shadow-lg);
      text-decoration: none;
      color: white;
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
      text-decoration: none;
      color: var(--gray-700);
    }
    .btn-accent {
      background: linear-gradient(135deg, var(--accent) 0%, #059669 100%);
      color: white;
      box-shadow: var(--shadow);
    }
    .btn-accent:hover {
      transform: translateY(-2px);
      box-shadow: var(--shadow-lg);
      text-decoration: none;
      color: white;
    }
    .btn-accent:disabled,
    .btn-accent[style*="opacity: 0.5"] {
      background: #9ca3af !important;
      color: #6b7280 !important;
      cursor: not-allowed !important;
      transform: none !important;
      box-shadow: none !important;
    }
    .message-container {
      max-width: 600px;
      margin: 0 auto 24px auto;
      padding: 16px 24px;
      border-radius: 12px;
      font-weight: 600;
      font-size: 1.05rem;
      text-align: center;
      display: flex;
      align-items: center;
      gap: 12px;
      animation: fadeInSlide 0.3s ease-out;
    }
    @keyframes fadeInSlide {
      from {
        opacity: 0;
        transform: translateY(-10px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    .message-error {
      background: rgba(239, 68, 68, 0.1);
      color: #dc2626;
      border: 1px solid rgba(239, 68, 68, 0.2);
      border-left: 4px solid #ef4444;
    }
    .message-success {
      background: rgba(34, 197, 94, 0.1);
      color: #059669;
      border: 1px solid rgba(34, 197, 94, 0.2);
      border-left: 4px solid #10b981;
    }
    .empty-patients {
      text-align: center;
      padding: 40px 20px;
      color: var(--gray-500);
      animation: fadeIn 0.5s ease-out;
    }
    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }
    .empty-patients i {
      font-size: 3rem;
      margin-bottom: 16px;
      opacity: 0.5;
    }
    
    /* Styles pour la scrollbar de la liste des patients */
    #patientsList {
      max-height: calc(100vh - 400px);
      min-height: 200px;
      overflow-y: auto;
      scrollbar-width: thin;
      scrollbar-color: #cbd5e1 #f1f5f9;
      flex: 1;
    }
    
    #patientsList::-webkit-scrollbar {
      width: 6px;
    }
    
    #patientsList::-webkit-scrollbar-track {
      background: #f1f5f9;
      border-radius: 3px;
    }
    
    #patientsList::-webkit-scrollbar-thumb {
      background: #cbd5e1;
      border-radius: 3px;
    }
    
    #patientsList::-webkit-scrollbar-thumb:hover {
      background: #94a3b8;
    }
    
    .refresh-btn {
      position: absolute;
      right: 12px;
      top: 12px;
      background: var(--gray-100);
      border: none;
      border-radius: 50%;
      width: 32px;
      height: 32px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--gray-600);
      cursor: pointer;
      transition: all 0.2s ease;
    }
    .refresh-btn:hover {
      background: var(--primary);
      color: white;
      transform: rotate(90deg);
    }
    
    @media (max-width: 1024px) {
      .main-content {
        grid-template-columns: 1fr;
      }
      .sidebar {
        border-right: none;
        border-bottom: 1px solid var(--gray-200);
        max-height: 300px;
      }
    }
    @media (max-width: 768px) {
      body {
        padding: 10px;
      }
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
      .btn-retour {
        position: static;
        margin-bottom: 16px;
        width: fit-content;
      }
    }
    .btn-retour-global {
      background: linear-gradient(135deg, #e0e7ff 0%, #bae6fd 100%);
      color: #0047ab;
      border: none;
      border-radius: 30px;
      padding: 12px 32px;
      font-size: 1.1em;
      font-weight: 600;
      box-shadow: 0 2px 8px 0 #0047ab22;
      cursor: pointer;
      transition: background 0.2s, color 0.2s;
      margin-top: 12px;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .btn-retour-global:hover {
      background: linear-gradient(135deg, #10b981 0%, #1e90ff 100%);
      color: white;
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="header">
      <h1><i class="fas fa-user-plus"></i> Ajouter Patient</h1>
      <p>Remplissez le formulaire pour ajouter un nouveau patient</p>
    </div>
    
    <div class="main-content">
      <div class="sidebar">
        <div class="search-container">
          <input type="text" class="search-input" placeholder="Rechercher un patient..." id="searchInput">
          <i class="fas fa-search search-icon"></i>
          <button class="refresh-btn" onclick="loadPatients()" title="Actualiser la liste">
            <i class="fas fa-sync-alt"></i>
          </button>
        </div>

        <div class="patients-header">
          <h3 class="patients-title">
            <i class="fas fa-calendar-day"></i>
            Patients du jour
            <span class="today-indicator" id="todayDate"></span>
          </h3>
          <span class="patients-count" id="patientsCount">0</span>
        </div>

        <div id="patientsList">
          <div class="empty-patients">
            <i class="fas fa-users"></i>
            <p>Chargement des patients...</p>
          </div>
        </div>
      </div>

      <div class="content-area">
        <?php if (!empty($message)): ?>
          <div class="message-container message-<?php echo $messageType; ?>">
            <i class="fas fa-<?php echo $messageType === 'error' ? 'exclamation-triangle' : 'check-circle'; ?>"></i>
            <?php echo htmlspecialchars($message); ?>
          </div>
        <?php endif; ?>
        
        <form method="POST" action="Insert_patient.php" id="ajoutPatientForm">
          <div class="form-section">
            <h2 class="section-title"><i class="fas fa-user"></i> Informations personnelles</h2>
            <div class="form-grid">
              <div class="form-field">
                <label class="form-label">N° Urap</label>
                <input type="text" class="form-input" id="Numero_urap" name="Numero_urap" required placeholder="Taper le numéro Urap">
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
                <select class="form-select" id="reside" name="reside" onchange="togglePreciseField()">
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
            <button type="button" class="btn btn-secondary" onclick="clearForm()">
              <i class="fas fa-plus"></i>
              Nouveau
            </button>
            <a href="Liste_patient.php" class="btn btn-secondary">
              <i class="fas fa-list"></i>
              Liste complète
            </a>
            <a href="nouveau_dossier.php" class="btn btn-accent">
              <i class="fas fa-folder-plus"></i>
              Nouveau dossier
            </a>
            <a href="#" class="btn btn-accent" id="btnNouvelleVisite" style="opacity: 0.5; pointer-events: none; cursor: not-allowed;">
              <i class="fas fa-stethoscope"></i>
              Nouvelle visite
            </a>
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-save"></i>
              Enregistrer
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
  
  <!-- Bouton retour global en bas de page -->
  <div style="width:100%;display:flex;justify-content:center;margin:32px 0 0 0;">
    <button onclick="window.history.back()" class="btn-retour-global">
      <i class="fas fa-arrow-left"></i> Retour
    </button>
  </div>
  
  <script>
    // Afficher la date du jour
    function updateTodayDate() {
      const today = new Date();
      const options = { day: '2-digit', month: '2-digit' };
      document.getElementById('todayDate').textContent = today.toLocaleDateString('fr-FR', options);
    }

    // Charger la liste des patients du jour
    function loadPatients() {
      console.log('Chargement des patients du jour...');
      
      // Animation du bouton refresh
      const refreshBtn = document.querySelector('.refresh-btn i');
      refreshBtn.style.animation = 'spin 1s linear infinite';
      
      fetch('get_patients_list.php')
        .then(response => response.json())
        .then(data => {
          const patientsList = document.getElementById('patientsList');
          const patientsCount = document.getElementById('patientsCount');
          
          console.log('Données reçues:', data);
          
          if (data.error) {
            console.error('Erreur:', data.error);
            patientsList.innerHTML = '<div class="empty-patients"><i class="fas fa-exclamation-triangle"></i><p>Erreur de chargement</p></div>';
            refreshBtn.style.animation = '';
            return;
          }
          
          if (data.length === 0) {
            console.log('Aucun patient trouvé pour aujourd\'hui');
            patientsList.innerHTML = '<div class="empty-patients"><i class="fas fa-users"></i><p>Aucun patient enregistré</p><small>Les nouveaux patients apparaîtront ici</small></div>';
            patientsCount.textContent = '0';
            refreshBtn.style.animation = '';
            return;
          }
          
          console.log('Patients trouvés aujourd\'hui:', data.length);
          patientsCount.textContent = data.length;
          let html = '';
          
          data.forEach((patient, index) => {
            // Sécuriser les champs pour éviter 'undefined'
            const nom = patient.Nom_patient ? patient.Nom_patient : '';
            const prenom = patient.Prenom_patient ? patient.Prenom_patient : '';
            const numeroUrap = patient.Numero_urap ? patient.Numero_urap : '';
            const age = patient.Age ? patient.Age : '';
            const heure = patient.heure_creation ? patient.heure_creation : '';
            html += `
              <div class="patient-card" data-name="${(nom + ' ' + prenom).toLowerCase()}" data-urap="${numeroUrap}" onclick="selectPatient(${index})" style="animation-delay: ${index * 0.1}s">
                <div class="patient-name">
                  ${nom} ${prenom}
                  <span class="patient-time">${heure}</span>
                </div>
                <div class="patient-details">
                  <span>N° ${numeroUrap}</span>
                  <span class="patient-age">
                    <i class="fas fa-calendar-alt"></i>
                    ${age} ans
                  </span>
                </div>
              </div>
            `;
          });
          
          patientsList.innerHTML = html;
          
          // Stocker les données pour utilisation dans selectPatient
          window.patientsData = data;
          
          refreshBtn.style.animation = '';
          setTimeout(checkCurrentPatientInList, 0);
        })
        .catch(error => {
          console.error('Erreur:', error);
          document.getElementById('patientsList').innerHTML = '<div class="empty-patients"><i class="fas fa-exclamation-triangle"></i><p>Erreur de chargement</p></div>';
          refreshBtn.style.animation = '';
        });
    }

    // Sélectionner un patient
    function selectPatient(index) {
      if (!window.patientsData || !window.patientsData[index]) return;
      
      const patient = window.patientsData[index];
      
      // Mettre à jour les classes de sélection
      document.querySelectorAll('.patient-card').forEach(card => {
        card.classList.remove('selected');
      });
      document.querySelectorAll('.patient-card')[index].classList.add('selected');
      
      // Remplir le formulaire
      document.getElementById('N_Urap').value = patient.Numero_urap || '';
      document.getElementById('Nom').value = patient.Nom_patient || '';
      document.getElementById('Prenom').value = patient.Prenom_patient || '';
      document.getElementById('Age').value = patient.Age || '';
      document.getElementById('SexeP').value = patient.Sexe_patient || 'Masculin';
      document.getElementById('datenaiss').value = patient.Date_naissance || '';
      document.getElementById('contact').value = patient.Contact_patient || '';
      document.getElementById('Adresse').value = patient.Adresse || '';
      document.getElementById('SituaM').value = patient.Situation_matrimoniale || 'Célibataire';
      document.getElementById('reside').value = patient.Lieu_résidence || 'Abidjan';
      document.getElementById('Precise').value = patient.Precise || '';
      document.getElementById('Type_log').value = patient.Type_logement || 'Studio';
      document.getElementById('NiveauE').value = patient.Niveau_etude || 'Aucun';
      document.getElementById('Profession').value = patient.Profession || 'Aucun';
      
      // Gérer le champ Precise
      togglePreciseField();
      
      // Activer le bouton "Nouvelle visite"
      const btnNouvelleVisite = document.getElementById('btnNouvelleVisite');
      btnNouvelleVisite.style.pointerEvents = 'auto';
      btnNouvelleVisite.style.opacity = '1';
      btnNouvelleVisite.style.cursor = 'pointer';
      btnNouvelleVisite.href = 'visite.php?idU=' + encodeURIComponent(patient.Numero_urap);
    }
    
    // Vérifier si le patient actuel est dans la liste
    function checkCurrentPatientInList() {
      const nomInput = document.getElementById('Nom');
      const prenomInput = document.getElementById('Prenom');
      const btnNouvelleVisite = document.getElementById('btnNouvelleVisite');
      
      if (!nomInput || !prenomInput || !btnNouvelleVisite) return;
      
      const currentPatientName = (nomInput.value.trim() + ' ' + prenomInput.value.trim()).toLowerCase();
      const patientCards = document.querySelectorAll('.patient-card');
      let patientFound = false;
      let patientUrap = '';
      
      patientCards.forEach(card => {
        const cardName = card.getAttribute('data-name');
        if (cardName === currentPatientName && currentPatientName.trim().length > 1) {
          patientFound = true;
          patientUrap = card.getAttribute('data-urap');
        }
      });
      
      if (patientFound) {
        btnNouvelleVisite.style.pointerEvents = 'auto';
        btnNouvelleVisite.style.opacity = '1';
        btnNouvelleVisite.style.cursor = 'pointer';
        btnNouvelleVisite.href = 'visite.php?idU=' + encodeURIComponent(patientUrap);
      } else {
        btnNouvelleVisite.style.pointerEvents = 'none';
        btnNouvelleVisite.style.opacity = '0.5';
        btnNouvelleVisite.style.cursor = 'not-allowed';
        btnNouvelleVisite.href = '#';
      }
    }

    // Recherche de patients
    function setupSearch() {
      const searchInput = document.getElementById('searchInput');
      searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const patientCards = document.querySelectorAll('.patient-card');
        let visibleCount = 0;
        
        patientCards.forEach(card => {
          const patientName = card.getAttribute('data-name');
          const patientUrap = card.getAttribute('data-urap');
          if (patientName.includes(searchTerm) || patientUrap.includes(searchTerm)) {
            card.style.display = 'block';
            visibleCount++;
          } else {
            card.style.display = 'none';
          }
        });
        
        document.getElementById('patientsCount').textContent = visibleCount;
      });
    }

    // Gérer le champ "Precise"
    function togglePreciseField() {
      const selectReside = document.getElementById("reside");
      const inputPrecise = document.getElementById("Precise");

      if (selectReside.value === "Abidjan") {
        inputPrecise.disabled = true;
        inputPrecise.value = "";
        inputPrecise.style.backgroundColor = "var(--gray-50)";
        inputPrecise.placeholder = "Non applicable pour Abidjan";
      } else {
        inputPrecise.disabled = false;
        inputPrecise.style.backgroundColor = "white";
        inputPrecise.placeholder = "Précisez le lieu de résidence";
      }
    }

    // Vider le formulaire
    function clearForm() {
      document.getElementById('ajoutPatientForm').reset();
      document.getElementById('Age').value = '';
      
      // Désélectionner tous les patients
      document.querySelectorAll('.patient-card').forEach(card => {
        card.classList.remove('selected');
      });
      
      // Désactiver le bouton "Nouvelle visite"
      const btnNouvelleVisite = document.getElementById('btnNouvelleVisite');
      btnNouvelleVisite.style.pointerEvents = 'none';
      btnNouvelleVisite.style.opacity = '0.5';
      btnNouvelleVisite.style.cursor = 'not-allowed';
      btnNouvelleVisite.href = '#';
      
      togglePreciseField();
    }

    // Calcul automatique de l'âge
    function calculateAge() {
      const dateInput = document.getElementById('datenaiss');
      const ageInput = document.getElementById('Age');
      
      if (dateInput.value) {
        const birthDate = new Date(dateInput.value);
        const today = new Date();
        let age = today.getFullYear() - birthDate.getFullYear();
        const monthDiff = today.getMonth() - birthDate.getMonth();
        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
          age--;
        }
        ageInput.value = age >= 0 ? age : '';
      } else {
        ageInput.value = '';
      }
    }

    // Initialisation
    document.addEventListener('DOMContentLoaded', function() {
      updateTodayDate();
      
      const dateInput = document.getElementById('datenaiss');
      dateInput.addEventListener('change', calculateAge);
      dateInput.addEventListener('input', calculateAge);
      
      // Charger les patients et configurer la recherche
      loadPatients();
      setupSearch();
      
      // Recharger la liste toutes les 30 secondes pour rester à jour
      setInterval(loadPatients, 30000);
      
      // Vérifier le patient quand les champs nom et prénom changent
      const nomInput = document.getElementById('Nom');
      const prenomInput = document.getElementById('Prenom');
      
      if (nomInput && prenomInput) {
        nomInput.addEventListener('input', checkCurrentPatientInList);
        prenomInput.addEventListener('input', checkCurrentPatientInList);
      }
      
      // Initialiser le champ Precise
      togglePreciseField();
      
      // Effacer le message après 5 secondes si succès
      <?php if ($messageType === 'success'): ?>
        setTimeout(() => {
          const messageDiv = document.querySelector('.message-container');
          if (messageDiv) {
            messageDiv.style.opacity = '0';
            setTimeout(() => messageDiv.remove(), 300);
          }
          clearForm();
        }, 5000);
      <?php endif; ?>
    });

    // Ajouter la fonction fillPatientForm
    function fillPatientForm(index) {
      if (!window.patientsData || !window.patientsData[index]) return;
      const patient = window.patientsData[index];
      document.getElementById('N_Urap').value = patient.Numero_urap || '';
      document.getElementById('Nom').value = patient.Nom_patient || '';
      document.getElementById('Prenom').value = patient.Prenom_patient || '';
      document.getElementById('Age').value = patient.Age || '';
      document.getElementById('SexeP').value = patient.Sexe_patient || 'Masculin';
      document.getElementById('datenaiss').value = patient.Date_naissance || '';
      document.getElementById('contact').value = patient.Contact_patient || '';
      document.getElementById('Adresse').value = patient.Adresse || '';
      document.getElementById('SituaM').value = patient.Situation_matrimoniale || 'Célibataire';
      document.getElementById('reside').value = patient.Lieu_résidence || 'Abidjan';
      document.getElementById('Precise').value = patient.Precise || '';
      document.getElementById('Type_log').value = patient.Type_logement || 'Studio';
      document.getElementById('NiveauE').value = patient.Niveau_etude || 'Aucun';
      document.getElementById('Profession').value = patient.Profession || 'Aucun';
      togglePreciseField();
      // Ne pas toucher à la sélection de la carte ni au bouton Nouvelle visite ici
      checkCurrentPatientInList();
    }
  </script>
</body>
</html>