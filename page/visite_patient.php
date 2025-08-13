<?php
require_once("identifier.php");
require_once("connexion.php");

$numero_urap = $_GET['urap'] ?? '';

if (!$numero_urap) {
    echo "<div class='alert alert-danger'>Aucun numéro URAP fourni.</div>";
    exit;
}

// Récupération des informations du patient
$stmt = $pdo->prepare("SELECT * FROM patient WHERE Numero_urap = ?");
$stmt->execute([$numero_urap]);
$patient = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$patient) {
    echo "<div class='alert alert-danger'>Patient non trouvé.</div>";
    exit;
}

// Récupération de toutes les visites du patient avec prescripteur
$stmt = $pdo->prepare("
    SELECT v.*, p.Nom as prescripteur_nom, p.Prenom as prescripteur_prenom 
    FROM visite v 
    LEFT JOIN prescriteur p ON v.ID_prescripteur = p.ID_prescripteur 
    WHERE v.Numero_urap = ? 
    ORDER BY v.date_visite DESC, v.`Heure visite` DESC
");
$stmt->execute([$numero_urap]);
$visites = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Statistiques
$total_visites = count($visites);
$visites_this_year = 0;
$visites_this_month = 0;
$current_year = date('Y');
$current_month = date('Y-m');

foreach ($visites as $visite) {
    if (date('Y', strtotime($visite['date_visite'])) == $current_year) {
        $visites_this_year++;
    }
    if (date('Y-m', strtotime($visite['date_visite'])) == $current_month) {
        $visites_this_month++;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visites de <?= htmlspecialchars($patient['Nom_patient'] . ' ' . $patient['Prenom_patient']) ?> - UATG</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary: #0047ab;
            --primary-light: #1e90ff;
            --success: #22c55e;
            --warning: #f59e0b;
            --danger: #ef4444;
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
            font-size: 2.2rem;
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

        .patient-overview {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 32px;
            margin-bottom: 24px;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            border-left: 4px solid var(--primary);
        }

        .patient-header {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 24px;
            align-items: center;
            margin-bottom: 24px;
        }

        .patient-main {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .patient-avatar {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
        }

        .patient-info h2 {
            color: var(--primary);
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .patient-meta {
            color: var(--gray-600);
            font-size: 1rem;
            line-height: 1.5;
        }

        .patient-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 16px;
        }

        .stat-card {
            text-align: center;
            padding: 16px;
            background: var(--gray-50);
            border-radius: 12px;
            border-left: 4px solid var(--primary);
        }

        .stat-number {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 4px;
        }

        .stat-label {
            color: var(--gray-600);
            font-size: 0.85rem;
            font-weight: 500;
        }

        .actions-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        }

        .section-title {
            color: var(--gray-700);
            font-size: 1.4rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
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

        .visites-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .visite-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            transition: all 0.3s ease;
            cursor: pointer;
            border-left: 4px solid var(--primary);
            position: relative;
        }

        .visite-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1);
            border-left-color: var(--success);
        }

        .visite-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 16px;
        }

        .visite-date-time {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .visite-date {
            color: var(--primary);
            font-size: 1.3rem;
            font-weight: 700;
        }

        .visite-card:hover .visite-date {
            color: var(--success);
        }

        .visite-time {
            color: var(--gray-600);
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .visite-id {
            background: var(--gray-100);
            color: var(--gray-700);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .visite-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 16px;
        }

        .visite-detail {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            background: var(--gray-50);
            border-radius: 8px;
        }

        .detail-icon {
            color: var(--primary);
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
        }

        .detail-content {
            flex: 1;
        }

        .detail-label {
            font-size: 0.8rem;
            color: var(--gray-600);
            margin-bottom: 2px;
        }

        .detail-value {
            font-weight: 600;
            color: var(--gray-800);
        }

        .visite-actions {
            position: absolute;
            top: 16px;
            right: 16px;
            opacity: 0;
            transition: opacity 0.3s ease;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .visite-card:hover .visite-actions {
            opacity: 1;
        }

        .action-btn {
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid var(--gray-200);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: var(--primary);
            font-size: 1rem;
            transition: all 0.2s ease;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .action-btn:hover {
            background: var(--primary);
            color: white;
            transform: scale(1.1);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }

        .action-btn.dossier:hover {
            background: var(--success);
        }

        .action-btn.edit:hover {
            background: var(--warning);
        }

        .empty-state {
            text-align: center;
            padding: 80px 20px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        }

        .empty-state i {
            font-size: 4rem;
            color: var(--gray-400);
            margin-bottom: 16px;
        }

        .empty-state h3 {
            color: var(--gray-700);
            font-size: 1.5rem;
            margin-bottom: 8px;
        }

        .empty-state p {
            color: var(--gray-600);
            margin-bottom: 24px;
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

        .recent-badge {
            background: var(--success);
            color: white;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 0.7rem;
            font-weight: 600;
            margin-left: 8px;
        }

        .today-badge {
            background: var(--warning);
            color: white;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 0.7rem;
            font-weight: 600;
            margin-left: 8px;
        }

        /* Tooltip pour les boutons d'action */
        .action-btn[title]:hover:after {
            content: attr(title);
            position: absolute;
            bottom: -35px;
            left: 50%;
            transform: translateX(-50%);
            background: var(--gray-800);
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.7rem;
            white-space: nowrap;
            z-index: 1000;
        }

        /* Message d'indication pour les clics */
        .click-indicator {
            position: absolute;
            bottom: 8px;
            right: 8px;
            background: rgba(0, 71, 171, 0.1);
            color: var(--primary);
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.7rem;
            font-weight: 600;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .visite-card:hover .click-indicator {
            opacity: 1;
        }

        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }
            
            .patient-header {
                grid-template-columns: 1fr;
                text-align: center;
            }
            
            .visite-content {
                grid-template-columns: 1fr;
            }
            
            .actions-header {
                flex-direction: column;
                gap: 16px;
                text-align: center;
            }

            .visite-actions {
                position: relative;
                opacity: 1;
                flex-direction: row;
                justify-content: center;
                margin-top: 16px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-calendar-alt"></i> Visites du Patient</h1>
            <p>Cliquez sur une visite pour accéder au dossier complet correspondant</p>
        </div>

        <div class="patient-overview">
            <div class="patient-header">
                <div class="patient-main">
                    <div class="patient-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="patient-info">
                        <h2><?= htmlspecialchars($patient['Nom_patient'] . ' ' . $patient['Prenom_patient']) ?></h2>
                        <div class="patient-meta">
                            <strong>N°URAP:</strong> <?= htmlspecialchars($patient['Numero_urap']) ?><br>
                            <strong>Âge:</strong> <?= htmlspecialchars($patient['Age']) ?> ans | 
                            <strong>Sexe:</strong> <?= htmlspecialchars($patient['Sexe_patient']) ?><br>
                            <strong>Contact:</strong> <?= htmlspecialchars($patient['Contact_patient']) ?> | 
                            <strong>Résidence:</strong> <?= htmlspecialchars($patient['Lieu_résidence']) ?>
                        </div>
                    </div>
                </div>
                
                <div class="patient-stats">
                    <div class="stat-card">
                        <div class="stat-number"><?= $total_visites ?></div>
                        <div class="stat-label">Total visites</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number"><?= $visites_this_year ?></div>
                        <div class="stat-label">Cette année</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number"><?= $visites_this_month ?></div>
                        <div class="stat-label">Ce mois</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="actions-header">
            <h2 class="section-title">
                <i class="fas fa-history"></i>
                Historique des visites (<?= $total_visites ?>)
            </h2>
            <a href="visite.php?idU=<?= htmlspecialchars($patient['Numero_urap']) ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nouvelle visite
            </a>
        </div>

        <?php if (empty($visites)): ?>
            <div class="empty-state">
                <i class="fas fa-calendar-times"></i>
                <h3>Aucune visite enregistrée</h3>
                <p>Ce patient n'a encore aucune visite dans le système.</p>
                <a href="visite.php?idU=<?= htmlspecialchars($patient['Numero_urap']) ?>" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Créer la première visite
                </a>
            </div>
        <?php else: ?>
            <div class="visites-list">
                <?php foreach ($visites as $index => $visite): ?>
                    <?php 
                    $date_visite = date('d/m/Y', strtotime($visite['date_visite']));
                    $is_today = date('Y-m-d') == $visite['date_visite'];
                    $is_recent = (strtotime('now') - strtotime($visite['date_visite'])) <= (7 * 24 * 60 * 60); // 7 jours
                    ?>
                    <div class="visite-card" onclick="window.location.href='dossier_complet.php?urap=<?= htmlspecialchars($patient['Numero_urap']) ?>&visite_id=<?= htmlspecialchars($visite['id_visite']) ?>'">
                        <div class="visite-actions" onclick="event.stopPropagation()">
                            <button class="action-btn dossier" onclick="window.location.href='dossier_complet.php?urap=<?= htmlspecialchars($patient['Numero_urap']) ?>&visite_id=<?= htmlspecialchars($visite['id_visite']) ?>'" title="Dossier complet de cette visite">
                                <i class="fas fa-file-medical"></i>
                            </button>
                            <button class="action-btn edit" onclick="window.location.href='visite.php?idU=<?= htmlspecialchars($patient['Numero_urap']) ?>&edit_visite=<?= htmlspecialchars($visite['id_visite']) ?>'" title="Modifier cette visite">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>

                        <div class="click-indicator">
                            <i class="fas fa-mouse-pointer"></i> Cliquer pour le dossier complet
                        </div>

                        <div class="visite-header">
                            <div class="visite-date-time">
                                <div class="visite-date">
                                    Visite du <?= htmlspecialchars($date_visite) ?>
                                    <?php if ($is_today): ?>
                                        <span class="today-badge">AUJOURD'HUI</span>
                                    <?php elseif ($is_recent): ?>
                                        <span class="recent-badge">RÉCENTE</span>
                                    <?php endif; ?>
                                </div>
                                <div class="visite-time">
                                    <i class="fas fa-clock"></i>
                                    <?= htmlspecialchars($visite['Heure visite']) ?>
                                </div>
                            </div>
                            <div class="visite-id">
                                Visite #<?= htmlspecialchars($visite['id_visite']) ?>
                            </div>
                        </div>

                        <div class="visite-content">
                            <div class="visite-detail">
                                <i class="fas fa-stethoscope detail-icon"></i>
                                <div class="detail-content">
                                    <div class="detail-label">Motif</div>
                                    <div class="detail-value"><?= htmlspecialchars($visite['Motif visite']) ?></div>
                                </div>
                            </div>
                            
                            <?php if ($visite['prescripteur_nom']): ?>
                            <div class="visite-detail">
                                <i class="fas fa-user-md detail-icon"></i>
                                <div class="detail-content">
                                    <div class="detail-label">Prescripteur</div>
                                    <div class="detail-value"><?= htmlspecialchars($visite['prescripteur_nom'] . ' ' . $visite['prescripteur_prenom']) ?></div>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <?php if ($visite['Structure_provenance']): ?>
                            <div class="visite-detail">
                                <i class="fas fa-hospital detail-icon"></i>
                                <div class="detail-content">
                                    <div class="detail-label">Structure</div>
                                    <div class="detail-value"><?= htmlspecialchars($visite['Structure_provenance']) ?></div>
                                </div>
                            </div>
                            <?php endif; ?>

                            <div class="visite-detail">
                                <i class="fas fa-calendar detail-icon"></i>
                                <div class="detail-content">
                                    <div class="detail-label">Ancienneté</div>
                                    <div class="detail-value">
                                        <?php
                                        $diff = (strtotime('now') - strtotime($visite['date_visite'])) / (24 * 60 * 60);
                                        if ($diff == 0) {
                                            echo "Aujourd'hui";
                                        } elseif ($diff == 1) {
                                            echo "Hier";
                                        } elseif ($diff < 7) {
                                            echo "Il y a " . round($diff) . " jours";
                                        } elseif ($diff < 30) {
                                            echo "Il y a " . round($diff/7) . " semaine(s)";
                                        } else {
                                            echo "Il y a " . round($diff/30) . " mois";
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="actions-bar">
            <a href="liste_dossiers.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour à la liste
            </a>
            <a href="dossier_complet.php?urap=<?= htmlspecialchars($patient['Numero_urap']) ?>" class="btn btn-secondary">
                <i class="fas fa-file-medical"></i> Dossier complet (dernière visite)
            </a>
            <a href="visite.php?idU=<?= htmlspecialchars($patient['Numero_urap']) ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nouvelle visite
            </a>
        </div>
    </div>

    <script>
        // Animation d'entrée pour les cartes
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.visite-card');
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

        // Effet de hover amélioré pour les cartes
        document.querySelectorAll('.visite-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-4px)';
                this.style.boxShadow = '0 10px 15px -3px rgb(0 0 0 / 0.1)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = '0 4px 6px -1px rgb(0 0 0 / 0.1)';
            });
        });

        // Animation de feedback au clic
        document.querySelectorAll('.visite-card').forEach(card => {
            card.addEventListener('click', function(e) {
                // Ne pas appliquer l'effet si on clique sur les boutons d'action
                if (e.target.closest('.visite-actions')) return;
                
                this.style.transform = 'scale(0.98)';
                setTimeout(() => {
                    this.style.transform = 'translateY(-4px)';
                }, 100);
            });
        });
    </script>
</body>
</html>