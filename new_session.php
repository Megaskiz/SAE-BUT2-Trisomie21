<?php
require_once('fonctions.php');
is_logged();
?>

<?php


$linkpdo = connexionBd();

// retourner sur la page avec le nouveau sys et la nouvelle session

$id = $_GET['id'];

// update le premier jeton de la derniere session a la nouvelle session
try {
    // je recup la derniere session pour ce sys
    $session_max_query = $linkpdo->query("SELECT max(id_session) from placer_jeton where id_objectif=" . $id);
    //$session_max_query->debugDumpParams();

} catch (Exception $e) { 
    die('Erreur : ' . $e->getMessage());
}
$double_tab = $session_max_query->fetchAll(); 
$session_max = $double_tab[0][0];

if ($session_max == NULL) {

    // echo"je vais insere le jeton témoin de ce système";
    // exit();
    // il n'y a aucune session pour ce système
    $session_max = 0;

    //mise de l'ancien jeton factice dans la nouvelle session
    try {
        echo "jesuisdansletry";
        $req5 = $linkpdo->prepare("INSERT INTO `placer_jeton`(`id_objectif`, `date_heure`, `id_membre`, `id_session`) VALUES (:un,:deux,:trois,:quatre)");
        $req5->execute(array('un' => $id, 'deux' => date("Y/m/d H:i:s"), 'trois' => $_SESSION['logged_user'], 'quatre' => 1));
        // $req5 -> debugDumpParams();
        // exit();
    } catch (Exception $e) { 
        die('Erreur : ' . $e->getMessage());
    }
} else {
    $session_actuelle = $session_max + 1;

    try {
        //je recupere la date du premier jeton placé pour cette session dans ce sys (jeton factice, témoin du début de la session)
        $jeton_premier_query = $linkpdo->query("SELECT min(date_heure) from placer_jeton where id_session=" . $session_max . " and id_objectif=" . $id);
    } catch (Exception $e) { 
        die('Erreur : ' . $e->getMessage());
    }

    

    $double_tab = $jeton_premier_query->fetchAll();

    $jeton_premier =  $double_tab[0][0];

    //mise de l'ancien jeton factice dans la nouvelle session
    try {
        $req5 = $linkpdo->prepare('UPDATE placer_jeton SET date_heure = :nouvelle_heure, id_session= :id_session where date_heure = :ancienne_date ');
        $req5->execute(array('nouvelle_heure' => date("Y/m/d H:i:s"), 'id_session' => $session_actuelle, 'ancienne_date' => $jeton_premier,));

        //$req5 -> debugDumpParams();
    } catch (Exception $e) { 
        die('Erreur : ' . $e->getMessage());
    }

    // remise a 0 du système :
    try {
        $res = $linkpdo->query("SELECT nom FROM objectif where id_objectif=$id");
    } catch (Exception $e) { 
        die('Erreur : ' . $e->getMessage());
    }
    $double_tab = $res->fetchAll();

    $chaine = $double_tab[0][0]; // je recup ma chaine 'nom' du sys

    $MaVariable = str_replace("1", "0", $chaine); // je la reset

    try {
        //mise a jour du nom du sys pour remettre à 0
        $req5 = $linkpdo->prepare('UPDATE objectif SET nom = :intit where id_objectif = :id ');
        $req5->execute(array('intit' => $MaVariable, 'id' => $id,));

        //$req5 -> debugDumpParams();
    } catch (Exception $e) { 
        die('Erreur : ' . $e->getMessage());
    }
}









header('Location: objectif.php?id_sys=' . $id);
