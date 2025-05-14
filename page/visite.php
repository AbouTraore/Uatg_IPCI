<?php
// Initialiser les variables
$message = '';
$date = '';
$prescripteur = '';
$structure = '';
$heure = '';
$motif = '';

// Traiter le formulaire si soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $date = $_POST['date'] ?? '';
    $prescripteur = $_POST['prescripteur'] ?? '';
    $structure = $_POST['structure'] ?? '';
    $heure = $_POST['heure'] ?? '';
    $motif = $_POST['motif'] ?? '';
    
    // Traitement des données (peut être ajouté selon les besoins)
    
    // Message de confirmation
    $message = "Formulaire de visite soumis avec succès !";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Formulaire Visite</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-bg: #ffffff;
            --primary-color: #0047ab;
            --secondary-color: #1e90ff;
            --accent-color: #00008b;
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
            --box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            --transition-speed: 0.3s;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html, body {
            height: 100%;
            overflow: hidden;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f9ff;
            color: var(--text-dark);
            margin: 0;
            padding: 10px;
            line-height: 1.5;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .container {
            width: 100%;
            max-width: 1200px;
            background-color: var(--section-bg);
            box-shadow: var(--box-shadow);
            border-radius: var(--border-radius);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            height: calc(100vh - 20px);
        }

        .header {
            font-size: 1.4em;
            font-weight: bold;
            padding: 12px 15px;
            text-align: center;
            text-transform: uppercase;
            background: var(--header-bg);
            color: var(--text-color);
            letter-spacing: 1px;
            flex-shrink: 0;
        }

        .content-container {
            flex: 1;
            overflow-y: auto;
            padding: 10px 20px;
            display: flex;
            flex-direction: column;
        }

        .form-section {
            background-color: var(--section-bg);
            border-radius: var(--border-radius);
            padding: 15px;
            margin-bottom: 10px;
            border: 1px solid var(--section-border);
        }

        .form-row {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 10px;
            gap: 15px;
        }

        .form-field {
            display: flex;
            flex-direction: column;
            flex: 1;
            min-width: 200px;
        }

        label {
            font-size: 0.9em;
            font-weight: 600;
            margin-bottom: 5px;
            color: var(--primary-color);
        }

        input[type="text"],
        input[type="date"],
        input[type="time"] {
            background-color: var(--input-bg);
            color: var(--input-text);
            border: 1px solid var(--input-border);
            padding: 8px 10px;
            font-size: 0.95em;
            border-radius: var(--border-radius);
            transition: all var(--transition-speed);
        }

        input[type="text"]:focus,
        input[type="date"]:focus,
        input[type="time"]:focus {
            outline: none;
            border-color: var(--input-focus);
            box-shadow: 0 0 0 2px rgba(82, 118, 193, 0.25);
        }

        .examens-section {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 10px;
        }

        .examen-col {
            flex: 1;
            min-width: 270px;
            background-color: var(--section-bg);
            padding: 80px;
            border-radius: var(--border-radius);
            text-align: center;
            box-shadow: var(--box-shadow);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            cursor: pointer;
            text-decoration: none;
            color: var(--primary-color);
            border: 1px solid var(--section-border);
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .examen-col:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            border-color: var(--secondary-color);
        }

        .examen-title {
            font-weight: bold;
            font-size: 0.85em;
            letter-spacing: 0.3px;
        }

        .buttons-container {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            padding: 0 0 5px 0;
            margin-top: auto;
            border-top: 1px solid var(--section-border);
            padding-top: 10px;
        }

        .btn-group {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .btn {
            font-weight: 500;
            padding: 8px 14px;
            border: none;
            cursor: pointer;
            border-radius: var(--border-radius);
            color: var(--button-text);
            transition: all var(--transition-speed);
            font-size: 0.9em;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            white-space: nowrap;
        }

        .btn i {
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
            box-shadow: 0 4px 8px rgba(44, 76, 140, 0.2);
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
            padding: 10px 15px;
            margin-bottom: 10px;
            border-radius: var(--border-radius);
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-success {
            background-color: var(--success-bg);
            color: white;
        }

        .alert i {
            font-size: 1.2em;
        }

        .section-title {
            color: var(--primary-color);
            font-size: 1.1em;
            margin-bottom: 10px;
            font-weight: 600;
        }

        @media (max-width: 768px) {
            .btn-group {
                justify-content: center;
                margin-bottom: 5px;
            }
            
            .buttons-container {
                flex-direction: column;
                gap: 8px;
            }
            
            .container {
                height: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">FORMULAIRE VISITE</div>
        
        <div class="content-container">
            <?php if (!empty($message)): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" style="display: flex; flex-direction: column; flex: 1;">
                <div class="form-section">
                    <div class="section-title">Informations de la visite</div>
                    <div class="form-row">
                        <div class="form-field">
                            <label for="date">Date</label>
                            <input type="date" id="date" name="date" value="<?php echo $date; ?>" required />
                        </div>
                        <div class="form-field">
                            <label for="heure">Heure</label>
                            <input type="time" id="heure" name="heure" value="<?php echo $heure; ?>" required />
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-field">
                            <label for="prescripteur">Prescripteur</label>
                            <input type="text" id="prescripteur" name="prescripteur" value="<?php echo $prescripteur; ?>" required />
                        </div>
                        <div class="form-field">
                            <label for="structure">Structure de provenance</label>
                            <input type="text" id="structure" name="structure" value="<?php echo $structure; ?>" required />
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-field">
                            <label for="motif">Motif de la visite</label>
                            <input type="text" id="motif" name="motif" value="<?php echo $motif; ?>" required />
                        </div>
                    </div>
                </div>

                <div class="section-title">Types d'examens disponibles</div>
                <div class="examens-section">
                    <a href="ecs.php" class="examen-col">
                        <div class="examen-title">EXAMEN CYTOBACTERIOLOGIQUE DU SPERME</div>
                    </a>
                    <a href="EXA_CYTO_SEC_VAG.php" class="examen-col">
                        <div class="examen-title">
                            EXAMEN CYTOBACTERIOLOGIQUE SECRETION VAGINALE
                        </div>
                    </a>
                    <a href="ecsu.php" class="examen-col">
                        <div class="examen-title">
                            EXAMEN CYTOBACTERIOLOGIQUE SECRETION URETARLE
                        </div>
                    </a>
                </div>

                <div class="buttons-container">
                    <div class="btn-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Enregistrer
                        </button>
                        <a href="echantillons.php" class="btn btn-secondary" style="text-decoration: none;">
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
                        <button type="button" class="btn btn-danger" onclick="window.history.back()">
                            <i class="fas fa-arrow-left"></i> Retour
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        // Ajuster la hauteur du container au chargement et au redimensionnement
        function adjustHeight() {
            const container = document.querySelector('.container');
            container.style.height = window.innerHeight - 20 + 'px';
        }
        
        window.addEventListener('load', adjustHeight);
        window.addEventListener('resize', adjustHeight);
        
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