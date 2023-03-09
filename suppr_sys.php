<?php

session_start();

$id_sys = $_GET['id_sys'];



///Sélection de tout le contenu de la table enfant

try {
    $linkpdo = new PDO("mysql:host=localhost;dbname=bddsae", "root", "");
}
///Capture des erreurs éventuelles
catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

   
/*
- la table système
*/
$req = $linkpdo->prepare('UPDATE objectif SET visibilite = "1" where id_objectif = '.$id_sys);

if ($req == false){
    die("erreur linkpdo");
}   
    ///Exécution de la requête
try{
    
    $req->execute(array());
    // $req->debugDumpParams();
    // exit();
    header("Location:page_admin.php");
    


    if ($req == false){
        $req->debugDumpParams;
        die("erreur execute");
    }else{
        echo"<a href=\"page_admin.php\"> recharger la page</a>";         
        
    }
}
catch (Exception $e)
{die('Erreur : ' . $e->getMessage());}
