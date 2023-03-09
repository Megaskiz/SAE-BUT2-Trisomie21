<?php

session_start();
///Connexion au serveur MySQL
try {
    $linkpdo = new PDO("mysql:host=localhost;dbname=bddsae", "root", "");
}
///Capture des erreurs éventuelles
catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

if (isset($_GET["id_sys"]) and isset($_GET["valeur"]) ) {
    $sys=$_GET["id_sys"];
    $val=$_GET["valeur"];

    $req = $linkpdo->prepare('UPDATE objectif SET travaille = :invers where id_objectif = :id ');

    if ($req == false){
        die("erreur linkpdo");
    }   
        ///Exécution de la requête
    try{
        $req->execute(array('invers' => $val, 'id' => $sys));
        header('Location: page_admin.php?id='.$_SESSION["id_enfant"]);
        if ($req == false){
            $req->debugDumpParams();
            die("erreur execute");
        }else{
            echo"<a href=\"choix_sys.php?id_sys=$id\"> recharger la page</a>";         
           
        }
    }
    catch (Exception $e)
    {die('Erreur : ' . $e->getMessage());}


    
}
else {
    header('Location: page_admin?id='.$_SESSION["logged_user"].'.php');
}
