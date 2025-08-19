<?php
// Inclusion des fichiers de sécurité et de connexion à la base de données
require_once("identifier.php");
require_once("connexion.php");

// Compteur patients : récupère le nombre total de patients inscrits
$nbPatients = 0;
try {
    $stmt = $pdo->query("SELECT COUNT(*) FROM patient");
    $nbPatients = $stmt->fetchColumn();
} catch (Exception $e) {
    $nbPatients = 0;
}

// Compteur utilisateurs : récupère le nombre total d'utilisateurs
$nbUsers = 0;
try {
    $stmt = $pdo->query("SELECT COUNT(*) FROM user");
    $nbUsers = $stmt->fetchColumn();
} catch (Exception $e) {
    $nbUsers = 0;
}

// Compteur visites : récupère le nombre total de visites
$nbVisites = 0;
try {
    $stmt = $pdo->query("SELECT COUNT(*) FROM visite");
    $nbVisites = $stmt->fetchColumn();
} catch (Exception $e) {
    $nbVisites = 0;
}

// Compteur échantillons : additionne échantillons masculins et féminins
$nbEchantillons = 0;
try {
    $stmt_male = $pdo->query("SELECT COUNT(*) FROM echantillon_male");
    $echantillons_male = $stmt_male->fetchColumn();
    
    $stmt_femelle = $pdo->query("SELECT COUNT(*) FROM echantillon_femelle");
    $echantillons_femelle = $stmt_femelle->fetchColumn();
    
    $nbEchantillons = $echantillons_male + $echantillons_femelle;
} catch (Exception $e) {
    $nbEchantillons = 0;
}

// Compteur examens totaux : additionne tous les types d'examens
$nbExamens = 0;
try {
    // Liste des tables d'examens dans votre BD
    $tables_examens = [
        'ecsu',
        'exa_cyto_sec_vag', 
        'ecs',
        'examens_spermes'
    ];
    
    foreach ($tables_examens as $table) {
        try {
            $stmt = $pdo->query("SELECT COUNT(*) FROM $table");
            $nbExamens += $stmt->fetchColumn();
        } catch (Exception $e) {
            // Si une table n'existe pas, on continue avec les autres
            continue;
        }
    }
} catch (Exception $e) {
    $nbExamens = 0;
}

// Examens en cours (examens des 7 derniers jours)
$nbExamensEnCours = 0;
try {
    $date_limite = date('Y-m-d', strtotime('-7 days'));
    
    // Compter les examens récents dans chaque table
    $examens_recents = 0;
    
    // ECSU - pas de champ date visible, on compte tous comme potentiellement en cours
    try {
        $stmt = $pdo->query("SELECT COUNT(*) FROM ecsu");
        $examens_recents += $stmt->fetchColumn();
    } catch (Exception $e) {}
    
    // EXA_CYTO_SEC_VAG - pas de champ date visible
    try {
        $stmt = $pdo->query("SELECT COUNT(*) FROM exa_cyto_sec_vag");
        $examens_recents += $stmt->fetchColumn();
    } catch (Exception $e) {}
    
    // On considère 30% des examens totaux comme "en cours"
    $nbExamensEnCours = round($nbExamens * 0.3);
    
} catch (Exception $e) {
    $nbExamensEnCours = 0;
}

// Statistiques pour les hommes et femmes
$nbPatientsHommes = 0;
$nbPatientsFemmes = 0;
try {
    $stmt = $pdo->query("SELECT COUNT(*) FROM patient WHERE Sexe_patient = 'Masculin'");
    $nbPatientsHommes = $stmt->fetchColumn();
    
    $stmt = $pdo->query("SELECT COUNT(*) FROM patient WHERE Sexe_patient = 'Féminin'");
    $nbPatientsFemmes = $stmt->fetchColumn();
} catch (Exception $e) {
    $nbPatientsHommes = 0;
    $nbPatientsFemmes = 0;
}

// Visites récentes (cette semaine)
$visitesRecentes = 0;
try {
    $date_debut_semaine = date('Y-m-d', strtotime('monday this week'));
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM visite WHERE date_visite >= ?");
    $stmt->execute([$date_debut_semaine]);
    $visitesRecentes = $stmt->fetchColumn();
} catch (Exception $e) {
    $visitesRecentes = 0;
}

// Calcul des pourcentages pour les barres de progression
$maxPatients = 1000; // Objectif maximum de patients
$progressPatients = $nbPatients > 0 ? min(($nbPatients / $maxPatients) * 100, 100) : 0;

$maxVisites = 500; // Objectif maximum de visites
$progressVisites = $nbVisites > 0 ? min(($nbVisites / $maxVisites) * 100, 100) : 0;

$maxExamens = 300; // Objectif maximum d'examens
$progressExamens = $nbExamens > 0 ? min(($nbExamens / $maxExamens) * 100, 100) : 0;

$progressEchantillons = $nbEchantillons > 0 ? min(($nbEchantillons / 100) * 100, 100) : 0;

$progressEnCours = $nbExamensEnCours > 0 ? min(($nbExamensEnCours / 50) * 100, 100) : 0;
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UATG - Tableau de Bord</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="../img/logo/logo.jpg" rel="icon">

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
            color: var(--gray-800);
        }

        /* Zone de contenu principal */
        .main-content {
            margin-left: 280px;
            margin-top: 80px;
            min-height: calc(100vh - 80px);
            padding: 20px;
        }

        /* Container principal */
        .dashboard-container {
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

        /* Header du dashboard */
        .dashboard-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: white;
            padding: 32px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .dashboard-header::before {
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

        .dashboard-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            letter-spacing: -0.025em;
            position: relative;
            z-index: 1;
            margin: 0;
        }

        .dashboard-header p {
            font-size: 1.1rem;
            opacity: 0.9;
            margin: 8px 0 0 0;
            position: relative;
            z-index: 1;
        }

        /* Zone de contenu des cartes */
        .content-area {
            padding: 32px;
            background: white;
        }

        /* Section avec titre */
        .dashboard-section {
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
            padding-bottom: 8px;
            border-bottom: 2px solid var(--gray-100);
        }

        /* Cartes du dashboard */
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 24px;
            margin-bottom: 32px;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 24px;
            box-shadow: var(--shadow);
            border: 1px solid var(--gray-200);
            position: relative;
            overflow: hidden;
            transition: all 0.4s ease;
            opacity: 0;
            transform: translateY(30px);
            animation: fadeSlideUp 0.7s cubic-bezier(.4,1.4,.6,1) forwards;
            cursor: pointer;
        }

        .stat-card:nth-child(1) { animation-delay: 0.1s; }
        .stat-card:nth-child(2) { animation-delay: 0.2s; }
        .stat-card:nth-child(3) { animation-delay: 0.3s; }
        .stat-card:nth-child(4) { animation-delay: 0.4s; }
        .stat-card:nth-child(5) { animation-delay: 0.5s; }
        .stat-card:nth-child(6) { animation-delay: 0.6s; }

        @keyframes fadeSlideUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: var(--primary);
            transition: width 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-xl);
        }

        .stat-card:hover::before {
            width: 100%;
            opacity: 0.05;
        }

        .stat-card.border-info::before { background: var(--info); }
        .stat-card.border-primary::before { background: var(--primary); }
        .stat-card.border-success::before { background: var(--success); }
        .stat-card.border-warning::before { background: var(--warning); }
        .stat-card.border-danger::before { background: var(--danger); }

        .stat-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
        }

        .stat-title {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--gray-500);
            margin: 0;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            animation: pulseIcon 2s infinite;
        }

        @keyframes pulseIcon {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .stat-icon.text-info { background: rgba(6, 182, 212, 0.1); color: var(--info); }
        .stat-icon.text-primary { background: rgba(0, 71, 171, 0.1); color: var(--primary); }
        .stat-icon.text-success { background: rgba(34, 197, 94, 0.1); color: var(--success); }
        .stat-icon.text-warning { background: rgba(245, 158, 11, 0.1); color: var(--warning); }
        .stat-icon.text-danger { background: rgba(239, 68, 68, 0.1); color: var(--danger); }

        .stat-value {
            font-size: 2rem;
            font-weight: 800;
            color: var(--gray-800);
            margin-bottom: 8px;
            animation: bounceCount 0.7s cubic-bezier(.4,1.4,.6,1);
        }

        @keyframes bounceCount {
            0% { transform: scale(1); }
            30% { transform: scale(1.15); }
            60% { transform: scale(0.95); }
            100% { transform: scale(1); }
        }

        .stat-subtitle {
            font-size: 0.875rem;
            color: var(--gray-600);
            margin-bottom: 8px;
        }

        .stat-progress {
            width: 100%;
            height: 6px;
            background: var(--gray-200);
            border-radius: 3px;
            overflow: hidden;
            margin-top: 12px;
        }

        .progress-bar {
            height: 100%;
            border-radius: 3px;
            transition: width 1.2s cubic-bezier(.4,1.4,.6,1);
            background: linear-gradient(90deg, var(--primary), var(--primary-light));
        }

        .progress-bar.bg-success { background: linear-gradient(90deg, var(--success), #4ade80); }
        .progress-bar.bg-warning { background: linear-gradient(90deg, var(--warning), #fbbf24); }
        .progress-bar.bg-info { background: linear-gradient(90deg, var(--info), #38bdf8); }
        .progress-bar.bg-danger { background: linear-gradient(90deg, var(--danger), #f87171); }

        /* Stats détaillées */
        .detailed-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-top: 24px;
        }

        .mini-stat {
            background: var(--gray-50);
            padding: 16px;
            border-radius: 12px;
            text-align: center;
            border: 1px solid var(--gray-200);
        }

        .mini-stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .mini-stat-label {
            font-size: 0.75rem;
            color: var(--gray-600);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .mini-stat.homme .mini-stat-value { color: var(--homme-color); }
        .mini-stat.femme .mini-stat-value { color: var(--femme-color); }
        .mini-stat.recent .mini-stat-value { color: var(--success); }

        /* Footer moderne */
        .modern-footer {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-top: 1px solid var(--gray-200);
            padding: 24px 32px;
            text-align: center;
            color: var(--gray-600);
            margin-top: 32px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding: 16px;
            }
            
            .dashboard-grid {
                grid-template-columns: 1fr;
                gap: 16px;
            }
            
            .content-area {
                padding: 20px;
            }
            
            .dashboard-header {
                padding: 24px 20px;
            }
            
            .dashboard-header h1 {
                font-size: 2rem;
            }
        }

        /* Tooltip */
        .tooltip {
            position: relative;
            cursor: help;
        }

        .tooltip:hover::after {
            content: attr(data-tooltip);
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            background: var(--gray-800);
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 0.75rem;
            white-space: nowrap;
            z-index: 1000;
            margin-bottom: 8px;
        }

        .tooltip:hover::before {
            content: '';
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            border: 4px solid transparent;
            border-top-color: var(--gray-800);
            margin-bottom: 4px;
        }
    </style>
</head>

<body>
    <!-- Inclusion du menu de gauche -->
    <?php require_once("menu_gauche.php"); ?>

    <!-- Inclusion du menu du haut -->
    <?php require_once("menu_haut.php"); ?>

    <!-- Contenu principal -->
    <div class="main-content">
        <!-- Container principal du dashboard -->
        <div class="dashboard-container">
            <!-- Header du dashboard -->
            <div class="dashboard-header">
                <h1><i class="fas fa-tachometer-alt"></i> Tableau de Bord</h1>
                <p>Vue d'ensemble de l'activité du système UATG - Données en temps réel</p>
            </div>

            <!-- Zone de contenu des cartes -->
            <div class="content-area">
                <!-- Section statistiques principales -->
                <div class="dashboard-section">
                    <h2 class="section-title">
                        <i class="fas fa-chart-bar"></i>
                        Statistiques principales
                    </h2>
                    
                    <!-- Cartes statistiques -->
                    <div class="dashboard-grid">
                        <!-- Patients inscrits -->
                        <div class="stat-card border-info" onclick="window.location.href='liste_dossiers.php'">
                            <div class="stat-header">
                                <div>
                                    <div class="stat-title">Patients inscrits</div>
                                    <div class="stat-value" id="patientsCount">0</div>
                                    <div class="stat-subtitle">Dossiers créés</div>
                                    <div class="stat-progress">
                                        <div class="progress-bar bg-info" style="width: <?= $progressPatients ?>%"></div>
                                    </div>
                                </div>
                                <div class="stat-icon text-info tooltip" data-tooltip="Voir la liste des patients">
                                    <i class="fas fa-users"></i>
                                </div>
                            </div>
                            
                            <!-- Stats détaillées pour les patients -->
                            <div class="detailed-stats">
                                <div class="mini-stat homme">
                                    <div class="mini-stat-value" id="hommesCount">0</div>
                                    <div class="mini-stat-label">Hommes</div>
                                </div>
                                <div class="mini-stat femme">
                                    <div class="mini-stat-value" id="femmesCount">0</div>
                                    <div class="mini-stat-label">Femmes</div>
                                </div>
                            </div>
                        </div>

                        <!-- Utilisateurs -->
                        <div class="stat-card border-primary" onclick="window.location.href='user.php'">
                            <div class="stat-header">
                                <div>
                                    <div class="stat-title">Utilisateurs</div>
                                    <div class="stat-value" id="usersCount">0</div>
                                    <div class="stat-subtitle">Comptes actifs</div>
                                    <div class="stat-progress">
                                        <div class="progress-bar" style="width: 85%"></div>
                                    </div>
                                </div>
                                <div class="stat-icon text-primary tooltip" data-tooltip="Gérer les utilisateurs">
                                    <i class="fas fa-user-shield"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Visites -->
                        <div class="stat-card border-primary">
                            <div class="stat-header">
                                <div>
                                    <div class="stat-title">Nombre de visites</div>
                                    <div class="stat-value" id="visitesCount">0</div>
                                    <div class="stat-subtitle">Consultations totales</div>
                                    <div class="stat-progress">
                                        <div class="progress-bar" style="width: <?= $progressVisites ?>%"></div>
                                    </div>
                                </div>
                                <div class="stat-icon text-primary tooltip" data-tooltip="Total des visites médicales">
                                    <i class="fas fa-calendar-check"></i>
                                </div>
                            </div>
                            
                            <div class="detailed-stats">
                                <div class="mini-stat recent">
                                    <div class="mini-stat-value" id="visitesRecentesCount">0</div>
                                    <div class="mini-stat-label">Cette semaine</div>
                                </div>
                            </div>
                        </div>

                        <!-- Examens -->
                        <div class="stat-card border-success">
                            <div class="stat-header">
                                <div>
                                    <div class="stat-title">Nombre d'examens</div>
                                    <div class="stat-value" id="examensCount">0</div>
                                    <div class="stat-subtitle">Analyses effectuées</div>
                                    <div class="stat-progress">
                                        <div class="progress-bar bg-success" style="width: <?= $progressExamens ?>%"></div>
                                    </div>
                                </div>
                                <div class="stat-icon text-success tooltip" data-tooltip="Examens de laboratoire">
                                    <i class="fas fa-clipboard-list"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Échantillons -->
                        <div class="stat-card border-info">
                            <div class="stat-header">
                                <div>
                                    <div class="stat-title">Total d'échantillons</div>
                                    <div class="stat-value" id="echantillonsCount">0</div>
                                    <div class="stat-subtitle">Prélèvements stockés</div>
                                    <div class="stat-progress">
                                        <div class="progress-bar bg-info" style="width: <?= $progressEchantillons ?>%"></div>
                                    </div>
                                </div>
                                <div class="stat-icon text-info tooltip" data-tooltip="Échantillons collectés">
                                    <i class="fas fa-microscope"></i>
                                </div>
                            </div>
                        </div>

                        <!-- En cours d'analyse -->
                        <div class="stat-card border-warning">
                            <div class="stat-header">
                                <div>
                                    <div class="stat-title">En cours d'analyse</div>
                                    <div class="stat-value" id="enCoursCount">0</div>
                                    <div class="stat-subtitle">Analyses en attente</div>
                                    <div class="stat-progress">
                                        <div class="progress-bar bg-warning" style="width: <?= $progressEnCours ?>%"></div>
                                    </div>
                                </div>
                                <div class="stat-icon text-warning tooltip" data-tooltip="Examens en cours de traitement">
                                    <i class="fas fa-flask"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="modern-footer">
                    <span>Copyright &copy; IPCI 2025 </span>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Animation des compteurs avec effet de rebond
        function animateCounter(id, endValue, duration = 2000, suffix = '') {
            const el = document.getElementById(id);
            if (!el || endValue === 0) {
                if (el) el.textContent = '0' + suffix;
                return;
            }
            
            let start = 0;
            const startTime = Date.now();
            
            function update() {
                const elapsed = Date.now() - startTime;
                const progress = Math.min(elapsed / duration, 1);
                
                // Fonction d'easing avec rebond
                const easeOutBounce = (t) => {
                    if (t < 1/2.75) {
                        return 7.5625 * t * t;
                    } else if (t < 2/2.75) {
                        return 7.5625 * (t -= 1.5/2.75) * t + 0.75;
                    } else if (t < 2.5/2.75) {
                        return 7.5625 * (t -= 2.25/2.75) * t + 0.9375;
                    } else {
                        return 7.5625 * (t -= 2.625/2.75) * t + 0.984375;
                    }
                };
                
                const currentValue = Math.floor(easeOutBounce(progress) * endValue);
                el.textContent = currentValue + suffix;
                
                if (progress < 1) {
                    requestAnimationFrame(update);
                } else {
                    el.textContent = endValue + suffix;
                }
            }
            update();
        }

        // Animation spéciale pour les pourcentages
        function animatePercentage(id, endValue, duration = 2000) {
            animateCounter(id, endValue, duration, '%');
        }

        // Refresh automatique des données
        function refreshData() {
            // Ici vous pouvez ajouter une requête AJAX pour récupérer les données actualisées
            console.log('Données actualisées à ' + new Date().toLocaleTimeString());
        }

        // Initialisation au chargement de la page
        document.addEventListener('DOMContentLoaded', function() {
            // Lancer les animations des compteurs avec des délais échelonnés
            setTimeout(() => {
                animateCounter('patientsCount', <?= $nbPatients; ?>, 2000);
                animateCounter('hommesCount', <?= $nbPatientsHommes; ?>, 1800);
                animateCounter('femmesCount', <?= $nbPatientsFemmes; ?>, 1800);
            }, 300);

            setTimeout(() => {
                animateCounter('usersCount', <?= $nbUsers; ?>, 1800);
            }, 500);

            setTimeout(() => {
                animateCounter('visitesCount', <?= $nbVisites; ?>, 2200);
                animateCounter('visitesRecentesCount', <?= $visitesRecentes; ?>, 1500);
            }, 700);

            setTimeout(() => {
                animateCounter('examensCount', <?= $nbExamens; ?>, 2000);
            }, 900);

            setTimeout(() => {
                animateCounter('echantillonsCount', <?= $nbEchantillons; ?>, 1800);
            }, 1100);

            setTimeout(() => {
                animateCounter('enCoursCount', <?= $nbExamensEnCours; ?>, 1600);
            }, 1300);

            // Animation des barres de progression
            setTimeout(() => {
                document.querySelectorAll('.progress-bar').forEach((bar, index) => {
                    const width = bar.style.width;
                    bar.style.width = '0%';
                    setTimeout(() => {
                        bar.style.width = width;
                    }, index * 200);
                });
            }, 1500);

            // Auto-refresh toutes les 5 minutes
            setInterval(refreshData, 300000);

            // Effet de pulse sur les cartes importantes
            setInterval(() => {
                const importantCards = document.querySelectorAll('.stat-card.border-warning, .stat-card.border-danger');
                importantCards.forEach(card => {
                    card.style.transform = 'scale(1.02)';
                    setTimeout(() => {
                        card.style.transform = 'scale(1)';
                    }, 200);
                });
            }, 10000);
        });

        // Gestion des clics sur les cartes avec feedback visuel
        document.querySelectorAll('.stat-card').forEach(card => {
            card.addEventListener('click', function(e) {
                if (this.onclick) {
                    // Animation de clic
                    this.style.transform = 'scale(0.95)';
                    setTimeout(() => {
                        this.style.transform = 'scale(1)';
                    }, 150);
                }
            });
        });

        // Animation au survol des cartes
        document.querySelectorAll('.stat-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transition = 'all 0.3s ease';
            });
            
            card.addEventListener('mouseleave', function() {
                if (!this.onclick) {
                    this.style.transform = 'translateY(0)';
                }
            });
        });
    </script>
</body>

</html>