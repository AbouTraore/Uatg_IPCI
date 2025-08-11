<?php
require_once("identifier.php");
require_once("connexion.php");

$numero_urap = $_GET['urap'] ?? '';
$histoire_id = $_GET['id'] ?? '';
$message = '';
$message_type = '';

// Récupérer les informations du patient pour déterminer le sexe
$stmt = $pdo->prepare("SELECT Sexe_patient FROM patient WHERE Numero_urap = ?");
$stmt->execute([$numero_urap]);
$patient = $stmt->fetch(PDO::FETCH_ASSOC);
$sexe_patient = $patient ? strtolower($patient['Sexe_patient']) : '';

// Récupérer les données existantes
$histoire = null;
if ($histoire_id) {
    $stmt = $pdo->prepare("SELECT * FROM histoire_maladie WHERE ID_histoire_maladie = ? AND Numero_urap = ?");
    $stmt->execute([$histoire_id, $numero_urap]);
    $histoire = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Initialiser les valeurs par défaut
$sexe_patient_form = $histoire ? $histoire['sexe_patient'] : $sexe_patient;
$motif_homme = $histoire ? $histoire['motif_homme'] : '';
$motif_femme = $histoire ? $histoire['motif_femme'] : '';
$signes_fonctionnels = $histoire ? $histoire['signes_fonctionnels'] : '';

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sexe_patient_form = htmlspecialchars($_POST['sexe_patient'] ?? '');
    $motif_homme = htmlspecialchars($_POST['motif_homme'] ?? '');
    $motif_femme = htmlspecialchars($_POST['motif_femme'] ?? '');
    $signes_fonctionnels = htmlspecialchars($_POST['signes_fonctionnels'] ?? '');
    $date_creation = date('Y-m-d');

    try {
        if ($histoire_id && $histoire) {
            // Modification
            $sql = "UPDATE histoire_maladie SET 
                    sexe_patient = ?, 
                    motif_homme = ?, 
                    motif_femme = ?, 
                    signes_fonctionnels = ?, 
                    date_creation = ? 
                    WHERE ID_histoire_maladie = ?";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$sexe_patient_form, $motif_homme, $motif_femme, $signes_fonctionnels, $date_creation, $histoire_id]);
            
            $message = "L'histoire de la maladie a été mise à jour avec succès !";
        } else {
            // Création - Générer un nouvel ID
            $stmt = $pdo->prepare("SELECT MAX(ID_histoire_maladie) as max_id FROM histoire_maladie");
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $nouvel_id = ($result['max_id'] ?? 0) + 1;
            
            $sql = "INSERT INTO histoire_maladie (ID_histoire_maladie, Numero_urap, sexe_patient, motif_homme, motif_femme, signes_fonctionnels, date_creation) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nouvel_id, $numero_urap, $sexe_patient_form, $motif_homme, $motif_femme, $signes_fonctionnels, $date_creation]);
            
            $message = "L'histoire de la maladie a été enregistrée avec succès !";
            $histoire_id = $nouvel_id;
        }
        
        $message_type = 'success';
        
        // Recharger les données
        $stmt = $pdo->prepare("SELECT * FROM histoire_maladie WHERE ID_histoire_maladie = ?");
        $stmt->execute([$histoire_id]);
        $histoire = $stmt->fetch(PDO::FETCH_ASSOC);
        
    } catch (PDOException $e) {
        $message = "Erreur lors de l'enregistrement : " . $e->getMessage();
        $message_type = 'danger';
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $histoire ? 'Modifier' : 'Ajouter' ?> Histoire de la Maladie</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary: #0047ab;
            --primary-light: #1e90ff;
            --success: #22c55e;
            --danger: #ef4444;
            --warning: #f59e0b;
            --purple: #8b5cf6;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
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
            color: var(--gray-800);
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 32px;
            margin-bottom: 24px;
            box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1);
            text-align: center;
        }

        .header h1 {
            color: var(--purple);
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .alert {
            padding: 16px 20px;
            margin-bottom: 24px;
            border-radius: 12px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #34d399;
        }

        .alert-danger {
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fca5a5;
        }

        .form-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 32px;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            border-left: 4px solid var(--purple);
        }

        .form-section {
            margin-bottom: 32px;
            padding-bottom: 24px;
            border-bottom: 2px solid var(--gray-100);
        }

        .form-section:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        .section-title {
            color: var(--purple);
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
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
            margin-bottom: 8px;
        }

        .required::after {
            content: " *";
            color: var(--danger);
            font-weight: 600;
        }

        .form-input, .form-textarea {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid var(--gray-200);
            border-radius: 12px;
            font-size: 16px;
            transition: all 0.2s ease;
            background: white;
            font-family: inherit;
        }

        .form-textarea {
            min-height: 100px;
            resize: vertical;
        }

        .form-input:focus, .form-textarea:focus {
            outline: none;
            border-color: var(--purple);
            box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
        }

        .full-width {
            grid-column: 1 / -1;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--purple) 0%, #a855f7 100%);
            color: white;
        }

        .btn-secondary {
            background: white;
            color: var(--gray-700);
            border: 1px solid var(--gray-300);
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        }

        .actions-bar {
            display: flex;
            justify-content: space-between;
            margin-top: 32px;
            gap: 16px;
        }

        .info-box {
            background: #f5f3ff;
            border: 1px solid #e9d5ff;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 24px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .info-box i {
            color: var(--purple);
            font-size: 1.2rem;
            margin-top: 2px;
        }

        .info-box-content h4 {
            color: var(--purple);
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .info-box-content p {
            color: #7c3aed;
            font-size: 0.875rem;
            line-height: 1.4;
        }

        .motif-section {
            padding: 20px;
            background: var(--gray-50);
            border-radius: 12px;
            border: 2px dashed var(--gray-300);
            margin-bottom: 20px;
        }

        .motif-section.active {
            border-color: var(--purple);
            background: rgba(139, 92, 246, 0.05);
        }

        .motif-section.hidden {
            display: none;
        }

        .motif-title {
            color: var(--purple);
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .actions-bar {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-file-medical-alt"></i> <?= $histoire ? 'Modifier' : 'Ajouter' ?> Histoire de la Maladie</h1>
            <p>Patient N°URAP: <?= htmlspecialchars($numero_urap) ?></p>
        </div>

        <?php if (!empty($message)): ?>
            <div class="alert alert-<?= $message_type ?>">
                <i class="fas fa-<?= ($message_type == 'success' ? 'check-circle' : 'exclamation-triangle') ?>"></i>
                <?= $message ?>
            </div>
        <?php endif; ?>

        <div class="info-box">
            <i class="fas fa-info-circle"></i>
            <div class="info-box-content">
                <h4>Information importante</h4>
                <p>L'histoire de la maladie permet de documenter les symptômes et motifs de consultation du patient. Les champs affichés s'adaptent automatiquement selon le sexe du patient.</p>
            </div>
        </div>

        <div class="form-container">
            <form method="post">
                <!-- Section Informations générales -->
                <div class="form-section">
                    <h3 class="section-title">
                        <i class="fas fa-user"></i>
                        Informations générales
                    </h3>
                    <div class="form-grid">
                        <div class="form-field">
                            <label for="sexe_patient" class="form-label required">Sexe du patient</label>
                            <select id="sexe_patient" name="sexe_patient" class="form-input" required onchange="toggleMotifs()">
                                <option value="">-- Sélectionner --</option>
                                <option value="masculin" <?= $sexe_patient_form == 'masculin' ? 'selected' : '' ?>>Masculin</option>
                                <option value="feminin" <?= $sexe_patient_form == 'feminin' ? 'selected' : '' ?>>Féminin</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Section Motifs selon le sexe -->
                <div class="form-section">
                    <h3 class="section-title">
                        <i class="fas fa-stethoscope"></i>
                        Motif de consultation
                    </h3>
                    
                    <!-- Motifs pour homme -->
                    <div id="motifs-homme" class="motif-section <?= $sexe_patient_form == 'masculin' ? 'active' : 'hidden' ?>">
                        <div class="motif-title">
                            <i class="fas fa-male"></i>
                            Motifs de consultation (Homme)
                        </div>
                        <div class="form-field">
                            <label for="motif_homme" class="form-label">Motif de consultation</label>
                            <select id="motif_homme" name="motif_homme" class="form-input">
                                <option value="">-- Sélectionner --</option>
                                <option value="gene_uretral" <?= $motif_homme == 'gene_uretral' ? 'selected' : '' ?>>Gêne urétrale</option>
                                <option value="ecoulement_uretral" <?= $motif_homme == 'ecoulement_uretral' ? 'selected' : '' ?>>Écoulement urétral</option>
                                <option value="brulure_mictionnelle" <?= $motif_homme == 'brulure_mictionnelle' ? 'selected' : '' ?>>Brûlure mictionnelle</option>
                                <option value="douleur_testiculaire" <?= $motif_homme == 'douleur_testiculaire' ? 'selected' : '' ?>>Douleur testiculaire</option>
                                <option value="ulceration_genitale" <?= $motif_homme == 'ulceration_genitale' ? 'selected' : '' ?>>Ulcération génitale</option>
                                <option value="prurit_genital" <?= $motif_homme == 'prurit_genital' ? 'selected' : '' ?>>Prurit génital</option>
                                <option value="amp" <?= $motif_homme == 'amp' ? 'selected' : '' ?>>AMP (Assistance Médicale à la Procréation)</option>
                                <option value="adenopathie_inguinale" <?= $motif_homme == 'adenopathie_inguinale' ? 'selected' : '' ?>>Adénopathie inguinale</option>
                                <option value="controle" <?= $motif_homme == 'controle' ? 'selected' : '' ?>>Contrôle</option>
                                <option value="autre" <?= $motif_homme == 'autre' ? 'selected' : '' ?>>Autre</option>
                            </select>
                        </div>
                    </div>

                    <!-- Motifs pour femme -->
                    <div id="motifs-femme" class="motif-section <?= $sexe_patient_form == 'feminin' ? 'active' : 'hidden' ?>">
                        <div class="motif-title">
                            <i class="fas fa-female"></i>
                            Motifs de consultation (Femme)
                        </div>
                        <div class="form-field">
                            <label for="motif_femme" class="form-label">Motif de consultation</label>
                            <select id="motif_femme" name="motif_femme" class="form-input">
                                <option value="">-- Sélectionner --</option>
                                <option value="leucorrhee" <?= $motif_femme == 'leucorrhee' ? 'selected' : '' ?>>Leucorrhée</option>
                                <option value="prurit_vulvaire" <?= $motif_femme == 'prurit_vulvaire' ? 'selected' : '' ?>>Prurit vulvaire</option>
                                <option value="douleur_pelvienne" <?= $motif_femme == 'douleur_pelvienne' ? 'selected' : '' ?>>Douleur pelvienne</option>
                                <option value="brulure_mictionnelle" <?= $motif_femme == 'brulure_mictionnelle' ? 'selected' : '' ?>>Brûlure mictionnelle</option>
                                <option value="ulceration_genitale" <?= $motif_femme == 'ulceration_genitale' ? 'selected' : '' ?>>Ulcération génitale</option>
                                <option value="dyspaereunie" <?= $motif_femme == 'dyspaereunie' ? 'selected' : '' ?>>Dyspareunie</option>
                                <option value="metrorragie" <?= $motif_femme == 'metrorragie' ? 'selected' : '' ?>>Métrorragie</option>
                                <option value="amenorrhee" <?= $motif_femme == 'amenorrhee' ? 'selected' : '' ?>>Aménorrhée</option>
                                <option value="agent_contaminateur" <?= $motif_femme == 'agent_contaminateur' ? 'selected' : '' ?>>Recherche agent contaminateur</option>
                                <option value="amp" <?= $motif_femme == 'amp' ? 'selected' : '' ?>>AMP (Assistance Médicale à la Procréation)</option>
                                <option value="controle" <?= $motif_femme == 'controle' ? 'selected' : '' ?>>Contrôle</option>
                                <option value="autre" <?= $motif_femme == 'autre' ? 'selected' : '' ?>>Autre</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Section Signes fonctionnels -->
                <div class="form-section">
                    <h3 class="section-title">
                        <i class="fas fa-notes-medical"></i>
                        Signes fonctionnels
                    </h3>
                    <div class="form-grid">
                        <div class="form-field full-width">
                            <label for="signes_fonctionnels" class="form-label">Signes fonctionnels observés</label>
                            <select id="signes_fonctionnels" name="signes_fonctionnels" class="form-input">
                                <option value="">-- Sélectionner --</option>
                                <option value="prurit" <?= $signes_fonctionnels == 'prurit' ? 'selected' : '' ?>>Prurit</option>
                                <option value="mal_odeur" <?= $signes_fonctionnels == 'mal_odeur' ? 'selected' : '' ?>>Mauvaise odeur</option>
                                <option value="brulure" <?= $signes_fonctionnels == 'brulure' ? 'selected' : '' ?>>Brûlure</option>
                                <option value="douleur" <?= $signes_fonctionnels == 'douleur' ? 'selected' : '' ?>>Douleur</option>
                                <option value="ecoulement" <?= $signes_fonctionnels == 'ecoulement' ? 'selected' : '' ?>>Écoulement</option>
                                <option value="pertes_vaginales" <?= $signes_fonctionnels == 'pertes_vaginales' ? 'selected' : '' ?>>Pertes vaginales</option>
                                <option value="dysurie" <?= $signes_fonctionnels == 'dysurie' ? 'selected' : '' ?>>Dysurie</option>
                                <option value="pollakiurie" <?= $signes_fonctionnels == 'pollakiurie' ? 'selected' : '' ?>>Pollakiurie</option>
                                <option value="fievre" <?= $signes_fonctionnels == 'fievre' ? 'selected' : '' ?>>Fièvre</option>
                                <option value="aucun" <?= $signes_fonctionnels == 'aucun' ? 'selected' : '' ?>>Aucun signe</option>
                                <option value="autre" <?= $signes_fonctionnels == 'autre' ? 'selected' : '' ?>>Autre</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="actions-bar">
                    <a href="dossier_complet.php?urap=<?= htmlspecialchars($numero_urap) ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Annuler
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> <?= $histoire ? 'Mettre à jour' : 'Enregistrer' ?>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleMotifs() {
            const sexeSelect = document.getElementById('sexe_patient');
            const motifsHomme = document.getElementById('motifs-homme');
            const motifsFemme = document.getElementById('motifs-femme');
            const selectHomme = document.getElementById('motif_homme');
            const selectFemme = document.getElementById('motif_femme');
            
            if (sexeSelect.value === 'masculin') {
                motifsHomme.classList.remove('hidden');
                motifsHomme.classList.add('active');
                motifsFemme.classList.add('hidden');
                motifsFemme.classList.remove('active');
                selectFemme.value = '';
            } else if (sexeSelect.value === 'feminin') {
                motifsFemme.classList.remove('hidden');
                motifsFemme.classList.add('active');
                motifsHomme.classList.add('hidden');
                motifsHomme.classList.remove('active');
                selectHomme.value = '';
            } else {
                motifsHomme.classList.add('hidden');
                motifsHomme.classList.remove('active');
                motifsFemme.classList.add('hidden');
                motifsFemme.classList.remove('active');
                selectHomme.value = '';
                selectFemme.value = '';
            }
        }

        // Initialiser l'état au chargement
        document.addEventListener('DOMContentLoaded', function() {
            toggleMotifs();
        });
    </script>
</body>
</html>