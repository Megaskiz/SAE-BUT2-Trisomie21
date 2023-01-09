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
        
    // je récupere les informations de mon formulaire
    // var_dump($_POST);
    // var_dump($_FILES);
    // exit();
  
            $nom = $_POST['nom_enfant'];
            $prenom = $_POST['prenom_enfant'];
            $activite = $_POST['activite'];
            $adresse = $_POST['adresse'];
            $handicap = $_POST['handicap'];
            $info_sup = $_POST['info_sup'];
            $date_naissance = $_POST['date_naissance'];
            
    
 



            
            

                    

                    $req = $linkpdo->prepare("UPDATE enfant  SET nom=? ,prenom= ?,date_naissance= ?,adresse= ?,activite= ?,handicap= ?, info_sup= ? WHERE id_enfant= ?");

                    if ($req == false){
                        die("erreur linkpdo");
                    }   
                        ///Exécution de la requête
                    try{
                        $req->execute([$nom, $prenom, $date_naissance, $adresse, $activite, $handicap, $info_sup, $_SESSION['id_enfant']]);

                        if ($req == false){
                            $req->debugDumpParams();
                            die("erreur execute");
                        }
                    }
                    
                    catch (Exception $e)
                    {die('Erreur : ' . $e->getMessage());}
                    
                    header('Location: page_admin.php?id='.$_SESSION['id_enfant'].'');
                    exit();
                
            ?>