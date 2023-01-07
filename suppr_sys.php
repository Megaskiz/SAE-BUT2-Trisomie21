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
    


$req_suppr = "DELETE FROM message where id_objectif=$id_sys;DELETE FROM lier where id_objectif=$id_sys;DELETE FROM objectif where id_objectif=$id_sys";

try {
    $res = $linkpdo->query($req_suppr);
    // ne fonctionne plus parce qu'il y a le id sys dans la table "placer_jeton", il faut rajouter un champ " visible" dans la table objectif, pour qu'on l'affiche ou non
    header('Location: page_admin.php?id='.$_SESSION["id_enfant"]);
} catch (Exception $e) { // toujours faire un test de retour au cas ou ça crash
    die('Erreur : ' . $e->getMessage());
}



?>