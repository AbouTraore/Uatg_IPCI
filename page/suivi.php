<?php
require_once("identifier.php");
require_once("connexion.php");

// Recherche par patient (optionnel)
$search = $_GET['search'] ?? '';
$sql = "SELECT d.*, p.Nom_patient, p.Prenom_patient FROM dossier_patient d JOIN patient p ON d.numero_urap = p.Numero_urap";
$params = [];
if ($search) {
    $sql .= " WHERE p.Nom_patient LIKE :search OR p.Prenom_patient LIKE :search OR d.numero_urap LIKE :search";
    $params[':search'] = "%$search%";
}
$sql .= " ORDER BY d.date_ouverture DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$dossiers = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Suivi des dossiers patients</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <h1>Suivi des dossiers patients</h1>
    <form method="get" action="">
        <input type="text" name="search" placeholder="Recherche patient ou numéro..." value="<?= htmlspecialchars($search) ?>">
        <button type="submit">Rechercher</button>
    </form>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>ID Dossier</th>
                <th>Patient</th>
                <th>Numéro URAP</th>
                <th>Date ouverture</th>
                <th>Date fermeture</th>
                <th>Statut</th>
                <th>Titre</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($dossiers as $dossier): ?>
            <tr>
                <td><?= $dossier['id_dossier'] ?></td>
                <td><?= htmlspecialchars($dossier['Nom_patient'] . ' ' . $dossier['Prenom_patient']) ?></td>
                <td><?= htmlspecialchars($dossier['numero_urap']) ?></td>
                <td><?= $dossier['date_ouverture'] ?></td>
                <td><?= $dossier['date_fermeture'] ?: '-' ?></td>
                <td><?= htmlspecialchars($dossier['statut']) ?></td>
                <td><?= htmlspecialchars($dossier['titre']) ?></td>
                <td><a href="suivi_fiches.php?id_dossier=<?= $dossier['id_dossier'] ?>">Voir fiches</a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html> 