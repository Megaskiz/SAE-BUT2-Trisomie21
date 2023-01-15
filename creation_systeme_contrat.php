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
            $rows = 0;
        }
        ?>


        <form action="" method="post" class="form-nb-taches">
            <h1 class="flex-simple">Création d'un système de type : "Contrat"</h1>
            <div class="flex_simple">
                <label>combien de tâches voulez vous inserer ? : </label>
                <div class="heure">
                <input class='insere_nb' type="number" min="0" max="15" name="rows" required="required"  placeholder="Nombre de tâche ?">
                <input id="bouton" type="submit" value="valider">
                </div>
            </div>
        </form>

        <form action="insert_systeme_bd.php" method="post">

            <div class="flex_simple">
                <label>Quel est le nom de ce système ? : </label>
                <input type="text" name="nom" placeholder="Ecrivez le nom" required="required">
            </div>


            <div class="flex_simple">
                <label>Quel est la durée de ce système ? : </label>
                <div class="heure">
                    <input  class='insere_nb' type="number" min="0" name="duree" placeholder="Indiquez une durée" required="required">
                    <select name="echelle">
                        <option value="h">heure(s)</option>
                        <option value="h">jour(s)</option>
                        <option value="s">semaine(s)</option>
                    </select>
                </div>
            </div>

            <div class="flex_simple">
                <label>Quel est la priorité de ce système ? : </label>
                <input type="text" name="prio" placeholder="Quelle est la prioritée ?">
            </div>


            <table class="tableau">
                <tr class="jour">
                    <td>
                        <p></p>
                    </td>
                    <td>
                        <p>Lundi</p>
                    </td>
                    <td>
                        <p>Mardi</p>
                    </td>
                    <td>
                        <p>Mercredi</p>
                    </td>
                    <td>
                        <p>Jeudi</p>
                    </td>
                    <td>
                        <p>Vendredi</p>
                    </td>
                    <td>
                        <p>Samedi</p>
                    </td>
                    <td>
                        <p>Dimanche</p>
                    </td>
                </tr>

                <?php
                $i = 1;
                while ($i <= $rows) {
                    echo "<tr>";
                    echo "<td><input type='text' name='tache$i.0000000'placeholder='tâche à faire'/ required='required'></td>";
                    echo "<td></td>";
                    echo "<td></td>";
                    echo "<td></td>";
                    echo "<td></td>";
                    echo "<td></td>";
                    echo "<td></td>";
                    echo "<td></td>";
                    echo "</tr>";
                    $i++;
                }
                ?>
            </table>

            <div class="bouton-systeme">
                <a class="annuler" href="page_creatsystem.php?id=<?php echo $_SESSION['id_enfant'] ?>">Annuler &#x1F5D9;</a>
                <input class="valider" type="submit" value="Valider  &#x2714;">
            </div>
        </form>

    </main>

</body>



</html>