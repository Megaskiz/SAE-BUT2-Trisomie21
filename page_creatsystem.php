<!DOCTYPE html>
<html lang="fr" style="font-family: Arial,sans-serif;">
<html lang="fr">
<?php
require('fonctions.php');
is_logged();
is_validateur();
?>
<?php
///Connexion au serveur MySQL
try {
    $linkpdo = new PDO("mysql:host=localhost;dbname=bddsae", "root", "");
}
///Capture des erreurs éventuelles
catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

?>

<head>
    <title> Administrateur </title>
    <link rel="stylesheet" href="style_css/style_creatsystem.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script type="text/javascript" src="js/script.js"></script>
    <link rel="icon" href="logo/icon-admin.png">
</head>

<body>
    <!--------------------------------------------------------------- header ------------------------------------------------------------------->
    <?php create_header($linkpdo); ?>



    <main>
        <nav class="div-info-enfant">
            <div class="info-enfant">
                <a class="retour" href="page_admin.php?id=<?php echo $_SESSION['id_enfant']  ?>"> <button class="button-retour">Retour</button> </a>
                <?php
                $id = $_SESSION['id_enfant'];
                ///Sélection de tout le contenu de la table carnet_adresse
                try {
                    $res = $linkpdo->query("SELECT * FROM enfant where id_enfant='$id'");
                } catch (Exception $e) { // toujours faire un test de retour au cas ou ça crash
                    die('Erreur : ' . $e->getMessage());
                }



                ///Affichage des entrées du résultat une à une
                while ($data = $res->fetch()) {
                    echo '<div class="div-photo-enfant">';
                    echo "<img class=\"photo-enfant\" src=\"$data[9]\" alt=\"visage de : " . $data['prenom'] . "\">";
                    echo '</div>';
                    $date = strval($data[3]);
                    $datefinal = new DateTime($date);
                    echo (ucfirst(
                        "
                        <div class='id-enfant'> <a class='id-nom'>Nom: <strong> $data[1] </strong></a> </div>
                        <div class='id-enfan'> <a class='id-prenm'>Prénom: <strong> $data[2]  </strong></a> </div>
                        <div class='id-enfant'> <a class='id-age'>Date de naissance: <strong> " . date_format($datefinal, 'd/m/Y') . "</strong></a></div>
                        <div class='id-enfants'> <a class='id-adresse'>Adresse: <strong> $data[5] </strong> </a></div>
                        <div class='id-enfants'> <a class='id-activite'>Handicap : <strong> $data[7] </strong> </a></div>
                        <div class='id-enfants'> <a class='id-activite'>Activité : <strong> $data[6] </strong> </a></div>"
                    )
                    );
                }
                ?>

            </div>
        </nav>
        <div class="choix-systeme">
            <form action="test.php" class="forme titre" method="POST">

                <h2 class="titletype">Choisissez le type d'objectif :</h2>
                <section class="choix-objectif">
                    <input type="radio" name="radio1" id="choix11" value="1" required="required"> <label class="choix11-label four col detail1" for="choix11">
                        <div class="image_product"> <img class="img-systeme" src="img/project_images/img1.png">
                            <p><strong>Objectif Chargement</strong></p>
                        </div>
                    </label>
                    <input type="radio" name="radio1" id="choix13" value="3" required="required"><label class="choix13-label four col detail2" for="choix13">
                        <div class="image_product"> <img class="img-systeme" src="img/project_images/img2.png">
                            <p><strong>Objectif routine</strong></p>
                        </div>
                    </label>
                </section>


                <h2 class="titletype">Choisissez le type de récompense :</h2>
                <section class="choix-recompense">

                    <input type="radio" name="radio2" id="choix21" value="4" required="required"> <label class="choix21-label four col product detail3" for="choix21">
                        <div class="image_product"> <img class="img-systeme" src="img/project_images/img3.png">
                            <p><strong>Récompense Unique</strong></p>
                        </div>
                    </label>
                    <input type="radio" name="radio2" id="choix22" value="5" required="required"> <label class="choix22-label four col detail4" for="choix22">
                        <div class="image_product"> <img class="img-systeme" src="img/project_images/img4.png">
                            <p><strong>Récompense Boutique</strong></p>
                        </div>
                    </label>
                </section>

                <div class="bouton-objectif">
                    <button type="button" class="annuler" onclick="window.location.href='page_creatsystem.php?id=<?php echo $id ?>'"> Annuler &#x1F5D9;</button>
                    <input class="valider" type="submit" value="Valider &#x2714;">
                </div>
            </form>
        </div>

    </main>

</html>