<?php
require_once("connexion.php");

echo "<h2>Debug - Filtrage par date</h2>";

try {
    // Vérifier la structure de la table
    $stmt = $pdo->query("SHOW COLUMNS FROM patient LIKE 'date_creation'");
    $hasDateCreation = $stmt->rowCount() > 0;
    
    echo "<p>Colonne date_creation : " . ($hasDateCreation ? "✅ Présente" : "❌ Absente") . "</p>";
    
    if ($hasDateCreation) {
        // Afficher la date actuelle
        echo "<p>Date actuelle (CURDATE()) : " . date('Y-m-d') . "</p>";
        
        // Vérifier les dates dans la base
        $stmt = $pdo->query("SELECT DISTINCT DATE(date_creation) as dates FROM patient ORDER BY dates DESC LIMIT 10");
        $dates = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<h3>Dates présentes dans la base :</h3>";
        foreach ($dates as $date) {
            echo "<p>" . $date['dates'] . "</p>";
        }
        
        // Compter les patients par date
        $stmt = $pdo->query("SELECT DATE(date_creation) as date_creation, COUNT(*) as total FROM patient GROUP BY DATE(date_creation) ORDER BY date_creation DESC LIMIT 10");
        $counts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<h3>Nombre de patients par date :</h3>";
        foreach ($counts as $count) {
            echo "<p>" . $count['date_creation'] . " : " . $count['total'] . " patients</p>";
        }
        
        // Test de la requête exacte utilisée
        echo "<h3>Test de la requête exacte :</h3>";
        $stmt = $pdo->query("SELECT Numero_urap, Nom_patient, Prenom_patient, Age, date_creation FROM patient WHERE DATE(date_creation) = CURDATE() ORDER BY date_creation DESC");
        $patients = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<p>Patients d'aujourd'hui trouvés : " . count($patients) . "</p>";
        
        if (count($patients) > 0) {
            echo "<table border='1'>";
            echo "<tr><th>N° URAP</th><th>Nom</th><th>Prénom</th><th>Âge</th><th>Date création</th></tr>";
            foreach ($patients as $patient) {
                echo "<tr>";
                echo "<td>" . $patient['Numero_urap'] . "</td>";
                echo "<td>" . $patient['Nom_patient'] . "</td>";
                echo "<td>" . $patient['Prenom_patient'] . "</td>";
                echo "<td>" . $patient['Age'] . "</td>";
                echo "<td>" . $patient['date_creation'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
        
        // Test de la requête JSON
        echo "<h3>Résultat JSON :</h3>";
        $jsonData = file_get_contents('get_patients_list.php');
        echo "<pre>" . htmlspecialchars($jsonData) . "</pre>";
        
    } else {
        echo "<p>❌ La colonne date_creation n'existe pas</p>";
    }
    
} catch (PDOException $e) {
    echo "<p>❌ Erreur : " . $e->getMessage() . "</p>";
}

echo "<p><a href='ajouter_patient.php'>Retour au formulaire</a></p>";

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