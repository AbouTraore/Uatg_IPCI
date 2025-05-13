<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Formulaire Visite</title>
    <style>
        :root {
            --primary-bg: #001c57;
            --section-bg: #002575;
            --header-bg: #001440;
            --text-color: #ffffff;
            --input-bg: #ffffff;
            --input-text: #333333;
            --button-bg: #ffffff;
            --button-text: #001c57;
            --button-hover: #e0e0e0;
            --border-radius: 8px;
            --box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            --section-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--primary-bg);
            color: var(--text-color);
            margin: 0;
            padding: 20px;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            width: 100%;
            max-width: 1000px;
            background-color: var(--primary-bg);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            overflow: hidden;
            padding-bottom: 20px;
        }

        .header {
            background-color: var(--header-bg);
            padding: 20px;
            font-size: 1.5em;
            font-weight: bold;
            text-align: center;
            letter-spacing: 1px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .form-section {
            background-color: var(--section-bg);
            margin: 0 20px 20px;
            padding: 20px;
            border-radius: var(--border-radius);
            box-shadow: var(--section-shadow);
        }

        .form-row {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 15px;
            align-items: center;
            gap: 15px;
        }

        .form-field {
            display: flex;
            align-items: center;
            flex: 1;
            min-width: 250px;
        }

        label {
            font-size: 0.95em;
            margin-right: 10px;
            white-space: nowrap;
        }

        input[type="text"],
        input[type="date"],
        input[type="time"] {
            background-color: var(--input-bg);
            color: var(--input-text);
            border: none;
            border-radius: 4px;
            padding: 10px;
            flex: 1;
            min-width: 0;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="date"]:focus,
        input[type="time"]:focus {
            outline: none;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1),
                0 0 0 2px rgba(255, 255, 255, 0.3);
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
            box-shadow: var(--section-shadow);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            cursor: pointer;
            text-decoration: none; /* Supprime le soulignement */
            color: var(--text-color); /* Couleur du texte en blanc */
            display: block; /* Pour s'assurer que le lien se comporte comme un bloc */
        }

        .examen-col:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
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
            box-shadow: var(--section-shadow);
        }

        h2 {
            margin-top: 0;
            margin-bottom: 15px;
            font-size: 1.2em;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding-bottom: 10px;
        }

        .bottom-buttons {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            padding: 0 20px;
            gap: 20px;
        }

        .left-buttons,
        .right-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .btn {
            background-color: var(--button-bg);
            color: var(--button-text);
            font-weight: bold;
            padding: 12px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            width: 230px;
            text-align: center;
            transition: all 0.2s ease;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
        }

        .btn:hover {
            background-color: var(--button-hover);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .btn:active {
            transform: translateY(0);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
        }

        /* Responsive adjustments */
        @media (max-width: 900px) {
            .bottom-buttons {
                flex-direction: column;
                align-items: center;
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
            }

            .form-field {
                flex-direction: column;
                align-items: flex-start;
            }

            label {
                margin-bottom: 5px;
            }

            input[type="text"],
            input[type="date"],
            input[type="time"] {
                width: 100%;
            }

            .btn {
                width: 100%;
            }
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
                    <input type="date" />
                </div>
                <div class="form-field">
                    <label>Prescripteur</label>
                    <input type="text" />
                </div>
                <div class="form-field">
                    <label>Structure de provenance</label>
                    <input type="text" />
                </div>
            </div>

            <div class="form-row">
                <div class="form-field">
                    <label>Heure</label>
                    <input type="time" />
                </div>
                <div class="form-field">
                    <label>Motif de la visite</label>
                    <input type="text" />
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
                    <label>Type de l'échantillon</label>
                    <input type="text" />
                </div>
                <div class="form-row">
                    <label>Date prélèvement</label>
                    <input type="date" />
                </div>
                <div class="form-row">
                    <label>Techniciens responsable</label>
                    <input type="text" />
                </div>
            </div>
            <div class="echantillon-col">
                <h2>Echantillons 2</h2>
                <div class="form-row">
                    <label>Type de l'échantillon</label>
                    <input type="text" />
                </div>
                <div class="form-row">
                    <label>Date prélèvement</label>
                    <input type="date" />
                </div>
                <div class="form-row">
                    <label>Techniciens responsable</label>
                    <input type="text" />
                </div>
            </div>
        </div>

        <!-- Boutons -->
        <div class="bottom-buttons">
            <div class="left-buttons">
                <button class="btn">Enregistrer les données</button>
                <button class="btn">Générer un rapport</button>
            </div>
            <div class="right-buttons">
                <button class="btn">Imprimer les résultats</button>
                <button class="btn">Retour</button>
            </div>
        </div>
    </div>
</body>
</html>