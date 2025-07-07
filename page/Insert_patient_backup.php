<?php
require_once("identifier.php");
require_once("connexion.php");

// Récupération des données du formulaire
$Numero_urap = isset($_POST['N_Urap']) ? trim($_POST['N_Urap']) : "";
$Nom_patient = isset($_POST['Nom']) ? trim($_POST['Nom']) : "";
$Prenom_patient = isset($_POST['Prenom']) ? trim($_POST['Prenom']) : "";
$Age = isset($_POST['Age']) ? trim($_POST['Age']) : "";
$Sexe_patient = isset($_POST['SexeP']) ? trim($_POST['SexeP']) : "";
$Date_naissance = isset($_POST['datenaiss']) ? trim($_POST['datenaiss']) : "";
$Contact_patient = isset($_POST['contact']) ? trim($_POST['contact']) : "";
$Situation_matrimoniale = isset($_POST['SituaM']) ? trim($_POST['SituaM']) : "";
$Lieu_résidence = isset($_POST['reside']) ? trim($_POST['reside']) : "";
$Precise = isset($_POST['Precise']) ? trim($_POST['Precise']) : "";
$Type_logement = isset($_POST['Type_log']) ? trim($_POST['Type_log']) : "";
$Niveau_etude = isset($_POST['NiveauE']) ? trim($_POST['NiveauE']) : "";
$Profession = isset($_POST['Profession']) ? trim($_POST['Profession']) : "";

// Validation des champs obligatoires
if (empty($Numero_urap) || empty($Nom_patient) || empty($Prenom_patient) || empty($Date_naissance) || empty($Contact_patient)) {
    header('Location: ajouter_patient.php?error=' . urlencode('Veuillez remplir tous les champs obligatoires'));
    exit();
}

// Validation du champ "Precise" pour "Hors Abidjan"
if ($Lieu_résidence === "Hors Abidjan" && empty($Precise)) {
    header('Location: ajouter_patient.php?error=' . urlencode('Veuillez préciser le lieu de résidence pour "Hors Abidjan"'));
    exit();
}

// Si c'est Abidjan, vider le champ Precise
if ($Lieu_résidence === "Abidjan") {
    $Precise = "";
}

try {
    // Vérifier si le numéro URAP existe déjà
    $checkStmt = $pdo->prepare("SELECT Numero_urap FROM patient WHERE Numero_urap = ?");
    $checkStmt->execute([$Numero_urap]);
    
    if ($checkStmt->rowCount() > 0) {
        header('Location: ajouter_patient.php?error=' . urlencode('Le numéro URAP "' . $Numero_urap . '" existe déjà dans la base de données'));
        exit();
    }

    // Version précédente sans les champs Adresse et date_creation
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
    $result = $stmt->execute($params);

    if ($result) {
        header('Location: ajouter_patient.php?success=' . urlencode('Patient enregistré avec succès ! Numéro URAP : ' . $Numero_urap));
        exit();
    } else {
        header('Location: ajouter_patient.php?error=' . urlencode('Erreur lors de l\'enregistrement du patient'));
        exit();
    }

} catch (PDOException $e) {
    if ($e->getCode() == 23000) {
        // Erreur de doublon (clé unique sur Numero_urap)
        header('Location: ajouter_patient.php?error=' . urlencode('Le numéro URAP "' . $Numero_urap . '" existe déjà dans la base de données'));
    } else {
        // Autres erreurs PDO
        header('Location: ajouter_patient.php?error=' . urlencode('Erreur d\'enregistrement : ' . $e->getMessage()));
    }
    exit();
}
?>