<?php
require('fonctions.php');
is_logged();
is_validateur();
?>
<!DOCTYPE html>
<html lang="fr" style="font-family: Arial,sans-serif;">

<?php
///Connexion au serveur MySQL
try {
    $linkpdo = new PDO("mysql:host=localhost;dbname=bddsae", "root", "");
}
///Capture des erreurs éventuelles
catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

if (isset($_GET['id_suppr'])) {
    $id_suppression = $_GET['id_suppr'];
    $req_suppr = "DELETE FROM suivre where id_enfant=$id_suppression;DELETE FROM enfant where id_enfant=$id_suppression";
    try {
        $res = $linkpdo->query($req_suppr);
        header('Location: page_admin.php');
    } catch (Exception $e) { // toujours faire un test de retour au cas ou ça crash
        die('Erreur : ' . $e->getMessage());
    }
}

?>

<head>
    <meta charset="utf-8">
    <title> Administrateur </title>
    <link rel="stylesheet" href="style_creation_systemes.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script type="text/javascript" src="script.js"></script>
</head>

<body>


    <header>
        <img class="logo-association" src="/sae-but2-s1/img/logo_trisomie.png" alt="logo de l'association">
        <img class="img-user" src="/sae-but2-s1/img/user_logo.png" alt="tete de l'utilisateur">

        <?php
        $mail =  $_SESSION['login_user'];
        try {
            $res = $linkpdo->query("SELECT nom, prenom FROM membre where courriel='$mail'");
        } catch (Exception $e) { // toujours faire un test de retour au cas ou ça crash
            die('Erreur : ' . $e->getMessage());
        }

        ///Affichage des entrées du résultat une à une

        $double_tab = $res->fetchAll(); // je met le result de ma query dans un double tableau
        $nombre_ligne = $res->rowCount(); // =2 car il y a 2 ligne dans ma base
        $liste = array();
        echo "<table>";

        for ($i = 0; $i < $nombre_ligne; $i++) {
            echo "<tr>";
            for ($y = 0; $y < 2; $y++) {
                echo "<td>";
                print_r($double_tab[$i][$y]);
                $liste[$y] = $double_tab[$i][$y];
                echo "</td>";
            }

            echo "</tr>";
        }

        echo "</table>";
        ?>
        <div onclick="window.location.href ='logout.php';" class="h-deconnexion">
           <img class="img-deco" src="img/deconnexion.png" alt="Déconnexion"> Déconnexion
        </div>
    </header>


    <!--------------------------------------------------------------- Contenu ------------------------------------------------------------------->

    <main>

        <?php

        if (isset($_POST['rows'])) {
            $rows = $_POST['rows'];
        } else {
            $rows = 1;
        }
        ?>



        <form action="" method="post" class="form-nb-taches">
            <div class="flex_simple">
                <label>combien de taches voulez vous inserer ? : </label>
                <div class="heure">
                <input type="number" name="rows" value=<?php echo $rows ?> required="required" placeholder="Nombre de récompense ?">
                <input id="bouton" type="submit" value="valider">
                </div>
        </form>

        <form action="insert_recompense_bd.php" enctype="multipart/form-data" method="post">
            <?php
            echo '<input style="display:none"type="text" name="nb_rec" value="' . $rows . '">';
            $i = 1;
            while ($i <= $rows) {
                echo '

                    <h1 class="flex-recompense">Récompense n°' . $i . '</h1>

                    <div class="flex_simple1">
                    <label>Quel sera le nom de la récompense : </label>
                    <input type="text" width=100% name="intitule' . $i . '" placeholder="Ecrivez le nom"  required="required">
                    </div>  

                    <div class="flex_simple1">
                        <label>Quelle sera la déscription de la récompense  : </label>
                        <input type="text" name="descriptif' . $i . '" placeholder="Détail">
                    </div>

                    <div class="flex_simple1">
                    <label>Quelle sera l\'image associée à cette récompense : </label>
                    <input name="photo_recompense' . $i . '" type="file" class="zip_input" required="required">
                    </div>';

                $i++;
            }
            ?>
            <div class="bouton-systeme">
                <a class="annuler" href="page_creatsystem.php?id=<?php echo $_SESSION['id_enfant'] ?>">Annuler &#x1F5D9;</a>
                <input class="valider" type="submit" value="Valider  &#x2714;">
            </div>
        </form>

    </main>
</body>



</html>