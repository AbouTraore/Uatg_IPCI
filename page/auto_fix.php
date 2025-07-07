<?php
// Script de correction automatique
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>🔧 Correction automatique des problèmes</h2>";

try {
    require_once("connexion.php");
    echo "✅ Connexion établie<br>";
    
    // 1. Ajouter le champ Adresse s'il n'existe pas
    echo "<h3>1. Vérification du champ Adresse</h3>";
    $checkAdresse = $pdo->query("SHOW COLUMNS FROM patient LIKE 'Adresse'");
    if ($checkAdresse->rowCount() == 0) {
        $pdo->exec("ALTER TABLE patient ADD COLUMN Adresse varchar(100) NOT NULL DEFAULT '' AFTER Contact_patient");
        echo "✅ Champ Adresse ajouté<br>";
    } else {
        echo "ℹ️ Le champ Adresse existe déjà<br>";
    }
    
    // 2. Ajouter la clé primaire
    echo "<h3>2. Vérification de la clé primaire</h3>";
    $checkPrimary = $pdo->query("SHOW KEYS FROM patient WHERE Key_name = 'PRIMARY'");
    if ($checkPrimary->rowCount() == 0) {
        $pdo->exec("ALTER TABLE patient ADD PRIMARY KEY (Numero_urap)");
        echo "✅ Clé primaire ajoutée<br>";
    } else {
        echo "ℹ️ La clé primaire existe déjà<br>";
    }
    
    // 3. Vérifier et corriger les doublons existants
    echo "<h3>3. Nettoyage des doublons</h3>";
    $doublons = $pdo->query("SELECT Numero_urap, COUNT(*) as count FROM patient GROUP BY Numero_urap HAVING count > 1");
    if ($doublons->rowCount() > 0) {
        echo "⚠️ Doublons détectés, nettoyage en cours...<br>";
        // Supprimer les doublons en gardant le plus récent
        $pdo->exec("DELETE p1 FROM patient p1 INNER JOIN patient p2 WHERE p1.Numero_urap = p2.Numero_urap AND p1.date_creation < p2.date_creation");
        echo "✅ Doublons supprimés<br>";
    } else {
        echo "ℹ️ Aucun doublon détecté<br>";
    }
    
    // 4. Test d'insertion
    echo "<h3>4. Test d'insertion</h3>";
    $testNumero = 'AUTOFIX' . date('YmdHis');
    $insert = $pdo->prepare("INSERT INTO patient (Numero_urap, Nom_patient, Prenom_patient, Age, Sexe_patient, Date_naissance, Contact_patient, Adresse, Situation_matrimoniale, Lieu_résidence, Precise, Type_logement, Niveau_etude, Profession) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    $result = $insert->execute([
        $testNumero, 'Test', 'AutoFix', 25, 'Masculin', '2000-01-01',
        '0123456789', 'Adresse test', 'Célibataire', 'Abidjan', '',
        'Studio', 'Secondaire', 'Étudiant'
    ]);
    
    if ($result) {
        echo "✅ Test d'insertion réussi avec le numéro : $testNumero<br>";
        // Supprimer le patient de test
        $pdo->exec("DELETE FROM patient WHERE Numero_urap = '$testNumero'");
        echo "✅ Patient de test supprimé<br>";
    } else {
        echo "❌ Échec du test d'insertion<br>";
    }
    
    echo "<h3>✅ Correction terminée avec succès !</h3>";
    echo "<p>Vous pouvez maintenant utiliser le formulaire d'ajout de patient.</p>";
    
} catch (Exception $e) {
    echo "❌ Erreur lors de la correction : " . $e->getMessage() . "<br>";
}

echo "<br><a href='ajouter_patient.php' style='background: #0047ab; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Retour à l'ajout de patient</a>";
?> 