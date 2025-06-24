<?php
require_once("identifier.php");
require_once("connexion.php");

// Vérifier que l'identifiant du patient est bien passé en GET
$Numero_urap = isset($_GET['idU']) ? $_GET['idU'] : null;
if (!$Numero_urap) {
    echo "<script>alert('Aucun patient sélectionné.'); window.location.href='Liste_patient.php';</script>";
    exit();
}

try {
    // Préparer et exécuter la requête de suppression
    $stmt = $pdo->prepare("DELETE FROM patient WHERE Numero_urap = ?");
    $stmt->execute([$Numero_urap]);
    echo "<script>alert('✅ Patient supprimé avec succès !'); window.location.href='Liste_patient.php';</script>";
    exit();
} catch (PDOException $e) {
    echo "<script>alert('❌ Erreur lors de la suppression : " . htmlspecialchars($e->getMessage()) . "'); window.location.href='Liste_patient.php';</script>";
    exit();
}
