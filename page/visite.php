<?php
// Start the PHP file
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Formulaire Visite</title>
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

        .form-section {
            background-color: var(--section-bg);
            border-radius: var(--border-radius);
            padding: 20px;
            margin: 0 20px 20px;
            border: 1px solid var(--section-border);
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
        input[type="date"],
        input[type="time"] {
            background-color: var(--input-bg);
            color: var(--input-text);
            border: 1px solid var(--input-border);
            padding: 12px 15px;
            font-size: 1em;
            border-radius: var(--border-radius);
            transition: all var(--transition-speed);
        }

        input[type="text"]:focus,
        input[type="date"]:focus,
        input[type="time"]:focus {
            outline: none;
            border-color: var(--input-focus);
            box-shadow: 0 0 0 3px rgba(82, 118, 193, 0.25);
        }

        .examens-section {
            display: flex;
            flex-wrap: wrap;
            margin: 0 20px 20px;
            gap: 15px;
        }

        .examen-col {
            flex: 1;
            min-width: 300px;
            background-color: var(--section-bg);
            padding: 15px;
            border-radius: var(--border-radius);
            text-align: center;
            box-shadow: var(--box-shadow);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            cursor: pointer;
            text-decoration: none;
            color: var(--primary-color);
            display: block;
            border: 1px solid var(--section-border);
        }

        .examen-col:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            border-color: var(--secondary-color);
        }

        .examen-title {
            font-weight: bold;
            font-size: 0.9em;
            padding: 10px;
            letter-spacing: 0.5px;
        }

        .echantillons-section {
            display: flex;
            flex-wrap: wrap;
            margin: 0 20px 20px;
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

            .form-section,
            .examens-section,
            .echantillons-section {
                margin: 0 10px 15px;
                padding: 15px;
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
        <div class="header">FORMULAIRE VISITE</div>

        <!-- Informations de base -->
        <div class="form-section">
            <div class="form-row">
                <div class="form-field">
                    <label>Date</label>
                    <input type="date" name="date" />
                </div>
                <div class="form-field">
                    <label>Prescripteur</label>
                    <input type="text" name="prescripteur" />
                </div>
                <div class="form-field">
                    <label>Structure de provenance</label>
                    <input type="text" name="structure" />
                </div>
            </div>

            <div class="form-row">
                <div class="form-field">
                    <label>Heure</label>
                    <input type="time" name="heure" />
                </div>
                <div class="form-field">
                    <label>Motif de la visite</label>
                    <input type="text" name="motif" />
                </div>
            </div>
        </div>

        <!-- Examens -->
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

        <!-- Échantillons -->
        <div class="echantillons-section">
            <div class="echantillon-col">
                <h2>Echantillons 1</h2>
                <div class="form-row">
                    <div class="form-field">
                        <label>Type de l'échantillon</label>
                        <input type="text" name="type_echantillon_1" />
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-field">
                        <label>Date prélèvement</label>
                        <input type="date" name="date_prelevement_1" />
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-field">
                        <label>Technicien responsable</label>
                        <input type="text" name="tech_responsable_1" />
                    </div>
                </div>
            </div>
            <div class="echantillon-col">
                <h2>Echantillons 2</h2>
                <div class="form-row">
                    <div class="form-field">
                        <label>Type de l'échantillon</label>
                        <input type="text" name="type_echantillon_2" />
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-field">
                        <label>Date prélèvement</label>
                        <input type="date" name="date_prelevement_2" />
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-field">
                        <label>Technicien responsable</label>
                        <input type="text" name="tech_responsable_2" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Boutons -->
        <div class="bottom-buttons">
            <div class="left-buttons">
                <button class="btn btn-primary">Enregistrer les données</button>
                <button class="btn btn-secondary">Générer un rapport</button>
            </div>
            <div class="right-buttons">
                <button class="btn btn-secondary">Imprimer les résultats</button>
                <button class="btn btn-danger">Retour</button>
            </div>
        </div>
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

<?php
// End the PHP file
?>