<?php
// nouvelle_habitude_sexuelle.php

// Inclusion des fichiers de sécurité et de connexion à la base de données
// Assurez-vous que ces fichiers existent et sont configurés correctement.
require_once("identifier.php");
require_once("connexion.php"); // Assuming you need a DB connection for future saving

$message = '';
$messageType = '';

// Traitement du formulaire si soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Récupération et nettoyage des données du formulaire
        $numero_urap = htmlspecialchars(trim($_POST['numero_urap'] ?? ''));
        $type_rapport = htmlspecialchars($_POST['Quel_type_rapport_avez_vous'] ?? '');
        $pratique_fellation = htmlspecialchars($_POST['Pratiquez_vous__fellation'] ?? '');
        $change_partenaire = htmlspecialchars($_POST['Avez_vous_changé_partenais_ces_deux_dernier_mois'] ?? '');
        $utilise_preservatif = htmlspecialchars($_POST['Utilisez_vous_preservatif'] ?? '');

        // Validation: tous les champs sont requis
        if (empty($numero_urap) || empty($type_rapport) || empty($pratique_fellation) || empty($change_partenaire) || empty($utilise_preservatif)) {
            throw new Exception("Veuillez remplir tous les champs, y compris le numéro URAP.");
        }

        // Insertion en base de données
        $sql = "INSERT INTO habitude_sexuelles (Numero_urap, Quel_type_rapport_avez_vous_, Pratiquez_vous__fellation, Avez_vous_changé_partenais_ces_deux_dernier_mois, Utilisez_vous_preservatif) VALUES (?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([
            $numero_urap,
            $type_rapport,
            $pratique_fellation,
            $change_partenaire,
            $utilise_preservatif
        ]);

        if ($result) {
            $message = 'Habitude sexuelle enregistrée avec succès !';
            $messageType = 'success';
        } else {
            $message = 'Erreur lors de l\'enregistrement de l\'habitude sexuelle.';
            $messageType = 'error';
        }
    } catch (Exception $e) {
        $message = 'Erreur : ' . $e->getMessage();
        $messageType = 'error';
    }
}

// Ensure the form action is correct. If you want to process in the same file,
// the action should be empty or point to itself.
// original code used "insertagence.php" but the context implies self-processing.
// I'll set it to process in this file.
$form_action = $_SERVER['PHP_SELF'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Nouvelle Habitude Sexuelle - UATG</title>
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
            font-size: 1rem; /* Increased font size for labels */
            font-weight: 600; /* Made labels bolder */
            color: var(--gray-700);
            margin-bottom: 12px; /* Increased margin below labels */
        }

        .radio-group {
            display: flex;
            flex-direction: column;
            gap: 12px; /* Spacing between radio options */
        }

        .radio-group label {
            display: flex;
            align-items: center;
            font-size: 0.95rem;
            color: var(--gray-600);
            cursor: pointer;
            transition: color 0.2s;
        }

        .radio-group label:hover {
            color: var(--primary-dark);
        }

        .radio-group input[type="radio"] {
            margin-right: 10px;
            width: 18px;
            height: 18px;
            accent-color: var(--primary); /* Highlight color for radio buttons */
            cursor: pointer;
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
    <style>
        .input-urap {
            padding: 14px 18px;
            border: 2px solid var(--primary);
            border-radius: 10px;
            font-size: 1.15rem;
            font-weight: 600;
            background: var(--gray-50);
            color: var(--primary-dark);
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            transition: border 0.2s, box-shadow 0.2s;
            outline: none;
        }
        .input-urap:focus {
            border: 2px solid var(--accent);
            box-shadow: 0 4px 12px rgba(16,185,129,0.10);
            background: #fff;
        }
        .form-label[for="numero_urap"] {
            color: var(--primary-dark);
            font-size: 1.1rem;
            letter-spacing: 0.5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <button onclick="window.history.back()" class="btn-retour">
                <i class="fas fa-arrow-left"></i> Retour
            </button>
            <h1><i class="fas fa-venus-mars"></i> Habitudes Sexuelles</h1>
            <p>Veuillez saisir les données de votre habitude sexuelle</p>
        </div>

        <div class="content-area">
            <?php if (!empty($message)): ?>
                <div class="alert alert-<?php echo $messageType; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <form id="sexualHabitForm" method="POST" action="<?php echo $form_action; ?>">
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-id-card"></i>
                        Numéro URAP
                    </h2>
                    <div class="form-grid">
                        <div class="form-field">
                            <label class="form-label" for="numero_urap">Numéro URAP du patient</label>
                            <input type="text" name="numero_urap" id="numero_urap" class="input-urap" value="<?php echo isset($_POST['numero_urap']) ? htmlspecialchars($_POST['numero_urap']) : ''; ?>" required />
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-question-circle"></i>
                        Type de Rapport
                    </h2>
                    <div class="form-grid">
                        <div class="form-field">
                            <label class="form-label">Quel type de rapport avez-vous ?</label>
                            <div class="radio-group">
                                <label for="hetero">
                                    <input type="radio" name="Quel_type_rapport_avez_vous" id="hetero" value="Hétérosexuel" <?php echo (isset($_POST['Quel_type_rapport_avez_vous']) && $_POST['Quel_type_rapport_avez_vous'] == 'Hétérosexuel') ? 'checked' : ''; ?> /> Hétérosexuel
                                </label>
                                <label for="homo">
                                    <input type="radio" name="Quel_type_rapport_avez_vous" id="homo" value="Homosexuel" <?php echo (isset($_POST['Quel_type_rapport_avez_vous']) && $_POST['Quel_type_rapport_avez_vous'] == 'Homosexuel') ? 'checked' : ''; ?> /> Homosexuel
                                </label>
                                <label for="quelquefois_rapport">
                                    <input type="radio" name="Quel_type_rapport_avez_vous" id="quelquefois_rapport" value="quelque fois" <?php echo (isset($_POST['Quel_type_rapport_avez_vous']) && $_POST['Quel_type_rapport_avez_vous'] == 'quelque fois') ? 'checked' : ''; ?> /> Quelque fois
                                </label>
                                <label for="bi">
                                    <input type="radio" name="Quel_type_rapport_avez_vous" id="bi" value="Bisexuel" <?php echo (isset($_POST['Quel_type_rapport_avez_vous']) && $_POST['Quel_type_rapport_avez_vous'] == 'Bisexuel') ? 'checked' : ''; ?> /> Bisexuel
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="section-divider"></div>

                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-question-circle"></i>
                        Pratique de la Fellation
                    </h2>
                    <div class="form-grid">
                        <div class="form-field">
                            <label class="form-label">Pratiquez-vous la fellation ?</label>
                            <div class="radio-group">
                                <label for="jamais_fellation">
                                    <input type="radio" name="Pratiquez_vous__fellation" id="jamais_fellation" value="Jamais" <?php echo (isset($_POST['Pratiquez_vous__fellation']) && $_POST['Pratiquez_vous__fellation'] == 'Jamais') ? 'checked' : ''; ?> /> Jamais
                                </label>
                                <label for="rarement_fellation">
                                    <input type="radio" name="Pratiquez_vous__fellation" id="rarement_fellation" value="Rarement" <?php echo (isset($_POST['Pratiquez_vous__fellation']) && $_POST['Pratiquez_vous__fellation'] == 'Rarement') ? 'checked' : ''; ?> /> Rarement
                                </label>
                                <label for="quelquefois_fellation">
                                    <input type="radio" name="Pratiquez_vous__fellation" id="quelquefois_fellation" value="quelque fois" <?php echo (isset($_POST['Pratiquez_vous__fellation']) && $_POST['Pratiquez_vous__fellation'] == 'quelque fois') ? 'checked' : ''; ?> /> Quelque fois
                                </label>
                                <label for="toujours_fellation">
                                    <input type="radio" name="Pratiquez_vous__fellation" id="toujours_fellation" value="Toujours" <?php echo (isset($_POST['Pratiquez_vous__fellation']) && $_POST['Pratiquez_vous__fellation'] == 'Toujours') ? 'checked' : ''; ?> /> Toujours
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="section-divider"></div>

                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-question-circle"></i>
                        Changement de Partenaire
                    </h2>
                    <div class="form-grid">
                        <div class="form-field">
                            <label class="form-label">Avez-vous changé de partenaire ces (2) derniers mois ?</label>
                            <div class="radio-group">
                                <label for="oui_partenaire">
                                    <input type="radio" name="Avez_vous_changé_partenais_ces_deux_dernier_mois" id="oui_partenaire" value="oui" <?php echo (isset($_POST['Avez_vous_changé_partenais_ces_deux_dernier_mois']) && $_POST['Avez_vous_changé_partenais_ces_deux_dernier_mois'] == 'oui') ? 'checked' : ''; ?> /> Oui
                                </label>
                                <label for="non_partenaire">
                                    <input type="radio" name="Avez_vous_changé_partenais_ces_deux_dernier_mois" id="non_partenaire" value="Non" <?php echo (isset($_POST['Avez_vous_changé_partenais_ces_deux_dernier_mois']) && $_POST['Avez_vous_changé_partenais_ces_deux_dernier_mois'] == 'Non') ? 'checked' : ''; ?> /> Non
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="section-divider"></div>

                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-question-circle"></i>
                        Utilisation du Préservatif
                    </h2>
                    <div class="form-grid">
                        <div class="form-field">
                            <label class="form-label">Utilisez-vous un préservatif ?</label>
                            <div class="radio-group">
                                <label for="jamais_preservatif">
                                    <input type="radio" name="Utilisez_vous_preservatif" id="jamais_preservatif" value="Jamais" <?php echo (isset($_POST['Utilisez_vous_preservatif']) && $_POST['Utilisez_vous_preservatif'] == 'Jamais') ? 'checked' : ''; ?> /> Jamais
                                </label>
                                <label for="rarement_preservatif">
                                    <input type="radio" name="Utilisez_vous_preservatif" id="rarement_preservatif" value="Rarement" <?php echo (isset($_POST['Utilisez_vous_preservatif']) && $_POST['Utilisez_vous_preservatif'] == 'Rarement') ? 'checked' : ''; ?> /> Rarement
                                </label>
                                <label for="quelquefois_preservatif">
                                    <input type="radio" name="Utilisez_vous_preservatif" id="quelquefois_preservatif" value="quelque fois" <?php echo (isset($_POST['Utilisez_vous_preservatif']) && $_POST['Utilisez_vous_preservatif'] == 'quelque fois') ? 'checked' : ''; ?> /> Quelque fois
                                </label>
                                <label for="toujours_preservatif">
                                    <input type="radio" name="Utilisez_vous_preservatif" id="toujours_preservatif" value="Toujours" <?php echo (isset($_POST['Utilisez_vous_preservatif']) && $_POST['Utilisez_vous_preservatif'] == 'Toujours') ? 'checked' : ''; ?> /> Toujours
                                </label>
                            </div>
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
        function resetForm() {
            document.getElementById('sexualHabitForm').reset();
        }

        // Animation d'apparition des éléments
        document.addEventListener('DOMContentLoaded', function() {
            const formSections = document.querySelectorAll('.form-section');
            formSections.forEach((section, index) => {
                section.style.opacity = '0';
                section.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    section.style.transition = 'all 0.3s ease';
                    section.style.opacity = '1';
                    section.style.transform = 'translateY(0)';
                }, index * 150);
            });

            // Gestion de la soumission du formulaire
            document.getElementById('sexualHabitForm').addEventListener('submit', function(e) {
                // Animation de chargement
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalContent = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enregistrement...';
                submitBtn.disabled = true;

                // You might want to add client-side validation here if needed.
                // For radio buttons, one option is always selected by default if 'checked' is used,
                // so explicit validation might only be needed if the user can somehow unselect all.
            });
        });
    </script>
</body>
</html> 