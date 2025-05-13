<?php
// Start the PHP file
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Nouveau Dossier</title>
    <style>
        :root {
            --primary-bg: #ffffff; /* White background */
            --header-bg: #0047ab; /* Dark blue */
            --text-color: #0047ab; /* Dark blue text */
            --input-bg: #ffffff; /* White input */
            --input-text: #333333; /* Dark text */
            --button-bg: #0047ab; /* Dark blue button */
            --button-text: #ffffff; /* White text for buttons */
            --button-hover: #1e90ff; /* Lighter blue on hover */
            --border-radius: 8px;
            --box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: var(--primary-bg);
            color: var(--text-color);
            margin: 0;
            padding: 20px;
            min-height: 100vh;
        }

        .container {
            width: 100%;
            max-width: 1000px;
            margin: 0 auto;
            background-color: var(--primary-bg);
            box-shadow: var(--box-shadow);
            border-radius: var(--border-radius);
            padding: 20px;
        }

        .header {
            font-size: 1.8em;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
            text-transform: uppercase;
            background-color: var(--header-bg);
            color: var(--button-text);
            padding: 10px;
            border-radius: var(--border-radius);
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px 40px;
            margin-bottom: 30px;
        }

        .form-field {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        label {
            width: 170px;
            font-size: 1.1em;
            white-space: nowrap;
            text-align: left;
            color: var(--text-color);
        }

        input,
        select {
            background-color: var(--input-bg);
            color: var(--input-text);
            border: 1px solid #0047ab; /* Dark blue border */
            padding: 10px 15px;
            flex-grow: 1;
            font-size: 1em;
            border-radius: var(--border-radius);
            box-shadow: inset 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        select {
            appearance: none;
            background-image: url('data:image/svg+xml;utf8,<svg fill="navy" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M7 10l5 5 5-5z"/><path d="M0 0h24v24H0z" fill="none"/></svg>');
            background-repeat: no-repeat;
            background-position: right 8px center;
            padding-right: 30px;
        }

        .button-group {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .btn {
            background-color: var(--button-bg);
            color: var(--button-text);
            font-weight: bold;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            min-width: 120px;
            text-align: center;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            transition: background-color 0.3s, transform 0.2s;
        }

        .btn:hover {
            background-color: var(--button-hover);
            transform: translateY(-2px);
        }

        .btn-link {
            text-decoration: none; /* Supprime le soulignement */
            display: inline-block; /* Permet d'utiliser les styles de bouton */
            color: var(--button-text); /* Couleur du texte du bouton */
            background-color: var(--button-bg); /* Couleur de fond */
            padding: 10px 20px;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            transition: background-color 0.3s, transform 0.2s;
            font-weight: bold; /* Texte en gras */
        }

        .btn-link:hover {
            background-color: var(--button-hover); /* Effet au survol */
            transform: translateY(-2px);
        }

        .notes-area {
            background-color: white;
            height: 350px;
            width: 100%;
            border: 1px solid #ccc;
            border-radius: var(--border-radius);
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
                gap: 10px;
            }

            .button-group {
                flex-direction: column;
                gap: 10px;
            }

            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">NOUVEAU DOSSIER</div>

        <div class="form-grid">
            <div class="left-column">
                <div class="form-field">
                    <label>Date :</label>
                    <input type="date" />
                </div>

                <div class="form-field">
                    <label>Nom :</label>
                    <input type="text" />
                </div>

                <div class="form-field">
                    <label>Sexe :</label>
                    <input type="text" />
                </div>

                <div class="form-field">
                    <label>Age :</label>
                    <input type="text" placeholder="....... ANS" />
                </div>

                <div class="form-field">
                    <label>Contact :</label>
                    <input type="text" placeholder=".-.-.-.-.-" />
                </div>

                <div class="form-field">
                    <label>Niveau d'étude :</label>
                    <select>
                        <option value="" selected disabled>Sélectionnez...</option>
                        <option value="aucun">Aucun</option>
                        <option value="primaire">Primaire</option>
                        <option value="secondaire">Secondaire</option>
                        <option value="universitaire">Universitaire</option>
                    </select>
                </div>
            </div>

            <div class="right-column">
                <div class="form-field">
                    <label>Heure :</label>
                    <input type="time" />
                </div>

                <div class="form-field">
                    <label>Prénom :</label>
                    <input type="text" />
                </div>

                <div class="form-field">
                    <label>Date de naissance :</label>
                    <input type="date" />
                </div>

                <div class="form-field">
                    <label>Lieu de résidence :</label>
                    <input type="text" />
                </div>

                <div class="form-field">
                    <label>Type de logement :</label>
                    <select>
                        <option value="" selected disabled>Sélectionnez...</option>
                        <option value="appartement">Baraquement</option>
                        <option value="maison">Cours commune</option>
                        <option value="studio">Studio</option>
                        <option value="villa">Villa</option>
                        <option value="autre">Autre</option>
                    </select>
                </div>

                <div class="form-field">
                    <label>Profession :</label>
                    <select>
                        <option value="" selected disabled>Sélectionnez...</option>
                        <option value="aucun">Aucun</option>
                        <option value="etudiant">Étudiant</option>
                        <option value="eleve">Élève</option>
                        <option value="corps_habille">Corps habillé</option>
                        <option value="retraite">Retraité</option>
                        <option value="sans_profession">Sans profession</option>
                        <option value="cadre_superieur">Cadre superieur</option>
                        <option value="cadre_moyen">Cadre moyen</option>
                        <option value="sectreur_informel">Secteur informel</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="button-group">
            <button class="btn">Suivie</button>
            <a href="visite.php" class="btn-link">Nouvelle visite</a>
            <button class="btn">Enregistrer</button>
        </div>

        <div class="notes-area"></div>
    </div>
</body>
</html>

<?php
// End the PHP file
?>