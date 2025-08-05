<?php
require_once("identifier.php");
require_once("connexion.php");

$numero_urap = $_GET['urap'] ?? '';
$visite_id = $_GET['visite_id'] ?? '';

if (!$numero_urap) {
    echo "<div class='alert alert-danger'>Aucun numéro URAP fourni.</div>";
    exit;
}

// 1. Infos de visite - soit spécifique, soit la dernière
if ($visite_id) {
    $stmt = $pdo->prepare("SELECT * FROM visite WHERE id_visite = ? AND Numero_urap = ?");
    $stmt->execute([$visite_id, $numero_urap]);
} else {
    $stmt = $pdo->prepare("SELECT * FROM visite WHERE Numero_urap = ? ORDER BY `Date visite` DESC LIMIT 1");
    $stmt->execute([$numero_urap]);
}
$visite = $stmt->fetch(PDO::FETCH_ASSOC);

// 2. Infos personnelles
$stmt = $pdo->prepare("SELECT * FROM patient WHERE Numero_urap = ?");
$stmt->execute([$numero_urap]);
$patient = $stmt->fetch(PDO::FETCH_ASSOC);

// 3. Examens de sperme (table ecs) - filtrés par date de visite si disponible
if ($visite) {
    $date_visite = $visite['Date visite'];
    $stmt = $pdo->prepare("SELECT * FROM ecs WHERE numero_identification = ? AND DATE(date_creation) = ? ORDER BY numero_identification DESC LIMIT 1");
    $stmt->execute([$numero_urap, $date_visite]);
} else {
    $stmt = $pdo->prepare("SELECT * FROM ecs WHERE numero_identification = ? ORDER BY numero_identification DESC LIMIT 1");
    $stmt->execute([$numero_urap]);
}
$examen_sperme = $stmt->fetch(PDO::FETCH_ASSOC);

// 4. Examen cytobactériologique vaginal (table exa_cyto_sec_vag)
if ($visite) {
    $stmt = $pdo->prepare("SELECT * FROM exa_cyto_sec_vag WHERE numero_identification = ? AND DATE(date_creation) = ? ORDER BY numero_identification DESC LIMIT 1");
    $stmt->execute([$numero_urap, $date_visite]);
} else {
    $stmt = $pdo->prepare("SELECT * FROM exa_cyto_sec_vag WHERE numero_identification = ? ORDER BY numero_identification DESC LIMIT 1");
    $stmt->execute([$numero_urap]);
}
$examen_vaginal = $stmt->fetch(PDO::FETCH_ASSOC);

// 5. Examen sécrétion urétrale (table ecsu)
if ($visite) {
    $stmt = $pdo->prepare("SELECT * FROM ecsu WHERE numero_identification = ? AND DATE(date_creation) = ? ORDER BY numero_identification DESC LIMIT 1");
    $stmt->execute([$numero_urap, $date_visite]);
} else {
    $stmt = $pdo->prepare("SELECT * FROM ecsu WHERE numero_identification = ? ORDER BY numero_identification DESC LIMIT 1");
    $stmt->execute([$numero_urap]);
}
$examen_uretral = $stmt->fetch(PDO::FETCH_ASSOC);

// Continuation du code existant...
// [Le reste du code reste identique]
?>