<?php
header('Content-Type: application/json');

// Connexion à la base de données
require_once("identifier.php");
require_once("connexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['urap'])) {
    try {
        $N_Urap = $_POST['urap'];
        
        // Récupérer les données du patient
        $sql = "SELECT * FROM patient WHERE N_Urap = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$N_Urap]);
        $patient = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($patient) {
            // Préparer le lieu de résidence complet
            $lieu_residence = ($patient['reside'] === "Abidjan") ? "Abidjan" : $patient['Precise'];
            
            echo json_encode([
                'success' => true,
                'data' => [
                    'nom' => $patient['Nom'],
                    'prenom' => $patient['Prenom'],
                    'sexe' => $patient['SexeP'],
                    'age' => $patient['Age'],
                    'contact' => $patient['contact'],
                    'datenaiss' => $patient['datenaiss'],
                    'lieu_residence' => $lieu_residence,
                    'type_logement' => $patient['Type_log'],
                    'niveau_etude' => $patient['NiveauE'],
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