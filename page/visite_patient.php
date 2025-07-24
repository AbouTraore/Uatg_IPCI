<?php
require_once("identifier.php");
require_once("connexion.php");

$numero_urap = $_GET['urap'] ?? '';
if (!$numero_urap) {
    echo "<div class='alert alert-danger'>Aucun numéro URAP fourni.</div>";
    exit;
}

// 1. Infos de visite
$stmt = $pdo->prepare("SELECT * FROM visite WHERE Numero_urap = ? ORDER BY `Date visite` DESC LIMIT 1");
$stmt->execute([$numero_urap]);
$visite = $stmt->fetch(PDO::FETCH_ASSOC);

// 2. Infos personnelles
$stmt = $pdo->prepare("SELECT * FROM patient WHERE Numero_urap = ?");
$stmt->execute([$numero_urap]);
$patient = $stmt->fetch(PDO::FETCH_ASSOC);

// 3. Examens de sperme (table ecs)
$stmt = $pdo->prepare("SELECT * FROM ecs WHERE numero_identification = ? ORDER BY numero_identification DESC LIMIT 1");
$stmt->execute([$numero_urap]);
$examen_sperme = $stmt->fetch(PDO::FETCH_ASSOC);

// 4. Examen cytobactériologique vaginal (table exa_cyto_sec_vag)
$stmt = $pdo->prepare("SELECT * FROM exa_cyto_sec_vag WHERE numero_identification = ? ORDER BY numero_identification DESC LIMIT 1");
$stmt->execute([$numero_urap]);
$examen_vaginal = $stmt->fetch(PDO::FETCH_ASSOC);

// 5. Examen sécrétion urétrale (table ecsu)
$stmt = $pdo->prepare("SELECT * FROM ecsu WHERE numero_identification = ? ORDER BY numero_identification DESC LIMIT 1");
$stmt->execute([$numero_urap]);
$examen_uretral = $stmt->fetch(PDO::FETCH_ASSOC);

// 6. Habitudes sexuelles
$stmt = $pdo->prepare("SELECT * FROM habitude_sexuelles WHERE Numero_urap = ?");
$stmt->execute([$numero_urap]);
$habitudes = $stmt->fetch(PDO::FETCH_ASSOC);

// 7. Antécédents IST (selon le sexe du patient)
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

// 8. Histoire de la maladie
$stmt = $pdo->prepare("SELECT * FROM histoire_maladie WHERE Numero_urap = ?");
$stmt->execute([$numero_urap]);
$histoire = $stmt->fetch(PDO::FETCH_ASSOC);

// 9. Échantillons (selon le sexe)
$echantillons_males = null;
$echantillons_femelles = null;

if ($patient && $patient['Sexe_patient'] == 'Masculin') {
    $stmt = $pdo->prepare("SELECT * FROM echantillon_male WHERE numero_urap = ? ORDER BY date_prelevement1 DESC");
    $stmt->execute([$numero_urap]);
    $echantillons_males = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $stmt = $pdo->prepare("SELECT * FROM echantillon_femelle ORDER BY date_prelevement DESC");
    $stmt->execute();
    $echantillons_femelles = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// 10. Prescripteur (dernière entrée)
$stmt = $pdo->prepare("SELECT * FROM prescriteur ORDER BY ID_prescripteur DESC LIMIT 1");
$stmt->execute();
$prescripteur = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dossier complet du patient - <?php echo htmlspecialchars($patient['Nom_patient'] ?? 'Inconnu'); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary: #0047ab;
            --primary-light: #1e90ff;
            --success: #22c55e;
            --warning: #f59e0b;
            --danger: #ef4444;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
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
            box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1);
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
            position: relative;
            z-index: 1;
        }

        .patient-summary {
            background: var(--gray-50);
            margin: 32px;
            padding: 24px;
            border-radius: 16px;
            border-left: 4px solid var(--primary);
        }

        .content-area {
            padding: 32px;
            background: white;
        }

        .sections-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
            gap: 24px;
        }

        .section {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .section:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1);
        }

        .section-header {
            padding: 20px 24px;
            font-size: 1.2rem;
            font-weight: 600;
            color: white;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .section-header.patient { background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%); }
        .section-header.visite { background: linear-gradient(135deg, var(--success) 0%, #16a34a 100%); }
        .section-header.examens { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
        .section-header.habitudes { background: linear-gradient(135deg, #ec4899 0%, #f472b6 100%); }
        .section-header.antecedents { background: linear-gradient(135deg, var(--warning) 0%, #eab308 100%); }
        .section-header.histoire { background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%); }
        .section-header.echantillons { background: linear-gradient(135deg, #8b5cf6 0%, #a855f7 100%); }

        .section-content {
            padding: 24px;
        }

        .data-grid {
            display: grid;
            gap: 12px;
        }

        .data-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid var(--gray-200);
        }

        .data-label {
            font-weight: 500;
            color: var(--gray-600);
            min-width: 180px;
        }

        .data-value {
            font-weight: 600;
            color: var(--gray-800);
            text-align: right;
        }

        .data-value.empty {
            color: var(--gray-400);
            font-style: italic;
        }

        .empty-section {
            text-align: center;
            padding: 40px 24px;
            color: var(--gray-500);
        }

        .empty-section i {
            font-size: 3rem;
            margin-bottom: 16px;
            opacity: 0.5;
        }

        .table-container {
            overflow-x: auto;
            margin-top: 16px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgb(0 0 0 / 0.1);
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid var(--gray-200);
        }

        th {
            background: var(--gray-100);
            font-weight: 600;
            color: var(--gray-700);
        }

        .actions-bar {
            background: var(--gray-50);
            padding: 20px 32px;
            display: flex;
            justify-content: center;
            gap: 16px;
            border-top: 1px solid var(--gray-200);
        }

        .btn {
            padding: 12px 24px;
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

        @media (max-width: 768px) {
            .sections-grid {
                grid-template-columns: 1fr;
            }
            
            .container {
                margin: 10px;
                border-radius: 16px;
            }
            
            .content-area {
                padding: 20px;
            }
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }
            
            .container {
                box-shadow: none;
                background: white;
            }
            
            .actions-bar {
                display: none;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1><i class="fas fa-file-medical"></i> Dossier Médical Complet</h1>
    </div>

    <?php if ($patient): ?>
    <div class="patient-summary">
        <h2><i class="fas fa-user"></i> 
            <?= htmlspecialchars($patient['Nom_patient'] . ' ' . $patient['Prenom_patient']) ?>
        </h2>
        <p><strong>N° URAP:</strong> <?= htmlspecialchars($patient['Numero_urap']) ?> | 
           <strong>Âge:</strong> <?= htmlspecialchars($patient['Age']) ?> ans | 
           <strong>Sexe:</strong> <?= htmlspecialchars($patient['Sexe_patient']) ?>
        </p>
    </div>
    <?php endif; ?>

    <div class="content-area">
        <div class="sections-grid">
            
            <!-- 1. Informations personnelles -->
            <div class="section">
                <div class="section-header patient">
                    <i class="fas fa-id-card"></i>
                    Informations personnelles
                </div>
                <div class="section-content">
                    <?php if ($patient): ?>
                        <div class="data-grid">
                            <div class="data-item">
                                <span class="data-label">Nom complet</span>
                                <span class="data-value"><?= htmlspecialchars($patient['Nom_patient'] . ' ' . $patient['Prenom_patient']) ?></span>
                            </div>
                            <div class="data-item">
                                <span class="data-label">Date de naissance</span>
                                <span class="data-value"><?= htmlspecialchars($patient['Date_naissance']) ?></span>
                            </div>
                            <div class="data-item">
                                <span class="data-label">Contact</span>
                                <span class="data-value"><?= htmlspecialchars($patient['Contact_patient']) ?></span>
                            </div>
                            <div class="data-item">
                                <span class="data-label">Situation matrimoniale</span>
                                <span class="data-value"><?= htmlspecialchars($patient['Situation_matrimoniale']) ?></span>
                            </div>
                            <div class="data-item">
                                <span class="data-label">Lieu de résidence</span>
                                <span class="data-value"><?= htmlspecialchars($patient['Lieu_résidence']) ?></span>
                            </div>
                            <div class="data-item">
                                <span class="data-label">Précision lieu</span>
                                <span class="data-value"><?= htmlspecialchars($patient['Precise'] ?: '-') ?></span>
                            </div>
                            <div class="data-item">
                                <span class="data-label">Type de logement</span>
                                <span class="data-value"><?= htmlspecialchars($patient['Type_logement']) ?></span>
                            </div>
                            <div class="data-item">
                                <span class="data-label">Niveau d'étude</span>
                                <span class="data-value"><?= htmlspecialchars($patient['Niveau_etude']) ?></span>
                            </div>
                            <div class="data-item">
                                <span class="data-label">Profession</span>
                                <span class="data-value"><?= htmlspecialchars($patient['Profession']) ?></span>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="empty-section">
                            <i class="fas fa-user-slash"></i>
                            <p>Aucune information personnelle trouvée.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- 2. Informations de visite -->
            <div class="section">
                <div class="section-header visite">
                    <i class="fas fa-calendar-check"></i>
                    Informations de visite
                </div>
                <div class="section-content">
                    <?php if ($visite): ?>
                        <div class="data-grid">
                            <div class="data-item">
                                <span class="data-label">Date de visite</span>
                                <span class="data-value"><?= htmlspecialchars($visite['Date visite']) ?></span>
                            </div>
                            <div class="data-item">
                                <span class="data-label">Heure de visite</span>
                                <span class="data-value"><?= htmlspecialchars($visite['Heure visite']) ?></span>
                            </div>
                            <div class="data-item">
                                <span class="data-label">Motif de visite</span>
                                <span class="data-value"><?= htmlspecialchars($visite['Motif visite']) ?></span>
                            </div>
                            <div class="data-item">
                                <span class="data-label">Structure de provenance</span>
                                <span class="data-value"><?= htmlspecialchars($visite['Structure_provenance'] ?? 'Non renseignée') ?></span>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="empty-section">
                            <i class="fas fa-calendar-times"></i>
                            <p>Aucune visite enregistrée.</p>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($prescripteur): ?>
                        <h4 style="margin-top: 20px; color: var(--gray-700);">Prescripteur</h4>
                        <div class="data-grid">
                            <div class="data-item">
                                <span class="data-label">Nom du prescripteur</span>
                                <span class="data-value"><?= htmlspecialchars($prescripteur['Nom'] . ' ' . $prescripteur['Prenom']) ?></span>
                            </div>
                            <div class="data-item">
                                <span class="data-label">Contact</span>
                                <span class="data-value"><?= htmlspecialchars($prescripteur['Contact'] ?? 'Non renseigné') ?></span>
                            </div>
                            <div class="data-item">
                                <span class="data-label">Structure de provenance</span>
                                <span class="data-value"><?= htmlspecialchars($prescripteur['Structure_provenance']) ?></span>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- 3. Habitudes sexuelles -->
            <div class="section">
                <div class="section-header habitudes">
                    <i class="fas fa-venus-mars"></i>
                    Habitudes sexuelles
                </div>
                <div class="section-content">
                    <?php if ($habitudes): ?>
                        <div class="data-grid">
                            <div class="data-item">
                                <span class="data-label">Type de rapport</span>
                                <span class="data-value"><?= htmlspecialchars($habitudes['Quel_type_rapport_avez_vous_'] ?? '-') ?></span>
                            </div>
                            <div class="data-item">
                                <span class="data-label">Pratique fellation</span>
                                <span class="data-value"><?= htmlspecialchars($habitudes['Pratiquez_vous__fellation'] ?? '-') ?></span>
                            </div>
                            <div class="data-item">
                                <span class="data-label">Changement partenaire</span>
                                <span class="data-value"><?= htmlspecialchars($habitudes['Avez_vous_changé_partenais_ces_deux_dernier_mois'] ?? '-') ?></span>
                            </div>
                            <div class="data-item">
                                <span class="data-label">Utilisation préservatif</span>
                                <span class="data-value"><?= htmlspecialchars($habitudes['Utilisez_vous_preservatif'] ?? '-') ?></span>
                            </div>
                            <div class="data-item">
                                <span class="data-label">Pratique cunnilingus</span>
                                <span class="data-value"><?= htmlspecialchars($habitudes['Pratqez_le_cunni'] ?? '-') ?></span>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="empty-section">
                            <i class="fas fa-heart"></i>
                            <p>Aucune habitude sexuelle enregistrée.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- 4. Antécédents IST -->
            <div class="section">
                <div class="section-header antecedents">
                    <i class="fas fa-viruses"></i>
                    Antécédents IST
                </div>
                <div class="section-content">
                    <?php if ($ist_femmes): ?>
                        <h4 style="color: var(--gray-700); margin-bottom: 16px;">Antécédents gynécologiques</h4>
                        <div class="data-grid">
                            <div class="data-item">
                                <span class="data-label">Pertes vaginales (2 derniers mois)</span>
                                <span class="data-value"><?= htmlspecialchars($ist_femmes['Avez_vous_eu_des_pertes_vaginales_ces_deux_derniers_mois']) ?></span>
                            </div>
                            <div class="data-item">
                                <span class="data-label">Douleurs bas ventre</span>
                                <span class="data-value"><?= htmlspecialchars($ist_femmes['Avez_vous_eu_des_douleurs_au_bas_ventre_ces_deux_derniers_mois'] ?? '-') ?></span>
                            </div>
                            <div class="data-item">
                                <span class="data-label">Plaies vaginales</span>
                                <span class="data-value"><?= htmlspecialchars($ist_femmes['Avez_vous_eu_des_plaies_vaginales_ces_deux_derniers_mois']) ?></span>
                            </div>
                            <div class="data-item">
                                <span class="data-label">Gestité</span>
                                <span class="data-value"><?= htmlspecialchars($ist_femmes['Antecedant_ist_genicologique_gestité'] ?? '-') ?></span>
                            </div>
                            <div class="data-item">
                                <span class="data-label">Parité</span>
                                <span class="data-value"><?= htmlspecialchars($ist_femmes['Antecedant_ist_genicologique_parité'] ?? '-') ?></span>
                            </div>
                            <div class="data-item">
                                <span class="data-label">Êtes-vous enceinte</span>
                                <span class="data-value"><?= htmlspecialchars($ist_femmes['etes_vous_enceinte']) ?></span>
                            </div>
                            <div class="data-item">
                                <span class="data-label">Date de création</span>
                                <span class="data-value"><?= htmlspecialchars($ist_femmes['date_creation']) ?></span>
                            </div>
                        </div>
                    <?php elseif ($ist_hommes): ?>
                        <h4 style="color: var(--gray-700); margin-bottom: 16px;">Antécédents masculins</h4>
                        <div class="data-grid">
                            <div class="data-item">
                                <span class="data-label">Antécédent</span>
                                <span class="data-value"><?= htmlspecialchars($ist_hommes['antecedent']) ?></span>
                            </div>
                            <div class="data-item">
                                <span class="data-label">Antibiotique actuel</span>
                                <span class="data-value"><?= htmlspecialchars($ist_hommes['antibiotique_actuel'] ?? '-') ?></span>
                            </div>
                            <div class="data-item">
                                <span class="data-label">Préciser antibiotique</span>
                                <span class="data-value"><?= htmlspecialchars($ist_hommes['preciser_antibiotique'] ?? '-') ?></span>
                            </div>
                            <div class="data-item">
                                <span class="data-label">Date de création</span>
                                <span class="data-value"><?= htmlspecialchars($ist_hommes['date_creation']) ?></span>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="empty-section">
                            <i class="fas fa-notes-medical"></i>
                            <p>Aucun antécédent IST enregistré.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- 5. Histoire de la maladie -->
            <div class="section">
                <div class="section-header histoire">
                    <i class="fas fa-file-medical-alt"></i>
                    Histoire de la maladie
                </div>
                <div class="section-content">
                    <?php if ($histoire): ?>
                        <div class="data-grid">
                            <div class="data-item">
                                <span class="data-label">Sexe patient</span>
                                <span class="data-value"><?= htmlspecialchars($histoire['sexe_patient']) ?></span>
                            </div>
                            <div class="data-item">
                                <span class="data-label">Motif (homme)</span>
                                <span class="data-value"><?= htmlspecialchars($histoire['motif_homme'] ?? '-') ?></span>
                            </div>
                            <div class="data-item">
                                <span class="data-label">Motif (femme)</span>
                                <span class="data-value"><?= htmlspecialchars($histoire['motif_femme'] ?? '-') ?></span>
                            </div>
                            <div class="data-item">
                                <span class="data-label">Signes fonctionnels</span>
                                <span class="data-value"><?= htmlspecialchars($histoire['signes_fonctionnels'] ?? '-') ?></span>
                            </div>
                            <div class="data-item">
                                <span class="data-label">Date de création</span>
                                <span class="data-value"><?= htmlspecialchars($histoire['date_creation']) ?></span>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="empty-section">
                            <i class="fas fa-file-medical-alt"></i>
                            <p>Aucune histoire de maladie enregistrée.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- 6. Examens médicaux -->
            <div class="section">
                <div class="section-header examens">
                    <i class="fas fa-vials"></i>
                    Examens médicaux
                </div>
                <div class="section-content">
                    
                    <!-- Examen du sperme (ECS) -->
                    <?php if ($examen_sperme): ?>
                        <h4 style="color: var(--gray-700); margin-bottom: 16px;">Examen cytobactériologique du sperme</h4>
                        <div class="data-grid">
                            <div class="data-item">
                                <span class="data-label">Couleur</span>
                                <span class="data-value"><?= htmlspecialchars($examen_sperme['couleur']) ?></span>
                            </div>
                            <div class="data-item">
                                <span class="data-label">Nombre leucocytes</span>
                                <span class="data-value"><?= htmlspecialchars($examen_sperme['nombre_leucocyte']) ?></span>
                            </div>
                            <div class="data-item">
                                <span class="data-label">Spermatozoïdes</span>
                                <span class="data-value"><?= htmlspecialchars($examen_sperme['spermatozoide']) ?></span>
                            </div>
                            <div class="data-item">
                                <span class="data-label">Mobilité</span>
                                <span class="data-value"><?= htmlspecialchars($examen_sperme['mobilite']) ?></span>
                            </div>
                            <div class="data-item">
                                <span class="data-label">Culture</span>
                                <span class="data-value"><?= htmlspecialchars($examen_sperme['culture']) ?></span>
                            </div>
                            <div class="data-item">
                                <span class="data-label">Médecin prescripteur</span>
                                <span class="data-value"><?= htmlspecialchars($examen_sperme['medecin']) ?></span>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Examen vaginal -->
                    <?php if ($examen_vaginal): ?>
                        <h4 style="color: var(--gray-700); margin: 20px 0 16px;">Examen cytobactériologique vaginal</h4>
                        <div class="data-grid">
                            <div class="data-item">
                                <span class="data-label">Muqueuse vaginale</span>
                                <span class="data-value"><?= htmlspecialchars($examen_vaginal['muqueuse_vaginale']) ?></span>
                            </div>
                            <div class="data-item">
                                <span class="data-label">Écoulement vaginal</span>
                                <span class="data-value"><?= htmlspecialchars($examen_vaginal['ecoulement_vaginal']) ?></span>
                            </div>
                            <div class="data-item">
                                <span class="data-label">Abondance</span>
                                <span class="data-value"><?= htmlspecialchars($examen_vaginal['abondance']) ?></span>
                            </div>
                            <div class="data-item">
                                <span class="data-label">Aspect</span>
                                <span class="data-value"><?= htmlspecialchars($examen_vaginal['aspect']) ?></span>
                            </div>
                            <div class="data-item">
                                <span class="data-label">Odeur</span>
                                <span class="data-value"><?= htmlspecialchars($examen_vaginal['odeur']) ?></span>
                            </div>
                            <div class="data-item">
                                <span class="data-label">Leucocytes</span>
                                <span class="data-value"><?= htmlspecialchars($examen_vaginal['leucocytes']) ?></span>
                            </div>
                            <div class="data-item">
                                <span class="data-label">Médecin</span>
                                <span class="data-value"><?= htmlspecialchars($examen_vaginal['medecin']) ?></span>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Examen urétral (ECSU) -->
                    <?php if ($examen_uretral): ?>
                        <h4 style="color: var(--gray-700); margin: 20px 0 16px;">Examen cytobactériologique sécrétion urétrale</h4>
                        <div class="data-grid">
                            <div class="data-item">
                                <span class="data-label">Culture</span>
                                <span class="data-value"><?= htmlspecialchars($examen_uretral['culture']) ?></span>
                            </div>
                            <div class="data-item">
                                <span class="data-label">Écoulement</span>
                                <span class="data-value"><?= htmlspecialchars($examen_uretral['ecoulement']) ?></span>
                            </div>
                            <div class="data-item">
                                <span class="data-label">Frottis polynucléaires</span>
                                <span class="data-value"><?= htmlspecialchars($examen_uretral['frottis_polynu']) ?></span>
                            </div>
                            <div class="data-item">
                                <span class="data-label">Cocci gram négatif</span>
                                <span class="data-value"><?= htmlspecialchars($examen_uretral['cocci_gram_negatif']) ?></span>
                            </div>
                            <div class="data-item">
                                <span class="data-label">Médecin</span>
                                <span class="data-value"><?= htmlspecialchars($examen_uretral['medecin']) ?></span>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (!$examen_sperme && !$examen_vaginal && !$examen_uretral): ?>
                        <div class="empty-section">
                            <i class="fas fa-vial"></i>
                            <p>Aucun examen médical enregistré.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- 7. Échantillons -->
            <div class="section">
                <div class="section-header echantillons">
                    <i class="fas fa-flask"></i>
                    Échantillons prélevés
                </div>
                <div class="section-content">
                    <?php if ($echantillons_males && count($echantillons_males) > 0): ?>
                        <h4 style="color: var(--gray-700); margin-bottom: 16px;">Échantillons masculins</h4>
                        <div class="table-container">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Type Échantillon 1</th>
                                        <th>Date Prélèvement 1</th>
                                        <th>Technicien 1</th>
                                        <th>Type Échantillon 2</th>
                                        <th>Date Prélèvement 2</th>
                                        <th>Technicien 2</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($echantillons_males as $ech): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($ech['type_echantillon1']) ?></td>
                                            <td><?= htmlspecialchars($ech['date_prelevement1']) ?></td>
                                            <td><?= htmlspecialchars($ech['technicien1']) ?></td>
                                            <td><?= htmlspecialchars($ech['type_echantillon2'] ?: '-') ?></td>
                                            <td><?= htmlspecialchars($ech['date_prelevement2'] !== '0000-00-00' ? $ech['date_prelevement2'] : '-') ?></td>
                                            <td><?= htmlspecialchars($ech['technicien2'] ?: '-') ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php elseif ($echantillons_femelles && count($echantillons_femelles) > 0): ?>
                        <h4 style="color: var(--gray-700); margin-bottom: 16px;">Échantillons féminins</h4>
                        <div class="table-container">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Type Échantillon</th>
                                        <th>Date Prélèvement</th>
                                        <th>Technicien</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($echantillons_femelles as $ech): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($ech['type_echantillon']) ?></td>
                                            <td><?= htmlspecialchars($ech['date_prelevement']) ?></td>
                                            <td><?= htmlspecialchars($ech['technicien']) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="empty-section">
                            <i class="fas fa-flask"></i>
                            <p>Aucun échantillon prélevé.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>

    <div class="actions-bar">
        <a href="javascript:history.back()" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
        <button onclick="window.print()" class="btn btn-primary">
            <i class="fas fa-print"></i> Imprimer le dossier
        </button>
    </div>
</div>
</body>
</html>