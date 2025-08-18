<?php
// Configuration de la base de données
$host = 'localhost';
$dbname = 'uatg';
$username = 'root'; // Ajustez selon votre configuration
$password = '';     // Ajustez selon votre configuration

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Variables pour la recherche
$patient = null;
$examens = array();
$message_erreur = '';
$numero_urap = '';
$prescripteur = null;

// Fonction pour récupérer les informations du patient
function getPatientInfo($pdo, $numero_urap) {
    $stmt = $pdo->prepare("SELECT * FROM patient WHERE Numero_urap = ?");
    $stmt->execute([$numero_urap]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Fonction pour récupérer les examens ECSU
function getExamensECSU($pdo, $numero_urap) {
    $stmt = $pdo->prepare("SELECT * FROM ecsu WHERE numero_identification = ?");
    $stmt->execute([$numero_urap]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fonction pour récupérer les examens ECS
function getExamensECS($pdo, $numero_urap) {
    $stmt = $pdo->prepare("SELECT * FROM ecs WHERE numero_identification = ?");
    $stmt->execute([$numero_urap]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fonction pour récupérer les examens cytologiques vaginaux
function getExamensCytoVag($pdo, $numero_urap) {
    $stmt = $pdo->prepare("SELECT * FROM exa_cyto_sec_vag WHERE numero_identification = ?");
    $stmt->execute([$numero_urap]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fonction pour récupérer les échantillons mâles
function getEchantillonsMale($pdo, $numero_urap) {
    $stmt = $pdo->prepare("SELECT * FROM echantillon_male WHERE Numero_urap = ?");
    $stmt->execute([$numero_urap]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fonction pour récupérer les échantillons femelles
function getEchantillonsFemelle($pdo, $numero_urap) {
    $stmt = $pdo->prepare("SELECT * FROM echantillon_femelle WHERE Numero_urap = ?");
    $stmt->execute([$numero_urap]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fonction pour récupérer le prescripteur
function getPrescripteur($pdo, $numero_urap) {
    $stmt = $pdo->prepare("
        SELECT p.Nom, p.Prenom 
        FROM prescriteur p 
        INNER JOIN visite v ON p.ID_prescripteur = v.ID_prescripteur 
        WHERE v.Numero_urap = ? 
        ORDER BY v.date_visite DESC 
        LIMIT 1
    ");
    $stmt->execute([$numero_urap]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Traitement de la recherche
if ($_POST && isset($_POST['numero_urap'])) {
    $numero_urap = trim($_POST['numero_urap']);
    
    if (!empty($numero_urap)) {
        $patient = getPatientInfo($pdo, $numero_urap);
        
        if ($patient) {
            // Récupérer tous les types d'examens
            $examens_ecsu = getExamensECSU($pdo, $numero_urap);
            $examens_ecs = getExamensECS($pdo, $numero_urap);
            $examens_cyto = getExamensCytoVag($pdo, $numero_urap);
            $echantillons_male = getEchantillonsMale($pdo, $numero_urap);
            $echantillons_femelle = getEchantillonsFemelle($pdo, $numero_urap);
            $prescripteur = getPrescripteur($pdo, $numero_urap);
            
            // Organiser tous les examens dans un tableau
            foreach ($examens_ecsu as $examen) {
                $examens[] = array(
                    'type' => 'ECSU (Examen Cytobactériologique des Urines)',
                    'medecin' => $examen['medecin'],
                    'icon' => 'fas fa-vial',
                    'color' => 'blue',
                    'data' => $examen
                );
            }
            
            foreach ($examens_ecs as $examen) {
                $examens[] = array(
                    'type' => 'ECS (Examen Cytologique du Sperme)',
                    'medecin' => $examen['medecin'],
                    'icon' => 'fas fa-microscope',
                    'color' => 'green',
                    'data' => $examen
                );
            }
            
            foreach ($examens_cyto as $examen) {
                $examens[] = array(
                    'type' => 'Examen Cytologique des Sécrétions Vaginales',
                    'medecin' => $examen['medecin'],
                    'icon' => 'fas fa-search',
                    'color' => 'purple',
                    'data' => $examen
                );
            }
            
            foreach ($echantillons_male as $echantillon) {
                $examens[] = array(
                    'type' => 'Échantillons Prélevés (Homme)',
                    'medecin' => $echantillon['technicien1'],
                    'icon' => 'fas fa-male',
                    'color' => 'indigo',
                    'data' => $echantillon
                );
            }
            
            foreach ($echantillons_femelle as $echantillon) {
                $examens[] = array(
                    'type' => 'Échantillons Prélevés (Femme)',
                    'medecin' => $echantillon['technicien'],
                    'icon' => 'fas fa-female',
                    'color' => 'pink',
                    'data' => $echantillon
                );
            }
        } else {
            $message_erreur = "Aucun patient trouvé avec ce numéro URAP.";
        }
    } else {
        $message_erreur = "Veuillez saisir un numéro URAP.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Système IPCI - Résultats d'Examens</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .fade-in {
            animation: fadeIn 0.8s ease-out;
        }
        
        .slide-in {
            animation: slideIn 0.6s ease-out;
        }
        
        .pulse-custom {
            animation: pulseCustom 2s infinite;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes slideIn {
            from { opacity: 0; transform: translateX(-30px); }
            to { opacity: 1; transform: translateX(0); }
        }
        
        @keyframes pulseCustom {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 120px;
            font-weight: bold;
            color: rgba(93, 92, 222, 0.03);
            z-index: -1;
            pointer-events: none;
            user-select: none;
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .exam-card {
            transition: all 0.3s ease;
            transform: translateY(0);
        }
        
        .exam-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        
        .search-container {
            position: relative;
            overflow: hidden;
        }
        
        .search-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            animation: shimmer 2s infinite;
        }
        
        @keyframes shimmer {
            0% { left: -100%; }
            100% { left: 100%; }
        }
        
        .status-badge {
            position: relative;
            overflow: hidden;
        }
        
        .status-badge::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            animation: badge-shine 3s infinite;
        }
        
        @keyframes badge-shine {
            0% { left: -100%; }
            100% { left: 100%; }
        }
        
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; }
            .glass-effect { background: white !important; backdrop-filter: none !important; }
            .gradient-bg { background: white !important; }
        }
        
        .dark .glass-effect {
            background: rgba(30, 30, 30, 0.95);
            color: #fff;
        }

        .exam-enter {
            animation: examEnter 0.6s ease-out forwards;
        }

        @keyframes examEnter {
            from { 
                opacity: 0; 
                transform: translateY(30px) scale(0.95); 
            }
            to { 
                opacity: 1; 
                transform: translateY(0) scale(1); 
            }
        }
    </style>
</head>
<body class="min-h-screen gradient-bg">
    <!-- Filigrane IPCI -->
    <div class="watermark">IPCI</div>
    
    <!-- Header -->
    <header class="bg-white/10 backdrop-blur-md border-b border-white/20 sticky top-0 z-50">
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center">
                        <i class="fas fa-flask text-purple-600 text-lg"></i>
                    </div>
                    <div>
                        <h1 class="text-white font-bold text-xl">IPCI</h1>
                        <p class="text-white/80 text-sm">Institut Pasteur de Côte d'Ivoire</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="status-badge bg-green-500 text-white px-3 py-1 rounded-full text-sm font-medium">
                        <i class="fas fa-circle text-xs mr-1"></i>
                        Système en ligne
                    </div>
                    <div class="text-white/80 text-sm">
                        <i class="fas fa-calendar mr-1"></i>
                        <?php echo date('d/m/Y H:i'); ?>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container mx-auto p-4 max-w-6xl">
        <!-- Section de recherche -->
        <div class="search-container glass-effect rounded-2xl shadow-2xl p-8 mb-8 no-print fade-in">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-800 dark:text-white mb-2">
                    <i class="fas fa-search mr-3 text-purple-600"></i>
                    Recherche de Patient
                </h2>
                <p class="text-gray-600 dark:text-gray-300">Entrez le numéro URAP pour accéder au dossier médical</p>
            </div>
            
            <form method="POST" class="max-w-md mx-auto">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-id-card text-gray-400"></i>
                    </div>
                    <input 
                        type="number" 
                        name="numero_urap" 
                        value="<?php echo htmlspecialchars($numero_urap); ?>"
                        class="w-full pl-10 pr-4 py-4 text-lg border-2 border-gray-200 dark:border-gray-600 rounded-xl focus:outline-none focus:ring-4 focus:ring-purple-500/30 focus:border-purple-500 transition-all duration-300 bg-white/50 dark:bg-gray-800/50"
                        placeholder="Numéro URAP..."
                        required
                    >
                </div>
                <button 
                    type="submit"
                    class="w-full mt-6 px-8 py-4 bg-gradient-to-r from-purple-600 to-blue-600 text-white font-semibold rounded-xl hover:from-purple-700 hover:to-blue-700 focus:outline-none focus:ring-4 focus:ring-purple-500/30 transform hover:scale-105 transition-all duration-300 shadow-lg"
                >
                    <i class="fas fa-search mr-2"></i>
                    Rechercher Patient
                </button>
            </form>
        </div>

        <!-- Message d'erreur -->
        <?php if (!empty($message_erreur)): ?>
        <div class="bg-red-500/90 backdrop-blur-md text-white px-6 py-4 rounded-xl mb-6 no-print slide-in">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle mr-3"></i>
                <span><?php echo htmlspecialchars($message_erreur); ?></span>
            </div>
        </div>
        <?php endif; ?>

        <!-- Fiche de résultat -->
        <?php if ($patient): ?>
        <div class="glass-effect rounded-2xl shadow-2xl p-8 fade-in">
            <!-- En-tête -->
            <div class="text-center mb-10">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-r from-purple-600 to-blue-600 rounded-full mb-4 pulse-custom">
                    <i class="fas fa-file-medical-alt text-white text-2xl"></i>
                </div>
                <h1 class="text-4xl font-bold text-gray-800 dark:text-white mb-2">FICHE RÉSULTAT D'EXAMEN</h1>
                <h2 class="text-xl text-gray-600 dark:text-gray-300">Institut Pasteur de Côte d'Ivoire (IPCI)</h2>
                <div class="flex items-center justify-center mt-4 space-x-4">
                    <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                        <i class="fas fa-calendar mr-2"></i>
                        <span>Date d'édition: <?php echo date('d/m/Y'); ?></span>
                    </div>
                    <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                        <i class="fas fa-clock mr-2"></i>
                        <span><?php echo date('H:i'); ?></span>
                    </div>
                </div>
            </div>

            <!-- Informations patient -->
            <div class="mb-10">
                <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-6 flex items-center">
                    <i class="fas fa-user-circle mr-3 text-purple-600"></i>
                    INFORMATIONS PATIENT
                </h3>
                <div class="bg-gradient-to-r from-purple-50 to-blue-50 dark:from-gray-800 dark:to-gray-700 rounded-xl p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <i class="fas fa-id-badge mr-3 text-purple-600"></i>
                                <span class="font-semibold w-32">Numéro URAP:</span>
                                <span class="text-purple-600 font-bold"><?php echo htmlspecialchars($patient['Numero_urap']); ?></span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-user mr-3 text-blue-600"></i>
                                <span class="font-semibold w-32">Nom:</span>
                                <span><?php echo htmlspecialchars($patient['Nom_patient']); ?></span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-user mr-3 text-green-600"></i>
                                <span class="font-semibold w-32">Prénom:</span>
                                <span><?php echo htmlspecialchars($patient['Prenom_patient']); ?></span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-birthday-cake mr-3 text-orange-600"></i>
                                <span class="font-semibold w-32">Âge:</span>
                                <span><?php echo htmlspecialchars($patient['Age']); ?> ans</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-venus-mars mr-3 text-pink-600"></i>
                                <span class="font-semibold w-32">Sexe:</span>
                                <span><?php echo htmlspecialchars($patient['Sexe_patient']); ?></span>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <i class="fas fa-calendar-alt mr-3 text-indigo-600"></i>
                                <span class="font-semibold w-32">Naissance:</span>
                                <span><?php echo date('d/m/Y', strtotime($patient['Date_naissance'])); ?></span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-phone mr-3 text-teal-600"></i>
                                <span class="font-semibold w-32">Contact:</span>
                                <span><?php echo htmlspecialchars($patient['Contact_patient']); ?></span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-heart mr-3 text-red-600"></i>
                                <span class="font-semibold w-32">Situation:</span>
                                <span><?php echo htmlspecialchars($patient['Situation_matrimoniale']); ?></span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-map-marker-alt mr-3 text-gray-600"></i>
                                <span class="font-semibold w-32">Résidence:</span>
                                <span><?php echo htmlspecialchars($patient['Lieu_résidence']); ?>
                                <?php if (!empty($patient['Precise'])): ?>
                                    - <?php echo htmlspecialchars($patient['Precise']); ?>
                                <?php endif; ?>
                                </span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-briefcase mr-3 text-yellow-600"></i>
                                <span class="font-semibold w-32">Profession:</span>
                                <span><?php echo htmlspecialchars($patient['Profession']); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Résultats d'examens -->
            <div class="mb-10">
                <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-6 flex items-center">
                    <i class="fas fa-microscope mr-3 text-purple-600"></i>
                    RÉSULTATS D'EXAMENS
                </h3>
                <div class="space-y-6">
                    <?php if (!empty($examens)): ?>
                        <?php foreach ($examens as $index => $examen): ?>
                        <div class="exam-card bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg border-l-4 border-<?php echo $examen['color']; ?>-500 exam-enter" style="animation-delay: <?php echo $index * 0.2; ?>s">
                            <h4 class="font-bold text-lg text-gray-800 dark:text-white mb-3 flex items-center">
                                <i class="<?php echo $examen['icon']; ?> mr-3 text-<?php echo $examen['color']; ?>-600"></i>
                                <?php echo htmlspecialchars($examen['type']); ?>
                            </h4>
                            <p class="text-sm text-gray-600 dark:text-gray-300 mb-4 flex items-center">
                                <i class="fas fa-user-md mr-2"></i>
                                Médecin/Technicien: <span class="font-semibold ml-1"><?php echo htmlspecialchars($examen['medecin']); ?></span>
                            </p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                                <?php foreach ($examen['data'] as $key => $value): ?>
                                    <?php if (!in_array($key, ['numero_identification', 'Numero_urap', 'medecin', 'nom', 'prenom', 'age']) && !empty($value)): ?>
                                    <div class="flex items-center">
                                        <span class="font-medium w-40 text-gray-700 dark:text-gray-300">
                                            <?php echo htmlspecialchars(ucwords(str_replace('_', ' ', $key))); ?>:
                                        </span>
                                        <span class="text-gray-800 dark:text-white font-medium"><?php echo htmlspecialchars($value); ?></span>
                                    </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                            <i class="fas fa-microscope text-4xl mb-4"></i>
                            <p class="text-lg">Aucun examen trouvé pour ce patient</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Section Conclusion -->
            <div class="mb-10">
                <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-6 flex items-center">
                    <i class="fas fa-stethoscope mr-3 text-purple-600"></i>
                    CONCLUSION MÉDICALE
                </h3>
                <div class="bg-gradient-to-r from-blue-50 to-purple-50 dark:from-gray-800 dark:to-gray-700 rounded-xl p-6">
                    <textarea 
                        id="conclusion"
                        class="w-full h-32 p-4 text-base border-2 border-gray-200 dark:border-gray-600 rounded-lg bg-white/70 dark:bg-gray-800/70 focus:outline-none focus:ring-4 focus:ring-purple-500/30 focus:border-purple-500 resize-none transition-all duration-300"
                        placeholder="Le médecin peut saisir ici sa conclusion et ses recommandations..."
                    ></textarea>
                    <div class="mt-6 flex justify-between items-center">
                        <div class="text-sm text-gray-600 dark:text-gray-300">
                            <p>Médecin prescripteur: 
                                <span class="font-semibold">
                                    <?php 
                                    if (isset($prescripteur) && $prescripteur) {
                                        echo htmlspecialchars($prescripteur['Nom'] . ' ' . $prescripteur['Prenom']);
                                    } else {
                                        echo "Non renseigné";
                                    }
                                    ?>
                                </span>
                            </p>
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-300">
                            <p>Signature: ________</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center no-print">
                <button 
                    onclick="imprimerFiche()"
                    class="px-8 py-4 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold rounded-xl hover:from-green-700 hover:to-emerald-700 focus:outline-none focus:ring-4 focus:ring-green-500/30 transform hover:scale-105 transition-all duration-300 shadow-lg"
                >
                    <i class="fas fa-print mr-2"></i>
                    Imprimer la Fiche
                </button>
                <button 
                    onclick="exportPDF()"
                    class="px-8 py-4 bg-gradient-to-r from-orange-600 to-red-600 text-white font-semibold rounded-xl hover:from-orange-700 hover:to-red-700 focus:outline-none focus:ring-4 focus:ring-orange-500/30 transform hover:scale-105 transition-all duration-300 shadow-lg"
                >
                    <i class="fas fa-file-pdf mr-2"></i>
                    Exporter PDF
                </button>
                <button 
                    onclick="nouvelleRecherche()"
                    class="px-8 py-4 bg-gradient-to-r from-gray-600 to-gray-700 text-white font-semibold rounded-xl hover:from-gray-700 hover:to-gray-800 focus:outline-none focus:ring-4 focus:ring-gray-500/30 transform hover:scale-105 transition-all duration-300 shadow-lg"
                >
                    <i class="fas fa-search mr-2"></i>
                    Nouvelle Recherche
                </button>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <script>
        // Gestion du thème
        function initTheme() {
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
        }

        function imprimerFiche() {
            if (window.print) {
                window.print();
            } else {
                showCustomAlert('La fonction d\'impression n\'est pas supportée par votre navigateur.');
            }
        }

        function exportPDF() {
            showCustomAlert('Fonctionnalité d\'export PDF en cours de développement.');
        }

        function nouvelleRecherche() {
            // Rediriger vers la même page sans paramètres POST
            window.location.href = window.location.pathname;
        }

        function showCustomAlert(message) {
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 fade-in';
            modal.innerHTML = `
                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-2xl max-w-sm w-full mx-4 slide-in">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-info-circle text-blue-600 text-2xl"></i>
                        </div>
                        <p class="text-gray-700 dark:text-gray-300 mb-6">${message}</p>
                        <button 
                            class="px-6 py-3 bg-gradient-to-r from-purple-600 to-blue-600 text-white font-semibold rounded-xl hover:from-purple-700 hover:to-blue-700 transition-all duration-300"
                            onclick="this.closest('.fixed').remove()"
                        >
                            Compris
                        </button>
                    </div>
                </div>
            `;
            document.body.appendChild(modal);
        }

        // Initialisation
        initTheme();

        // Auto-focus sur le champ de recherche si pas de résultats
        <?php if (!$patient): ?>
        document.querySelector('input[name="numero_urap"]').focus();
        <?php endif; ?>

        // Animation des cartes d'examens au chargement
        <?php if ($patient && !empty($examens)): ?>
        document.addEventListener('DOMContentLoaded', function() {
            const examCards = document.querySelectorAll('.exam-card');
            examCards.forEach((card, index) => {
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 200);
            });
        });
        <?php endif; ?>
    </script>
</body>
</html>