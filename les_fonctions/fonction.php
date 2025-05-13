<?php 


function rechercher_par_login($login){
    global $pdo;
    $requete=$pdo->prepare("select * from user where Login_user =?");
    $requete->execute(array($login));
    return $requete->rowCount();
}

function rechercher_par_email($email){
    global $pdo;
    $requete=$pdo->prepare("select * from user where Email_user =?");
    $requete->execute(array($email));
    return $requete->rowCount();
}

function rechercher_utilisateur_par_email($email){
    global $pdo;

    $requete=$pdo->prepare("select * from user where Email_user =?");

    $requete->execute(array($email));

    $user=$requete->fetch();

    if($user)
        return $user;
    else
        return null;
}








?>