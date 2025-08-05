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

// Récupération de toutes les visites du patient
$stmt = $pdo->prepare("SELECT v.*, p.Nom as prescripteur_nom, p.Prenom as prescripteur_prenom 
                       FROM visite v 
                       LEFT JOIN prescriteur p ON v.ID_prescripteur = p.ID_prescripteur 
                       WHERE v.Numero_urap = ? 
                       ORDER BY v.`Date visite` DESC, v.`Heure visite` DESC");
$stmt->execute([$numero_urap]);
$visites = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ===== RÉCUPÉRATION DES DONNÉES MÉDICALES (ADAPTÉE À VOTRE BD) =====

// Habitudes sexuelles
$stmt = $pdo->prepare("SELECT * FROM habitude_sexuelles WHERE Numero_urap = ?");
$stmt->execute([$numero_urap]);
$habitudes = $stmt->fetch(PDO::FETCH_ASSOC);

// Antécédents IST (selon le sexe du patient)
$ist_femmes = null;
$ist_hommes = null;

if ($patient && $patient['Sexe_patient'] == 'Féminin') {
    $stmt = $pdo->prepare("SELECT * FROM antecedents_ist_genicologiques WHERE Numero_urap = ?");
    $stmt->execute([$numero_urap]);
    $ist_femmes = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    $stmt = $pdo->prepare("SELECT * FROM antecedents_ist_hommes WHERE Numero_urap = ?");
    $stmt->execute([$numero_urap]);
    $ist_hommes = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Histoire de la maladie
$stmt = $pdo->prepare("SELECT * FROM histoire_maladie WHERE Numero_urap = ?");
$stmt->execute([$numero_urap]);
$histoire = $stmt->fetch(PDO::FETCH_ASSOC);

// Examens médicaux (SANS date_creation car elle n'existe pas)
$stmt = $pdo->prepare("SELECT * FROM ecs WHERE numero_identification = ?");
$stmt->execute([$numero_urap]);
$examens_sperme = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT * FROM exa_cyto_sec_vag WHERE numero_identification = ?");
$stmt->execute([$numero_urap]);
$examens_vaginal = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT * FROM ecsu WHERE numero_identification = ?");
$stmt->execute([$numero_urap]);
$examens_uretral = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Échantillons (ADAPTATION SELON VOTRE STRUCTURE)
$echantillons_males = null;
$echantillons_femelles = null;

if ($patient && $patient['Sexe_patient'] == 'Masculin') {
    $stmt = $pdo->prepare("SELECT * FROM echantillon_male WHERE numero_urap = ? ORDER BY date_prelevement1 DESC");
    $stmt->execute([$numero_urap]);
    $echantillons_males = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // PROBLÈME : echantillon_femelle n'a pas de numero_urap
    // Pour l'instant, on récupère tous les échantillons féminins
    $stmt = $pdo->prepare("SELECT * FROM echantillon_femelle ORDER BY date_prelevement DESC");
    $stmt->execute();
    $echantillons_femelles = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Statistiques
$total_visites = count($visites);
$total_examens = count($examens_sperme) + count($examens_vaginal) + count($examens_uretral);
$total_echantillons = count($echantillons_males ?: []) + count($echantillons_femelles ?: []);
$visites_this_year = 0;
$current_year = date('Y');
foreach ($visites as $visite) {
    if (date('Y', strtotime($visite['Date visite'])) == $current_year) {
        $visites_this_year++;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dossier de <?= htmlspecialchars($patient['Nom_patient'] . ' ' . $patient['Prenom_patient']) ?> - UATG</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* [MÊME CSS QUE PRÉCÉDEMMENT] */
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
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }

        .alert {
            padding: 16px 20px;
            margin: 20px;
            border-radius: 8px;
            font-weight: 500;
        }

        .alert-warning {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #fbbf24;
        }

        .alert-info {
            background: #dbeafe;
            color: #1e40af;
            border: 1px solid #60a5fa;
        }

        .header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 32px;
            margin-bottom: 24px;
            box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1);
            text-align: center;
        }

        .header h1 {
            color: var(--primary);
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 8px;
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

        .section {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            overflow: hidden;
            transition: all 0.3s ease;
            margin-bottom: 24px;
        }

        .section:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 12px -2px rgb(0 0 0 / 0.1);
        }

        .section-header {
            padding: 20px 24px;
            font-size: 1.2rem;
            font-weight: 600;
            color: white;
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
        }

        .section-header.habitudes { background: linear-gradient(135deg, var(--pink) 0%, #f472b6 100%); }
        .section-header.antecedents { background: linear-gradient(135deg, var(--warning) 0%, #eab308 100%); }
        .section-header.histoire { background: linear-gradient(135deg, var(--info) 0%, #0ea5e9 100%); }
        .section-header.examens { background: linear-gradient(135deg, var(--success) 0%, #16a34a 100%); }
        .section-header.echantillons { background: linear-gradient(135deg, var(--purple) 0%, #a855f7 100%); }
        .section-header.visites { background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%); }

        .section-content {
            padding: 24px;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }

        .section-content.expanded {
            max-height: 2000px;
        }

        .expand-icon {
            margin-left: auto;
            transition: transform 0.3s ease;
        }

        .expand-icon.rotated {
            transform: rotate(180deg);
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 16px;
        }

        .info-item {
            background: var(--gray-50);
            padding: 16px;
            border-radius: 8px;
            border-left: 3px solid var(--primary);
        }

        .info-item h5 {
            color: var(--primary);
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .info-item p {
            color: var(--gray-700);
            font-size: 0.85rem;
            line-height: 1.4;
        }

        .empty-section {
            text-align: center;
            padding: 40px 20px;
            color: var(--gray-500);
        }

        .empty-section i {
            font-size: 2.5rem;
            margin-bottom: 12px;
            opacity: 0.5;
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

        .actions-bar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 20px;
            display: flex;
            justify-content: center;
            gap: 16px;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        }

        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }
            
            .patient-header {
                grid-template-columns: 1fr;
                text-align: center;
            }
            
            .info-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-user-md"></i> Dossier Patient Complet</h1>
            <p>Consultation complète du dossier médical</p>
        </div>

        <!-- ALERTE SI PAS DE VISITES -->
        <?php if (empty($visites)): ?>
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i>
            <strong>Attention :</strong> Aucune visite n'est enregistrée pour ce patient dans la base de données. 
            Les informations médicales sont affichées de manière globale.
        </div>
        <?php endif; ?>

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
                            <strong>Contact:</strong> <?= htmlspecialchars($patient['Contact_patient']) ?>
                        </div>
                    </div>
                </div>
                
                <div class="patient-stats">
                    <div class="stat-card">
                        <div class="stat-number"><?= $total_visites ?></div>
                        <div class="stat-label">Visites</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number"><?= $total_examens ?></div>
                        <div class="stat-label">Examens</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number"><?= $total_echantillons ?></div>
                        <div class="stat-label">Échantillons</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number"><?= $visites_this_year ?></div>
                        <div class="stat-label">Cette année</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section Habitudes sexuelles -->
        <div class="section">
            <div class="section-header habitudes" onclick="toggleSection('habitudes')">
                <i class="fas fa-venus-mars"></i>
                Habitudes sexuelles
                <div class="expand-icon" id="icon-habitudes">
                    <i class="fas fa-chevron-down"></i>
                </div>
            </div>
            <div class="section-content" id="content-habitudes">
                <?php if ($habitudes): ?>
                    <div class="info-grid">
                        <div class="info-item">
                            <h5>Type de rapport</h5>
                            <p><?= htmlspecialchars($habitudes['Quel_type_rapport_avez_vous_'] ?? '-') ?></p>
                        </div>
                        <div class="info-item">
                            <h5>Pratique fellation</h5>
                            <p><?= htmlspecialchars($habitudes['Pratiquez_vous__fellation'] ?? '-') ?></p>
                        </div>
                        <div class="info-item">
                            <h5>Pratique cunnilingus</h5>
                            <p><?= htmlspecialchars($habitudes['Pratqez_le_cunni'] ?? '-') ?></p>
                        </div>
                        <div class="info-item">
                            <h5>Changement partenaire (2 mois)</h5>
                            <p><?= htmlspecialchars($habitudes['Avez_vous_changé_partenais_ces_deux_dernier_mois'] ?? '-') ?></p>
                        </div>
                        <div class="info-item">
                            <h5>Utilisation préservatif</h5>
                            <p><?= htmlspecialchars($habitudes['Utilisez_vous_preservatif'] ?? '-') ?></p>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="empty-section">
                        <i class="fas fa-heart"></i>
                        <p>Aucune habitude sexuelle enregistrée</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Section Antécédents IST -->
        <div class="section">
            <div class="section-header antecedents" onclick="toggleSection('antecedents')">
                <i class="fas fa-viruses"></i>
                Antécédents IST
                <div class="expand-icon" id="icon-antecedents">
                    <i class="fas fa-chevron-down"></i>
                </div>
            </div>
            <div class="section-content" id="content-antecedents">
                <?php if ($ist_femmes): ?>
                    <div class="info-grid">
                        <div class="info-item">
                            <h5>Pertes vaginales (2 mois)</h5>
                            <p><?= htmlspecialchars($ist_femmes['Avez_vous_eu_des_pertes_vaginales_ces_deux_derniers_mois']) ?></p>
                        </div>
                        <div class="info-item">
                            <h5>Douleurs bas ventre</h5>
                            <p><?= htmlspecialchars($ist_femmes['Avez_vous_eu_des_douleurs_au_bas_ventre_ces_deux_derniers_mois'] ?? '-') ?></p>
                        </div>
                        <div class="info-item">
                            <h5>Plaies vaginales</h5>
                            <p><?= htmlspecialchars($ist_femmes['Avez_vous_eu_des_plaies_vaginales_ces_deux_derniers_mois']) ?></p>
                        </div>
                        <div class="info-item">
                            <h5>Gestité</h5>
                            <p><?= htmlspecialchars($ist_femmes['Antecedant_ist_genicologique_gestité'] ?? '-') ?></p>
                        </div>
                        <div class="info-item">
                            <h5>Parité</h5>
                            <p><?= htmlspecialchars($ist_femmes['Antecedant_ist_genicologique_parité'] ?? '-') ?></p>
                        </div>
                        <div class="info-item">
                            <h5>Êtes-vous enceinte</h5>
                            <p><?= htmlspecialchars($ist_femmes['etes_vous_enceinte']) ?></p>
                        </div>
                        <div class="info-item">
                            <h5>Date dernières règles</h5>
                            <p><?= htmlspecialchars($ist_femmes['Date_des_derniers_regles']) ?></p>
                        </div>
                    </div>
                <?php elseif ($ist_hommes): ?>
                    <div class="info-grid">
                        <div class="info-item">
                            <h5>Antécédent</h5>
                            <p><?= htmlspecialchars($ist_hommes['antecedent']) ?></p>
                        </div>
                        <div class="info-item">
                            <h5>Antibiotique actuel</h5>
                            <p><?= htmlspecialchars($ist_hommes['antibiotique_actuel'] ?? '-') ?></p>
                        </div>
                        <div class="info-item">
                            <h5>Préciser antibiotique</h5>
                            <p><?= htmlspecialchars($ist_hommes['preciser_antibiotique'] ?? '-') ?></p>
                        </div>
                        <div class="info-item">
                            <h5>Date de création</h5>
                            <p><?= htmlspecialchars(date('d/m/Y', strtotime($ist_hommes['date_creation']))) ?></p>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="empty-section">
                        <i class="fas fa-notes-medical"></i>
                        <p>Aucun antécédent IST enregistré</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Section Histoire de la maladie -->
        <div class="section">
            <div class="section-header histoire" onclick="toggleSection('histoire')">
                <i class="fas fa-file-medical-alt"></i>
                Histoire de la maladie
                <div class="expand-icon" id="icon-histoire">
                    <i class="fas fa-chevron-down"></i>
                </div>
            </div>
            <div class="section-content" id="content-histoire">
                <?php if ($histoire): ?>
                    <div class="info-grid">
                        <div class="info-item">
                            <h5>Sexe patient</h5>
                            <p><?= htmlspecialchars($histoire['sexe_patient']) ?></p>
                        </div>
                        <?php if ($histoire['motif_homme']): ?>
                        <div class="info-item">
                            <h5>Motif (homme)</h5>
                            <p><?= htmlspecialchars($histoire['motif_homme']) ?></p>
                        </div>
                        <?php endif; ?>
                        <?php if ($histoire['motif_femme']): ?>
                        <div class="info-item">
                            <h5>Motif (femme)</h5>
                            <p><?= htmlspecialchars($histoire['motif_femme']) ?></p>
                        </div>
                        <?php endif; ?>
                        <div class="info-item">
                            <h5>Signes fonctionnels</h5>
                            <p><?= htmlspecialchars($histoire['signes_fonctionnels'] ?? '-') ?></p>
                        </div>
                        <div class="info-item">
                            <h5>Date de création</h5>
                            <p><?= htmlspecialchars(date('d/m/Y H:i', strtotime($histoire['date_creation']))) ?></p>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="empty-section">
                        <i class="fas fa-file-medical-alt"></i>
                        <p>Aucune histoire de maladie enregistrée</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Section Examens médicaux -->
        <div class="section">
            <div class="section-header examens" onclick="toggleSection('examens')">
                <i class="fas fa-vials"></i>
                Examens médicaux (<?= $total_examens ?>)
                <div class="expand-icon" id="icon-examens">
                    <i class="fas fa-chevron-down"></i>
                </div>
            </div>
            <div class="section-content" id="content-examens">
                <?php if ($total_examens > 0): ?>
                    <div class="info-grid">
                        <?php foreach ($examens_sperme as $index => $examen): ?>
                        <div class="info-item">
                            <h5>ECS #<?= $index + 1 ?> - <?= htmlspecialchars($examen['medecin']) ?></h5>
                            <p><strong>Couleur:</strong> <?= htmlspecialchars($examen['couleur']) ?><br>
                               <strong>Leucocytes:</strong> <?= htmlspecialchars($examen['nombre_leucocyte']) ?><br>
                               <strong>Spermatozoïdes:</strong> <?= htmlspecialchars($examen['spermatozoide']) ?><br>
                               <strong>Culture:</strong> <?= htmlspecialchars($examen['culture']) ?></p>
                        </div>
                        <?php endforeach; ?>
                        
                        <?php foreach ($examens_vaginal as $index => $examen): ?>
                        <div class="info-item">
                            <h5>Examen vaginal #<?= $index + 1 ?> - <?= htmlspecialchars($examen['medecin']) ?></h5>
                            <p><strong>Muqueuse:</strong> <?= htmlspecialchars($examen['muqueuse_vaginale']) ?><br>
                               <strong>Écoulement:</strong> <?= htmlspecialchars($examen['ecoulement_vaginal']) ?><br>
                               <strong>Leucocytes:</strong> <?= htmlspecialchars($examen['leucocytes']) ?><br>
                               <strong>Flore:</strong> <?= htmlspecialchars($examen['flore_vaginale']) ?></p>
                        </div>
                        <?php endforeach; ?>
                        
                        <?php foreach ($examens_uretral as $index => $examen): ?>
                        <div class="info-item">
                            <h5>ECSU #<?= $index + 1 ?> - <?= htmlspecialchars($examen['medecin']) ?></h5>
                            <p><strong>Écoulement:</strong> <?= htmlspecialchars($examen['ecoulement']) ?><br>
                               <strong>Frottis:</strong> <?= htmlspecialchars($examen['frottis_polynu']) ?><br>
                               <strong>Culture:</strong> <?= htmlspecialchars($examen['culture']) ?></p>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-section">
                        <i class="fas fa-vial"></i>
                        <p>Aucun examen médical enregistré</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Section Échantillons -->
        <div class="section">
            <div class="section-header echantillons" onclick="toggleSection('echantillons')">
                <i class="fas fa-flask"></i>
                Échantillons prélevés (<?= $total_echantillons ?>)
                <div class="expand-icon" id="icon-echantillons">
                    <i class="fas fa-chevron-down"></i>
                </div>
            </div>
            <div class="section-content" id="content-echantillons">
                <?php if ($total_echantillons > 0): ?>
                    <div class="info-grid">
                        <?php if ($echantillons_males): ?>
                            <?php foreach ($echantillons_males as $index => $ech): ?>
                            <div class="info-item">
                                <h5>Échantillon masculin #<?= $index + 1 ?></h5>
                                <p><strong>Échantillon 1:</strong> <?= htmlspecialchars($ech['type_echantillon1']) ?><br>
                                   <strong>Date 1:</strong> <?= htmlspecialchars($ech['date_prelevement1']) ?><br>
                                   <strong>Technicien 1:</strong> <?= htmlspecialchars($ech['technicien1']) ?>
                                   <?php if ($ech['type_echantillon2']): ?>
                                   <br><strong>Échantillon 2:</strong> <?= htmlspecialchars($ech['type_echantillon2']) ?><br>
                                   <strong>Date 2:</strong> <?= htmlspecialchars($ech['date_prelevement2'] !== '0000-00-00' ? $ech['date_prelevement2'] : '-') ?><br>
                                   <strong>Technicien 2:</strong> <?= htmlspecialchars($ech['technicien2']) ?>
                                   <?php endif; ?>
                                </p>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        
                        <?php if ($echantillons_femelles): ?>
                            <?php foreach ($echantillons_femelles as $index => $ech): ?>
                            <div class="info-item">
                                <h5>Échantillon féminin #<?= $index + 1 ?></h5>
                                <p><strong>Type:</strong> <?= htmlspecialchars($ech['type_echantillon']) ?><br>
                                   <strong>Date:</strong> <?= htmlspecialchars($ech['date_prelevement']) ?><br>
                                   <strong>Technicien:</strong> <?= htmlspecialchars($ech['technicien']) ?></p>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    
                    <?php if ($patient['Sexe_patient'] == 'Féminin'): ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Note :</strong> Les échantillons féminins ne sont pas liés directement au patient car la table `echantillon_femelle` n'a pas de colonne `numero_urap`.
                    </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="empty-section">
                        <i class="fas fa-flask"></i>
                        <p>Aucun échantillon prélevé</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Section Visites (affichée même si vide) -->
        <div class="section">
            <div class="section-header visites" onclick="toggleSection('visites')">
                <i class="fas fa-calendar-alt"></i>
                Historique des visites (<?= $total_visites ?>)
                <div class="expand-icon" id="icon-visites">
                    <i class="fas fa-chevron-down"></i>
                </div>
            </div>
            <div class="section-content expanded" id="content-visites">
                <?php if (!empty($visites)): ?>
                    <div class="info-grid">
                        <?php foreach ($visites as $index => $visite): ?>
                        <div class="info-item">
                            <h5>Visite #<?= $index + 1 ?> - <?= htmlspecialchars(date('d/m/Y', strtotime($visite['Date visite']))) ?></h5>
                            <p><strong>Heure:</strong> <?= htmlspecialchars($visite['Heure visite']) ?><br>
                               <strong>Motif:</strong> <?= htmlspecialchars($visite['Motif visite']) ?><br>
                               <?php if ($visite['Structure_provenance']): ?>
                               <strong>Structure:</strong> <?= htmlspecialchars($visite['Structure_provenance']) ?><br>
                               <?php endif; ?>
                               <?php if ($visite['prescripteur_nom']): ?>
                               <strong>Prescripteur:</strong> <?= htmlspecialchars($visite['prescripteur_nom'] . ' ' . $visite['prescripteur_prenom']) ?>
                               <?php endif; ?>
                            </p>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-section">
                        <i class="fas fa-calendar-times"></i>
                        <h3>Aucune visite enregistrée</h3>
                        <p>Ce patient n'a pas encore de visite dans le système.<br>
                        Vous devez d'abord enregistrer des visites pour ce patient.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="actions-bar">
            <a href="liste_dossiers.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour à la liste
            </a>
        </div>
    </div>

    <script>
        function toggleSection(sectionId) {
            const content = document.getElementById('content-' + sectionId);
            const icon = document.getElementById('icon-' + sectionId);
            
            if (content.classList.contains('expanded')) {
                content.classList.remove('expanded');
                icon.classList.remove('rotated');
            } else {
                content.classList.add('expanded');
                icon.classList.add('rotated');
            }
        }

        // Ouvrir certaines sections par défaut
        document.addEventListener('DOMContentLoaded', function() {
            // Ouvrir les sections qui ont des données
            <?php if ($habitudes): ?>
            toggleSection('habitudes');
            <?php endif; ?>
            
            <?php if ($ist_femmes || $ist_hommes): ?>
            toggleSection('antecedents');
            <?php endif; ?>
            
            <?php if ($histoire): ?>
            toggleSection('histoire');
            <?php endif; ?>
            
            <?php if ($total_examens > 0): ?>
            toggleSection('examens');
            <?php endif; ?>
            
            // Toujours ouvrir la section visites
            const visitesIcon = document.getElementById('icon-visites');
            if (visitesIcon && !document.getElementById('content-visites').classList.contains('expanded')) {
                visitesIcon.classList.add('rotated');
            }
        });
    </script>
</body>
</html>