<?php
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
    // Récupérer les données du formulaire
    $type_echantillon1 = $_POST['type_echantillon1'] ?? '';
    $date_prelevement1 = $_POST['date_prelevement1'] ?? '';
    $technicien1 = $_POST['technicien1'] ?? '';
    $type_echantillon2 = $_POST['type_echantillon2'] ?? '';
    $date_prelevement2 = $_POST['date_prelevement2'] ?? '';
    $technicien2 = $_POST['technicien2'] ?? '';
    
    // Traitement des données (peut être ajouté selon les besoins)
    
    // Message de confirmation
    $message = "Échantillons enregistrés avec succès !";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Gestion des Échantillons</title>
    <style>
        :root {
            --primary-bg: #ffffff;
            --primary-color: #0047ab; /* Cobalt blue */
            --secondary-color: #1e90ff; /* Dodger blue */
            --accent-color: #00008b; /* Dark blue */
            --header-bg: linear-gradient(135deg, #0047ab 0%, #1e90ff 100%);
            --text-color: #ffffff;
            --text-dark: #333333;
            --section-bg: #ffffff;
            --section-border: #d0e1f9;
            --input-bg: #ffffff;
            --input-border: #d0e1f9;
            --input-focus: #4169e1;
            --input-text: #333333;
            --button-primary: linear-gradient(135deg, #0047ab 0%, #1e90ff 100%);
            --button-secondary: linear-gradient(135deg, #4169e1 0%, #6495ed 100%);
            --button-danger: linear-gradient(135deg, #d63031 0%, #e84393 100%);
            --button-text: #ffffff;
            --button-hover-primary: linear-gradient(135deg, #003d91 0%, #0077e6 100%);
            --button-hover-secondary: linear-gradient(135deg, #375ad9 0%, #5a89eb 100%);
            --button-hover-danger: linear-gradient(135deg, #c12525 0%, #d63384 100%);
            --success-bg: #2ecc71;
            --error-bg: #e74c3c;
            --border-radius: 8px;
            --box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            --transition-speed: 0.3s;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--primary-bg);
            color: var(--text-dark);
            margin: 0;
            padding: 20px;
            min-height: 100vh;
            line-height: 1.6;
        }

        .container {
            width: 100%;
            max-width: 1100px;
            margin: 0 auto;
            background-color: var(--section-bg);
            box-shadow: var(--box-shadow);
            border-radius: var(--border-radius);
            overflow: hidden;
        }

        .header {
            font-size: 1.5em;
            font-weight: bold;
            padding: 25px 20px;
            text-align: center;
            text-transform: uppercase;
            background: var(--header-bg);
            color: var(--text-color);
            letter-spacing: 1px;
            position: relative;
        }

        .echantillons-section {
            display: flex;
            flex-wrap: wrap;
            margin: 20px;
            gap: 20px;
        }

        .echantillon-col {
            flex: 1;
            min-width: 300px;
            background-color: var(--section-bg);
            padding: 20px;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            border: 1px solid var(--section-border);
        }

        h2 {
            margin-top: 0;
            margin-bottom: 15px;
            font-size: 1.2em;
            color: var(--primary-color);
            border-bottom: 2px solid var(--secondary-color);
            padding-bottom: 10px;
            position: relative;
        }

        h2::before {
            content: "";
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 80px;
            height: 2px;
            background-color: var(--accent-color);
        }

        .form-row {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 15px;
            gap: 20px;
        }

        .form-field {
            display: flex;
            flex-direction: column;
            flex: 1;
            min-width: 250px;
        }

        label {
            font-size: 1em;
            font-weight: 500;
            margin-bottom: 8px;
            color: var(--primary-color);
        }

        input[type="text"],
        input[type="date"] {
            background-color: var(--input-bg);
            color: var(--input-text);
            border: 1px solid var(--input-border);
            padding: 12px 15px;
            font-size: 1em;
            border-radius: var(--border-radius);
            transition: all var(--transition-speed);
        }

        input[type="text"]:focus,
        input[type="date"]:focus {
            outline: none;
            border-color: var(--input-focus);
            box-shadow: 0 0 0 3px rgba(82, 118, 193, 0.25);
        }

        .bottom-buttons {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            padding: 0 20px 20px;
            gap: 20px;
        }

        .left-buttons,
        .right-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }

        .btn {
            font-weight: 600;
            padding: 12px 25px;
            border: none;
            cursor: pointer;
            min-width: 180px;
            text-align: center;
            border-radius: var(--border-radius);
            color: var(--button-text);
            transition: all var(--transition-speed);
            font-size: 1em;
        }

        .btn-primary {
            background: var(--button-primary);
        }

        .btn-secondary {
            background: var(--button-secondary);
        }

        .btn-danger {
            background: var(--button-danger);
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(44, 76, 140, 0.3);
        }

        .btn-primary:hover {
            background: var(--button-hover-primary);
        }

        .btn-secondary:hover {
            background: var(--button-hover-secondary);
        }

        .btn-danger:hover {
            background: var(--button-hover-danger);
        }

        .alert {
            padding: 15px;
            margin: 20px;
            border-radius: var(--border-radius);
            font-weight: 500;
        }

        .alert-success {
            background-color: var(--success-bg);
            color: white;
        }

        /* Responsive adjustments */
        @media (max-width: 900px) {
            .bottom-buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .left-buttons,
            .right-buttons {
                justify-content: center;
                width: 100%;
            }

            .form-field {
                width: 100%;
            }
        }

        @media (max-width: 600px) {
            body {
                padding: 10px;
            }

            .container {
                border-radius: 0;
            }

            .echantillons-section {
                margin: 10px;
            }

            label {
                margin-bottom: 5px;
            }

            .btn {
                width: 100%;
            }
            
            .form-row {
                gap: 10px;
            }
        }

        /* Dark mode support - Disabled to maintain blue on white */
        @media (prefers-color-scheme: dark) {
            /* Keep the light mode theme for dark mode users */
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">GESTION DES ÉCHANTILLONS</div>

        <?php if (!empty($message)): ?>
            <div class="alert alert-success">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <!-- Échantillons -->
            <div class="echantillons-section">
                <div class="echantillon-col">
                    <h2>Échantillon 1</h2>
                    <div class="form-row">
                        <div class="form-field">
                            <label for="type_echantillon1">Type de l'échantillon</label>
                            <input type="text" id="type_echantillon1" name="type_echantillon1" value="<?php echo $type_echantillon1; ?>" />
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-field">
                            <label for="date_prelevement1">Date prélèvement</label>
                            <input type="date" id="date_prelevement1" name="date_prelevement1" value="<?php echo $date_prelevement1; ?>" />
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-field">
                            <label for="technicien1">Technicien responsable</label>
                            <input type="text" id="technicien1" name="technicien1" value="<?php echo $technicien1; ?>" />
                        </div>
                    </div>
                </div>
                <div class="echantillon-col">
                    <h2>Échantillon 2</h2>
                    <div class="form-row">
                        <div class="form-field">
                            <label for="type_echantillon2">Type de l'échantillon</label>
                            <input type="text" id="type_echantillon2" name="type_echantillon2" value="<?php echo $type_echantillon2; ?>" />
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-field">
                            <label for="date_prelevement2">Date prélèvement</label>
                            <input type="date" id="date_prelevement2" name="date_prelevement2" value="<?php echo $date_prelevement2; ?>" />
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-field">
                            <label for="technicien2">Technicien responsable</label>
                            <input type="text" id="technicien2" name="technicien2" value="<?php echo $technicien2; ?>" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Boutons -->
            <div class="bottom-buttons">
                <div class="left-buttons">
                    <button type="submit" class="btn btn-primary">Enregistrer les échantillons</button>
                    <a href="echantillon_unique.php" class="btn btn-secondary" style="text-decoration: none;">Échantillon unique</a>
                </div>
                <div class="right-buttons">
                    <a href="formulaire_visite.php" class="btn btn-secondary" style="text-decoration: none;">Retour au formulaire</a>
                    <button type="button" class="btn btn-danger" onclick="window.history.back()">Annuler</button>
                </div>
            </div>
        </form>
    </div>
    
    <script>
        // Dark mode detection
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            document.documentElement.classList.add('dark');
        }
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', event => {
            if (event.matches) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        });
    </script>
</body>
</html>