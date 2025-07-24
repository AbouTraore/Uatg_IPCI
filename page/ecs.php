<?php
// Connexion à la base de données
require_once("identifier.php");
require_once("connexion.php");

// Initialiser les variables pour stocker les données du formulaire
$id = $age = $nom = $prenom = $medecin = $mobilite = $titre = $compte_rendu = '';
$erreur = '';

// Récupérer le nom du médecin prescripteur depuis l'URL si disponible
if (isset($_GET['medecin'])) {
    $medecin = htmlspecialchars($_GET['medecin']);
}

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $id = $_POST['id'] ?? '';
    $age = $_POST['age'] ?? '';
    $nom = $_POST['nom'] ?? '';
    $prenom = $_POST['prenom'] ?? '';
    $medecin = $_POST['medecin'] ?? '';
    $couleur = $_POST['couleur'] ?? '';
    $nombre_leucocyte = $_POST['nombre_leucocyte'] ?? '';
    $spermatozoide = $_POST['spermatozoide'] ?? '';
    $mobilite = $_POST['mobilite'] ?? '';
    $parasite = $_POST['parasite'] ?? '';
    $cristaux = $_POST['cristaux'] ?? '';
    $culture = $_POST['culture'] ?? '';
    $especes_bacteriennes = $_POST['especes_bacteriennes'] ?? '';
    $titre = $_POST['titre'] ?? '';
    $compte_rendu = $_POST['compte_rendu'] ?? '';

    // Vérifier si tous les champs requis sont remplis
    if (empty($id) || empty($age) || empty($nom) || empty($prenom) || empty($medecin) ||
        empty($mobilite) || empty($titre) || empty($compte_rendu)) {
        $erreur = "Veuillez remplir tous les champs obligatoires";
    } else {
        // Insertion en base
        $sql = "INSERT INTO ecs (
            numero_identification, age, nom, prenom, medecin, couleur, nombre_leucocyte,
            spermatozoide, mobilite, parasite, cristaux, culture, especes_bacteriennes, titre, compte_rendu
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $id, $age, $nom, $prenom, $medecin, $couleur, $nombre_leucocyte,
            $spermatozoide, $mobilite, $parasite, $cristaux, $culture, $especes_bacteriennes, $titre, $compte_rendu
        ]);
        // Redirection JS après succès (pour éviter les problèmes d'en-tête déjà envoyés)
        echo '<script>window.location.href = "echantillon_male.php?urap=' . urlencode($id) . '";</script>';
        echo '<noscript><meta http-equiv="refresh" content="0;url=echantillon_male.php?urap=' . urlencode($id) . '"></noscript>';
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Examen Cytobactériologique du Sperme</title>
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
            max-width: 1000px;
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
            font-size: 2rem;
            font-weight: 700;
            letter-spacing: -0.025em;
            position: relative;
            z-index: 1;
        }

        .header p {
            font-size: 1rem;
            opacity: 0.9;
            margin-top: 8px;
            position: relative;
            z-index: 1;
        }

        .content-area {
            padding: 32px;
            background: white;
        }

        .error-message {
            background: linear-gradient(135deg, var(--danger) 0%, #dc2626 100%);
            color: white;
            padding: 16px 20px;
            margin-bottom: 24px;
            border-radius: 12px;
            text-align: center;
            font-weight: 500;
            box-shadow: var(--shadow);
            animation: shake 0.5s ease-in-out;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        .form-section {
            margin-bottom: 32px;
            background: var(--gray-50);
            border-radius: 16px;
            padding: 24px;
            border: 1px solid var(--gray-200);
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--gray-800);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
            padding-bottom: 12px;
            border-bottom: 2px solid var(--gray-200);
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
            margin-bottom: 8px;
        }

        .required::after {
            content: " *";
            color: var(--danger);
            font-weight: 600;
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

        .full-width {
            grid-column: 1 / -1;
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
        }

        .status-indicator {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--accent);
            display: inline-block;
            margin-right: 8px;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        @media (max-width: 768px) {
            body {
                padding: 10px;
            }

            .header {
                padding: 24px 20px;
            }

            .header h1 {
                font-size: 1.5rem;
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

            .form-section {
                padding: 16px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-microscope"></i> Examen Cytobactériologique du Sperme</h1>
            <p>Formulaire d'examen médical spécialisé</p>
        </div>

        <div class="content-area">
            <?php if (!empty($erreur)): ?>
                <div class="error-message" style="<?php echo ($erreur === 'Enregistrement effectué avec succès !') ? 'background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%); color: white;' : ''; ?>">
                    <i class="fas fa-exclamation-triangle"></i>
                    <?php echo $erreur; ?>
                </div>
            <?php endif; ?>

            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="form-section">
                    <div class="section-title">
                        <i class="fas fa-user"></i>
                        Informations Patient
                    </div>
                    <div class="form-grid">
                        <div class="form-field">
                            <label for="id" class="form-label required">N° Identification</label>
                            <input type="text" id="id" name="id" class="form-input" value="<?php echo htmlspecialchars($id); ?>" required 
                                   placeholder="Entrez le numéro URAP pour auto-remplir" />
                        </div>
                        <div class="form-field">
                            <label for="age" class="form-label required">Âge</label>
                            <input type="text" id="age" name="age" class="form-input" value="<?php echo htmlspecialchars($age); ?>" required readonly />
                        </div>
                        <div class="form-field">
                            <label for="nom" class="form-label required">Nom</label>
                            <input type="text" id="nom" name="nom" class="form-input" value="<?php echo htmlspecialchars($nom); ?>" required readonly />
                        </div>
                        <div class="form-field">
                            <label for="prenom" class="form-label required">Prénom</label>
                            <input type="text" id="prenom" name="prenom" class="form-input" value="<?php echo htmlspecialchars($prenom); ?>" required readonly />
                        </div>
                        <div class="form-field full-width">
                            <label for="medecin" class="form-label required">Médecin prescripteur</label>
                            <input type="text" id="medecin" name="medecin" class="form-input" value="<?php echo htmlspecialchars($medecin); ?>" required />
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="section-title">
                        <i class="fas fa-flask"></i>
                        Examens
                    </div>
                    <div class="form-grid">
                        <div class="form-field">
                            <label for="couleur" class="form-label">Couleur</label>
                            <select id="couleur" name="couleur" class="form-select">
                                <option value="blanchatre">Blanchâtre</option>
                                <option value="grisatre">Grisâtre</option>
                                <option value="jaunatre">Jaunâtre</option>
                                <option value="autre">Autre</option>
                            </select>
                        </div>
                        <div class="form-field">
                            <label for="nombre_leucocyte" class="form-label">Nombre de leucocytes</label>
                            <select id="nombre_leucocyte" name="nombre_leucocyte" class="form-select">
                                <option value="<5">&lt; 5</option>
                                <option value=">5">&gt; 5</option>
                            </select>
                        </div>
                        <div class="form-field">
                            <label for="spermatozoide" class="form-label">Spermatozoïdes</label>
                            <select id="spermatozoide" name="spermatozoide" class="form-select">
                                <option value="moyen">Moyen</option>
                                <option value="nombreux">Nombreux</option>
                                <option value="tres nombreux">Très nombreux</option>
                            </select>
                        </div>
                        <div class="form-field">
                            <label for="mobilite" class="form-label required">Mobilité</label>
                            <input type="text" id="mobilite" name="mobilite" class="form-input" value="<?php echo htmlspecialchars($mobilite); ?>" required />
                        </div>
                        <div class="form-field">
                            <label for="parasite" class="form-label">Parasite</label>
                            <select id="parasite" name="parasite" class="form-select">
                                <option value="absence">Absence</option>
                                <option value="presence">Présence</option>
                            </select>
                        </div>
                        <div class="form-field">
                            <label for="cristaux" class="form-label">Cristaux</label>
                            <select id="cristaux" name="cristaux" class="form-select">
                                <option value="absence">Absence</option>
                                <option value="presence">Présence</option>
                            </select>
                        </div>
                        <div class="form-field">
                            <label for="culture" class="form-label">Culture</label>
                            <select id="culture" name="culture" class="form-select">
                                <option value="negative">Négative</option>
                                <option value="positive">Positive</option>
                            </select>
                        </div>
                        <div class="form-field">
                            <label for="especes_bacteriennes" class="form-label">Espèces bactériennes isolées</label>
                            <select id="especes_bacteriennes" name="especes_bacteriennes" class="form-select">
                                <option value="absence">Absence</option>
                                <option value="presence">Présence</option>
                            </select>
                        </div>
                        <div class="form-field">
                            <label for="titre" class="form-label required">Titre</label>
                            <input type="text" id="titre" name="titre" class="form-input" value="<?php echo htmlspecialchars($titre); ?>" required />
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="section-title">
                        <i class="fas fa-chart-line"></i>
                        Compte Rendu
                    </div>
                    <div class="form-field full-width">
                        <label for="compte_rendu" class="form-label required">Compte Rendu d'Analyse</label>
                        <input type="text" id="compte_rendu" name="compte_rendu" class="form-input" value="<?php echo htmlspecialchars($compte_rendu); ?>" required />
                    </div>
                </div>
                
                <div class="actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Enregistrer
                    </button>
                    <a href="echantillon_male.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                        Retour
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Fonction pour rechercher et remplir automatiquement les informations du patient
        function rechercherPatient(numeroUrap) {
            if (!numeroUrap) {
                return;
            }
            
            // Faire la requête AJAX
            fetch(`get_patient_info.php?urap=${encodeURIComponent(numeroUrap)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remplir automatiquement les champs
                        document.getElementById('age').value = data.data.age || '';
                        document.getElementById('nom').value = data.data.nom || '';
                        document.getElementById('prenom').value = data.data.prenom || '';
                        
                        // Afficher un message de succès
                        showMessage('Patient trouvé et informations remplies automatiquement', 'success');
                    } else {
                        // Ne pas afficher d'erreur si c'est juste un champ vide
                        if (numeroUrap.length > 0) {
                            showMessage(data.message || 'Patient non trouvé', 'error');
                        }
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    if (numeroUrap.length > 0) {
                        showMessage('Erreur lors de la recherche du patient', 'error');
                    }
                });
        }
        
        // Fonction pour afficher des messages
        function showMessage(message, type) {
            // Supprimer les messages existants
            const existingMessages = document.querySelectorAll('.message');
            existingMessages.forEach(msg => msg.remove());
            
            // Créer le nouveau message
            const messageDiv = document.createElement('div');
            messageDiv.className = `message ${type}`;
            messageDiv.style.cssText = `
                padding: 12px 16px;
                margin: 16px 0;
                border-radius: 8px;
                font-weight: 500;
                text-align: center;
                animation: slideIn 0.3s ease-out;
            `;
            
            if (type === 'success') {
                messageDiv.style.background = 'linear-gradient(135deg, #10b981 0%, #059669 100%)';
                messageDiv.style.color = 'white';
            } else {
                messageDiv.style.background = 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)';
                messageDiv.style.color = 'white';
            }
            
            messageDiv.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'}"></i> ${message}`;
            
            // Insérer le message après le header
            const header = document.querySelector('.header');
            header.parentNode.insertBefore(messageDiv, header.nextSibling);
            
            // Supprimer le message après 5 secondes
            setTimeout(() => {
                if (messageDiv.parentNode) {
                    messageDiv.style.animation = 'slideOut 0.3s ease-in';
                    setTimeout(() => messageDiv.remove(), 300);
                }
            }, 5000);
        }
        
        // Ajouter les styles CSS pour les animations
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideIn {
                from { opacity: 0; transform: translateY(-20px); }
                to { opacity: 1; transform: translateY(0); }
            }
            @keyframes slideOut {
                from { opacity: 1; transform: translateY(0); }
                to { opacity: 0; transform: translateY(-20px); }
            }
        `;
        document.head.appendChild(style);
        
        // Auto-remplir si un numéro URAP est passé en paramètre URL
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const urap = urlParams.get('urap');
            const medecin = urlParams.get('medecin');
            
            if (urap) {
                document.getElementById('id').value = urap;
                // Déclencher automatiquement la recherche
                rechercherPatient(urap);
            }
            
            // Remplir automatiquement le nom du médecin prescripteur si disponible
            if (medecin) {
                document.getElementById('medecin').value = medecin;
            }
        });
        
        // Recherche automatique quand l'utilisateur tape dans le champ
        document.getElementById('id').addEventListener('input', function() {
            const numeroUrap = this.value.trim();
            if (numeroUrap.length >= 3) { // Rechercher seulement si au moins 3 caractères
                // Attendre un peu que l'utilisateur finisse de taper
                clearTimeout(window.searchTimeout);
                window.searchTimeout = setTimeout(() => {
                    rechercherPatient(numeroUrap);
                }, 500); // Attendre 500ms après que l'utilisateur arrête de taper
            }
        });
    </script>
</body>
</html>