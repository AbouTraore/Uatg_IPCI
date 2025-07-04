<?php
require_once("identifier.php");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .modern-sidebar {
            width: 280px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-right: 1px solid var(--gray-200);
            box-shadow: var(--shadow-xl);
            z-index: 1000;
            overflow-y: auto;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            animation: slideInLeft 0.6s ease-out;
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-100%);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 20px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: white;
            text-decoration: none;
            position: relative;
            overflow: hidden;
        }

        .sidebar-brand::before {
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

        .sidebar-brand-icon {
            font-size: 2rem;
            margin-right: 12px;
            position: relative;
            z-index: 1;
            transform: rotate(-15deg);
            animation: iconFloat 3s ease-in-out infinite;
        }

        @keyframes iconFloat {
            0%, 100% { transform: rotate(-15deg) translateY(0px); }
            50% { transform: rotate(-15deg) translateY(-5px); }
        }

        .sidebar-brand-text {
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: -0.025em;
            position: relative;
            z-index: 1;
        }

        .sidebar-brand:hover {
            text-decoration: none;
            color: white;
        }

        .sidebar-divider {
            height: 1px;
            background: linear-gradient(to right, transparent, var(--gray-300), transparent);
            margin: 16px 20px;
        }

        .sidebar-heading {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--gray-500);
            text-transform: uppercase;
            letter-spacing: 0.1em;
            padding: 16px 20px 8px;
            margin-top: 8px;
        }

        .nav-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .nav-item {
            margin: 4px 12px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            color: var(--gray-700);
            text-decoration: none;
            border-radius: 12px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            font-weight: 500;
        }

        .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 0;
            height: 100%;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            transition: width 0.3s ease;
            z-index: -1;
        }

        .nav-link:hover {
            color: white;
            text-decoration: none;
            transform: translateX(4px);
            box-shadow: var(--shadow-md);
        }

        .nav-link:hover::before {
            width: 100%;
        }

        .nav-link.active {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: white;
            box-shadow: var(--shadow);
        }

        .nav-link i {
            margin-right: 12px;
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
            transition: transform 0.3s ease;
        }

        .nav-link:hover i {
            transform: scale(1.1);
        }

        .nav-link span {
            position: relative;
            z-index: 1;
        }

        /* Couleurs spécifiques pour les icônes */
        .text-warning { color: var(--warning) !important; }
        .text-danger { color: var(--danger) !important; }
        .text-pink { color: #ec4899 !important; }
        .text-success { color: var(--success) !important; }
        .text-info { color: var(--primary) !important; }

        .nav-link:hover .text-warning,
        .nav-link:hover .text-danger,
        .nav-link:hover .text-pink,
        .nav-link:hover .text-success,
        .nav-link:hover .text-info {
            color: white !important;
        }

        /* Menu déroulant */
        .nav-link.collapsed::after {
            content: '\f107';
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
            margin-left: auto;
            transition: transform 0.3s ease;
        }

        .nav-link.collapsed[aria-expanded="true"]::after {
            transform: rotate(180deg);
        }

        .collapse {
            margin-top: 8px;
            margin-left: 12px;
            border-left: 2px solid var(--gray-200);
            padding-left: 12px;
        }

        .collapse-header {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--gray-500);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 8px 0;
            margin-bottom: 4px;
        }

        .collapse-item {
            display: block;
            padding: 8px 12px;
            color: var(--gray-600);
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.2s ease;
            font-size: 0.9rem;
            margin-bottom: 2px;
        }

        .collapse-item:hover {
            background: var(--gray-100);
            color: var(--primary);
            text-decoration: none;
            transform: translateX(4px);
        }

        /* Animation d'entrée des éléments */
        .nav-item {
            opacity: 0;
            transform: translateX(-20px);
            animation: slideInItem 0.4s ease-out forwards;
        }

        .nav-item:nth-child(1) { animation-delay: 0.1s; }
        .nav-item:nth-child(2) { animation-delay: 0.2s; }
        .nav-item:nth-child(3) { animation-delay: 0.3s; }
        .nav-item:nth-child(4) { animation-delay: 0.4s; }
        .nav-item:nth-child(5) { animation-delay: 0.5s; }
        .nav-item:nth-child(6) { animation-delay: 0.6s; }
        .nav-item:nth-child(7) { animation-delay: 0.7s; }

        @keyframes slideInItem {
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .modern-sidebar {
                width: 100%;
                height: auto;
                position: relative;
                border-right: none;
                border-bottom: 1px solid var(--gray-200);
            }
        }

        /* Scrollbar personnalisée */
        .modern-sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .modern-sidebar::-webkit-scrollbar-track {
            background: var(--gray-100);
        }

        .modern-sidebar::-webkit-scrollbar-thumb {
            background: var(--gray-300);
            border-radius: 3px;
        }

        .modern-sidebar::-webkit-scrollbar-thumb:hover {
            background: var(--gray-400);
        }
    </style>
</head>
<body>
    <!-- Sidebar moderne -->
    <div class="modern-sidebar">
        <!-- Brand -->
        <a class="sidebar-brand" href="acceuil.php">
            <div class="sidebar-brand-icon">
                <i class="fas fa-user-md"></i>
            </div>
            <div class="sidebar-brand-text">UATG</div>
        </a>

        <!-- Divider -->
        <div class="sidebar-divider"></div>

        <!-- Navigation -->
        <ul class="nav-list">
            <!-- Dashboard -->
            <li class="nav-item">
                <a class="nav-link active" href="acceuil.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Tableau de bord</span>
                </a>
            </li>
        </ul>

        <!-- Divider -->
        <div class="sidebar-divider"></div>

        <!-- Heading -->
        <div class="sidebar-heading">Consultation</div>

        <ul class="nav-list">
            <!-- Nouveau dossier -->
            <li class="nav-item">
                <a class="nav-link" href="nouveau_dossier.php">
                    <i class="fas fa-folder text-warning"></i>
                    <span>Nouveau dossier</span>
                </a>
            </li>

            <!-- Antécédents IST -->
            <li class="nav-item">
                <a class="nav-link" href="antecedent.php">
                    <i class="fas fa-clipboard-check text-danger"></i>
                    <span>Antécédents IST</span>
                </a>
            </li>

            <!-- Habitudes sexuelles -->
            <li class="nav-item">
                <a class="nav-link" href="habitude_sexuelle.php">
                    <i class="fas fa-heart text-pink"></i>
                    <span>Habitudes sexuelles</span>
                </a>
            </li>

            <!-- Histoire de la maladie -->
            <li class="nav-item">
                <a class="nav-link" href="Histoire de la maladie.ph">
                    <i class="fas fa-file-medical-alt text-success"></i>
                    <span>Histoire de la maladie</span>
                </a>
            </li>
        </ul>

        <!-- Divider -->
        <div class="sidebar-divider"></div>

        <!-- Heading -->
        <div class="sidebar-heading">Gestion</div>

        <ul class="nav-list">
            <!-- Patients -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseForm" aria-expanded="false" aria-controls="collapseForm">
                    <i class="fas fa-users text-info"></i>
                    <span>Patients</span>
                </a>
                <div id="collapseForm" class="collapse">
                    <div class="collapse-header">Gestion des Patients</div>
                    <a class="collapse-item" href="ajouter_patient.php">
                        <i class="fas fa-user-plus"></i> Ajouter un Patient
                    </a>
                    <a class="collapse-item" href="Liste_patient.php">
                        <i class="fas fa-list"></i> Liste des Patients
                    </a>
                </div>
            </li>

            <!-- Administrateur -->
            <?php if($_SESSION['user']['Type_user']=='ADMIN') { ?>
                <li class="nav-item">
                    <a class="nav-link" href="admin.php">
                        <i class="fas fa-fw fa-user text-warning"></i>
                        <span>Administrateur</span>
                    </a>
                </li>
            <?php } ?>
        </ul>
    </div>

    <script>
        // Gestion du menu déroulant
        document.addEventListener('DOMContentLoaded', function() {
            const collapseLinks = document.querySelectorAll('[data-toggle="collapse"]');
            
            collapseLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    const targetId = this.getAttribute('data-target');
                    const targetElement = document.querySelector(targetId);
                    const isExpanded = this.getAttribute('aria-expanded') === 'true';
                    
                    if (isExpanded) {
                        targetElement.style.height = '0px';
                        targetElement.style.opacity = '0';
                        this.setAttribute('aria-expanded', 'false');
                        setTimeout(() => {
                            targetElement.classList.remove('show');
                        }, 300);
                    } else {
                        targetElement.classList.add('show');
                        targetElement.style.height = 'auto';
                        const height = targetElement.scrollHeight;
                        targetElement.style.height = '0px';
                        targetElement.offsetHeight; // Force reflow
                        targetElement.style.height = height + 'px';
                        targetElement.style.opacity = '1';
                        this.setAttribute('aria-expanded', 'true');
                    }
                });
            });
            
            // Initialiser les styles du collapse
            const collapseElements = document.querySelectorAll('.collapse');
            collapseElements.forEach(element => {
                element.style.transition = 'height 0.3s ease, opacity 0.3s ease';
                element.style.overflow = 'hidden';
                element.style.height = '0px';
                element.style.opacity = '0';
            });
        });

        // Gestion de la page active
        const currentPage = window.location.pathname.split('/').pop();
        const navLinks = document.querySelectorAll('.nav-link');
        
        navLinks.forEach(link => {
            link.classList.remove('active');
            const href = link.getAttribute('href');
            if (href && href.includes(currentPage)) {
                link.classList.add('active');
            }
        });
    </script>
</body>
</html>