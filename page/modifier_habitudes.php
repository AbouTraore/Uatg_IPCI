<?php
require_once("identifier.php");
require_once("connexion.php");

$numero_urap = $_GET['urap'] ?? '';
$habitude_id = $_GET['id'] ?? '';
$message = '';
$message_type = '';

// Récupérer les données existantes
$habitudes = null;
if ($habitude_id) {
    $stmt = $pdo->prepare("SELECT * FROM habitude_sexuelles WHERE ID_habitude_sexuelles = ? AND Numero_urap = ?");
    $stmt->execute([$habitude_id, $numero_urap]);
    $habitudes = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Initialiser les valeurs par défaut
$type_rapport = $habitudes ? $habitudes['Quel_type_rapport_avez_vous'] : '';
$fellation = $habitudes ? $habitudes['Pratiquez_vous__fellation'] : '';
$cunnilingus = $habitudes ? $habitudes['Pratiquez_vous_cunilingus'] : '';
$changement = $habitudes ? $habitudes['Avez_vous_changé_partenais_ces_deux_dernier_mois'] : '';
$preservatif = $habitudes ? $habitudes['Utilisez_vous_preservatif'] : '';

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $type_rapport = htmlspecialchars($_POST['type_rapport'] ?? '');
    $fellation = htmlspecialchars($_POST['fellation'] ?? '');
    $cunnilingus = htmlspecialchars($_POST['cunnilingus'] ?? '');
    $changement = htmlspecialchars($_POST['changement'] ?? '');
    $preservatif = htmlspecialchars($_POST['preservatif'] ?? '');

    try {
        if ($habitude_id && $habitudes) {
            // Modification
            $sql = "UPDATE habitude_sexuelles SET 
                    Quel_type_rapport_avez_vous = ?, 
                    Pratiquez_vous__fellation = ?, 
                    Pratiquez_vous_cunilingus = ?, 
                    Avez_vous_changé_partenais_ces_deux_dernier_mois = ?, 
                    Utilisez_vous_preservatif = ? 
                    WHERE ID_habitude_sexuelles = ?";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$type_rapport, $fellation, $cunnilingus, $changement, $preservatif, $habitude_id]);
            
            $message = "Les habitudes sexuelles ont été mises à jour avec succès !";
        } else {
            // Création
            $sql = "INSERT INTO habitude_sexuelles (Numero_urap, Quel_type_rapport_avez_vous, Pratiquez_vous__fellation, Pratiquez_vous_cunilingus, Avez_vous_changé_partenais_ces_deux_dernier_mois, Utilisez_vous_preservatif) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$numero_urap, $type_rapport, $fellation, $cunnilingus, $changement, $preservatif]);
            
            $message = "Les habitudes sexuelles ont été enregistrées avec succès !";
        }
        
        $message_type = 'success';
        
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
    <title><?= $habitudes ? 'Modifier' : 'Ajouter' ?> Habitudes Sexuelles</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* [Même CSS que modifier_patient.php] */
        :root {
            --primary: #0047ab;
            --primary-light: #1e90ff;
            --success: #22c55e;
            --danger: #ef4444;
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
            max-width: 800px;
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
            color: var(--primary);
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
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
            margin-bottom: 24px;
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
            font-size: 16px;
            transition: all 0.2s ease;
            background: white;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(0, 71, 171, 0.1);
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
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
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

        @media (max-width: 768px) {
            .actions-bar {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-venus-mars"></i> <?= $habitudes ? 'Modifier' : 'Ajouter' ?> Habitudes Sexuelles</h1>
            <p>Patient N°URAP: <?= htmlspecialchars($numero_urap) ?></p>
        </div>

        <?php if (!empty($message)): ?>
            <div class="alert alert-<?= $message_type ?>">
                <i class="fas fa-<?= ($message_type == 'success' ? 'check-circle' : 'exclamation-triangle') ?>"></i>
                <?= $message ?>
            </div>
        <?php endif; ?>

        <div class="form-container">
            <form method="post">
                <div class="form-grid">
                    <div class="form-field">
                        <label for="type_rapport" class="form-label">Quel type de rapport avez-vous ?</label>
                        <select id="type_rapport" name="type_rapport" class="form-input">
                            <option value="">-- Sélectionner --</option>
                            <option value="Hétérosexuel" <?= $type_rapport == 'Hétérosexuel' ? 'selected' : '' ?>>Hétérosexuel</option>
                            <option value="Homosexuel" <?= $type_rapport == 'Homosexuel' ? 'selected' : '' ?>>Homosexuel</option>
                            <option value="Bisexuel" <?= $type_rapport == 'Bisexuel' ? 'selected' : '' ?>>Bisexuel</option>
                        </select>
                    </div>
                    
                    <div class="form-field">
                        <label for="fellation" class="form-label">Pratiquez-vous la fellation ?</label>
                        <select id="fellation" name="fellation" class="form-input">
                            <option value="">-- Sélectionner --</option>
                            <option value="Jamais" <?= $fellation == 'Jamais' ? 'selected' : '' ?>>Jamais</option>
                            <option value="Rarement" <?= $fellation == 'Rarement' ? 'selected' : '' ?>>Rarement</option>
                            <option value="Parfois" <?= $fellation == 'Parfois' ? 'selected' : '' ?>>Parfois</option>
                            <option value="Souvent" <?= $fellation == 'Souvent' ? 'selected' : '' ?>>Souvent</option>
                            <option value="Toujours" <?= $fellation == 'Toujours' ? 'selected' : '' ?>>Toujours</option>
                        </select>
                    </div>
                    
                    <div class="form-field">
                        <label for="cunnilingus" class="form-label">Pratiquez-vous le cunnilingus ?</label>
                        <select id="cunnilingus" name="cunnilingus" class="form-input">
                            <option value="">-- Sélectionner --</option>
                            <option value="Jamais" <?= $cunnilingus == 'Jamais' ? 'selected' : '' ?>>Jamais</option>
                            <option value="Rarement" <?= $cunnilingus == 'Rarement' ? 'selected' : '' ?>>Rarement</option>
                            <option value="Parfois" <?= $cunnilingus == 'Parfois' ? 'selected' : '' ?>>Parfois</option>
                            <option value="Souvent" <?= $cunnilingus == 'Souvent' ? 'selected' : '' ?>>Souvent</option>
                            <option value="Toujours" <?= $cunnilingus == 'Toujours' ? 'selected' : '' ?>>Toujours</option>
                        </select>
                    </div>
                    
                    <div class="form-field">
                        <label for="changement" class="form-label">Avez-vous changé de partenaire ces deux derniers mois ?</label>
                        <select id="changement" name="changement" class="form-input">
                            <option value="">-- Sélectionner --</option>
                            <option value="Oui" <?= $changement == 'Oui' ? 'selected' : '' ?>>Oui</option>
                            <option value="Non" <?= $changement == 'Non' ? 'selected' : '' ?>>Non</option>
                        </select>
                    </div>
                    
                    <div class="form-field">
                        <label for="preservatif" class="form-label">Utilisez-vous le préservatif ?</label>
                        <select id="preservatif" name="preservatif" class="form-input">
                            <option value="">-- Sélectionner --</option>
                            <option value="Jamais" <?= $preservatif == 'Jamais' ? 'selected' : '' ?>>Jamais</option>
                            <option value="Rarement" <?= $preservatif == 'Rarement' ? 'selected' : '' ?>>Rarement</option>
                            <option value="Parfois" <?= $preservatif == 'Parfois' ? 'selected' : '' ?>>Parfois</option>
                            <option value="Souvent" <?= $preservatif == 'Souvent' ? 'selected' : '' ?>>Souvent</option>
                            <option value="Toujours" <?= $preservatif == 'Toujours' ? 'selected' : '' ?>>Toujours</option>
                        </select>
                    </div>
                </div>

                <div class="actions-bar">
                    <a href="dossier_complet.php?urap=<?= htmlspecialchars($numero_urap) ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Annuler
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> <?= $habitudes ? 'Mettre à jour' : 'Enregistrer' ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>