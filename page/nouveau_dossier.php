<?php
// nouveau_dossier.php

// Données simulées des patients (à remplacer par une requête à votre base de données)
$patients = [
    [
        'N_Urap' => '001',
        'Nom' => 'KOUAME',
        'Prenom' => 'Jean',
        'datenaiss' => '1985-05-15',
        'Age' => '38',
        'SexeP' => 'Masculin',
        'contact' => '0101020304',
        'Adresse' => 'Cocody Riviera',
        'SituaM' => 'Marié',
        'reside' => 'Abidjan',
        'Precise' => '',
        'Type_log' => 'Villa',
        'NiveauE' => 'Universitaire',
        'Profession' => 'Cadre superieur'
    ],
    [
        'N_Urap' => '002',
        'Nom' => 'TRAORE',
        'Prenom' => 'Aminata',
        'datenaiss' => '1992-08-22',
        'Age' => '31',
        'SexeP' => 'Féminin',
        'contact' => '0707080910',
        'Adresse' => 'Yopougon Selmer',
        'SituaM' => 'Célibataire',
        'reside' => 'Abidjan',
        'Precise' => '',
        'Type_log' => 'Studio',
        'NiveauE' => 'Secondaire',
        'Profession' => 'Secteur informel'
    ],
    [
        'N_Urap' => '003',
        'Nom' => 'DIABATE',
        'Prenom' => 'Mohamed',
        'datenaiss' => '1978-12-03',
        'Age' => '45',
        'SexeP' => 'Masculin',
        'contact' => '0505060708',
        'Adresse' => 'Bouaké Centre',
        'SituaM' => 'Marié',
        'reside' => 'Hors Abidjan',
        'Precise' => 'Bouaké',
        'Type_log' => 'Cour commune',
        'NiveauE' => 'Primaire',
        'Profession' => 'Secteur informel'
    ],
    [
        'N_Urap' => '004',
        'Nom' => 'KONE',
        'Prenom' => 'Mariam',
        'datenaiss' => '1995-03-10',
        'Age' => '28',
        'SexeP' => 'Féminin',
        'contact' => '0909080706',
        'Adresse' => 'Adjamé Liberté',
        'SituaM' => 'Célibataire',
        'reside' => 'Abidjan',
        'Precise' => '',
        'Type_log' => 'Cour commune',
        'NiveauE' => 'Universitaire',
        'Profession' => 'Etudiant'
    ],
    [
        'N_Urap' => '005',
        'Nom' => 'OUATTARA',
        'Prenom' => 'Ibrahim',
        'datenaiss' => '1960-07-18',
        'Age' => '63',
        'SexeP' => 'Masculin',
        'contact' => '0202030405',
        'Adresse' => 'Plateau Centre',
        'SituaM' => 'Marié',
        'reside' => 'Abidjan',
        'Precise' => '',
        'Type_log' => 'Villa',
        'NiveauE' => 'Universitaire',
        'Profession' => 'Retraité'
    ]
];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Nouveau Dossier Patient</title>
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

        .main-content {
            display: grid;
            grid-template-columns: 380px 1fr;
            gap: 0;
            min-height: 600px;
        }

        .sidebar {
            background: var(--gray-50);
            border-right: 1px solid var(--gray-200);
            padding: 24px;
            overflow-y: auto;
        }

        .search-container {
            position: relative;
            margin-bottom: 24px;
        }

        .search-input {
            width: 100%;
            padding: 12px 16px 12px 44px;
            border: 2px solid var(--gray-200);
            border-radius: 12px;
            font-size: 14px;
            transition: all 0.2s ease;
            background: white;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(0, 71, 171, 0.1);
        }

        .search-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-400);
        }

        .patients-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
        }

        .patients-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--gray-700);
        }

        .patients-count {
            background: var(--primary);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .patient-card {
            background: white;
            border: 2px solid var(--gray-200);
            border-radius: 16px;
            padding: 16px;
            margin-bottom: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .patient-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: var(--gray-300);
            transition: all 0.3s ease;
        }

        .patient-card:hover {
            border-color: var(--primary);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .patient-card:hover::before {
            background: var(--primary);
            width: 6px;
        }

        .patient-card.selected {
            border-color: var(--primary);
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: white;
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .patient-card.selected::before {
            background: white;
            width: 6px;
        }

        .patient-name {
            font-weight: 600;
            font-size: 1rem;
            margin-bottom: 4px;
        }

        .patient-details {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.875rem;
            opacity: 0.8;
        }

        .patient-age {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .content-area {
            padding: 32px;
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

        .notes-section {
            margin-bottom: 32px;
        }

        .notes-textarea {
            width: 100%;
            min-height: 200px;
            padding: 16px;
            border: 2px solid var(--gray-200);
            border-radius: 16px;
            font-size: 14px;
            font-family: inherit;
            resize: vertical;
            transition: all 0.2s ease;
            background: white;
        }

        .notes-textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(0, 71, 171, 0.1);
        }

        .actions {
            display: flex;
            gap: 16px;
            justify-content: center;
            flex-wrap: wrap;
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

        .btn-accent {
            background: linear-gradient(135deg, var(--accent) 0%, #059669 100%);
            color: white;
            box-shadow: var(--shadow);
        }

        .btn-accent:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .btn-accent:disabled {
            background: var(--gray-300) !important;
            color: var(--gray-500) !important;
            cursor: not-allowed !important;
            box-shadow: none !important;
            border: none !important;
        }

        .floating-label {
            position: relative;
        }

        .floating-label .form-input:focus + .form-label,
        .floating-label .form-input:not(:placeholder-shown) + .form-label {
            transform: translateY(-24px) scale(0.875);
            color: var(--primary);
        }

        .status-indicator {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--accent);
            display: inline-block;
            margin-right: 8px;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        .patient-card.hidden {
            display: none;
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

            .form-grid {
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 16px;
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
        }

        .btn-patient-detail {
            display: none;
            position: absolute;
            top: 12px;
            right: 12px;
            background: #e0edff;
            color: #2563eb;
            border: none;
            border-radius: 50%;
            width: 36px;
            height: 36px;
            font-size: 1.1em;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
            transition: background 0.2s, color 0.2s;
            z-index: 2;
        }

        .patient-card:hover .btn-patient-detail {
            display: block;
        }

        .btn-patient-detail:hover {
            background: #2563eb;
            color: #fff;
        }

        .btn-patient-edit {
            display: none;
            position: absolute;
            top: 12px;
            right: 54px;
            background: #f3f4f6;
            color: #0047ab;
            border: none;
            border-radius: 50%;
            width: 36px;
            height: 36px;
            font-size: 1.1em;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
            transition: background 0.2s, color 0.2s;
            z-index: 2;
            text-align: center;
            line-height: 36px;
            text-decoration: none;
        }

        .patient-card:hover .btn-patient-edit {
            display: inline-block;
        }

        .btn-patient-edit:hover {
            background: #0047ab;
            color: #fff;
        }
    </style>
</head>
<body>
    <div id="formErrorMessage" style="display:none; justify-content: center; align-items: center; margin: 32px auto 24px auto; max-width: 600px; padding: 16px 24px; border-radius: 12px; background: #ffeaea; color: #b91c1c; font-weight: 600; border: 1.5px solid #fca5a5; box-shadow: 0 2px 8px rgba(239,68,68,0.07); font-size: 1.05rem; text-align: center; gap: 12px; transition: opacity 0.3s; opacity: 0;"><i class="fas fa-exclamation-triangle" style="margin-right: 10px; color: #ef4444;"></i><span id="formErrorText"></span></div>
    <div class="container">
        <div class="header">
            <button onclick="window.history.back()" style="position:absolute;left:24px;top:32px;background:var(--gray-100);border:none;border-radius:50px;padding:10px 18px;font-size:1.1em;box-shadow:0 2px 8px rgba(0,0,0,0.07);color:var(--primary);cursor:pointer;display:flex;align-items:center;gap:8px;transition:background 0.2s;z-index:2;">
                <i class="fas fa-arrow-left"></i> Retour
            </button>
            <h1><i class="fas fa-user-plus"></i> Nouveau Dossier Patient</h1>
            <p>Créer ou modifier un dossier patient existant</p>
        </div>
        <div id="saveMessage"></div>

        <div class="main-content">
            <div class="sidebar">
                <div class="search-container">
                    <input type="text" class="search-input" placeholder="Rechercher un patient..." id="searchInput">
                    <i class="fas fa-search search-icon"></i>
                </div>

                <div class="patients-header">
                    <h3 class="patients-title">Patients</h3>
                    <span class="patients-count" id="patientsCount"><?php echo count($patients); ?></span>
                </div>

                <div id="patientsList">
                    <?php foreach($patients as $index => $patient): ?>
                        <div class="patient-card" onclick="selectPatient(<?php echo $index; ?>)" data-name="<?php echo strtolower($patient['Nom'] . ' ' . $patient['Prenom']); ?>">
                            <div class="patient-name">
                                <?php echo htmlspecialchars($patient['Nom'] . ' ' . $patient['Prenom']); ?>
                            </div>
                            <div class="patient-details">
                                <span>N° <?php echo htmlspecialchars($patient['N_Urap']); ?></span>
                                <span class="patient-age">
                                    <i class="fas fa-calendar-alt"></i>
                                    <?php echo htmlspecialchars($patient['Age']); ?> ans
                                </span>
                            </div>
                            <button type="button" class="btn-patient-detail" title="Voir le détail" onclick="event.stopPropagation();showPatientDetail(<?php echo $index; ?>)"><i class="fas fa-eye"></i></button>
                            <a href="modifpatient.php?idU=<?php echo urlencode($patient['N_Urap']); ?>" class="btn-patient-edit" title="Modifier" onclick="event.stopPropagation();"><i class="fas fa-pen"></i></a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="content-area">
                <form id="patientForm" method="POST" action="traitement_dossier.php">
                    <div class="form-section">
                        <h2 class="section-title">
                            <i class="fas fa-user"></i>
                            Informations personnelles
                        </h2>
                        <div class="form-grid">
                            <div class="form-field">
                                <label class="form-label">N° Urap</label>
                                <input type="text" class="form-input" id="N_Urap" name="N_Urap" placeholder="Numéro Urap" required>
                            </div>
                            <div class="form-field">
                                <label class="form-label">Nom</label>
                                <input type="text" class="form-input" id="Nom" name="Nom" placeholder="Nom de famille" required>
                            </div>
                            <div class="form-field">
                                <label class="form-label">Prénom</label>
                                <input type="text" class="form-input" id="Prenom" name="Prenom" placeholder="Prénom" required>
                            </div>
                            <div class="form-field">
                                <label class="form-label">Date de naissance</label>
                                <input type="date" class="form-input" id="datenaiss" name="datenaiss" required>
                            </div>
                            <div class="form-field">
                                <label class="form-label">Âge</label>
                                <input type="text" class="form-input" id="Age" name="Age" placeholder="Calculé automatiquement" readonly>
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
                        <h2 class="section-title">
                            <i class="fas fa-address-card"></i>
                            Contact et adresse
                        </h2>
                        <div class="form-grid">
                            <div class="form-field">
                                <label class="form-label">Contact</label>
                                <input type="tel" class="form-input" id="contact" name="contact" placeholder="Numéro de téléphone" required>
                            </div>
                            <div class="form-field">
                                <label class="form-label">Adresse</label>
                                <input type="text" class="form-input" id="Adresse" name="Adresse" placeholder="Adresse complète" required>
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
                                <input type="text" class="form-input" id="Precise" name="Precise" placeholder="Commune, quartier...">
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h2 class="section-title">
                            <i class="fas fa-info-circle"></i>
                            Informations sociales
                        </h2>
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

                    <div class="form-section">
                        <h2 class="section-title">
                            <i class="fas fa-clock"></i>
                            Consultation
                        </h2>
                        <div class="form-grid">
                            <div class="form-field">
                                <label class="form-label">Date</label>
                                <input type="date" class="form-input" id="dateConsultation" name="dateConsultation">
                            </div>
                            <div class="form-field">
                                <label class="form-label">Heure</label>
                                <input type="time" class="form-input" id="heureConsultation" name="heureConsultation">
                            </div>
                        </div>
                    </div>
                </form>

                <div class="notes-section">
                    <h2 class="section-title">
                        <i class="fas fa-sticky-note"></i>
                        Notes et observations
                    </h2>
                    <textarea class="notes-textarea" name="notes" placeholder="Saisissez vos notes et observations ici..."></textarea>
                </div>

                <div class="actions">
                    <button type="button" class="btn btn-secondary" onclick="clearForm()">
                        <i class="fas fa-plus"></i>
                        Nouveau
                    </button>
                    <button type="button" class="btn btn-secondary">
                        <i class="fas fa-history"></i>
                        Suivie
                    </button>
                    <a href="visite.php" class="btn btn-accent">
                        <i class="fas fa-stethoscope"></i>
                        Nouvelle visite
                    </a>
                    <button type="button" class="btn btn-primary" onclick="window.location.href='visite.php'">
                        <i class="fas fa-save"></i>
                        Enregistrer
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modale détail patient -->
    <div id="detailModal" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(31,41,55,0.35); z-index:10000; align-items:center; justify-content:center;">
        <div style="background:white; border-radius:18px; max-width:480px; width:90vw; margin:auto; box-shadow:0 8px 32px rgba(0,0,0,0.18); padding:32px 24px 24px 24px; position:relative; animation:fadeIn .3s;">
            <button id="closeDetailModal" style="position:absolute; top:16px; right:16px; background:none; border:none; font-size:1.5em; color:#b91c1c; cursor:pointer;"><i class="fas fa-times"></i></button>
            <h2 style="font-size:1.3em; color:#0047ab; margin-bottom:18px; text-align:center;"><i class="fas fa-user"></i> Détail du patient</h2>
            <div id="detailContent"></div>
        </div>
    </div>
    <style>@keyframes fadeIn{from{opacity:0;transform:scale(0.98);}to{opacity:1;transform:scale(1);}}</style>

    <script>
        // Données des patients depuis PHP
        const patients = <?php echo json_encode($patients); ?>;

        // Recherche de patients
        const searchInput = document.getElementById('searchInput');
        const patientCards = document.querySelectorAll('.patient-card');
        const patientsCount = document.getElementById('patientsCount');

        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            let visibleCount = 0;

            patientCards.forEach(card => {
                const patientName = card.dataset.name;
                if (patientName.includes(searchTerm)) {
                    card.classList.remove('hidden');
                    visibleCount++;
                } else {
                    card.classList.add('hidden');
                }
            });

            patientsCount.textContent = visibleCount;
        });

        // Ajout gestion bouton Modifier
        const btnModifier = document.getElementById('btnModifier');
        let selectedPatientIndex = null;

        function selectPatient(index) {
            // Animation de sélection
            patientCards.forEach(card => {
                card.classList.remove('selected');
            });
            patientCards[index].classList.add('selected');

            // Remplir le formulaire avec animation
            const patient = patients[index];
            
            Object.keys(patient).forEach(key => {
                const field = document.getElementById(key);
                if (field) {
                    field.style.transform = 'scale(1.02)';
                    setTimeout(() => {
                        field.value = patient[key];
                        field.style.transform = 'scale(1)';
                    }, 100);
                }
            });

            // Mettre à jour le champ Precise
            setTimeout(togglePreciseField, 200);

            // Scroll vers le formulaire sur mobile
            if (window.innerWidth <= 1024) {
                document.querySelector('.content-area').scrollIntoView({ 
                    behavior: 'smooth' 
                });
            }
        }

        function clearForm() {
            document.getElementById('patientForm').reset();
            document.querySelector('.notes-textarea').value = '';
            
            // Enlever la sélection des patients
            patientCards.forEach(card => {
                card.classList.remove('selected');
            });
            
            // Remettre la date et l'heure actuelles
            const now = new Date();
            document.getElementById('dateConsultation').value = now.toISOString().split('T')[0];
            document.getElementById('heureConsultation').value = now.toTimeString().slice(0, 5);

            // Animation de reset
            const inputs = document.querySelectorAll('.form-input, .form-select');
            inputs.forEach(input => {
                input.style.borderColor = 'var(--accent)';
                setTimeout(() => {
                    input.style.borderColor = '';
                }, 300);
            });

            selectedPatientIndex = null;
            btnModifier.disabled = true;
            updateVisiteButtonState();
            updateDetailButtonState();
        }

        // Fonction pour gérer le champ "Precise"
        function togglePreciseField() {
            const selectReside = document.getElementById("reside");
            const inputPrecise = document.getElementById("Precise");

            if (selectReside.value === "Abidjan") {
                inputPrecise.disabled = true;
                inputPrecise.value = "";
                inputPrecise.style.backgroundColor = "var(--gray-50)";
            } else {
                inputPrecise.disabled = false;
                inputPrecise.style.backgroundColor = "";
            }
        }

        // Fonction pour calculer l'âge
        function calculateAge() {
            const dateInput = document.getElementById("datenaiss");
            const ageInput = document.getElementById("Age");
            
            if (dateInput.value) {
                const birthDate = new Date(dateInput.value);
                const today = new Date();
                let age = today.getFullYear() - birthDate.getFullYear();
                const monthDiff = today.getMonth() - birthDate.getMonth();
                
                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                    age--;
                }
                
                ageInput.value = isNaN(age) || age < 0 ? "" : age;

                // Animation pour l'âge calculé
                if (age >= 0) {
                    ageInput.style.color = 'var(--accent)';
                    ageInput.style.fontWeight = '600';
                    setTimeout(() => {
                        ageInput.style.color = '';
                        ageInput.style.fontWeight = '';
                    }, 1000);
                }
            }
        }

        // Initialisation
        document.addEventListener("DOMContentLoaded", function() {
            // Mettre la date et l'heure actuelles par défaut
            const now = new Date();
            document.getElementById('dateConsultation').value = now.toISOString().split('T')[0];
            document.getElementById('heureConsultation').value = now.toTimeString().slice(0, 5);

            // Événements
            document.getElementById("reside").addEventListener("change", togglePreciseField);
            document.getElementById("datenaiss").addEventListener("input", calculateAge);
            
            // Initialiser l'état du champ Precise
            togglePreciseField();

            // Animation d'entrée pour les cartes patients
            patientCards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateX(-20px)';
                setTimeout(() => {
                    card.style.transition = 'all 0.3s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateX(0)';
                }, index * 100);
            });
        });

        // Amélioration UX: Auto-save en local storage
        const formInputs = document.querySelectorAll('.form-input, .form-select, .notes-textarea');
        formInputs.forEach(input => {
            input.addEventListener('change', () => {
                localStorage.setItem('dossier_temp_' + input.name, input.value);
            });
        });

        // Restaurer les données au chargement
        window.addEventListener('load', () => {
            formInputs.forEach(input => {
                const saved = localStorage.getItem('dossier_temp_' + input.name);
                if (saved && !input.value) {
                    input.value = saved;
                }
            });
        });

        // Nettoyer le localStorage après soumission
        document.getElementById('patientForm').addEventListener('submit', () => {
            formInputs.forEach(input => {
                localStorage.removeItem('dossier_temp_' + input.name);
            });
        });

        // Remplacer l'action du bouton Enregistrer par un enregistrement AJAX
        const btnEnregistrer = document.getElementById('btnEnregistrer');
        const patientForm = document.getElementById('patientForm');
        const patientsList = document.getElementById('patientsList');
        const notesTextarea = document.querySelector('.notes-textarea');

        function showMessage(message, type = 'success') {
            let msgDiv = document.getElementById('saveMessage');
            if (!msgDiv) {
                msgDiv = document.createElement('div');
                msgDiv.id = 'saveMessage';
                document.body.appendChild(msgDiv);
            }
            // Style général
            msgDiv.style.position = 'fixed';
            msgDiv.style.top = '60px';
            msgDiv.style.left = '50%';
            msgDiv.style.transform = 'translateX(-50%)';
            msgDiv.style.zIndex = '9999';
            msgDiv.style.minWidth = '340px';
            msgDiv.style.maxWidth = '700px';
            msgDiv.style.display = 'flex';
            msgDiv.style.alignItems = 'center';
            msgDiv.style.justifyContent = 'center';
            msgDiv.style.gap = '16px';
            msgDiv.style.padding = '20px 40px';
            msgDiv.style.borderRadius = '16px';
            msgDiv.style.fontWeight = 'bold';
            msgDiv.style.fontSize = '1.18rem';
            msgDiv.style.boxShadow = '0 6px 24px rgba(0,0,0,0.10)';
            msgDiv.style.opacity = '0';
            msgDiv.style.transition = 'opacity 0.3s';
            // Ajout d'une icône et d'un titre selon le type
            let icon = '';
            let title = '';
            if(type === 'success') {
                icon = '<i class="fas fa-info-circle" style="color:#2563eb;font-size:1.5em;"></i>';
                title = '<span style="color:#2563eb;font-size:1.1em;margin-right:8px;">Succès !</span>';
                msgDiv.style.background = '#e0edff';
                msgDiv.style.color = '#1e3a8a';
                msgDiv.style.border = '2px solid #60a5fa';
            } else {
                icon = '<i class="fas fa-exclamation-circle" style="color:#ea580c;font-size:1.5em;"></i>';
                title = '<span style="color:#ea580c;font-size:1.1em;margin-right:8px;">Attention !</span>';
                msgDiv.style.background = '#fff7ed';
                msgDiv.style.color = '#9a3412';
                msgDiv.style.border = '2px solid #fdba74';
            }
            msgDiv.innerHTML = icon + title + '<span>' + message + '</span>';
            msgDiv.style.display = 'flex';
            setTimeout(() => { msgDiv.style.opacity = '1'; }, 10);
            // Scroll automatique vers la notification
            window.scrollTo({ top: 0, behavior: 'smooth' });
            setTimeout(() => {
                msgDiv.style.opacity = '0';
                setTimeout(() => { msgDiv.style.display = 'none'; }, 300);
            }, 5000);
        }

        btnEnregistrer.addEventListener('click', function(e) {
            e.preventDefault();
            // Validation JS des champs obligatoires
            const N_Urap = document.getElementById('N_Urap').value.trim();
            const Nom = document.getElementById('Nom').value.trim();
            const Prenom = document.getElementById('Prenom').value.trim();
            if (!N_Urap || !Nom || !Prenom) {
                showMessage('Champs obligatoires manquants', 'error');
                return;
            }
            const formData = new FormData(patientForm);
            formData.append('notes', notesTextarea.value);
            fetch('traitement_dossier.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.patient) {
                    // Ajouter le patient à la liste de gauche
                    patients.push(data.patient);
                    const index = patients.length - 1;
                    const card = document.createElement('div');
                    card.className = 'patient-card';
                    card.setAttribute('onclick', `selectPatient(${index})`);
                    card.setAttribute('data-name', (data.patient.Nom + ' ' + data.patient.Prenom).toLowerCase());
                    card.innerHTML = `
                        <div class="patient-name">${data.patient.Nom} ${data.patient.Prenom}</div>
                        <div class="patient-details">
                            <span>N° ${data.patient.N_Urap}</span>
                            <span class="patient-age"><i class="fas fa-calendar-alt"></i> ${data.patient.Age} ans</span>
                        </div>
                    `;
                    patientsList.appendChild(card);
                    document.getElementById('patientsCount').textContent = patients.length;
                    showMessage('Patient enregistré avec succès !', 'success');
                    clearForm();
                } else {
                    showMessage(data.error || 'Erreur lors de l\'enregistrement', 'error');
                }
            })
            .catch(() => {
                showMessage('Erreur lors de l\'enregistrement', 'error');
            });
        });

        const btnNouvelleVisite = document.getElementById('btnNouvelleVisite');

        function updateVisiteButtonState() {
            if (selectedPatientIndex !== null) {
                btnNouvelleVisite.style.pointerEvents = 'auto';
                btnNouvelleVisite.style.opacity = '1';
                // Met à jour le lien avec l'id du patient sélectionné si besoin
                const numeroUrap = patients[selectedPatientIndex]['N_Urap'];
                btnNouvelleVisite.href = 'visite.php?idU=' + encodeURIComponent(numeroUrap);
            } else {
                btnNouvelleVisite.style.pointerEvents = 'none';
                btnNouvelleVisite.style.opacity = '0.6';
                btnNouvelleVisite.href = '#';
            }
        }

        // Message si on clique sans sélection (sécurité supplémentaire)
        btnNouvelleVisite.addEventListener('click', function(e) {
            if (selectedPatientIndex === null) {
                e.preventDefault();
                showMessage('Veuillez d\'abord sélectionner un patient enregistré.', 'error');
            }
        });

        // Initialiser l'état du bouton à l'ouverture
        updateVisiteButtonState();

        function showPatientDetail(index) {
            const p = patients[index];
            let html = '<table style="width:100%;border-collapse:collapse;font-size:1em;">';
            for (const [key, value] of Object.entries(p)) {
                html += `<tr><td style='font-weight:600;padding:6px 8px;color:#374151;text-align:left;'>${key}</td><td style='padding:6px 8px;color:#1e293b;text-align:left;'>${value ? value : '-'}</td></tr>`;
            }
            html += '</table>';
            detailContent.innerHTML = html;
            detailModal.style.display = 'flex';
        }

        // --- Correction fermeture modale détail patient ---
        const detailModal = document.getElementById('detailModal');
        const closeDetailModal = document.getElementById('closeDetailModal');
        if (closeDetailModal && detailModal) {
            closeDetailModal.addEventListener('click', function() {
                detailModal.style.display = 'none';
            });
            // Fermer la modale si on clique en dehors
            detailModal.addEventListener('click', function(e) {
                if (e.target === detailModal) detailModal.style.display = 'none';
            });
            // Fermer la modale avec la touche Echap
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && detailModal.style.display === 'flex') {
                    detailModal.style.display = 'none';
                }
            });
        }
    </script>
</body>
</html>