<?php
    try {
        // Correction de la chaîne de connexion PDO
        $pdo = new PDO("mysql:host=localhost;dbname=uatg", "root", "");

        // Optionnel : Définir le mode d'erreur PDO pour afficher les exceptions
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    } catch (PDOException $e) {
        // Correction de l'exception capturée
        echo 'Erreur de connexion : ' .$e->getMessage();
    }
?>