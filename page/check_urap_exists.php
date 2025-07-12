<?php
// check_urap_exists.php
// Vérifie si un numéro URAP existe déjà dans la table histoire_maladie

require_once("connexion.php");

// Définir le type de contenu comme JSON
header('Content-Type: application/json');

// Vérifier si la requête est POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Méthode non autorisée']);
    exit;
}

// Récupérer le numéro URAP
$numero_urap = trim($_POST['numero_urap'] ?? '');

if (empty($numero_urap)) {
    echo json_encode(['error' => 'Numéro URAP manquant']);
    exit;
}

try {
    // Vérifier si le numéro URAP existe dans histoire_maladie
    $stmt = $pdo->prepare("SELECT hm.*, p.Nom_patient, p.Prenom_patient 
                           FROM histoire_maladie hm 
                           LEFT JOIN patient p ON hm.numero_urap = p.Numero_urap 
                           WHERE hm.numero_urap = ?");
    $stmt->execute([$numero_urap]);
    $existing_record = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($existing_record) {
        // Le numéro URAP existe déjà
        $patient_name = $existing_record['Nom_patient'] ? 
            $existing_record['Nom_patient'] . ' ' . $existing_record['Prenom_patient'] : 
            'Patient non trouvé';
        
        echo json_encode([
            'exists' => true,
            'patientInfo' => $patient_name,
            'date_creation' => $existing_record['date_creation'],
            'sexe_patient' => $existing_record['sexe_patient']
        ]);
    } else {
        // Le numéro URAP n'existe pas
        echo json_encode([
            'exists' => false
        ]);
    }
    
} catch (Exception $e) {
    echo json_encode([
        'error' => 'Erreur lors de la vérification : ' . $e->getMessage()
    ]);
}
?> 