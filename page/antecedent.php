<?php
// antecedents_ist.php

// Inclusion des fichiers de sécurité et de connexion à la base de données
require_once("identifier.php");
require_once("connexion.php");

// Variables pour stocker les données du formulaire
$message = '';
$messageType = '';

// Traitement du formulaire si soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // DEBUG: Afficher toutes les données POST
        error_log("POST Data: " . print_r($_POST, true));
        
        // Récupération des données communes
        $numero_urap = trim($_POST['numero_urap'] ?? '');
        $sexe_patient = trim($_POST['sexe_patient'] ?? '');
        
        if (empty($numero_urap) || empty($sexe_patient)) {
            $message = "Veuillez remplir le numéro URAP et sélectionner le sexe du patient.";
            $messageType = 'error';
        } else {
            // Traitement selon le sexe
            if ($sexe_patient === 'masculin') {
                // Traitement pour homme
                $antecedent_homme = trim($_POST['antecedent_homme'] ?? '');
                $antibiotique_homme = trim($_POST['antibiotique_homme'] ?? 'non');
                $preciser_antibiotique_homme = trim($_POST['preciser_antibiotique_homme'] ?? '');
                
                if (empty($antecedent_homme)) {
                    $message = "Veuillez renseigner les antécédents pour un patient masculin.";
                    $messageType = 'error';
                } else {
                    // Vérifier doublon pour homme
                    $check = $pdo->prepare("SELECT COUNT(*) FROM antecedents_ist_hommes WHERE Numero_urap = ?");
                    $check->execute([$numero_urap]);
                    if ($check->fetchColumn() > 0) {
                        $message = "Un enregistrement existe déjà pour ce patient (homme).";
                        $messageType = 'error';
                    } else {
                        // Insertion pour homme
                        $sql = "INSERT INTO antecedents_ist_hommes (
                            Numero_urap, antecedent, antibiotique_actuel, preciser_antibiotique
                        ) VALUES (?, ?, ?, ?)";
                        
                        $stmt = $pdo->prepare($sql);
                        $result = $stmt->execute([
                            $numero_urap,
                            $antecedent_homme,
                            $antibiotique_homme,
                            $preciser_antibiotique_homme
                        ]);
                        
                        if ($result) {
                            header('Location: antecedents_ist.php?success=' . urlencode('Antécédents IST (Homme) enregistrés avec succès !'));
                            exit;
                        } else {
                            $message = 'Erreur lors de l\'enregistrement des antécédents (homme).';
                            $messageType = 'error';
                        }
                    }
                }
            } elseif ($sexe_patient === 'feminin') {
                // Traitement pour femme
                $pertes_vaginales = trim($_POST['pertes_vaginales'] ?? '');
                $douleurs_bas_ventre = trim($_POST['douleurs_bas_ventre'] ?? 'non');
                $plaies_genitales = trim($_POST['plaies_genitales'] ?? '');
                $douleur_rapport = trim($_POST['douleur_rapport'] ?? '');
                
                if (empty($pertes_vaginales) || empty($plaies_genitales) || empty($douleur_rapport)) {
                    $message = "Veuillez remplir tous les champs obligatoires (marqués d'un *)";
                    $messageType = 'error';
                } else {
                    // Vérifier doublon pour femme
                    $check = $pdo->prepare("SELECT COUNT(*) FROM antecedents_ist_genicologiques WHERE Numero_urap = ?");
                    $check->execute([$numero_urap]);
                    if ($check->fetchColumn() > 0) {
                        $message = "Un enregistrement existe déjà pour cette patiente (femme).";
                        $messageType = 'error';
                    } else {
                        // Récupération de tous les champs femme avec valeurs par défaut
                        $gestite = trim($_POST['gestite'] ?? '') ?: null;
                        $parite = trim($_POST['parite'] ?? '') ?: null;
                        $date_regles = trim($_POST['date_regles'] ?? '') ?: null;
                        $ivg = trim($_POST['ivg'] ?? 'non');
                        $toilette_vaginale = trim($_POST['toilette_vaginale'] ?? 'non');
                        $avec_quoi = trim($_POST['avec_quoi'] ?? '');
                        $autre = trim($_POST['autre'] ?? '');
                        $enceinte = trim($_POST['enceinte'] ?? '');
                        $tampons = trim($_POST['tampons'] ?? '');
                        $consultation = trim($_POST['consultation'] ?? '');
                        $medicaments = trim($_POST['medicaments'] ?? 'non');
                        $preciser_medicaments = trim($_POST['preciser_medicaments'] ?? '');
                        $duree_traitement = trim($_POST['duree_traitement'] ?? '');
                        
                        // Nettoyer les champs conditionnels
                        if ($toilette_vaginale === 'non') {
                            $avec_quoi = '';
                            $autre = '';
                        } elseif ($avec_quoi !== 'Autre') {
                            $autre = '';
                        }
                        
                        if ($medicaments === 'non') {
                            $preciser_medicaments = '';
                            $duree_traitement = '';
                        }
                        
                        // Requête SQL simplifiée - utilisez votre vraie structure de table
                        $sql = "INSERT INTO antecedents_ist_genicologiques (
                            Numero_urap, 
                            Avez_vous_eu_des_pertes_vaginales_ces_deux_derniers_mois, 
                            Avez_vous_eu_des_douleurs_au_bas_ventre_ces_deux_derniers_mois, 
                            Avez_vous_eu_des_plaies_vaginales_ces_deux_derniers_mois, 
                            Avez_vous_eu_mal_au_cours_des_derniers_rapport_sexuels, 
                            Antecedant_ist_genicologique_gestité, 
                            Antecedant_ist_genicologique_parité, 
                            Date_des_derniers_regles, 
                            Avez_vous_eu_des_ivgcette_annee__moins_d_un_an_, 
                            Praiquez_vous_une_toillette_vaginale_avec_les_doigt_, 
                            Si_oui_avec_quoi, 
                            autre, 
                            etes_vous_enceinte, 
                            Quel_tampon_utilisez_vous_pandant_les_regles, 
                            qui_avez_vous_consulte, 
                            medicaments_prescrits, 
                            preciser_medicaments, 
                            duree_traitement
                        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                        
                        $params = [
                            $numero_urap, 
                            $pertes_vaginales, 
                            $douleurs_bas_ventre, 
                            $plaies_genitales,
                            $douleur_rapport, 
                            $gestite, 
                            $parite, 
                            $date_regles, 
                            $ivg, 
                            $toilette_vaginale,
                            $avec_quoi, 
                            $autre, 
                            $enceinte, 
                            $tampons, 
                            $consultation, 
                            $medicaments,
                            $preciser_medicaments, 
                            $duree_traitement
                        ];
                        
                        $stmt = $pdo->prepare($sql);
                        $result = $stmt->execute($params);
                        
                        if ($result) {
                            header('Location: antecedents_ist.php?success=' . urlencode('Antécédents IST (Femme) enregistrés avec succès !'));
                            exit;
                        } else {
                            $errorInfo = $stmt->errorInfo();
                            $message = 'Erreur lors de l\'enregistrement : ' . $errorInfo[2];
                            $messageType = 'error';
                            error_log("Erreur SQL: " . print_r($errorInfo, true));
                        }
                    }
                }
            }
        }
        
    } catch (Exception $e) {
        $message = 'Erreur : ' . $e->getMessage();
        $messageType = 'error';
        error_log("Exception: " . $e->getMessage());
    }
}

// Gestion des messages de retour
if (isset($_GET['success'])) {
    $message = urldecode($_GET['success']);
    $messageType = 'success';
} elseif (isset($_GET['error'])) {
    $message = urldecode($_GET['error']);
    $messageType = 'error';
}

// Récupération des informations du patient si ID fourni
$patient = null;
$numero_urap = '';
if (isset($_GET['urap'])) {
    $numero_urap = $_GET['urap'];
    $stmt = $pdo->prepare("SELECT * FROM patient WHERE Numero_urap = ?");
    $stmt->execute([$numero_urap]);
    $patient = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Antécédents IST - UATG</title>
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
            --success: #22c55e;
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
            --homme-color: #2563eb;
            --femme-color: #ec4899;
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
            max-width: 1200px;
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

        .content-area {
            padding: 32px;
            background: white;
        }

        .progress-bar {
            background: var(--gray-200);
            border-radius: 12px;
            height: 8px;
            margin-bottom: 32px;
            overflow: hidden;
        }

        .progress-fill {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            height: 100%;
            width: 33%;
            border-radius: 12px;
            transition: width 0.3s ease;
        }

        .step-indicator {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 32px;
            margin-bottom: 32px;
        }

        .step {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .step.active {
            background: var(--primary);
            color: white;
        }

        .step.inactive {
            background: var(--gray-200);
            color: var(--gray-500);
        }

        .form-section {
            margin-bottom: 32px;
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.4s ease;
        }

        .form-section.show {
            opacity: 1;
            transform: translateY(0);
        }

        .form-section.hide {
            display: none;
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--gray-800);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 16px 20px;
            border-radius: 12px;
            position: relative;
        }

        .section-title.sexe {
            background: linear-gradient(135deg, var(--accent) 0%, #059669 100%);
            color: white;
        }

        .section-title.homme {
            background: linear-gradient(135deg, var(--homme-color) 0%, #3b82f6 100%);
            color: white;
        }

        .section-title.femme {
            background: linear-gradient(135deg, var(--femme-color) 0%, #f472b6 100%);
            color: white;
        }

        .sexe-selection {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 32px;
        }

        .sexe-option {
            position: relative;
        }

        .sexe-radio {
            display: none;
        }

        .sexe-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 24px;
            border: 3px solid var(--gray-200);
            border-radius: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: white;
            text-align: center;
        }

        .sexe-label:hover {
            border-color: var(--primary);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .sexe-radio:checked + .sexe-label {
            border-color: var(--primary);
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: white;
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .sexe-radio:checked + .sexe-label.homme {
            background: linear-gradient(135deg, var(--homme-color) 0%, #3b82f6 100%);
        }

        .sexe-radio:checked + .sexe-label.femme {
            background: linear-gradient(135deg, var(--femme-color) 0%, #f472b6 100%);
        }

        .sexe-icon {
            font-size: 3rem;
            margin-bottom: 12px;
        }

        .sexe-text {
            font-size: 1.2rem;
            font-weight: 600;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 20px;
        }

        .form-field {
            position: relative;
            opacity: 0;
            transform: translateY(20px);
            animation: slideInField 0.4s ease-out forwards;
        }

        .form-field:nth-child(1) { animation-delay: 0.1s; }
        .form-field:nth-child(2) { animation-delay: 0.2s; }
        .form-field:nth-child(3) { animation-delay: 0.3s; }
        .form-field:nth-child(4) { animation-delay: 0.4s; }
        .form-field:nth-child(5) { animation-delay: 0.5s; }
        .form-field:nth-child(6) { animation-delay: 0.6s; }

        @keyframes slideInField {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-field.disabled {
            opacity: 0.5;
            pointer-events: none;
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--gray-700);
            margin-bottom: 6px;
        }

        .form-label.required::after {
            content: ' *';
            color: var(--danger);
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
            color: var(--gray-400);
            cursor: not-allowed;
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

        .form-select:disabled {
            background: var(--gray-50);
            color: var(--gray-400);
            cursor: not-allowed;
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

        .btn-success {
            background: linear-gradient(135deg, var(--success) 0%, #16a34a 100%);
            color: white;
            box-shadow: var(--shadow);
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none !important;
        }

        .alert {
            padding: 16px 20px;
            margin-bottom: 16px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 12px;
            animation: fadeInSlide 0.3s ease-out;
        }

        .alert::before {
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
            font-size: 16px;
        }

        .alert-success {
            background: rgba(34, 197, 94, 0.1);
            color: var(--success);
            border: 1px solid rgba(34, 197, 94, 0.2);
            border-left: 4px solid var(--success);
        }

        .alert-success::before {
            content: "\f00c";
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger);
            border: 1px solid rgba(239, 68, 68, 0.2);
            border-left: 4px solid var(--danger);
        }

        .alert-error::before {
            content: "\f071";
        }

        .alert-info {
            background: rgba(59, 130, 246, 0.1);
            color: var(--primary);
            border: 1px solid rgba(59, 130, 246, 0.2);
            border-left: 4px solid var(--primary);
        }

        .alert-info::before {
            content: "\f05a";
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

        .patient-info {
            background: var(--gray-50);
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 24px;
            border-left: 4px solid var(--primary);
        }

        .patient-info h3 {
            color: var(--primary);
            margin-bottom: 8px;
            font-size: 1.1rem;
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

            .sexe-selection {
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

            .step-indicator {
                gap: 16px;
            }

            .step {
                padding: 6px 12px;
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <button onclick="window.history.back()" class="btn-retour">
                <i class="fas fa-arrow-left"></i> Retour
            </button>
            <h1><i class="fas fa-clipboard-check"></i> Antécédents IST</h1>
            <p>Questionnaire d'évaluation des antécédents et facteurs de risque</p>
        </div>

        <div class="content-area">
            <!-- Barre de progression -->
            <div class="progress-bar">
                <div class="progress-fill" id="progressFill"></div>
            </div>

            <!-- Indicateur d'étapes -->
            <div class="step-indicator">
                <div class="step active" id="step1">
                    <i class="fas fa-user"></i>
                    Patient
                </div>
                <div class="step inactive" id="step2">
                    <i class="fas fa-venus-mars"></i>
                    Sexe
                </div>
                <div class="step inactive" id="step3">
                    <i class="fas fa-clipboard-check"></i>
                    Antécédents
                </div>
            </div>

            <!-- Messages d'alerte -->
            <?php if (!empty($message)): ?>
                <div class="alert alert-<?php echo $messageType; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <!-- Informations du patient si disponible -->
            <?php if ($patient): ?>
                <div class="patient-info">
                    <h3><i class="fas fa-user"></i> Patient sélectionné</h3>
                    <p><strong><?php echo htmlspecialchars($patient['Nom_patient'] . ' ' . $patient['Prenom_patient']); ?></strong> - N° URAP: <?php echo htmlspecialchars($patient['Numero_urap']); ?></p>
                </div>
            <?php endif; ?>

            <form id="antecedentsForm" method="POST">
                
                <!-- Étape 1: Numéro URAP -->
                <div class="form-section show" id="section-patient">
                    <h2 class="section-title">
                        <i class="fas fa-user"></i>
                        Identification du patient
                    </h2>
                    <div class="form-grid">
                        <div class="form-field">
                            <label for="numero_urap" class="form-label required">Numéro URAP</label>
                            <input type="text" id="numero_urap" name="numero_urap" class="form-input" 
                                   value="<?php echo htmlspecialchars($numero_urap); ?>" required 
                                   placeholder="Saisir le numéro URAP du patient" />
                        </div>
                    </div>
                </div>

                <!-- Étape 2: Sélection du sexe -->
                <div class="form-section hide" id="section-sexe">
                    <h2 class="section-title sexe">
                        <i class="fas fa-venus-mars"></i>
                        Sélection du sexe du patient
                    </h2>
                    <div class="sexe-selection">
                        <div class="sexe-option">
                            <input type="radio" id="sexe_masculin" name="sexe_patient" value="masculin" class="sexe-radio">
                            <label for="sexe_masculin" class="sexe-label homme">
                                <div class="sexe-icon">
                                    <i class="fas fa-mars"></i>
                                </div>
                                <div class="sexe-text">Masculin</div>
                            </label>
                        </div>
                        <div class="sexe-option">
                            <input type="radio" id="sexe_feminin" name="sexe_patient" value="feminin" class="sexe-radio">
                            <label for="sexe_feminin" class="sexe-label femme">
                                <div class="sexe-icon">
                                    <i class="fas fa-venus"></i>
                                </div>
                                <div class="sexe-text">Féminin</div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Section Homme -->
                <div class="form-section hide" id="section-homme">
                    <h2 class="section-title homme">
                        <i class="fas fa-mars"></i>
                        Antécédents IST - Section Homme
                    </h2>
                    <div class="form-grid">
                        <div class="form-field">
                            <label class="form-label required">Avez-vous déjà...</label>
                            <select class="form-select" name="antecedent_homme" id="antecedent_homme" required>
                                <option value="">-- Sélectionnez une option --</option>
                                <option value="deja ete atteint d'une MST">été atteint d'une MST</option>
                                <option value="brulure au niveau des organes genitaux">eu des brûlures au niveau des organes génitaux</option>
                                <option value="eu des traumatismes testiculaires">eu des traumatismes testiculaires</option>
                                <option value="eu des interventions chirugicale au niveau des organes genitaux">eu des interventions chirurgicales au niveau des organes génitaux</option>
                                <option value="ete sondé">été sondé</option>
                            </select>
                        </div>

                        <div class="form-field">
                            <label class="form-label">Prenez-vous un antibiotique actuellement ?</label>
                            <select class="form-select" name="antibiotique_homme" id="antibiotique_homme">
                                <option value="">-- Sélectionnez une option --</option>
                                <option value="non">Non</option>
                                <option value="oui">Oui</option>
                            </select>
                        </div>

                        <div class="form-field disabled" id="field-preciser-homme">
                            <label class="form-label">Si oui, préciser lequel (lesquels)</label>
                            <input type="text" class="form-input" name="preciser_antibiotique_homme" 
                                   id="preciser_antibiotique_homme" placeholder="Nom de l'antibiotique" disabled>
                        </div>
                    </div>
                </div>

                <!-- Section Femme -->
                <div class="form-section hide" id="section-femme">
                    <h2 class="section-title femme">
                        <i class="fas fa-venus"></i>
                        Antécédents IST - Section Femme
                    </h2>
                    <div class="form-grid">
                        <div class="form-field">
                            <label class="form-label required">Avez-vous eu des pertes vaginales ces deux derniers mois ?</label>
                            <select class="form-select" name="pertes_vaginales" required>
                                <option value="">-- Sélectionnez une option --</option>
                                <option value="non">Non</option>
                                <option value="oui">Oui</option>
                            </select>
                        </div>

                        <div class="form-field">
                            <label class="form-label">Avez-vous eu des douleurs au bas ventre ces deux derniers mois ?</label>
                            <select class="form-select" name="douleurs_bas_ventre">
                                <option value="">-- Sélectionnez une option --</option>
                                <option value="non">Non</option>
                                <option value="oui">Oui</option>
                            </select>
                        </div>

                        <div class="form-field">
                            <label class="form-label required">Avez-vous eu des plaies génitales ces deux derniers mois ?</label>
                            <select class="form-select" name="plaies_genitales" required>
                                <option value="">-- Sélectionnez une option --</option>
                                <option value="non">Non</option>
                                <option value="oui">Oui</option>
                            </select>
                        </div>

                        <div class="form-field">
                            <label class="form-label required">Avez-vous eu mal au cours des derniers rapports sexuels ?</label>
                            <select class="form-select" name="douleur_rapport" required>
                                <option value="">-- Sélectionnez une option --</option>
                                <option value="non">Non</option>
                                <option value="oui">Oui</option>
                            </select>
                        </div>

                        <div class="form-field">
                            <label class="form-label">Gestité (nombre de grossesses)</label>
                            <input type="number" class="form-input" name="gestite" min="0" placeholder="Exemple: 2">
                        </div>

                        <div class="form-field">
                            <label class="form-label">Parité (nombre d'accouchements)</label>
                            <input type="number" class="form-input" name="parite" min="0" placeholder="Exemple: 1">
                        </div>

                        <div class="form-field">
                            <label class="form-label">Date des dernières règles</label>
                            <input type="date" class="form-input" name="date_regles">
                        </div>

                        <div class="form-field">
                            <label class="form-label">Avez-vous fait une IVG cette année (moins d'un an) ?</label>
                            <select class="form-select" name="ivg">
                                <option value="">-- Sélectionnez une option --</option>
                                <option value="non">Non</option>
                                <option value="oui">Oui</option>
                            </select>
                        </div>

                        <div class="form-field">
                            <label class="form-label">Pratiquez-vous une toilette vaginale (avec les doigts) ?</label>
                            <select class="form-select" name="toilette_vaginale" id="toilette_vaginale">
                                <option value="">-- Sélectionnez une option --</option>
                                <option value="non">Non</option>
                                <option value="oui">Oui</option>
                            </select>
                        </div>

                        <div class="form-field disabled" id="field-avec-quoi">
                            <label class="form-label">Si oui, avec quoi ?</label>
                            <select class="form-select" name="avec_quoi" id="avec_quoi" disabled>
                                <option value="">-- Sélectionnez une option --</option>
                                <option value="Eau simple">Eau simple</option>
                                <option value="Eau et savon">Eau et savon</option>
                                <option value="Produit pharmaceutique">Produit pharmaceutique</option>
                                <option value="Produit africain">Produit africain</option>
                                <option value="Autre">Autre</option>
                            </select>
                        </div>

                        <div class="form-field disabled" id="field-autre">
                            <label class="form-label">Si autre, préciser :</label>
                            <input type="text" class="form-input" name="autre" id="autre" placeholder="Préciser" disabled>
                        </div>

                        <div class="form-field">
                            <label class="form-label">Êtes-vous enceinte ?</label>
                            <select class="form-select" name="enceinte">
                                <option value="">-- Sélectionnez une option --</option>
                                <option value="Femme non enceinte">Femme non enceinte</option>
                                <option value="Femme enceinte">Femme enceinte</option>
                                <option value="Femme menopausée">Femme ménopausée</option>
                            </select>
                        </div>

                        <div class="form-field">
                            <label class="form-label">Quels tampons utilisez-vous pendant les règles ?</label>
                            <select class="form-select" name="tampons">
                                <option value="">-- Sélectionnez une option --</option>
                                <option value="Serviettes hygieniques">Serviettes hygiéniques</option>
                                <option value="Tampons(tampax)">Tampons (tampax)</option>
                                <option value="Serviettes non hygieniques">Serviettes non hygiéniques</option>
                            </select>
                        </div>

                        <div class="form-field">
                            <label class="form-label">Qui avez-vous consulté pour ces signes ?</label>
                            <select class="form-select" name="consultation">
                                <option value="">-- Sélectionnez une option --</option>
                                <option value="Medecin">Médecin</option>
                                <option value="Infirmier">Infirmier</option>
                                <option value="Pharmacien">Pharmacien</option>
                                <option value="Technicien de laboratoire">Technicien de laboratoire</option>
                                <option value="Tradipraticien">Tradipraticien</option>
                                <option value="Vendeur en pharmacien">Vendeur en pharmacie</option>
                            </select>
                        </div>

                        <div class="form-field">
                            <label class="form-label">Vous a-t-il prescrit des médicaments ?</label>
                            <select class="form-select" name="medicaments" id="medicaments">
                                <option value="">-- Sélectionnez une option --</option>
                                <option value="non">Non</option>
                                <option value="oui">Oui</option>
                            </select>
                        </div>

                        <div class="form-field disabled" id="field-preciser-medicaments">
                            <label class="form-label">Si oui, préciser lequel (lesquels)</label>
                            <input type="text" class="form-input" name="preciser_medicaments" 
                                   id="preciser_medicaments" placeholder="Nom des médicaments" disabled>
                        </div>

                        <div class="form-field disabled" id="field-duree-traitement">
                            <label class="form-label">Depuis combien de temps vous vous traitez ?</label>
                            <select class="form-select" name="duree_traitement" id="duree_traitement" disabled>
                                <option value="">-- Sélectionnez une durée --</option>
                                <option value="07jours">07 jours</option>
                                <option value="15jours">15 jours</option>
                                <option value="1mois">1 mois</option>
                                <option value="plus1mois">> 1 mois</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="actions">
                    <button type="button" class="btn btn-secondary" id="btnPrevious" onclick="previousStep()" style="display: none;">
                        <i class="fas fa-arrow-left"></i>
                        Précédent
                    </button>
                    <button type="button" class="btn btn-primary" id="btnNext" onclick="nextStep()">
                        <i class="fas fa-arrow-right"></i>
                        Suivant
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="resetForm()">
                        <i class="fas fa-undo"></i>
                        Réinitialiser
                    </button>
                    <button type="submit" class="btn btn-success" id="btnSubmit" style="display: none;">
                        <i class="fas fa-paper-plane"></i>
                        Soumettre
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let currentStep = 1;
        const totalSteps = 3;

        // Gestion des étapes
        function updateProgress() {
            const progressFill = document.getElementById('progressFill');
            const progress = (currentStep / totalSteps) * 100;
            progressFill.style.width = progress + '%';

            // Mettre à jour les indicateurs d'étapes
            for (let i = 1; i <= totalSteps; i++) {
                const step = document.getElementById('step' + i);
                if (i <= currentStep) {
                    step.classList.remove('inactive');
                    step.classList.add('active');
                } else {
                    step.classList.remove('active');
                    step.classList.add('inactive');
                }
            }
        }

        function showSection(sectionId) {
            // Masquer toutes les sections
            document.querySelectorAll('.form-section').forEach(section => {
                section.classList.remove('show');
                section.classList.add('hide');
            });

            // Afficher la section active
            const activeSection = document.getElementById(sectionId);
            if (activeSection) {
                activeSection.classList.remove('hide');
                activeSection.classList.add('show');
            }
        }

        function updateButtons() {
            const btnPrevious = document.getElementById('btnPrevious');
            const btnNext = document.getElementById('btnNext');
            const btnSubmit = document.getElementById('btnSubmit');

            btnPrevious.style.display = currentStep > 1 ? 'inline-flex' : 'none';
            
            if (currentStep < totalSteps) {
                btnNext.style.display = 'inline-flex';
                btnSubmit.style.display = 'none';
            } else {
                btnNext.style.display = 'none';
                btnSubmit.style.display = 'inline-flex';
            }
        }

        function nextStep() {
            if (currentStep < totalSteps) {
                // Validation avant de passer à l'étape suivante
                if (currentStep === 1) {
                    const numeroUrap = document.getElementById('numero_urap').value.trim();
                    if (!numeroUrap) {
                        alert('Veuillez saisir le numéro URAP avant de continuer.');
                        return;
                    }
                    currentStep = 2;
                    showSection('section-sexe');
                } else if (currentStep === 2) {
                    const sexeSelected = document.querySelector('input[name="sexe_patient"]:checked');
                    if (!sexeSelected) {
                        alert('Veuillez sélectionner le sexe du patient avant de continuer.');
                        return;
                    }
                    currentStep = 3;
                    if (sexeSelected.value === 'masculin') {
                        showSection('section-homme');
                    } else {
                        showSection('section-femme');
                    }
                }
                updateProgress();
                updateButtons();
            }
        }

        function previousStep() {
            if (currentStep > 1) {
                currentStep--;
                if (currentStep === 1) {
                    showSection('section-patient');
                } else if (currentStep === 2) {
                    showSection('section-sexe');
                }
                updateProgress();
                updateButtons();
            }
        }

        // Gestion des champs conditionnels
        function setupConditionalFields() {
            // Section homme
            const antibiotiqueHomme = document.getElementById('antibiotique_homme');
            if (antibiotiqueHomme) {
                antibiotiqueHomme.addEventListener('change', function() {
                    const preciserField = document.getElementById('field-preciser-homme');
                    const preciserInput = document.getElementById('preciser_antibiotique_homme');
                    
                    if (this.value === 'oui') {
                        preciserField.classList.remove('disabled');
                        preciserInput.disabled = false;
                        preciserInput.required = true;
                    } else {
                        preciserField.classList.add('disabled');
                        preciserInput.disabled = true;
                        preciserInput.required = false;
                        preciserInput.value = '';
                    }
                });
            }

            // Section femme - toilette vaginale
            const toiletteVaginale = document.getElementById('toilette_vaginale');
            if (toiletteVaginale) {
                toiletteVaginale.addEventListener('change', function() {
                    const avecQuoiField = document.getElementById('field-avec-quoi');
                    const avecQuoiSelect = document.getElementById('avec_quoi');
                    
                    if (this.value === 'oui') {
                        avecQuoiField.classList.remove('disabled');
                        avecQuoiSelect.disabled = false;
                    } else {
                        avecQuoiField.classList.add('disabled');
                        avecQuoiSelect.disabled = true;
                        avecQuoiSelect.value = '';
                        // Réinitialiser aussi le champ "autre"
                        document.getElementById('field-autre').classList.add('disabled');
                        document.getElementById('autre').disabled = true;
                        document.getElementById('autre').value = '';
                    }
                });
            }

            // Section femme - avec quoi
            const avecQuoi = document.getElementById('avec_quoi');
            if (avecQuoi) {
                avecQuoi.addEventListener('change', function() {
                    const autreField = document.getElementById('field-autre');
                    const autreInput = document.getElementById('autre');
                    
                    if (this.value === 'Autre') {
                        autreField.classList.remove('disabled');
                        autreInput.disabled = false;
                    } else {
                        autreField.classList.add('disabled');
                        autreInput.disabled = true;
                        autreInput.value = '';
                    }
                });
            }

            // Section femme - médicaments
            const medicaments = document.getElementById('medicaments');
            if (medicaments) {
                medicaments.addEventListener('change', function() {
                    const preciserField = document.getElementById('field-preciser-medicaments');
                    const preciserInput = document.getElementById('preciser_medicaments');
                    const dureeField = document.getElementById('field-duree-traitement');
                    const dureeSelect = document.getElementById('duree_traitement');
                    
                    if (this.value === 'oui') {
                        preciserField.classList.remove('disabled');
                        preciserInput.disabled = false;
                        dureeField.classList.remove('disabled');
                        dureeSelect.disabled = false;
                    } else {
                        preciserField.classList.add('disabled');
                        preciserInput.disabled = true;
                        preciserInput.value = '';
                        dureeField.classList.add('disabled');
                        dureeSelect.disabled = true;
                        dureeSelect.value = '';
                    }
                });
            }
        }

        // Fonction pour réinitialiser le formulaire
        function resetForm() {
            if (confirm('Êtes-vous sûr de vouloir réinitialiser le formulaire ?')) {
                document.getElementById('antecedentsForm').reset();
                currentStep = 1;
                showSection('section-patient');
                updateProgress();
                updateButtons();
                
                // Réinitialiser les champs conditionnels
                document.querySelectorAll('.form-field.disabled').forEach(field => {
                    field.classList.add('disabled');
                });
                document.querySelectorAll('input:disabled, select:disabled').forEach(element => {
                    element.disabled = true;
                });
            }
        }

        // Validation du formulaire avant soumission
        document.getElementById('antecedentsForm').addEventListener('submit', function(e) {
            console.log('Soumission du formulaire...');
            
            const sexeSelected = document.querySelector('input[name="sexe_patient"]:checked');
            
            if (!sexeSelected) {
                e.preventDefault();
                alert('Veuillez sélectionner le sexe du patient.');
                return;
            }
            
            console.log('Sexe sélectionné:', sexeSelected.value);
            
            // Validation spécifique selon le sexe
            if (sexeSelected.value === 'masculin') {
                const antecedentHomme = document.querySelector('select[name="antecedent_homme"]').value;
                if (!antecedentHomme) {
                    e.preventDefault();
                    alert('Veuillez sélectionner un antécédent pour la section homme.');
                    return;
                }
            } else if (sexeSelected.value === 'feminin') {
                const pertesVaginales = document.querySelector('select[name="pertes_vaginales"]').value;
                const plaiesGenitales = document.querySelector('select[name="plaies_genitales"]').value;
                const douleurRapport = document.querySelector('select[name="douleur_rapport"]').value;
                
                console.log('Validation femme:');
                console.log('Pertes vaginales:', pertesVaginales);
                console.log('Plaies génitales:', plaiesGenitales);
                console.log('Douleur rapport:', douleurRapport);
                
                if (!pertesVaginales || !plaiesGenitales || !douleurRapport) {
                    e.preventDefault();
                    alert('Veuillez remplir tous les champs obligatoires (marqués d\'un *)');
                    return;
                }
            }

            // Animation de soumission
            const submitBtn = document.getElementById('btnSubmit');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Envoi en cours...';
            submitBtn.disabled = true;
            
            console.log('Formulaire prêt à être soumis');
        });

        // Initialisation
        document.addEventListener('DOMContentLoaded', function() {
            updateProgress();
            updateButtons();
            showSection('section-patient');
            setupConditionalFields();
            
            // Effacer le message de succès après 5 secondes
            <?php if ($messageType === 'success'): ?>
                setTimeout(() => {
                    const alertDiv = document.querySelector('.alert-success');
                    if (alertDiv) {
                        alertDiv.style.opacity = '0';
                        setTimeout(() => alertDiv.remove(), 300);
                    }
                    resetForm();
                }, 5000);
            <?php endif; ?>
        });
    </script>
</body>
</html>