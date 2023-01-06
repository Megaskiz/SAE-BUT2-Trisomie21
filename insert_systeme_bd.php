<?php
require('fonctions.php');
is_logged();
is_validateur();

///Connexion au serveur MySQL
try {
    $linkpdo = new PDO("mysql:host=localhost;dbname=bddsae","root","");
    }
///Capture des erreurs éventuelles
catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
    }

// 
//  FICHIER QUI DOIT ETRE MODIFIE ! ANCIENE VERSION
//

// fichier qui insert le système dans une base de données


$nom = $_POST["nom"];
unset($_POST["nom"]); // je retire le nom du système pour qu'il ne soit pas dans $all_keys

$duree = $_POST["duree"];
unset($_POST["duree"]);

$jeton = "jeton";

$prio = $_POST["prio"];
unset($_POST["prio"]);



switch ($_SESSION['type_sys']) {
    case '1': // type = chargement
        $total_jeton=$_POST['rows'];
        $res=$_POST['intitule'];
        $res.='_';
        for ($i=0; $i < $total_jeton; $i++) { 
            $res.='0';
        }
        $res.=':';
        break;
    
    case '2':
        echo"système non géré";
        exit();
        break;

    case '3':// type = routine  / contrat
        $all_keys = array_keys($_POST); // je récupères toutes les clés de mon $_POST dans lesqueles il y à toutes les taches
        $res="";
        $i = 0;
        foreach ($all_keys as $cle){
            //echo$_POST[$cle]; // affiche chaque tâches
            $res.=$_POST[$cle]."_".substr($cle, -7).":"; // je construit ma chaine
            $i+=1;
        }
        $res.=" ";

        $total_jeton = 7 * $i;
        break;
    
    default:
        echo"erreur, on ne devrait jamais être là";
        break;
}






    
// je creé la requete d'insertion 
$req = $linkpdo->prepare('INSERT INTO `objectif`(`nom`, `intitule`, `nb_jetons`, `duree`, `lien_image`, `priorite`, `travaille`, `id_membre`, `id_enfant`, `type`)
VALUES (:nom, :intitule, :nb_jeton, :duree, :jeton, :prio, :etat, :id_membre, :id_enfant, :type_sys)');

if ($req == false){
    die("erreur linkpdo");
}   
///Exécution de la requête
try{
    
    $req->execute(array('nom' => $res, // la chaine que je reconstruit pour avoir ce que je veux
                        'intitule' => $nom,
                        'nb_jeton' => $total_jeton,
                        'duree' => $duree,
                        'jeton' => $jeton,
                        'prio' => $prio,
                        'etat' =>  0,
                        'id_membre' => $_SESSION['logged_user'],
                        'id_enfant' => $_SESSION['id_enfant'],
                        'type_sys' => $_SESSION['type_sys']));
                        // $req->debugDumpParams();
                        // exit();
    if ($req == false){
        $req->debugDumpParams();
        die("erreur execute");
    }
}

catch (Exception $e)
{die('Erreur : ' . $e->getMessage());}

switch ($_SESSION["type_rec"]) {
    case '4':
        header('Location: creation_recompense_unique.php');
        break;
    
    case '5':
        echo"page pas encore creée";
        break;

    case '6':
        echo"page pas encore creée";
        break;
    
    default:
        echo$_SESSION["type_rec"];
        echo"erreur de choix de récompense";
        //header('Location: page_admin.php');
        exit();
        break;
}
?>