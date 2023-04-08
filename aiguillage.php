<?php
/**
 * @file aiguillage.php
 * @brief Page d'aiguillage
 * @details Page d'aiguillage, permet à l'utilisateur de choisir le type de système et de récompenses qu'il souhaite créer
 */
session_start();
if (isset($_POST["radio1"]) & isset($_POST["radio2"])) { // si les deux boutons radio sont cochés
    $res1 = $_POST["radio1"];
    $res2 = $_POST["radio2"];
    // je met le type de système et de recompenses dans une variable superglobale pour que je récupère les informations dans les pages suivantes sans faire de post, ou de get
    $_SESSION["type_sys"] = $res1;
    $_SESSION["type_rec"] = $res2;
    switch ($_SESSION["type_sys"]) { // en fonction du type d'objectif choisi, on aiguille vers la bonne page
            
        case '1':
            header('Location: creation_systeme_chargement.php');
        break;
        case '3':
            header('Location: creation_systeme_contrat.php');
        break;
        default:
            echo "erreur de choix de système";
        break;
    }
}
?>