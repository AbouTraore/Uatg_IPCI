<?php
// Initialiser les variables pour stocker les données du formulaire
$id = $age = $nom = $prenom = $medecin = '';
$erreur = '';

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $id = $_POST['id'] ?? '';
    $age = $_POST['age'] ?? '';
    $nom = $_POST['nom'] ?? '';
    $prenom = $_POST['prenom'] ?? '';
    $medecin = $_POST['medecin'] ?? '';
    $ecoulement = $_POST['ecoulement'] ?? '';
    $frottis_polynu = $_POST['frottis_polynu'] ?? '';
    $cocci_gram_negatif = $_POST['cocci_gram_negatif'] ?? '';
    $autres_flores = $_POST['autres_flores'] ?? '';
    $culture = $_POST['culture'] ?? '';
    
    // Vérifier si tous les champs requis sont remplis
    if (empty($id) || empty($age) || empty($nom) || empty($prenom) || empty($medecin)) {
        $erreur = "Veuillez remplir tous les champs obligatoires";
    } else {
        // Tous les champs sont remplis, rediriger vers echantillon_male.php
        // Enregistrer les données du formulaire dans la session
        session_start();
        $_SESSION['ecsu_data'] = $_POST;
        
        // Rediriger vers la page suivante
        header("Location: echantillon_male.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Examen Cytobactériologique des Sécrétions Urétrales</title>
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
            --error-color: #e74c3c; /* Red for error messages */
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
            text-align: center;
            letter-spacing: 1px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 1.5em;
            font-weight: bold;
            margin: 0;
            color: var(--button-text);
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

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .full-width {
            grid-column: 1 / -1;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-size: 0.95em;
            color: var(--text-color);
        }

        .required::after {
            content: " *";
            color: var(--error-color);
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

        .error-message {
            background-color: var(--error-color);
            color: white;
            padding: 10px 15px;
            margin: 0 20px 20px;
            border-radius: var(--border-radius);
            text-align: center;
            font-weight: bold;
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

            .header h1 {
                font-size: 1.2em;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>EXAMEN CYTOBACTÉRIOLOGIQUE DES SÉCRÉTIONS URÉTRALES</h1>
        </div>

        <?php if (!empty($erreur)): ?>
            <div class="error-message"><?php echo $erreur; ?></div>
        <?php endif; ?>

        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="section">
                <div class="section-header">Informations Patient</div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="id" class="required">N° Identification</label>
                        <input type="text" id="id" name="id" value="<?php echo $id; ?>" required />
                    </div>

                    <div class="form-group">
                        <label for="age" class="required">Âge</label>
                        <input type="text" id="age" name="age" value="<?php echo $age; ?>" required />
                    </div>

                    <div class="form-group">
                        <label for="nom" class="required">Nom</label>
                        <input type="text" id="nom" name="nom" value="<?php echo $nom; ?>" required />
                    </div>

                    <div class="form-group">
                        <label for="prenom" class="required">Prénom</label>
                        <input type="text" id="prenom" name="prenom" value="<?php echo $prenom; ?>" required />
                    </div>

                    <div class="form-group full-width">
                        <label for="medecin" class="required">Médecin prescripteur</label>
                        <input type="text" id="medecin" name="medecin" value="<?php echo $medecin; ?>" required />
                    </div>
                </div>
            </div>

            <div class="section">
                <div class="section-header">Examens Macroscopiques</div>

                <div class="form-group">
                    <label for="ecoulement">Écoulement</label>
                    <select id="ecoulement" name="ecoulement">
                        <option value="absence">Absence</option>
                        <option value="presence">Présence</option>
                    </select>
                </div>
            </div>

            <div class="section">
                <div class="section-header">Examens Microscopiques</div>

                <div class="form-group">
                    <label for="frottis_polynu">Sécrétions urétrales frottis coloré au Gram - polynucléaire</label>
                    <select id="frottis_polynu" name="frottis_polynu">
                        <option value="<5/champs">&lt; 5/champs</option>
                        <option value=">5/champs">&gt; 5/champs</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="cocci_gram_negatif">Sécrétions urétrales frottis coloré au Gram - cocci Gram négatif</label>
                    <select id="cocci_gram_negatif" name="cocci_gram_negatif">
                        <option value="absence">Absence</option>
                        <option value="presence">Présence</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="autres_flores">Sécrétions urétrales frottis coloré au Gram - Autres flores</label>
                    <select id="autres_flores" name="autres_flores">
                        <option value="absence">Absence</option>
                        <option value="presence">Présence</option>
                    </select>
                </div>
            </div>

            <div class="section">
                <div class="section-header">Culture</div>

                <div class="form-group">
                    <label for="culture">Sécrétion urétrale</label>
                    <select id="culture" name="culture">
                        <option value="negatif">Négatif</option>
                        <option value="positif">Positif</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn">Soumettre</button>
        </form>
    </div>
</body>
</html>