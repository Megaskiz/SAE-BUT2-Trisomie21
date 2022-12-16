<!DOCTYPE HTML>
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
    if (!empty($_POST['nom']) 
        && !empty($_POST['prenom']) 
        && !empty($_POST['adresse']) 
        && !empty($_POST['code_postal']) 
        && !empty($_POST['ville']) 
        && !empty($_POST['courriel']) 
        && !empty($_POST['ddn']) 
        && !empty($_POST['password'])
        && !empty($_POST['password_verif']) )
        {   
            $nom = $_POST['nom'];
            $prenom = $_POST['prenom'];
            $adresse = $_POST['adresse'];
            $code = $_POST['code_postal'];
            $ville = $_POST['ville'];
            $courriel = $_POST['courriel'];
            $ddn = $_POST['ddn'];
            $Mdp = $_POST['password'];
            $Mdp_verif = $_POST['password_verif'];
            $pro = $_POST['pro'];

            // fonction qui hash le mot de passe
            $mot = "ZEN02anWobA4ve5zxzZz".$Mdp; // je rajoute une chaine que je vais ajouter au mot de passe
            $insert_mdp = hash('sha256', $mot);

            if  ($Mdp == $Mdp_verif){
                // requete avec le mail si, rowcount() > 0 faire fail
                $requete_verif_mail = "SELECT count(*) FROM membre WHERE courriel='$courriel'";
                // Execution de la requete
                try {
                    $res = $linkpdo->query($requete_verif_mail);
                    }
                    catch (Exception $e) { // toujours faire un test de retour au cas ou ça crash
                        die('Erreur : ' . $e->getMessage());
                    }

                    $count = $res -> fetchColumn();

                    if ($count > 0 ){
                        $message_erreur = "il y déjà un compte avec cette adresse mail ";
                    }else{
                        
                    
                    // je creé la requete d'insertion 

                    $req = $linkpdo->prepare('INSERT INTO membre(nom, prenom, adresse, code_postal, ville, courriel, date_naissance, mdp, pro, compte_valide)
                    VALUES(:nom, :prenom, :adresse, :code_postal, :ville, :courriel, :date_naissance, :mdp, :pro, :compte_valide)');

                    if ($req == false){
                        die("erreur linkpdo");
                    }   
                        ///Exécution de la requête
                    try{
                        $req->execute(array('nom' => $nom,
                                            'prenom' => $prenom,
                                            'adresse' => $adresse,
                                            'code_postal' => $code,
                                            'ville' => $ville,
                                            'courriel' => $courriel,
                                            'date_naissance' => $ddn,
                                            'mdp' => $insert_mdp,
                                            'pro' => $pro, // à changer
                                            'compte_valide' => 0
                                        
                                        
                                        
                                        ));
                        if ($req == false){
                            $req->debugDumpParams;
                            die("erreur execute");
                        }
                    }
                    
                    catch (Exception $e)
                    {die('Erreur : ' . $e->getMessage());}
                    
                    header('Location: compte_cree.php');
                    exit();
                }

            }else{
                $message_erreur = " Les mots de passe ne correspondent pas.";
            }
           
        }else{

            $nom = "";
            $prenom = "";
            $adresse =  "";
            $code =  "";
            $ville =  "";
            $courriel =  "";
            $ddn = "";
            $Mdp = "";
            $Mdp_verif =  "";

        }

?>
<html>
    <head>
        <meta charset="utf-8">
        <!-- importer le fichier de style -->
        <link rel="stylesheet" href="style_setup.css" media="screen" type="text/css" />
        <title>bienvenue</title>
    </head>
    <body>
        <div class="login-page">
            <div class="form">
                <p><?php if (isset($message_erreur)){
                    echo$message_erreur;
                }  ?></p>
                <div class="grille">
                    <img class="logo" src="/sae-but2-s1/img/logo_trisomie.png" alt="Logo de l'association Trisomie 21">
                    <form action="" method="post" class="login-form">
                        <input required type="text" name="nom" placeholder="Nom" value=<?php echo $nom ?>>
                        <input required type="text" name="prenom" placeholder="Prénom"value=<?php echo $prenom ?>>
                        <input required type="text" name="adresse" placeholder="Adresse"value=<?php echo $adresse ?>>
                        <input required type="number" name="code_postal" placeholder="Code postal"value=<?php echo $code ?>>
                        <input required type="text" name="ville" placeholder="Ville"value=<?php echo $ville ?>>
                        <input required type="email" name="courriel" placeholder="Adresse e-mail"value=<?php echo $courriel ?>>
                        <input required type="date" name="ddn" placeholder="date de naissance"value=<?php echo $ddn ?>>
                        <input required type="text" name="password" placeholder="Mot de passe"value=<?php echo $Mdp ?>>
                        <input required type="text" name="password_verif" placeholder="Confirmer le Mot de passe"value=<?php echo $Mdp_verif ?>>
                        <p>Etes vous un professionel ?</p>
                        <div class="sous-grille">
                            <div>
                                <label for="oui">oui</label>
                                <input type="radio" id="oui" name="pro" value="1" >
                            </div>
                            <div>
                                <label for="oui">non</label>
                                <input type="radio" id="oui"  name="pro" value="0"checked>
                            
                            </div>
                        </div>
                        <input class="button" type="submit" value="Valider l'inscription">
                        <p class="message">Déjà un compte ? <a href="html_login.php">S'identifer</a></p>
                        
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>