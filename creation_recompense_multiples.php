<?php
require('fonctions.php');
is_logged();
is_validateur();
?>
<!DOCTYPE html>
<html lang="fr" style="font-family: Arial,sans-serif;">

<?php
///Connexion au serveur MySQL
$linkpdo = connexionBd()
?>

<head>
    <meta charset="utf-8">
    <title> Administrateur </title>
    <link rel="stylesheet" href="style_creation_systemes.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script type="text/javascript" src="script.js"></script>
</head>

<body>

<!--------------------------------------------------------------- header ------------------------------------------------------------------->
<?php
create_header($linkpdo);
?>


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
                <label>combien de récopmpenses voulez vous inserer ? : </label>
                <div class="heure">
                <input  class='insere_nb' #x1F5D9min="0"  max="15" type="number" name="rows" value=<?php echo $rows ?> required="required" placeholder="Nombre de récompense ?">
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