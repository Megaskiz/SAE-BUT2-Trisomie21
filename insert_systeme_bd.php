<?php

// fichier qui insert le système dans une base de données


$nom = $_POST["nom"];
unset($_POST["nom"]); // je retire le nom du système pour qu'il ne soit pas dans $all_keys
$all_keys = array_keys($_POST); // je récupères toutes les clés de mon $_POST dans lesqueles il y à toutes les taches


$res="";
$i = 0;
foreach ($all_keys as $cle){
    //echo$_POST[$cle]; // affiche chaque tâches
    $res.=$_POST[$cle]."_".substr($cle, -7).":"; // je construit ma chaine
    $i+=1;
}
$res.=" ";

$total_jeton = 7* $i;

//étapes :
// debut : tache<numero_de_ligne>_<valeur_jetons> = <nom_de_tache>
// final : <nom_de_tache>_<valeur_jetons>:

///Connexion au serveur MySQL
try {
    $linkpdo = new PDO("mysql:host=localhost;dbname=routine","root","");
    }
///Capture des erreurs éventuelles
catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
    }
    
// je creé la requete d'insertion 
$req = $linkpdo->prepare('INSERT INTO objectif(nom,intitule, nb_jeton)
VALUES(:nom, :intitule, :nb_jeton)');

if ($req == false){
    die("erreur linkpdo");
}   
///Exécution de la requête
try{
    $req->execute(array('nom' => $nom,
                        'intitule' => $res,
                        'nb_jeton' => $total_jeton,
                ));
    if ($req == false){
        $req->debugDumpParams;
        die("erreur execute");
    }
}

catch (Exception $e)
{die('Erreur : ' . $e->getMessage());}

header('Location: choix_sys.php');
?>