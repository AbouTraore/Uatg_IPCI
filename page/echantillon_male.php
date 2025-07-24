<?php
// Connexion à la base de données
require_once("identifier.php");
require_once("connexion.php");

// Initialiser les variables
$message = '';
$messageType = '';
$type_echantillon1 = '';
$date_prelevement1 = '';
$technicien1 = '';
$type_echantillon2 = '';
$date_prelevement2 = '';
$technicien2 = '';

// Récupérer le numéro URAP si présent dans l'URL
$numero_urap = $_GET['urap'] ?? '';

// Traiter le formulaire si soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $numero_urap = $_POST['numero_urap'] ?? '';
    $type_echantillon1 = trim($_POST['type_echantillon1'] ?? '');
    $date_prelevement1 = $_POST['date_prelevement1'] ?? '';
    $technicien1 = trim($_POST['technicien1'] ?? '');
    $type_echantillon2 = trim($_POST['type_echantillon2'] ?? '');
    $date_prelevement2 = $_POST['date_prelevement2'] ?? '';
    $technicien2 = trim($_POST['technicien2'] ?? '');

    // Validation des données
    if (empty($numero_urap)) {
        $message = "Le numéro URAP est obligatoire.";
        $messageType = 'error';
    } elseif (empty($type_echantillon1) && empty($type_echantillon2)) {
        $message = "Au moins un échantillon doit être renseigné.";
        $messageType = 'error';
    } else {
        try {
            // Vérifier que le patient existe
            $check_patient = $pdo->prepare("SELECT COUNT(*) FROM patient WHERE Numero_urap = ?");
            $check_patient->execute([$numero_urap]);
            
            if ($check_patient->fetchColumn() == 0) {
                $message = "Patient non trouvé avec ce numéro URAP.";
                $messageType = 'error';
            } else {
                // Insertion en base avec le numéro URAP
                $sql = "INSERT INTO echantillon_male 
                    (numero_urap, type_echantillon1, date_prelevement1, technicien1, type_echantillon2, date_prelevement2, technicien2)
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                $result = $stmt->execute([
                    $numero_urap, $type_echantillon1, $date_prelevement1, $technicien1,
                    $type_echantillon2, $date_prelevement2, $technicien2
                ]);
                
                if ($result) {
                    // Redirection vers la page de visite du patient après validation
                    header('Location: visite_patient.php?urap=' . urlencode($numero_urap));
                    exit;
                } else {
                    $message = "Erreur lors de l'enregistrement des échantillons.";
                    $messageType = 'error';
                }
            }

        } catch (PDOException $e) {
            $message = "Erreur lors de l'enregistrement : " . $e->getMessage();
            $messageType = 'error';
            error_log("Erreur échantillons: " . $e->getMessage());
        }
    }
}

// Récupération des informations du patient si URAP fourni
$patient = null;
if (!empty($numero_urap)) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM patient WHERE Numero_urap = ?");
        $stmt->execute([$numero_urap]);
        $patient = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Erreur récupération patient: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Gestion des Échantillons - UATG</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
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
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
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
            animation: fadeIn 0.6s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
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
            font-size: 2.2rem;
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

        .content-area {
            padding: 32px;
            background: white;
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

        .urap-section {
            background: linear-gradient(135deg, var(--accent) 0%, #059669 100%);
            color: white;
            padding: 20px 24px;
            margin-bottom: 32px;
            border-radius: 12px;
            text-align: center;
            box-shadow: var(--shadow-md);
        }

        .urap-section h2 {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .urap-input-container {
            max-width: 400px;
            margin: 16px auto 0;
        }

        .urap-input {
            width: 100%;
            padding: 14px 18px;
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

        .alert {
            padding: 16px 20px;
            margin-bottom: 24px;
            border-radius: 12px;
            font-weight: 500;
            box-shadow: var(--shadow);
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

        .form-section {
            display: flex;
            flex-wrap: wrap;
            gap: 32px;
            margin-bottom: 32px;
        }

        .echantillon-col {
            flex: 1;
            min-width: 400px;
            background: var(--gray-50);
            border-radius: 16px;
            padding: 24px;
            border: 1px solid var(--gray-200);
            box-shadow: var(--shadow-sm);
            transition: all 0.3s ease;
        }

        .echantillon-col:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .echantillon-col h2 {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 10px;
            padding-bottom: 12px;
            border-bottom: 2px solid var(--gray-200);
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
            margin-bottom: 8px;
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

        .actions {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            padding: 0 32px 32px;
            gap: 16px;
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
            min-width: 180px;
            justify-content: center;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: white;
            box-shadow: var(--shadow-md);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }
        
        .btn-secondary {
            background: white;
            color: var(--gray-700);
            border: 2px solid var(--gray-200);
            box-shadow: var(--shadow-sm);
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
            box-shadow: var(--shadow-md);
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        @media (max-width: 900px) {
            .echantillon-col {
                min-width: 100%;
            }
            .actions {
                flex-direction: column;
                align-items: stretch;
            }
            .btn {
                width: 100%;
            }
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="header">
            <a href="javascript:history.back()" class="btn-retour">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
            <h1><i class="fas fa-vial"></i> Gestion des Échantillons</h1>
            <p>Enregistrement de deux échantillons ou plus</p>
        </div>

        <div class="content-area">
            <!-- Section URAP -->
            <div class="urap-section">
                <h2><i class="fas fa-id-card"></i> Numéro URAP du Patient</h2>
                <div class="urap-input-container">
                    <input type="text" class="urap-input" id="numero_urap_display" 
                           value="<?php echo htmlspecialchars($numero_urap); ?>" 
                           placeholder="Saisir le numéro URAP du patient" 
                           onchange="updateMainUrapField(this.value)">
                </div>
            </div>

            <!-- Informations du patient si disponible -->
            <?php if ($patient): ?>
                <div class="patient-info">
                    <h3><i class="fas fa-user"></i> Patient sélectionné</h3>
                    <p><strong><?php echo htmlspecialchars($patient['Nom_patient'] . ' ' . $patient['Prenom_patient']); ?></strong> - N° URAP: <?php echo htmlspecialchars($patient['Numero_urap']); ?></p>
                </div>
            <?php endif; ?>

            <?php if (!empty($message)): ?>
                <div class="alert alert-<?php echo $messageType; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" id="echantillonForm">
                <!-- Champ URAP caché -->
                <input type="hidden" id="numero_urap" name="numero_urap" value="<?php echo htmlspecialchars($numero_urap); ?>" required>

                <div class="form-section">
                    <div class="echantillon-col">
                        <h2><i class="fas fa-capsules"></i> Échantillon 1</h2>
                        <div class="form-grid">
                            <div class="form-field">
                                <label for="type_echantillon1" class="form-label">Type de l'échantillon</label>
                                <input type="text" id="type_echantillon1" name="type_echantillon1" class="form-input" 
                                       value="<?php echo htmlspecialchars($type_echantillon1); ?>" 
                                       placeholder="Ex: Urine, Sang, Salive..." />
                            </div>
                            <div class="form-field">
                                <label for="date_prelevement1" class="form-label">Date de prélèvement</label>
                                <input type="date" id="date_prelevement1" name="date_prelevement1" class="form-input" 
                                       value="<?php echo htmlspecialchars($date_prelevement1); ?>" />
                            </div>
                            <div class="form-field">
                                <label for="technicien1" class="form-label">Technicien responsable</label>
                                <input type="text" id="technicien1" name="technicien1" class="form-input" 
                                       value="<?php echo htmlspecialchars($technicien1); ?>" 
                                       placeholder="Nom du technicien" />
                            </div>
                        </div>
                    </div>

                    <div class="echantillon-col">
                        <h2><i class="fas fa-capsules"></i> Échantillon 2</h2>
                        <div class="form-grid">
                            <div class="form-field">
                                <label for="type_echantillon2" class="form-label">Type de l'échantillon</label>
                                <input type="text" id="type_echantillon2" name="type_echantillon2" class="form-input" 
                                       value="<?php echo htmlspecialchars($type_echantillon2); ?>" 
                                       placeholder="Ex: Urine, Sang, Salive..." />
                            </div>
                            <div class="form-field">
                                <label for="date_prelevement2" class="form-label">Date de prélèvement</label>
                                <input type="date" id="date_prelevement2" name="date_prelevement2" class="form-input" 
                                       value="<?php echo htmlspecialchars($date_prelevement2); ?>" />
                            </div>
                            <div class="form-field">
                                <label for="technicien2" class="form-label">Technicien responsable</label>
                                <input type="text" id="technicien2" name="technicien2" class="form-input" 
                                       value="<?php echo htmlspecialchars($technicien2); ?>" 
                                       placeholder="Nom du technicien" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="actions">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Enregistrer et voir le rapport
                    </button>
                    <a href="echantillon_unique.php" class="btn btn-secondary">
                        <i class="fas fa-flask"></i> Échantillon unique
                    </a>
                    <button type="button" class="btn btn-secondary" onclick="resetForm()">
                        <i class="fas fa-undo"></i> Réinitialiser
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Synchroniser les champs URAP
        function updateMainUrapField(value) {
            document.getElementById('numero_urap').value = value;
        }

        // Fonction pour réinitialiser le formulaire
        function resetForm() {
            if (confirm('Êtes-vous sûr de vouloir réinitialiser le formulaire ?')) {
                document.getElementById('echantillonForm').reset();
                document.getElementById('numero_urap_display').value = '';
            }
        }

        // Validation du formulaire avant soumission
        document.getElementById('echantillonForm').addEventListener('submit', function(e) {
            const numeroUrap = document.getElementById('numero_urap').value.trim();
            const type1 = document.getElementById('type_echantillon1').value.trim();
            const type2 = document.getElementById('type_echantillon2').value.trim();
            
            if (!numeroUrap) {
                e.preventDefault();
                alert('Veuillez saisir le numéro URAP du patient.');
                document.getElementById('numero_urap_display').focus();
                return;
            }
            
            if (!type1 && !type2) {
                e.preventDefault();
                alert('Veuillez renseigner au moins un type d\'échantillon.');
                return;
            }

            // Animation de chargement
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enregistrement...';
            submitBtn.disabled = true;
        });

        // Initialisation
        document.addEventListener('DOMContentLoaded', function() {
            // Si un URAP est dans l'URL, le mettre dans le champ visible
            const urlParams = new URLSearchParams(window.location.search);
            const urapFromUrl = urlParams.get('urap');
            if (urapFromUrl) {
                document.getElementById('numero_urap_display').value = urapFromUrl;
                document.getElementById('numero_urap').value = urapFromUrl;
            }
        });
    </script>
</body>

</html>