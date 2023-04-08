<?php
/**
 * @file login.php
 * @brief Page de connexion
 * @details Page de connexion, permet à l'utilisateur de se connecter à son compte ou etre redirigé vers la page d'inscription
 * @version 1.0
 */
//utilisation des fonctions de la page fonctions.php
require_once ("fonctions.php");
$linkpdo = connexionBd(); //connexion à la base de données
// Je récupère les informations de mon formulaire
if (!empty($_POST['courriel']) && !empty($_POST['password'])) {
    $courriel = $_POST['courriel'];
    $mdp_test = $_POST['password'];
    //$mdp_test = hash('sha256', "ZEN02anWobA4ve5zxzZz" . $_POST['password']);
    // Je récupère le mot de passe hashé correspondant à l'adresse email fournie
    $query = "SELECT id_membre, compte_valide, pro, role_user, mdp FROM membre WHERE courriel=:courriel";
    $stmt = $linkpdo->prepare($query);
    $stmt->bindParam(':courriel', $courriel, PDO::PARAM_STR);
    $stmt->execute();
    // Je récupère les informations sur le compte de l'utilisateur
    $valide = $stmt->fetchAll();
    // Je vérifie que le compte existe et que le mot de passe est correct
    if (count($valide) != 0) {
        $id = $valide[0][0];
        $compte_valide = $valide[0][1];
        $role = $valide[0][3];
        $hashed_password = $valide[0][4];
        if (password_verify($mdp_test, $hashed_password)) {
            if ($compte_valide == 1) {
                session_start();
                $_SESSION['login_user'] = $courriel;
                $_SESSION['logged_user'] = $id;
                $_SESSION['role_user'] = $role;
                if ($_SESSION['role_user'] == 2) {
                    header("location: page_certif_compte.php");
                } else {
                    header("location: index.php");
                }
            } else {
                $message_erreur = "Votre compte n'est pas encore validé";
            }
        } else {
            $message_erreur = "Identifiant ou mot de passe invalide";
        }
    } else {
        $message_erreur = "Identifiant ou mot de passe invalide";
    }
}
?>

<!DOCTYPE HTML>
<html lang="fr" style="font-family: raleway-extrabold,Helvetica,Arial,Lucida,sans-serif;">

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="style_css/style_login.css" media="screen" type="text/css" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenue</title>
</head>


<body>
    <div class="login-page">
        <div class="form">
            <p><?php if (isset($message_erreur)) {
    echo $message_erreur;
} ?></p>
            <div class="grille">
                <img class="logo" src="./img/logo_trisomie.png" alt="Logo de l'association Trisomie 21">
                <form action="" method="post" class="login-form">
                    <input type="text" name="courriel" placeholder="Adresse e-mail" />
                    <input type="password" name="password" placeholder="Mot de passe" />
                    <input class="button" type="submit" value="Acceder">

                    <div class="texte_creer-compte">
                        <p class="message">Pas de compte ? </p>
                        <a href="creation_compte.php">Créer un compte</a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</body>

</html>
