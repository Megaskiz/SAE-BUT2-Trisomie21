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
  
            $nom = $_POST['nom_membre'];
            $prenom = $_POST['prenom_membre'];
            $date_naissance = $_POST['ddn_membre']; 
            $ville = $_POST['ville'];
            $adresse = $_POST['ad_membre'];
            $Cpostal = $_POST['cpostal_membre'];
            $role=$_POST['role'];

            if ($role==NULL){
                $role = '1';
            }



                    $req = $linkpdo->prepare("UPDATE membre  SET nom=? ,prenom= ?,adresse= ?,code_postal= ?,ville= ?, date_naissance= ?, role_user=? WHERE id_membre= ?");

                    if ($req == false){
                        die("erreur linkpdo");
                    }   
                        ///Exécution de la requête
                    try{
                        $req->execute([$nom, $prenom, $adresse, $Cpostal, $ville, $date_naissance,$role, $_SESSION['id_compte_modif']]);

                        if ($req == false){
                            die("erreur execute");
                        }
                    }
                    
                    catch (Exception $e)
                    {die('Erreur : ' . $e->getMessage());}
                    
                    header('Location: page_certif_compte.php?idv='.$_SESSION['id_compte_modif'].'');
                    exit();
                
            ?>