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

        /* Container principal comme dans le modèle */
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

        /* Cartes du dashboard */
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
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

        .stat-card.border-info::before { background: var(--primary); }
        .stat-card.border-primary::before { background: var(--primary); }
        .stat-card.border-success::before { background: var(--success); }
        .stat-card.border-warning::before { background: var(--warning); }

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

        .stat-icon.text-info { background: rgba(0, 71, 171, 0.1); color: var(--primary); }
        .stat-icon.text-primary { background: rgba(0, 71, 171, 0.1); color: var(--primary); }
        .stat-icon.text-success { background: rgba(34, 197, 94, 0.1); color: var(--success); }
        .stat-icon.text-warning { background: rgba(245, 158, 11, 0.1); color: var(--warning); }

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

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            z-index: 10000;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 1;
        }

        .modal-content {
            background: white;
            border-radius: 20px;
            padding: 32px;
            box-shadow: var(--shadow-xl);
            max-width: 400px;
            width: 90%;
            transform: scale(0.9);
            transition: transform 0.3s ease;
        }

        .modal.show .modal-content {
            transform: scale(1);
        }

        .modal-header {
            margin-bottom: 24px;
        }

        .modal-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--gray-800);
            margin: 0;
        }

        .modal-body {
            margin-bottom: 24px;
            color: var(--gray-600);
            line-height: 1.6;
        }

        .modal-footer {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
        }

        .btn {
            padding: 12px 24px;
            border-radius: 12px;
            border: none;
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .btn-secondary {
            background: var(--gray-200);
            color: var(--gray-700);
        }

        .btn-secondary:hover {
            background: var(--gray-300);
            transform: translateY(-2px);
        }

        .btn-danger {
            background: var(--danger);
            color: white;
        }

        .btn-danger:hover {
            background: #dc2626;
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
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
                <p>Vue d'ensemble de l'activité du système UATG</p>
            </div>

            <!-- Zone de contenu des cartes -->
            <div class="content-area">
                <!-- Cartes statistiques -->
                <div class="dashboard-grid">
                    <!-- Patients inscrits -->
                    <div class="stat-card border-info">
                        <div class="stat-header">
                            <div>
                                <div class="stat-title">Patients inscrits</div>
                                <div class="stat-value" id="patientsCount">0</div>
                            </div>
                            <div class="stat-icon text-info">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Utilisateurs -->
                    <div class="stat-card border-primary">
                        <div class="stat-header">
                            <div>
                                <div class="stat-title">Utilisateurs</div>
                                <div class="stat-value" id="usersCount">0</div>
                            </div>
                            <div class="stat-icon text-primary">
                                <i class="fas fa-user-shield"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Visites -->
                    <div class="stat-card border-primary">
                        <div class="stat-header">
                            <div>
                                <div class="stat-title">Nombre de visites</div>
                                <div class="stat-value">400</div>
                                <div class="stat-progress">
                                    <div class="progress-bar" style="width: 60%"></div>
                                </div>
                            </div>
                            <div class="stat-icon text-primary">
                                <i class="fas fa-calendar"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Examens -->
                    <div class="stat-card border-success">
                        <div class="stat-header">
                            <div>
                                <div class="stat-title">Nombre d'examens</div>
                                <div class="stat-value">200</div>
                                <div class="stat-progress">
                                    <div class="progress-bar bg-success" style="width: 30%"></div>
                                </div>
                            </div>
                            <div class="stat-icon text-success">
                                <i class="fas fa-clipboard-list"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Échantillons -->
                    <div class="stat-card border-info">
                        <div class="stat-header">
                            <div>
                                <div class="stat-title">Total d'échantillons</div>
                                <div class="stat-value">50</div>
                                <div class="stat-progress">
                                    <div class="progress-bar" style="width: 50%"></div>
                                </div>
                            </div>
                            <div class="stat-icon text-info">
                                <i class="fas fa-microscope"></i>
                            </div>
                        </div>
                    </div>

                    <!-- En cours d'analyse -->
                    <div class="stat-card border-warning">
                        <div class="stat-header">
                            <div>
                                <div class="stat-title">En cours d'analyse</div>
                                <div class="stat-value">18</div>
                                <div class="stat-progress">
                                    <div class="progress-bar bg-warning" style="width: 80%"></div>
                                </div>
                            </div>
                            <div class="stat-icon text-warning">
                                <i class="fas fa-flask"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="modern-footer">
                    <span>Copyright &copy; IPCI 2025</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Animation des compteurs
        function animateCounter(id, endValue, duration = 2000) {
            const el = document.getElementById(id);
            if (!el) return;
            
            let start = 0;
            const increment = Math.ceil(endValue / (duration / 16));
            
            function update() {
                start += increment;
                if (start >= endValue) {
                    el.textContent = endValue;
                } else {
                    el.textContent = start;
                    requestAnimationFrame(update);
                }
            }
            update();
        }

        // Initialisation au chargement de la page
        document.addEventListener('DOMContentLoaded', function() {
            // Lancer les animations des compteurs
            setTimeout(() => {
                animateCounter('patientsCount', <?php echo $nbPatients; ?>, 2000);
                animateCounter('usersCount', <?php echo $nbUsers; ?>, 2000);
            }, 500);

            // Animation des barres de progression
            setTimeout(() => {
                document.querySelectorAll('.progress-bar').forEach(bar => {
                    const width = bar.style.width;
                    bar.style.width = '0%';
                    setTimeout(() => {
                        bar.style.width = width;
                    }, 100);
                });
            }, 1000);
        });
    </script>
</body>
</html>