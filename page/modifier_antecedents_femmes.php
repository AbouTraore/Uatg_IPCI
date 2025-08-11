<?php
require_once("identifier.php");
require_once("connexion.php");

$numero_urap = $_GET['urap'] ?? '';
$antecedent_id = $_GET['id'] ?? '';
$message = '';
$message_type = '';

// Récupérer les données existantes
$antecedents = null;
if ($antecedent_id) {
    $stmt = $pdo->prepare("SELECT * FROM antecedents_ist_genicologiques WHERE ID_antecedents = ? AND Numero_urap = ?");
    $stmt->execute([$antecedent_id, $numero_urap]);
    $antecedents = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Initialiser les valeurs par défaut
$pertes_vaginales = $antecedents ? $antecedents['Avez_vous_eu_des_pertes_vaginales_ces_deux_derniers_mois'] : '';
$douleurs_bas_ventre = $antecedents ? $antecedents['Avez_vous_eu_des_douleurs_au_bas_ventre_ces_deux_derniers_mois'] : '';
$plaies_vaginales = $antecedents ? $antecedents['Avez_vous_eu_des_plaies_vaginales_ces_deux_derniers_mois'] : '';
$douleurs_rapports = $antecedents ? $antecedents['Avez_vous_eu_mal_au_cours_des_derniers_rapport_sexuels'] : '';
$gestite = $antecedents ? $antecedents['Antecedant_ist_genicologique_gestité'] : '';
$parite = $antecedents ? $antecedents['Antecedant_ist_genicologique_parité'] : '';
$derniers_regles = $antecedents ? $antecedents['Date_des_derniers_regles'] : '';
$ivg = $antecedents ? $antecedents['Avez_vous_eu_des_ivgcette_annee_moins_d_un_an'] : '';
$toilette_vaginale = $antecedents ? $antecedents['Pratiquez_vous_une_toillette_vaginale_avec_les_doigts_'] : '';
$avec_quoi = $antecedents ? $antecedents['Si_oui_avec_quoi'] : '';
$tampon = $antecedents ? $antecedents['Quel_tampon_utilisez_vous_pendant_les_regles'] : '';
$enceinte = $antecedents ? $antecedents['etes_vous_enceinte'] : '';
$qui_consulte = $antecedents ? $antecedents['qui_avez_vous_consulte'] : '';
$medicaments = $antecedents ? $antecedents['medicaments_prescrits'] : '';
$preciser_medicaments = $antecedents ? $antecedents['preciser_medicaments'] : '';
$duree_traitement = $antecedents ? $antecedents['duree_traitement'] : '';
$antibiotique = $antecedents ? $antecedents['Prenez_vous_un_antibiotique_actuellement'] : '';
$autre = $antecedents ? $antecedents['autre'] : '';

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pertes_vaginales = htmlspecialchars($_POST['pertes_vaginales'] ?? '');
    $douleurs_bas_ventre = htmlspecialchars($_POST['douleurs_bas_ventre'] ?? '');
    $plaies_vaginales = htmlspecialchars($_POST['plaies_vaginales'] ?? '');
    $douleurs_rapports = htmlspecialchars($_POST['douleurs_rapports'] ?? '');
    $gestite = htmlspecialchars($_POST['gestite'] ?? '');
    $parite = htmlspecialchars($_POST['parite'] ?? '');
    $derniers_regles = htmlspecialchars($_POST['derniers_regles'] ?? '');
    $ivg = htmlspecialchars($_POST['ivg'] ?? '');
    $toilette_vaginale = htmlspecialchars($_POST['toilette_vaginale'] ?? '');
    $avec_quoi = htmlspecialchars($_POST['avec_quoi'] ?? '');
    $tampon = htmlspecialchars($_POST['tampon'] ?? '');
    $enceinte = htmlspecialchars($_POST['enceinte'] ?? '');
    $qui_consulte = htmlspecialchars($_POST['qui_consulte'] ?? '');
    $medicaments = htmlspecialchars($_POST['medicaments'] ?? '');
    $preciser_medicaments = htmlspecialchars($_POST['preciser_medicaments'] ?? '');
    $duree_traitement = htmlspecialchars($_POST['duree_traitement'] ?? '');
    $antibiotique = htmlspecialchars($_POST['antibiotique'] ?? '');
    $autre = htmlspecialchars($_POST['autre'] ?? '');
    $date_creation = date('Y-m-d');

    try {
        if ($antecedent_id && $antecedents) {
            // Modification
            $sql = "UPDATE antecedents_ist_genicologiques SET 
                    Avez_vous_eu_des_pertes_vaginales_ces_deux_derniers_mois = ?,
                    Avez_vous_eu_des_douleurs_au_bas_ventre_ces_deux_derniers_mois = ?,
                    Avez_vous_eu_des_plaies_vaginales_ces_deux_derniers_mois = ?,
                    Avez_vous_eu_mal_au_cours_des_derniers_rapport_sexuels = ?,
                    Antecedant_ist_genicologique_gestité = ?,
                    Antecedant_ist_genicologique_parité = ?,
                    Date_des_derniers_regles = ?,
                    Avez_vous_eu_des_ivgcette_annee_moins_d_un_an = ?,
                    Pratiquez_vous_une_toillette_vaginale_avec_les_doigts_ = ?,
                    Si_oui_avec_quoi = ?,
                    Quel_tampon_utilisez_vous_pendant_les_regles = ?,
                    etes_vous_enceinte = ?,
                    qui_avez_vous_consulte = ?,
                    medicaments_prescrits = ?,
                    preciser_medicaments = ?,
                    duree_traitement = ?,
                    Prenez_vous_un_antibiotique_actuellement = ?,
                    autre = ?,
                    date_creation = ?
                    WHERE ID_antecedents = ?";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $pertes_vaginales, $douleurs_bas_ventre, $plaies_vaginales, $douleurs_rapports,
                $gestite, $parite, $derniers_regles, $ivg, $toilette_vaginale, $avec_quoi,
                $tampon, $enceinte, $qui_consulte, $medicaments, $preciser_medicaments,
                $duree_traitement, $antibiotique, $autre, $date_creation, $antecedent_id
            ]);
            
            $message = "Les antécédents gynécologiques ont été mis à jour avec succès !";
        } else {
            // Création - Générer un nouvel ID
            $stmt = $pdo->prepare("SELECT MAX(ID_antecedents) as max_id FROM antecedents_ist_genicologiques");
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $nouvel_id = ($result['max_id'] ?? 0) + 1;
            
            $sql = "INSERT INTO antecedents_ist_genicologiques (
                    ID_antecedents, Numero_urap, 
                    Avez_vous_eu_des_pertes_vaginales_ces_deux_derniers_mois,
                    Avez_vous_eu_des_douleurs_au_bas_ventre_ces_deux_derniers_mois,
                    Avez_vous_eu_des_plaies_vaginales_ces_deux_derniers_mois,
                    Avez_vous_eu_mal_au_cours_des_derniers_rapport_sexuels,
                    Antecedant_ist_genicologique_gestité,
                    Antecedant_ist_genicologique_parité,
                    Date_des_derniers_regles,
                    Avez_vous_eu_des_ivgcette_annee_moins_d_un_an,
                    Pratiquez_vous_une_toillette_vaginale_avec_les_doigts_,
                    Si_oui_avec_quoi,
                    Quel_tampon_utilisez_vous_pendant_les_regles,
                    etes_vous_enceinte,
                    qui_avez_vous_consulte,
                    medicaments_prescrits,
                    preciser_medicaments,
                    duree_traitement,
                    Prenez_vous_un_antibiotique_actuellement,
                    autre,
                    date_creation
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $nouvel_id, $numero_urap, $pertes_vaginales, $douleurs_bas_ventre, 
                $plaies_vaginales, $douleurs_rapports, $gestite, $parite, $derniers_regles, 
                $ivg, $toilette_vaginale, $avec_quoi, $tampon, $enceinte, $qui_consulte, 
                $medicaments, $preciser_medicaments, $duree_traitement, $antibiotique, 
                $autre, $date_creation
            ]);
            
            $message = "Les antécédents gynécologiques ont été enregistrés avec succès !";
            $antecedent_id = $nouvel_id;
        }
        
        $message_type = 'success';
        
        // Recharger les données
        $stmt = $pdo->prepare("SELECT * FROM antecedents_ist_genicologiques WHERE ID_antecedents = ?");
        $stmt->execute([$antecedent_id]);
        $antecedents = $stmt->fetch(PDO::FETCH_ASSOC);
        
    } catch (PDOException $e) {
        $message = "Erreur lors de l'enregistrement : " . $e->getMessage();
        $message_type = 'danger';
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $antecedents ? 'Modifier' : 'Ajouter' ?> Antécédents Gynécologiques</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary: #0047ab;
            --primary-light: #1e90ff;
            --success: #22c55e;
            --danger: #ef4444;
            --warning: #f59e0b;
            --pink: #ec4899;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
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
            color: var(--gray-800);
        }

        .container {
            max-width: 1000px;
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
        }

        .header h1 {
            color: var(--pink);
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .alert {
            padding: 16px 20px;
            margin-bottom: 24px;
            border-radius: 12px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #34d399;
        }

        .alert-danger {
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fca5a5;
        }

        .form-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 32px;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            border-left: 4px solid var(--pink);
        }

        .form-section {
            margin-bottom: 32px;
            padding-bottom: 24px;
            border-bottom: 2px solid var(--gray-100);
        }

        .form-section:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        .section-title {
            color: var(--pink);
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }

        .form-field {
            position: relative;
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--gray-700);
            margin-bottom: 8px;
        }

        .required::after {
            content: " *";
            color: var(--danger);
            font-weight: 600;
        }

        .form-input, .form-textarea {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid var(--gray-200);
            border-radius: 12px;
            font-size: 16px;
            transition: all 0.2s ease;
            background: white;
            font-family: inherit;
        }

        .form-textarea {
            min-height: 80px;
            resize: vertical;
        }

        .form-input:focus, .form-textarea:focus {
            outline: none;
            border-color: var(--pink);
            box-shadow: 0 0 0 3px rgba(236, 72, 153, 0.1);
        }

        .full-width {
            grid-column: 1 / -1;
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
            background: linear-gradient(135deg, var(--pink) 0%, #f472b6 100%);
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
            display: flex;
            justify-content: space-between;
            margin-top: 32px;
            gap: 16px;
        }

        .info-box {
            background: #fdf2f8;
            border: 1px solid #fce7f3;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 24px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .info-box i {
            color: var(--pink);
            font-size: 1.2rem;
            margin-top: 2px;
        }

        .info-box-content h4 {
            color: var(--pink);
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .info-box-content p {
            color: #be185d;
            font-size: 0.875rem;
            line-height: 1.4;
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .actions-bar {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-female"></i> <?= $antecedents ? 'Modifier' : 'Ajouter' ?> Antécédents Gynécologiques</h1>
            <p>Patient N°URAP: <?= htmlspecialchars($numero_urap) ?></p>
        </div>

        <?php if (!empty($message)): ?>
            <div class="alert alert-<?= $message_type ?>">
                <i class="fas fa-<?= ($message_type == 'success' ? 'check-circle' : 'exclamation-triangle') ?>"></i>
                <?= $message ?>
            </div>
        <?php endif; ?>

        <div class="info-box">
            <i class="fas fa-info-circle"></i>
            <div class="info-box-content">
                <h4>Information importante</h4>
                <p>Ces informations concernent les antécédents gynécologiques et d'infections sexuellement transmissibles. Toutes les données restent strictement confidentielles.</p>
            </div>
        </div>

        <div class="form-container">
            <form method="post">
                <!-- Section Symptômes récents -->
                <div class="form-section">
                    <h3 class="section-title">
                        <i class="fas fa-exclamation-triangle"></i>
                        Symptômes des 2 derniers mois
                    </h3>
                    <div class="form-grid">
                        <div class="form-field">
                            <label for="pertes_vaginales" class="form-label">Avez-vous eu des pertes vaginales ?</label>
                            <select id="pertes_vaginales" name="pertes_vaginales" class="form-input">
                                <option value="">-- Sélectionner --</option>
                                <option value="oui" <?= $pertes_vaginales == 'oui' ? 'selected' : '' ?>>Oui</option>
                                <option value="non" <?= $pertes_vaginales == 'non' ? 'selected' : '' ?>>Non</option>
                            </select>
                        </div>
                        
                        <div class="form-field">
                            <label for="douleurs_bas_ventre" class="form-label">Avez-vous eu des douleurs au bas ventre ?</label>
                            <select id="douleurs_bas_ventre" name="douleurs_bas_ventre" class="form-input">
                                <option value="">-- Sélectionner --</option>
                                <option value="oui" <?= $douleurs_bas_ventre == 'oui' ? 'selected' : '' ?>>Oui</option>
                                <option value="non" <?= $douleurs_bas_ventre == 'non' ? 'selected' : '' ?>>Non</option>
                            </select>
                        </div>
                        
                        <div class="form-field">
                            <label for="plaies_vaginales" class="form-label">Avez-vous eu des plaies vaginales ?</label>
                            <select id="plaies_vaginales" name="plaies_vaginales" class="form-input">
                                <option value="">-- Sélectionner --</option>
                                <option value="oui" <?= $plaies_vaginales == 'oui' ? 'selected' : '' ?>>Oui</option>
                                <option value="non" <?= $plaies_vaginales == 'non' ? 'selected' : '' ?>>Non</option>
                            </select>
                        </div>
                        
                        <div class="form-field">
                            <label for="douleurs_rapports" class="form-label">Avez-vous eu mal lors des rapports sexuels ?</label>
                            <select id="douleurs_rapports" name="douleurs_rapports" class="form-input">
                                <option value="">-- Sélectionner --</option>
                                <option value="oui" <?= $douleurs_rapports == 'oui' ? 'selected' : '' ?>>Oui</option>
                                <option value="non" <?= $douleurs_rapports == 'non' ? 'selected' : '' ?>>Non</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Section Antécédents gynécologiques -->
                <div class="form-section">
                    <h3 class="section-title">
                        <i class="fas fa-baby"></i>
                        Antécédents gynécologiques
                    </h3>
                    <div class="form-grid">
                        <div class="form-field">
                            <label for="gestite" class="form-label">Gestité (nombre de grossesses)</label>
                            <input type="number" id="gestite" name="gestite" class="form-input" 
                                   value="<?= htmlspecialchars($gestite) ?>" min="0" max="20">
                        </div>
                        
                        <div class="form-field">
                            <label for="parite" class="form-label">Parité (nombre d'accouchements)</label>
                            <input type="number" id="parite" name="parite" class="form-input" 
                                   value="<?= htmlspecialchars($parite) ?>" min="0" max="20">
                        </div>
                        
                        <div class="form-field">
                            <label for="derniers_regles" class="form-label">Date des dernières règles</label>
                            <input type="date" id="derniers_regles" name="derniers_regles" class="form-input" 
                                   value="<?= htmlspecialchars($derniers_regles) ?>">
                        </div>
                        
                        <div class="form-field">
                            <label for="ivg" class="form-label">Avez-vous eu des IVG cette année ?</label>
                            <select id="ivg" name="ivg" class="form-input">
                                <option value="">-- Sélectionner --</option>
                                <option value="oui" <?= $ivg == 'oui' ? 'selected' : '' ?>>Oui</option>
                                <option value="non" <?= $ivg == 'non' ? 'selected' : '' ?>>Non</option>
                            </select>
                        </div>
                        
                        <div class="form-field">
                            <label for="enceinte" class="form-label">Êtes-vous enceinte ?</label>
                            <select id="enceinte" name="enceinte" class="form-input">
                                <option value="">-- Sélectionner --</option>
                                <option value="Femme non enceinte" <?= $enceinte == 'Femme non enceinte' ? 'selected' : '' ?>>Femme non enceinte</option>
                                <option value="Femme enceinte" <?= $enceinte == 'Femme enceinte' ? 'selected' : '' ?>>Femme enceinte</option>
                                <option value="Indéterminé" <?= $enceinte == 'Indéterminé' ? 'selected' : '' ?>>Indéterminé</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Section Hygiène -->
                <div class="form-section">
                    <h3 class="section-title">
                        <i class="fas fa-soap"></i>
                        Hygiène intime
                    </h3>
                    <div class="form-grid">
                        <div class="form-field">
                            <label for="toilette_vaginale" class="form-label">Pratiquez-vous une toilette vaginale avec les doigts ?</label>
                            <select id="toilette_vaginale" name="toilette_vaginale" class="form-input" onchange="toggleToilette()">
                                <option value="">-- Sélectionner --</option>
                                <option value="oui" <?= $toilette_vaginale == 'oui' ? 'selected' : '' ?>>Oui</option>
                                <option value="non" <?= $toilette_vaginale == 'non' ? 'selected' : '' ?>>Non</option>
                            </select>
                        </div>
                        
                        <div class="form-field" id="field-avec-quoi" style="<?= $toilette_vaginale == 'oui' ? '' : 'display: none;' ?>">
                            <label for="avec_quoi" class="form-label">Si oui, avec quoi ?</label>
                            <select id="avec_quoi" name="avec_quoi" class="form-input">
                                <option value="">-- Sélectionner --</option>
                                <option value="Eau simple" <?= $avec_quoi == 'Eau simple' ? 'selected' : '' ?>>Eau simple</option>
                                <option value="Savon" <?= $avec_quoi == 'Savon' ? 'selected' : '' ?>>Savon</option>
                                <option value="Antiseptique" <?= $avec_quoi == 'Antiseptique' ? 'selected' : '' ?>>Antiseptique</option>
                                <option value="Autre" <?= $avec_quoi == 'Autre' ? 'selected' : '' ?>>Autre</option>
                            </select>
                        </div>
                        
                        <div class="form-field">
                            <label for="tampon" class="form-label">Quel tampon utilisez-vous pendant les règles ?</label>
                            <select id="tampon" name="tampon" class="form-input">
                                <option value="">-- Sélectionner --</option>
                                <option value="Serviettes hygiéniques" <?= $tampon == 'Serviettes hygiéniques' ? 'selected' : '' ?>>Serviettes hygiéniques</option>
                                <option value="Tampons(tampax)" <?= $tampon == 'Tampons(tampax)' ? 'selected' : '' ?>>Tampons(tampax)</option>
                                <option value="Coupe menstruelle" <?= $tampon == 'Coupe menstruelle' ? 'selected' : '' ?>>Coupe menstruelle</option>
                                <option value="Autre" <?= $tampon == 'Autre' ? 'selected' : '' ?>>Autre</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Section Traitement -->
                <div class="form-section">
                    <h3 class="section-title">
                        <i class="fas fa-pills"></i>
                        Traitement et consultation
                    </h3>
                    <div class="form-grid">
                        <div class="form-field">
                            <label for="qui_consulte" class="form-label">Qui avez-vous consulté ?</label>
                            <select id="qui_consulte" name="qui_consulte" class="form-input">
                                <option value="">-- Sélectionner --</option>
                                <option value="Médecin" <?= $qui_consulte == 'Médecin' ? 'selected' : '' ?>>Médecin</option>
                                <option value="Pharmacien" <?= $qui_consulte == 'Pharmacien' ? 'selected' : '' ?>>Pharmacien</option>
                                <option value="Sage-femme" <?= $qui_consulte == 'Sage-femme' ? 'selected' : '' ?>>Sage-femme</option>
                                <option value="Autre" <?= $qui_consulte == 'Autre' ? 'selected' : '' ?>>Autre</option>
                            </select>
                        </div>
                        
                        <div class="form-field">
                            <label for="medicaments" class="form-label">Médicaments prescrits ?</label>
                            <select id="medicaments" name="medicaments" class="form-input" onchange="toggleMedicaments()">
                                <option value="">-- Sélectionner --</option>
                                <option value="oui" <?= $medicaments == 'oui' ? 'selected' : '' ?>>Oui</option>
                                <option value="non" <?= $medicaments == 'non' ? 'selected' : '' ?>>Non</option>
                            </select>
                        </div>
                        
                        <div class="form-field" id="field-preciser-med" style="<?= $medicaments == 'oui' ? '' : 'display: none;' ?>">
                            <label for="preciser_medicaments" class="form-label">Préciser les médicaments</label>
                            <textarea id="preciser_medicaments" name="preciser_medicaments" class="form-textarea" 
                                      placeholder="Précisez les médicaments prescrits..."><?= htmlspecialchars($preciser_medicaments) ?></textarea>
                        </div>
                        
                        <div class="form-field" id="field-duree" style="<?= $medicaments == 'oui' ? '' : 'display: none;' ?>">
                            <label for="duree_traitement" class="form-label">Durée du traitement</label>
                            <input type="text" id="duree_traitement" name="duree_traitement" class="form-input" 
                                   value="<?= htmlspecialchars($duree_traitement) ?>" placeholder="Ex: 7 jours">
                        </div>
                        
                        <div class="form-field">
                            <label for="antibiotique" class="form-label">Prenez-vous un antibiotique actuellement ?</label>
                            <select id="antibiotique" name="antibiotique" class="form-input">
                                <option value="">-- Sélectionner --</option>
                                <option value="oui" <?= $antibiotique == 'oui' ? 'selected' : '' ?>>Oui</option>
                                <option value="non" <?= $antibiotique == 'non' ? 'selected' : '' ?>>Non</option>
                            </select>
                        </div>
                        
                        <div class="form-field full-width">
                            <label for="autre" class="form-label">Autres informations</label>
                            <textarea id="autre" name="autre" class="form-textarea" 
                                      placeholder="Autres informations pertinentes..."><?= htmlspecialchars($autre) ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="actions-bar">
                    <a href="dossier_complet.php?urap=<?= htmlspecialchars($numero_urap) ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Annuler
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> <?= $antecedents ? 'Mettre à jour' : 'Enregistrer' ?>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleToilette() {
            const select = document.getElementById('toilette_vaginale');
            const field = document.getElementById('field-avec-quoi');
            
            if (select.value === 'oui') {
                field.style.display = 'block';
            } else {
                field.style.display = 'none';
                document.getElementById('avec_quoi').value = '';
            }
        }

        function toggleMedicaments() {
            const select = document.getElementById('medicaments');
            const fieldMed = document.getElementById('field-preciser-med');
            const fieldDuree = document.getElementById('field-duree');
            
            if (select.value === 'oui') {
                fieldMed.style.display = 'block';
                fieldDuree.style.display = 'block';
            } else {
                fieldMed.style.display = 'none';
                fieldDuree.style.display = 'none';
                document.getElementById('preciser_medicaments').value = '';
                document.getElementById('duree_traitement').value = '';
            }
        }

        // Initialiser l'état au chargement
        document.addEventListener('DOMContentLoaded', function() {
            toggleToilette();
            toggleMedicaments();
        });
    </script>
</body>
</html>