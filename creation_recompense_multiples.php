<?php
/**
 * @file creation_recompense_multiples.php
 * @brief Page de création de récompenses multiples
 * @details Page de création de récompenses multiples, permet à l'utilisateur de créer des récompenses multiples
 * @version 1.0
 */
require_once ('fonctions.php'); //utilisation des fonctions de la page fonctions.php
is_logged(); //vérifie si l'utilisateur est connecté
is_validateur(); //vérifie si l'utilisateur est un validateur
$linkpdo = connexionBd() //connexion à la base de données

?>



<!DOCTYPE html>
<html lang="fr" style="font-family: raleway-extrabold,Helvetica,Arial,Lucida,sans-serif;">

<head>
    <meta charset="utf-8">
    <title> Récompense </title>
    <link rel="stylesheet" href="style_css/style_creation_systemes.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script type="text/javascript" src="js/script.js"></script>
</head>

<body>

    <!--HEADER-->
    <?php create_header($linkpdo); //création du header

?>

    <!----------------------------------------- Contenu ------------------------------------------------>
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
                <label>Combien de récopmpenses voulez vous inserer ? : </label>
                <div class="heure">
                    <input class='insere_nb' #x1F5D9min="0" max="15" type="number" name="rows" value=<?php echo $rows ?>
                        required="required" placeholder="Nombre de récompense ?">
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
                <a class="annuler" href="page_creatsystem.php?id=<?php echo $_SESSION['id_enfant'] ?>">Annuler
                    &#x1F5D9;</a>
                <input class="valider" type="submit" value="Valider  &#x2714;">
            </div>
        </form>

    </main>
</body>



</html>