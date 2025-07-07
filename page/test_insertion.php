<?php
// Script de test pour diagnostiquer les problèmes d'insertion
require_once("connexion.php");

echo "<h2>🔍 Diagnostic des problèmes d'insertion</h2>";

// Test 1: Vérifier la connexion à la base de données
echo "<h3>1. Test de connexion à la base de données</h3>";
try {
    $testConnection = $pdo->query("SELECT 1");
    echo "✅ Connexion à la base de données réussie<br>";
} catch (PDOException $e) {
    echo "❌ Erreur de connexion : " . $e->getMessage() . "<br>";
    exit();
}

// Test 2: Vérifier l'existence de la table patient
echo "<h3>2. Vérification de la table patient</h3>";
try {
    $stmt = $pdo->query("SHOW TABLES LIKE 'patient'");
    if ($stmt->rowCount() > 0) {
        echo "✅ Table 'patient' existe<br>";
    } else {
        echo "❌ Table 'patient' n'existe pas<br>";
        exit();
    }
} catch (PDOException $e) {
    echo "❌ Erreur lors de la vérification de la table : " . $e->getMessage() . "<br>";
    exit();
}

// Test 3: Afficher la structure de la table patient
echo "<h3>3. Structure de la table patient</h3>";
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

// Test 4: Test d'insertion simple
echo "<h3>4. Test d'insertion simple</h3>";
try {
    // Vérifier si le champ Adresse existe
    $stmt = $pdo->query("SHOW COLUMNS FROM patient LIKE 'Adresse'");
    $hasAdresse = $stmt->rowCount() > 0;
    
    // Vérifier si le champ date_creation existe
    $stmt = $pdo->query("SHOW COLUMNS FROM patient LIKE 'date_creation'");
    $hasDateCreation = $stmt->rowCount() > 0;
    
    echo "Champ 'Adresse' existe : " . ($hasAdresse ? "✅ Oui" : "❌ Non") . "<br>";
    echo "Champ 'date_creation' existe : " . ($hasDateCreation ? "✅ Oui" : "❌ Non") . "<br>";
    
    // Test d'insertion selon la structure détectée
    if ($hasAdresse && $hasDateCreation) {
        // Version avec tous les champs
        $sql = "INSERT INTO patient (
            Numero_urap, Nom_patient, Prenom_patient, Age, Sexe_patient,
            Date_naissance, Contact_patient, Adresse, Situation_matrimoniale, 
            Lieu_résidence, Precise, Type_logement, Niveau_etude, Profession, date_creation
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        
        $params = array(
            'TEST001', 'Test', 'Patient', 25, 'Masculin',
            '2000-01-01', '0123456789', 'Adresse test', 'Célibataire',
            'Abidjan', '', 'Studio', 'Secondaire', 'Etudiant'
        );
        
        echo "🔄 Test avec champs Adresse et date_creation...<br>";
    } else {
        // Version sans ces champs
        $sql = "INSERT INTO patient (
            Numero_urap, Nom_patient, Prenom_patient, Age, Sexe_patient,
            Date_naissance, Contact_patient, Situation_matrimoniale, 
            Lieu_résidence, Precise, Type_logement, Niveau_etude, Profession
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $params = array(
            'TEST001', 'Test', 'Patient', 25, 'Masculin',
            '2000-01-01', '0123456789', 'Célibataire',
            'Abidjan', '', 'Studio', 'Secondaire', 'Etudiant'
        );
        
        echo "🔄 Test sans champs Adresse et date_creation...<br>";
    }
    
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute($params);
    
    if ($result) {
        echo "✅ Insertion de test réussie !<br>";
        
        // Supprimer l'enregistrement de test
        $pdo->exec("DELETE FROM patient WHERE Numero_urap = 'TEST001'");
        echo "🗑️ Enregistrement de test supprimé<br>";
    } else {
        echo "❌ Échec de l'insertion de test<br>";
    }
    
} catch (PDOException $e) {
    echo "❌ Erreur lors du test d'insertion : " . $e->getMessage() . "<br>";
}

// Test 5: Vérifier les données existantes
echo "<h3>5. Données existantes dans la table patient</h3>";
try {
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM patient");
    $count = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    echo "Nombre total d'enregistrements : " . $count . "<br>";
    
    if ($count > 0) {
        echo "<h4>Derniers enregistrements :</h4>";
        $stmt = $pdo->query("SELECT * FROM patient ORDER BY Numero_urap DESC LIMIT 5");
        echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
        $first = true;
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if ($first) {
                echo "<tr>";
                foreach ($row as $key => $value) {
                    echo "<th>" . $key . "</th>";
                }
                echo "</tr>";
                $first = false;
            }
            echo "<tr>";
            foreach ($row as $value) {
                echo "<td>" . htmlspecialchars($value) . "</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    }
} catch (PDOException $e) {
    echo "❌ Erreur lors de la vérification des données : " . $e->getMessage() . "<br>";
}

echo "<h3>6. Recommandations</h3>";
echo "<ul>";
echo "<li>Si les tests 1-3 échouent, vérifiez la connexion à la base de données</li>";
echo "<li>Si le test 4 échoue, vérifiez la structure de la table</li>";
echo "<li>Si vous voyez des erreurs SQL, notez-les pour correction</li>";
echo "</ul>";

echo "<h3>7. Actions possibles</h3>";
echo "<p><a href='ajouter_patient.php'>Retour au formulaire d'ajout</a></p>";
echo "<p><a href='Liste_patient.php'>Voir la liste des patients</a></p>";

// Bouton retour global en bas de page
?>
<div style="width:100%;display:flex;justify-content:center;margin:32px 0 0 0;">
  <button onclick="window.history.back()" class="btn-retour-global">
    <i class="fas fa-arrow-left"></i> Retour
  </button>
</div>
<style>
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
?> 