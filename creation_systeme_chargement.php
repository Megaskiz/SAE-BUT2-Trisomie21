<?php
/**
 * @file creation_systeme_chargement.php
 * @brief Page de création d'un système de type "chargement"
 * @details Page de création d'un système de type "chargement", permet à l'utilisateur de créer un système de type "chargement" et de rentrer ses informations personnelles
 * @version 1.0
 */
require_once ('fonctions.php');
is_logged();
is_validateur();
$linkpdo = connexionBd()
?>
<!DOCTYPE html>
<html lang="fr">




<head>
    <meta charset="utf-8">
    <title> Objectif </title>
    <link rel="stylesheet" href="style_css/style_creation_systemes.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script type="text/javascript" src="js/script.js"></script>
</head>

<body>

    <!--------------------------------------------------------------- header ------------------------------------------------------------------->
    <?php
create_header($linkpdo);
?>


    <!--------------------------------------------------------------- Contenu ------------------------------------------------------------------->

    <main>
        <form action="insert_systeme_bd.php" method="post">
            <h1 class="flex-simple">Création d'un objectif de type : "chargement"</h1>

            <div class="flex_simple">
                <label>Quel est le nom de cet objectif ? : </label>

                <input type="text" name="nom" placeholder="Ecrivez le nom" required="required">
            </div>


            <div class="flex_simple">
                <label>Combien de cases voulez-vous inserer ? : </label>
                <div class="heure">
                    <input type="number" min="0" name="rows" required="required" placeholder="Nombre de cases ?">

                </div>
            </div>

            <div class="flex_simple">
                <label>Quelle sera la condition d'ajout d'un jeton : </label>
                <input type="text" width=100% name="intitule" placeholder="Ce que l'enfant doit faire" required="required">
            </div>

            <div class="flex_simple">
                <label>Quel est la durée de cet objectif ? : </label>
                <div class="heure">
                    <input type="number" min="0" name="duree" placeholder="Indiquez une durée" required="required">
                    <select name="echelle">
                        <option value="h">Heure(s)</option>
                        <option value="h">Jour(s)</option>
                        <option value="s">Semaine(s)</option>
                    </select>
                </div>
            </div>

            <div class="flex_simple">
                <label>Quel est la priorité de cet objectif ? : </label>
                <input type="text" name="prio" placeholder="Quelle est la prioritée ?">
            </div>

            <div class="bouton-systeme">
                <a class="annuler" href="page_creatsystem.php?id=<?php echo $_SESSION['id_enfant'] ?>"> Annuler &#x1F5D9; </a>
                <input class="valider" type="submit" value="Valider &#x2714;">
            </div>
        </form>

    </main>

</body>

</html>