<?php
require_once("identifier.php");
require_once("connexion.php");

$numero_urap = $_GET['urap'] ?? '';
$patient = null;
$message = '';
$messageType = '';

// Récupérer les données existantes du patient
if ($numero_urap) {
    $stmt = $pdo->prepare("SELECT * FROM patient WHERE Numero_urap = ?");
    $stmt->execute([$numero_urap]);
    $patient = $stmt->fetch(PDO::FETCH_ASSOC);
}

if (!$patient) {
    echo "<div class='alert alert-danger'>Patient non trouvé.</div>";
    exit;
}

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = trim($_POST['Nom'] ?? '');
    $prenom = trim($_POST['Prenom'] ?? '');
    $age = intval($_POST['Age'] ?? 0);
    $sexe = $_POST['SexeP'] ?? '';
    $date_naissance = $_POST['datenaiss'] ?? '';
    $contact = trim($_POST['contact'] ?? '');
    $adresse = trim($_POST['Adresse'] ?? '');
    $situation = $_POST['SituaM'] ?? '';
    $lieu = $_POST['reside'] ?? '';
    $precise = trim($_POST['Precise'] ?? '');
    $logement = $_POST['Type_log'] ?? '';
    $niveau = $_POST['NiveauE'] ?? '';
    $profession = $_POST['Profession'] ?? '';

    // Validation
    if (empty($nom) || empty($prenom) || empty($contact)) {
        $message = "Les champs nom, prénom et contact sont obligatoires.";
        $messageType = 'error';
    } else {
        try {
            $sql = "UPDATE patient SET 
                    Nom_patient = ?, Prenom_patient = ?, Age = ?, Sexe_patient = ?, 
                    Date_naissance = ?, Contact_patient = ?, Lieu_résidence = ?, 
                    Precise = ?, Situation_matrimoniale = ?, Type_logement = ?, 
                    Niveau_etude = ?, Profession = ?
                    WHERE Numero_urap = ?";
            
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute([
                $nom, $prenom, $age, $sexe, $date_naissance, $contact, 
                $lieu, $precise, $situation, $logement, $niveau, $profession, $numero_urap
            ]);
            
            if ($result) {
                $message = "Les informations du patient ont été mises à jour avec succès !";
                $messageType = 'success';
                
                // Recharger les données du patient
                $stmt = $pdo->prepare("SELECT * FROM patient WHERE Numero_urap = ?");
                $stmt->execute([$numero_urap]);
                $patient = $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                $message = "Erreur lors de la mise à jour du patient.";
                $messageType = 'error';
            }
        } catch (PDOException $e) {
            $message = "Erreur lors de la mise à jour : " . $e->getMessage();
            $messageType = 'error';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Modifier Patient - UATG</title>
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
            text-decoration: none;
        }

        .btn-retour:hover {
            background: var(--gray-200);
        }

        .patient-overview {
            background: linear-gradient(135deg, var(--accent) 0%, #059669 100%);
            color: white;
            padding: 24px 32px;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
        }

        .patient-info h2 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .patient-meta {
            font-size: 1rem;
            opacity: 0.9;
        }

        .patient-stats {
            display: flex;
            align-items: center;
            gap: 24px;
            font-size: 0.9rem;
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            font-size: 1.2rem;
            font-weight: 700;
            display: block;
        }

        .content-area {
            padding: 40px 32px;
            background: white;
        }

        .form-section {
            margin-bottom: 32px;
            opacity: 0;
            transform: translateY(20px);
            animation: slideInSection 0.6s ease-out forwards;
        }

        .form-section:nth-child(1) { animation-delay: 0.1s; }
        .form-section:nth-child(2) { animation-delay: 0.2s; }
        .form-section:nth-child(3) { animation-delay: 0.3s; }
        .form-section:nth-child(4) { animation-delay: 0.4s; }

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
            padding-bottom: 8px;
            border-bottom: 2px solid var(--gray-100);
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

        .required::after {
            content: " *";
            color: var(--danger);
            font-weight: 600;
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

        .message-container {
            max-width: 100%;
            margin: 0 0 24px 0;
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
            .patient-overview {
                flex-direction: column;
                text-align: center;
            }
            .patient-stats {
                justify-content: center;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <a href="dossier_complet.php?urap=<?= htmlspecialchars($numero_urap) ?>" class="btn-retour">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
            <h1><i class="fas fa-user-edit"></i> Modifier Patient</h1>
            <p>Mise à jour des informations du patient</p>
        </div>

        <div class="patient-overview">
            <div class="patient-info">
                <h2><?= htmlspecialchars($patient['Nom_patient'] . ' ' . $patient['Prenom_patient']) ?></h2>
                <div class="patient-meta">
                    N°URAP: <?= htmlspecialchars($patient['Numero_urap']) ?> | 
                    <?= htmlspecialchars($patient['Age']) ?> ans | 
                    <?= htmlspecialchars($patient['Sexe_patient']) ?>
                </div>
            </div>
            <div class="patient-stats">
                <div class="stat-item">
                    <span class="stat-number"><?= htmlspecialchars($patient['Numero_urap']) ?></span>
                    <span>N°URAP</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number"><?= htmlspecialchars($patient['Age']) ?></span>
                    <span>Ans</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number"><?= htmlspecialchars($patient['Contact_patient']) ?></span>
                    <span>Contact</span>
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
            
            <form method="POST" id="modifierPatientForm">
                <div class="form-section">
                    <h2 class="section-title"><i class="fas fa-user"></i> Informations personnelles</h2>
                    <div class="form-grid">
                        <div class="form-field">
                            <label class="form-label">N° Urap</label>
                            <input type="text" class="form-input" value="<?= htmlspecialchars($patient['Numero_urap']) ?>" disabled>
                        </div>
                        <div class="form-field">
                            <label class="form-label required">Nom</label>
                            <input type="text" class="form-input" id="Nom" name="Nom" required 
                                   value="<?= htmlspecialchars($patient['Nom_patient']) ?>" placeholder="Taper le nom">
                        </div>
                        <div class="form-field">
                            <label class="form-label required">Prénom</label>
                            <input type="text" class="form-input" id="Prenom" name="Prenom" required 
                                   value="<?= htmlspecialchars($patient['Prenom_patient']) ?>" placeholder="Taper le prénom">
                        </div>
                        <div class="form-field">
                            <label class="form-label required">Date de naissance</label>
                            <input type="date" class="form-input" id="datenaiss" name="datenaiss" required
                                   value="<?= htmlspecialchars($patient['Date_naissance']) ?>">
                        </div>
                        <div class="form-field">
                            <label class="form-label">Âge</label>
                            <input type="text" class="form-input" id="Age" name="Age" readonly 
                                   value="<?= htmlspecialchars($patient['Age']) ?>" placeholder="L'âge du patient">
                        </div>
                        <div class="form-field">
                            <label class="form-label">Sexe</label>
                            <select class="form-select" id="SexeP" name="SexeP">
                                <option value="Masculin" <?= $patient['Sexe_patient'] == 'Masculin' ? 'selected' : '' ?>>Masculin</option>
                                <option value="Féminin" <?= $patient['Sexe_patient'] == 'Féminin' ? 'selected' : '' ?>>Féminin</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="form-section">
                    <h2 class="section-title"><i class="fas fa-address-card"></i> Contact et adresse</h2>
                    <div class="form-grid">
                        <div class="form-field">
                            <label class="form-label required">Contact</label>
                            <input type="tel" class="form-input" id="contact" name="contact" required 
                                   value="<?= htmlspecialchars($patient['Contact_patient']) ?>" placeholder="Taper le contact">
                        </div>
                        <div class="form-field">
                            <label class="form-label">Adresse</label>
                            <input type="text" class="form-input" id="Adresse" name="Adresse" 
                                   value="<?= htmlspecialchars($patient['Lieu_résidence'] ?? '') ?>" placeholder="Taper l'adresse">
                        </div>
                        <div class="form-field">
                            <label class="form-label">Lieu de résidence</label>
                            <select class="form-select" id="reside" name="reside" onchange="togglePreciseField()">
                                <option value="Abidjan" <?= $patient['Lieu_résidence'] == 'Abidjan' ? 'selected' : '' ?>>Abidjan</option>
                                <option value="Hors Abidjan" <?= $patient['Lieu_résidence'] == 'Hors Abidjan' ? 'selected' : '' ?>>Hors Abidjan</option>
                            </select>
                        </div>
                        <div class="form-field">
                            <label class="form-label">Précisez le lieu</label>
                            <div id="preciseContainer">
                                <select class="form-select" id="PreciseSelect" name="Precise" style="display: block;">
                                    <option value="">Sélectionnez une commune</option>
                                    <option value="Abobo" <?= $patient['Precise'] == 'Abobo' ? 'selected' : '' ?>>Abobo</option>
                                    <option value="Adjamé" <?= $patient['Precise'] == 'Adjamé' ? 'selected' : '' ?>>Adjamé</option>
                                    <option value="Anyama" <?= $patient['Precise'] == 'Anyama' ? 'selected' : '' ?>>Anyama</option>
                                    <option value="Attécoubé" <?= $patient['Precise'] == 'Attécoubé' ? 'selected' : '' ?>>Attécoubé</option>
                                    <option value="Bingerville" <?= $patient['Precise'] == 'Bingerville' ? 'selected' : '' ?>>Bingerville</option>
                                    <option value="Cocody" <?= $patient['Precise'] == 'Cocody' ? 'selected' : '' ?>>Cocody</option>
                                    <option value="Koumassi" <?= $patient['Precise'] == 'Koumassi' ? 'selected' : '' ?>>Koumassi</option>
                                    <option value="Marcory" <?= $patient['Precise'] == 'Marcory' ? 'selected' : '' ?>>Marcory</option>
                                    <option value="Plateau" <?= $patient['Precise'] == 'Plateau' ? 'selected' : '' ?>>Plateau</option>
                                    <option value="Port-Bouët" <?= $patient['Precise'] == 'Port-Bouët' ? 'selected' : '' ?>>Port-Bouët</option>
                                    <option value="Songon" <?= $patient['Precise'] == 'Songon' ? 'selected' : '' ?>>Songon</option>
                                    <option value="Treichville" <?= $patient['Precise'] == 'Treichville' ? 'selected' : '' ?>>Treichville</option>
                                    <option value="Yopougon" <?= $patient['Precise'] == 'Yopougon' ? 'selected' : '' ?>>Yopougon</option>
                                </select>
                                <input type="text" class="form-input" id="PreciseInput" name="Precise" 
                                       value="<?= htmlspecialchars($patient['Precise'] ?? '') ?>"
                                       placeholder="Précisez le lieu de résidence" style="display: none;">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="form-section">
                    <h2 class="section-title"><i class="fas fa-info-circle"></i> Informations sociales</h2>
                    <div class="form-grid">
                        <div class="form-field">
                            <label class="form-label">Situation matrimoniale</label>
                            <select class="form-select" id="SituaM" name="SituaM">
                                <option value="Célibataire" <?= $patient['Situation_matrimoniale'] == 'Célibataire' ? 'selected' : '' ?>>Célibataire</option>
                                <option value="Marié" <?= $patient['Situation_matrimoniale'] == 'Marié' ? 'selected' : '' ?>>Marié(e)</option>
                                <option value="Divorcé" <?= $patient['Situation_matrimoniale'] == 'Divorcé' ? 'selected' : '' ?>>Divorcé(e)</option>
                                <option value="Veuve" <?= $patient['Situation_matrimoniale'] == 'Veuve' ? 'selected' : '' ?>>Veuf/Veuve</option>
                            </select>
                        </div>
                        <div class="form-field">
                            <label class="form-label">Type de logement</label>
                            <select class="form-select" id="Type_log" name="Type_log">
                                <option value="Studio" <?= $patient['Type_logement'] == 'Studio' ? 'selected' : '' ?>>Studio</option>
                                <option value="Cour commune" <?= $patient['Type_logement'] == 'Cour commune' ? 'selected' : '' ?>>Cour commune</option>
                                <option value="Villa" <?= $patient['Type_logement'] == 'Villa' ? 'selected' : '' ?>>Villa</option>
                                <option value="Baraquement" <?= $patient['Type_logement'] == 'Baraquement' ? 'selected' : '' ?>>Baraquement</option>
                                <option value="Autre" <?= $patient['Type_logement'] == 'Autre' ? 'selected' : '' ?>>Autre</option>
                            </select>
                        </div>
                        <div class="form-field">
                            <label class="form-label">Niveau d'étude</label>
                            <select class="form-select" id="NiveauE" name="NiveauE">
                                <option value="Aucun" <?= $patient['Niveau_etude'] == 'Aucun' ? 'selected' : '' ?>>Aucun</option>
                                <option value="Primaire" <?= $patient['Niveau_etude'] == 'Primaire' ? 'selected' : '' ?>>Primaire</option>
                                <option value="Secondaire" <?= $patient['Niveau_etude'] == 'Secondaire' ? 'selected' : '' ?>>Secondaire</option>
                                <option value="Universitaire" <?= $patient['Niveau_etude'] == 'Universitaire' ? 'selected' : '' ?>>Universitaire</option>
                            </select>
                        </div>
                        <div class="form-field">
                            <label class="form-label">Profession</label>
                            <select class="form-select" id="Profession" name="Profession">
                                <option value="Aucun" <?= $patient['Profession'] == 'Aucun' ? 'selected' : '' ?>>Aucun</option>
                                <option value="Etudiant" <?= $patient['Profession'] == 'Etudiant' ? 'selected' : '' ?>>Étudiant</option>
                                <option value="Eleve" <?= $patient['Profession'] == 'Eleve' ? 'selected' : '' ?>>Élève</option>
                                <option value="Corps habillé" <?= $patient['Profession'] == 'Corps habillé' ? 'selected' : '' ?>>Corps habillé</option>
                                <option value="Cadre superieur" <?= $patient['Profession'] == 'Cadre superieur' ? 'selected' : '' ?>>Cadre supérieur</option>
                                <option value="Cadre moyen" <?= $patient['Profession'] == 'Cadre moyen' ? 'selected' : '' ?>>Cadre moyen</option>
                                <option value="Secteur informel" <?= $patient['Profession'] == 'Secteur informel' ? 'selected' : '' ?>>Secteur informel</option>
                                <option value="Sans profession" <?= $patient['Profession'] == 'Sans profession' ? 'selected' : '' ?>>Sans profession</option>
                                <option value="Retraité" <?= $patient['Profession'] == 'Retraité' ? 'selected' : '' ?>>Retraité</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="actions">
                    <a href="dossier_complet.php?urap=<?= htmlspecialchars($numero_urap) ?>" class="btn btn-secondary">
                        <i class="fas fa-times"></i>
                        Annuler
                    </a>
                    <a href="visites_patient.php?urap=<?= htmlspecialchars($numero_urap) ?>" class="btn btn-accent">
                        <i class="fas fa-calendar-alt"></i>
                        Voir les visites
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Sauvegarder les modifications
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Gérer le champ "Precise" avec communes d'Abidjan
        function togglePreciseField() {
            const selectReside = document.getElementById("reside");
            const selectPrecise = document.getElementById("PreciseSelect");
            const inputPrecise = document.getElementById("PreciseInput");

            if (selectReside.value === "Abidjan") {
                // Afficher le select des communes d'Abidjan
                selectPrecise.style.display = "block";
                inputPrecise.style.display = "none";
                selectPrecise.disabled = false;
                inputPrecise.disabled = true;
                inputPrecise.value = ""; // Vider le champ input
            } else {
                // Afficher l'input text pour hors Abidjan
                selectPrecise.style.display = "none";
                inputPrecise.style.display = "block";
                selectPrecise.disabled = true;
                inputPrecise.disabled = false;
                selectPrecise.value = ""; // Vider le select
                inputPrecise.placeholder = "Précisez le lieu de résidence";
            }
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

        // Validation du formulaire
        function validateForm() {
            const nom = document.getElementById('Nom').value.trim();
            const prenom = document.getElementById('Prenom').value.trim();
            const contact = document.getElementById('contact').value.trim();

            if (!nom || !prenom || !contact) {
                showAlert('Les champs nom, prénom et contact sont obligatoires.', 'error');
                return false;
            }

            return true;
        }

        // Afficher une alerte personnalisée
        function showAlert(message, type) {
            // Supprimer les alertes existantes
            const existingAlerts = document.querySelectorAll('.message-container');
            existingAlerts.forEach(alert => alert.remove());

            // Créer la nouvelle alerte
            const alertDiv = document.createElement('div');
            alertDiv.className = `message-container message-${type}`;
            
            const icon = type === 'error' ? 'exclamation-triangle' : 'check-circle';
            alertDiv.innerHTML = `
                <i class="fas fa-${icon}"></i>
                ${message}
            `;

            // Insérer l'alerte au début du content-area
            const contentArea = document.querySelector('.content-area');
            contentArea.insertBefore(alertDiv, contentArea.firstChild);

            // Faire défiler vers le haut pour voir l'alerte
            window.scrollTo({ top: 0, behavior: 'smooth' });

            // Supprimer l'alerte après 5 secondes si c'est un succès
            if (type === 'success') {
                setTimeout(() => {
                    alertDiv.style.opacity = '0';
                    setTimeout(() => alertDiv.remove(), 300);
                }, 5000);
            }
        }

        // Initialisation
        document.addEventListener('DOMContentLoaded', function() {
            const dateInput = document.getElementById('datenaiss');
            dateInput.addEventListener('change', calculateAge);
            dateInput.addEventListener('input', calculateAge);

            // Initialiser le champ Precise
            togglePreciseField();

            // Validation du formulaire à la soumission
            document.getElementById('modifierPatientForm').addEventListener('submit', function(e) {
                if (!validateForm()) {
                    e.preventDefault();
                    return false;
                }

                // Animation du bouton de soumission
                const submitBtn = this.querySelector('button[type="submit"]');
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sauvegarde...';
                submitBtn.disabled = true;
            });

            // Effacer le message de succès après 5 secondes
            <?php if ($messageType === 'success'): ?>
                setTimeout(() => {
                    const messageDiv = document.querySelector('.message-container');
                    if (messageDiv) {
                        messageDiv.style.opacity = '0';
                        setTimeout(() => messageDiv.remove(), 300);
                    }
                }, 5000);
            <?php endif; ?>
        });
    </script>
</body>
</html>