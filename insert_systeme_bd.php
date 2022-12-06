<?php
session_start();
// 
//  FICHIER QUI DOIT ETRE MODIFIE ! ANCIENE VERSION
//

// fichier qui insert le système dans une base de données


$nom = $_POST["nom"];
unset($_POST["nom"]); // je retire le nom du système pour qu'il ne soit pas dans $all_keys

$duree = $_POST["duree"];
unset($_POST["duree"]);

$prio = $_POST["prio"];
unset($_POST["prio"]);


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

//étapes :
// debut : tache<numero_de_ligne>_<valeur_jetons> = <nom_de_tache>
// final : <nom_de_tache>_<valeur_jetons>:

///Connexion au serveur MySQL
try {
    $linkpdo = new PDO("mysql:host=localhost;dbname=bddsae","root","");
    }
///Capture des erreurs éventuelles
catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
    }
    
// je creé la requete d'insertion 
$req = $linkpdo->prepare('INSERT INTO `objectif`(`nom`, `intitule`, `nb_jetons`, `duree`, `lien_image`, `priorite`, `travaille`, `id_membre`, `id_enfant`)
VALUES (:nom, :intitule, :nb_jeton, :duree, :jeton, :prio, :etat, :id_membre, :id_enfant)');

if ($req == false){
    die("erreur linkpdo");
}   
///Exécution de la requête
try{
    $var="salit";
    $req->execute(array('nom' => $res,
                        'intitule' => $nom,
                        'nb_jeton' => $total_jeton,
                        'duree' => $duree,
                        'jeton' => $var,
                        'prio' => $prio,
                        'etat' =>  0,
                        'id_membre' => $_SESSION['logged_user'],
                        'id_enfant' => $_SESSION['id_enfant']));
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