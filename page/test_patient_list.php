<?php
require_once("connexion.php");

echo "<h2>Test du système de liste des patients du jour</h2>";

try {
    // Vérifier la structure de la table
    $stmt = $pdo->query("SHOW COLUMNS FROM patient LIKE 'date_creation'");
    $hasDateCreation = $stmt->rowCount() > 0;
    
    echo "<p>Colonne date_creation : " . ($hasDateCreation ? "✅ Présente" : "❌ Absente") . "</p>";
    
    // Compter les patients d'aujourd'hui
    if ($hasDateCreation) {
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM patient WHERE DATE(date_creation) = CURDATE()");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "<p>Patients enregistrés aujourd'hui : " . $result['total'] . "</p>";
        
        // Afficher les patients d'aujourd'hui
        $stmt = $pdo->query("SELECT Numero_urap, Nom_patient, Prenom_patient, Age, date_creation FROM patient WHERE DATE(date_creation) = CURDATE() ORDER BY date_creation DESC");
        $patients = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (count($patients) > 0) {
            echo "<h3>Liste des patients d'aujourd'hui :</h3>";
            echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
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
        } else {
            echo "<p>Aucun patient enregistré aujourd'hui</p>";
        }
        
        // Compter les patients d'hier pour comparaison
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM patient WHERE DATE(date_creation) = DATE_SUB(CURDATE(), INTERVAL 1 DAY)");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "<p>Patients d'hier : " . $result['total'] . "</p>";
        
    } else {
        echo "<p>⚠️ La colonne date_creation n'existe pas. Le système ne peut pas filtrer par jour.</p>";
    }
    
    // Test de la requête JSON
    echo "<h3>Test de la requête JSON :</h3>";
    $jsonData = file_get_contents('get_patients_list.php');
    echo "<pre>" . htmlspecialchars($jsonData) . "</pre>";
    
} catch (PDOException $e) {
    echo "<p>❌ Erreur : " . $e->getMessage() . "</p>";
}

echo "<p><a href='ajouter_patient.php'>Retour au formulaire</a></p>";
?>
<!-- Bouton retour global en bas de page -->
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