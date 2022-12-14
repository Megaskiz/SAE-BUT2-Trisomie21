<?php
require('fonctions.php');
is_logged();
?>
<!DOCTYPE html>
<html lang="fr">

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
        <p class="h-deconnexion"><button class="deco" onclick="window.location.href ='logout.php';">Déconnexion</button></p>
    </header>


    <!--------------------------------------------------------------- Contenu ------------------------------------------------------------------->

    <main>
    <?php
if (isset ($_POST['rows']))  {
    $rows = $_POST['rows'];
}
else{
    $rows = 0;
}
?>
<style>
    table, td {
    border-collapse:collapse;
    border:solid black 1px;
    padding: 1rem;
    }
    form {
        max-width:1000px;
        margin:auto;
    }
    label {
        display:block;
        background: rgb(212, 210, 210);
        padding:10px;
    }
</style>
        <form action="" method="post" class="form-nb-taches">
            <div class="flex_simple">
            <label>combien de taches voulez vous inserer ? : </label>
            <input type="number" name="rows"  value=<?php echo $rows ?> >
            <input id="bouton" type="submit" value="valider">
            </div>
            
        </form>
        <form action="insert_systeme_bd.php" method="post">
            <div class="flex_simple">
            <label>Quel est le nom de ce système ?  : </label>
            <input type="text" name="nom" placeholder="ecrivez le nom ici">
            </div>
            <div class="flex_simple">
            <label>Quel est la durée de ce système ?  : </label>
            <input type="text" name="duree" placeholder="ecrivez la duree ici">
            </div>
            <div class="flex_simple">
            <label>Quel est la priorité de ce système ?  : </label>
            <input type="text" name="prio" placeholder="ecrivez le niveau de priorite ici">
            </div>

            <div class="flex_simple">
            <label>Quelle sera l'image pour le jeton : </label>
            <input type="text" name="prio" placeholder="ecrivez le niveau de priorite ici">
            </div>

            <table class="tableau">
                <tr>
                    <td>
                        <p></p>
                    </td>
                    <td>
                        <p>lundi</p>
                    </td>
                    <td>
                        <p>mardi</p>
                    </td>
                    <td>
                        <p>mercredi</p>
                    </td>
                    <td>
                        <p>jeudi</p>
                    </td>
                    <td>
                        <p>vendredi</p>
                    </td>
                    <td>
                        <p>samedi</p>
                    </td>
                    <td>
                        <p>dimanche</p>
                    </td>
                </tr>

                <?php                         
                $i = 1;
                while( $i <= $rows ) {
                    echo"<tr>";
                    echo"<td><input type='text' name='tache$i.0000000'placeholder='tâche à faire'/ required='required'></td>";
                    echo"<td></td>";
                    echo"<td></td>";
                    echo"<td></td>";
                    echo"<td></td>";
                    echo"<td></td>";
                    echo"<td></td>";
                    echo"<td></td>";
                    echo"</tr>";
                    $i++;
                }
                ?>
                </table>
            <input class="button" type="submit" value="Valider le système">
        </form>
        <br>
        <br>
        <div style="float:right;">
            <button class="annuler"><a href="page_admin.php">annuler la creation de système</a></button>
        </div>
    </main>

    <!------------------------------------------------------- Footer -------------------------------------------------->
    <footer>

        <img class="footer-logo-association" src="/sae-but2-s1/img/logo_trisomie.png" alt="logo de l'association">

        <div class="f-contenu">
            <div class="f-menu">
                <li><p class="f-info">Qui sommes nous ?</p></li>
                <li><p class="f-propos">A propos</p></li>
                <li><p class="f-association">Association</p></li>
            </div>
            <p class="f-copyright">© Copyright 2022 </p>
        </div>
       
        
    </footer>
</body>



</html>