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

// Affichage des messages d'alerte Bootstrap si besoin
if (isset($_GET['success']) && $_GET['success'] == '1') {
    echo '<div class="alert alert-success alert-dismissible fade show mt-4" role="alert">
            <strong>Succès !</strong> Les informations du patient ont été modifiées avec succès.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
          </div>';
}
if (isset($_GET['error'])) {
    $msg = htmlspecialchars($_GET['error']);
    echo '<div class="alert alert-danger alert-dismissible fade show mt-4" role="alert">
            <strong>Erreur !</strong> ' . $msg . '
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
          </div>';
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Modifier Patient</title>
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
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
        }
      body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
            color: var(--gray-800);
        }
        .container {
            max-width: 700px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.97);
            border-radius: 24px;
            box-shadow: var(--shadow-xl);
            overflow: hidden;
            padding: 0 0 32px 0;
        }
        .header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: white;
            padding: 32px 32px 24px 32px;
            text-align: center;
            position: relative;
        }
        .header h1 {
            font-size: 2rem;
            font-weight: 700;
            margin: 0;
        }
        .header p {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-top: 8px;
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
        .form-input:disabled {
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
            box-shadow: var(--shadow-xl);
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
        }
        @media (max-width: 768px) {
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
      }
  </style>
</head>
<body>
    <div class="container">
          <div class="header">
            <button onclick="window.history.back()" class="btn-retour"><i class="fas fa-arrow-left"></i> Retour</button>
            <h1><i class="fas fa-user-edit"></i> Modifier Patient</h1>
            <p>Modifiez les informations du patient</p>
          </div>
        <div class="content-area">
            <div id="formMessage" style="display:none;max-width:600px;margin:32px auto 0 auto;padding:16px 24px;border-radius:12px;font-weight:600;font-size:1.05rem;text-align:center;transition:opacity 0.3s;opacity:0;"></div>
            <form method="POST" action="update_patient.php">
                <div class="form-section">
                    <h2 class="section-title"><i class="fas fa-user"></i> Informations personnelles</h2>
                    <div class="form-grid">
                        <div class="form-field">
                            <label class="form-label">N° Urap</label>
                            <input type="text" class="form-input" id="N_Urap" name="N_Urap_display" value="<?php echo $N_Urap ?>" readonly>
                    <input type="hidden" name="N_Urap" value="<?php echo $N_Urap ?>">
                      </div>
                        <div class="form-field">
                            <label class="form-label">Nom</label>
                            <input type="text" class="form-input" id="Nom" name="Nom" value="<?php echo $Nom ?>" required>
                      </div>
                        <div class="form-field">
                            <label class="form-label">Prénom</label>
                            <input type="text" class="form-input" id="Prenom" name="Prenom" value="<?php echo $Prenom ?>" required>
                      </div>
                        <div class="form-field">
                            <label class="form-label">Date de naissance</label>
                            <input type="date" class="form-input" id="datenaiss" name="datenaiss" value="<?php echo $datenaiss ?>" required>
                    </div>
                        <div class="form-field">
                            <label class="form-label">Âge</label>
                            <input type="text" class="form-input" id="Age" name="Age" value="<?php echo $Age ?>" required readonly>
                      </div>
                        <div class="form-field">
                            <label class="form-label">Sexe</label>
                            <select class="form-select" id="SexeP" name="SexeP">
                                <option value="Masculin" <?php if($SexeP=="Masculin") echo "selected"; ?>>Masculin</option>
                                <option value="Féminin" <?php if($SexeP=="Féminin") echo "selected"; ?>>Féminin</option>
                        </select>
                      </div>
                    </div>
                      </div>
                <div class="form-section">
                    <h2 class="section-title"><i class="fas fa-address-card"></i> Contact et adresse</h2>
                    <div class="form-grid">
                        <div class="form-field">
                            <label class="form-label">Contact</label>
                            <input type="tel" class="form-input" id="contact" name="contact" value="<?php echo $contact ?>" required>
                      </div>
                        <div class="form-field">
                            <label class="form-label">Adresse</label>
                            <input type="text" class="form-input" id="Adresse" name="Adresse" value="<?php echo $Adresse ?>" required>
                    </div>
                        <div class="form-field">
                            <label class="form-label">Lieu de résidence</label>
                            <select class="form-select" id="reside" name="reside">
                                <option value="Abidjan" <?php if($reside=="Abidjan") echo "selected"; ?>>Abidjan</option>
                                <option value="Hors Abidjan" <?php if($reside=="Hors Abidjan") echo "selected"; ?>>Hors Abidjan</option>
                        </select>
                      </div>
                        <div class="form-field">
                            <label class="form-label">Précisez le lieu</label>
                            <input type="text" class="form-input" id="Precise" name="Precise" value="<?php echo $Precise ?>">
                      </div>
                    </div>
                </div>
                <div class="form-section">
                    <h2 class="section-title"><i class="fas fa-info-circle"></i> Informations sociales</h2>
                    <div class="form-grid">
                        <div class="form-field">
                            <label class="form-label">Situation matrimoniale</label>
                            <select class="form-select" id="SituaM" name="SituaM">
                                <option value="Célibataire" <?php if($SituaM=="Célibataire") echo "selected"; ?>>Célibataire</option>
                                <option value="Marié" <?php if($SituaM=="Marié") echo "selected"; ?>>Marié(e)</option>
                                <option value="Divorcé" <?php if($SituaM=="Divorcé") echo "selected"; ?>>Divorcé(e)</option>
                                <option value="Veuve" <?php if($SituaM=="Veuve") echo "selected"; ?>>Veuf/Veuve</option>
                            </select>
                        </div>
                        <div class="form-field">
                            <label class="form-label">Type de logement</label>
                            <select class="form-select" id="Type_log" name="Type_log">
                                <option value="Studio" <?php if($Type_log=="Studio") echo "selected"; ?>>Studio</option>
                                <option value="Cour commune" <?php if($Type_log=="Cour commune") echo "selected"; ?>>Cour commune</option>
                                <option value="Villa" <?php if($Type_log=="Villa") echo "selected"; ?>>Villa</option>
                                <option value="Baraquement" <?php if($Type_log=="Baraquement") echo "selected"; ?>>Baraquement</option>
                                <option value="Autre" <?php if($Type_log=="Autre") echo "selected"; ?>>Autre</option>
                        </select>
                      </div>
                        <div class="form-field">
                            <label class="form-label">Niveau d'étude</label>
                            <select class="form-select" id="NiveauE" name="NiveauE">
                                <option value="Aucun" <?php if($NiveauE=="Aucun") echo "selected"; ?>>Aucun</option>
                                <option value="Primaire" <?php if($NiveauE=="Primaire") echo "selected"; ?>>Primaire</option>
                                <option value="Secondaire" <?php if($NiveauE=="Secondaire") echo "selected"; ?>>Secondaire</option>
                                <option value="Universitaire" <?php if($NiveauE=="Universitaire") echo "selected"; ?>>Universitaire</option>
                        </select>
                      </div>
                        <div class="form-field">
                            <label class="form-label">Profession</label>
                            <select class="form-select" id="Profession" name="Profession">
                                <option value="Aucun" <?php if($Profession=="Aucun") echo "selected"; ?>>Aucun</option>
                                <option value="Etudiant" <?php if($Profession=="Etudiant") echo "selected"; ?>>Étudiant</option>
                                <option value="Eleve" <?php if($Profession=="Eleve") echo "selected"; ?>>Élève</option>
                                <option value="Corps habillé" <?php if($Profession=="Corps habillé") echo "selected"; ?>>Corps habillé</option>
                                <option value="Cadre superieur" <?php if($Profession=="Cadre superieur") echo "selected"; ?>>Cadre supérieur</option>
                                <option value="Cadre moyen" <?php if($Profession=="Cadre moyen") echo "selected"; ?>>Cadre moyen</option>
                                <option value="Secteur informel" <?php if($Profession=="Secteur informel") echo "selected"; ?>>Secteur informel</option>
                                <option value="Sans profession" <?php if($Profession=="Sans profession") echo "selected"; ?>>Sans profession</option>
                                <option value="Retraité" <?php if($Profession=="Retraité") echo "selected"; ?>>Retraité</option>
                        </select>
                      </div>
                    </div>
                    </div>
                <div class="actions">
                    <a href="Liste_patient.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Retour</a>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Enregistrer les modifications</button>
                </div>
            </form>
      </div>
    </div>
  <div id="saveMessage"></div>
  <script>
    // Calcul automatique de l'âge
    document.addEventListener('DOMContentLoaded', function() {
        const dateInput = document.getElementById('datenaiss');
        const ageInput = document.getElementById('Age');
        function calculateAge() {
            if (dateInput.value) {
                const birthDate = new Date(dateInput.value);
                const today = new Date();
                let age = today.getFullYear() - birthDate.getFullYear();
                const monthDiff = today.getMonth() - birthDate.getMonth();
                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                    age--;
                }
                ageInput.value = isNaN(age) || age < 0 ? '' : age;
            }
        }
        if (dateInput && ageInput) {
            dateInput.addEventListener('input', calculateAge);
            calculateAge();
        }
    });

    // Validation et message stylisé
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const msgDiv = document.getElementById('formMessage');
        form.addEventListener('submit', function(e) {
            let error = '';
            if (!form.Nom.value.trim() || !form.Prenom.value.trim() || !form.datenaiss.value.trim() || !form.SexeP.value.trim() || !form.contact.value.trim()) {
                error = 'Veuillez remplir tous les champs obligatoires.';
            }
            if (error) {
                e.preventDefault();
                msgDiv.innerHTML = '<i class="fas fa-exclamation-triangle" style="color:#ef4444;margin-right:10px;"></i>' + error;
                msgDiv.style.background = '#ffeaea';
                msgDiv.style.color = '#b91c1c';
                msgDiv.style.border = '1.5px solid #fca5a5';
                msgDiv.style.display = 'block';
                msgDiv.style.opacity = '1';
                setTimeout(() => { msgDiv.style.opacity = '0'; }, 3000);
                return false;
            }
        });
    });

    // Affichage notification style nouveau_dossier.php
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
        if(type === 'success') {
            icon = '<i class="fas fa-info-circle" style="color:#2563eb;font-size:1.5em;"></i>';
            title = '<span style="color:#2563eb;font-size:1.1em;margin-right:8px;">Succès !</span>';
            msgDiv.style.background = '#e0edff';
            msgDiv.style.color = '#1e3a8a';
            msgDiv.style.border = '2px solid #60a5fa';
        } else {
            icon = '<i class="fas fa-exclamation-circle" style="color:#ea580c;font-size:1.5em;"></i>';
            title = '<span style="color:#ea580c;font-size:1.1em;margin-right:8px;">Attention !</span>';
            msgDiv.style.background = '#fff7ed';
            msgDiv.style.color = '#9a3412';
            msgDiv.style.border = '2px solid #fdba74';
        }
        msgDiv.innerHTML = icon + title + '<span>' + message + '</span>';
        msgDiv.style.display = 'flex';
        setTimeout(() => { msgDiv.style.opacity = '1'; }, 10);
        window.scrollTo({ top: 0, behavior: 'smooth' });
        setTimeout(() => {
            msgDiv.style.opacity = '0';
            setTimeout(() => { msgDiv.style.display = 'none'; }, 300);
        }, 2000);
    }

    // Si succès, afficher notification et rediriger
    var successAlert = document.querySelector('.alert-success');
    if (successAlert) {
        showMessage('Les informations du patient ont été modifiées avec succès.', 'success');
        setTimeout(function() {
            window.location.href = 'nouveau_dossier.php';
        }, 2000);
    }
  </script>
</body>
</html>