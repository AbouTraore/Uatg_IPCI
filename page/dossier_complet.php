<?php
require_once("identifier.php");
require_once("connexion.php");

$numero_urap = $_GET['urap'] ?? '';
$visite_id = $_GET['visite_id'] ?? '';

if (!$numero_urap) {
    echo "<div class='alert alert-danger'>Aucun numéro URAP fourni.</div>";
    exit;
}

// 1. Informations du patient
$stmt = $pdo->prepare("SELECT * FROM patient WHERE Numero_urap = ?");
$stmt->execute([$numero_urap]);
$patient = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$patient) {
    echo "<div class='alert alert-danger'>Patient non trouvé.</div>";
    exit;
}

// 2. Visite spécifique ou dernière visite
$visite = null;
if ($visite_id) {
    $stmt = $pdo->prepare("
        SELECT v.*, p.Nom as prescripteur_nom, p.Prenom as prescripteur_prenom 
        FROM visite v 
        LEFT JOIN prescriteur p ON v.ID_prescripteur = p.ID_prescripteur 
        WHERE v.id_visite = ? AND v.Numero_urap = ?
    ");
    $stmt->execute([$visite_id, $numero_urap]);
    $visite = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    $stmt = $pdo->prepare("
        SELECT v.*, p.Nom as prescripteur_nom, p.Prenom as prescripteur_prenom 
        FROM visite v 
        LEFT JOIN prescriteur p ON v.ID_prescripteur = p.ID_prescripteur 
        WHERE v.Numero_urap = ? 
        ORDER BY v.date_visite DESC 
        LIMIT 1
    ");
    $stmt->execute([$numero_urap]);
    $visite = $stmt->fetch(PDO::FETCH_ASSOC);
}

// 3. Habitudes sexuelles
$stmt = $pdo->prepare("SELECT * FROM habitude_sexuelles WHERE Numero_urap = ? ORDER BY ID_habitude_sexuelles DESC LIMIT 1");
$stmt->execute([$numero_urap]);
$habitudes = $stmt->fetch(PDO::FETCH_ASSOC);

// 4. Antécédents IST selon le sexe
$ist_data = null;
$ist_type = '';
$ist_id = 0;
if ($patient['Sexe_patient'] == 'Féminin') {
    $stmt = $pdo->prepare("SELECT * FROM antecedents_ist_genicologiques WHERE Numero_urap = ? ORDER BY ID_antecedents DESC LIMIT 1");
    $stmt->execute([$numero_urap]);
    $ist_data = $stmt->fetch(PDO::FETCH_ASSOC);
    $ist_type = 'femmes';
    $ist_id = $ist_data ? $ist_data['ID_antecedents'] : 0;
} else {
    $stmt = $pdo->prepare("SELECT * FROM antecedents_ist_hommes WHERE Numero_urap = ? ORDER BY ID_antecedent DESC LIMIT 1");
    $stmt->execute([$numero_urap]);
    $ist_data = $stmt->fetch(PDO::FETCH_ASSOC);
    $ist_type = 'hommes';
    $ist_id = $ist_data ? $ist_data['ID_antecedent'] : 0;
}

// 5. Histoire de la maladie
$stmt = $pdo->prepare("SELECT * FROM histoire_maladie WHERE Numero_urap = ? ORDER BY ID_histoire_maladie DESC LIMIT 1");
$stmt->execute([$numero_urap]);
$histoire = $stmt->fetch(PDO::FETCH_ASSOC);

// 6. Examens médicaux
$stmt = $pdo->prepare("SELECT * FROM ecs WHERE numero_identification = ?");
$stmt->execute([$numero_urap]);
$examen_ecs = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT * FROM exa_cyto_sec_vag WHERE numero_identification = ?");
$stmt->execute([$numero_urap]);
$examen_vaginal = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT * FROM ecsu WHERE numero_identification = ?");
$stmt->execute([$numero_urap]);
$examen_uretral = $stmt->fetch(PDO::FETCH_ASSOC);

// 7. Échantillons selon le sexe
$echantillons = null;
$echantillon_type = '';
$echantillon_id = 0;
if ($patient['Sexe_patient'] == 'Masculin') {
    $stmt = $pdo->prepare("SELECT * FROM echantillon_male WHERE Numero_urap = ? ORDER BY ID_echantillon_male DESC LIMIT 1");
    $stmt->execute([$numero_urap]);
    $echantillons = $stmt->fetch(PDO::FETCH_ASSOC);
    $echantillon_type = 'male';
    $echantillon_id = $echantillons ? $echantillons['ID_echantillon_male'] : 0;
} else {
    $stmt = $pdo->prepare("SELECT * FROM echantillon_femelle WHERE Numero_urap = ? ORDER BY ID_echantillon_femelle DESC LIMIT 1");
    $stmt->execute([$numero_urap]);
    $echantillons = $stmt->fetch(PDO::FETCH_ASSOC);
    $echantillon_type = 'femelle';
    $echantillon_id = $echantillons ? $echantillons['ID_echantillon_femelle'] : 0;
}

// Statistiques
$stmt = $pdo->prepare("SELECT COUNT(*) as total FROM visite WHERE Numero_urap = ?");
$stmt->execute([$numero_urap]);
$total_visites = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dossier Complet - <?= htmlspecialchars($patient['Nom_patient'] . ' ' . $patient['Prenom_patient']) ?> - UATG</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* [Le même CSS que précédemment] */
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

        /* [Reprendre tout le CSS de la version précédente] */
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
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2.5rem;
        }

        .patient-info h2 {
            color: var(--primary);
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .patient-meta {
            color: var(--gray-600);
            font-size: 1rem;
            line-height: 1.6;
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

        .section-header {
            padding: 24px;
            font-size: 1.3rem;
            font-weight: 600;
            color: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
            cursor: pointer;
        }

        .section-header.patient { background: linear-gradient(135deg, var(--info) 0%, #0ea5e9 100%); }
        .section-header.visite { background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%); }
        .section-header.habitudes { background: linear-gradient(135deg, var(--pink) 0%, #f472b6 100%); }
        .section-header.antecedents { background: linear-gradient(135deg, var(--warning) 0%, #eab308 100%); }
        .section-header.histoire { background: linear-gradient(135deg, var(--purple) 0%, #a855f7 100%); }
        .section-header.examens { background: linear-gradient(135deg, var(--success) 0%, #16a34a 100%); }
        .section-header.echantillons { background: linear-gradient(135deg, var(--danger) 0%, #dc2626 100%); }

        .section-title {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .btn-modify {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .btn-modify:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: scale(1.05);
        }

        .section-content {
            padding: 24px;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }

        .section-content.expanded {
            max-height: 2000px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
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

        .expand-icon {
            transition: transform 0.3s ease;
        }

        .expand-icon.rotated {
            transform: rotate(180deg);
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

            .section-header {
                flex-direction: column;
                gap: 16px;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-file-medical"></i> Dossier Médical Complet</h1>
            <p>Consultation détaillée du dossier patient</p>
        </div>

        <!-- [Patient Overview identique] -->
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
                            <strong>Contact:</strong> <?= htmlspecialchars($patient['Contact_patient']) ?><br>
                            <strong>Situation:</strong> <?= htmlspecialchars($patient['Situation_matrimoniale']) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="sections-container">
            <!-- Section Informations Patient -->
            <div class="section">
                <div class="section-header patient" onclick="toggleSection('patient')">
                    <div class="section-title">
                        <i class="fas fa-user-circle"></i>
                        Informations Personnelles
                    </div>
                    <a href="modifier_patient.php?urap=<?= htmlspecialchars($patient['Numero_urap']) ?>" class="btn-modify">
                        <i class="fas fa-edit"></i> Modifier
                    </a>
                    <div class="expand-icon" id="icon-patient">
                        <i class="fas fa-chevron-down"></i>
                    </div>
                </div>
                <div class="section-content expanded" id="content-patient">
                    <div class="info-grid">
                        <div class="info-item">
                            <h5>Identité complète</h5>
                            <p><strong>Nom :</strong> <?= htmlspecialchars($patient['Nom_patient']) ?><br>
                               <strong>Prénom :</strong> <?= htmlspecialchars($patient['Prenom_patient']) ?><br>
                               <strong>N°URAP :</strong> <?= htmlspecialchars($patient['Numero_urap']) ?></p>
                        </div>
                        <div class="info-item">
                            <h5>Informations civiles</h5>
                            <p><strong>Âge :</strong> <?= htmlspecialchars($patient['Age']) ?> ans<br>
                               <strong>Sexe :</strong> <?= htmlspecialchars($patient['Sexe_patient']) ?><br>
                               <strong>Situation :</strong> <?= htmlspecialchars($patient['Situation_matrimoniale']) ?></p>
                        </div>
                        <div class="info-item">
                            <h5>Contact et résidence</h5>
                            <p><strong>Téléphone :</strong> <?= htmlspecialchars($patient['Contact_patient']) ?><br>
                               <strong>Lieu :</strong> <?= htmlspecialchars($patient['Lieu_résidence']) ?><br>
                               <strong>Logement :</strong> <?= htmlspecialchars($patient['Type_logement']) ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section Habitudes sexuelles -->
            <div class="section">
                <div class="section-header habitudes" onclick="toggleSection('habitudes')">
                    <div class="section-title">
                        <i class="fas fa-venus-mars"></i>
                        Habitudes Sexuelles
                    </div>
                    <a href="modifier_habitudes.php?urap=<?= htmlspecialchars($patient['Numero_urap']) ?><?= $habitudes ? '&id=' . $habitudes['ID_habitude_sexuelles'] : '' ?>" class="btn-modify">
                        <i class="fas fa-edit"></i> <?= $habitudes ? 'Modifier' : 'Ajouter' ?>
                    </a>
                    <div class="expand-icon" id="icon-habitudes">
                        <i class="fas fa-chevron-down"></i>
                    </div>
                </div>
                <div class="section-content" id="content-habitudes">
                    <?php if ($habitudes): ?>
                        <div class="info-grid">
                            <div class="info-item">
                                <h5>Type de rapports</h5>
                                <p><?= htmlspecialchars($habitudes['Quel_type_rapport_avez_vous'] ?? '-') ?></p>
                            </div>
                            <div class="info-item">
                                <h5>Pratique fellation</h5>
                                <p><?= htmlspecialchars($habitudes['Pratiquez_vous__fellation'] ?? '-') ?></p>
                            </div>
                            <div class="info-item">
                                <h5>Pratique cunnilingus</h5>
                                <p><?= htmlspecialchars($habitudes['Pratiquez_vous_cunilingus'] ?? '-') ?></p>
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
                    <div class="section-title">
                        <i class="fas fa-viruses"></i>
                        Antécédents IST (<?= ucfirst($ist_type) ?>)
                    </div>
                    <a href="modifier_antecedents_<?= $ist_type ?>.php?urap=<?= htmlspecialchars($patient['Numero_urap']) ?><?= $ist_data ? '&id=' . $ist_id : '' ?>" class="btn-modify">
                        <i class="fas fa-edit"></i> <?= $ist_data ? 'Modifier' : 'Ajouter' ?>
                    </a>
                    <div class="expand-icon" id="icon-antecedents">
                        <i class="fas fa-chevron-down"></i>
                    </div>
                </div>
                <div class="section-content" id="content-antecedents">
                    <?php if ($ist_data): ?>
                        <div class="info-grid">
                            <?php if ($ist_type == 'femmes'): ?>
                                <div class="info-item">
                                    <h5>Pertes vaginales (2 mois)</h5>
                                    <p><?= htmlspecialchars($ist_data['Avez_vous_eu_des_pertes_vaginales_ces_deux_derniers_mois']) ?></p>
                                </div>
                                <div class="info-item">
                                    <h5>Douleurs bas ventre</h5>
                                    <p><?= htmlspecialchars($ist_data['Avez_vous_eu_des_douleurs_au_bas_ventre_ces_deux_derniers_mois']) ?></p>
                                </div>
                                <div class="info-item">
                                    <h5>Plaies vaginales</h5>
                                    <p><?= htmlspecialchars($ist_data['Avez_vous_eu_des_plaies_vaginales_ces_deux_derniers_mois']) ?></p>
                                </div>
                            <?php else: ?>
                                <div class="info-item">
                                    <h5>Antécédent</h5>
                                    <p><?= htmlspecialchars($ist_data['antecedent']) ?></p>
                                </div>
                                <div class="info-item">
                                    <h5>Antibiotique actuel</h5>
                                    <p><?= htmlspecialchars($ist_data['antibiotique_actuel']) ?></p>
                                </div>
                            <?php endif; ?>
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
                    <div class="section-title">
                        <i class="fas fa-file-medical-alt"></i>
                        Histoire de la Maladie
                    </div>
                    <a href="modifier_histoire.php?urap=<?= htmlspecialchars($patient['Numero_urap']) ?><?= $histoire ? '&id=' . $histoire['ID_histoire_maladie'] : '' ?>" class="btn-modify">
                        <i class="fas fa-edit"></i> <?= $histoire ? 'Modifier' : 'Ajouter' ?>
                    </a>
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
                                <p><?= htmlspecialchars($histoire['signes_fonctionnels']) ?></p>
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

            <!-- Section Échantillons -->
            <div class="section">
                <div class="section-header echantillons" onclick="toggleSection('echantillons')">
                    <div class="section-title">
                        <i class="fas fa-flask"></i>
                        Échantillons Prélevés
                    </div>
                    <a href="modifier_echantillons.php?urap=<?= htmlspecialchars($patient['Numero_urap']) ?>&type=<?= $echantillon_type ?><?= $echantillons ? '&id=' . $echantillon_id : '' ?>" class="btn-modify">
                        <i class="fas fa-edit"></i> <?= $echantillons ? 'Modifier' : 'Ajouter' ?>
                    </a>
                    <div class="expand-icon" id="icon-echantillons">
                        <i class="fas fa-chevron-down"></i>
                    </div>
                </div>
                <div class="section-content" id="content-echantillons">
                    <?php if ($echantillons): ?>
                        <div class="info-grid">
                            <?php if ($echantillon_type == 'male'): ?>
                                <div class="info-item">
                                    <h5>Échantillon masculin</h5>
                                    <p><strong>Type 1 :</strong> <?= htmlspecialchars($echantillons['type_echantillon1']) ?><br>
                                       <strong>Date 1 :</strong> <?= htmlspecialchars(date('d/m/Y', strtotime($echantillons['date_prelevement1']))) ?><br>
                                       <strong>Technicien 1 :</strong> <?= htmlspecialchars($echantillons['technicien1']) ?>
                                       <?php if ($echantillons['type_echantillon2']): ?>
                                       <br><br><strong>Type 2 :</strong> <?= htmlspecialchars($echantillons['type_echantillon2']) ?><br>
                                       <strong>Date 2 :</strong> <?= htmlspecialchars($echantillons['date_prelevement2'] !== '0000-00-00' ? date('d/m/Y', strtotime($echantillons['date_prelevement2'])) : '-') ?><br>
                                       <strong>Technicien 2 :</strong> <?= htmlspecialchars($echantillons['technicien2']) ?>
                                       <?php endif; ?>
                                    </p>
                                </div>
                            <?php else: ?>
                                <div class="info-item">
                                    <h5>Échantillon féminin</h5>
                                    <p><strong>Type :</strong> <?= htmlspecialchars($echantillons['type_echantillon']) ?><br>
                                       <strong>Date :</strong> <?= htmlspecialchars(date('d/m/Y', strtotime($echantillons['date_prelevement']))) ?><br>
                                       <strong>Technicien :</strong> <?= htmlspecialchars($echantillons['technicien']) ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="empty-section">
                            <i class="fas fa-flask"></i>
                            <p>Aucun échantillon prélevé</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="actions-bar">
            <a href="visites_patient.php?urap=<?= htmlspecialchars($patient['Numero_urap']) ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour aux visites
            </a>
            <a href="liste_dossiers.php" class="btn btn-secondary">
                <i class="fas fa-list"></i> Liste des dossiers
            </a>
            <a href="visite.php?idU=<?= htmlspecialchars($patient['Numero_urap']) ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nouvelle visite
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
            const patientIcon = document.getElementById('icon-patient');
            if (patientIcon) {
                patientIcon.classList.add('rotated');
            }
        });
    </script>
</body>
</html>