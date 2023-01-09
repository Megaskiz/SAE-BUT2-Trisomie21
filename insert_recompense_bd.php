<?php
require('fonctions.php');
is_logged();
is_validateur();
is_not_admin();

///Connexion au serveur MySQL
try {
    $linkpdo = new PDO("mysql:host=localhost;dbname=bddsae","root","");
    }
///Capture des erreurs éventuelles
catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
    }

// fichier qui insert la récompense dans une base de données

// je récupere les informations de mon formulaire
                    // var_dump($_POST);
                    // var_dump($_FILES);
                    // exit();


$intitule = $_POST["intitule"];
unset($_POST["intitule"]); // je retire le nom du système pour qu'il ne soit pas dans $all_keys

$descriptif = $_POST["descriptif"];
unset($_POST["intitule"]);

$photo_recompense = uploadImage($_FILES['photo_recompense']);


switch ($_SESSION['type_rec']) {
    case '4': // type = unique
        // je creé la requete d'insertion 
        $req = $linkpdo->prepare('INSERT INTO `recompense`(`intitule`, `descriptif`, `lien_image`) VALUES (:intitule, :descriptif, :lien_image)');

        if ($req == false){
            die("erreur linkpdo");
        }   
        ///Exécution de la requête
        try{
            
            $req->execute(array('intitule' => $intitule, // la chaine que je reconstruit pour avoir ce que je veux
                                'descriptif' => $descriptif,
                                'lien_image' => $photo_recompense,
                                ));
            if ($req == false){
                $req->debugDumpParams();
                die("erreur execute");
            }
        }   

        catch (Exception $e)
        {die('Erreur : ' . $e->getMessage());}
        break;
    
    case '2':
        echo"système non géré";
        exit();
        break;

    case '3':
        break;
    
    default:
        echo"erreur, on ne devrait jamais être là";
        break;
}



// lier la derniere récompense créee et le derniere systeme crée dans la table "lier"

try {
    $res = $linkpdo->query("SELECT MAX(id_recompense) FROM recompense");
    $res2 = $linkpdo->query("SELECT MAX(id_objectif) FROM objectif");
} catch (Exception $e) { // toujours faire un test de retour au cas ou ça crash
    die('Erreur : ' . $e->getMessage()); 
}

///Affichage des entrées du résultat une à une

$double_tab = $res->fetchAll();
$double_tab2 = $res2->fetchAll();

var_dump($double_tab[0][0]);// j'ai l'id de la derniere récompense de la bd
var_dump($double_tab2[0][0]);//j'ai l'id du dernier objectif de la bd

$req = $linkpdo->prepare('INSERT INTO `lier`(`id_objectif`, `id_recompense`) VALUES (:id_objectif, :id_recompense)');

        if ($req == false){
            die("erreur linkpdo");
        }   
        ///Exécution de la requête
        try{
            
            $req->execute(array('id_objectif' => $double_tab2[0][0], // la chaine que je reconstruit pour avoir ce que je veux
                                'id_recompense' => $double_tab[0][0],
                                ));
                                // $req->debugDumpParams();
                                // exit();
            if ($req == false){
                $req->debugDumpParams();
                die("erreur execute");
            }
        }   

        catch (Exception $e)
        {die('Erreur : ' . $e->getMessage());}

    

header('Location: page_admin.php?id='.$_SESSION['id_enfant']);

?>