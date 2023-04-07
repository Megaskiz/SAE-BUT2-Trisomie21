<?php
/**
 * @file suppr_sys.php
 * @brief Suppression d'un système
 * @details Page de suppression d'un système, permet à un validateur de supprimer un système
 * @version 1.0
 */
require_once('fonctions.php');
session_start();

$id_sys = $_GET['id_sys'];

$linkpdo = connexionBd();

/*
- la table système
*/
$req = $linkpdo->prepare('UPDATE objectif SET visibilite = "1" where id_objectif = '.$id_sys);

if ($req == false){
    die("erreur linkpdo");
}   
    
try{
    
    $req->execute(array());
    // $req->debugDumpParams();
    // exit();
    header("Location:index.php?id=".$_SESSION['id_enfant']);
    

}
catch (Exception $e)
{die('Erreur : ' . $e->getMessage());}
