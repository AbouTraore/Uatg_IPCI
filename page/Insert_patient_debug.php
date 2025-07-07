<?php
require_once("identifier.php");
require_once("connexion.php");

// Activer l'affichage des erreurs
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Debug - Insertion Patient</h2>";

// Récupération des données du formulaire
$Numero_urap = isset($_POST['N_Urap']) ? trim($_POST['N_Urap']) : "";
$Nom_patient = isset($_POST['Nom']) ? trim($_POST['Nom']) : "";
$Prenom_patient = isset($_POST['Prenom']) ? trim($_POST['Prenom']) : "";
$Age = isset($_POST['Age']) ? trim($_POST['Age']) : "";
$Sexe_patient = isset($_POST['SexeP']) ? trim($_POST['SexeP']) : "";
$Date_naissance = isset($_POST['datenaiss']) ? trim($_POST['datenaiss']) : "";
$Contact_patient = isset($_POST['contact']) ? trim($_POST['contact']) : "";
$Adresse = isset($_POST['Adresse']) ? trim($_POST['Adresse']) : "";
$Situation_matrimoniale = isset($_POST['SituaM']) ? trim($_POST['SituaM']) : "";
$Lieu_résidence = isset($_POST['reside']) ? trim($_POST['reside']) : "";
$Precise = isset($_POST['Precise']) ? trim($_POST['Precise']) : "";
$Type_logement = isset($_POST['Type_log']) ? trim($_POST['Type_log']) : "";
$Niveau_etude = isset($_POST['NiveauE']) ? trim($_POST['NiveauE']) : "";
$Profession = isset($_POST['Profession']) ? trim($_POST['Profession']) : "";

echo "<h3>Données reçues :</h3>";
echo "<ul>";
echo "<li>Numero_urap: " . htmlspecialchars($Numero_urap) . "</li>";
echo "<li>Nom_patient: " . htmlspecialchars($Nom_patient) . "</li>";
echo "<li>Prenom_patient: " . htmlspecialchars($Prenom_patient) . "</li>";
echo "<li>Age: " . htmlspecialchars($Age) . "</li>";
echo "<li>Sexe_patient: " . htmlspecialchars($Sexe_patient) . "</li>";
echo "<li>Date_naissance: " . htmlspecialchars($Date_naissance) . "</li>";
echo "<li>Contact_patient: " . htmlspecialchars($Contact_patient) . "</li>";
echo "<li>Adresse: " . htmlspecialchars($Adresse) . "</li>";
echo "<li>Situation_matrimoniale: " . htmlspecialchars($Situation_matrimoniale) . "</li>";
echo "<li>Lieu_résidence: " . htmlspecialchars($Lieu_résidence) . "</li>";
echo "<li>Precise: " . htmlspecialchars($Precise) . "</li>";
echo "<li>Type_logement: " . htmlspecialchars($Type_logement) . "</li>";
echo "<li>Niveau_etude: " . htmlspecialchars($Niveau_etude) . "</li>";
echo "<li>Profession: " . htmlspecialchars($Profession) . "</li>";
echo "</ul>";

// Validation des champs obligatoires
if (empty($Numero_urap) || empty($Nom_patient) || empty($Prenom_patient) || empty($Date_naissance) || empty($Contact_patient)) {
    echo "<p style='color: red;'>❌ Erreur : Veuillez remplir tous les champs obligatoires</p>";
    echo "<p><a href='ajouter_patient.php'>Retour au formulaire</a></p>";
    exit();
}

// Validation du champ "Precise" pour "Hors Abidjan"
if ($Lieu_résidence === "Hors Abidjan" && empty($Precise)) {
    echo "<p style='color: red;'>❌ Erreur : Veuillez préciser le lieu de résidence pour 'Hors Abidjan'</p>";
    echo "<p><a href='ajouter_patient.php'>Retour au formulaire</a></p>";
    exit();
}

// Si c'est Abidjan, vider le champ Precise
if ($Lieu_résidence === "Abidjan") {
    $Precise = "";
}

try {
    echo "<h3>Vérification de la base de données...</h3>";
    
    // Vérifier si le numéro URAP existe déjà
    $checkStmt = $pdo->prepare("SELECT Numero_urap FROM patient WHERE Numero_urap = ?");
    $checkStmt->execute([$Numero_urap]);
    
    if ($checkStmt->rowCount() > 0) {
        echo "<p style='color: red;'>❌ Erreur : Le numéro URAP '" . htmlspecialchars($Numero_urap) . "' existe déjà dans la base de données</p>";
        echo "<p><a href='ajouter_patient.php'>Retour au formulaire</a></p>";
        exit();
    }

    // Vérifier la structure de la table pour adapter la requête
    $stmt = $pdo->query("SHOW COLUMNS FROM patient LIKE 'Adresse'");
    $hasAdresse = $stmt->rowCount() > 0;
    
    $stmt = $pdo->query("SHOW COLUMNS FROM patient LIKE 'date_creation'");
    $hasDateCreation = $stmt->rowCount() > 0;

    echo "<p>Colonne Adresse : " . ($hasAdresse ? "✅ Présente" : "❌ Absente") . "</p>";
    echo "<p>Colonne date_creation : " . ($hasDateCreation ? "✅ Présente" : "❌ Absente") . "</p>";

    // Construire la requête selon la structure détectée
    if ($hasAdresse && $hasDateCreation) {
        // Version avec tous les champs
        $req = "INSERT INTO patient (
            Numero_urap, Nom_patient, Prenom_patient, Age, Sexe_patient,
            Date_naissance, Contact_patient, Adresse, Situation_matrimoniale, 
            Lieu_résidence, Precise, Type_logement, Niveau_etude, Profession, date_creation
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

        $params = array(
            $Numero_urap, $Nom_patient, $Prenom_patient, $Age, $Sexe_patient,
            $Date_naissance, $Contact_patient, $Adresse, $Situation_matrimoniale,
            $Lieu_résidence, $Precise, $Type_logement, $Niveau_etude, $Profession
        );
    } else {
        // Version sans les champs Adresse et date_creation
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
    }

    echo "<h3>Requête SQL :</h3>";
    echo "<pre>" . htmlspecialchars($req) . "</pre>";
    
    echo "<h3>Paramètres :</h3>";
    echo "<pre>" . print_r($params, true) . "</pre>";

    $stmt = $pdo->prepare($req);
    $result = $stmt->execute($params);

    if ($result) {
        echo "<p style='color: green;'>✅ Patient " . htmlspecialchars($Nom_patient) . " " . htmlspecialchars($Prenom_patient) . " enregistré avec succès !</p>";
        echo "<p>Numéro URAP : " . htmlspecialchars($Numero_urap) . "</p>";
        
        // Vérifier que le patient a bien été enregistré
        $stmt = $pdo->prepare("SELECT * FROM patient WHERE Numero_urap = ?");
        $stmt->execute([$Numero_urap]);
        $patient = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($patient) {
            echo "<p style='color: green;'>✅ Patient trouvé dans la base de données</p>";
        } else {
            echo "<p style='color: red;'>❌ Patient non trouvé dans la base de données</p>";
        }
        
        echo "<p><a href='ajouter_patient.php'>Retour au formulaire</a></p>";
    } else {
        echo "<p style='color: red;'>❌ Erreur lors de l'enregistrement du patient</p>";
        echo "<p><a href='ajouter_patient.php'>Retour au formulaire</a></p>";
    }

} catch (PDOException $e) {
    echo "<p style='color: red;'>❌ Erreur PDO : " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p>Code d'erreur : " . $e->getCode() . "</p>";
    echo "<p><a href='ajouter_patient.php'>Retour au formulaire</a></p>";
}
?> 