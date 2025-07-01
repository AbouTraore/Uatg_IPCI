<?php
// Inclusion des fichiers de sécurité et de connexion à la base de données
require_once("identifier.php");
require_once("connexion.php");

// Récupérer l'identifiant du patient à modifier depuis l'URL (GET)
$Numero_urap = isset($_GET['idU']) ? $_GET['idU'] : null;
if (!$Numero_urap) {
    // Si aucun identifiant n'est fourni, afficher un message d'erreur et arrêter le script
    echo '<div class="alert alert-danger">Aucun patient sélectionné.</div>';
    exit();
}

// Préparer et exécuter la requête pour récupérer les informations du patient
$stmt = $pdo->prepare("SELECT * FROM patient WHERE Numero_urap = ?");
$stmt->execute([$Numero_urap]);
$patient = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$patient) {
    // Si le patient n'existe pas, afficher un message d'erreur et arrêter le script
    echo '<div class="alert alert-danger">Patient introuvable.</div>';
    exit();
}

// Préparer les variables pour pré-remplir le formulaire avec les données du patient
$N_Urap = htmlspecialchars($patient['Numero_urap']); // Clé primaire, non modifiable
$Nom = htmlspecialchars($patient['Nom_patient']);
$Prenom = htmlspecialchars($patient['Prenom_patient']);
$Age = htmlspecialchars($patient['Age']);
$SexeP = htmlspecialchars($patient['Sexe_patient']);
$datenaiss = htmlspecialchars($patient['Date_naissance']);
$contact = htmlspecialchars($patient['Contact_patient']);
$SituaM = htmlspecialchars($patient['Situation_matrimoniale']);
$reside = htmlspecialchars($patient['Lieu_résidence']);
$Precise = htmlspecialchars($patient['Precise']);
$Type_log = htmlspecialchars($patient['Type_logement']);
$NiveauE = htmlspecialchars($patient['Niveau_etude']);
$Profession = htmlspecialchars($patient['Profession']);
$Adresse = isset($patient['Adresse']) ? htmlspecialchars($patient['Adresse']) : '';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Modifier Patient - UATG</title>
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
            max-width: 1200px;
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
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }

        .form-field {
            position: relative;
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--gray-700);
            margin-bottom: 6px;
        }

        .form-input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid var(--gray-200);
            border-radius: 12px;
            font-size: 14px;
            transition: all 0.2s ease;
            background: white;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(0, 71, 171, 0.1);
        }

        .form-input:disabled,
        .form-input[readonly] {
            background: var(--gray-50);
            color: var(--gray-500);
        }

        .form-select {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid var(--gray-200);
            border-radius: 12px;
            font-size: 14px;
            background: white;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .form-select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(0, 71, 171, 0.1);
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

        .alert-danger {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger);
            border: 1px solid rgba(239, 68, 68, 0.2);
            border-left: 4px solid var(--danger);
        }

        .alert-danger::before {
            content: "\f071";
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

            .form-grid {
                grid-template-columns: 1fr;
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
</head>
<body>
    <div class="container">
        <div class="header">
            <button onclick="window.history.back()" class="btn-retour">
                <i class="fas fa-arrow-left"></i> Retour
            </button>
            <h1><i class="fas fa-user-edit"></i> Modifier Patient</h1>
            <p>Modifiez les informations du patient <?php echo $Nom . ' ' . $Prenom; ?></p>
        </div>

        <div class="content-area">
            <!-- Messages de notification -->
            <?php 
            if (isset($_GET['success']) && $_GET['success'] == '1') {
                echo '<div class="alert alert-success">Les informations du patient ont été modifiées avec succès.</div>';
            }
            if (isset($_GET['error'])) {
                $msg = htmlspecialchars($_GET['error']);
                echo '<div class="alert alert-danger">' . $msg . '</div>';
            }
            ?>

            <form method="POST" action="update_patient.php" id="patientForm">
                <!-- Section 1: Informations personnelles -->
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-user"></i>
                        Informations personnelles
                    </h2>
                    <div class="form-grid">
                        <div class="form-field">
                            <label class="form-label">N° Urap</label>
                            <input type="text" class="form-input" value="<?php echo $N_Urap; ?>" readonly>
                            <input type="hidden" name="N_Urap" value="<?php echo $N_Urap; ?>">
                        </div>

                        <div class="form-field">
                            <label class="form-label">Nom *</label>
                            <input type="text" class="form-input" name="Nom" value="<?php echo $Nom; ?>" required>
                        </div>

                        <div class="form-field">
                            <label class="form-label">Prénom *</label>
                            <input type="text" class="form-input" name="Prenom" value="<?php echo $Prenom; ?>" required>
                        </div>

                        <div class="form-field">
                            <label class="form-label">Date de naissance *</label>
                            <input type="date" class="form-input" id="datenaiss" name="datenaiss" value="<?php echo $datenaiss; ?>" required>
                        </div>

                        <div class="form-field">
                            <label class="form-label">Âge</label>
                            <input type="text" class="form-input" id="Age" name="Age" value="<?php echo $Age; ?>" readonly>
                        </div>

                        <div class="form-field">
                            <label class="form-label">Sexe *</label>
                            <select class="form-select" name="SexeP" required>
                                <option value="Masculin" <?php if($SexeP == "Masculin") echo "selected"; ?>>Masculin</option>
                                <option value="Féminin" <?php if($SexeP == "Féminin") echo "selected"; ?>>Féminin</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Contact et adresse -->
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-address-card"></i>
                        Contact et adresse
                    </h2>
                    <div class="form-grid">
                        <div class="form-field">
                            <label class="form-label">Contact *</label>
                            <input type="tel" class="form-input" name="contact" value="<?php echo $contact; ?>" required>
                        </div>

                        <div class="form-field">
                            <label class="form-label">Adresse *</label>
                            <input type="text" class="form-input" name="Adresse" value="<?php echo $Adresse; ?>" required>
                        </div>

                        <div class="form-field">
                            <label class="form-label">Lieu de résidence</label>
                            <select class="form-select" id="reside" name="reside">
                                <option value="Abidjan" <?php if($reside == "Abidjan") echo "selected"; ?>>Abidjan</option>
                                <option value="Hors Abidjan" <?php if($reside == "Hors Abidjan") echo "selected"; ?>>Hors Abidjan</option>
                            </select>
                        </div>

                        <div class="form-field">
                            <label class="form-label">Précisez le lieu</label>
                            <input type="text" class="form-input" id="Precise" name="Precise" value="<?php echo $Precise; ?>" placeholder="Commune, quartier...">
                        </div>
                    </div>
                </div>

                <!-- Section 3: Informations sociales -->
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-info-circle"></i>
                        Informations sociales
                    </h2>
                    <div class="form-grid">
                        <div class="form-field">
                            <label class="form-label">Situation matrimoniale</label>
                            <select class="form-select" name="SituaM">
                                <option value="Célibataire" <?php if($SituaM == "Célibataire") echo "selected"; ?>>Célibataire</option>
                                <option value="Marié" <?php if($SituaM == "Marié") echo "selected"; ?>>Marié(e)</option>
                                <option value="Divorcé" <?php if($SituaM == "Divorcé") echo "selected"; ?>>Divorcé(e)</option>
                                <option value="Veuve" <?php if($SituaM == "Veuve") echo "selected"; ?>>Veuf/Veuve</option>
                            </select>
                        </div>

                        <div class="form-field">
                            <label class="form-label">Type de logement</label>
                            <select class="form-select" name="Type_log">
                                <option value="Studio" <?php if($Type_log == "Studio") echo "selected"; ?>>Studio</option>
                                <option value="Cour commune" <?php if($Type_log == "Cour commune") echo "selected"; ?>>Cour commune</option>
                                <option value="Villa" <?php if($Type_log == "Villa") echo "selected"; ?>>Villa</option>
                                <option value="Baraquement" <?php if($Type_log == "Baraquement") echo "selected"; ?>>Baraquement</option>
                                <option value="Autre" <?php if($Type_log == "Autre") echo "selected"; ?>>Autre</option>
                            </select>
                        </div>

                        <div class="form-field">
                            <label class="form-label">Niveau d'étude</label>
                            <select class="form-select" name="NiveauE">
                                <option value="Aucun" <?php if($NiveauE == "Aucun") echo "selected"; ?>>Aucun</option>
                                <option value="Primaire" <?php if($NiveauE == "Primaire") echo "selected"; ?>>Primaire</option>
                                <option value="Secondaire" <?php if($NiveauE == "Secondaire") echo "selected"; ?>>Secondaire</option>
                                <option value="Universitaire" <?php if($NiveauE == "Universitaire") echo "selected"; ?>>Universitaire</option>
                            </select>
                        </div>

                        <div class="form-field">
                            <label class="form-label">Profession</label>
                            <select class="form-select" name="Profession">
                                <option value="Aucun" <?php if($Profession == "Aucun") echo "selected"; ?>>Aucun</option>
                                <option value="Etudiant" <?php if($Profession == "Etudiant") echo "selected"; ?>>Étudiant</option>
                                <option value="Eleve" <?php if($Profession == "Eleve") echo "selected"; ?>>Élève</option>
                                <option value="Corps habillé" <?php if($Profession == "Corps habillé") echo "selected"; ?>>Corps habillé</option>
                                <option value="Cadre superieur" <?php if($Profession == "Cadre superieur") echo "selected"; ?>>Cadre supérieur</option>
                                <option value="Cadre moyen" <?php if($Profession == "Cadre moyen") echo "selected"; ?>>Cadre moyen</option>
                                <option value="Secteur informel" <?php if($Profession == "Secteur informel") echo "selected"; ?>>Secteur informel</option>
                                <option value="Sans profession" <?php if($Profession == "Sans profession") echo "selected"; ?>>Sans profession</option>
                                <option value="Retraité" <?php if($Profession == "Retraité") echo "selected"; ?>>Retraité</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="actions">
                    <a href="Liste_patient.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                        Retour à la liste
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Fonction pour gérer le champ "Precise"
        function togglePreciseField() {
            const selectReside = document.getElementById("reside");
            const inputPrecise = document.getElementById("Precise");

            if (selectReside && inputPrecise) {
                if (selectReside.value === "Abidjan") {
                    inputPrecise.disabled = true;
                    inputPrecise.value = "";
                    inputPrecise.style.backgroundColor = "var(--gray-50)";
                } else {
                    inputPrecise.disabled = false;
                    inputPrecise.style.backgroundColor = "";
                }
            }
        }

        // Fonction pour calculer l'âge
        function calculateAge() {
            const dateInput = document.getElementById("datenaiss");
            const ageInput = document.getElementById("Age");
            
            if (dateInput && ageInput && dateInput.value) {
                const birthDate = new Date(dateInput.value);
                const today = new Date();
                let age = today.getFullYear() - birthDate.getFullYear();
                const monthDiff = today.getMonth() - birthDate.getMonth();
                
                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                    age--;
                }
                
                ageInput.value = isNaN(age) || age < 0 ? "" : age;

                // Animation pour l'âge calculé
                if (age >= 0) {
                    ageInput.style.color = 'var(--accent)';
                    ageInput.style.fontWeight = '600';
                    setTimeout(() => {
                        ageInput.style.color = '';
                        ageInput.style.fontWeight = '';
                    }, 1000);
                }
            }
        }

        // Fonction pour afficher des messages
        function showMessage(message, type = 'success') {
            let msgDiv = document.getElementById('saveMessage');
            if (!msgDiv) {
                msgDiv = document.createElement('div');
                msgDiv.id = 'saveMessage';
                document.body.appendChild(msgDiv);
            }
            
            msgDiv.style.position = 'fixed';
            msgDiv.style.top = '60px';
            msgDiv.style.left = '50%';
            msgDiv.style.transform = 'translateX(-50%)';
            msgDiv.style.zIndex = '9999';
            msgDiv.style.minWidth = '340px';
            msgDiv.style.maxWidth = '700px';
            msgDiv.style.display = 'flex';
            msgDiv.style.alignItems = 'center';
            msgDiv.style.justifyContent = 'center';
            msgDiv.style.gap = '16px';
            msgDiv.style.padding = '20px 40px';
            msgDiv.style.borderRadius = '16px';
            msgDiv.style.fontWeight = 'bold';
            msgDiv.style.fontSize = '1.18rem';
            msgDiv.style.boxShadow = '0 6px 24px rgba(0,0,0,0.10)';
            msgDiv.style.opacity = '0';
            msgDiv.style.transition = 'opacity 0.3s';
            
            let icon = '';
            let title = '';
            if (type === 'success') {
                icon = '<i class="fas fa-check-circle" style="color:#22c55e;font-size:1.5em;"></i>';
                title = '<span style="color:#22c55e;font-size:1.1em;margin-right:8px;">Succès !</span>';
                msgDiv.style.background = '#f0fdf4';
                msgDiv.style.color = '#166534';
                msgDiv.style.border = '2px solid #86efac';
            } else {
                icon = '<i class="fas fa-exclamation-circle" style="color:#ef4444;font-size:1.5em;"></i>';
                title = '<span style="color:#ef4444;font-size:1.1em;margin-right:8px;">Erreur !</span>';
                msgDiv.style.background = '#fef2f2';
                msgDiv.style.color = '#dc2626';
                msgDiv.style.border = '2px solid #fca5a5';
            }
            
            msgDiv.innerHTML = icon + title + '<span>' + message + '</span>';
            msgDiv.style.display = 'flex';
            setTimeout(() => { msgDiv.style.opacity = '1'; }, 10);
            window.scrollTo({ top: 0, behavior: 'smooth' });
            
            setTimeout(() => {
                msgDiv.style.opacity = '0';
                setTimeout(() => { msgDiv.style.display = 'none'; }, 300);
            }, 3000);
        }

        // Initialisation
        document.addEventListener("DOMContentLoaded", function() {
            // Événements
            const resideSelect = document.getElementById("reside");
            const dateInput = document.getElementById("datenaiss");
            
            if (resideSelect) {
                resideSelect.addEventListener("change", togglePreciseField);
                togglePreciseField(); // Initialiser l'état
            }
            
            if (dateInput) {
                dateInput.addEventListener("input", calculateAge);
                calculateAge(); // Calculer l'âge initial
            }

            // Animation d'apparition des éléments
            const formFields = document.querySelectorAll('.form-field');
            formFields.forEach((field, index) => {
                field.style.opacity = '0';
                field.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    field.style.transition = 'all 0.3s ease';
                    field.style.opacity = '1';
                    field.style.transform = 'translateY(0)';
                }, index * 50);
            });

            // Validation du formulaire
            const form = document.getElementById('patientForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const requiredFields = form.querySelectorAll('[required]');
                    let isValid = true;
                    
                    requiredFields.forEach(field => {
                        if (!field.value.trim()) {
                            isValid = false;
                            field.style.borderColor = 'var(--danger)';
                        } else {
                            field.style.borderColor = '';
                        }
                    });
                    
                    if (!isValid) {
                        e.preventDefault();
                        showMessage('Veuillez remplir tous les champs obligatoires.', 'error');
                    }
                });
            }
        });
    </script>
</body>
</html>