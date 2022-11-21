<!DOCTYPE HTML>
<?php // la partie de la connexion

 ///Connexion au serveur MySQL
 try {
    $linkpdo = new PDO("mysql:host=localhost;dbname=sae", "root", "");
    }
    ///Capture des erreurs éventuelles
    catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
    }
    

    
    // je récupere les informations de mon formulaire
    if (!empty($_POST['courriel']) && !empty($_POST['password'])){
        $Courriel = $_POST['courriel'];
        $Mdp = $_POST['password'];


        // je creé la requete
        $query = "SELECT count(*) FROM membre WHERE courriel='$Courriel' and mdp='$Mdp'";
                // Execution de la requete
                try {
                    $res = $linkpdo->query($query);
                    }
                    catch (Exception $e) { // toujours faire un test de retour au cas ou ça crash
                        die('Erreur : ' . $e->getMessage());
                    }

                    $count = $res -> fetchColumn();

                    if ($count == 1 ){
                        session_start();
                        $_SESSION['login_user'] = $Courriel;
                        header("location: page_admin_v2.php");
                    }else{
                        $message_erreur = "identifiant ou mot de passe invalide";
                    }
                    
                    
                      
                   


    }



?>




<html>
    <head>
        <meta charset="utf-8">
        <!-- importer le fichier de style -->
        <link rel="stylesheet" href="style_login.css" media="screen" type="text/css" />
        <title>bienvenue</title>
    </head>
    <body>
        <div class="login-page"> 
            <div class="form">
            <p><?php if (isset($message_erreur)){
                    echo$message_erreur;
                }  ?></p>
                <div class="grille">
                    <img class="logo" src="/sae/img/logo trisomie.png" alt="Logo de l'association Trisomie 21">
                    <form action="" method="post" class="login-form">
                        <input type="text" name="courriel"placeholder="Adresse e-mail"/>
                        <input type="password" name="password" placeholder="Mot de passe"/>
                        <input class="button" type="submit" value="Acceder">

                        <p class="message">Pas de compte ? <a href="creation_compte.php">Creer un compte</a></p>
                        
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>