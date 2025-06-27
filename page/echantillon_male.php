<?php
// Connexion à la base de données
require_once("identifier.php");
require_once("connexion.php");

// Initialiser les variables
$message = '';
$type_echantillon1 = '';
$date_prelevement1 = '';
$technicien1 = '';
$type_echantillon2 = '';
$date_prelevement2 = '';
$technicien2 = '';

// Traiter le formulaire si soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $type_echantillon1 = $_POST['type_echantillon1'] ?? '';
    $date_prelevement1 = $_POST['date_prelevement1'] ?? '';
    $technicien1 = $_POST['technicien1'] ?? '';
    $type_echantillon2 = $_POST['type_echantillon2'] ?? '';
    $date_prelevement2 = $_POST['date_prelevement2'] ?? '';
    $technicien2 = $_POST['technicien2'] ?? '';

    try {
        // Insertion en base
        $sql = "INSERT INTO echantillon_male 
            (type_echantillon1, date_prelevement1, technicien1, type_echantillon2, date_prelevement2, technicien2)
            VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $type_echantillon1, $date_prelevement1, $technicien1,
            $type_echantillon2, $date_prelevement2, $technicien2
        ]);
        
        // Redirection après insertion pour éviter la resoumission du formulaire
        header("Location: echantillon_male.php?success=1");
        exit();

    } catch (PDOException $e) {
        $message = "Erreur lors de l'enregistrement : " . $e->getMessage();
    }
}

// Afficher un message de succès après redirection
if (isset($_GET['success']) && $_GET['success'] == 1) {
    $message = "Échantillons enregistrés avec succès !";
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Gestion des Échantillons</title>
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

        @media (max-width: 900px) {
            .echantillon-col {
                min-width: 100%;
            }
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
    </style>
</head>

<body>

    <div class="container">
        <div class="header">
            <h1><i class="fas fa-vial"></i> Gestion des Échantillons</h1>
            <p>Enregistrement de deux échantillons ou plus</p>
        </div>

        <div class="content-area">
            <?php if (!empty($message)): ?>
                <div class="alert <?php echo strpos($message, 'succès') !== false ? 'alert-success' : 'alert-danger'; ?>">
                    <i class="fas fa-info-circle"></i>
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="form-section">
                    <div class="echantillon-col">
                        <h2><i class="fas fa-capsules"></i> Échantillon 1</h2>
                        <div class="form-grid">
                            <div class="form-field">
                                <label for="type_echantillon1" class="form-label">Type de l'échantillon</label>
                                <input type="text" id="type_echantillon1" name="type_echantillon1" class="form-input" value="<?php echo htmlspecialchars($type_echantillon1); ?>" />
                            </div>
                            <div class="form-field">
                                <label for="date_prelevement1" class="form-label">Date de prélèvement</label>
                                <input type="date" id="date_prelevement1" name="date_prelevement1" class="form-input" value="<?php echo htmlspecialchars($date_prelevement1); ?>" />
                            </div>
                            <div class="form-field">
                                <label for="technicien1" class="form-label">Technicien responsable</label>
                                <input type="text" id="technicien1" name="technicien1" class="form-input" value="<?php echo htmlspecialchars($technicien1); ?>" />
                            </div>
                        </div>
                    </div>

                    <div class="echantillon-col">
                        <h2><i class="fas fa-capsules"></i> Échantillon 2</h2>
                        <div class="form-grid">
                            <div class="form-field">
                                <label for="type_echantillon2" class="form-label">Type de l'échantillon</label>
                                <input type="text" id="type_echantillon2" name="type_echantillon2" class="form-input" value="<?php echo htmlspecialchars($type_echantillon2); ?>" />
                            </div>
                            <div class="form-field">
                                <label for="date_prelevement2" class="form-label">Date de prélèvement</label>
                                <input type="date" id="date_prelevement2" name="date_prelevement2" class="form-input" value="<?php echo htmlspecialchars($date_prelevement2); ?>" />
                            </div>
                            <div class="form-field">
                                <label for="technicien2" class="form-label">Technicien responsable</label>
                                <input type="text" id="technicien2" name="technicien2" class="form-input" value="<?php echo htmlspecialchars($technicien2); ?>" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="actions">
                    <div class="btn-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Enregistrer les échantillons
                        </button>
                        <a href="echantillon_unique.php" class="btn btn-secondary">
                            <i class="fas fa-flask"></i> Échantillon unique
                        </a>
                    </div>
                    <div class="btn-group">
                        <a href="javascript:history.back()" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Retour
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
page/
</html>