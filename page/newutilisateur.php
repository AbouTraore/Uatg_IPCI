<?php
require_once('connexion.php');
require_once('../les_fonctions/fonction.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login = $_POST['login'];
    $nom = $_POST['Nom'];
    $Prenom = $_POST['Prenom'];
    $contact = $_POST['contact'];
    $role = $_POST['Role'];
    $pwd1 = $_POST['pwd1'];
    $pwd2 = $_POST['pwd2'];
    $email = $_POST['email'];

    $validationErreur = array();

    if (isset($login)) {
        $filtredLogin = filter_var($login, FILTER_SANITIZE_SPECIAL_CHARS);
        if (strlen($filtredLogin) < 4) {
            $validationErreur[] = "<strong>Erreur !!</strong> le login doit contenir au moins 4 caractere";
        }
    }

    if (isset($pwd1) && isset($pwd2)) {
        if (empty($pwd1)) {
            $validationErreur[] = "<strong>Erreur !!</strong> le mot de passe ne doit etre vide";
        }

        if (md5($pwd1) !== md5($pwd2)) {
            $validationErreur[] = "<strong>Erreur !!</strong> les deux mot de passe ne sont pas identiques";
        }
    }

    if (isset($email)) {
        $filtredemail = filter_var($email, FILTER_SANITIZE_EMAIL);
        if ($filtredemail != true) {
            $validationErreur[] = "<strong>Erreur !!</strong> Email non valide";
        }
    }

    if (empty($validationErreur)) {
        if (rechercher_par_login($login) == 0 && rechercher_par_email($email) == 0) {
            $requete = $pdo->prepare("INSERT INTO user(Nom_user, Prenom_user, Email_user, Mdp_user, Type_user, Etat_user, Contact_user, Login_user)
                                      VALUES(:pNom, :pPrenom, :pEmail, :Mdp, :pType, :pEtat, :pContact, :pLogin)");
            $requete->execute(array(
                'pNom' => $nom,
                'pPrenom' => $Prenom,
                'pEmail' => $email,
                'Mdp' => $pwd1,
                'pType' => $role,
                'pEtat' => 0,
                'pContact' => $contact,
                'pLogin' => $login
            ));
            $success_msg = "Félicitation, votre compte est créé, mais temporairement inactif jusqu'à activation par l'administration";
        } else if (rechercher_par_login($login) > 0) {
            $validationErreur[] = "Désolé le login existe déjà";
        } else if (rechercher_par_email($email) > 0) {
            $validationErreur[] = "Désolé l'email existe déjà";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Nouveau Utilisateur</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #003366;
            color: white;
            padding: 25px;
            text-align: center;
        }
        main {
            max-width: 960px;
            margin: auto;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            margin-top: 40px;
            box-shadow: 0 0 20px rgba(0, 51, 102, 0.1);
        }
        .header {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 25px;
            color: #003366;
            text-align: center;
        }
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }
        .form-field {
            display: flex;
            flex-direction: column;
            margin-bottom: 20px;
        }
        label {
            font-weight: 600;
            color: #003366;
            margin-bottom: 8px;
            font-size: 0.95em;
        }
        input, select {
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 1em;
            transition: border-color 0.3s, box-shadow 0.3s;
        }
        input:focus, select:focus {
            outline: none;
            border-color: #003366;
            box-shadow: 0 0 5px rgba(0, 51, 102, 0.3);
        }
        .button-group {
            text-align: center;
            margin-top: 30px;
        }
        .btn {
            background-color: #003366;
            color: white;
            padding: 14px 32px;
            border: none;
            border-radius: 8px;
            font-size: 1em;
            cursor: pointer;
            transition: background 0.3s;
        }
        .btn:hover {
            background-color: #0055aa;
        }
        .alert {
            padding: 12px;
            margin-bottom: 15px;
            border-left: 6px solid red;
            background-color: #ffeaea;
            color: #990000;
            font-size: 0.95em;
            border-radius: 4px;
        }
        .alert-success {
            border-left-color: green;
            background-color: #e0ffe0;
            color: #006600;
        }
    </style>
</head>
<body>

<header>
    <h1>Gestion des Utilisateurs</h1>
</header>

<main>
    <div class="header">Création d’un nouveau compte utilisateur</div>

    <div class="messages">
        <?php 
        if (!empty($validationErreur)) {
            foreach ($validationErreur as $error) {
                echo '<div class="alert">' . $error . '</div>';
            }
        }
        if (!empty($success_msg)) {
            echo '<div class="alert alert-success">' . $success_msg . '</div>';
            header('refresh:5;url=admin.php');
        }
        ?>
    </div>

    <form method="POST" action="newutilisateur.php">
        <div class="form-grid">
            <div class="form-field">
                <label>Nom</label>
                <input type="text" name="Nom" required placeholder="Votre nom">
            </div>
            <div class="form-field">
                <label>Prénom</label>
                <input type="text" name="Prenom" required placeholder="Votre prénom">
            </div>
            <div class="form-field">
                <label>Login</label>
                <input type="text" name="login" required placeholder="Votre identifiant">
            </div>
            <div class="form-field">
                <label>Email</label>
                <input type="email" name="email" required placeholder="Votre email">
            </div>
            <div class="form-field">
                <label>Contact</label>
                <input type="text" name="contact" required placeholder="Votre téléphone">
            </div>
            <div class="form-field">
                <label>Rôle</label>
                <select name="Role">
                    <option value="ADMIN">Docteur</option>
                    <option value="TECHNICIEN">Technicien</option>
                </select>
            </div>
            <div class="form-field">
                <label>Mot de passe</label>
                <input type="password" name="pwd1" required placeholder="Mot de passe">
            </div>
            <div class="form-field">
                <label>Confirmation</label>
                <input type="password" name="pwd2" required placeholder="Confirmez le mot de passe">
            </div>
        </div>

        <div class="button-group">
            <button type="submit" class="btn">Créer le compte</button>
        </div>
    </form>
</main>

</body>
</html>
