<?php
require_once("identifier.php");
require_once("connexion.php");

$numero_urap = $_GET['urap'] ?? '';
$antecedent_id = $_GET['id'] ?? '';
$message = '';
$message_type = '';

// Récupérer les données existantes
$antecedents = null;
if ($antecedent_id) {
    $stmt = $pdo->prepare("SELECT * FROM antecedents_ist_hommes WHERE ID_antecedent = ? AND Numero_urap = ?");
    $stmt->execute([$antecedent_id, $numero_urap]);
    $antecedents = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Initialiser les valeurs par défaut
$antecedent = $antecedents ? $antecedents['antecedent'] : '';
$antibiotique_actuel = $antecedents ? $antecedents['antibiotique_actuel'] : '';
$preciser_antibiotique = $antecedents ? $antecedents['preciser_antibiotique'] : '';

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $antecedent = htmlspecialchars($_POST['antecedent'] ?? '');
    $antibiotique_actuel = htmlspecialchars($_POST['antibiotique_actuel'] ?? '');
    $preciser_antibiotique = htmlspecialchars($_POST['preciser_antibiotique'] ?? '');
    $date_creation = date('Y-m-d H:i:s');

    try {
        if ($antecedent_id && $antecedents) {
            // Modification
            $sql = "UPDATE antecedents_ist_hommes SET 
                    antecedent = ?, 
                    antibiotique_actuel = ?, 
                    preciser_antibiotique = ?, 
                    date_creation = ? 
                    WHERE ID_antecedent = ?";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$antecedent, $antibiotique_actuel, $preciser_antibiotique, $date_creation, $antecedent_id]);
            
            $message = "Les antécédents IST ont été mis à jour avec succès !";
        } else {
            // Création - Générer un nouvel ID
            $stmt = $pdo->prepare("SELECT MAX(ID_antecedent) as max_id FROM antecedents_ist_hommes");
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $nouvel_id = ($result['max_id'] ?? 0) + 1;
            
            $sql = "INSERT INTO antecedents_ist_hommes (ID_antecedent, Numero_urap, antecedent, antibiotique_actuel, preciser_antibiotique, date_creation) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nouvel_id, $numero_urap, $antecedent, $antibiotique_actuel, $preciser_antibiotique, $date_creation]);
            
            $message = "Les antécédents IST ont été enregistrés avec succès !";
            $antecedent_id = $nouvel_id;
        }
        
        $message_type = 'success';
        
        // Recharger les données
        $stmt = $pdo->prepare("SELECT * FROM antecedents_ist_hommes WHERE ID_antecedent = ?");
        $stmt->execute([$antecedent_id]);
        $antecedents = $stmt->fetch(PDO::FETCH_ASSOC);
        
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
    <title><?= $antecedents ? 'Modifier' : 'Ajouter' ?> Antécédents IST Hommes</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary: #0047ab;
            --primary-light: #1e90ff;
            --success: #22c55e;
            --danger: #ef4444;
            --warning: #f59e0b;
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
            color: var(--warning);
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
            border-left: 4px solid var(--warning);
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
            border-color: var(--warning);
            box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
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
            background: linear-gradient(135deg, var(--warning) 0%, #eab308 100%);
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
            background: #fffbeb;
            border: 1px solid #fed7aa;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 24px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .info-box i {
            color: var(--warning);
            font-size: 1.2rem;
            margin-top: 2px;
        }

        .info-box-content h4 {
            color: var(--warning);
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .info-box-content p {
            color: #92400e;
            font-size: 0.875rem;
            line-height: 1.4;
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
            <h1><i class="fas fa-male"></i> <?= $antecedents ? 'Modifier' : 'Ajouter' ?> Antécédents IST Hommes</h1>
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
                <p>Ces informations concernent les antécédents d'infections sexuellement transmissibles pour les patients masculins. Toutes les données restent strictement confidentielles.</p>
            </div>
        </div>

        <div class="form-container">
            <form method="post">
                <div class="form-grid">
                    <div class="form-field">
                        <label for="antecedent" class="form-label required">Antécédent</label>
                        <select id="antecedent" name="antecedent" class="form-input" required>
                            <option value="">-- Sélectionner un antécédent --</option>
                            <option value="deja ete atteint d'une MST" <?= $antecedent == "deja ete atteint d'une MST" ? 'selected' : '' ?>>Déjà été atteint d'une MST</option>
                            <option value="brulure au niveau de l'uretre" <?= $antecedent == "brulure au niveau de l'uretre" ? 'selected' : '' ?>>Brûlure au niveau de l'urètre</option>
                            <option value="ecoulement uretral" <?= $antecedent == "ecoulement uretral" ? 'selected' : '' ?>>Écoulement urétral</option>
                            <option value="ulceration genitale" <?= $antecedent == "ulceration genitale" ? 'selected' : '' ?>>Ulcération génitale</option>
                            <option value="douleur testiculaire" <?= $antecedent == "douleur testiculaire" ? 'selected' : '' ?>>Douleur testiculaire</option>
                            <option value="adenopathie inguinale" <?= $antecedent == "adenopathie inguinale" ? 'selected' : '' ?>>Adénopathie inguinale</option>
                            <option value="aucun antecedent" <?= $antecedent == "aucun antecedent" ? 'selected' : '' ?>>Aucun antécédent</option>
                        </select>
                    </div>
                    
                    <div class="form-field">
                        <label for="antibiotique_actuel" class="form-label required">Prenez-vous un antibiotique actuellement ?</label>
                        <select id="antibiotique_actuel" name="antibiotique_actuel" class="form-input" required onchange="toggleAntibiotique()">
                            <option value="">-- Sélectionner --</option>
                            <option value="oui" <?= $antibiotique_actuel == 'oui' ? 'selected' : '' ?>>Oui</option>
                            <option value="non" <?= $antibiotique_actuel == 'non' ? 'selected' : '' ?>>Non</option>
                        </select>
                    </div>
                    
                    <div class="form-field" id="field-preciser" style="<?= $antibiotique_actuel == 'oui' ? '' : 'display: none;' ?>">
                        <label for="preciser_antibiotique" class="form-label">Préciser l'antibiotique</label>
                        <textarea id="preciser_antibiotique" name="preciser_antibiotique" class="form-textarea" 
                                  placeholder="Précisez le nom de l'antibiotique, la posologie et la durée du traitement..."><?= htmlspecialchars($preciser_antibiotique) ?></textarea>
                    </div>
                </div>

                <div class="actions-bar">
                    <a href="dossier_complet.php?urap=<?= htmlspecialchars($numero_urap) ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Annuler
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> <?= $antecedents ? 'Mettre à jour' : 'Enregistrer' ?>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleAntibiotique() {
            const select = document.getElementById('antibiotique_actuel');
            const field = document.getElementById('field-preciser');
            const textarea = document.getElementById('preciser_antibiotique');
            
            if (select.value === 'oui') {
                field.style.display = 'block';
                textarea.setAttribute('required', 'required');
            } else {
                field.style.display = 'none';
                textarea.removeAttribute('required');
                textarea.value = '';
            }
        }

        // Initialiser l'état au chargement
        document.addEventListener('DOMContentLoaded', function() {
            toggleAntibiotique();
        });
    </script>
</body>
</html>