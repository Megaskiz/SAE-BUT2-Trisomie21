<?php
require_once('fonctions.php');
$linkpdo = connexionBd();


$id_recompense = $_GET['id_recompense'];

$intitule = $_POST['nom_recompense'];
$descriptif = $_POST['descriptif_recompense'];
$image_recompense = $_POST['image_recompense'];


echo "voici le descriptif : $descriptif";


if ($id_recompense !== null) {
    $data = [];


    if ($_FILES['image_recompense']['size'] > 0) {
        // Changer le nom de l'image dans la base de données
        $image_recompense = uploadImage($_FILES['image_recompense']);
        $req = "UPDATE recompense SET lien_image = ? WHERE id_recompense = ?";
        try {
            $stmt = $linkpdo->prepare($req);
            $stmt->execute([$image_recompense, $id_recompense]);
            echo "L'image a été mise à jour";
        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    } else {
        echo "pas d'image";
        echo "voici l'image : $image_recompense";
    }


    echo "<br>";


    if (!empty($intitule) && $descriptif == null) {
        $req = "UPDATE recompense SET intitule = '$intitule' WHERE id_recompense = $id_recompense ";
        try {
            $res = $linkpdo->query($req);
            echo "Le nom de la récompense a été mis à jour avec la requête : $req";
        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    } elseif (isset($descriptif) && $intitule == null) {
        $req = "UPDATE recompense SET descriptif = '$descriptif' WHERE id_recompense = $id_recompense ";
        try {
            $res = $linkpdo->query($req);
            echo "La description de la récompense a été mise à jour avec la requête : $req";
        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    } elseif (isset($intitule) && isset($descriptif)) {
        $req = "UPDATE recompense SET intitule = '$intitule', descriptif = '$descriptif' WHERE id_recompense = $id_recompense ";
        try {
            $res = $linkpdo->query($req);
            echo "Le nom et la description de la récompense ont été mis à jour avec la requête : $req";
        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    } else {
        echo "Aucune modification n'a été effectuée";
    }
    header("Location: page_recompense.php");
}

if (isset($_GET['id_suppr'])) {
    $id_suppr = $_GET['id_suppr'];
    $req = "DELETE FROM lier WHERE id_recompense = $id_suppr;
        DELETE FROM recompense WHERE id_recompense = $id_suppr;";
    try {
        $res = $linkpdo->query($req);
        echo "La récompense a été supprimée";
        header("Location: page_recompense.php");
    } catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }
    echo "La récompense a été supprimée avec la requête : $req";
}

if (isset($_GET['id_add'])) {
    $id_add = $_GET['id_add'];

    if ($_FILES['image_recompense_add']['size'] > 0) {
        // Changer le nom de l'image dans la base de données
        $image_recompense = uploadImage($_FILES['image_recompense_add']);
    }
   
    $intitule_add= $_POST['nom_recompense_add'];
    $descriptif_add = $_POST['descriptif_recompense_add'];

    $req = "INSERT INTO recompense (intitule, descriptif, lien_image) VALUES ('$intitule_add', '$descriptif_add', '$image_recompense');
    INSERT INTO lier (id_objectif, id_recompense) VALUES ($id_add, (SELECT MAX(id_recompense) FROM recompense));";

   try {
        $res = $linkpdo->query($req);
        echo "La récompense a été ajoutée";
    } catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }
    echo "La récompense a été ajoutée avec la requête : $req";



}
