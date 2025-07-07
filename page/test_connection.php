<?php
require_once("identifier.php");
require_once("connexion.php");

echo "<h2>Test de connexion à la base de données</h2>";

try {
    // Test de connexion
    echo "<p>✅ Connexion à la base de données réussie</p>";
    
    // Vérifier la structure de la table patient
    $stmt = $pdo->query("DESCRIBE patient");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h3>Structure de la table patient :</h3>";
    echo "<table border='1'>";
    echo "<tr><th>Champ</th><th>Type</th><th>Null</th><th>Clé</th><th>Défaut</th></tr>";
    
    foreach ($columns as $column) {
        echo "<tr>";
        echo "<td>" . $column['Field'] . "</td>";
        echo "<td>" . $column['Type'] . "</td>";
        echo "<td>" . $column['Null'] . "</td>";
        echo "<td>" . $column['Key'] . "</td>";
        echo "<td>" . $column['Default'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Compter les patients
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM patient");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "<p>Nombre total de patients : " . $result['total'] . "</p>";
    
    // Vérifier les patients d'aujourd'hui
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM patient WHERE DATE(date_creation) = CURDATE()");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "<p>Patients enregistrés aujourd'hui : " . $result['total'] . "</p>";
    
} catch (PDOException $e) {
    echo "<p>❌ Erreur de connexion : " . $e->getMessage() . "</p>";
}
?> 