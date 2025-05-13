<?php
     session_start();
    if(isset($_SESSION['user'])){
          require_once("connexion.php");
          // Supposons que $_GET['idA'] est utilisé pour identifier l'enregistrement à mettre à jour
          $idU = isset($_GET['idU']) ? $_GET['idU'] :0;
          $etat = isset($_GET['etat']) ? $_GET['etat'] :0;
          
          if($etat==1)
               $newetat=0;
          else
               $newetat=1;
          // Requête SQL avec des marqueurs de position pour PDO
          $req = "UPDATE user SET Etat_user=? WHERE ID_user=?";
          
          // Tableau des paramètres pour la méthode execute
          $params = array($newetat,$idU);
          // Préparer la requête
          $resultatA = $pdo->prepare($req);
          // Exécuter la requête avec les paramètres
          $resultatA->execute($params);
          
          // Rediriger vers admin après la mise à jour
          header('Location: admin.php');
     }else {
          header('location:login.php');
    }



?>