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
                $sql = "INSERT INTO echantillon_male (numero_urap, type_echantillon, date_prelevement, technicien) VALUES (?, ?, ?, ?)";
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
        /* Styles inchangés */
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <a href="javascript:history.back()" class="btn-retour">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
            <h1><i class="fas fa-vial"></i> Gestion des Échantillons</h1>
            <p>Enregistrement d'un échantillon</p>
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
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" id="echantillonForm">
                <input type="hidden" id="numero_urap" name="numero_urap" value="<?php echo htmlspecialchars($numero_urap); ?>" required>

                <div class="form-section">
                    <div class="echantillon-col">
                        <h2><i class="fas fa-capsules"></i> Échantillon</h2>
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
            if (confirm('Êtes-vous sûr de vouloir réinitialiser le formulaire ?')) {
                document.getElementById('echantillonForm').reset();
                document.getElementById('numero_urap_display').value = '';
            }
        }

        document.getElementById('echantillonForm').addEventListener('submit', function(e) {
            const numeroUrap = document.getElementById('numero_urap').value.trim();
            const type = document.getElementById('type_echantillon').value.trim();
            
            if (!numeroUrap) {
                e.preventDefault();
                alert('Veuillez saisir le numéro URAP du patient.');
                document.getElementById('numero_urap_display').focus();
                return;
            }
            
            if (!type) {
                e.preventDefault();
                alert('Veuillez renseigner le type d\'échantillon.');
                return;
            }

            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enregistrement...';
            submitBtn.disabled = true;
        });

        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const urapFromUrl = urlParams.get('urap');
            if (urapFromUrl) {
                document.getElementById('numero_urap_display').value = urapFromUrl;
                document.getElementById('numero_urap').value = urapFromUrl;
            }
        });
    </script>
</body>
</html>