<?php
// Script de diagnostic pour identifier les erreurs
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>🔍 Diagnostic des erreurs</h2>";

// 1. Vérifier la connexion à la base de données
echo "<h3>1. Test de connexion à la base de données</h3>";
try {
    require_once("connexion.php");
    echo "✅ Connexion à la base de données réussie<br>";
} catch (Exception $e) {
    echo "❌ Erreur de connexion : " . $e->getMessage() . "<br>";
}

// 2. Vérifier la structure de la table patient
echo "<h3>2. Structure de la table patient</h3>";
try {
    $structure = $pdo->query("DESCRIBE patient");
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>Champ</th><th>Type</th><th>Null</th><th>Clé</th></tr>";
    while ($row = $structure->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . $row['Field'] . "</td>";
        echo "<td>" . $row['Type'] . "</td>";
        echo "<td>" . $row['Null'] . "</td>";
        echo "<td>" . $row['Key'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} catch (Exception $e) {
    echo "❌ Erreur : " . $e->getMessage() . "<br>";
}

// 3. Test d'insertion simple
echo "<h3>3. Test d'insertion</h3>";
try {
    // Vérifier si le test existe déjà
    $check = $pdo->prepare("SELECT Numero_urap FROM patient WHERE Numero_urap = 'DEBUG001'");
    $check->execute();
    
    if ($check->rowCount() == 0) {
        $insert = $pdo->prepare("INSERT INTO patient (Numero_urap, Nom_patient, Prenom_patient, Age, Sexe_patient, Date_naissance, Contact_patient, Adresse, Situation_matrimoniale, Lieu_résidence, Precise, Type_logement, Niveau_etude, Profession) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        $result = $insert->execute([
            'DEBUG001', 'Test', 'Debug', 25, 'Masculin', '2000-01-01', 
            '0123456789', 'Adresse test', 'Célibataire', 'Abidjan', '', 
            'Studio', 'Secondaire', 'Étudiant'
        ]);
        
        if ($result) {
            echo "✅ Test d'insertion réussi<br>";
        } else {
            echo "❌ Échec de l'insertion<br>";
        }
    } else {
        echo "ℹ️ Le patient de test existe déjà<br>";
    }
} catch (Exception $e) {
    echo "❌ Erreur d'insertion : " . $e->getMessage() . "<br>";
}

// 4. Vérifier les permissions
echo "<h3>4. Permissions et configuration</h3>";
echo "Version PHP : " . phpversion() . "<br>";
echo "Extensions PDO : " . (extension_loaded('pdo') ? '✅' : '❌') . "<br>";
echo "Extension PDO MySQL : " . (extension_loaded('pdo_mysql') ? '✅' : '❌') . "<br>";

// 5. Test du fichier Insert_patient.php
echo "<h3>5. Test du fichier Insert_patient.php</h3>";
if (file_exists('Insert_patient.php')) {
    echo "✅ Le fichier Insert_patient.php existe<br>";
    
    // Simuler une requête POST
    $_POST = [
        'N_Urap' => 'DEBUG002',
        'Nom' => 'Test',
        'Prenom' => 'Insert',
        'Age' => '30',
        'SexeP' => 'Féminin',
        'datenaiss' => '1994-01-01',
        'contact' => '0987654321',
        'Adresse' => 'Adresse de test',
        'SituaM' => 'Marié',
        'reside' => 'Abidjan',
        'Precise' => '',
        'Type_log' => 'Villa',
        'NiveauE' => 'Universitaire',
        'Profession' => 'Cadre'
    ];
    
    // Capturer la sortie
    ob_start();
    include 'Insert_patient.php';
    $output = ob_get_clean();
    
    echo "Réponse du serveur : <pre>" . htmlspecialchars($output) . "</pre>";
} else {
    echo "❌ Le fichier Insert_patient.php n'existe pas<br>";
}

echo "<br><a href='ajouter_patient.php'>Retour à l'ajout de patient</a>";
?> 