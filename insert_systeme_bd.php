<?php
require_once('fonctions.php');
is_logged();
is_validateur();
is_not_admin();

$linkpdo=connexionBd();

// fichier qui insert le système dans une base de données


$nom = htmlspecialchars($_POST["nom"]);
unset($_POST["nom"]); // je retire le nom du système pour qu'il ne soit pas dans $all_keys

$duree_raw = htmlspecialchars($_POST["duree"]);
unset($_POST["duree"]);

$echelle = htmlspecialchars($_POST["echelle"]);
unset($_POST["echelle"]);


switch ($echelle) {  
    case 'j':
        $duree=$duree_raw*24;
        echo$duree;
        break;

    case 's':
        $duree=$duree_raw*24*7;
        echo$duree;
        break;

    default:
        $duree = $duree_raw;
        echo$duree;
        break;
}

$jeton = "jeton";

$prio = htmlspecialchars($_POST["prio"]);
unset($_POST["prio"]);



switch ($_SESSION['type_sys']) {
    case '1': // type = chargement
        $total_jeton=htmlspecialchars($_POST['rows']);
        $res=htmlspecialchars($_POST['intitule']);
        $res.='_';
        for ($i=0; $i < $total_jeton; $i++) { 
            $res.='0';
        }
        $res.=':';
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
        header('Location: creation_recompense_multiples.php');
        break;
    default:
        echo$_SESSION["type_rec"];
        echo"erreur de choix de récompense";
        //header('Location: index.php');
        break;
}
