<?php 
require_once("identifier.php");
require_once("connexion.php");

$name = isset($_GET['name']) ? $_GET['name'] : "";
$size = isset($_GET['size']) ? intval($_GET['size']) : 3;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $size;

// Préparer la requête de comptage filtrée
$reqcount = "SELECT COUNT(*) as countP FROM patient WHERE Nom_patient LIKE :name";
$stmtCount = $pdo->prepare($reqcount);
$stmtCount->execute([':name' => "%$name%"]);
$tabcount = $stmtCount->fetch();
$nbrliste = $tabcount['countP'];
$reste = $nbrliste % $size;

if ($reste === 0) {
    $nbrPage = $nbrliste / $size;
} else {
    $nbrPage = floor($nbrliste / $size) + 1;
}

// Préparer la requête de liste paginée et filtrée
$reqliste = "SELECT * FROM patient WHERE Nom_patient LIKE :name ORDER BY Nom_patient ASC LIMIT $size OFFSET $offset";
$stmtListe = $pdo->prepare($reqliste);
$stmtListe->bindValue(':name', "%$name%", PDO::PARAM_STR);
$stmtListe->execute();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Liste des Patients - UATG</title>
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

        .dark {
            --gray-50: #111827;
            --gray-100: #1f2937;
            --gray-200: #374151;
            --gray-300: #4b5563;
            --gray-400: #6b7280;
            --gray-500: #9ca3af;
            --gray-600: #d1d5db;
            --gray-700: #e5e7eb;
            --gray-800: #f3f4f6;
            --gray-900: #f9fafb;
            --secondary: #1f2937;
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
            transition: all 0.3s ease;
        }

        .container {
            max-width: 1400px;
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
            margin-bottom: 8px;
        }

        .header p {
            font-size: 1.1rem;
            opacity: 0.9;
            position: relative;
            z-index: 1;
        }

        .main-content {
            padding: 32px;
            background: white;
        }

        .dark .main-content {
            background: var(--gray-100);
        }

        .search-section {
            background: var(--gray-50);
            border-radius: 20px;
            padding: 24px;
            margin-bottom: 32px;
            border: 1px solid var(--gray-200);
        }

        .search-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 16px;
        }

        .search-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--gray-800);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .patient-count {
            background: linear-gradient(135deg, var(--accent) 0%, #059669 100%);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 600;
            box-shadow: var(--shadow);
        }

        .search-form {
            display: flex;
            gap: 16px;
            align-items: center;
            flex-wrap: wrap;
        }

        .search-input {
            flex: 1;
            min-width: 300px;
            padding: 12px 16px;
            border: 2px solid var(--gray-200);
            border-radius: 12px;
            font-size: 14px;
            transition: all 0.2s ease;
            background: white;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(0, 71, 171, 0.1);
        }

        .btn {
            padding: 12px 20px;
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
            justify-content: center;
            white-space: nowrap;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: white;
            box-shadow: var(--shadow);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
            color: white;
            text-decoration: none;
        }

        .btn-success {
            background: linear-gradient(135deg, var(--accent) 0%, #059669 100%);
            color: white;
            box-shadow: var(--shadow);
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
            color: white;
            text-decoration: none;
        }

        .table-container {
            background: white;
            border-radius: 20px;
            box-shadow: var(--shadow-lg);
            overflow: hidden;
            border: 1px solid var(--gray-200);
        }

        .dark .table-container {
            background: var(--gray-100);
            border-color: var(--gray-300);
        }

        .table-header {
            background: linear-gradient(135deg, var(--gray-50) 0%, var(--gray-100) 100%);
            padding: 20px 24px;
            border-bottom: 1px solid var(--gray-200);
        }

        .table-header h3 {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--gray-800);
            margin: 0;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .modern-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        .modern-table th {
            background: var(--gray-50);
            padding: 16px 12px;
            text-align: left;
            font-weight: 600;
            color: var(--gray-700);
            border-bottom: 2px solid var(--gray-200);
            white-space: nowrap;
        }

        .modern-table td {
            padding: 16px 12px;
            border-bottom: 1px solid var(--gray-200);
            vertical-align: middle;
        }

        .modern-table tbody tr {
            transition: all 0.2s ease;
        }

        .modern-table tbody tr:hover {
            background: var(--gray-50);
            transform: scale(1.002);
        }

        .action-buttons {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .action-btn {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.2s ease;
            border: 1px solid transparent;
        }

        .action-btn.edit {
            background: rgba(16, 185, 129, 0.1);
            color: var(--accent);
            border-color: rgba(16, 185, 129, 0.2);
        }

        .action-btn.edit:hover {
            background: var(--accent);
            color: white;
            transform: scale(1.1);
        }

        .action-btn.delete {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger);
            border-color: rgba(239, 68, 68, 0.2);
        }

        .action-btn.delete:hover {
            background: var(--danger);
            color: white;
            transform: scale(1.1);
        }

        .pagination-container {
            padding: 24px;
            display: flex;
            justify-content: center;
            border-top: 1px solid var(--gray-200);
        }

        .pagination {
            display: flex;
            gap: 8px;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .page-item {
            margin: 0;
        }

        .page-link {
            padding: 10px 16px;
            border: 2px solid var(--gray-200);
            border-radius: 10px;
            color: var(--gray-600);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s ease;
            display: block;
        }

        .page-link:hover {
            border-color: var(--primary);
            color: var(--primary);
            transform: translateY(-1px);
            text-decoration: none;
        }

        .page-item.active .page-link {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            border-color: var(--primary);
            color: white;
            box-shadow: var(--shadow);
        }

        .stats-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .stats-badge.male {
            background: rgba(59, 130, 246, 0.1);
            color: #2563eb;
        }

        .stats-badge.female {
            background: rgba(236, 72, 153, 0.1);
            color: #be185d;
        }

        .empty-state {
            text-align: center;
            padding: 48px 24px;
            color: var(--gray-500);
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 16px;
            opacity: 0.5;
        }

        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid var(--gray-300);
            border-radius: 50%;
            border-top-color: var(--primary);
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
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

            .main-content {
                padding: 20px;
            }

            .search-form {
                flex-direction: column;
                align-items: stretch;
            }

            .search-input {
                min-width: auto;
            }

            .modern-table {
                font-size: 12px;
            }

            .modern-table th,
            .modern-table td {
                padding: 12px 8px;
            }

            .action-buttons {
                flex-direction: column;
                gap: 4px;
            }
        }

        /* Dark mode support */
        @media (prefers-color-scheme: dark) {
            .dark body {
                background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
            }

            .dark .container {
                background: rgba(31, 41, 55, 0.95);
            }

            .dark .search-section,
            .dark .table-container {
                background: var(--gray-200);
                border-color: var(--gray-300);
            }

            .dark .modern-table th {
                background: var(--gray-300);
                color: var(--gray-700);
            }

            .dark .modern-table tbody tr:hover {
                background: var(--gray-300);
            }

            .dark .search-input {
                background: var(--gray-100);
                border-color: var(--gray-300);
                color: var(--gray-800);
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
            <h1><i class="fas fa-users"></i> Liste des Patients</h1>
            <p>Gestion et consultation des dossiers patients</p>
        </div>

        <div class="main-content">
            <div class="search-section">
                <div class="search-header">
                    <h2 class="search-title">
                        <i class="fas fa-search"></i>
                        Rechercher un patient
                    </h2>
                    <div class="patient-count">
                        <i class="fas fa-user-check"></i>
                        <?php echo $nbrliste; ?> patients enregistrés
                    </div>
                </div>

                <form method="get" action="Liste_patient.php" class="search-form">
                    <input type="text" 
                           name="name" 
                           placeholder="Rechercher par nom..." 
                           class="search-input" 
                           value="<?php echo htmlspecialchars($name); ?>">
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i>
                        Chercher
                    </button>
                    
                    <a href="nouveau_dossier.php" class="btn btn-success">
                        <i class="fas fa-user-plus"></i>
                        Ajouter un patient
                    </a>
                </form>
            </div>

            <div class="table-container">
                <div class="table-header">
                    <h3>
                        <i class="fas fa-table"></i>
                        Résultats de la recherche
                        <?php if($name): ?>
                            pour "<?php echo htmlspecialchars($name); ?>"
                        <?php endif; ?>
                    </h3>
                </div>

                <div class="table-responsive">
                    <?php if($nbrliste > 0): ?>
                        <table class="modern-table">
                            <thead>
                                <tr>
                                    <th>N° URAP</th>
                                    <th>Nom</th>
                                    <th>Prénom</th>
                                    <th>Âge</th>
                                    <th>Sexe</th>
                                    <th>Contact</th>
                                    <th>Résidence</th>
                                    <th>Précision</th>
                                    <th>Profession</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($patient = $stmtListe->fetch()): ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo htmlspecialchars($patient["Numero_urap"]); ?></strong>
                                        </td>
                                        <td><?php echo htmlspecialchars($patient["Nom_patient"]); ?></td>
                                        <td><?php echo htmlspecialchars($patient["Prenom_patient"]); ?></td>
                                        <td><?php echo htmlspecialchars($patient["Age"]); ?> ans</td>
                                        <td>
                                            <span class="stats-badge <?php echo strtolower($patient["Sexe_patient"]) === 'masculin' ? 'male' : 'female'; ?>">
                                                <i class="fas fa-<?php echo strtolower($patient["Sexe_patient"]) === 'masculin' ? 'mars' : 'venus'; ?>"></i>
                                                <?php echo htmlspecialchars($patient["Sexe_patient"]); ?>
                                            </span>
                                        </td>
                                        <td><?php echo htmlspecialchars($patient["Contact_patient"]); ?></td>
                                        <td><?php echo htmlspecialchars($patient["Lieu_résidence"]); ?></td>
                                        <td><?php echo htmlspecialchars($patient["Precise"]); ?></td>
                                        <td><?php echo htmlspecialchars($patient["Profession"]); ?></td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="modifpatient.php?idU=<?php echo $patient["Numero_urap"]; ?>" 
                                                   class="action-btn edit"
                                                   title="Modifier"
                                                   onclick="return confirm('Êtes-vous sûr de vouloir modifier ce patient ?')">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="supprimpatient.php?idU=<?php echo $patient["Numero_urap"]; ?>" 
                                                   class="action-btn delete"
                                                   title="Supprimer"
                                                   onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce patient ? Cette action est irréversible.')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="empty-state">
                            <i class="fas fa-search"></i>
                            <h3>Aucun patient trouvé</h3>
                            <p>
                                <?php if($name): ?>
                                    Aucun patient ne correspond à votre recherche "<?php echo htmlspecialchars($name); ?>".
                                <?php else: ?>
                                    Aucun patient n'est enregistré dans la base de données.
                                <?php endif; ?>
                            </p>
                            <a href="nouveau_dossier.php" class="btn btn-success" style="margin-top: 16px;">
                                <i class="fas fa-user-plus"></i>
                                Ajouter le premier patient
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if($nbrPage > 1): ?>
                    <div class="pagination-container">
                        <nav aria-label="Navigation des pages">
                            <ul class="pagination">
                                <?php for($i = 1; $i <= $nbrPage; $i++): ?>
                                    <li class="page-item <?php if($i == $page) echo 'active'; ?>">
                                        <a class="page-link" 
                                           href="Liste_patient.php?page=<?php echo $i; ?>&name=<?php echo urlencode($name); ?>">
                                            <?php echo $i; ?>
                                        </a>
                                    </li>
                                <?php endfor; ?>
                            </ul>
                        </nav>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Bouton retour global en bas de page -->
    <div style="width:100%;display:flex;justify-content:center;margin:32px 0 0 0;">
        <a href="acceuil.php" class="btn-retour-global">
            <i class="fas fa-arrow-left"></i> Retour à l'accueil
        </a>
    </div>

    <script>
        // Support du mode sombre
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            document.documentElement.classList.add('dark');
        }
        
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', event => {
            if (event.matches) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        });

        // Animation d'apparition des lignes du tableau
        document.addEventListener('DOMContentLoaded', function() {
            const rows = document.querySelectorAll('.modern-table tbody tr');
            rows.forEach((row, index) => {
                row.style.opacity = '0';
                row.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    row.style.transition = 'all 0.3s ease';
                    row.style.opacity = '1';
                    row.style.transform = 'translateY(0)';
                }, index * 50);
            });

            // Auto-focus sur le champ de recherche
            const searchInput = document.querySelector('.search-input');
            if (searchInput && !searchInput.value) {
                searchInput.focus();
            }
        });

        // Amélioration de l'expérience utilisateur pour les confirmations
        document.querySelectorAll('.action-btn.delete').forEach(btn => {
            btn.addEventListener('click', function(e) {
                const patientName = this.closest('tr').children[1].textContent + ' ' + 
                                  this.closest('tr').children[2].textContent;
                
                if (!confirm(`Êtes-vous sûr de vouloir supprimer le patient ${patientName} ?\n\nCette action est irréversible.`)) {
                    e.preventDefault();
                }
            });
        });

        // Indicateur de chargement pour la recherche
        document.querySelector('.search-form').addEventListener('submit', function() {
            const button = this.querySelector('button[type="submit"]');
            const originalContent = button.innerHTML;
            button.innerHTML = '<div class="loading-spinner"></div> Recherche...';
            button.disabled = true;
            
            // Restaurer le bouton si la page ne se recharge pas (pour les erreurs)
            setTimeout(() => {
                button.innerHTML = originalContent;
                button.disabled = false;
            }, 5000);
        });
    </script>
</body>
</html>