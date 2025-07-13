<?php
header('Content-Type: application/json');
require_once("identifier.php");
require_once("connexion.php");

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['urap'])) {
    try {
        $numero_urap = $_GET['urap'];
        
        // Récupérer les données du patient
        $sql = "SELECT * FROM patient WHERE Numero_urap = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$numero_urap]);
        $patient = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($patient) {
            echo json_encode([
                'success' => true,
                'data' => [
                    'numero_urap' => $patient['Numero_urap'],
                    'nom' => $patient['Nom_patient'],
                    'prenom' => $patient['Prenom_patient'],
                    'age' => $patient['Age'],
                    'sexe' => $patient['Sexe_patient'],
                    'contact' => $patient['Contact_patient'],
                    'date_naissance' => $patient['Date_naissance'],
                    'adresse' => $patient['Adresse'] ?? '',
                    'situation_matrimoniale' => $patient['Situation_matrimoniale'],
                    'lieu_residence' => $patient['Lieu_résidence'],
                    'precise' => $patient['Precise'] ?? '',
                    'type_logement' => $patient['Type_logement'],
                    'niveau_etude' => $patient['Niveau_etude'],
                    'profession' => $patient['Profession']
                ]
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Aucun patient trouvé avec ce N° URAP'
            ]);
        }
        
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Erreur lors de la récupération des données : ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'N° URAP manquant'
    ]);
}
?> 