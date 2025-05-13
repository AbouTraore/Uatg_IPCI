<?php
// Start the PHP file
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Examen Cytobactériologique du Sperme</title>
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
            max-width: 800px;
            background-color: var(--primary-bg);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding-bottom: 30px;
            margin: 20px auto;
        }

        .header {
            background-color: var(--header-bg);
            padding: 20px;
            font-size: 1.5em;
            font-weight: bold;
            text-align: center;
            color: var(--button-text);
            letter-spacing: 1px;
            margin-bottom: 20px;
        }

        .section {
            background-color: var(--primary-bg);
            margin: 0 20px 20px;
            padding: 20px;
            border-radius: var(--border-radius);
            box-shadow: var(--section-shadow);
        }

        .section-header {
            font-size: 1.2em;
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
            color: var(--text-color);
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-size: 0.95em;
            color: var(--text-color);
        }

        select,
        input[type="text"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #0047ab; /* Dark blue border */
            border-radius: 4px;
            background-color: var(--input-bg);
            color: var(--input-text);
            font-size: 1em;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.3s ease;
        }

        select:focus,
        input[type="text"]:focus {
            outline: none;
            border-color: var(--button-hover); /* Lighter blue on focus */
            box-shadow: 0 0 0 2px rgba(30, 144, 255, 0.3);
        }

        .btn {
            display: block;
            width: 200px;
            margin: 30px auto 0;
            padding: 12px 20px;
            background-color: var(--button-bg);
            color: var(--button-text);
            border: none;
            border-radius: 6px;
            font-size: 1em;
            font-weight: bold;
            cursor: pointer;
            text-align: center;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.2s ease;
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
        @media (max-width: 768px) {
            .container {
                padding: 0 0 20px 0;
                margin: 0;
                border-radius: 0;
            }

            .section {
                margin: 0 10px 15px;
                padding: 15px;
            }

            .btn {
                width: 80%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">EXAMEN CYTOBACTÉRIOLOGIQUE DU SPERME</div>

        <form>
            <div class="section">
                <div class="section-header">Informations Patient</div>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="id">N° Identification</label>
                        <input type="text" id="id" name="id" required />
                    </div>
                    <div class="form-group">
                        <label for="age">Âge</label>
                        <input type="text" id="age" name="age" required />
                    </div>
                    <div class="form-group">
                        <label for="nom">Nom</label>
                        <input type="text" id="nom" name="nom" required />
                    </div>
                    <div class="form-group">
                        <label for="prenom">Prénom</label>
                        <input type="text" id="prenom" name="prenom" required />
                    </div>
                    <div class="form-group full-width">
                        <label for="medecin">Médecin prescripteur</label>
                        <input type="text" id="medecin" name="medecin" required />
                    </div>
                </div>
            </div>

            <div class="section">
                <div class="section-header">Examens Macroscopiques</div>
                <div class="form-group">
                    <label for="couleur">Couleur</label>
                    <select id="couleur" name="couleur">
                        <option value="blanchatre">Blanchâtre</option>
                        <option value="grisatre">Grisâtre</option>
                        <option value="jaunatre">Jaunâtre</option>
                        <option value="autre">Autre</option>
                    </select>
                </div>
            </div>

            <div class="section">
                <div class="section-header">Examens Microscopiques</div>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="nombre_leucocyte">Nombre de leucocytes</label>
                        <select id="nombre_leucocyte" name="nombre_leucocyte">
                            <option value="<5">&lt; 5</option>
                            <option value=">5">&gt; 5</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="spermatozoide">Spermatozoïdes</label>
                        <select id="spermatozoide" name="spermatozoide">
                            <option value="moyen">Moyen</option>
                            <option value="nombreux">Nombreux</option>
                            <option value="tres nombreux">Très nombreux</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="mobilite">Mobilité</label>
                        <input type="text" id="mobilite" name="mobilite" required />
                    </div>
                    <div class="form-group">
                        <label for="parasite">Parasite</label>
                        <select id="parasite" name="parasite">
                            <option value="absence">Absence</option>
                            <option value="presence">Présence</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="cristaux">Cristaux</label>
                        <select id="cristaux" name="cristaux">
                            <option value="absence">Absence</option>
                            <option value="presence">Présence</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="culture">Culture</label>
                        <select id="culture" name="culture">
                            <option value="negative">Négative</option>
                            <option value="positive">Positive</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="especes_bacteriennes">Espèces bactériennes isolées</label>
                        <select id="especes_bacteriennes" name="especes_bacteriennes">
                            <option value="absence">Absence</option>
                            <option value="presence">Présence</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="titre">Titre</label>
                        <input type="text" id="titre" name="titre" required />
                    </div>
                </div>
            </div>

            <div class="section">
                <div class="section-header">Analyse et Conclusion</div>
                <div class="form-group">
                    <label for="compte_rendu">Compte Rendu d'Analyse</label>
                    <input type="text" id="compte_rendu" name="compte_rendu" required />
                </div>
            </div>

            <button type="submit" class="btn">Soumettre</button>
        </form>
    </div>
</body>
</html>

<?php
// End the PHP file
?>