<?php
/**
 * @file insert_recompense_bd.php
 * @brief Page d'insertion d'une récompense dans la base de données
 * @details Page d'insertion d'une récompense dans la base de données, permet à un validateur de créer une récompense
 * @version 1.0
 */
require_once ('fonctions.php');
is_logged();
is_validateur();
is_not_admin();
$linkpdo = connexionBd();
// fichier qui insert la récompense dans une base de données
switch ($_SESSION['type_rec']) {
    case '4': // type = unique
        // je creé la requete d'insertion
        $intitule = htmlspecialchars($_POST["intitule"]);
        unset($_POST["intitule"]); // je retire le nom du système pour qu'il ne soit pas dans $all_keys
        $descriptif = htmlspecialchars($_POST["descriptif"]);
        unset($_POST["intitule"]);
        $photo_recompense = uploadImage($_FILES['photo_recompense']);
        $req = $linkpdo->prepare('INSERT INTO `recompense`(`intitule`, `descriptif`, `lien_image`) VALUES (:intitule, :descriptif, :lien_image)');
        if ($req == false) {
            die("erreur linkpdo");
        }
        try {
            $req->execute(array('intitule' => htmlspecialchars($intitule), // la chaine que je reconstruit pour avoir ce que je veux
            'descriptif' => htmlspecialchars($descriptif), 'lien_image' => htmlspecialchars($photo_recompense),));
            if ($req == false) {
                $req->debugDumpParams();
                die("erreur execute");
            }
        }
        catch(Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
        //liaison dans la bd
        try {
            $res = $linkpdo->query("SELECT MAX(id_recompense) FROM recompense");
            $res2 = $linkpdo->query("SELECT MAX(id_objectif) FROM objectif");
        }
        catch(Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
        $double_tab = $res->fetchAll();
        $double_tab2 = $res2->fetchAll();
        var_dump($double_tab[0][0]); // j'ai l'id de la derniere récompense de la bd
        var_dump($double_tab2[0][0]); //j'ai l'id du dernier objectif de la bd
        $req = $linkpdo->prepare('INSERT INTO `lier`(`id_objectif`, `id_recompense`) VALUES (:id_objectif, :id_recompense)');
        if ($req == false) {
            die("erreur linkpdo");
        }
        try {
            $req->execute(array('id_objectif' => $double_tab2[0][0], // la chaine que je reconstruit pour avoir ce que je veux
            'id_recompense' => $double_tab[0][0],));
            // $req->debugDumpParams();
            // exit();
            if ($req == false) {
                $req->debugDumpParams();
                die("erreur execute");
            }
        }
        catch(Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    break;
    case '5':
        // je récupere le nombre de récompenses :
        $nb_recompenses = $_POST["nb_rec"];
        $y = 1;
        while ($y <= $nb_recompenses) {
            $intitule = htmlspecialchars($_POST["intitule$y"]);
            $descriptif = htmlspecialchars($_POST["descriptif$y"]);
            $photo_recompense = uploadImage($_FILES['photo_recompense' . $y]);
            $req = $linkpdo->prepare('INSERT INTO `recompense`(`intitule`, `descriptif`, `lien_image`) VALUES (:intitule, :descriptif, :lien_image)');
            if ($req == false) {
                die("erreur linkpdo");
            }
            try {
                $req->execute(array('intitule' => ($intitule), 'descriptif' => ($descriptif), 'lien_image' => $photo_recompense,));
                // $req->debugDumpParams();
                // exit();
                if ($req == false) {
                    die("erreur execute");
                }
            }
            catch(Exception $e) {
                die('Erreur : ' . $e->getMessage());
            }
            echo "ajout de la recompense n°" . $y;
            $y++;
        }
        echo "boucle finie";
        //liaison dans la bd
        try {
            $res = $linkpdo->query("SELECT MAX(id_recompense) FROM recompense");
            $res2 = $linkpdo->query("SELECT MAX(id_objectif) FROM objectif");
        }
        catch(Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
        $double_tab = $res->fetchAll();
        $double_tab2 = $res2->fetchAll();
        //debut de la boucle
        $insert = 0;
        while ($insert < $nb_recompenses) {
            $req = $linkpdo->prepare('INSERT INTO `lier`(`id_objectif`, `id_recompense`) VALUES (:id_objectif, :id_recompense)');
            if ($req == false) {
                die("erreur linkpdo");
            }
            try {
                $req->execute(array('id_objectif' => $double_tab2[0][0], // la chaine que je reconstruit pour avoir ce que je veux
                'id_recompense' => $double_tab[0][0] - $insert,));
                //$req->debugDumpParams();
                // exit();
                if ($req == false) {
                    $req->debugDumpParams();
                    die("erreur execute");
                }
            }
            catch(Exception $e) {
                die('Erreur : ' . $e->getMessage());
            }
            $insert++;
        } //fin de la bouble d'insert
        
    break;
    default:
        echo "erreur, on ne devrait jamais être là";
    break;
}
header('Location: index.php?id=' . $_SESSION['id_enfant']);
