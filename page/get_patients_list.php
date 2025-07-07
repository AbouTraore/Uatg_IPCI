<?php
// Script pour récupérer la liste des patients
require_once("connexion.php");

try {
    // Vérifier si la colonne date_creation existe
    $stmt = $pdo->query("SHOW COLUMNS FROM patient LIKE 'date_creation'");
    $hasDateCreation = $stmt->rowCount() > 0;
    
    if ($hasDateCreation) {
        // Récupérer uniquement les patients d'aujourd'hui, triés par date de création décroissante
        $query = "SELECT Numero_urap, Nom_patient, Prenom_patient, Age, Date_naissance, date_creation 
                  FROM patient 
                  WHERE DATE(date_creation) = CURDATE() 
                  ORDER BY date_creation DESC";
        
        $stmt = $pdo->query($query);
        $patients = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Log pour debug (à retirer en production)
        error_log("Date actuelle: " . date('Y-m-d'));
        error_log("Patients trouvés: " . count($patients));
        
    } else {
        // Si pas de date_creation, récupérer les patients d'aujourd'hui basé sur la date du système
        // ou les 20 derniers patients si on ne peut pas déterminer la date
        $query = "SELECT Numero_urap, Nom_patient, Prenom_patient, Age, Date_naissance 
                  FROM patient 
                  ORDER BY Numero_urap DESC 
                  LIMIT 20";
        
        $stmt = $pdo->query($query);
        $patients = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        error_log("Pas de colonne date_creation - récupération de tous les patients");
    }
    
    // Retourner les données au format JSON
    header('Content-Type: application/json');
    echo json_encode($patients);
    
} catch (PDOException $e) {
    error_log("Erreur dans get_patients_list.php: " . $e->getMessage());
    header('Content-Type: application/json');
    echo json_encode(['error' => $e->getMessage()]);
}
?> 