<?php
// Test rapide de l'insertion
require_once("connexion.php");

echo "<h2>🚀 Test rapide d'insertion</h2>";

try {
    // Test de connexion
    $pdo->query("SELECT 1");
    echo "✅ Connexion OK<br>";
    
    // Vérifier la structure
    $stmt = $pdo->query("DESCRIBE patient");
    echo "✅ Structure de la table patient :<br>";
    echo "<ul>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<li>" . $row['Field'] . " - " . $row['Type'] . "</li>";
    }
    echo "</ul>";
    
    // Test d'insertion simple
    $testNumero = 'TEST_' . time();
    
    // Vérifier si Adresse et date_creation existent
    $stmt = $pdo->query("SHOW COLUMNS FROM patient LIKE 'Adresse'");
    $hasAdresse = $stmt->rowCount() > 0;
    
    $stmt = $pdo->query("SHOW COLUMNS FROM patient LIKE 'date_creation'");
    $hasDateCreation = $stmt->rowCount() > 0;
    
    echo "Champ Adresse : " . ($hasAdresse ? "✅" : "❌") . "<br>";
    echo "Champ date_creation : " . ($hasDateCreation ? "✅" : "❌") . "<br>";
    
    if ($hasAdresse && $hasDateCreation) {
        $sql = "INSERT INTO patient (
            Numero_urap, Nom_patient, Prenom_patient, Age, Sexe_patient,
            Date_naissance, Contact_patient, Adresse, Situation_matrimoniale, 
            Lieu_résidence, Precise, Type_logement, Niveau_etude, Profession, date_creation
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        
        $params = array(
            $testNumero, 'Test', 'Patient', 25, 'Masculin',
            '2000-01-01', '0123456789', 'Adresse test', 'Célibataire',
            'Abidjan', '', 'Studio', 'Secondaire', 'Etudiant'
        );
    } else {
        $sql = "INSERT INTO patient (
            Numero_urap, Nom_patient, Prenom_patient, Age, Sexe_patient,
            Date_naissance, Contact_patient, Situation_matrimoniale, 
            Lieu_résidence, Precise, Type_logement, Niveau_etude, Profession
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $params = array(
            $testNumero, 'Test', 'Patient', 25, 'Masculin',
            '2000-01-01', '0123456789', 'Célibataire',
            'Abidjan', '', 'Studio', 'Secondaire', 'Etudiant'
        );
    }
    
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute($params);
    
    if ($result) {
        echo "✅ Insertion réussie ! Numéro de test : " . $testNumero . "<br>";
        
        // Supprimer le test
        $pdo->exec("DELETE FROM patient WHERE Numero_urap = '$testNumero'");
        echo "🗑️ Enregistrement de test supprimé<br>";
        
        echo "<div style='background: #ecfdf5; color: #065f46; padding: 15px; border-radius: 8px; margin: 15px 0;'>";
        echo "🎉 <strong>TOUT FONCTIONNE !</strong> Vous pouvez maintenant ajouter des patients normalement.";
        echo "</div>";
    } else {
        echo "❌ Échec de l'insertion<br>";
    }
    
} catch (PDOException $e) {
    echo "❌ Erreur : " . $e->getMessage() . "<br>";
    echo "Code d'erreur : " . $e->getCode() . "<br>";
}

echo "<h3>🔗 Liens utiles</h3>";
echo "<ul>";
echo "<li><a href='ajouter_patient.php'>📝 Ajouter un patient</a></li>";
echo "<li><a href='Liste_patient.php'>📋 Liste des patients</a></li>";
echo "<li><a href='test_insertion.php'>🔍 Diagnostic complet</a></li>";
echo "<li><a href='fix_insertion_problems.php'>🔧 Correction automatique</a></li>";
echo "</ul>";
?> 