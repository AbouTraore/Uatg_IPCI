<?php
require_once("identifier.php");
require_once("connexion.php");

// Initialiser les variables
$message = '';
$message_type = '';
$numero_urap = $_GET['idU'] ?? $_GET['urap'] ?? '';

// Récupérer les informations du patient si URAP fourni
$patient = null;
if ($numero_urap) {
    $stmt = $pdo->prepare("SELECT * FROM patient WHERE Numero_urap = ?");
    $stmt->execute([$numero_urap]);
    $patient = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Traitement AJAX pour enregistrer la visite et rediriger vers l'examen
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'save_and_redirect') {
    
    // Récupérer les données
    $numero_urap = htmlspecialchars($_POST['numero_urap'] ?? '');
    $date_visite = htmlspecialchars($_POST['date'] ?? '');
    $heure_visite = htmlspecialchars($_POST['heure'] ?? '');
    $motif_visite = htmlspecialchars($_POST['motif'] ?? '');
    $structure_provenance = htmlspecialchars($_POST['structure'] ?? '');
    $prescripteur_nom = htmlspecialchars($_POST['prescripteur'] ?? '');
    $type_examen = htmlspecialchars($_POST['type_examen'] ?? '');
    
    // Validation
    if (empty($numero_urap) || empty($date_visite) || empty($heure_visite) || empty($motif_visite)) {
        echo json_encode([
            'success' => false, 
            'message' => 'Tous les champs obligatoires doivent être remplis.'
        ]);
        exit;
    }
    
    try {
        $pdo->beginTransaction();
        
        // 1. Enregistrer ou récupérer le prescripteur
        $id_prescripteur = null;
        if (!empty($prescripteur_nom)) {
            // Vérifier si le prescripteur existe déjà
            $stmt = $pdo->prepare("SELECT ID_prescripteur FROM prescriteur WHERE Nom = ? OR CONCAT(Nom, ' ', Prenom) = ?");
            $stmt->execute([$prescripteur_nom, $prescripteur_nom]);
            $prescripteur_existant = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($prescripteur_existant) {
                $id_prescripteur = $prescripteur_existant['ID_prescripteur'];
            } else {
                // Créer un nouveau prescripteur
                $parts = explode(' ', $prescripteur_nom, 2);
                $nom = $parts[0];
                $prenom = isset($parts[1]) ? $parts[1] : '';
                
                $stmt = $pdo->prepare("INSERT INTO prescriteur (Nom, Prenom, Structure_provenance) VALUES (?, ?, ?)");
                $stmt->execute([$nom, $prenom, $structure_provenance]);
                $id_prescripteur = $pdo->lastInsertId();
            }
        }
        
        // 2. Générer un nouvel ID de visite
        $stmt = $pdo->prepare("SELECT MAX(id_visite) as max_id FROM visite");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $nouvel_id_visite = ($result['max_id'] ?? 0) + 1;
        
        // 3. Enregistrer la visite
        $stmt = $pdo->prepare("INSERT INTO visite (id_visite, `date_visite`, `Heure visite`, `Motif visite`, Numero_urap, ID_prescripteur, Structure_provenance) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $nouvel_id_visite,
            $date_visite,
            $heure_visite,
            $motif_visite,
            $numero_urap,
            $id_prescripteur,
            $structure_provenance
        ]);
        
        $pdo->commit();
        
        // 4. Déterminer l'URL de redirection selon le type d'examen
        $redirect_urls = [
            'ecs' => "ecs.php?numero_urap=$numero_urap&id_visite=$nouvel_id_visite&medecin=" . urlencode($prescripteur_nom),
            'vag' => "EXA_CYTO_SEC_VAG.php?numero_urap=$numero_urap&id_visite=$nouvel_id_visite&medecin=" . urlencode($prescripteur_nom),
            'ecsu' => "ecsu.php?numero_urap=$numero_urap&id_visite=$nouvel_id_visite&medecin=" . urlencode($prescripteur_nom)
        ];
        
        $redirect_url = $redirect_urls[$type_examen] ?? '#';
        
        echo json_encode([
            'success' => true,
            'message' => 'Visite enregistrée avec succès !',
            'redirect_url' => $redirect_url,
            'id_visite' => $nouvel_id_visite
        ]);
        
    } catch (PDOException $e) {
        $pdo->rollBack();
        echo json_encode([
            'success' => false,
            'message' => 'Erreur lors de l\'enregistrement : ' . $e->getMessage()
        ]);
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Nouvelle Visite - UATG</title>
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
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            width: 100%;
            max-width: 1000px;
            margin: auto;
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
            font-size: 2rem;
            font-weight: 700;
            letter-spacing: -0.025em;
            position: relative;
            z-index: 1;
        }

        .header p {
            font-size: 1rem;
            opacity: 0.9;
            margin-top: 8px;
            position: relative;
            z-index: 1;
        }

        .content-area {
            padding: 32px;
            background: white;
        }

        .alert {
            background: linear-gradient(135deg, var(--accent) 0%, #34d399 100%);
            color: white;
            padding: 16px 20px;
            margin-bottom: 24px;
            border-radius: 12px;
            text-align: center;
            font-weight: 500;
            box-shadow: var(--shadow);
            animation: fadeIn 0.5s ease-in-out;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .alert-danger {
            background: linear-gradient(135deg, var(--danger) 0%, #dc2626 100%);
            animation: shake 0.5s ease-in-out;
        }

        .alert-info {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        .patient-info {
            background: var(--gray-50);
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 24px;
            border-left: 4px solid var(--primary);
        }

        .patient-details {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 16px;
        }

        .patient-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
        }

        .patient-name {
            flex: 1;
        }

        .patient-name h3 {
            color: var(--primary);
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .patient-meta {
            color: var(--gray-600);
            font-size: 0.9rem;
        }

        .form-section {
            margin-bottom: 32px;
            background: var(--gray-50);
            border-radius: 16px;
            padding: 24px;
            border: 1px solid var(--gray-200);
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--gray-800);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
            padding-bottom: 12px;
            border-bottom: 2px solid var(--gray-200);
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
            margin-bottom: 8px;
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
            background: var(--gray-100);
            color: var(--gray-500);
        }

        .full-width {
            grid-column: 1 / -1;
        }

        .examens-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .examen-card {
            background-color: white;
            border: 2px solid var(--gray-200);
            border-radius: 16px;
            padding: 24px;
            text-align: center;
            box-shadow: var(--shadow-sm);
            transition: all 0.3s ease;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
            position: relative;
        }
        
        .examen-card:hover:not(.disabled) {
            transform: translateY(-5px) scale(1.02);
            box-shadow: var(--shadow-md);
            border-color: var(--primary-light);
        }
        
        .examen-card.disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        .examen-card i {
            font-size: 2.5rem;
            color: var(--primary);
            transition: color 0.3s ease;
        }
        
        .examen-card:hover:not(.disabled) i {
            color: var(--primary-light);
        }
        
        .examen-title {
            font-weight: 600;
            font-size: 1em;
            letter-spacing: -0.02em;
            color: var(--gray-800);
        }

        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .loading-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        .loading-spinner {
            background: white;
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            box-shadow: var(--shadow-xl);
        }

        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid var(--gray-200);
            border-top: 4px solid var(--primary);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 15px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .actions-bar {
            background: var(--gray-50);
            padding: 20px 32px;
            display: flex;
            justify-content: center;
            gap: 16px;
            border-top: 1px solid var(--gray-200);
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

        .btn-secondary {
            background: white;
            color: var(--gray-700);
            border: 1px solid var(--gray-300);
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        }

        @media (max-width: 768px) {
            body {
                padding: 10px;
            }

            .header {
                padding: 24px 20px;
            }

            .header h1 {
                font-size: 1.5rem;
            }

            .content-area {
                padding: 20px;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-section {
                padding: 16px;
            }

            .examens-section {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner">
            <div class="spinner"></div>
            <p>Enregistrement de la visite...</p>
        </div>
    </div>

    <div class="container">
        <div class="header">
            <h1><i class="fas fa-calendar-plus"></i> Nouvelle Visite</h1>
            <p>Enregistrez une nouvelle visite et choisissez le type d'examen</p>
        </div>
        
        <div class="content-area">
            <?php if (!$numero_urap): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    Aucun numéro URAP fourni. Veuillez sélectionner un patient.
                </div>
            <?php endif; ?>

            <?php if ($patient): ?>
                <div class="patient-info">
                    <div class="patient-details">
                        <div class="patient-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="patient-name">
                            <h3><?= htmlspecialchars($patient['Nom_patient'] . ' ' . $patient['Prenom_patient']) ?></h3>
                            <div class="patient-meta">
                                N°URAP: <?= htmlspecialchars($patient['Numero_urap']) ?> | 
                                <?= htmlspecialchars($patient['Age']) ?> ans | 
                                <?= htmlspecialchars($patient['Sexe_patient']) ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <form id="visiteForm">
                <input type="hidden" id="numero_urap" name="numero_urap" value="<?= htmlspecialchars($numero_urap) ?>">
                
                <div class="form-section">
                    <div class="section-title">
                        <i class="fas fa-info-circle"></i>
                        Informations de la Visite
                    </div>
                    <div class="form-grid">
                        <div class="form-field">
                            <label for="date" class="form-label required">Date</label>
                            <input type="date" id="date" name="date" class="form-input" value="<?= date('Y-m-d') ?>" required />
                        </div>
                        <div class="form-field">
                            <label for="heure" class="form-label required">Heure</label>
                            <input type="time" id="heure" name="heure" class="form-input" value="<?= date('H:i') ?>" required />
                        </div>
                        <div class="form-field">
                            <label for="prescripteur" class="form-label required">Prescripteur</label>
                            <input type="text" id="prescripteur" name="prescripteur" class="form-input" placeholder="Nom du médecin prescripteur" required />
                        </div>
                        <div class="form-field">
                            <label for="structure" class="form-label">Structure de provenance</label>
                            <input type="text" id="structure" name="structure" class="form-input" placeholder="Nom de la structure" />
                        </div>
                        <div class="form-field full-width">
                            <label for="motif" class="form-label required">Motif de la visite</label>
                            <input type="text" id="motif" name="motif" class="form-input" placeholder="Ex: Consultation, contrôle, dépistage..." required />
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="section-title">
                        <i class="fas fa-vial"></i>
                        Choisir le Type d'Examen
                    </div>
                    <p style="color: var(--gray-600); margin-bottom: 20px; font-size: 0.9rem;">
                        <i class="fas fa-info-circle"></i> 
                        Remplissez d'abord les informations de visite, puis cliquez sur le type d'examen souhaité.
                    </p>
                    <div class="examens-section">
                        <div class="examen-card disabled" data-type="ecs">
                            <i class="fas fa-microscope"></i>
                            <div class="examen-title">EXAMEN CYTOBACTÉRIOLOGIQUE DU SPERME</div>
                        </div>
                        <div class="examen-card disabled" data-type="vag">
                            <i class="fas fa-bacteria"></i>
                            <div class="examen-title">EXAMEN CYTOBACTÉRIOLOGIQUE SÉCRÉTION VAGINALE</div>
                        </div>
                        <div class="examen-card disabled" data-type="ecsu">
                            <i class="fas fa-syringe"></i>
                            <div class="examen-title">EXAMEN CYTOBACTÉRIOLOGIQUE SÉCRÉTION URÉTRALE</div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="actions-bar">
            <a href="liste_dossiers.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour à la liste
            </a>
        </div>
    </div>

    <script>
        // Validation du formulaire et activation des examens
        function validateForm() {
            const requiredFields = ['numero_urap', 'date', 'heure', 'prescripteur', 'motif'];
            let isValid = true;
            
            requiredFields.forEach(fieldName => {
                const field = document.getElementById(fieldName);
                if (!field.value.trim()) {
                    isValid = false;
                }
            });
            
            // Activer/désactiver les cartes d'examen
            const examCards = document.querySelectorAll('.examen-card');
            examCards.forEach(card => {
                if (isValid) {
                    card.classList.remove('disabled');
                } else {
                    card.classList.add('disabled');
                }
            });
            
            return isValid;
        }

        // Fonction pour enregistrer la visite et rediriger
        async function saveVisitAndRedirect(typeExamen) {
            if (!validateForm()) {
                alert('Veuillez remplir tous les champs obligatoires avant de choisir un examen.');
                return;
            }

            // Afficher le loading
            document.getElementById('loadingOverlay').classList.add('show');

            const formData = new FormData();
            formData.append('action', 'save_and_redirect');
            formData.append('numero_urap', document.getElementById('numero_urap').value);
            formData.append('date', document.getElementById('date').value);
            formData.append('heure', document.getElementById('heure').value);
            formData.append('prescripteur', document.getElementById('prescripteur').value);
            formData.append('structure', document.getElementById('structure').value);
            formData.append('motif', document.getElementById('motif').value);
            formData.append('type_examen', typeExamen);

            try {
                const response = await fetch(window.location.href, {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    // Rediriger vers la page d'examen
                    window.location.href = result.redirect_url;
                } else {
                    document.getElementById('loadingOverlay').classList.remove('show');
                    alert('Erreur: ' + result.message);
                }
            } catch (error) {
                document.getElementById('loadingOverlay').classList.remove('show');
                alert('Erreur de communication avec le serveur.');
                console.error('Erreur:', error);
            }
        }

        // Écouter les changements dans les champs du formulaire
        document.addEventListener('DOMContentLoaded', function() {
            const formInputs = document.querySelectorAll('.form-input');
            formInputs.forEach(input => {
                input.addEventListener('input', validateForm);
                input.addEventListener('change', validateForm);
            });

            // Gérer les clics sur les cartes d'examen
            const examCards = document.querySelectorAll('.examen-card');
            examCards.forEach(card => {
                card.addEventListener('click', function() {
                    if (!this.classList.contains('disabled')) {
                        const typeExamen = this.getAttribute('data-type');
                        saveVisitAndRedirect(typeExamen);
                    }
                });
            });

            // Validation initiale
            validateForm();
        });
    </script>
</body>
</html>