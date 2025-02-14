<?php
/**
 * @file creation_recompense_unique.php
 * @brief Page de création d'une récompense de type unique
 * @details Page de création d'une récompense de type unique, permet à l'utilisateur de créer une récompense de type unique
 * @version 1.0
 */
require_once ('fonctions.php'); //utilisation des fonctions de la page fonctions.php
is_logged(); //vérifie si l'utilisateur est connecté
is_validateur(); //vérifie si l'utilisateur est un validateur

?>
<!DOCTYPE html>
<html lang="fr" style="font-family: raleway-extrabold,Helvetica,Arial,Lucida,sans-serif;">

<?php
$linkpdo = connexionBd();
?>

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
        <form action="insert_recompense_bd.php" enctype="multipart/form-data" method="post">
            <h1 class="flex-simple">Création d'une récompense de type : "unique"</h1>

            <div class="flex_simple">
                <label>Quel sera le nom de la récompense : </label>
                <input type="text" width=100% name="intitule" placeholder="Nom" required="required">
            </div>

            <div class="flex_simple">
                <label>Quelle sera la déscription de la récompense : </label>
                <input type="text" name="descriptif" placeholder="Détail">
            </div>

            <div class="flex_simple">
                <label>Quelle sera l'image associée à cette récompense : </label>
                <input name="photo_recompense" type="file" class="zip_input" required="required">
            </div>


            <div class="bouton-systeme">
                <a class="annuler" href="page_creatsystem.php?id=<?php echo $_SESSION['id_enfant'] ?>">Annuler &#x1F5D9;</a>
                <input class="valider" style="float:right;" type="submit" value="Valider  &#x2714;">
            </div>
        </form>

    </main>
</body>

</html>