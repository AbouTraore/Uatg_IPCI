<?php
// Script de test pour la connexion et création de la table histoire_maladie
require_once("connexion.php");

echo "<h2>🔧 Test de connexion et création de la table histoire_maladie</h2>";

try {
    // Test de connexion
    echo "✅ Connexion à la base de données réussie<br>";
    
    // Vérifier si la table patient existe
    $stmt = $pdo->query("SHOW TABLES LIKE 'patient'");
    if ($stmt->rowCount() > 0) {
        echo "✅ La table 'patient' existe<br>";
        
        // Afficher quelques patients pour test
        $stmt = $pdo->query("SELECT Numero_urap, Nom_patient, Prenom_patient FROM patient LIMIT 5");
        $patients = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "<h3>Patients disponibles :</h3>";
        echo "<ul>";
        foreach ($patients as $patient) {
            echo "<li>N° URAP: " . htmlspecialchars($patient['Numero_urap']) . 
                 " - " . htmlspecialchars($patient['Nom_patient']) . 
                 " " . htmlspecialchars($patient['Prenom_patient']) . "</li>";
        }
        echo "</ul>";
    } else {
        echo "❌ La table 'patient' n'existe pas<br>";
    }
    
    // Créer la table histoire_maladie
    echo "<h3>Création de la table histoire_maladie :</h3>";
    
    $createTableSQL = "CREATE TABLE IF NOT EXISTS `histoire_maladie` (
        `id` int NOT NULL AUTO_INCREMENT,
        `numero_urap` varchar(15) NOT NULL,
        `sexe_patient` varchar(10) NOT NULL,
        `motif_homme` varchar(100) DEFAULT NULL,
        `motif_femme` varchar(100) DEFAULT NULL,
        `signes_fonctionnels` varchar(100) DEFAULT NULL,
        `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        UNIQUE KEY `numero_urap` (`numero_urap`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";
    
    $pdo->exec($createTableSQL);
    echo "✅ Table 'histoire_maladie' créée avec succès<br>";
    
    // Vérifier la structure de la table
    $stmt = $pdo->query("DESCRIBE histoire_maladie");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h3>Structure de la table histoire_maladie :</h3>";
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>Champ</th><th>Type</th><th>Null</th><th>Clé</th><th>Défaut</th></tr>";
    foreach ($columns as $column) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($column['Field']) . "</td>";
        echo "<td>" . htmlspecialchars($column['Type']) . "</td>";
        echo "<td>" . htmlspecialchars($column['Null']) . "</td>";
        echo "<td>" . htmlspecialchars($column['Key']) . "</td>";
        echo "<td>" . htmlspecialchars($column['Default']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Test d'insertion
    echo "<h3>Test d'insertion :</h3>";
    
    // Vérifier si un patient existe pour le test
    $stmt = $pdo->query("SELECT Numero_urap FROM patient LIMIT 1");
    $testPatient = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($testPatient) {
        $numero_urap = $testPatient['Numero_urap'];
        
        // Supprimer l'enregistrement de test s'il existe
        $stmt = $pdo->prepare("DELETE FROM histoire_maladie WHERE numero_urap = ?");
        $stmt->execute([$numero_urap]);
        
        // Insérer un enregistrement de test
        $sql = "INSERT INTO histoire_maladie (
            numero_urap, sexe_patient, motif_homme, motif_femme, signes_fonctionnels
        ) VALUES (?, ?, ?, ?, ?)";
        
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([
            $numero_urap,
            'masculin',
            'paternite',
            '',
            'leucorrhees'
        ]);
        
        if ($result) {
            echo "✅ Test d'insertion réussi<br>";
            
            // Vérifier l'insertion
            $stmt = $pdo->prepare("SELECT * FROM histoire_maladie WHERE numero_urap = ?");
            $stmt->execute([$numero_urap]);
            $testRecord = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($testRecord) {
                echo "✅ Données insérées correctement :<br>";
                echo "<ul>";
                echo "<li>ID: " . $testRecord['id'] . "</li>";
                echo "<li>N° URAP: " . htmlspecialchars($testRecord['numero_urap']) . "</li>";
                echo "<li>Sexe: " . htmlspecialchars($testRecord['sexe_patient']) . "</li>";
                echo "<li>Motif homme: " . htmlspecialchars($testRecord['motif_homme']) . "</li>";
                echo "<li>Signes: " . htmlspecialchars($testRecord['signes_fonctionnels']) . "</li>";
                echo "<li>Date: " . $testRecord['date_creation'] . "</li>";
                echo "</ul>";
            }
            
            // Nettoyer le test
            $stmt = $pdo->prepare("DELETE FROM histoire_maladie WHERE numero_urap = ?");
            $stmt->execute([$numero_urap]);
            echo "✅ Données de test supprimées<br>";
        } else {
            echo "❌ Échec du test d'insertion<br>";
        }
    } else {
        echo "⚠️ Aucun patient trouvé pour le test d'insertion<br>";
    }
    
    echo "<h3>🎉 Configuration terminée avec succès !</h3>";
    echo "<p>La page 'Histoire de la maladie' est maintenant prête à être utilisée.</p>";
    echo "<p><a href='histoire_maladie.php'>Accéder à la page Histoire de la maladie</a></p>";
    
} catch (Exception $e) {
    echo "❌ Erreur : " . $e->getMessage() . "<br>";
    echo "<p>Vérifiez la connexion à la base de données et les permissions.</p>";
}
?> 