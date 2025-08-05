<?php
require_once("identifier.php");
require_once("connexion.php");

// Récupération des paramètres de recherche/filtre
$search = $_GET['search'] ?? '';
$date_debut = $_GET['date_debut'] ?? '';
$date_fin = $_GET['date_fin'] ?? '';
$sexe_filter = $_GET['sexe'] ?? '';

// Construction de la requête de base
$sql = "SELECT DISTINCT p.*, v.id_visite, v.`Date visite`, v.`Heure visite`, v.`Motif visite`, v.Structure_provenance
        FROM patient p 
        LEFT JOIN visite v ON p.Numero_urap = v.Numero_urap 
        WHERE 1=1";

$params = [];

// Ajout des conditions de recherche
if ($search) {
    $sql .= " AND (p.Nom_patient LIKE ? OR p.Prenom_patient LIKE ? OR p.Numero_urap LIKE ?)";
    $searchParam = "%$search%";
    $params[] = $searchParam;
    $params[] = $searchParam;
    $params[] = $searchParam;
}

if ($date_debut) {
    $sql .= " AND v.`Date visite` >= ?";
    $params[] = $date_debut;
}

if ($date_fin) {
    $sql .= " AND v.`Date visite` <= ?";
    $params[] = $date_fin;
}

if ($sexe_filter) {
    $sql .= " AND p.Sexe_patient = ?";
    $params[] = $sexe_filter;
}

// CORRECTION : Utiliser le bon nom de colonne avec backticks
$sql .= " ORDER BY p.Nom_patient, p.Prenom_patient, p.`date_visite` DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Regroupement des données par patient
$patients = [];
foreach ($results as $row) {
    $urap = $row['Numero_urap'];
    
    if (!isset($patients[$urap])) {
        $patients[$urap] = [
            'info' => [
                'Numero_urap' => $row['Numero_urap'],
                'Nom_patient' => $row['Nom_patient'],
                'Prenom_patient' => $row['Prenom_patient'],
                'Age' => $row['Age'],
                'Sexe_patient' => $row['Sexe_patient'],
                'Contact_patient' => $row['Contact_patient'],
                'Situation_matrimoniale' => $row['Situation_matrimoniale'],
                'Lieu_résidence' => $row['Lieu_résidence']
            ],
            'visites' => []
        ];
    }
    
    // Ajouter la visite si elle existe
    if ($row['id_visite']) {
        $patients[$urap]['visites'][] = [
            'id_visite' => $row['id_visite'],
            'Date visite' => $row['Date visite'],
            'Heure visite' => $row['Heure visite'],
            'Motif visite' => $row['Motif visite'],
            'Structure_provenance' => $row['Structure_provenance']
        ];
    }
}

// Statistiques
$total_patients = count($patients);
$total_visites = 0;
foreach ($patients as $patient) {
    $total_visites += count($patient['visites']);
}
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
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
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
            font-size: 14px;
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
            font-size: 14px;
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

        .btn-secondary {
            background: white;
            color: var(--gray-700);
            border: 1px solid var(--gray-300);
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        }

        .patients-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .patient-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .patient-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1);
        }

        .patient-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: white;
            padding: 20px 24px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .patient-info {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .patient-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .patient-details h3 {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .patient-meta {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .patient-stats {
            display: flex;
            align-items: center;
            gap: 20px;
            text-align: center;
        }

        .patient-stat {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .patient-stat-number {
            font-size: 1.2rem;
            font-weight: 700;
        }

        .patient-stat-label {
            font-size: 0.8rem;
            opacity: 0.8;
        }

        .expand-icon {
            font-size: 1.2rem;
            transition: transform 0.3s ease;
        }

        .expand-icon.rotated {
            transform: rotate(180deg);
        }

        .patient-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }

        .patient-content.expanded {
            max-height: 1000px;
        }

        .patient-body {
            padding: 24px;
        }

        .patient-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            background: var(--gray-50);
            border-radius: 8px;
        }

        .info-icon {
            color: var(--primary);
            font-size: 1.1rem;
        }

        .info-content {
            flex: 1;
        }

        .info-label {
            font-size: 0.8rem;
            color: var(--gray-600);
            margin-bottom: 2px;
        }

        .info-value {
            font-weight: 600;
            color: var(--gray-800);
        }

        .visites-section {
            border-top: 2px solid var(--gray-200);
            padding-top: 20px;
        }

        .visites-title {
            color: var(--gray-700);
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .visites-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 16px;
        }

        .visite-card {
            background: white;
            border: 1px solid var(--gray-200);
            border-radius: 12px;
            padding: 16px;
            transition: all 0.2s ease;
            position: relative;
            cursor: pointer;
        }

        .visite-card:hover {
            border-color: var(--primary);
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            transform: translateY(-2px);
        }

        .visite-date {
            color: var(--primary);
            font-weight: 700;
            font-size: 1rem;
            margin-bottom: 8px;
        }

        .visite-details {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .visite-detail {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
        }

        .visite-detail i {
            color: var(--gray-500);
            width: 16px;
        }

        .visite-actions {
            position: absolute;
            top: 12px;
            right: 12px;
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 0.8rem;
        }

        .no-data {
            text-align: center;
            padding: 60px 20px;
            color: var(--gray-500);
        }

        .no-data i {
            font-size: 4rem;
            margin-bottom: 16px;
            opacity: 0.5;
        }

        .empty-visites {
            text-align: center;
            padding: 30px;
            color: var(--gray-500);
            font-style: italic;
        }

        .actions-bar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 20px;
            margin-top: 24px;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            display: flex;
            justify-content: center;
            gap: 16px;
        }

        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }
            
            .filters-grid {
                grid-template-columns: 1fr;
            }
            
            .patient-header {
                flex-direction: column;
                gap: 16px;
                text-align: center;
            }
            
            .patient-info-grid,
            .visites-grid {
                grid-template-columns: 1fr;
            }
            
            .patient-stats {
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-folder-open"></i> Liste des Dossiers</h1>
            <p>Gestion et consultation des dossiers patients</p>
        </div>

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
                <i class="fas fa-filter"></i>
                Filtres et recherche
            </h3>
            <form method="GET" class="filters-form">
                <div class="filters-grid">
                    <div class="filter-group">
                        <label class="filter-label">Recherche (Nom, Prénom, N°URAP)</label>
                        <input type="text" name="search" class="filter-input" 
                               value="<?= htmlspecialchars($search) ?>" 
                               placeholder="Rechercher un patient...">
                    </div>
                    <div class="filter-group">
                        <label class="filter-label">Date début</label>
                        <input type="date" name="date_debut" class="filter-input" 
                               value="<?= htmlspecialchars($date_debut) ?>">
                    </div>
                    <div class="filter-group">
                        <label class="filter-label">Date fin</label>
                        <input type="date" name="date_fin" class="filter-input" 
                               value="<?= htmlspecialchars($date_fin) ?>">
                    </div>
                    <div class="filter-group">
                        <label class="filter-label">Sexe</label>
                        <select name="sexe" class="filter-input">
                            <option value="">Tous</option>
                            <option value="Masculin" <?= $sexe_filter === 'Masculin' ? 'selected' : '' ?>>Masculin</option>
                            <option value="Féminin" <?= $sexe_filter === 'Féminin' ? 'selected' : '' ?>>Féminin</option>
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

        <div class="patients-list">
            <?php if (empty($patients)): ?>
                <div class="no-data">
                    <i class="fas fa-folder-open"></i>
                    <h3>Aucun dossier trouvé</h3>
                    <p>Aucun patient ne correspond aux critères de recherche.</p>
                </div>
            <?php else: ?>
                <?php foreach ($patients as $urap => $patient): ?>
                    <div class="patient-card">
                        <div class="patient-header" onclick="togglePatient('patient-<?= $urap ?>')">
                            <div class="patient-info">
                                <div class="patient-avatar">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="patient-details">
                                    <h3><?= htmlspecialchars($patient['info']['Nom_patient'] . ' ' . $patient['info']['Prenom_patient']) ?></h3>
                                    <div class="patient-meta">
                                        N°URAP: <?= htmlspecialchars($patient['info']['Numero_urap']) ?> | 
                                        <?= htmlspecialchars($patient['info']['Age']) ?> ans | 
                                        <?= htmlspecialchars($patient['info']['Sexe_patient']) ?>
                                    </div>
                                </div>
                            </div>
                            <div class="patient-stats">
                                <div class="patient-stat">
                                    <div class="patient-stat-number"><?= count($patient['visites']) ?></div>
                                    <div class="patient-stat-label">Visites</div>
                                </div>
                                <div class="expand-icon" id="icon-patient-<?= $urap ?>">
                                    <i class="fas fa-chevron-down"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="patient-content" id="content-patient-<?= $urap ?>">
                            <div class="patient-body">
                                <div class="patient-info-grid">
                                    <div class="info-item">
                                        <i class="fas fa-phone info-icon"></i>
                                        <div class="info-content">
                                            <div class="info-label">Contact</div>
                                            <div class="info-value"><?= htmlspecialchars($patient['info']['Contact_patient']) ?></div>
                                        </div>
                                    </div>
                                    <div class="info-item">
                                        <i class="fas fa-map-marker-alt info-icon"></i>
                                        <div class="info-content">
                                            <div class="info-label">Résidence</div>
                                            <div class="info-value"><?= htmlspecialchars($patient['info']['Lieu_résidence']) ?></div>
                                        </div>
                                    </div>
                                    <div class="info-item">
                                        <i class="fas fa-ring info-icon"></i>
                                        <div class="info-content">
                                            <div class="info-label">Situation matrimoniale</div>
                                            <div class="info-value"><?= htmlspecialchars($patient['info']['Situation_matrimoniale']) ?></div>
                                        </div>
                                    </div>
                                    <div class="info-item">
                                        <i class="fas fa-plus-circle info-icon"></i>
                                        <div class="info-content">
                                            <div class="info-label">Nouvelle visite</div>
                                            <div class="info-value">
                                                <a href="visite.php?idU=<?= htmlspecialchars($patient['info']['Numero_urap']) ?>" 
                                                   class="btn btn-primary btn-sm">
                                                    <i class="fas fa-calendar-plus"></i> Nouvelle visite
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="info-item">
                                        <i class="fas fa-file-medical info-icon"></i>
                                        <div class="info-content">
                                            <div class="info-label">Dossier complet</div>
                                            <div class="info-value">
                                                <a href="visites_patient.php?urap=<?= htmlspecialchars($patient['info']['Numero_urap']) ?>" 
                                                   class="btn btn-secondary btn-sm">
                                                    <i class="fas fa-folder-open"></i> Voir dossier
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="visites-section">
                                    <h4 class="visites-title">
                                        <i class="fas fa-calendar-alt"></i>
                                        Historique des visites (<?= count($patient['visites']) ?>)
                                    </h4>
                                    
                                    <?php if (empty($patient['visites'])): ?>
                                        <div class="empty-visites">
                                            <i class="fas fa-calendar-times"></i>
                                            <p>Aucune visite enregistrée pour ce patient</p>
                                            <a href="visite.php?idU=<?= htmlspecialchars($patient['info']['Numero_urap']) ?>" 
                                               class="btn btn-primary" style="margin-top: 12px;">
                                                <i class="fas fa-calendar-plus"></i> Créer la première visite
                                            </a>
                                        </div>
                                    <?php else: ?>
                                        <div class="visites-grid">
                                            <?php foreach ($patient['visites'] as $visite): ?>
                                                <div class="visite-card" onclick="window.location.href='dossier_complet.php?urap=<?= htmlspecialchars($patient['info']['Numero_urap']) ?>&visite_id=<?= htmlspecialchars($visite['id_visite']) ?>'">
                                                    <div class="visite-actions" onclick="event.stopPropagation()">
                                                        <a href="dossier_complet.php?urap=<?= htmlspecialchars($patient['info']['Numero_urap']) ?>&visite_id=<?= htmlspecialchars($visite['id_visite']) ?>" 
                                                           class="btn btn-primary btn-sm">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    </div>
                                                    <div class="visite-date">
                                                        <?= htmlspecialchars(date('d/m/Y', strtotime($visite['Date visite']))) ?>
                                                    </div>
                                                    <div class="visite-details">
                                                        <div class="visite-detail">
                                                            <i class="fas fa-clock"></i>
                                                            <span><?= htmlspecialchars($visite['Heure visite']) ?></span>
                                                        </div>
                                                        <div class="visite-detail">
                                                            <i class="fas fa-stethoscope"></i>
                                                            <span><?= htmlspecialchars($visite['Motif visite']) ?></span>
                                                        </div>
                                                        <?php if ($visite['Structure_provenance']): ?>
                                                        <div class="visite-detail">
                                                            <i class="fas fa-hospital"></i>
                                                            <span><?= htmlspecialchars($visite['Structure_provenance']) ?></span>
                                                        </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="actions-bar">
            <a href="accueil.php" class="btn btn-secondary">
                <i class="fas fa-home"></i> Accueil
            </a>
            <a href="patient.php" class="btn btn-primary">
                <i class="fas fa-user-plus"></i> Nouveau patient
            </a>
        </div>
    </div>

    <script>
        function togglePatient(patientId) {
            const content = document.getElementById('content-' + patientId);
            const icon = document.getElementById('icon-' + patientId);
            
            if (content.classList.contains('expanded')) {
                content.classList.remove('expanded');
                icon.classList.remove('rotated');
            } else {
                content.classList.add('expanded');
                icon.classList.add('rotated');
            }
        }

        // Animation d'entrée pour les cartes
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.patient-card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    card.style.transition = 'all 0.5s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });
    </script>
</body>
</html>