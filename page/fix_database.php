<?php
require_once("connexion.php");

try {
    // 1. Ajouter le champ Adresse s'il n'existe pas
    $checkAdresse = $pdo->query("SHOW COLUMNS FROM patient LIKE 'Adresse'");
    if ($checkAdresse->rowCount() == 0) {
        $pdo->exec("ALTER TABLE patient ADD COLUMN Adresse varchar(100) NOT NULL DEFAULT '' AFTER Contact_patient");
        echo "✅ Champ Adresse ajouté avec succès<br>";
    } else {
        echo "ℹ️ Le champ Adresse existe déjà<br>";
    }

    // 2. Vérifier si la clé primaire existe
    $checkPrimary = $pdo->query("SHOW KEYS FROM patient WHERE Key_name = 'PRIMARY'");
    if ($checkPrimary->rowCount() == 0) {
        $pdo->exec("ALTER TABLE patient ADD PRIMARY KEY (Numero_urap)");
        echo "✅ Clé primaire ajoutée avec succès<br>";
    } else {
        echo "ℹ️ La clé primaire existe déjà<br>";
    }

    echo "<br>✅ Structure de la base de données corrigée avec succès !";
    echo "<br><a href='ajouter_patient.php'>Retour à l'ajout de patient</a>";

} catch (PDOException $e) {
    echo "❌ Erreur : " . $e->getMessage();
}
?> 