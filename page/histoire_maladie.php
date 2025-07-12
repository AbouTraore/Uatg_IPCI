<?php
// histoire_maladie.php

// Inclusion des fichiers de sécurité et de connexion à la base de données
require_once("identifier.php");
require_once("connexion.php");

// Créer la table histoire_maladie si elle n'existe pas
try {
    $createTableSQL = "CREATE TABLE IF NOT EXISTS `histoire_maladie` (
        `id` int NOT NULL AUTO_INCREMENT,
        `numero_urap` varchar(15) NOT NULL,
        `sexe_patient` varchar(10) NOT NULL,
        `motif_homme` varchar(100) DEFAULT NULL,
        `motif_femme` varchar(100) DEFAULT NULL,
        `signes_fonctionnels` varchar(100) DEFAULT NULL,
        `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        UNIQUE KEY `numero_urap` (`numero_urap`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";
    
    $pdo->exec($createTableSQL);
} catch (Exception $e) {
    error_log("Erreur création table: " . $e->getMessage());
}

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
        
        // Récupération des données du formulaire
        $motif_homme = trim($_POST['motif_homme'] ?? '');
        $motif_femme = trim($_POST['motif_femme'] ?? '');
        $signes_fonctionnels = trim($_POST['signes_fonctionnels'] ?? '');
        
        if (empty($numero_urap) || empty($sexe_patient)) {
            $message = "Veuillez remplir le numéro URAP et sélectionner le sexe du patient.";
            $messageType = 'error';
        } else {
            // Validation selon le sexe
            if ($sexe_patient === 'masculin' && empty($motif_homme)) {
                $message = "Veuillez sélectionner un motif de consultation pour un patient masculin.";
                $messageType = 'error';
            } elseif ($sexe_patient === 'feminin' && empty($motif_femme)) {
                $message = "Veuillez sélectionner un motif de consultation pour une patiente féminine.";
                $messageType = 'error';
            } else {
                // Nettoyer les champs selon le sexe sélectionné
                if ($sexe_patient === 'masculin') {
                    $motif_femme = '';
                } elseif ($sexe_patient === 'feminin') {
                    $motif_homme = '';
                }
                
                // Vérifier doublon
                $check = $pdo->prepare("SELECT COUNT(*) FROM histoire_maladie WHERE numero_urap = ?");
                $check->execute([$numero_urap]);
                if ($check->fetchColumn() > 0) {
                    // Récupérer les informations de l'enregistrement existant
                    $stmt_existing = $pdo->prepare("SELECT hm.*, p.Nom_patient, p.Prenom_patient, p.Sexe_patient 
                                                   FROM histoire_maladie hm 
                                                   LEFT JOIN patient p ON hm.numero_urap = p.Numero_urap 
                                                   WHERE hm.numero_urap = ?");
                    $stmt_existing->execute([$numero_urap]);
                    $existing_record = $stmt_existing->fetch(PDO::FETCH_ASSOC);
                    
                    $patient_name = $existing_record['Nom_patient'] ? 
                        $existing_record['Nom_patient'] . ' ' . $existing_record['Prenom_patient'] : 
                        'Patient non trouvé';
                    
                    $message = "⚠️ ALERTE : Le numéro URAP <strong>" . htmlspecialchars($numero_urap) . "</strong> a déjà été utilisé pour enregistrer une histoire de maladie.<br><br>
                               <strong>Informations de l'enregistrement existant :</strong><br>
                               • Patient : " . htmlspecialchars($patient_name) . "<br>
                               • Sexe : " . ucfirst($existing_record['sexe_patient']) . "<br>
                               • Date d'enregistrement : " . date('d/m/Y à H:i', strtotime($existing_record['date_creation'])) . "<br><br>
                               Veuillez utiliser un autre numéro URAP ou consulter la liste des enregistrements existants.";
                    $messageType = 'error';
                } else {
                    // Préparation de la requête d'insertion
                    $sql = "INSERT INTO histoire_maladie (
                        numero_urap, sexe_patient, motif_homme, motif_femme, signes_fonctionnels, date_creation
                    ) VALUES (
                        ?, ?, ?, ?, ?, NOW()
                    )";
                    
                    $stmt = $pdo->prepare($sql);
                    $result = $stmt->execute([
                        $numero_urap,
                        $sexe_patient,
                        $motif_homme,
                        $motif_femme,
                        $signes_fonctionnels
                    ]);
                    
                    if ($result) {
                        header('Location: histoire_maladie.php?success=' . urlencode('Histoire de la maladie enregistrée avec succès !'));
                        exit;
                    } else {
                        $message = 'Erreur lors de l\'enregistrement de l\'histoire de la maladie.';
                        $messageType = 'error';
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
if (isset($_GET['idU'])) {
    $numero_urap = $_GET['idU'];
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
    <title>Histoire de la Maladie - UATG</title>
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
            max-width: 1000px;
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

        .urap-header {
            background: linear-gradient(135deg, var(--accent) 0%, #059669 100%);
            color: white;
            padding: 20px 24px;
            margin-bottom: 32px;
            border-radius: 12px;
            text-align: center;
            box-shadow: var(--shadow-md);
        }

        .urap-header h2 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .urap-input-container {
            max-width: 400px;
            margin: 16px auto 0;
        }

        .urap-input {
            width: 100%;
            padding: 16px 20px;
            border: 2px solid rgba(255,255,255,0.3);
            border-radius: 12px;
            font-size: 16px;
            background: rgba(255,255,255,0.1);
            color: white;
            text-align: center;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .urap-input::placeholder {
            color: rgba(255,255,255,0.7);
        }

        .urap-input:focus {
            outline: none;
            border-color: rgba(255,255,255,0.8);
            background: rgba(255,255,255,0.2);
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
            width: 50%;
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

        .form-section.disabled-section {
            opacity: 0.3;
            pointer-events: none;
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

        .section-title.general {
            background: linear-gradient(135deg, var(--accent) 0%, #059669 100%);
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
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .form-field {
            position: relative;
            opacity: 0;
            transform: translateY(20px);
            animation: slideInField 0.4s ease-out forwards;
            transition: all 0.3s ease;
        }

        .form-field:nth-child(1) { animation-delay: 0.1s; }
        .form-field:nth-child(2) { animation-delay: 0.2s; }
        .form-field:nth-child(3) { animation-delay: 0.3s; }
        .form-field:nth-child(4) { animation-delay: 0.4s; }

        @keyframes slideInField {
            to {
                opacity: 1;
                transform: translateY(0);
            }
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
            opacity: 0.6;
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

        .btn-danger {
            background: linear-gradient(135deg, var(--danger) 0%, #dc2626 100%);
            color: white;
            box-shadow: var(--shadow);
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
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
            font-weight: 600;
            animation: pulse 2s infinite;
        }

        .alert-error::before {
            content: "\f071";
        }

        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.4); }
            70% { box-shadow: 0 0 0 10px rgba(239, 68, 68, 0); }
            100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
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

        .section-divider {
            height: 1px;
            background: linear-gradient(to right, transparent, var(--gray-300), transparent);
            margin: 32px 0;
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

            .urap-input-container {
                max-width: 100%;
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
            <h1><i class="fas fa-file-medical-alt"></i> Histoire de la Maladie</h1>
            <p>Consultation et motifs de la visite médicale</p>
        </div>

        <div class="content-area">
            <!-- Champ Numero URAP en haut -->
            <div class="urap-header">
                <h2><i class="fas fa-id-card"></i> Numéro URAP du Patient</h2>
                <div class="urap-input-container">
                    <input type="text" class="urap-input" id="numero_urap_header" 
                           value="<?php echo htmlspecialchars($numero_urap); ?>" 
                           placeholder="Saisir le numéro URAP du patient" 
                           onchange="updateMainUrapField(this.value)">
                </div>
            </div>

            <!-- Barre de progression -->
            <div class="progress-bar">
                <div class="progress-fill" id="progressFill"></div>
            </div>

            <!-- Indicateur d'étapes -->
            <div class="step-indicator">
                <div class="step active" id="step1">
                    <i class="fas fa-venus-mars"></i>
                    Sexe
                </div>
                <div class="step inactive" id="step2">
                    <i class="fas fa-clipboard-check"></i>
                    Motifs & Signes
                </div>
            </div>

            <!-- Messages d'alerte -->
            <?php if (!empty($message)): ?>
                <div class="alert alert-<?php echo $messageType; ?>">
                    <?php if ($messageType === 'error' && strpos($message, 'ALERTE') !== false): ?>
                        <?php echo $message; ?>
                        <div style="margin-top: 16px;">
                            <a href="liste_histoire_maladie.php" class="btn btn-primary" style="margin-right: 8px;">
                                <i class="fas fa-list"></i> Voir tous les enregistrements
                            </a>
                            <button type="button" class="btn btn-secondary" onclick="resetForm()">
                                <i class="fas fa-undo"></i> Nouveau numéro URAP
                            </button>
                        </div>
                    <?php else: ?>
                        <?php echo htmlspecialchars($message); ?>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- Informations du patient si disponible -->
            <?php if ($patient): ?>
                <div class="patient-info">
                    <h3><i class="fas fa-user"></i> Patient sélectionné</h3>
                    <p><strong><?php echo htmlspecialchars($patient['Nom_patient'] . ' ' . $patient['Prenom_patient']); ?></strong> - N° URAP: <?php echo htmlspecialchars($patient['Numero_urap']); ?></p>
                </div>
            <?php endif; ?>

            <form id="histoireForm" method="POST">
                <!-- Champ URAP caché pour le formulaire -->
                <input type="hidden" id="numero_urap" name="numero_urap" value="<?php echo htmlspecialchars($numero_urap); ?>" required>

                <!-- Étape 1: Sélection du sexe -->
                <div class="form-section show" id="section-sexe">
                    <h2 class="section-title sexe">
                        <i class="fas fa-venus-mars"></i>
                        Sélection du sexe du patient
                    </h2>
                    <div class="sexe-selection">
                        <div class="sexe-option">
                            <input type="radio" id="sexe_masculin" name="sexe_patient" value="masculin" class="sexe-radio" onchange="handleSexeChange()">
                            <label for="sexe_masculin" class="sexe-label homme">
                                <div class="sexe-icon">
                                    <i class="fas fa-mars"></i>
                                </div>
                                <div class="sexe-text">Masculin</div>
                            </label>
                        </div>
                        <div class="sexe-option">
                            <input type="radio" id="sexe_feminin" name="sexe_patient" value="feminin" class="sexe-radio" onchange="handleSexeChange()">
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
                        Motif de consultation - Homme
                    </h2>
                    <div class="form-grid">
                        <div class="form-field">
                            <label class="form-label required">Motif de la consultation :</label>
                            <select class="form-select" name="motif_homme" id="motif_homme" required>
                                <option value="">-- Choisissez un motif --</option>
                                <option value="paternite">Désir de paternité</option>
                                <option value="dysurie">Dysurie</option>
                                <option value="douleur_testiculaire">Douleur testiculaire</option>
                                <option value="gene_uretral">Gène urétral</option>
                                <option value="amp">AMP</option>
                                <option value="anomalie_spermogramme">Anomalie du spermogramme</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Section Femme -->
                <div class="form-section hide" id="section-femme">
                    <h2 class="section-title femme">
                        <i class="fas fa-venus"></i>
                        Motif de consultation - Femme
                    </h2>
                    <div class="form-grid">
                        <div class="form-field">
                            <label class="form-label required">Motif de la consultation :</label>
                            <select class="form-select" name="motif_femme" id="motif_femme" required>
                                <option value="">-- Choisissez un motif --</option>
                                <option value="gynecologique">Gynécologique</option>
                                <option value="consultation_ist">Consultation IST</option>
                                <option value="agent_contaminateur">Agent contaminateur</option>
                                <option value="desire_grossesse">Désir de grossesse</option>
                                <option value="autre">Autre</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Section Signes fonctionnels (toujours visible) -->
                <div class="form-section show" id="section-signes">
                    <div class="section-divider"></div>
                    <h2 class="section-title general">
                        <i class="fas fa-stethoscope"></i>
                        Signes fonctionnels
                    </h2>
                    <div class="form-grid">
                        <div class="form-field">
                            <label class="form-label">Signes fonctionnels (optionnel) :</label>
                            <select class="form-select" name="signes_fonctionnels" id="signes_fonctionnels">
                                <option value="">-- Choisissez un signe (optionnel) --</option>
                                <option value="leucorrhees">Leucorrhées</option>
                                <option value="prurit">Prurit</option>
                                <option value="mal_odeur">Mauvaise odeur</option>
                                <option value="douleurs_pelviennes">Douleurs pelviennes</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="actions">
                    <button type="button" class="btn btn-secondary" onclick="resetForm()">
                        <i class="fas fa-undo"></i>
                        Réinitialiser
                    </button>
                    <button type="submit" class="btn btn-success" id="btnSubmit">
                        <i class="fas fa-save"></i>
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Synchroniser les champs URAP
        function updateMainUrapField(value) {
            document.getElementById('numero_urap').value = value;
            // Vérifier si le numéro URAP existe déjà
            if (value.trim() !== '') {
                checkUrapExists(value.trim());
            }
        }

        // Vérifier si un numéro URAP existe déjà
        function checkUrapExists(numeroUrap) {
            fetch('check_urap_exists.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'numero_urap=' + encodeURIComponent(numeroUrap)
            })
            .then(response => response.json())
            .then(data => {
                const urapInput = document.getElementById('numero_urap_header');
                if (data.exists) {
                    urapInput.style.borderColor = '#ef4444';
                    urapInput.style.backgroundColor = 'rgba(239, 68, 68, 0.1)';
                    showUrapWarning(numeroUrap, data.patientInfo);
                } else {
                    urapInput.style.borderColor = '';
                    urapInput.style.backgroundColor = '';
                    hideUrapWarning();
                }
            })
            .catch(error => {
                console.error('Erreur lors de la vérification:', error);
            });
        }

        // Afficher l'avertissement URAP
        function showUrapWarning(numeroUrap, patientInfo) {
            let warningDiv = document.getElementById('urap-warning');
            if (!warningDiv) {
                warningDiv = document.createElement('div');
                warningDiv.id = 'urap-warning';
                warningDiv.className = 'alert alert-error';
                warningDiv.style.marginTop = '8px';
                document.querySelector('.urap-input-container').appendChild(warningDiv);
            }
            
            warningDiv.innerHTML = `
                <i class="fas fa-exclamation-triangle"></i>
                <strong>Attention :</strong> Le numéro URAP ${numeroUrap} a déjà été utilisé.
                ${patientInfo ? `<br>Patient : ${patientInfo}` : ''}
            `;
        }

        // Masquer l'avertissement URAP
        function hideUrapWarning() {
            const warningDiv = document.getElementById('urap-warning');
            if (warningDiv) {
                warningDiv.remove();
            }
        }

        // Gestion du changement de sexe
        function handleSexeChange() {
            const sexeSelected = document.querySelector('input[name="sexe_patient"]:checked');
            
            if (sexeSelected) {
                const progressFill = document.getElementById('progressFill');
                progressFill.style.width = '100%';
                
                // Mettre à jour les étapes
                document.getElementById('step2').classList.remove('inactive');
                document.getElementById('step2').classList.add('active');
                
                // Afficher la section appropriée et désactiver l'autre
                if (sexeSelected.value === 'masculin') {
                    showSection('section-homme');
                    disableSection('section-femme');
                    // Nettoyer le champ femme
                    document.getElementById('motif_femme').value = '';
                    document.getElementById('motif_femme').required = false;
                    document.getElementById('motif_homme').required = true;
                } else {
                    showSection('section-femme');
                    disableSection('section-homme');
                    // Nettoyer le champ homme
                    document.getElementById('motif_homme').value = '';
                    document.getElementById('motif_homme').required = false;
                    document.getElementById('motif_femme').required = true;
                }
                
                // Les signes fonctionnels restent toujours visibles et accessibles
                showSection('section-signes');
            }
        }

        function showSection(sectionId) {
            const section = document.getElementById(sectionId);
            if (section) {
                section.classList.remove('hide', 'disabled-section');
                section.classList.add('show');
            }
        }

        function disableSection(sectionId) {
            const section = document.getElementById(sectionId);
            if (section) {
                section.classList.remove('show');
                section.classList.add('hide', 'disabled-section');
            }
        }

        // Fonction pour réinitialiser le formulaire
        function resetForm() {
            if (confirm('Êtes-vous sûr de vouloir réinitialiser le formulaire ?')) {
                document.getElementById('histoireForm').reset();
                document.getElementById('numero_urap_header').value = '';
                
                // Réinitialiser les sections
                document.getElementById('section-homme').classList.remove('show', 'disabled-section');
                document.getElementById('section-homme').classList.add('hide');
                document.getElementById('section-femme').classList.remove('show', 'disabled-section');
                document.getElementById('section-femme').classList.add('hide');
                document.getElementById('section-signes').classList.remove('hide', 'disabled-section');
                document.getElementById('section-signes').classList.add('show');
                
                // Réinitialiser la progression
                document.getElementById('progressFill').style.width = '50%';
                document.getElementById('step2').classList.remove('active');
                document.getElementById('step2').classList.add('inactive');
                
                // Réinitialiser les champs required
                document.getElementById('motif_homme').required = false;
                document.getElementById('motif_femme').required = false;
            }
        }

        // Validation du formulaire avant soumission
        document.getElementById('histoireForm').addEventListener('submit', function(e) {
            console.log('Soumission du formulaire...');
            
            const numeroUrap = document.getElementById('numero_urap').value.trim();
            const sexeSelected = document.querySelector('input[name="sexe_patient"]:checked');
            
            if (!numeroUrap) {
                e.preventDefault();
                alert('Veuillez saisir le numéro URAP du patient.');
                document.getElementById('numero_urap_header').focus();
                return;
            }
            
            if (!sexeSelected) {
                e.preventDefault();
                alert('Veuillez sélectionner le sexe du patient.');
                return;
            }
            
            console.log('Sexe sélectionné:', sexeSelected.value);
            
            // Validation spécifique selon le sexe
            if (sexeSelected.value === 'masculin') {
                const motifHomme = document.getElementById('motif_homme').value;
                if (!motifHomme) {
                    e.preventDefault();
                    alert('Veuillez sélectionner un motif de consultation pour un patient masculin.');
                    return;
                }
            } else if (sexeSelected.value === 'feminin') {
                const motifFemme = document.getElementById('motif_femme').value;
                if (!motifFemme) {
                    e.preventDefault();
                    alert('Veuillez sélectionner un motif de consultation pour une patiente féminine.');
                    return;
                }
            }

            // Animation de soumission
            const submitBtn = document.getElementById('btnSubmit');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enregistrement...';
            submitBtn.disabled = true;
            
            console.log('Formulaire prêt à être soumis');
        });

        // Initialisation
        document.addEventListener('DOMContentLoaded', function() {
            // Toujours afficher la section signes fonctionnels
            showSection('section-signes');
            
            // Animation d'apparition des éléments
            const formFields = document.querySelectorAll('.form-field');
            formFields.forEach((field, index) => {
                field.style.opacity = '0';
                field.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    field.style.transition = 'all 0.3s ease';
                    field.style.opacity = '1';
                    field.style.transform = 'translateY(0)';
                }, index * 200);
            });
            
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