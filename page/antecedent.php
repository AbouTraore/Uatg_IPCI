<?php
// antecedents_ist.php

// Inclusion des fichiers de sécurité et de connexion à la base de données
require_once("identifier.php");
require_once("connexion.php");

// Variables pour stocker les données du formulaire
$message = '';
$messageType = '';

// Traitement du formulaire si soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Récupération des données du formulaire
        $antecedent = $_POST['antecedent'] ?? '';
        $antibiotique = $_POST['antibiotique'] ?? 'non';
        $preciser_antibiotique = $_POST['preciser_antibiotique'] ?? '';
        
        // Section Femme
        $pertes_vaginales = $_POST['pertes_vaginales'] ?? 'non';
        $douleurs_bas_ventre = $_POST['douleurs_bas_ventre'] ?? 'non';
        $plaies_genitales = $_POST['plaies_genitales'] ?? 'non';
        $douleur_rapport = $_POST['douleur_rapport'] ?? 'non';
        $gestite = $_POST['gestite'] ?? '';
        $parite = $_POST['parite'] ?? '';
        $date_regles = $_POST['date_regles'] ?? '';
        $ivg = $_POST['ivg'] ?? 'non';
        $toilette_vaginale = $_POST['toilette_vaginale'] ?? 'non';
        $avec_quoi = $_POST['avec_quoi'] ?? '';
        $autre = $_POST['autre'] ?? '';
        $enceinte = $_POST['enceinte'] ?? 'Femme non enceinte';
        $tampons = $_POST['tampons'] ?? '';
        $consultation = $_POST['consultation'] ?? '';
        $medicaments = $_POST['medicaments'] ?? 'non';
        $preciser_medicaments = $_POST['preciser_medicaments'] ?? '';
        $duree_traitement = $_POST['duree_traitement'] ?? '';
        
        // Récupération de l'ID du patient si fourni
        $patient_id = $_GET['idU'] ?? $_POST['patient_id'] ?? null;
        
        // Préparation de la requête d'insertion
        $sql = "INSERT INTO antecedents_ist (
            patient_id, antecedent, antibiotique, preciser_antibiotique,
            pertes_vaginales, douleurs_bas_ventre, plaies_genitales, douleur_rapport,
            gestite, parite, date_regles, ivg, toilette_vaginale, avec_quoi, autre,
            enceinte, tampons, consultation, medicaments, preciser_medicaments,
            duree_traitement, date_creation
        ) VALUES (
            :patient_id, :antecedent, :antibiotique, :preciser_antibiotique,
            :pertes_vaginales, :douleurs_bas_ventre, :plaies_genitales, :douleur_rapport,
            :gestite, :parite, :date_regles, :ivg, :toilette_vaginale, :avec_quoi, :autre,
            :enceinte, :tampons, :consultation, :medicaments, :preciser_medicaments,
            :duree_traitement, NOW()
        )";
        
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([
            ':patient_id' => $patient_id,
            ':antecedent' => $antecedent,
            ':antibiotique' => $antibiotique,
            ':preciser_antibiotique' => $preciser_antibiotique,
            ':pertes_vaginales' => $pertes_vaginales,
            ':douleurs_bas_ventre' => $douleurs_bas_ventre,
            ':plaies_genitales' => $plaies_genitales,
            ':douleur_rapport' => $douleur_rapport,
            ':gestite' => $gestite,
            ':parite' => $parite,
            ':date_regles' => $date_regles ?: null,
            ':ivg' => $ivg,
            ':toilette_vaginale' => $toilette_vaginale,
            ':avec_quoi' => $avec_quoi,
            ':autre' => $autre,
            ':enceinte' => $enceinte,
            ':tampons' => $tampons,
            ':consultation' => $consultation,
            ':medicaments' => $medicaments,
            ':preciser_medicaments' => $preciser_medicaments,
            ':duree_traitement' => $duree_traitement
        ]);
        
        if ($result) {
            $message = 'Antécédents IST enregistrés avec succès !';
            $messageType = 'success';
        } else {
            $message = 'Erreur lors de l\'enregistrement des antécédents.';
            $messageType = 'error';
        }
        
    } catch (Exception $e) {
        $message = 'Erreur : ' . $e->getMessage();
        $messageType = 'error';
    }
}

// Récupération des informations du patient si ID fourni
$patient = null;
if (isset($_GET['idU'])) {
    $patient_id = $_GET['idU'];
    $stmt = $pdo->prepare("SELECT * FROM patient WHERE Numero_urap = ?");
    $stmt->execute([$patient_id]);
    $patient = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Antécédents IST - UATG</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #0047ab;
            --primary-light: #1e90ff;
            --primary-dark: #003380;
            --secondary: #f8fafc;
            --accent: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
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
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
            --homme-color: #2563eb;
            --femme-color: #ec4899;
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
            box-shadow: var(--shadow-xl);
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
            letter-spacing: -0.025em;
            position: relative;
            z-index: 1;
        }

        .header p {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-top: 8px;
            position: relative;
            z-index: 1;
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
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
            color: var(--primary);
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: background 0.2s;
            z-index: 2;
        }

        .btn-retour:hover {
            background: var(--gray-200);
        }

        .content-area {
            padding: 32px;
            background: white;
        }

        .form-section {
            margin-bottom: 32px;
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--gray-800);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 16px 20px;
            border-radius: 12px;
            position: relative;
        }

        .section-title.homme {
            background: linear-gradient(135deg, var(--homme-color) 0%, #3b82f6 100%);
            color: white;
        }

        .section-title.femme {
            background: linear-gradient(135deg, var(--femme-color) 0%, #f472b6 100%);
            color: white;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
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
            margin-bottom: 6px;
        }

        .form-input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid var(--gray-200);
            border-radius: 12px;
            font-size: 14px;
            transition: all 0.2s ease;
            background: white;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(0, 71, 171, 0.1);
        }

        .form-select {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid var(--gray-200);
            border-radius: 12px;
            font-size: 14px;
            background: white;
            cursor: pointer;
            transition: all 0.2s ease;
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 12px center;
            background-repeat: no-repeat;
            background-size: 16px;
            padding-right: 48px;
        }

        .form-select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(0, 71, 171, 0.1);
        }

        .actions {
            display: flex;
            gap: 16px;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 32px;
            padding-top: 24px;
            border-top: 2px solid var(--gray-200);
        }

        .btn {
            padding: 14px 28px;
            border: none;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            min-width: 120px;
            justify-content: center;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: white;
            box-shadow: var(--shadow);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .btn-secondary {
            background: white;
            color: var(--gray-700);
            border: 2px solid var(--gray-200);
        }

        .btn-secondary:hover {
            background: var(--gray-50);
            border-color: var(--gray-300);
            transform: translateY(-1px);
            text-decoration: none;
            color: var(--gray-700);
        }

        .btn-success {
            background: linear-gradient(135deg, var(--success) 0%, #16a34a 100%);
            color: white;
            box-shadow: var(--shadow);
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .alert {
            padding: 16px 20px;
            margin-bottom: 16px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 12px;
            animation: fadeInSlide 0.3s ease-out;
        }

        .alert::before {
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
            font-size: 16px;
        }

        .alert-success {
            background: rgba(34, 197, 94, 0.1);
            color: var(--success);
            border: 1px solid rgba(34, 197, 94, 0.2);
            border-left: 4px solid var(--success);
        }

        .alert-success::before {
            content: "\f00c";
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger);
            border: 1px solid rgba(239, 68, 68, 0.2);
            border-left: 4px solid var(--danger);
        }

        .alert-error::before {
            content: "\f071";
        }

        .alert-info {
            background: rgba(59, 130, 246, 0.1);
            color: var(--primary);
            border: 1px solid rgba(59, 130, 246, 0.2);
            border-left: 4px solid var(--primary);
        }

        .alert-info::before {
            content: "\f05a";
        }

        @keyframes fadeInSlide {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .section-divider {
            height: 1px;
            background: linear-gradient(to right, transparent, var(--gray-300), transparent);
            margin: 32px 0;
        }

        .patient-info {
            background: var(--gray-50);
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 24px;
            border-left: 4px solid var(--primary);
        }

        .patient-info h3 {
            color: var(--primary);
            margin-bottom: 8px;
            font-size: 1.1rem;
        }

        @media (max-width: 768px) {
            body {
                padding: 10px;
            }

            .header {
                padding: 24px 20px;
            }

            .header h1 {
                font-size: 2rem;
            }

            .content-area {
                padding: 20px;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }

            .btn-retour {
                position: static;
                margin-bottom: 16px;
                width: fit-content;
            }
        }

        .btn-retour-global {
            background: linear-gradient(135deg, #e0e7ff 0%, #bae6fd 100%);
            color: #0047ab;
            border: none;
            border-radius: 30px;
            padding: 12px 32px;
            font-size: 1.1em;
            font-weight: 600;
            box-shadow: 0 2px 8px 0 #0047ab22;
            cursor: pointer;
            transition: background 0.2s, color 0.2s;
            margin-top: 12px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .btn-retour-global:hover {
            background: linear-gradient(135deg, #10b981 0%, #1e90ff 100%);
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <button onclick="window.history.back()" class="btn-retour">
                <i class="fas fa-arrow-left"></i> Retour
            </button>
            <h1><i class="fas fa-clipboard-check"></i> Antécédents IST</h1>
            <p>Questionnaire d'évaluation des antécédents et facteurs de risque</p>
        </div>

        <div class="content-area">
            <!-- Messages d'alerte -->
            <?php if (!empty($message)): ?>
                <div class="alert alert-<?php echo $messageType; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <!-- Informations du patient si disponible -->
            <?php if ($patient): ?>
                <div class="patient-info">
                    <h3><i class="fas fa-user"></i> Patient sélectionné</h3>
                    <p><strong><?php echo htmlspecialchars($patient['Nom_patient'] . ' ' . $patient['Prenom_patient']); ?></strong> - N° URAP: <?php echo htmlspecialchars($patient['Numero_urap']); ?></p>
                </div>
            <?php else: ?>
                <div class="alert alert-info">
                    Veuillez remplir ce questionnaire en fonction du sexe du patient. Les informations collectées sont confidentielles et nécessaires pour un diagnostic précis.
                </div>
            <?php endif; ?>

            <form id="antecedentsForm" method="POST" action="antecedents_ist.php<?php echo isset($_GET['idU']) ? '?idU=' . htmlspecialchars($_GET['idU']) : ''; ?>">
                <?php if (isset($_GET['idU'])): ?>
                    <input type="hidden" name="patient_id" value="<?php echo htmlspecialchars($_GET['idU']); ?>">
                <?php endif; ?>

                <!-- Section Homme -->
                <div class="form-section">
                    <h2 class="section-title homme">
                        <i class="fas fa-mars"></i>
                        Section Homme
                    </h2>
                    <div class="form-grid">
                        <div class="form-field">
                            <label class="form-label">Avez-vous déjà ?</label>
                            <select class="form-select" name="antecedent" id="antecedent">
                                <option value="deja ete atteint d'une MST?">déjà été atteint d'une MST?</option>
                                <option value="brulure au niveau des organes genitaux">brûlures au niveau des organes génitaux</option>
                                <option value="eu des traumatismes testiculaires">eu des traumatismes testiculaires</option>
                                <option value="eu des interventions chirugicale au niveau des organes genitaux">eu des interventions chirurgicales au niveau des organes génitaux</option>
                                <option value="ete sondé">été sondé</option>
                            </select>
                        </div>

                        <div class="form-field">
                            <label class="form-label">Prenez-vous un antibiotique actuellement ?</label>
                            <select class="form-select" name="antibiotique" id="antibiotique">
                                <option value="non" selected>Non</option>
                                <option value="oui">Oui</option>
                            </select>
                        </div>

                        <div class="form-field">
                            <label class="form-label">Si oui, préciser lequel (lesquels)</label>
                            <input type="text" class="form-input" name="preciser_antibiotique" id="preciser_antibiotique" placeholder="Nom de l'antibiotique">
                        </div>
                    </div>
                </div>

                <div class="section-divider"></div>

                <!-- Section Femme -->
                <div class="form-section">
                    <h2 class="section-title femme">
                        <i class="fas fa-venus"></i>
                        Section Femme
                    </h2>
                    <div class="form-grid">
                        <div class="form-field">
                            <label class="form-label">Avez-vous eu des pertes vaginales ces deux derniers mois ?</label>
                            <select class="form-select" name="pertes_vaginales">
                                <option value="non" selected>Non</option>
                                <option value="oui">Oui</option>
                            </select>
                        </div>

                        <div class="form-field">
                            <label class="form-label">Avez-vous eu des douleurs au bas ventre ces deux derniers mois ?</label>
                            <select class="form-select" name="douleurs_bas_ventre">
                                <option value="non" selected>Non</option>
                                <option value="oui">Oui</option>
                            </select>
                        </div>

                        <div class="form-field">
                            <label class="form-label">Avez-vous eu des plaies génitales ces deux derniers mois ?</label>
                            <select class="form-select" name="plaies_genitales">
                                <option value="non" selected>Non</option>
                                <option value="oui">Oui</option>
                            </select>
                        </div>

                        <div class="form-field">
                            <label class="form-label">Avez-vous eu mal au cours des derniers rapports sexuels ?</label>
                            <select class="form-select" name="douleur_rapport">
                                <option value="non" selected>Non</option>
                                <option value="oui">Oui</option>
                            </select>
                        </div>

                        <div class="form-field">
                            <label class="form-label">Gestité</label>
                            <input type="text" class="form-input" name="gestite" placeholder="Nombre de grossesses">
                        </div>

                        <div class="form-field">
                            <label class="form-label">Parité</label>
                            <input type="text" class="form-input" name="parite" placeholder="Nombre d'accouchements">
                        </div>

                        <div class="form-field">
                            <label class="form-label">Date des dernières règles</label>
                            <input type="date" class="form-input" name="date_regles">
                        </div>

                        <div class="form-field">
                            <label class="form-label">Avez-vous fait une IVG cette année (moins d'un an) ?</label>
                            <select class="form-select" name="ivg">
                                <option value="non" selected>Non</option>
                                <option value="oui">Oui</option>
                            </select>
                        </div>

                        <div class="form-field">
                            <label class="form-label">Pratiquez-vous une toilette vaginale (avec les doigts) ?</label>
                            <select class="form-select" name="toilette_vaginale">
                                <option value="non" selected>Non</option>
                                <option value="oui">Oui</option>
                            </select>
                        </div>

                        <div class="form-field">
                            <label class="form-label">Si oui avec quoi ?</label>
                            <select class="form-select" name="avec_quoi">
                                <option value="Eau simple">Eau simple</option>
                                <option value="Eau et savon">Eau et savon</option>
                                <option value="Produit pharmaceutique">Produit pharmaceutique</option>
                                <option value="Produit africain">Produit africain</option>
                            </select>
                        </div>

                        <div class="form-field">
                            <label class="form-label">Autre</label>
                            <input type="text" class="form-input" name="autre" placeholder="Préciser si autre">
                        </div>

                        <div class="form-field">
                            <label class="form-label">Êtes-vous enceinte ?</label>
                            <select class="form-select" name="enceinte">
                                <option value="Femme non enceinte" selected>Femme non enceinte</option>
                                <option value="Femme enceinte">Femme enceinte</option>
                                <option value="Femme menopausée">Femme ménopausée</option>
                            </select>
                        </div>

                        <div class="form-field">
                            <label class="form-label">Quels tampons utilisez-vous pendant les règles ?</label>
                            <select class="form-select" name="tampons">
                                <option value="Serviettes hygieniques">Serviettes hygiéniques</option>
                                <option value="Tampons(tampax)">Tampons (tampax)</option>
                                <option value="Serviettes non hygieniques">Serviettes non hygiéniques</option>
                            </select>
                        </div>

                        <div class="form-field">
                            <label class="form-label">Qui avez-vous consulté pour ces signes ?</label>
                            <select class="form-select" name="consultation">
                                <option value="Medecin">Médecin</option>
                                <option value="Infirmier">Infirmier</option>
                                <option value="Pharmacien">Pharmacien</option>
                                <option value="Technicien de laboratoire">Technicien de laboratoire</option>
                                <option value="Tradipraticien">Tradipraticien</option>
                                <option value="Vendeur en pharmacien">Vendeur en pharmacie</option>
                            </select>
                        </div>

                        <div class="form-field">
                            <label class="form-label">Vous a-t-il prescrit des médicaments ?</label>
                            <select class="form-select" name="medicaments">
                                <option value="non" selected>Non</option>
                                <option value="oui">Oui</option>
                            </select>
                        </div>

                        <div class="form-field">
                            <label class="form-label">Si oui, préciser lequel (lesquels)</label>
                            <input type="text" class="form-input" name="preciser_medicaments" placeholder="Nom des médicaments">
                        </div>

                        <div class="form-field">
                            <label class="form-label">Depuis combien de temps vous vous traitez ?</label>
                            <select class="form-select" name="duree_traitement">
                                <option value="07jours">07 jours</option>
                                <option value="15jours">15 jours</option>
                                <option value="1mois">1 mois</option>
                                <option value="plus1mois">> 1 mois</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="actions">
                    <button type="button" class="btn btn-secondary" onclick="resetForm()">
                        <i class="fas fa-undo"></i>
                        Réinitialiser
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-paper-plane"></i>
                        Soumettre
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bouton retour global en bas de page -->
    <div style="width:100%;display:flex;justify-content:center;margin:32px 0 0 0;">
        <button onclick="window.history.back()" class="btn-retour-global">
            <i class="fas fa-arrow-left"></i> Retour
        </button>
    </div>

    <script>
        // Fonction pour réinitialiser le formulaire
        function resetForm() {
            document.getElementById('antecedentsForm').reset();
        }

        // Animation d'apparition des éléments
        document.addEventListener('DOMContentLoaded', function() {
            const formFields = document.querySelectorAll('.form-field');
            formFields.forEach((field, index) => {
                field.style.opacity = '0';
                field.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    field.style.transition = 'all 0.3s ease';
                    field.style.opacity = '1';
                    field.style.transform = 'translateY(0)';
                }, index * 50);
            });
        });

        // Logique conditionnelle pour masquer/afficher les champs
        document.getElementById('antibiotique').addEventListener('change', function() {
            const preciserField = document.getElementById('preciser_antibiotique');
            if (this.value === 'oui') {
                preciserField.style.opacity = '1';
                preciserField.required = true;
            } else {
                preciserField.style.opacity = '0.5';
                preciserField.required = false;
                preciserField.value = '';
            }
        });

        // Initialiser l'affichage des champs conditionnels
        document.getElementById('antibiotique').dispatchEvent(new Event('change'));
    </script>
</body>
</html>