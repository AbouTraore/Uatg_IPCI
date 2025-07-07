<?php
require_once("identifier.php");
require_once("connexion.php");

// Données de test
$testData = [
    'N_Urap' => 'TEST' . date('YmdHis'),
    'Nom' => 'Test',
    'Prenom' => 'Patient',
    'Age' => '25',
    'SexeP' => 'Masculin',
    'datenaiss' => '1999-01-01',
    'contact' => '0123456789',
    'Adresse' => 'Test Adresse',
    'SituaM' => 'Célibataire',
    'reside' => 'Abidjan',
    'Precise' => '',
    'Type_log' => 'Studio',
    'NiveauE' => 'Secondaire',
    'Profession' => 'Etudiant'
];

echo "<h2>Test d'enregistrement d'un patient</h2>";

try {
    // Vérifier la structure de la table
    $stmt = $pdo->query("SHOW COLUMNS FROM patient LIKE 'Adresse'");
    $hasAdresse = $stmt->rowCount() > 0;
    
    $stmt = $pdo->query("SHOW COLUMNS FROM patient LIKE 'date_creation'");
    $hasDateCreation = $stmt->rowCount() > 0;
    
    echo "<p>Colonne Adresse : " . ($hasAdresse ? "✅ Présente" : "❌ Absente") . "</p>";
    echo "<p>Colonne date_creation : " . ($hasDateCreation ? "✅ Présente" : "❌ Absente") . "</p>";
    
    // Construire la requête selon la structure
    if ($hasAdresse && $hasDateCreation) {
        $req = "INSERT INTO patient (
            Numero_urap, Nom_patient, Prenom_patient, Age, Sexe_patient,
            Date_naissance, Contact_patient, Adresse, Situation_matrimoniale, 
            Lieu_résidence, Precise, Type_logement, Niveau_etude, Profession, date_creation
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

        $params = array(
            $testData['N_Urap'], $testData['Nom'], $testData['Prenom'], $testData['Age'], $testData['SexeP'],
            $testData['datenaiss'], $testData['contact'], $testData['Adresse'], $testData['SituaM'],
            $testData['reside'], $testData['Precise'], $testData['Type_log'], $testData['NiveauE'], $testData['Profession']
        );
    } else {
        $req = "INSERT INTO patient (
            Numero_urap, Nom_patient, Prenom_patient, Age, Sexe_patient,
            Date_naissance, Contact_patient, Situation_matrimoniale, 
            Lieu_résidence, Precise, Type_logement, Niveau_etude, Profession
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $params = array(
            $testData['N_Urap'], $testData['Nom'], $testData['Prenom'], $testData['Age'], $testData['SexeP'],
            $testData['datenaiss'], $testData['contact'], $testData['SituaM'],
            $testData['reside'], $testData['Precise'], $testData['Type_log'], $testData['NiveauE'], $testData['Profession']
        );
    }
    
    echo "<p>Requête SQL : " . $req . "</p>";
    echo "<p>Paramètres : " . implode(', ', $params) . "</p>";
    
    $stmt = $pdo->prepare($req);
    $result = $stmt->execute($params);
    
    if ($result) {
        echo "<p>✅ Patient enregistré avec succès !</p>";
        echo "<p>Numéro URAP : " . $testData['N_Urap'] . "</p>";
        
        // Vérifier que le patient a bien été enregistré
        $stmt = $pdo->prepare("SELECT * FROM patient WHERE Numero_urap = ?");
        $stmt->execute([$testData['N_Urap']]);
        $patient = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($patient) {
            echo "<p>✅ Patient trouvé dans la base de données</p>";
        } else {
            echo "<p>❌ Patient non trouvé dans la base de données</p>";
        }
    } else {
        echo "<p>❌ Erreur lors de l'enregistrement</p>";
    }
    
} catch (PDOException $e) {
    echo "<p>❌ Erreur PDO : " . $e->getMessage() . "</p>";
}
?> 