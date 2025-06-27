<?php
// page/profil.php
// Affichage du profil de l'utilisateur connecté
require_once("identifier.php");
require_once("connexion.php");

// Récupérer les infos de l'utilisateur connecté depuis la session
$user = isset($_SESSION['user']) ? $_SESSION['user'] : null;
if (!$user) {
    // Redirige vers la page de connexion si non connecté
    header('Location: login.php');
    exit();
}
// Optionnel : récupérer les infos à jour depuis la base (par ID ou login)
try {
    if (isset($user['id_user'])) {
        $stmt = $pdo->prepare("SELECT * FROM user WHERE id_user = ?");
        $stmt->execute([$user['id_user']]);
        $userDb = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($userDb) $user = $userDb;
    } elseif (isset($user['Login_user'])) {
        $stmt = $pdo->prepare("SELECT * FROM user WHERE Login_user = ?");
        $stmt->execute([$user['Login_user']]);
        $userDb = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($userDb) $user = $userDb;
    }
} catch (Exception $e) {}
// Message de succès après modification de la photo
$successMsg = isset($_GET['success']) ? 'Photo de profil mise à jour avec succès !' : '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', Arial, sans-serif;
            background: linear-gradient(135deg, #e0e7ff 0%, #f3f4f6 100%);
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }
        .profile-container {
            max-width: 520px;
            margin: 48px auto;
            background: rgba(255,255,255,0.99);
            border-radius: 32px;
            box-shadow: 0 8px 32px rgba(0,71,171,0.10);
            padding: 36px 32px 32px 32px;
            text-align: center;
            border: 2.5px solid #e0edff;
            animation: fadeSlideUp 0.8s cubic-bezier(.4,1.4,.6,1);
        }
        @keyframes fadeSlideUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            box-shadow: 0 2px 12px rgba(0,0,0,0.10);
            margin-bottom: 18px;
            background: #f3f4f6;
            display: inline-block;
            border: 3px solid #a5b4fc;
            transition: box-shadow 0.2s, border 0.2s;
        }
        .profile-avatar:hover {
            box-shadow: 0 6px 24px #a5b4fc44;
            border: 3px solid #6366f1;
        }
        .profile-name {
            font-size: 1.6rem;
            font-weight: 700;
            color: #0047ab;
            margin-bottom: 4px;
        }
        .profile-type {
            font-size: 1.08rem;
            color: #059669;
            font-weight: 600;
            margin-bottom: 18px;
        }
        .profile-info {
            text-align: left;
            margin: 0 auto 18px auto;
            max-width: 360px;
        }
        .profile-info-row {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 10px;
        }
        .profile-info-label {
            font-weight: 600;
            color: #374151;
            min-width: 110px;
        }
        .profile-info-value {
            color: #1e293b;
            font-size: 1.07em;
            word-break: break-all;
        }
        .profile-actions {
            margin-top: 32px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .btn-modifier, .btn-retour, .btn-action {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border: none;
            border-radius: 14px;
            padding: 16px 0;
            font-size: 1.13em;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 4px 18px rgba(16,185,129,0.13);
            transition: background 0.2s, box-shadow 0.2s, transform 0.2s;
            width: 80%;
            max-width: 320px;
            margin: 0 auto 12px auto;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
        }
        .btn-modifier:hover, .btn-retour:hover, .btn-action:hover {
            background: linear-gradient(135deg, #059669 0%, #10b981 100%);
            box-shadow: 0 8px 32px rgba(16,185,129,0.18);
            transform: translateY(-2px) scale(1.03);
        }
        .btn-modifier:disabled, .btn-modifier[disabled] {
            background: linear-gradient(135deg, #d1d5db 0%, #9ca3af 100%);
            color: #6b7280;
            cursor: not-allowed;
            box-shadow: none;
            opacity: 0.85;
        }
        .profile-disabled-msg {
            color: #b91c1c;
            font-size: 1.01em;
            margin-top: 8px;
            text-align: center;
        }
        .profile-upload-form {
            margin-bottom: 18px;
        }
        .profile-upload-label {
            display: block;
            margin-bottom: 8px;
            color: #6366f1;
            font-weight: 600;
            font-size: 1.05em;
        }
        .profile-upload-input {
            display: none;
        }
        .profile-upload-btn {
            background: #6366f1;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 8px 18px;
            font-size: 1em;
            font-weight: 600;
            cursor: pointer;
            margin-bottom: 8px;
            transition: background 0.2s;
        }
        .profile-upload-btn:hover {
            background: #4338ca;
        }
        .profile-upload-preview {
            margin: 0 auto 10px auto;
            display: block;
            border-radius: 50%;
            width: 90px;
            height: 90px;
            object-fit: cover;
            border: 2px solid #6366f1;
        }
        .profile-success-msg {
            background: #e0fbe6;
            color: #059669;
            border: 1.5px solid #34d399;
            border-radius: 12px;
            padding: 12px 18px;
            margin-bottom: 18px;
            font-weight: 600;
            text-align: center;
            display: flex;
            align-items: center;
            gap: 10px;
            justify-content: center;
            font-size: 1.08em;
        }
        @media (max-width: 600px) {
            .profile-container {
                padding: 18px 4vw 18px 4vw;
            }
            .profile-info {
                max-width: 98vw;
            }
            .btn-modifier, .btn-retour, .btn-action {
                width: 98vw;
                max-width: 98vw;
            }
        }
    </style>
</head>
<body>
    <!-- Bouton retour -->
    <div style="text-align:center;margin-top:24px;">
        <a href="acceuil.php" class="btn-retour"><i class="fas fa-arrow-left"></i> Retour</a>
    </div>
    <!-- Conteneur principal du profil -->
    <div class="profile-container">
        <!-- Avatar ou icône utilisateur -->
        <?php if (!empty($user['photo'])): ?>
            <img src="<?php echo htmlspecialchars($user['photo']); ?>" alt="Avatar" class="profile-avatar" id="mainAvatar">
        <?php else: ?>
            <span class="profile-avatar" id="mainAvatar" style="display:flex;align-items:center;justify-content:center;font-size:3.2em;color:#a5b4fc;background:#e0edff;">
                <i class="fas fa-user-circle"></i>
            </span>
        <?php endif; ?>
        <!-- Nom complet -->
        <div class="profile-name"><?php echo htmlspecialchars($user['Nom_user'] . ' ' . $user['Prenom_user']); ?></div>
        <!-- Type d'utilisateur -->
        <div class="profile-type"><i class="fas fa-user-shield"></i> <?php echo htmlspecialchars($user['Type_user']); ?></div>
        <!-- Informations détaillées -->
        <div class="profile-info">
            <div class="profile-info-row">
                <span class="profile-info-label"><i class="fas fa-envelope"></i> Email :</span>
                <span class="profile-info-value"><?php echo htmlspecialchars($user['Email_user']); ?></span>
            </div>
            <div class="profile-info-row">
                <span class="profile-info-label"><i class="fas fa-phone"></i> Contact :</span>
                <span class="profile-info-value"><?php echo htmlspecialchars($user['Contact_user']); ?></span>
            </div>
            <div class="profile-info-row">
                <span class="profile-info-label"><i class="fas fa-user"></i> Login :</span>
                <span class="profile-info-value"><?php echo htmlspecialchars($user['Login_user']); ?></span>
            </div>
            <?php if (!empty($user['date_inscription'])): ?>
            <div class="profile-info-row">
                <span class="profile-info-label"><i class="fas fa-calendar-alt"></i> Inscrit le :</span>
                <span class="profile-info-value"><?php echo htmlspecialchars($user['date_inscription']); ?></span>
            </div>
            <?php endif; ?>
            <?php if (!empty($user['Statut'])): ?>
            <div class="profile-info-row">
                <span class="profile-info-label"><i class="fas fa-check-circle"></i> Statut :</span>
                <span class="profile-info-value"><?php echo htmlspecialchars($user['Statut']); ?></span>
            </div>
            <?php endif; ?>
        </div>
        <!-- Actions profil (modifier, etc.) -->
        <div class="profile-actions">
            <a href="modif_utilisateur.php?
                <?php if (!empty($user['id_user'])) {
                    echo 'id=' . urlencode($user['id_user']);
                } elseif (!empty($user['Login_user'])) {
                    echo 'login=' . urlencode($user['Login_user']);
                } else {
                    echo 'profil=1';
                } ?>"
                class="btn-modifier"><i class="fas fa-pen"></i> Modifier</a>
            <a href="#" class="btn-action" style="background:linear-gradient(135deg,#6366f1 0%,#4338ca 100%);margin-top:8px;"><i class="fas fa-key"></i> Changer le mot de passe</a>
            <a href="#" class="btn-action" style="background:linear-gradient(135deg,#f59e42 0%,#fbbf24 100%);margin-top:8px;"><i class="fas fa-list"></i> Voir mes actions</a>
        </div>
    </div>
</body>
</html> 