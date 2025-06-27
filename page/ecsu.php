<?php
require_once("identifier.php");
require_once("connexion.php");

// Initialiser les variables pour stocker les données du formulaire
$id = $age = $nom = $prenom = $medecin = '';
$erreur = '';

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $id = $_POST['id'] ?? '';
    $age = $_POST['age'] ?? '';
    $nom = $_POST['nom'] ?? '';
    $prenom = $_POST['prenom'] ?? '';
    $medecin = $_POST['medecin'] ?? '';
    $ecoulement = $_POST['ecoulement'] ?? '';
    $frottis_polynu = $_POST['frottis_polynu'] ?? '';
    $cocci_gram_negatif = $_POST['cocci_gram_negatif'] ?? '';
    $autres_flores = $_POST['autres_flores'] ?? '';
    $culture = $_POST['culture'] ?? '';

    // Vérifier si tous les champs requis sont remplis
    if (empty($id) || empty($age) || empty($nom) || empty($prenom) || empty($medecin)) {
        $erreur = "Veuillez remplir tous les champs obligatoires";
    } else {
        try {
            // Insertion en base
            $sql = "INSERT INTO ecsu (numero_identification,
                age,nom, prenom,medecin,culture,ecoulement, frottis_polynu, cocci_gram_negatif,autres_flores
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $id, $age, $nom, $prenom, $medecin, $culture, $ecoulement,
                $frottis_polynu, $cocci_gram_negatif, $autres_flores
            ]);
            
            // Redirection vers la page echantillon_male.php après insertion réussie
            header("Location: echantillon_male.php");
            exit();
        } catch (Exception $e) {
            $erreur = "Erreur lors de l'enregistrement : " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Examen Cytobactériologique des Sécrétions Urétrales</title>
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

        .error-message {
            background: linear-gradient(135deg, var(--danger) 0%, #dc2626 100%);
            color: white;
            padding: 16px 20px;
            margin-bottom: 24px;
            border-radius: 12px;
            text-align: center;
            font-weight: 500;
            box-shadow: var(--shadow);
            animation: shake 0.5s ease-in-out;
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

        .full-width {
            grid-column: 1 / -1;
        }

        .actions {
            display: flex;
            gap: 16px;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 32px;
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

            .actions {
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
            <h1><i class="fas fa-microscope"></i> Examen Cytobactériologique des Sécrétions Urétrales</h1>
            <p>Formulaire d'examen médical spécialisé</p>
        </div>

        <div class="content-area">
            <?php if (!empty($erreur)): ?>
                <div class="error-message">
                    <i class="fas fa-exclamation-triangle"></i>
                    <?php echo $erreur; ?>
                </div>
            <?php endif; ?>

            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="form-section">
                    <div class="section-title">
                        <i class="fas fa-user"></i>
                        Informations Patient
                    </div>

                    <div class="form-grid">
                        <div class="form-field">
                            <label for="id" class="form-label required">N° Identification</label>
                            <input type="text" id="id" name="id" class="form-input" value="<?php echo htmlspecialchars($id); ?>" required />
                        </div>

                        <div class="form-field">
                            <label for="age" class="form-label required">Âge</label>
                            <input type="text" id="age" name="age" class="form-input" value="<?php echo htmlspecialchars($age); ?>" required />
                        </div>

                        <div class="form-field">
                            <label for="nom" class="form-label required">Nom</label>
                            <input type="text" id="nom" name="nom" class="form-input" value="<?php echo htmlspecialchars($nom); ?>" required />
                        </div>

                        <div class="form-field">
                            <label for="prenom" class="form-label required">Prénom</label>
                            <input type="text" id="prenom" name="prenom" class="form-input" value="<?php echo htmlspecialchars($prenom); ?>" required />
                        </div>

                        <div class="form-field full-width">
                            <label for="medecin" class="form-label required">Médecin prescripteur</label>
                            <input type="text" id="medecin" name="medecin" class="form-input" value="<?php echo htmlspecialchars($medecin); ?>" required />
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="section-title">
                        <i class="fas fa-eye"></i>
                        Examens Macroscopiques
                    </div>

                    <div class="form-field">
                        <label for="ecoulement" class="form-label">Écoulement</label>
                        <select id="ecoulement" name="ecoulement" class="form-select">
                            <option value="absence">Absence</option>
                            <option value="presence">Présence</option>
                        </select>
                    </div>
                </div>

                <div class="form-section">
                    <div class="section-title">
                        <i class="fas fa-search"></i>
                        Examens Microscopiques
                    </div>

                    <div class="form-field">
                        <label for="frottis_polynu" class="form-label">Sécrétions urétrales frottis coloré au Gram - polynucléaire</label>
                        <select id="frottis_polynu" name="frottis_polynu" class="form-select">
                            <option value="<5/champs">&lt; 5/champs</option>
                            <option value=">5/champs">&gt; 5/champs</option>
                        </select>
                    </div>

                    <div class="form-field">
                        <label for="cocci_gram_negatif" class="form-label">Sécrétions urétrales frottis coloré au Gram - cocci Gram négatif</label>
                        <select id="cocci_gram_negatif" name="cocci_gram_negatif" class="form-select">
                            <option value="absence">Absence</option>
                            <option value="presence">Présence</option>
                        </select>
                    </div>

                    <div class="form-field">
                        <label for="autres_flores" class="form-label">Sécrétions urétrales frottis coloré au Gram - Autres flores</label>
                        <select id="autres_flores" name="autres_flores" class="form-select">
                            <option value="absence">Absence</option>
                            <option value="presence">Présence</option>
                        </select>
                    </div>
                </div>

                <div class="form-section">
                    <div class="section-title">
                        <i class="fas fa-flask"></i>
                        Culture
                    </div>

                    <div class="form-field">
                        <label for="culture" class="form-label">Sécrétion urétrale</label>
                        <select id="culture" name="culture" class="form-select">
                            <option value="negatif">Négatif</option>
                            <option value="positif">Positif</option>
                        </select>
                    </div>
                </div>

                <div class="actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Enregistrer
                    </button>
                    <a href="echantillon_male.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                        Retour
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>