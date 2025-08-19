<?php
require_once("identifier.php");
require_once("connexion.php");

// Traitement des actions d'amélioration des visites
$message = '';
$messageType = 'info';

if ($_POST) {
    try {
        switch ($_POST['action']) {
            case 'ameliorer_visites':
                $nombre = intval($_POST['nombre_visites']);
                $type = $_POST['type_visite'];
                
                // Récupérer des patients aléatoirement
                $stmt = $pdo->query("SELECT Numero_urap FROM patient ORDER BY RAND() LIMIT " . min($nombre, 50));
                $patients = $stmt->fetchAll(PDO::FETCH_COLUMN);
                
                // Récupérer un prescripteur existant
                $stmt = $pdo->query("SELECT ID_prescripteur FROM prescriteur ORDER BY RAND() LIMIT 1");
                $prescripteur = $stmt->fetch();
                $id_prescripteur = $prescripteur ? $prescripteur['ID_prescripteur'] : 1;
                
                $visitesAjoutees = 0;
                foreach ($patients as $numeroUrap) {
                    $date = date('Y-m-d', strtotime('-' . rand(0, 30) . ' days'));
                    $heure = sprintf("%02d:%02d", rand(8, 17), rand(0, 59));
                    
                    $stmt = $pdo->prepare("
                        INSERT INTO visite (
                            date_visite, `Heure visite`, `Motif visite`, Numero_urap, 
                            ID_prescripteur, Structure_provenance, ID_antecedents, ID_antecedent,
                            ID_histoire_maladie, ID_habitude_sexuelles
                        ) VALUES (?, ?, ?, ?, ?, 'ipci', NULL, 0, NULL, NULL)
                    ");
                    
                    $stmt->execute([$date, $heure, $type, $numeroUrap, $id_prescripteur]);
                    $visitesAjoutees++;
                }
                
                $message = "$visitesAjoutees visites ajoutées avec succès !";
                $messageType = 'success';
                break;
                
            case 'optimiser_auto':
                // Ajouter 2-5 visites à chaque patient
                $stmt = $pdo->query("SELECT Numero_urap FROM patient");
                $patients = $stmt->fetchAll(PDO::FETCH_COLUMN);
                
                $stmt = $pdo->query("SELECT ID_prescripteur FROM prescriteur ORDER BY RAND() LIMIT 1");
                $prescripteur = $stmt->fetch();
                $id_prescripteur = $prescripteur ? $prescripteur['ID_prescripteur'] : 1;
                
                $totalAjoute = 0;
                $motifs = ['consultation', 'controle', 'depistage'];
                
                foreach ($patients as $numeroUrap) {
                    $nbVisites = rand(2, 5);
                    for ($i = 0; $i < $nbVisites; $i++) {
                        $date = date('Y-m-d', strtotime('-' . rand(0, 60) . ' days'));
                        $heure = sprintf("%02d:%02d", rand(8, 17), rand(0, 59));
                        $motif = $motifs[array_rand($motifs)];
                        
                        $stmt = $pdo->prepare("
                            INSERT INTO visite (
                                date_visite, `Heure visite`, `Motif visite`, Numero_urap, 
                                ID_prescripteur, Structure_provenance, ID_antecedents, ID_antecedent,
                                ID_histoire_maladie, ID_habitude_sexuelles
                            ) VALUES (?, ?, ?, ?, ?, 'ipci', NULL, 0, NULL, NULL)
                        ");
                        
                        $stmt->execute([$date, $heure, $motif, $numeroUrap, $id_prescripteur]);
                        $totalAjoute++;
                    }
                }
                
                $message = "Optimisation terminée ! $totalAjoute visites ajoutées.";
                $messageType = 'success';
                break;
        }
    } catch (Exception $e) {
        $message = "Erreur : " . $e->getMessage();
        $messageType = 'error';
    }
}

// Récupération des paramètres de recherche/filtre améliorés
$search = $_GET['search'] ?? '';
$sexe_filter = $_GET['sexe'] ?? '';
$age_min = $_GET['age_min'] ?? '';
$age_max = $_GET['age_max'] ?? '';
$situation_filter = $_GET['situation'] ?? '';
$lieu_filter = $_GET['lieu'] ?? '';

// D'abord, vérifier si la table visite a des données et quelles colonnes elle a
try {
    // Test simple pour voir si la table visite existe et a des colonnes
    $test_stmt = $pdo->prepare("SHOW COLUMNS FROM visite");
    $test_stmt->execute();
    $columns = $test_stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Vérifier si la table visite a des données
    $count_stmt = $pdo->prepare("SELECT COUNT(*) as total FROM visite");
    $count_stmt->execute();
    $visite_count = $count_stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    // Si la table visite est vide ou problématique, utiliser une requête simple
    if ($visite_count == 0) {
        // Requête simple sans jointure sur visite puisque la table est vide
        $sql = "SELECT p.*, 0 as nb_visites, NULL as derniere_visite
                FROM patient p 
                WHERE 1=1";
    } else {
        // Requête normale avec jointure
        $sql = "SELECT p.*, 
                       COALESCE(COUNT(v.id_visite), 0) as nb_visites, 
                       MAX(v.date_visite) as derniere_visite
                FROM patient p 
                LEFT JOIN visite v ON p.Numero_urap = v.Numero_urap 
                WHERE 1=1";
    }
    
} catch (PDOException $e) {
    // En cas d'erreur, utiliser une requête simple sans visite
    $sql = "SELECT p.*, 0 as nb_visites, NULL as derniere_visite
            FROM patient p 
            WHERE 1=1";
    $visite_count = 0;
}

$params = [];

// Ajout des conditions de recherche améliorées
if ($search) {
    $sql .= " AND (p.Nom_patient LIKE ? OR p.Prenom_patient LIKE ? OR p.Numero_urap LIKE ? OR p.Contact_patient LIKE ?)";
    $searchParam = "%$search%";
    $params[] = $searchParam;
    $params[] = $searchParam;
    $params[] = $searchParam;
    $params[] = $searchParam;
}

if ($sexe_filter) {
    $sql .= " AND p.Sexe_patient = ?";
    $params[] = $sexe_filter;
}

if ($age_min) {
    $sql .= " AND p.Age >= ?";
    $params[] = $age_min;
}

if ($age_max) {
    $sql .= " AND p.Age <= ?";
    $params[] = $age_max;
}

if ($situation_filter) {
    $sql .= " AND p.Situation_matrimoniale = ?";
    $params[] = $situation_filter;
}

if ($lieu_filter) {
    $sql .= " AND p.Lieu_résidence LIKE ?";
    $params[] = "%$lieu_filter%";
}

// Ajouter GROUP BY seulement si on a fait une jointure
if ($visite_count > 0) {
    $sql .= " GROUP BY p.Numero_urap";
}

$sql .= " ORDER BY p.Nom_patient, p.Prenom_patient";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $patients = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // En cas d'erreur, afficher le message et utiliser une requête de base
    echo "<!-- DEBUG: Erreur SQL - " . $e->getMessage() . " -->";
    
    // Requête de secours très simple
    $sql_simple = "SELECT *, 0 as nb_visites, NULL as derniere_visite FROM patient ORDER BY Nom_patient, Prenom_patient";
    $stmt = $pdo->prepare($sql_simple);
    $stmt->execute();
    $patients = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Statistiques
$total_patients = count($patients);
$total_visites = array_sum(array_column($patients, 'nb_visites'));

// Récupérer les options pour les filtres
$situations = $pdo->query("SELECT DISTINCT Situation_matrimoniale FROM patient WHERE Situation_matrimoniale != ''")->fetchAll(PDO::FETCH_COLUMN);
$lieux = $pdo->query("SELECT DISTINCT Lieu_résidence FROM patient WHERE Lieu_résidence != ''")->fetchAll(PDO::FETCH_COLUMN);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Dossiers - UATG</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary: #0047ab;
            --primary-light: #1e90ff;
            --success: #22c55e;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #06b6d4;
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
        }

        .container {
            max-width: 1400px;
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

        .alert {
            padding: 16px 20px;
            margin-bottom: 24px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
            animation: slideIn 0.5s ease;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .alert-info {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #fbbf24;
        }

        @keyframes slideIn {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .stats-bar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 24px;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        .stat-card {
            text-align: center;
            padding: 16px;
            border-radius: 12px;
            background: var(--gray-50);
            border-left: 4px solid var(--primary);
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 4px;
        }

        .stat-label {
            color: var(--gray-600);
            font-weight: 500;
        }

        .amelioration-section {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        }

        .amelioration-title {
            color: var(--gray-700);
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .amelioration-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        .amelioration-card {
            background: var(--gray-50);
            padding: 20px;
            border-radius: 12px;
            border-left: 4px solid var(--success);
        }

        .filters-section {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        }

        .filters-title {
            color: var(--gray-700);
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .filters-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 20px;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .filter-label {
            color: var(--gray-700);
            font-weight: 500;
            font-size: 0.9rem;
        }

        .filter-input {
            padding: 10px 12px;
            border: 1px solid var(--gray-300);
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.2s ease;
        }

        .filter-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgb(0 71 171 / 0.1);
        }

        .filter-actions {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: white;
        }

        .btn-success {
            background: var(--success);
            color: white;
        }

        .btn-warning {
            background: var(--warning);
            color: white;
        }

        .btn-secondary {
            background: white;
            color: var(--gray-700);
            border: 1px solid var(--gray-300);
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        }

        .dossiers-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 24px;
            margin-bottom: 24px;
        }

        .dossier-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 24px;
            box-shadow: 0 8px 25px -5px rgb(0 0 0 / 0.1);
            transition: all 0.3s ease;
            cursor: pointer;
            text-decoration: none;
            color: inherit;
            position: relative;
            overflow: hidden;
            border: 2px solid transparent;
        }

        .dossier-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
        }

        .dossier-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 20px 40px -5px rgb(0 0 0 / 0.15);
            border-color: var(--primary-light);
        }

        .dossier-header {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 20px;
        }

        .patient-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.8rem;
            box-shadow: 0 4px 12px rgb(0 71 171 / 0.3);
        }

        .patient-info h3 {
            color: var(--gray-800);
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .patient-meta {
            color: var(--gray-600);
            font-size: 0.9rem;
            line-height: 1.4;
        }

        .dossier-stats {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
            margin-bottom: 20px;
        }

        .stat-item {
            text-align: center;
            padding: 12px;
            background: var(--gray-50);
            border-radius: 12px;
            border-left: 3px solid var(--primary);
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 2px;
        }

        .stat-label-small {
            font-size: 0.8rem;
            color: var(--gray-600);
            font-weight: 500;
        }

        .dossier-details {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .detail-row {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
        }

        .detail-icon {
            color: var(--primary);
            width: 16px;
            text-align: center;
        }

        .detail-text {
            color: var(--gray-700);
            flex: 1;
        }

        .derniere-visite {
            background: var(--success);
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .aucune-visite {
            background: var(--gray-400);
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .dossier-actions {
            position: absolute;
            top: 16px;
            right: 16px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .dossier-card:hover .dossier-actions {
            opacity: 1;
        }

        .action-btn {
            background: rgba(255, 255, 255, 0.9);
            border: none;
            border-radius: 50%;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: var(--primary);
            font-size: 1rem;
            transition: all 0.2s ease;
            margin-bottom: 8px;
        }

        .action-btn:hover {
            background: var(--primary);
            color: white;
            transform: scale(1.1);
        }

        .no-data {
            text-align: center;
            padding: 80px 20px;
            color: var(--gray-500);
        }

        .no-data i {
            font-size: 4rem;
            margin-bottom: 16px;
            opacity: 0.5;
        }

        .actions-bar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            display: flex;
            justify-content: center;
            gap: 16px;
        }

        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }
            
            .dossiers-grid {
                grid-template-columns: 1fr;
                gap: 16px;
            }
            
            .filters-grid, .amelioration-grid {
                grid-template-columns: 1fr;
            }
            
            .dossier-stats {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-folder-open"></i> Liste des Dossiers</h1>
            <p>Cliquez sur un dossier patient pour voir ses visites</p>
        </div>

        <?php if ($message): ?>
        <div class="alert alert-<?= $messageType ?>">
            <i class="fas fa-<?= $messageType == 'success' ? 'check-circle' : ($messageType == 'error' ? 'exclamation-triangle' : 'info-circle') ?>"></i>
            <strong><?= htmlspecialchars($message) ?></strong>
        </div>
        <?php endif; ?>

        <?php if ($visite_count == 0): ?>
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i>
            <strong>Information :</strong> Aucune visite n'est encore enregistrée dans le système. 
            Utilisez les outils d'amélioration ci-dessous pour ajouter des visites.
        </div>
        <?php endif; ?>

        <div class="stats-bar">
            <div class="stat-card">
                <div class="stat-number"><?= $total_patients ?></div>
                <div class="stat-label">Patients</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $total_visites ?></div>
                <div class="stat-label">Visites</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $total_visites > 0 ? round($total_visites / max($total_patients, 1), 1) : 0 ?></div>
                <div class="stat-label">Visites/Patient</div>
            </div>
        </div>


        <div class="filters-section">
            <h3 class="filters-title">
                <i class="fas fa-search"></i>
                Recherche Avancée
            </h3>
            <form method="GET" class="filters-form">
                <div class="filters-grid">
                    <div class="filter-group">
                        <label class="filter-label">Recherche Générale</label>
                        <input type="text" name="search" class="filter-input" 
                               value="<?= htmlspecialchars($search) ?>" 
                               placeholder="Nom, prénom, N°URAP, téléphone...">
                    </div>
                    <div class="filter-group">
                        <label class="filter-label">Sexe</label>
                        <select name="sexe" class="filter-input">
                            <option value="">Tous</option>
                            <option value="Masculin" <?= $sexe_filter === 'Masculin' ? 'selected' : '' ?>>Masculin</option>
                            <option value="Féminin" <?= $sexe_filter === 'Féminin' ? 'selected' : '' ?>>Féminin</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label class="filter-label">Âge Min</label>
                        <input type="number" name="age_min" class="filter-input" 
                               value="<?= htmlspecialchars($age_min) ?>" 
                               placeholder="Ex: 18" min="0" max="120">
                    </div>
                    <div class="filter-group">
                        <label class="filter-label">Âge Max</label>
                        <input type="number" name="age_max" class="filter-input" 
                               value="<?= htmlspecialchars($age_max) ?>" 
                               placeholder="Ex: 65" min="0" max="120">
                    </div>
                    <div class="filter-group">
                        <label class="filter-label">Situation Matrimoniale</label>
                        <select name="situation" class="filter-input">
                            <option value="">Toutes</option>
                            <?php foreach ($situations as $situation): ?>
                            <option value="<?= htmlspecialchars($situation) ?>" 
                                    <?= $situation_filter === $situation ? 'selected' : '' ?>>
                                <?= htmlspecialchars($situation) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label class="filter-label">Lieu de Résidence</label>
                        <select name="lieu" class="filter-input">
                            <option value="">Tous</option>
                            <?php foreach ($lieux as $lieu): ?>
                            <option value="<?= htmlspecialchars($lieu) ?>" 
                                    <?= $lieu_filter === $lieu ? 'selected' : '' ?>>
                                <?= htmlspecialchars($lieu) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="filter-actions">
                    <a href="?" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Réinitialiser
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Rechercher
                    </button>
                </div>
            </form>
        </div>

        <?php if (empty($patients)): ?>
            <div class="no-data">
                <i class="fas fa-folder-open"></i>
                <h3>Aucun dossier trouvé</h3>
                <p>Aucun patient ne correspond aux critères de recherche.</p>
            </div>
        <?php else: ?>
            <div class="dossiers-grid">
                <?php foreach ($patients as $patient): ?>
                    <a href="visite_patient.php?urap=<?= htmlspecialchars($patient['Numero_urap']) ?>" class="dossier-card">
                        <div class="dossier-actions">
                            <button class="action-btn" onclick="event.stopPropagation(); window.location.href='visite.php?idU=<?= htmlspecialchars($patient['Numero_urap']) ?>'" title="Nouvelle visite">
                                <i class="fas fa-plus"></i>
                            </button>
                            <button class="action-btn" onclick="event.stopPropagation(); window.location.href='visite_patient.php?urap=<?= htmlspecialchars($patient['Numero_urap']) ?>'" title="Voir dossier complet">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>

                        <div class="dossier-header">
                            <div class="patient-avatar">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="patient-info">
                                <h3><?= htmlspecialchars($patient['Nom_patient'] . ' ' . $patient['Prenom_patient']) ?></h3>
                                <div class="patient-meta">
                                    <strong>N°URAP:</strong> <?= htmlspecialchars($patient['Numero_urap']) ?><br>
                                    <strong>Âge:</strong> <?= htmlspecialchars($patient['Age']) ?> ans | 
                                    <strong>Sexe:</strong> <?= htmlspecialchars($patient['Sexe_patient']) ?>
                                </div>
                            </div>
                        </div>

                        <div class="dossier-stats">
                            <div class="stat-item">
                                <div class="stat-value"><?= $patient['nb_visites'] ?></div>
                                <div class="stat-label-small">Visites</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-value"><?= htmlspecialchars($patient['Age']) ?></div>
                                <div class="stat-label-small">Ans</div>
                            </div>
                        </div>

                        <div class="dossier-details">
                            <div class="detail-row">
                                <i class="fas fa-phone detail-icon"></i>
                                <span class="detail-text"><?= htmlspecialchars($patient['Contact_patient']) ?></span>
                            </div>
                            <div class="detail-row">
                                <i class="fas fa-map-marker-alt detail-icon"></i>
                                <span class="detail-text"><?= htmlspecialchars($patient['Lieu_résidence']) ?></span>
                            </div>
                            <div class="detail-row">
                                <i class="fas fa-calendar-alt detail-icon"></i>
                                <span class="detail-text">
                                    <?php if ($patient['derniere_visite']): ?>
                                        Dernière visite: 
                                        <span class="derniere-visite">
                                            <?= htmlspecialchars(date('d/m/Y', strtotime($patient['derniere_visite']))) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="aucune-visite">Aucune visite</span>
                                    <?php endif; ?>
                                </span>
                            </div>
                            <div class="detail-row">
                                <i class="fas fa-ring detail-icon"></i>
                                <span class="detail-text"><?= htmlspecialchars($patient['Situation_matrimoniale']) ?></span>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="actions-bar">
            <a href="acceuil.php" class="btn btn-secondary">
                <i class="fas fa-home"></i> Accueil
            </a>
            <a href="nouveau_dossier.php" class="btn btn-primary">
                <i class="fas fa-user-plus"></i> Nouveau Dossier
            </a>
        </div>
    </div>

    <script>
        // Animation d'entrée pour les cartes
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.dossier-card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(30px)';
                setTimeout(() => {
                    card.style.transition = 'all 0.6s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 150);
            });
        });
    </script>
</body>
</html>