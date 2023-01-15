<?php

require('fonctions.php');
is_logged();
is_validateur();
is_not_admin();


// la partie de la connexion
///Connexion au serveur MySQL
try {
    $linkpdo = new PDO("mysql:host=localhost;dbname=bddsae", "root", "");
    }
    ///Capture des erreurs éventuelles
    catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
    }

        
    //je récupere les informations de mon formulaire
  
            $mdp=htmlspecialchars($_POST['mdp_membre']);

            // fonction qui hash le mot de passe
            $mot = "ZEN02anWobA4ve5zxzZz".$mdp; // je rajoute une chaine que je vais ajouter au mot de passe
            $nouveau_mdp = hash('sha256', $mot);
            

                    $req = $linkpdo->prepare("UPDATE membre  SET mdp=? WHERE id_membre= ?");

                    if ($req == false){
                        die("erreur linkpdo");
                    }   
                        ///Exécution de la requête
                    try{
                        $req->execute([$nouveau_mdp, $_SESSION['id_compte_modif']]);
                        //$req->debugDumpParams();
                        //exit();

                        if ($req == false){
                            die("erreur execute");
                        }
                    }
                    
                    catch (Exception $e)
                    {die('Erreur : ' . $e->getMessage());}
                    
                    header('Location: page_certif_compte.php?idv='.$_SESSION['id_compte_modif'].'');
                    exit();
                
            ?>