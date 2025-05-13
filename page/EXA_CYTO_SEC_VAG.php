<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>EXAMEN CYTOBACTERIOLOGIQUE DES SECRETIONS  </title>
    <style>
        :root {
            --primary-bg: #f0f5ff;
            --primary-color: #2c4c8c;
            --secondary-color: #5276c1;
            --accent-color: #122952;
            --header-bg: linear-gradient(135deg, #1e3a76 0%, #2c4c8c 100%);
            --text-color: #ffffff;
            --text-dark: #333333;
            --section-bg: #ffffff;
            --section-border: #c8d6e5;
            --input-bg: #ffffff;
            --input-border: #c8d6e5;
            --input-focus: #5276c1;
            --input-text: #333333;
            --button-primary: linear-gradient(135deg, #2c4c8c 0%, #3e5fa6 100%);
            --button-secondary: linear-gradient(135deg, #5276c1 0%, #6d8bd3 100%);
            --button-danger: linear-gradient(135deg, #d63031 0%, #e84393 100%);
            --button-text: #ffffff;
            --button-hover-primary: linear-gradient(135deg, #3e5fa6 0%, #4b6cb7 100%);
            --button-hover-secondary: linear-gradient(135deg, #6d8bd3 0%, #7f99db 100%);
            --button-hover-danger: linear-gradient(135deg, #e84393 0%, #fd79a8 100%);
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

        .form-container {
            padding: 30px;
        }

        .section-title {
            font-size: 1.4em;
            font-weight: 600;
            margin: 30px 0 20px 0;
            padding-bottom: 10px;
            color: var(--primary-color);
            border-bottom: 2px solid var(--secondary-color);
            position: relative;
        }

        .section-title::before {
            content: "";
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 100px;
            height: 2px;
            background-color: var(--accent-color);
        }

        .subsection-title {
            font-size: 1.2em;
            font-weight: 600;
            margin: 20px 0 15px 0;
            color: var(--secondary-color);
            padding-left: 15px;
            border-left: 4px solid var(--secondary-color);
        }

        .form-section {
            background-color: var(--section-bg);
            border-radius: var(--border-radius);
            padding: 20px;
            margin-bottom: 25px;
            border: 1px solid var(--section-border);
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-field {
            display: flex;
            flex-direction: column;
            margin-bottom: 15px;
        }

        label {
            font-size: 1em;
            font-weight: 500;
            margin-bottom: 8px;
            color: var(--primary-color);
        }

        input,
        select {
            background-color: var(--input-bg);
            color: var(--input-text);
            border: 1px solid var(--input-border);
            padding: 12px 15px;
            font-size: 1em;
            border-radius: var(--border-radius);
            transition: all var(--transition-speed);
        }

        input:focus,
        select:focus {
            outline: none;
            border-color: var(--input-focus);
            box-shadow: 0 0 0 3px rgba(82, 118, 193, 0.25);
        }

        select {
            appearance: none;
            background-image: url('data:image/svg+xml;utf8,<svg fill="%232c4c8c" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M7 10l5 5 5-5z"/><path d="M0 0h24v24H0z" fill="none"/></svg>');
            background-repeat: no-repeat;
            background-position: right 12px center;
            padding-right: 40px;
        }

        .button-group {
            display: flex;
            justify-content: space-between;
            gap: 15px;
            margin: 30px 0 10px 0;
        }

        .btn {
            font-weight: 600;
            padding: 12px 25px;
            border: none;
            cursor: pointer;
            min-width: 120px;
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

        .btn-primary:hover {
            background: var(--button-hover-primary);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(44, 76, 140, 0.3);
        }

        .btn-secondary:hover {
            background: var(--button-hover-secondary);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(82, 118, 193, 0.3);
        }

        .btn-danger:hover {
            background: var(--button-hover-danger);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(231, 76, 60, 0.3);
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: var(--border-radius);
            font-weight: 500;
        }

        .alert-success {
            background-color: var(--success-bg);
            color: white;
        }

        .full-width {
            grid-column: 1 / span 2;
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }

            .full-width {
                grid-column: 1;
            }

            .button-group {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }

            .header {
                font-size: 1.3em;
                padding: 20px 15px;
            }

            .form-container {
                padding: 20px 15px;
            }
        }
    </style>
</head>
<body>
    <?php
    // Initialiser les variables
    $message = '';
    $medecin = '';
    
    // Traiter le formulaire si soumis
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Récupérer les données du formulaire
        $medecin = $_POST['medecin'] ?? '';
        
        // Traitement des données (peut être ajouté selon les besoins)
        
        // Message de confirmation
        $message = "Formulaire soumis avec succès !";
    }
    ?>

    <div class="container">
        <div class="header">EXAMEN CYTOBACTERIOLOGIQUE DES SECRETIONS CERVICO VAGINALES</div>
        
        <div class="form-container">
            <?php if (!empty($message)): ?>
                <div class="alert alert-success">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>
            
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="form-section">
                    <div class="form-field">
                        <label for="medecin">Médecin prescripteur :</label>
                        <input type="text" id="medecin" name="medecin" value="<?php echo $medecin; ?>" required>
                    </div>
                </div>
                
                <div class="section-title">Examens macroscopiques</div>
                <div class="form-section">
                    <div class="form-grid">
                        <div class="form-field">
                            <label for="muqueuse_vaginale">Muqueuse vaginale :</label>
                            <select name="muqueuse_vaginale" id="muqueuse_vaginale">
                                <option value="Normale">Normale</option>
                                <option value="Abondante">Abondante</option>
                            </select>
                        </div>
                        
                        <div class="form-field">
                            <label for="ecoulement_vaginal">Écoulement vaginal :</label>
                            <select name="ecoulement_vaginal" id="ecoulement_vaginal">
                                <option value="OUI">OUI</option>
                                <option value="NON">NON</option>
                            </select>
                        </div>
                        
                        <div class="form-field">
                            <label for="abondance">Abondance :</label>
                            <select name="abondance" id="abondance">
                                <option value="Minime">Minime</option>
                                <option value="Moyenne">Moyenne</option>
                                <option value="Abondante">Abondante</option>
                            </select>
                        </div>
                        
                        <div class="form-field">
                            <label for="aspect">Aspect :</label>
                            <select name="aspect" id="aspect">
                                <option value="Crémeux">Crémeux</option>
                                <option value="Caillebotté">Caillebotté</option>
                                <option value="Spumeux">Spumeux</option>
                                <option value="Fluide">Fluide</option>
                            </select>
                        </div>
                        
                        <div class="form-field">
                            <label for="odeur">Odeur :</label>
                            <select name="odeur" id="odeur">
                                <option value="Inodore">Inodore</option>
                                <option value="Mal-odeur">Mal-odeur</option>
                            </select>
                        </div>
                        
                        <div class="form-field">
                            <label for="couleur">Couleur :</label>
                            <select name="couleur" id="couleur">
                                <option value="Blanchtre">Blanchâtre</option>
                                <option value="Grisatre">Grisâtre</option>
                                <option value="Jaunatre">Jaunâtre</option>
                                <option value="Autre">Autre</option>
                            </select>
                        </div>
                        
                        <div class="form-field">
                            <label for="test_potasse">Test à la potasse :</label>
                            <select name="test_potasse" id="test_potasse">
                                <option value="Positif">Positif</option>
                                <option value="Négatif">Négatif</option>
                            </select>
                        </div>
                        
                        <div class="form-field">
                            <label for="ph">Ph :</label>
                            <input type="text" id="ph" name="ph">
                        </div>
                        
                        <div class="form-field">
                            <label for="exocol">Exocol :</label>
                            <select name="exocol" id="exocol">
                                <option value="NORMALE">NORMALE</option>
                                <option value="ANORMALE">ANORMALE</option>
                            </select>
                        </div>
                        
                        <div class="form-field">
                            <label for="ecoulement_cervical">Écoulement cervical :</label>
                            <select name="ecoulement_cervical" id="ecoulement_cervical">
                                <option value="ABSENT">ABSENT</option>
                                <option value="CLAIRE">CLAIRE</option>
                                <option value="LOUCHE">LOUCHE</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="section-title">Examens microscopiques</div>
                <div class="form-section">
                    <div class="subsection-title">Sécrétions vaginales examen à l'état frais</div>
                    <div class="form-grid">
                        <div class="form-field">
                            <label for="cellules_epitheliales">Cellules épithéliales :</label>
                            <input type="text" id="cellules_epitheliales" name="cellules_epitheliales">
                        </div>
                        
                        <div class="form-field">
                            <label for="trichomonas">Trichomonas vaginalis :</label>
                            <select name="trichomonas" id="trichomonas">
                                <option value="Abscence">Absence</option>
                                <option value="Présence">Présence</option>
                            </select>
                        </div>
                        
                        <div class="form-field">
                            <label for="leucocytes">Leucocytes :</label>
                            <select name="leucocytes" id="leucocytes">
                                <option value="<05/champ"><05/champ</option>
                                <option value=">05/champ">>05/champ</option>
                            </select>
                        </div>
                        
                        <div class="form-field">
                            <label for="levure">Levure et/ou filaments mycéliens :</label>
                            <select name="levure" id="levure">
                                <option value="Abscence">Absence</option>
                                <option value="Précence">Présence</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="subsection-title">Sécrétions vaginales frottis coloré au gram</div>
                    <div class="form-grid">
                        <div class="form-field">
                            <label for="polynucleaires">Polynucléaires :</label>
                            <input type="text" id="polynucleaires" name="polynucleaires">
                        </div>
                        
                        <div class="form-field">
                            <label for="flore_vaginale">Flore vaginale :</label>
                            <select name="flore_vaginale" id="flore_vaginale">
                                <option value="Normal type I">Normal type I</option>
                                <option value="Normal type II">Normal type II</option>
                            </select>
                        </div>
                        
                        <div class="form-field">
                            <label for="clue_cells">Clue cells :</label>
                            <select name="clue_cells" id="clue_cells">
                                <option value="Abscence">Absence</option>
                                <option value="Précence">Présence</option>
                            </select>
                        </div>
                        
                        <div class="form-field">
                            <label for="mobiluncus">Mobiluncus :</label>
                            <select name="mobiluncus" id="mobiluncus">
                                <option value="Abscence">Absence</option>
                                <option value="Précence">Présence</option>
                            </select>
                        </div>
                        
                        <div class="form-field">
                            <label for="score">Score :</label>
                            <input type="text" id="score" name="score">
                        </div>
                    </div>
                    
                    <div class="subsection-title">Sécrétions endocervicales</div>
                    <div class="form-grid">
                        <div class="form-field">
                            <label for="polynucleaires_endo">Polynucléaires :</label>
                            <select name="polynucleaires_endo" id="polynucleaires_endo">
                                <option value="<05/champ"><05/champ</option>
                                <option value=">05/champ">>05/champ</option>
                            </select>
                        </div>
                        
                        <div class="form-field">
                            <label for="lymphocytes">Lymphocytes :</label>
                            <input type="text" id="lymphocytes" name="lymphocytes">
                        </div>
                    </div>
                </div>
                
                <div class="section-title">Cultures</div>
                <div class="form-section">
                    <div class="form-grid">
                        <div class="form-field">
                            <label for="secretions_vaginales">Sécrétions vaginales :</label>
                            <select name="secretions_vaginales" id="secretions_vaginales">
                                <option value="Absence de colonie de levure">Absence de colonie de levure</option>
                                <option value="Présence de colonie de levure">Présence de colonie de levure</option>
                            </select>
                        </div>
                        
                        <div class="form-field">
                            <label for="secretions_cervicales">Sécrétions cervicales :</label>
                            <select name="secretions_cervicales" id="secretions_cervicales">
                                <option value="Absence de colonie sur les milieux usuels">Absence de colonie sur les milieux usuels</option>
                                <option value="Présence de colonie sur les milieux usuels">Présence de colonie sur les milieux usuels</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="button-group">
                    <button type="button" class="btn btn-secondary" onclick="window.history.back()">Retour</button>
                    <button type="reset" class="btn btn-danger">Réinitialiser</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>