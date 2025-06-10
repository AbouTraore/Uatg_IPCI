<?php
require_once("identifier.php");
require_once("connexion.php");

$Numero_urap = isset($_POST['N_Urap']) ? $_POST['N_Urap'] : "";
$Nom_patient = isset($_POST['Nom']) ? $_POST['Nom'] : "";
$Prenom_patient = isset($_POST['Prenom']) ? $_POST['Prenom'] : "";
$Age = isset($_POST['Age']) ? $_POST['Age'] : "";
$Sexe_patient = isset($_POST['SexeP']) ? $_POST['SexeP'] : "";
$Date_naissance = isset($_POST['datenaiss']) ? $_POST['datenaiss'] : "";
$Contact_patient = isset($_POST['contact']) ? $_POST['contact'] : "";
$Situation_matrimoniale = isset($_POST['SituaM']) ? $_POST['SituaM'] : "";
$Lieu_résidence = isset($_POST['reside']) ? $_POST['reside'] : "";
$Precise = isset($_POST['Precise']) ? $_POST['Precise'] : "";
$Type_logement = isset($_POST['Type_log']) ? $_POST['Type_log'] : "";
$Niveau_etude = isset($_POST['NiveauE']) ? $_POST['NiveauE'] : "";
$Profession = isset($_POST['Profession']) ? $_POST['Profession'] : "";

// Validation du champ "Precise"
if ($Lieu_résidence === "Hors Abidjan" && empty($Precise)) {
    echo "<script>alert('Veuillez préciser le lieu de résidence.'); window.history.back();</script>";
    exit();
}

try {
    $req = "INSERT INTO patient (
        Numero_urap, Nom_patient, Prenom_patient, Age, Sexe_patient,
        Date_naissance, Contact_patient, Situation_matrimoniale, 
        Lieu_résidence, Precise, Type_logement, Niveau_etude, Profession
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $params = array(
        $Numero_urap, $Nom_patient, $Prenom_patient, $Age, $Sexe_patient,
        $Date_naissance, $Contact_patient, $Situation_matrimoniale,
        $Lieu_résidence, $Precise, $Type_logement, $Niveau_etude, $Profession
    );

    $stmt = $pdo->prepare($req);
    $stmt->execute($params);

    // Message de succès avec nom et prénom
    echo "<script>
        alert('✅ SUCCÈS\\n\\nLe patient " . htmlspecialchars($Nom_patient) . " " . htmlspecialchars($Prenom_patient) . " a été enregistré avec succès !\\n\\nNuméro URAP : " . htmlspecialchars($Numero_urap) . "');
        window.location.href='acceuil.php';
    </script>";
    exit();

} catch (PDOException $e) {
    if ($e->getCode() == 23000) {
        // Erreur de doublon (clé unique sur Numero_urap)
        echo "<script>
            alert('❌ ERREUR DE DOUBLON\\n\\nLe numéro URAP \"" . htmlspecialchars($Numero_urap) . "\" existe déjà dans la base de données.\\n\\nVeuillez choisir un autre numéro URAP.');
            window.history.back();
        </script>";
        exit();
    } else {
        // Autres erreurs PDO
        echo "<script>
            alert('❌ ERREUR D\\'ENREGISTREMENT\\n\\nUne erreur s\\'est produite lors de l\\'enregistrement du patient.\\n\\nDétails : " . htmlspecialchars($e->getMessage()) . "');
            window.history.back();
        </script>";
        exit();
    }
}
?>