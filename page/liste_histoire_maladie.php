<?php
// liste_histoire_maladie.php
require_once("identifier.php");
require_once("connexion.php");

// Récupération des données
$histoires = [];
try {
    $sql = "SELECT hm.*, p.Nom_patient, p.Prenom_patient, p.Sexe_patient 
            FROM histoire_maladie hm 
            LEFT JOIN patient p ON hm.numero_urap = p.Numero_urap 
            ORDER BY hm.date_creation DESC";
    $stmt = $pdo->query($sql);
    $histoires = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $error = "Erreur lors de la récupération des données : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Histoires de Maladie - UATG</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #0047ab;
            --primary-light: #1e90ff;
            --accent: #10b981;
            --danger: #ef4444;
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
            --shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
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
            box-shadow: var(--shadow-lg);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: white;
            padding: 32px;
            text-align: center;
        }

        .header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .header p {
            font-size: 1.1rem;
            opacity: 0.9;
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
            color: var(--primary);
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: background 0.2s;
            text-decoration: none;
        }

        .btn-retour:hover {
            background: var(--gray-200);
            text-decoration: none;
            color: var(--primary);
        }

        .content {
            padding: 32px;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 32px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: var(--shadow);
            text-align: center;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary);
        }

        .stat-label {
            color: var(--gray-600);
            font-size: 0.875rem;
            margin-top: 4px;
        }

        .table-container {
            background: white;
            border-radius: 12px;
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        .table-header {
            background: var(--gray-50);
            padding: 20px;
            border-bottom: 1px solid var(--gray-200);
        }

        .table-header h2 {
            color: var(--gray-800);
            font-size: 1.25rem;
            font-weight: 600;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th {
            background: var(--gray-50);
            padding: 12px 16px;
            text-align: left;
            font-weight: 600;
            color: var(--gray-700);
            border-bottom: 1px solid var(--gray-200);
        }

        .table td {
            padding: 12px 16px;
            border-bottom: 1px solid var(--gray-100);
        }

        .table tr:hover {
            background: var(--gray-50);
        }

        .badge {
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .badge-masculin {
            background: rgba(37, 99, 235, 0.1);
            color: #2563eb;
        }

        .badge-feminin {
            background: rgba(236, 72, 153, 0.1);
            color: #ec4899;
        }

        .actions {
            display: flex;
            gap: 8px;
        }

        .btn-action {
            padding: 4px 8px;
            border: none;
            border-radius: 4px;
            font-size: 0.75rem;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .btn-view {
            background: var(--primary);
            color: white;
        }

        .btn-edit {
            background: var(--accent);
            color: white;
        }

        .btn-delete {
            background: var(--danger);
            color: white;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--gray-500);
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 16px;
            opacity: 0.5;
        }

        @media (max-width: 768px) {
            .container {
                margin: 10px;
            }

            .header {
                padding: 24px 20px;
            }

            .header h1 {
                font-size: 2rem;
            }

            .content {
                padding: 20px;
            }

            .table-container {
                overflow-x: auto;
            }

            .table {
                min-width: 600px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <a href="javascript:history.back()" class="btn-retour">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
            <h1><i class="fas fa-file-medical-alt"></i> Liste des Histoires de Maladie</h1>
            <p>Gestion des consultations et motifs de visite médicale</p>
        </div>

        <div class="content">
            <?php if (isset($error)): ?>
                <div style="background: rgba(239, 68, 68, 0.1); color: #ef4444; padding: 16px; border-radius: 8px; margin-bottom: 20px;">
                    <i class="fas fa-exclamation-triangle"></i> <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <!-- Statistiques -->
            <div class="stats">
                <div class="stat-card">
                    <div class="stat-number"><?php echo count($histoires); ?></div>
                    <div class="stat-label">Total des enregistrements</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">
                        <?php echo count(array_filter($histoires, function($h) { return $h['sexe_patient'] === 'masculin'; })); ?>
                    </div>
                    <div class="stat-label">Patients masculins</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">
                        <?php echo count(array_filter($histoires, function($h) { return $h['sexe_patient'] === 'feminin'; })); ?>
                    </div>
                    <div class="stat-label">Patientes féminines</div>
                </div>
            </div>

            <!-- Tableau des données -->
            <div class="table-container">
                <div class="table-header">
                    <h2><i class="fas fa-list"></i> Historique des consultations</h2>
                </div>

                <?php if (empty($histoires)): ?>
                    <div class="empty-state">
                        <i class="fas fa-file-medical"></i>
                        <h3>Aucune histoire de maladie enregistrée</h3>
                        <p>Aucun enregistrement trouvé dans la base de données.</p>
                        <a href="histoire_maladie.php" style="display: inline-block; margin-top: 16px; padding: 12px 24px; background: var(--primary); color: white; text-decoration: none; border-radius: 8px;">
                            <i class="fas fa-plus"></i> Ajouter une histoire de maladie
                        </a>
                    </div>
                <?php else: ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>N° URAP</th>
                                <th>Patient</th>
                                <th>Sexe</th>
                                <th>Motif</th>
                                <th>Signes</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($histoires as $histoire): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($histoire['numero_urap']); ?></strong></td>
                                    <td>
                                        <?php if ($histoire['Nom_patient']): ?>
                                            <?php echo htmlspecialchars($histoire['Nom_patient'] . ' ' . $histoire['Prenom_patient']); ?>
                                        <?php else: ?>
                                            <em>Patient non trouvé</em>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge badge-<?php echo $histoire['sexe_patient']; ?>">
                                            <?php echo ucfirst($histoire['sexe_patient']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php 
                                        $motif = '';
                                        if ($histoire['sexe_patient'] === 'masculin' && $histoire['motif_homme']) {
                                            $motif = $histoire['motif_homme'];
                                        } elseif ($histoire['sexe_patient'] === 'feminin' && $histoire['motif_femme']) {
                                            $motif = $histoire['motif_femme'];
                                        }
                                        echo htmlspecialchars($motif ?: 'Non spécifié');
                                        ?>
                                    </td>
                                    <td>
                                        <?php echo htmlspecialchars($histoire['signes_fonctionnels'] ?: 'Aucun'); ?>
                                    </td>
                                    <td>
                                        <?php echo date('d/m/Y H:i', strtotime($histoire['date_creation'])); ?>
                                    </td>
                                    <td>
                                        <div class="actions">
                                            <a href="histoire_maladie.php?idU=<?php echo urlencode($histoire['numero_urap']); ?>" 
                                               class="btn-action btn-view" title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="histoire_maladie.php?idU=<?php echo urlencode($histoire['numero_urap']); ?>&edit=1" 
                                               class="btn-action btn-edit" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>

            <!-- Bouton d'ajout -->
            <div style="text-align: center; margin-top: 32px;">
                <a href="histoire_maladie.php" style="display: inline-flex; align-items: center; gap: 8px; padding: 14px 28px; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%); color: white; text-decoration: none; border-radius: 12px; font-weight: 600;">
                    <i class="fas fa-plus"></i> Nouvelle Histoire de Maladie
                </a>
            </div>
        </div>
    </div>
</body>
</html> 