<?php
session_start();
if (isset($_POST["radio1"]) & isset($_POST["radio2"])) {
    $res1 = $_POST["radio1"];
    $res2 = $_POST["radio2"];
    echo"choix de système : ".$res1."<br>";
    echo"choix de récompense : ".$res2."<br>";
    echo"identifiant de l'enfant : ".$_SESSION['id_enfant']."<br>";
    echo"identifiant de l'enfant : ".$_SESSION['logged_user']."<br>";
   // je met le type de système et de recompenses dans une variable superglobale pour que je récupère les informations dans les pages suivantes sans faire de post, ou de get
    $_SESSION["type_sys"]= $res1;
    $_SESSION["type_rec"]=$res2;

    switch ($_SESSION["type_sys"]) {
        case '1':
            header('Location: creation_systeme_chargement.php');
            break;
        
        case '2':
            echo"page pas encore creée";
            break;

        case '3':
            header('Location: creation_systeme_contrat.php');
            break;
        
        default:
            echo"erreur de choix de système";
            exit();
            break;
    }

}
