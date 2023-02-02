
<!DOCTYPE HTML>
<html lang="fr" style="font-family: Arial,sans-serif;">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="style_css/style_login.css" media="screen" type="text/css" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>bienvenue</title>
    </head>
    
<?php

// Connexion au serveur MySQL
try {
    $linkpdo = new PDO("mysql:host=localhost;dbname=bddsae", "root", "");
}
// Capture des erreurs éventuelles
catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

// Je récupère les informations de mon formulaire
if (!empty($_POST['courriel']) && !empty($_POST['password'])){
    $courriel = $_POST['courriel'];
    $mdp_test = hash('sha256', "ZEN02anWobA4ve5zxzZz" . $_POST['password']);

    // Je créé la requête préparée avec des paramètres nommés
    $query = "SELECT count(*) FROM membre WHERE courriel=:courriel AND mdp=:mdp";
    $stmt = $linkpdo->prepare($query);
    $stmt->bindParam(':courriel', $courriel, PDO::PARAM_STR);
    $stmt->bindParam(':mdp', $mdp_test, PDO::PARAM_STR);
    $stmt->execute();

    // Je récupère le nombre de résultats
    $count = $stmt->fetchColumn();

    // Je récupère les informations sur le compte de l'utilisateur
    $query2 = "SELECT id_membre, compte_valide, pro, role_user FROM membre WHERE courriel=:courriel AND mdp=:mdp";
    $stmt2 = $linkpdo->prepare($query2);
    $stmt2->bindParam(':courriel', $courriel, PDO::PARAM_STR);
    $stmt2->bindParam(':mdp', $mdp_test, PDO::PARAM_STR);
    $stmt2->execute();
    $valide = $stmt2->fetchAll();

    if(count($valide) != 0){
        $id = $valide[0][0];
        $compte_valide = $valide[0][1];
        $role = $valide[0][3];

        if ($count == 1 && $compte_valide == 1){
            session_start();
            $_SESSION['login_user'] = $courriel;
            $_SESSION['logged_user'] = $id;
            $_SESSION['role_user'] = $role;

            if($_SESSION['role_user'] == 2){
                header("location: page_certif_compte.php");
            }else{
                header("location: page_admin.php");
            }
        }else{
            $message_erreur = "Votre compte n'est pas encore validé";
        }
    }else{
        $message_erreur = "Identifiant ou mot de passe invalide";
    }
}

?>
    <body>
        <div class="login-page"> 
            <div class="form">
            <p><?php if (isset($message_erreur)){
                    echo$message_erreur;
                }  ?></p>
                <div class="grille">
                    <img class="logo" src="/sae-but2-s1/img/logo_trisomie.png" alt="Logo de l'association Trisomie 21">
                    <form action="" method="post" class="login-form">
                        <input type="text" name="courriel"placeholder="Adresse e-mail"/>
                        <input type="password" name="password" placeholder="Mot de passe"/>
                        <input class="button" type="submit" value="Acceder">

                        <div class="texte_creer-compte">
                        <p class="message">Pas de compte ? </p>
                        <a href="creation_compte.php">Creer un compte</a>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>