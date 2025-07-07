<?php
// Script de correction automatique des problèmes d'insertion
require_once("connexion.php");

echo "<h2>🔧 Correction automatique des problèmes d'insertion</h2>";

// Fonction pour exécuter une requête SQL avec gestion d'erreur
function executeSQL($pdo, $sql, $description) {
    try {
        $pdo->exec($sql);
        echo "✅ " . $description . " - Succès<br>";
        return true;
    } catch (PDOException $e) {
        echo "❌ " . $description . " - Erreur : " . $e->getMessage() . "<br>";
        return false;
    }
}

// 1. Vérifier et corriger la structure de la table patient
echo "<h3>1. Vérification et correction de la structure de la table patient</h3>";

// Vérifier si la table existe
$stmt = $pdo->query("SHOW TABLES LIKE 'patient'");
if ($stmt->rowCount() == 0) {
    echo "❌ La table 'patient' n'existe pas. Création...<br>";
    
    $createTableSQL = "CREATE TABLE `patient` (
        `Numero_urap` varchar(15) NOT NULL,
        `Nom_patient` varchar(20) NOT NULL,
        `Prenom_patient` varchar(30) NOT NULL,
        `Age` int NOT NULL,
        `Sexe_patient` varchar(10) NOT NULL,
        `Date_naissance` date NOT NULL,
        `Contact_patient` varchar(15) NOT NULL,
        `Situation_matrimoniale` varchar(25) NOT NULL,
        `Lieu_résidence` varchar(30) NOT NULL,
        `Precise` varchar(35) NOT NULL,
        `Type_logement` varchar(25) NOT NULL,
        `Niveau_etude` varchar(15) NOT NULL,
        `Profession` varchar(25) NOT NULL
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci";
    
    executeSQL($pdo, $createTableSQL, "Création de la table patient");
} else {
    echo "✅ La table 'patient' existe<br>";
}

// 2. Vérifier et ajouter les champs manquants
echo "<h3>2. Vérification des champs manquants</h3>";

// Vérifier le champ Adresse
$stmt = $pdo->query("SHOW COLUMNS FROM patient LIKE 'Adresse'");
if ($stmt->rowCount() == 0) {
    echo "🔄 Ajout du champ 'Adresse'...<br>";
    executeSQL($pdo, "ALTER TABLE `patient` ADD COLUMN `Adresse` varchar(100) NOT NULL DEFAULT '' AFTER `Contact_patient`", "Ajout du champ Adresse");
} else {
    echo "✅ Le champ 'Adresse' existe<br>";
}

// Vérifier le champ date_creation
$stmt = $pdo->query("SHOW COLUMNS FROM patient LIKE 'date_creation'");
if ($stmt->rowCount() == 0) {
    echo "🔄 Ajout du champ 'date_creation'...<br>";
    executeSQL($pdo, "ALTER TABLE `patient` ADD COLUMN `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP", "Ajout du champ date_creation");
} else {
    echo "✅ Le champ 'date_creation' existe<br>";
}

// 3. Vérifier et ajouter la clé primaire
echo "<h3>3. Vérification de la clé primaire</h3>";
$stmt = $pdo->query("SHOW KEYS FROM patient WHERE Key_name = 'PRIMARY'");
if ($stmt->rowCount() == 0) {
    echo "🔄 Ajout de la clé primaire sur Numero_urap...<br>";
    executeSQL($pdo, "ALTER TABLE `patient` ADD PRIMARY KEY (`Numero_urap`)", "Ajout de la clé primaire");
} else {
    echo "✅ La clé primaire existe<br>";
}

// 4. Test d'insertion après corrections
echo "<h3>4. Test d'insertion après corrections</h3>";

try {
    // Supprimer l'enregistrement de test s'il existe
    $pdo->exec("DELETE FROM patient WHERE Numero_urap = 'TEST_FIX'");
    
    // Test d'insertion avec la structure corrigée
    $sql = "INSERT INTO patient (
        Numero_urap, Nom_patient, Prenom_patient, Age, Sexe_patient,
        Date_naissance, Contact_patient, Adresse, Situation_matrimoniale, 
        Lieu_résidence, Precise, Type_logement, Niveau_etude, Profession, date_creation
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
    
    $params = array(
        'TEST_FIX', 'Test', 'Correction', 30, 'Féminin',
        '1994-01-01', '0123456789', 'Adresse test', 'Célibataire',
        'Abidjan', '', 'Studio', 'Secondaire', 'Etudiant'
    );
    
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute($params);
    
    if ($result) {
        echo "✅ Test d'insertion réussi après corrections !<br>";
        
        // Vérifier que l'enregistrement a été inséré
        $stmt = $pdo->query("SELECT * FROM patient WHERE Numero_urap = 'TEST_FIX'");
        if ($stmt->rowCount() > 0) {
            echo "✅ Vérification : l'enregistrement a bien été inséré<br>";
        }
        
        // Supprimer l'enregistrement de test
        $pdo->exec("DELETE FROM patient WHERE Numero_urap = 'TEST_FIX'");
        echo "🗑️ Enregistrement de test supprimé<br>";
    } else {
        echo "❌ Le test d'insertion a échoué même après corrections<br>";
    }
    
} catch (PDOException $e) {
    echo "❌ Erreur lors du test d'insertion : " . $e->getMessage() . "<br>";
}

// 5. Afficher la structure finale
echo "<h3>5. Structure finale de la table patient</h3>";
try {
    $stmt = $pdo->query("DESCRIBE patient");
    echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
    echo "<tr><th>Champ</th><th>Type</th><th>Null</th><th>Clé</th><th>Défaut</th><th>Extra</th></tr>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . $row['Field'] . "</td>";
        echo "<td>" . $row['Type'] . "</td>";
        echo "<td>" . $row['Null'] . "</td>";
        echo "<td>" . $row['Key'] . "</td>";
        echo "<td>" . $row['Default'] . "</td>";
        echo "<td>" . $row['Extra'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} catch (PDOException $e) {
    echo "❌ Erreur lors de l'affichage de la structure : " . $e->getMessage() . "<br>";
}

echo "<h3>6. Résumé des corrections</h3>";
echo "<ul>";
echo "<li>✅ Structure de la table vérifiée et corrigée</li>";
echo "<li>✅ Champs manquants ajoutés si nécessaire</li>";
echo "<li>✅ Clé primaire ajoutée si nécessaire</li>";
echo "<li>✅ Test d'insertion effectué</li>";
echo "</ul>";

echo "<h3>7. Prochaines étapes</h3>";
echo "<p>Maintenant vous pouvez :</p>";
echo "<ul>";
echo "<li><a href='ajouter_patient.php'>Tester l'ajout d'un patient</a></li>";
echo "<li><a href='test_insertion.php'>Lancer le diagnostic complet</a></li>";
echo "<li><a href='Liste_patient.php'>Voir la liste des patients</a></li>";
echo "</ul>";

echo "<h3>8. Si les problèmes persistent</h3>";
echo "<p>Si vous rencontrez encore des erreurs :</p>";
echo "<ol>";
echo "<li>Vérifiez que votre serveur MySQL est démarré</li>";
echo "<li>Vérifiez les paramètres de connexion dans connexion.php</li>";
echo "<li>Vérifiez les permissions de la base de données</li>";
echo "<li>Consultez les logs d'erreur de votre serveur web</li>";
echo "</ol>";
?> 