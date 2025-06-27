<?php
// Connexion à la base de données
require_once("identifier.php");
require_once("connexion.php");

// Initialiser les variables
$message = '';
$type_echantillon = '';
$date_prelevement = '';
$technicien = '';

// Traiter le formulaire si soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer et nettoyer les données du formulaire
    $type_echantillon = $_POST['type_echantillon'] ?? '';
    $date_prelevement = $_POST['date_prelevement'] ?? '';
    $technicien = $_POST['technicien'] ?? '';

    try {
        // Insertion en base de données avec requête préparée
        $sql = "INSERT INTO echantillon_femelle (type_echantillon, date_prelevement, technicien) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$type_echantillon, $date_prelevement, $technicien]);

        // Redirection après insertion pour éviter la resoumission du formulaire
        header("Location: echantillon_unique.php?success=1");
        exit();

    } catch (PDOException $e) {
        // Gérer les erreurs d'insertion
        $message = "Erreur lors de l'enregistrement : " . $e->getMessage();
    }
}

// Afficher un message de succès après redirection
if (isset($_GET['success']) && $_GET['success'] == 1) {
    $message = "Échantillon enregistré avec succès !";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Échantillon Unique</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #0047ab; /* Cobalt Blue */
            --primary-light: #1e90ff; /* Dodger Blue */
            --primary-dark: #003380; /* Darker Blue */
            --secondary: #f8fafc; /* Light Gray Background */
            --accent: #10b981; /* Green Accent */
            --danger: #ef4444; /* Red for Danger */
            --warning: #f59e0b; /* Amber for Warning */
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

        /* Basic & Typography */
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

        /* Main Container */
        .container {
            max-width: 900px;
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

        /* Header */
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

        /* Form Content */
        .content-area {
            padding: 32px;
            background: white;
        }

        .alert {
            padding: 16px 20px;
            margin-bottom: 24px;
            border-radius: 12px;
            text-align: center;
            font-weight: 500;
            box-shadow: var(--shadow);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }

        .alert-success {
            background: linear-gradient(45deg, var(--accent), #14b8a6);
            color: white;
        }
        
        .alert-danger {
            background: linear-gradient(45deg, var(--danger), #dc2626);
            color: white;
        }

        .echantillon-col {
            background: var(--gray-50);
            border-radius: 16px;
            padding: 24px;
            border: 1px solid var(--gray-200);
            box-shadow: var(--shadow-sm);
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

        /* Buttons */
        .actions {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            padding: 0 32px 32px;
            gap: 16px;
        }
        
        .btn-group {
            display: flex;
            gap: 12px;
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
        }

        .btn-danger {
            background: linear-gradient(135deg, var(--danger) 0%, #dc2626 100%);
            color: white;
            box-shadow: var(--shadow-md);
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        /* Responsive Design */
        @media (max-width: 900px) {
            .actions {
                flex-direction: column;
                align-items: stretch;
            }
            .btn-group {
                width: 100%;
                justify-content: center;
            }
            .btn {
                width: 100%;
            }
        }

        @media (max-width: 600px) {
            body {
                padding: 10px;
            }
            .container {
                border-radius: 16px;
            }
            .content-area {
                padding: 20px;
            }
            .header h1 {
                font-size: 1.8rem;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-flask"></i> Échantillon Unique</h1>
            <p>Enregistrement d'un seul échantillon</p>
        </div>

        <div class="content-area">
            <?php if (!empty($message)): ?>
                <div class="alert <?php echo strpos($message, 'succès') !== false ? 'alert-success' : 'alert-danger'; ?>">
                    <i class="fas fa-info-circle"></i>
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="echantillon-col">
                    <h2><i class="fas fa-microscope"></i> Détails de l'échantillon</h2>
                    <div class="form-grid">
                        <div class="form-field">
                            <label for="type_echantillon" class="form-label">Type de l'échantillon</label>
                            <input type="text" id="type_echantillon" name="type_echantillon" class="form-input" value="<?php echo htmlspecialchars($type_echantillon); ?>" />
                        </div>
                        <div class="form-field">
                            <label for="date_prelevement" class="form-label">Date de prélèvement</label>
                            <input type="date" id="date_prelevement" name="date_prelevement" class="form-input" value="<?php echo htmlspecialchars($date_prelevement); ?>" />
                        </div>
                        <div class="form-field">
                            <label for="technicien" class="form-label">Technicien responsable</label>
                            <input type="text" id="technicien" name="technicien" class="form-input" value="<?php echo htmlspecialchars($technicien); ?>" />
                        </div>
                    </div>
                </div>

                <div class="actions">
                    <div class="btn-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Enregistrer l'échantillon
                        </button>
                    </div>
                    <div class="btn-group">
                        <a href="echantillons.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Retour aux échantillons
                        </a>
                        <button type="button" class="btn btn-danger" onclick="window.history.back()">
                            <i class="fas fa-times-circle"></i> Annuler
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>
</html>