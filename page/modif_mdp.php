<?php
// page/modif_mdp.php
// Permet à l'utilisateur connecté de modifier son propre mot de passe
require_once("identifier.php");
require_once("connexion.php");

// Vérifier que l'utilisateur est connecté
$user = isset($_SESSION['user']) ? $_SESSION['user'] : null;
if (!$user) {
    header('Location: login.php');
    exit();
}
// Récupérer l'utilisateur depuis la base (id_user ou login)
try {
    if (isset($user['id_user'])) {
        $stmt = $pdo->prepare("SELECT * FROM user WHERE id_user = ?");
        $stmt->execute([$user['id_user']]);
        $userDb = $stmt->fetch(PDO::FETCH_ASSOC);
    } elseif (isset($user['Login_user'])) {
        $stmt = $pdo->prepare("SELECT * FROM user WHERE Login_user = ?");
        $stmt->execute([$user['Login_user']]);
        $userDb = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        $userDb = null;
    }
} catch (Exception $e) { $userDb = null; }
if (!$userDb) {
    echo '<div style="color:red;text-align:center;margin-top:40px;">Utilisateur introuvable.</div>';
    exit();
}

// Gestion du formulaire
$message = '';
$success = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old = $_POST['old_password'] ?? '';
    $new = $_POST['new_password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';
    // Vérification de l'ancien mot de passe
    if (empty($old) || empty($new) || empty($confirm)) {
        $message = "Veuillez remplir tous les champs.";
    } elseif (!password_verify($old, $userDb['Mdp_user'])) {
        $message = "Ancien mot de passe incorrect.";
    } elseif (strlen($new) < 6) {
        $message = "Le nouveau mot de passe doit contenir au moins 6 caractères.";
    } elseif ($new !== $confirm) {
        $message = "La confirmation ne correspond pas au nouveau mot de passe.";
    } else {
        // Mise à jour du mot de passe
        $hash = password_hash($new, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE user SET Mdp_user = ? WHERE id_user = ?");
        $stmt->execute([$hash, $userDb['id_user']]);
        $success = true;
        $message = "Mot de passe modifié avec succès !";
        // Optionnel : redirection après 2s
        echo '<script>setTimeout(function(){ window.location.href = "profil.php?success=1"; }, 2000);</script>';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier mon mot de passe</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', Arial, sans-serif;
            background: linear-gradient(135deg, #e0e7ff 0%, #f3f4f6 100%);
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }
        .mdp-container {
            max-width: 420px;
            margin: 60px auto;
            background: #fff;
            border-radius: 24px;
            box-shadow: 0 8px 32px rgba(0,71,171,0.10);
            padding: 36px 32px 32px 32px;
            text-align: center;
            border: 2.5px solid #e0edff;
        }
        .mdp-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: #0047ab;
            margin-bottom: 18px;
        }
        .mdp-form {
            margin-top: 18px;
        }
        .mdp-label {
            display: block;
            text-align: left;
            font-weight: 600;
            color: #374151;
            margin-bottom: 6px;
        }
        .mdp-input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 15px;
            margin-bottom: 18px;
            background: #f9fafb;
            transition: border 0.2s;
        }
        .mdp-input:focus {
            border-color: #6366f1;
            outline: none;
        }
        .mdp-btn {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 14px 0;
            font-size: 1.08em;
            font-weight: 700;
            cursor: pointer;
            width: 100%;
            box-shadow: 0 4px 18px rgba(16,185,129,0.13);
            transition: background 0.2s, box-shadow 0.2s, transform 0.2s;
        }
        .mdp-btn:hover {
            background: linear-gradient(135deg, #059669 0%, #10b981 100%);
            box-shadow: 0 8px 32px rgba(16,185,129,0.18);
            transform: translateY(-2px) scale(1.03);
        }
        .mdp-message {
            margin-bottom: 18px;
            padding: 12px 18px;
            border-radius: 12px;
            font-weight: 600;
            text-align: center;
            font-size: 1.05em;
            display: flex;
            align-items: center;
            gap: 10px;
            justify-content: center;
        }
        .mdp-message.success {
            background: #e0fbe6;
            color: #059669;
            border: 1.5px solid #34d399;
        }
        .mdp-message.error {
            background: #ffeaea;
            color: #b91c1c;
            border: 1.5px solid #fca5a5;
        }
    </style>
</head>
<body>
    <div class="mdp-container">
        <div class="mdp-title"><i class="fas fa-key"></i> Modifier mon mot de passe</div>
        <?php if ($message): ?>
            <div class="mdp-message <?php echo $success ? 'success' : 'error'; ?>">
                <i class="fas fa-<?php echo $success ? 'check-circle' : 'exclamation-triangle'; ?>"></i> <?php echo $message; ?>
            </div>
        <?php endif; ?>
        <form class="mdp-form" method="post" autocomplete="off">
            <label class="mdp-label" for="old_password">Ancien mot de passe</label>
            <input class="mdp-input" type="password" name="old_password" id="old_password" required autocomplete="current-password">
            <label class="mdp-label" for="new_password">Nouveau mot de passe</label>
            <input class="mdp-input" type="password" name="new_password" id="new_password" required autocomplete="new-password">
            <label class="mdp-label" for="confirm_password">Confirmer le nouveau mot de passe</label>
            <input class="mdp-input" type="password" name="confirm_password" id="confirm_password" required autocomplete="new-password">
            <button class="mdp-btn" type="submit"><i class="fas fa-save"></i> Enregistrer</button>
        </form>
    </div>
</body>
</html> 