<?php
// histoire_maladie.php

// Inclusion des fichiers de sécurité et de connexion à la base de données
require_once("identifier.php");
require_once("connexion.php");

// Variables pour stocker les données du formulaire
$message = '';
$messageType = '';

// Traitement du formulaire si soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Récupération des données du formulaire
        $motif_homme = htmlspecialchars($_POST['motif_homme'] ?? '');
        $motif_femme = htmlspecialchars($_POST['motif_femme'] ?? '');
        $signes_fonctionnels = htmlspecialchars($_POST['signes_fonctionnels'] ?? '');
        
        // Récupération de l'ID du patient si fourni
        $patient_id = $_GET['idU'] ?? $_POST['patient_id'] ?? null;
        
        // Validation : au moins un champ doit être rempli
        if (empty($motif_homme) && empty($motif_femme) && empty($signes_fonctionnels)) {
            throw new Exception("Veuillez sélectionner au moins un motif ou signe fonctionnel.");
        }
        
        // Préparation de la requête d'insertion
        $sql = "INSERT INTO histoire_maladie (
            patient_id, motif_homme, motif_femme, signes_fonctionnels, date_creation
        ) VALUES (
            :patient_id, :motif_homme, :motif_femme, :signes_fonctionnels, NOW()
        )";
        
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([
            ':patient_id' => $patient_id,
            ':motif_homme' => $motif_homme,
            ':motif_femme' => $motif_femme,
            ':signes_fonctionnels' => $signes_fonctionnels
        ]);
        
        if ($result) {
            $message = 'Histoire de la maladie enregistrée avec succès !';
            $messageType = 'success';
            // Réinitialiser les variables pour vider le formulaire
            $motif_homme = $motif_femme = $signes_fonctionnels = '';
        } else {
            $message = 'Erreur lors de l\'enregistrement de l\'histoire de la maladie.';
            $messageType = 'error';
        }
        
    } catch (Exception $e) {
        $message = 'Erreur : ' . $e->getMessage();
        $messageType = 'error';
    }
}

// Récupération des informations du patient si ID fourni
$patient = null;
if (isset($_GET['idU'])) {
    $patient_id = $_GET['idU'];
    $stmt = $pdo->prepare("SELECT * FROM patient WHERE Numero_urap = ?");
    $stmt->execute([$patient_id]);
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
            padding: 16px 20px;
            border-radius: 12px;
            position: relative;
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

        .form-grid {
            display: grid;
            grid-template-columns: 1fr;
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
            <?php else: ?>
                <div class="alert alert-info">
                    Veuillez renseigner le motif de consultation selon le sexe du patient. Seuls les champs pertinents doivent être remplis.
                </div>
            <?php endif; ?>

            <form id="histoireForm" method="POST" action="histoire_maladie.php<?php echo isset($_GET['idU']) ? '?idU=' . htmlspecialchars($_GET['idU']) : ''; ?>">
                <?php if (isset($_GET['idU'])): ?>
                    <input type="hidden" name="patient_id" value="<?php echo htmlspecialchars($_GET['idU']); ?>">
                <?php endif; ?>

                <!-- Section Homme -->
                <div class="form-section">
                    <h2 class="section-title homme">
                        <i class="fas fa-mars"></i>
                        Motif de consultation - Homme
                    </h2>
                    <div class="form-grid">
                        <div class="form-field">
                            <label class="form-label">Motif de la consultation :</label>
                            <select class="form-select" name="motif_homme" id="motif_homme" onchange="toggleFields()">
                                <option value="">Choisissez un motif</option>
                                <option value="paternite" <?php echo (isset($_POST['motif_homme']) && $_POST['motif_homme'] == 'paternite') ? 'selected' : ''; ?>>Désir de paternité</option>
                                <option value="dysurie" <?php echo (isset($_POST['motif_homme']) && $_POST['motif_homme'] == 'dysurie') ? 'selected' : ''; ?>>Dysurie</option>
                                <option value="douleur_testiculaire" <?php echo (isset($_POST['motif_homme']) && $_POST['motif_homme'] == 'douleur_testiculaire') ? 'selected' : ''; ?>>Douleur testiculaire</option>
                                <option value="gene_uretral" <?php echo (isset($_POST['motif_homme']) && $_POST['motif_homme'] == 'gene_uretral') ? 'selected' : ''; ?>>Gène urétral</option>
                                <option value="amp" <?php echo (isset($_POST['motif_homme']) && $_POST['motif_homme'] == 'amp') ? 'selected' : ''; ?>>AMP</option>
                                <option value="anomalie_spermogramme" <?php echo (isset($_POST['motif_homme']) && $_POST['motif_homme'] == 'anomalie_spermogramme') ? 'selected' : ''; ?>>Anomalie du spermogramme</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="section-divider"></div>

                <!-- Section Femme -->
                <div class="form-section">
                    <h2 class="section-title femme">
                        <i class="fas fa-venus"></i>
                        Motif de consultation - Femme
                    </h2>
                    <div class="form-grid">
                        <div class="form-field">
                            <label class="form-label">Motif de la consultation :</label>
                            <select class="form-select" name="motif_femme" id="motif_femme" onchange="toggleFields()">
                                <option value="">Choisissez un motif</option>
                                <option value="gynecologique" <?php echo (isset($_POST['motif_femme']) && $_POST['motif_femme'] == 'gynecologique') ? 'selected' : ''; ?>>Gynécologique</option>
                                <option value="consultation_ist" <?php echo (isset($_POST['motif_femme']) && $_POST['motif_femme'] == 'consultation_ist') ? 'selected' : ''; ?>>Consultation IST</option>
                                <option value="agent_contaminateur" <?php echo (isset($_POST['motif_femme']) && $_POST['motif_femme'] == 'agent_contaminateur') ? 'selected' : ''; ?>>Agent contaminateur</option>
                                <option value="desire_grossesse" <?php echo (isset($_POST['motif_femme']) && $_POST['motif_femme'] == 'desire_grossesse') ? 'selected' : ''; ?>>Désir de grossesse</option>
                                <option value="autre" <?php echo (isset($_POST['motif_femme']) && $_POST['motif_femme'] == 'autre') ? 'selected' : ''; ?>>Autre</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="section-divider"></div>

                <!-- Section Signes fonctionnels -->
                <div class="form-section">
                    <h2 class="section-title general">
                        <i class="fas fa-stethoscope"></i>
                        Signes fonctionnels
                    </h2>
                    <div class="form-grid">
                        <div class="form-field">
                            <label class="form-label">Signes fonctionnels :</label>
                            <select class="form-select" name="signes_fonctionnels" id="signes_fonctionnels" onchange="toggleFields()">
                                <option value="">Choisissez un signe</option>
                                <option value="leucorrhees" <?php echo (isset($_POST['signes_fonctionnels']) && $_POST['signes_fonctionnels'] == 'leucorrhees') ? 'selected' : ''; ?>>Leucorrhées</option>
                                <option value="prurit" <?php echo (isset($_POST['signes_fonctionnels']) && $_POST['signes_fonctionnels'] == 'prurit') ? 'selected' : ''; ?>>Prurit</option>
                                <option value="mal_odeur" <?php echo (isset($_POST['signes_fonctionnels']) && $_POST['signes_fonctionnels'] == 'mal_odeur') ? 'selected' : ''; ?>>Mauvaise odeur</option>
                                <option value="douleurs_pelviennes" <?php echo (isset($_POST['signes_fonctionnels']) && $_POST['signes_fonctionnels'] == 'douleurs_pelviennes') ? 'selected' : ''; ?>>Douleurs pelviennes</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="actions">
                    <button type="button" class="btn btn-danger" onclick="resetForm()">
                        <i class="fas fa-times"></i>
                        Annuler
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i>
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleFields() {
            const motifHomme = document.getElementById('motif_homme');
            const motifFemme = document.getElementById('motif_femme');
            const signesFonctionnels = document.getElementById('signes_fonctionnels');

            // Si un motif homme est sélectionné, désactiver femme et signes
            if (motifHomme.value) {
                motifFemme.disabled = true;
                signesFonctionnels.disabled = true;
                motifFemme.value = '';
                signesFonctionnels.value = '';
            } else {
                motifFemme.disabled = false;
                signesFonctionnels.disabled = false;
            }

            // Si un motif femme ou signe fonctionnel est sélectionné, désactiver homme
            if (motifFemme.value || signesFonctionnels.value) {
                motifHomme.disabled = true;
                if (motifFemme.value) {
                    motifHomme.value = '';
                }
            } else {
                motifHomme.disabled = false;
            }
        }

        function resetForm() {
            document.getElementById('histoireForm').reset();
            document.getElementById('motif_homme').disabled = false;
            document.getElementById('motif_femme').disabled = false;
            document.getElementById('signes_fonctionnels').disabled = false;
            
            // Mise à jour visuelle
            toggleFields();
        }

        // Animation d'apparition des éléments
        document.addEventListener('DOMContentLoaded', function() {
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

            // Initialiser l'état des champs
            toggleFields();
        });

        // Gestion de la soumission du formulaire
        document.getElementById('histoireForm').addEventListener('submit', function(e) {
            const motifHomme = document.getElementById('motif_homme').value;
            const motifFemme = document.getElementById('motif_femme').value;
            const signesFonctionnels = document.getElementById('signes_fonctionnels').value;
            
            // Vérifier qu'au moins un champ est rempli
            if (!motifHomme && !motifFemme && !signesFonctionnels) {
                e.preventDefault();
                alert('Veuillez sélectionner au moins un motif de consultation ou un signe fonctionnel.');
                return false;
            }
            
            // Animation de chargement
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalContent = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enregistrement...';
            submitBtn.disabled = true;
        });
    </script>
</body> 
</html> 