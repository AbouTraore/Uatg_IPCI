<?php
header('Content-Type: application/json');
require_once('connexion.php');

// Récupérer les données du formulaire
$N_Urap    = $_POST['N_Urap'] ?? '';
$Nom       = $_POST['Nom'] ?? '';
$Prenom    = $_POST['Prenom'] ?? '';
$datenaiss = $_POST['datenaiss'] ?? '';
$Age       = $_POST['Age'] ?? '';
$SexeP     = $_POST['SexeP'] ?? '';
$contact   = $_POST['contact'] ?? '';
$Adresse   = $_POST['Adresse'] ?? '';
$SituaM    = $_POST['SituaM'] ?? '';
$reside    = $_POST['reside'] ?? '';
$Precise   = $_POST['Precise'] ?? '';
$Type_log  = $_POST['Type_log'] ?? '';
$NiveauE   = $_POST['NiveauE'] ?? '';
$Profession= $_POST['Profession'] ?? '';

// Vérifier les champs obligatoires
if (!$N_Urap || !$Nom || !$Prenom) {
    echo json_encode(['success' => false, 'error' => 'Champs obligatoires manquants']);
    exit;
}

// Vérifier l'unicité du Numero_urap
try {
    $check = $pdo->prepare('SELECT COUNT(*) FROM patient WHERE Numero_urap = ?');
    $check->execute([$N_Urap]);
    if ($check->fetchColumn() > 0) {
        echo json_encode(['success' => false, 'error' => 'Ce numéro Urap existe déjà']);
        exit;
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Erreur SQL : ' . $e->getMessage()]);
    exit;
}

try {
    $stmt = $pdo->prepare('INSERT INTO patient (Numero_urap, Nom_patient, Prenom_patient, Date_naissance, Age, Sexe_patient, Contact_patient, Adresse, Situation_matrimoniale, Lieu_résidence, Precise, Type_logement, Niveau_etude, Profession) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
    $stmt->execute([$N_Urap, $Nom, $Prenom, $datenaiss, $Age, $SexeP, $contact, $Adresse, $SituaM, $reside, $Precise, $Type_log, $NiveauE, $Profession]);

    // Retourner le patient ajouté pour l'affichage JS
    echo json_encode([
        'success' => true,
        'patient' => [
            'N_Urap'    => $N_Urap,
            'Nom'       => $Nom,
            'Prenom'    => $Prenom,
            'datenaiss' => $datenaiss,
            'Age'       => $Age,
            'SexeP'     => $SexeP,
            'contact'   => $contact,
            'Adresse'   => $Adresse,
            'SituaM'    => $SituaM,
            'reside'    => $reside,
            'Precise'   => $Precise,
            'Type_log'  => $Type_log,
            'NiveauE'   => $NiveauE,
            'Profession'=> $Profession
        ]
    ]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Erreur SQL : ' . $e->getMessage()]);
} 