<!DOCTYPE html>
<html lang="fr">

<?php
require('fonctions.php');
is_logged();
is_validateur();
try {
    $linkpdo = new PDO("mysql:host=localhost;dbname=bddsae", "root", "");
}
///Capture des erreurs éventuelles
catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}
if (!$_SESSION['logged_user']) {
    header('Location: html_login.php');
}

if (isset($_GET['id'])) {
    echo '<label> ca marche la </label>';
}


try {
    $res = $linkpdo->query("SELECT * FROM `membre` WHERE compte_valide= 1;");
} catch (Exception $e) { // toujours faire un test de retour en cas de crash
    die('Erreur : ' . $e->getMessage());
}

$double_tab = $res->fetchAll(); // je met le result de ma query dans un double tableau
$nombre_ligne = $res->rowCount();
$liste = array();
echo "<table class='no-break'>";

for ($i = 0; $i < $nombre_ligne; $i++) {
    echo "<tr>";
    for ($y = 1; $y < 3; $y++) {
        echo "<td>";
        print_r($double_tab[$i][$y]);
        $liste[$y] = $double_tab[$i][$y];
        $nom = $double_tab[$i][1];
        $prenom = $double_tab[$i][2];

        echo "</td>";
    }
    $identifiant = $double_tab[$i][0];

    echo '<td>';
    echo "</div>";
    echo '</td>';
    echo "<td class=\"Profil\" >";
?>
    <form action="groupe_validation.php" method="post">
        <input type="hidden" name="id_enfant" value="<?php echo $_GET['id']; ?>">
        <input type="hidden" name="id_membre" value="<?php echo $double_tab[$i][0]; ?>">
        <input type="submit" value="Ajouter">
    </form>
<?php

    echo "</td>";
    echo "</tr>";
}
echo "</table>";
?>