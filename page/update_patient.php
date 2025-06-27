<?php
// Inclusion des fichiers de sécurité et de connexion à la base de données
require_once("identifier.php");
require_once("connexion.php");

// Vérifier que le formulaire a bien été soumis en POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données envoyées par le formulaire
    $Numero_urap = isset($_POST['N_Urap']) ? $_POST['N_Urap'] : '';
    $Nom_patient = isset($_POST['Nom']) ? $_POST['Nom'] : '';
    $Prenom_patient = isset($_POST['Prenom']) ? $_POST['Prenom'] : '';
    $Age = isset($_POST['Age']) ? $_POST['Age'] : '';
    $Sexe_patient = isset($_POST['SexeP']) ? $_POST['SexeP'] : '';
    $Date_naissance = isset($_POST['datenaiss']) ? $_POST['datenaiss'] : '';
    $Contact_patient = isset($_POST['contact']) ? $_POST['contact'] : '';
    $Situation_matrimoniale = isset($_POST['SituaM']) ? $_POST['SituaM'] : '';
    $Lieu_résidence = isset($_POST['reside']) ? $_POST['reside'] : '';
    $Precise = isset($_POST['Precise']) ? $_POST['Precise'] : '';
    $Type_logement = isset($_POST['Type_log']) ? $_POST['Type_log'] : '';
    $Niveau_etude = isset($_POST['NiveauE']) ? $_POST['NiveauE'] : '';
    $Profession = isset($_POST['Profession']) ? $_POST['Profession'] : '';
    $Adresse = isset($_POST['Adresse']) ? $_POST['Adresse'] : '';

    try {
        // Préparer la requête SQL de mise à jour
        $req = "UPDATE patient SET 
            Nom_patient = ?,
            Prenom_patient = ?,
            Age = ?,
            Sexe_patient = ?,
            Date_naissance = ?,
            Contact_patient = ?,
            Situation_matrimoniale = ?,
            Lieu_résidence = ?,
            Precise = ?,
            Type_logement = ?,
            Niveau_etude = ?,
            Profession = ?,
            Adresse = ?
            WHERE Numero_urap = ?";
        // Préparer les paramètres pour la requête
        $params = array(
            $Nom_patient, $Prenom_patient, $Age, $Sexe_patient, $Date_naissance, $Contact_patient,
            $Situation_matrimoniale, $Lieu_résidence, $Precise, $Type_logement, $Niveau_etude, $Profession, $Adresse, $Numero_urap
        );
        // Exécuter la requête de mise à jour
        $stmt = $pdo->prepare($req);
        $stmt->execute($params);
        // Rediriger vers modifpatient.php avec un message de succès
        header("Location: modifpatient.php?idU=$Numero_urap&success=1");
        exit();
    } catch (PDOException $e) {
        // Rediriger vers modifpatient.php avec un message d'erreur
        $errorMsg = urlencode($e->getMessage());
        header("Location: modifpatient.php?idU=$Numero_urap&error=$errorMsg");
        exit();
    }
} else {
    // Si la page est accédée sans soumission du formulaire, rediriger vers la liste
    header('Location: Liste_patient.php');
    exit();
} 