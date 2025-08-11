<?php
require_once("identifier.php");
require_once("connexion.php");

$message = '';
$messageType = '';
$type_echantillon = '';
$date_prelevement = '';
$technicien = '';
$numero_urap = $_GET['urap'] ?? '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $numero_urap = $_POST['numero_urap'] ?? '';
    $type_echantillon = trim($_POST['type_echantillon'] ?? '');
    $date_prelevement = $_POST['date_prelevement'] ?? '';
    $technicien = trim($_POST['technicien'] ?? '');

    if (empty($numero_urap)) {
        $message = "Le numéro URAP est obligatoire.";
        $messageType = 'error';
    } elseif (empty($type_echantillon)) {
        $message = "Le type d'échantillon doit être renseigné.";
        $messageType = 'error';
    } else {
        try {
            $check_patient = $pdo->prepare("SELECT COUNT(*) FROM patient WHERE Numero_urap = ?");
            $check_patient->execute([$numero_urap]);
            
            if ($check_patient->fetchColumn() == 0) {
                $message = "Patient non trouvé avec ce numéro URAP.";
                $messageType = 'error';
            } else {
                $sql = "INSERT INTO echantillon_femelle (numero_urap, type_echantillon, date_prelevement, technicien) VALUES (?, ?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                $result = $stmt->execute([$numero_urap, $type_echantillon, $date_prelevement, $technicien]);
                
                if ($result) {
                    header('Location: visite_patient.php?urap=' . urlencode($numero_urap));
                    exit;
                } else {
                    $message = "Erreur lors de l'enregistrement de l'échantillon.";
                    $messageType = 'error';
                }
            }
        } catch (PDOException $e) {
            $message = "Erreur lors de l'enregistrement : " . $e->getMessage();
            $messageType = 'error';
        }
    }
}

$patient = null;
if (!empty($numero_urap)) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM patient WHERE Numero_urap = ?");
        $stmt->execute([$numero_urap]);
        $patient = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Log error
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Gestion des Échantillons - UATG</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #0047ab;
            --primary-light: #1e90ff;
            --success: #22c55e;
            --danger: #ef4444;
            --warning: #f59e0b;
            --info: #06b6d4;
            --purple: #8b5cf6;
            --pink: #ec4899;
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
            color: var(--gray-800);
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 32px;
            margin-bottom: 24px;
            box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1);
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
            background: radial-gradient(circle, rgba(0,71,171,0.1) 0%, transparent 70%);
            animation: rotate 20s linear infinite;
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .header h1 {
            color: var(--primary);
            font-size: 2.5rem;
            font-weight: 700;
            position: relative;
            z-index: 1;
            margin-bottom: 8px;
        }

        .header p {
            color: var(--gray-600);
            font-size: 1.1rem;
            position: relative;
            z-index: 1;
        }

        .btn-retour {
            position: absolute;
            top: 20px;
            left: 20px;
            background: rgba(255, 255, 255, 0.9);
            color: var(--primary);
            padding: 10px 16px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            z-index: 2;
            position: relative;
        }

        .btn-retour:hover {
            background: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 15px -3px rgb(0 0 0 / 0.1);
        }

        .content-area {
            display: grid;
            gap: 24px;
        }

        .urap-section {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 32px;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            border-left: 4px solid var(--info);
        }

        .urap-section h2 {
            color: var(--info);
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .urap-input-container {
            position: relative;
        }

        .urap-input {
            width: 100%;
            padding: 16px 20px;
            font-size: 18px;
            border: 2px solid var(--gray-200);
            border-radius: 12px;
            background: white;
            transition: all 0.3s ease;
            font-weight: 600;
            text-align: center;
            letter-spacing: 1px;
        }

        .urap-input:focus {
            outline: none;
            border-color: var(--info);
            box-shadow: 0 0 0 3px rgba(6, 182, 212, 0.1);
            transform: scale(1.02);
        }

        .patient-info {
            background: linear-gradient(135deg, var(--success) 0%, #16a34a 100%);
            color: white;
            padding: 20px 24px;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            animation: slideInFromTop 0.5s ease-out;
        }

        @keyframes slideInFromTop {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .patient-info h3 {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .patient-info p {
            font-size: 1rem;
            opacity: 0.95;
        }

        .alert {
            padding: 16px 20px;
            border-radius: 12px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 12px;
            animation: fadeIn 0.5s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .alert-error {
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fca5a5;
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #34d399;
        }

        .form-section {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 32px;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        }

        .echantillon-col h2 {
            color: var(--purple);
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            padding-bottom: 16px;
            border-bottom: 2px solid var(--gray-100);
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 24px;
        }

        .form-field {
            position: relative;
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-input {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid var(--gray-200);
            border-radius: 12px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: white;
            font-family: inherit;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--purple);
            box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
            transform: translateY(-2px);
        }

        .form-input:hover {
            border-color: var(--gray-300);
        }

        .actions {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            display: flex;
            justify-content: center;
            gap: 16px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 14px 28px;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn-success {
            background: linear-gradient(135deg, var(--success) 0%, #16a34a 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
        }

        .btn-success:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(34, 197, 94, 0.4);
        }

        .btn-secondary {
            background: linear-gradient(135deg, var(--gray-600) 0%, var(--gray-700) 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(107, 114, 128, 0.3);
        }

        .btn-secondary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(107, 114, 128, 0.4);
        }

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none !important;
        }

        /* Animations personnalisées */
        .form-section {
            animation: slideInFromBottom 0.6s ease-out;
        }

        @keyframes slideInFromBottom {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .actions {
            animation: slideInFromBottom 0.8s ease-out;
        }

        /* Effets de focus avancés */
        .form-input:focus + .form-label {
            color: var(--purple);
        }

        /* Style pour les placeholders */
        .form-input::placeholder {
            color: var(--gray-400);
            font-style: italic;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }

            .header {
                padding: 20px;
                text-align: center;
            }

            .header h1 {
                font-size: 2rem;
            }

            .btn-retour {
                position: static;
                margin-bottom: 20px;
                align-self: flex-start;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .actions {
                flex-direction: column;
                align-items: stretch;
            }

            .btn {
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .header h1 {
                font-size: 1.5rem;
            }

            .urap-input {
                font-size: 16px;
                padding: 12px 16px;
            }
        }

        /* Animation de chargement pour le bouton */
        .btn .fa-spinner {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* Effet de brillance sur les cartes */
        .urap-section:hover,
        .form-section:hover,
        .actions:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px -5px rgb(0 0 0 / 0.1);
        }

        /* Style pour les icônes */
        .fa-vial {
            color: var(--purple);
        }

        .fa-id-card {
            color: var(--info);
        }

        .fa-user {
            color: white;
        }

        .fa-capsules {
            color: var(--purple);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <a href="javascript:history.back()" class="btn-retour">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
            <h1><i class="fas fa-vial"></i> Gestion des Échantillons</h1>
            <p>Enregistrement d'un échantillon médical</p>
        </div>

        <div class="content-area">
            <div class="urap-section">
                <h2><i class="fas fa-id-card"></i> Numéro URAP du Patient</h2>
                <div class="urap-input-container">
                    <input type="text" class="urap-input" id="numero_urap_display" 
                           value="<?php echo htmlspecialchars($numero_urap); ?>" 
                           placeholder="Saisir le numéro URAP du patient" 
                           onchange="updateMainUrapField(this.value)">
                </div>
            </div>

            <?php if ($patient): ?>
                <div class="patient-info">
                    <h3><i class="fas fa-user"></i> Patient sélectionné</h3>
                    <p><strong><?php echo htmlspecialchars($patient['Nom_patient'] . ' ' . $patient['Prenom_patient']); ?></strong> - N° URAP: <?php echo htmlspecialchars($patient['Numero_urap']); ?></p>
                </div>
            <?php endif; ?>

            <?php if (!empty($message)): ?>
                <div class="alert alert-<?php echo $messageType; ?>">
                    <i class="fas fa-<?php echo $messageType === 'error' ? 'exclamation-triangle' : 'check-circle'; ?>"></i>
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" id="echantillonForm">
                <input type="hidden" id="numero_urap" name="numero_urap" value="<?php echo htmlspecialchars($numero_urap); ?>" required>

                <div class="form-section">
                    <div class="echantillon-col">
                        <h2><i class="fas fa-capsules"></i> Informations de l'Échantillon</h2>
                        <div class="form-grid">
                            <div class="form-field">
                                <label for="type_echantillon" class="form-label">Type de l'échantillon</label>
                                <input type="text" id="type_echantillon" name="type_echantillon" class="form-input" 
                                       value="<?php echo htmlspecialchars($type_echantillon); ?>" 
                                       placeholder="Ex: Urine, Sang, Salive..." />
                            </div>
                            <div class="form-field">
                                <label for="date_prelevement" class="form-label">Date de prélèvement</label>
                                <input type="date" id="date_prelevement" name="date_prelevement" class="form-input" 
                                       value="<?php echo htmlspecialchars($date_prelevement); ?>" />
                            </div>
                            <div class="form-field">
                                <label for="technicien" class="form-label">Technicien responsable</label>
                                <input type="text" id="technicien" name="technicien" class="form-input" 
                                       value="<?php echo htmlspecialchars($technicien); ?>" 
                                       placeholder="Nom du technicien" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="actions">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Enregistrer et voir le rapport
                    </button>
                    <a href="echantillon_unique.php" class="btn btn-secondary">
                        <i class="fas fa-flask"></i> Échantillon unique
                    </a>
                    <button type="button" class="btn btn-secondary" onclick="resetForm()">
                        <i class="fas fa-undo"></i> Réinitialiser
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function updateMainUrapField(value) {
            document.getElementById('numero_urap').value = value;
        }

        function resetForm() {
            // Créer une modal de confirmation personnalisée
            showConfirmDialog('Êtes-vous sûr de vouloir réinitialiser le formulaire ?', function() {
                document.getElementById('echantillonForm').reset();
                document.getElementById('numero_urap_display').value = '';
                document.getElementById('numero_urap').value = '';
            });
        }

        function showConfirmDialog(message, onConfirm) {
            const modal = document.createElement('div');
            modal.style.cssText = `
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 1000;
                animation: fadeIn 0.3s ease-out;
            `;
            
            modal.innerHTML = `
                <div style="background: white; padding: 2rem; border-radius: 16px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); max-width: 400px; width: 90%; text-align: center;">
                    <div style="color: #f59e0b; font-size: 3rem; margin-bottom: 1rem;">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <h3 style="color: #374151; margin-bottom: 1rem; font-size: 1.2rem;">Confirmation</h3>
                    <p style="color: #6b7280; margin-bottom: 2rem;">${message}</p>
                    <div style="display: flex; gap: 1rem; justify-content: center;">
                        <button onclick="this.closest('div').parentElement.remove()" style="padding: 0.75rem 1.5rem; background: #e5e7eb; color: #374151; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">Annuler</button>
                        <button onclick="this.closest('div').parentElement.remove(); (${onConfirm})()" style="padding: 0.75rem 1.5rem; background: #ef4444; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">Confirmer</button>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
        }

        document.getElementById('echantillonForm').addEventListener('submit', function(e) {
            const numeroUrap = document.getElementById('numero_urap').value.trim();
            const type = document.getElementById('type_echantillon').value.trim();
            
            if (!numeroUrap) {
                e.preventDefault();
                showAlert('Veuillez saisir le numéro URAP du patient.', 'warning');
                document.getElementById('numero_urap_display').focus();
                return;
            }
            
            if (!type) {
                e.preventDefault();
                showAlert('Veuillez renseigner le type d\'échantillon.', 'warning');
                document.getElementById('type_echantillon').focus();
                return;
            }

            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enregistrement...';
            submitBtn.disabled = true;
        });

        function showAlert(message, type) {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type}`;
            alertDiv.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 1000;
                min-width: 300px;
                animation: slideInFromRight 0.5s ease-out;
            `;
            
            const icon = type === 'warning' ? 'exclamation-triangle' : 'info-circle';
            alertDiv.innerHTML = `
                <i class="fas fa-${icon}"></i>
                ${message}
                <button onclick="this.parentElement.remove()" style="margin-left: auto; background: none; border: none; color: inherit; cursor: pointer; padding: 0.25rem;">
                    <i class="fas fa-times"></i>
                </button>
            `;
            
            document.body.appendChild(alertDiv);
            
            setTimeout(() => {
                if (alertDiv.parentElement) {
                    alertDiv.remove();
                }
            }, 5000);
        }

        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const urapFromUrl = urlParams.get('urap');
            if (urapFromUrl) {
                document.getElementById('numero_urap_display').value = urapFromUrl;
                document.getElementById('numero_urap').value = urapFromUrl;
            }

            // Animation d'entrée pour les éléments
            const elements = document.querySelectorAll('.urap-section, .form-section, .actions');
            elements.forEach((el, index) => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(30px)';
                setTimeout(() => {
                    el.style.transition = 'all 0.6s ease';
                    el.style.opacity = '1';
                    el.style.transform = 'translateY(0)';
                }, index * 200);
            });
        });

        // Ajouter style pour l'animation slideInFromRight
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideInFromRight {
                from {
                    opacity: 0;
                    transform: translateX(100%);
                }
                to {
                    opacity: 1;
                    transform: translateX(0);
                }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>