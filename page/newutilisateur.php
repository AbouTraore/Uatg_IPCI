<?php
require_once('connexion.php');
require_once('../les_fonctions/fonction.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login = $_POST['login'];
    $nom = $_POST['Nom'];
    $Prenom = $_POST['Prenom'];
    $contact = $_POST['contact'];
    $role = $_POST['Role'];
    $pwd1 = $_POST['pwd1'];
    $pwd2 = $_POST['pwd2'];
    $email = $_POST['email'];

    $validationErreur = array();

    if (isset($login)) {
        $filtredLogin = filter_var($login, FILTER_SANITIZE_SPECIAL_CHARS);
        if (strlen($filtredLogin) < 4) {
            $validationErreur[] = "<strong>Erreur !!</strong> le login doit contenir au moins 4 caractere";
        }
    }

    if (isset($pwd1) && isset($pwd2)) {
        if (empty($pwd1)) {
            $validationErreur[] = "<strong>Erreur !!</strong> le mot de passe ne doit etre vide";
        }

        if (md5($pwd1) !== md5($pwd2)) {
            $validationErreur[] = "<strong>Erreur !!</strong> les deux mot de passe ne sont pas identiques";
        }
    }

    if (isset($email)) {
        $filtredemail = filter_var($email, FILTER_SANITIZE_EMAIL);
        if ($filtredemail != true) {
            $validationErreur[] = "<strong>Erreur !!</strong> Email non valide";
        }
    }

    if (empty($validationErreur)) {
        if (rechercher_par_login($login) == 0 && rechercher_par_email($email) == 0) {
            $requete = $pdo->prepare("INSERT INTO user(Nom_user, Prenom_user, Email_user, Mdp_user, Type_user, Etat_user, Contact_user, Login_user)
                                      VALUES(:pNom, :pPrenom, :pEmail, :Mdp, :pType, :pEtat, :pContact, :pLogin)");
            $requete->execute(array(
                'pNom' => $nom,
                'pPrenom' => $Prenom,
                'pEmail' => $email,
                'Mdp' => $pwd1,
                'pType' => $role,
                'pEtat' => 0,
                'pContact' => $contact,
                'pLogin' => $login
            ));
            $success_msg = "Félicitation, votre compte est créé, mais temporairement inactif jusqu'à activation par l'administration";
        } else if (rechercher_par_login($login) > 0) {
            $validationErreur[] = "Désolé le login existe déjà";
        } else if (rechercher_par_email($email) > 0) {
            $validationErreur[] = "Désolé l'email existe déjà";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Nouveau Utilisateur - UATG</title>
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

        .content-area {
            padding: 32px;
            background: white;
        }

        .messages {
            margin-bottom: 32px;
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

        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger);
            border: 1px solid rgba(239, 68, 68, 0.2);
            border-left: 4px solid var(--danger);
        }

        .alert-error::before {
            content: "\f071";
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
            font-size: 16px;
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
            font-size: 16px;
            background: white;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .form-select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(0, 71, 171, 0.1);
        }

        .password-field {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: var(--gray-400);
            transition: color 0.2s ease;
            margin-top: 12px;
        }

        .password-toggle:hover {
            color: var(--gray-600);
        }

        .strength-meter {
            margin-top: 8px;
            height: 4px;
            background: var(--gray-200);
            border-radius: 2px;
            overflow: hidden;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .strength-meter.visible {
            opacity: 1;
        }

        .strength-bar {
            height: 100%;
            background: var(--danger);
            transition: all 0.3s ease;
            width: 0%;
        }

        .strength-bar.weak {
            background: var(--danger);
            width: 25%;
        }

        .strength-bar.medium {
            background: var(--warning);
            width: 50%;
        }

        .strength-bar.strong {
            background: var(--success);
            width: 75%;
        }

        .strength-bar.very-strong {
            background: var(--accent);
            width: 100%;
        }

        .actions {
            display: flex;
            gap: 16px;
            justify-content: center;
            flex-wrap: wrap;
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

        .btn-accent {
            background: linear-gradient(135deg, var(--accent) 0%, #059669 100%);
            color: white;
            box-shadow: var(--shadow);
        }

        .btn-accent:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .loading-state .btn-primary {
            pointer-events: none;
            opacity: 0.7;
        }

        .loading-spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid transparent;
            border-top: 2px solid currentColor;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
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

            .form-input,
            .form-select {
                font-size: 16px; /* Prevent zoom on iOS */
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <button onclick="window.history.back()" style="position:absolute;left:24px;top:32px;background:var(--gray-100);border:none;border-radius:50px;padding:10px 18px;font-size:1.1em;box-shadow:0 2px 8px rgba(0,0,0,0.07);color:var(--primary);cursor:pointer;display:flex;align-items:center;gap:8px;transition:background 0.2s;z-index:2;">
                <i class="fas fa-arrow-left"></i> Retour
            </button>
            <h1><i class="fas fa-user-plus"></i> Nouveau Utilisateur</h1>
            <p>Création d'un compte utilisateur pour le système UATG</p>
        </div>

        <div class="content-area">
            <div class="messages">
                <?php 
                if (!empty($validationErreur)) {
                    foreach ($validationErreur as $error) {
                        echo '<div class="alert alert-error">' . $error . '</div>';
                    }
                }
                if (!empty($success_msg)) {
                    echo '<div class="alert alert-success">' . $success_msg . '</div>';
                    echo '<script>setTimeout(() => { window.location.href = "admin.php"; }, 3000);</script>';
                }
                ?>
            </div>

            <form method="POST" action="newutilisateur.php" id="userForm">
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-user"></i>
                        Informations personnelles
                    </h2>
                    <div class="form-grid">
                        <div class="form-field">
                            <label class="form-label">Nom *</label>
                            <input type="text" name="Nom" class="form-input" required placeholder="Nom de famille" value="<?php echo isset($_POST['Nom']) ? htmlspecialchars($_POST['Nom']) : ''; ?>">
                        </div>

                        <div class="form-field">
                            <label class="form-label">Prénom *</label>
                            <input type="text" name="Prenom" class="form-input" required placeholder="Prénom" value="<?php echo isset($_POST['Prenom']) ? htmlspecialchars($_POST['Prenom']) : ''; ?>">
                        </div>

                        <div class="form-field">
                            <label class="form-label">Contact *</label>
                            <input type="tel" name="contact" class="form-input" required placeholder="Numéro de téléphone" value="<?php echo isset($_POST['contact']) ? htmlspecialchars($_POST['contact']) : ''; ?>">
                        </div>

                        <div class="form-field">
                            <label class="form-label">Rôle *</label>
                            <select name="Role" class="form-select" required>
                                <option value="ADMIN" <?php echo (isset($_POST['Role']) && $_POST['Role'] == 'ADMIN') ? 'selected' : ''; ?>>Docteur</option>
                                <option value="TECHNICIEN" <?php echo (isset($_POST['Role']) && $_POST['Role'] == 'TECHNICIEN') ? 'selected' : ''; ?>>Technicien</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-key"></i>
                        Informations de connexion
                    </h2>
                    <div class="form-grid">
                        <div class="form-field">
                            <label class="form-label">Login *</label>
                            <input type="text" name="login" class="form-input" required placeholder="Identifiant de connexion (min. 4 caractères)" value="<?php echo isset($_POST['login']) ? htmlspecialchars($_POST['login']) : ''; ?>">
                        </div>

                        <div class="form-field">
                            <label class="form-label">Email *</label>
                            <input type="email" name="email" class="form-input" required placeholder="Adresse email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                        </div>

                        <div class="form-field password-field">
                            <label class="form-label">Mot de passe *</label>
                            <input type="password" name="pwd1" class="form-input" id="password1" required placeholder="Mot de passe sécurisé">
                            <i class="fas fa-eye password-toggle" onclick="togglePassword('password1', this)"></i>
                            <div class="strength-meter" id="strengthMeter">
                                <div class="strength-bar" id="strengthBar"></div>
                            </div>
                        </div>

                        <div class="form-field password-field">
                            <label class="form-label">Confirmation *</label>
                            <input type="password" name="pwd2" class="form-input" id="password2" required placeholder="Confirmez le mot de passe">
                            <i class="fas fa-eye password-toggle" onclick="togglePassword('password2', this)"></i>
                        </div>
                    </div>
                </div>

                <div class="actions">
                    <button type="reset" class="btn btn-secondary">
                        <i class="fas fa-undo"></i>
                        Réinitialiser
                    </button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="fas fa-user-plus"></i>
                        Créer le compte
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Fonction pour afficher/masquer les mots de passe
        function togglePassword(inputId, icon) {
            const input = document.getElementById(inputId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Vérification de la force du mot de passe
        function checkPasswordStrength(password) {
            let strength = 0;
            if (password.length >= 8) strength++;
            if (/[a-z]/.test(password)) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;
            return strength;
        }

        // Mise à jour du compteur de force du mot de passe
        document.getElementById('password1').addEventListener('input', function() {
            const password = this.value;
            const strengthMeter = document.getElementById('strengthMeter');
            const strengthBar = document.getElementById('strengthBar');
            
            if (password.length === 0) {
                strengthMeter.classList.remove('visible');
                return;
            }
            
            strengthMeter.classList.add('visible');
            const strength = checkPasswordStrength(password);
            
            strengthBar.className = 'strength-bar';
            if (strength <= 1) {
                strengthBar.classList.add('weak');
            } else if (strength === 2) {
                strengthBar.classList.add('medium');
            } else if (strength === 3) {
                strengthBar.classList.add('strong');
            } else if (strength >= 4) {
                strengthBar.classList.add('very-strong');
            }
        });

        // Validation en temps réel
        document.getElementById('userForm').addEventListener('submit', function(e) {
            const pwd1 = document.querySelector('input[name="pwd1"]').value;
            const pwd2 = document.querySelector('input[name="pwd2"]').value;
            const login = document.querySelector('input[name="login"]').value;
            
            let isValid = true;
            
            // Vérifier la longueur du login
            if (login.length < 4) {
                alert('Le login doit contenir au moins 4 caractères');
                isValid = false;
            }
            
            // Vérifier que les mots de passe correspondent
            if (pwd1 !== pwd2) {
                alert('Les mots de passe ne correspondent pas');
                isValid = false;
            }
            
            if (!isValid) {
                e.preventDefault();
                return;
            }
            
            // Animation de chargement
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.innerHTML = '<div class="loading-spinner"></div> Création en cours...';
            submitBtn.disabled = true;
            document.body.classList.add('loading-state');
        });

        // Animation d'apparition des éléments
        document.addEventListener('DOMContentLoaded', function() {
            const formFields = document.querySelectorAll('.form-field');
            formFields.forEach((field, index) => {
                field.style.opacity = '0';
                field.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    field.style.transition = 'all 0.3s ease';
                    field.style.opacity = '1';
                    field.style.transform = 'translateY(0)';
                }, index * 100);
            });

            // Auto-focus sur le premier champ
            const firstInput = document.querySelector('.form-input');
            if (firstInput) {
                firstInput.focus();
            }
        });

        // Validation en temps réel pour la correspondance des mots de passe
        document.getElementById('password2').addEventListener('input', function() {
            const pwd1 = document.getElementById('password1').value;
            const pwd2 = this.value;
            
            if (pwd2 && pwd1 !== pwd2) {
                this.style.borderColor = 'var(--danger)';
            } else {
                this.style.borderColor = '';
            }
        });
    </script>
</body>
</html>