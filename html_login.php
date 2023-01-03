
<!DOCTYPE HTML>
<html lang="fr" style="font-family: Arial,sans-serif;">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="./style_login.css" media="screen" type="text/css" />
        <title>bienvenue</title>
    </head>
    <?php // la partie de la connexion

 ///Connexion au serveur MySQL
 try {
    $linkpdo = new PDO("mysql:host=localhost;dbname=bddsae", "root", "");
    }
    ///Capture des erreurs éventuelles
    catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
    }
    

    
    // je récupere les informations de mon formulaire
    if (!empty($_POST['courriel']) && !empty($_POST['password'])){
        $Courriel = $_POST['courriel'];
        //echo("ce que je test : ZEN02anWobA4ve5zxzZz".$_POST['password']);
        //echo"<br>";
        $mdp_test = hash('sha256',"ZEN02anWobA4ve5zxzZz".$_POST['password']);
        //echo$mdp_test;


        // je creé la requete
        $query = "SELECT count(*) FROM membre WHERE courriel='$Courriel' and mdp='$mdp_test'";
        $query2 = "SELECT id_membre, compte_valide, pro FROM membre WHERE courriel='$Courriel' and mdp='$mdp_test'";
                // Execution de la requete
                try {
                    $res = $linkpdo->query($query);
                    $res2 = $linkpdo->query($query2);
                    }
                    catch (Exception $e) { // toujours faire un test de retour au cas ou ça crash
                        die('Erreur : ' . $e->getMessage());
                    }

                    $count = $res -> fetchColumn();
                    $valide = $res2 -> fetchAll();

                    if(count($valide)!=0){
                        $id=$valide[0][0];
                        $compte_valide=$valide[0][1];
                        $role=$valide[0][2];

                        if ($count == 1 && $compte_valide==1){
                            session_start();
                            $_SESSION['login_user'] = $Courriel;
                            $_SESSION['logged_user'] = $id;
                            $_SESSION['role_user'] = $role;

                            if($_SESSION['role_user']==2){
                                header("location: page_certif_compte.php");
                            }else{
                                header("location: page_admin.php");
                            }
    
                        }else{
                            $message_erreur="Votre compte n'est pas encore validé";
                        }
                    }else{
                        $message_erreur = "identifiant ou mot de passe invalide";
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