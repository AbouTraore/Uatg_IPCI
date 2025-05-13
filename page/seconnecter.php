<?php  

session_start();
require_once("connexion.php");

$login=isset($_POST['login'])?$_POST['login']:"";
$pwd=isset($_POST['pwd'])?$_POST['pwd']:"";

$req="SELECT Id_user,Nom_user,Prenom_user,Email_user,Mdp_user,Type_user,Etat_user,Contact_user,Login_user FROM user where (Login_user='$login') and Mdp_user='$pwd'";
$resultat=$pdo->query($req);

if($utilisateur=$resultat->fetch()){
    if($utilisateur['Etat_user']==1){
        $_SESSION['user']=$utilisateur;
        header('location:../index.php');
    }else{
        $_SESSION['erreurLogin']="<strong>Erreur !! Votre compte est désactivé .<br>Veuillez contacter un Médecin </strong>  ";
        header('location:login.php');
    }
}else{
    $_SESSION['erreurLogin']="<strong>Erreur !! Login ou mot de passe incorrecte !!!</strong>";
    header('location:login.php');
}

?>