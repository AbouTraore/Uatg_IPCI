<?php
// On inclut les fichiers de configuration nécessaires
// Assurez-vous que ces fichiers existent et sont correctement configurés
require_once("identifier.php"); // Gère probablement l'authentification et les sessions
require_once("connexion.php"); // Gère la connexion à la base de données (PDO)

// Initialiser les variables du formulaire avec des valeurs par défaut
$message = '';
$date_visite = date('Y-m-d'); // Date actuelle par défaut
$prescripteur = '';
$structure = '';
$heure = date('H:i'); // Heure actuelle par défaut
$motif = '';
$message_type = ''; // 'success' ou 'danger'

// Traiter le formulaire si soumis via la méthode POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer et sécuriser les données du formulaire
    $date_visite = htmlspecialchars($_POST['date'] ?? '');
    $prescripteur = htmlspecialchars($_POST['prescripteur'] ?? '');
    $structure = htmlspecialchars($_POST['structure'] ?? '');
    $heure = htmlspecialchars($_POST['heure'] ?? '');
    $motif = htmlspecialchars($_POST['motif'] ?? '');

    // Validation simple des champs requis
    if (empty($date_visite) || empty($prescripteur) || empty($structure) || empty($heure) || empty($motif)) {
        $message = "Tous les champs du formulaire de visite sont obligatoires. Veuillez remplir le formulaire.";
        $message_type = 'danger';
    } else {
        try {
            // Préparer la requête d'insertion des données
            // Les ? sont des "placeholders" pour les requêtes préparées
            $sql = "INSERT INTO visite (date, prescripteur, structure, heure, motif) VALUES (?, ?, ?, ?, ?)";
            
            // Préparer le statement
            $stmt = $pdo->prepare($sql);
            
            // Exécuter la requête avec les données du formulaire
            // L'ordre des valeurs dans le tableau doit correspondre à l'ordre des ? dans la requête
            $stmt->execute([$date_visite, $prescripteur, $structure, $heure, $motif]);
            
            // Message de succès après insertion
            $message = "Le formulaire de visite a été enregistré avec succès !";
            $message_type = 'success';
            
            // Réinitialiser les variables pour vider le formulaire après un succès
            $date_visite = '';
            $prescripteur = '';
            $structure = '';
            $heure = '';
            $motif = '';

        } catch (PDOException $e) {
            // Gérer les erreurs de base de données
            $message = "Une erreur est survenue lors de l'enregistrement : " . $e->getMessage();
            $message_type = 'danger';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Formulaire Visite</title>
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

        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        .form-section {
            margin-bottom: 32px;
            background: var(--gray-50);
            border-radius: 16px;
            padding: 24px;
            border: 1px solid var(--gray-200);
        }
        
        .examens-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .examen-card {
            background: white;
            border: 2px solid var(--gray-200);
            border-radius: 12px;
            padding: 24px;
            text-decoration: none;
            color: var(--gray-800);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            gap: 16px;
        }
        
        .examen-card:hover {
            border-color: var(--primary);
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }
        
        .examen-card i {
            font-size: 2.5rem;
            color: var(--primary);
        }
        
        .examen-card .examen-title {
            font-weight: 600;
            font-size: 1rem;
            line-height: 1.4;
        }
        
        .examen-card:not([style*="opacity: 0.5"]) {
            cursor: pointer;
        }
        
        .examen-card[style*="opacity: 0.5"] {
            cursor: not-allowed;
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

        .full-width {
            grid-column: 1 / -1;
        }
        
        .examens-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
        
        .examen-card {
            background-color: white;
            border: 1px solid var(--gray-200);
            border-radius: 16px;
            padding: 24px;
            text-align: center;
            box-shadow: var(--shadow-sm);
            transition: all 0.3s ease;
            text-decoration: none;
            color: var(--gray-800);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
        }
        
        .examen-card:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: var(--shadow-md);
            border-color: var(--primary-light);
        }
        
        .examen-card i {
            font-size: 2.5rem;
            color: var(--primary);
            transition: color 0.3s ease;
        }
        
        .examen-card:hover i {
            color: var(--primary-light);
        }
        
        .examen-title {
            font-weight: 600;
            font-size: 1em;
            letter-spacing: -0.02em;
        }

        .buttons-container {
            display: flex;
            flex-direction: column;
            gap: 20px;
            margin-top: 32px;
        }

        .btn-group {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
            justify-content: center;
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
        
        .btn-danger {
            background: var(--danger);
            color: white;
            box-shadow: var(--shadow);
        }
        
        .btn-danger:hover {
            background: #d42d2d;
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
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

            .buttons-container {
                gap: 16px;
            }
            
            .btn-group {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }

            .form-section {
                padding: 16px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-calendar-check"></i> Formulaire de Visite</h1>
            <p>Enregistrez les informations d'une nouvelle visite de patient</p>
        </div>
        
        <div class="content-area">
            <?php if (!empty($message)): ?>
                <div class="alert alert-<?php echo $message_type; ?>">
                    <i class="fas fa-<?php echo ($message_type == 'success' ? 'check-circle' : 'exclamation-triangle'); ?>"></i>
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="form-section">
                    <div class="section-title">
                        <i class="fas fa-info-circle"></i>
                        Informations de la Visite
                    </div>
                    <div class="form-grid">
                        <div class="form-field">
                            <label for="date" class="form-label required">Date</label>
                            <input type="date" id="date" name="date" class="form-input" value="<?php echo htmlspecialchars($date_visite); ?>" required />
                        </div>
                        <div class="form-field">
                            <label for="heure" class="form-label required">Heure</label>
                            <input type="time" id="heure" name="heure" class="form-input" value="<?php echo htmlspecialchars($heure); ?>" required />
                        </div>
                        <div class="form-field">
                            <label for="prescripteur" class="form-label required">Prescripteur</label>
                            <input type="text" id="prescripteur" name="prescripteur" class="form-input" value="<?php echo htmlspecialchars($prescripteur); ?>" placeholder="Nom du prescripteur" required />
                        </div>
                        <div class="form-field">
                            <label for="structure" class="form-label required">Structure de provenance</label>
                            <input type="text" id="structure" name="structure" class="form-input" value="<?php echo htmlspecialchars($structure); ?>" placeholder="Nom de la structure" required />
                        </div>
                        <div class="form-field full-width">
                            <label for="motif" class="form-label required">Motif de la visite</label>
                            <input type="text" id="motif" name="motif" class="form-input" value="<?php echo htmlspecialchars($motif); ?>" placeholder="Ex: Consultation, contrôle, etc." required />
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="section-title">
                        <i class="fas fa-vial"></i>
                        Types d'Examens
                    </div>
                    <div class="examens-section">
                        <a href="ecs.php" class="examen-card" id="btnEcs">
                            <i class="fas fa-microscope"></i>
                            <div class="examen-title">EXAMEN CYTOBACTERIOLOGIQUE DU SPERME</div>
                        </a>
                        <a href="EXA_CYTO_SEC_VAG.php" class="examen-card" id="btnSecVag">
                            <i class="fas fa-bacteria"></i>
                            <div class="examen-title">EXAMEN CYTOBACTERIOLOGIQUE SECRETION VAGINALE</div>
                        </a>
                        <a href="ecsu.php" class="examen-card" id="btnEcsu">
                            <i class="fas fa-syringe"></i>
                            <div class="examen-title">EXAMEN CYTOBACTERIOLOGIQUE SECRETION URETRALE</div>
                        </a>
                    </div>
                </div>
                
                <div class="buttons-container">
                    <div class="btn-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Enregistrer
                        </button>
                        <a href="echantillons.php" class="btn btn-secondary">
                            <i class="fas fa-flask"></i> Échantillons
                        </a>
                    </div>
                    <div class="btn-group">
                        <button type="button" class="btn btn-secondary">
                            <i class="fas fa-file-pdf"></i> Rapport
                        </button>
                        <button type="button" class="btn btn-secondary">
                            <i class="fas fa-print"></i> Imprimer
                        </button>
                        <a href="javascript:history.back()" class="btn btn-danger">
                            <i class="fas fa-arrow-left"></i> Retour
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Fonction pour mettre à jour les liens vers les examens avec le nom du médecin prescripteur
        function updateExamLinks() {
            const prescripteur = document.getElementById('prescripteur').value.trim();
            
            if (prescripteur) {
                // Mettre à jour les liens avec le nom du médecin prescripteur
                document.getElementById('btnEcs').href = `ecs.php?medecin=${encodeURIComponent(prescripteur)}`;
                document.getElementById('btnSecVag').href = `EXA_CYTO_SEC_VAG.php?medecin=${encodeURIComponent(prescripteur)}`;
                document.getElementById('btnEcsu').href = `ecsu.php?medecin=${encodeURIComponent(prescripteur)}`;
                
                // Activer les liens
                document.querySelectorAll('.examen-card').forEach(card => {
                    card.style.opacity = '1';
                    card.style.pointerEvents = 'auto';
                });
            } else {
                // Désactiver les liens si pas de médecin prescripteur
                document.querySelectorAll('.examen-card').forEach(card => {
                    card.style.opacity = '0.5';
                    card.style.pointerEvents = 'none';
                });
            }
        }
        
        // Écouter les changements dans le champ prescripteur
        document.getElementById('prescripteur').addEventListener('input', updateExamLinks);
        
        // Initialiser les liens au chargement de la page
        document.addEventListener('DOMContentLoaded', function() {
            updateExamLinks();
        });
    </script>
</body>
</html>