<!DOCTYPE html>
<html lang="fr" style="font-family: Arial,sans-serif;">
<head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="style_choix_sys.css" media="screen" type="text/css" />
        <title>récompenses</title>
    </head>
<?php
require('fonctions.php');
is_logged();
is_validateur();

///Connexion au serveur MySQL
try {
    $linkpdo = new PDO("mysql:host=localhost;dbname=bddsae","root","");
    }
    ///Capture des erreurs éventuelles
catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
    }
?>
<button>
<?php
echo"<a href=\"choix_sys.php?id_sys=".$_SESSION['id_sys']."\">retour</a>";
?>
</button>
<h1>Les récompenses</h1>
<?php

// faire une requete pour avoir l'id de la récompense
// faire en sorte qu'on puisse avoir plusieurs récompenses qu'il faut les mettre dans un tableau genre

try {
    $res = $linkpdo->query("select * from recompense where id_recompense in (SELECT id_recompense FROM lier where id_objectif=".$_SESSION['id_sys'].")");
    }
    catch (Exception $e) { // toujours faire un test de retour en cas de crash
        die('Erreur : ' . $e->getMessage());
    }    
    $double_tab = $res -> fetchAll(); // je met le result de ma query dans un double tableau
    $lignes = $res -> rowCount();
?>
<table>
    <tr>
    <td><p>Nom de la récompense</p></td>
    <td><p>Description de la récompense</p></td>
    <td><p>image de la récompense</p></td>
    
</tr>
<?php
for($i=0; $i<$lignes;$i++){ //affichage des récompenses ATTENTION il faut faire l'affichage de l'image de la récompense
    echo"
    <tr>
    <td><p>".$double_tab[$i][1]."</p></td>
    <td><p>".$double_tab[$i][2]."</p></td>
    <td><p>".$double_tab[$i][3]."</p></td> 
    <td><a href='choix_recompense.php?id_rec=".$double_tab[$i][0]."'>choisir cette récompense</a></td>
    </tr>";
}
?>
</table>
<?php   
//suite
?>