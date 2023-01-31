<!DOCTYPE html>
<html lang="fr" style="font-family: Arial,sans-serif;">

<?php
require('fonctions.php');
is_logged();
is_validateur();
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
    <meta charset="UTF-8">
    <title>Administrateur</title>
    <link rel="stylesheet" href="style_admin.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script type="text/javascript" src="script.js"></script>
</head>

<body>
        <!--------------------------------------------------------------- header ------------------------------------------------------------------->
<?php create_header($linkpdo);?>


    <main>
        <nav class="left-contenu">
            <ul class="scrolling-tabs nav-links gl-display-flex gl-flex-grow-1 gl-w-full nav gl-tabs-nav nav gl-tabs-nav">
                <li class="nav-item">
                    <a class="shortcuts-activity nav-link gl-tab-nav-item active gl-tab-nav-item-active" data-placement="" href="page_admin.php">Enfant</a>
                </li>
                <?php
                if ($_SESSION["role_user"] == 1 or $_SESSION["role_user"] == 2) {

                    echo '<li class="nav-item">';
                    echo '<a data-placement="" class="nav-link gl-tab-nav-item" href="page_certif_compte.php">Membre</a>';
                    echo '</li>';
                }

                ?>
               
            </ul>
            <?php

            if ($_SESSION["role_user"] == 1) {

                //Le bloc suivant est la fenêtre pop-in de l'ajout d'enfant, elle est caché tant qu'on appuie pas sur le bouton "ajouter enfant"
                echo '<div class="bouton_enfant">';
                echo '<button class="ajouter-enfant" type="button" onclick="openDialog(\'dialog1\', this)"> Ajouter un profil <img class="icone-ajouter-membre" src="img/ajouter-utilisateur.png" > </button>';
                echo '<div id="dialog_layer" class="dialogs">';
                echo '<div role="dialog" id="dialog1" aria-labelledby="dialog1_label" aria-modal="true" class="hidden">';
                echo '<h2 id="dialog1_label" class="dialog_label">Ajouter un profil d\'enfant</h2>';
                echo '<form enctype="multipart/form-data" action="insert_enfant.php" method="post" class="dialog_form">';
                echo '<div class="dialog_form_item">';
                echo '<label>';
                echo '<span class="label_text">nom :</span>';
                echo '<input name="nom" type="text" required="required">';
                echo '</label>';
                echo '</div>';
                echo '<div class="dialog_form_item">';
                echo '<label>';
                echo '<span class="label_text">prenom:</span>';
                echo '<input name="prenom" type="text" class="city_input" required="required">';
                echo '</label>';
                echo '</div>';
                echo '<div class="dialog_form_item">';
                echo '<label>';
                echo '<span class="label_text">date de naissance:</span>';
                echo '<input name="date_naissance" type="date" class="state_input" required="required">';
                echo '</label>';
                echo '</div>';
                echo '<div class="dialog_form_item">';
                echo '<label>';
                echo '<span class="label_text">jeton:</span>';
                echo '<input name="lien_jeton" type="file" class="zip_input" required="required">';
                echo '</label>';
                echo '<label>';
                echo '<span class="label_text">photo:</span>';
                echo '<input name="photo_enfant" type="file" class="zip_input" required="required">';
                echo '</label>';
                echo '</div>';
                echo '<div class="dialog_form_actions">';
                echo '<button type="submit">Valider l\'ajout</button>';
                echo '<button type="button" onclick="closeDialog(this)">Annuler</button>';
                echo '</div>';
                echo '</form>';
                echo '</div>';
                echo '</div>';
                echo '</div>';

                /* fin de la fenêtre popin de l'ajout d'enfant" */
            }
            ?>


            <?php
            ///Sélection de tout le contenu de la table enfant
            if ($_SESSION["role_user"] != 2) {
                echo "<table >";

            ?>
                <div class="resultat">
                    <?php
                    echo "<div class='liste-enfant'>";
                    echo "<div class=\"recherche\">
                    <form class='recherche' method=\"post\" action=\"search.php\">
                    <div>
                    <input class=\"input_recherche\" type=\"text\" placeholder=\"Mots-clés ...\" id=\"keywords\" name=\"keywords\" required> 
                    </div>
                    <input class=\"bouton_recherche\" type=\"submit\" value=\" &#x1F50E;\">
                    </form>
                    <a href=\"page_admin.php\"><button class=\"bouton_recherche_R\">&#x27F2;</button></a>
                    </div>";
                    echo "<table >";


                    if (isset($_POST['keywords'])) {
                        // Préparation de la requête
                        $search = implode(array_filter(str_split($_POST['keywords']), "filter_spaces"));
                        $keywords = explode(" ", $search);
                        $query = "SELECT * FROM enfant WHERE visibilite=0 and  ";

                        for ($i = 0; $i < count($keywords); $i++) {
                            $query .= "nom LIKE '%$keywords[$i]%' OR prenom LIKE '%$keywords[$i]%' and visibilite=0 ; ";
                        }
                        // Exécution de la requête
                        $stmt = $linkpdo->query($query);


                        $contacts = $stmt->fetchAll();

                        // Affichage des résultats
                        if (count($contacts) > 0) {
                            echo "<p class=\"titre_recherche\">" . count($contacts) . " résultat(s) trouvé(s) :</p>";
                            echo "<table>";
                            echo "<tr>
                        </tr>";
                            foreach ($contacts as $contact) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($contact['nom']) . "</td>";
                                echo "<td>" . htmlspecialchars($contact['prenom']) . "</td>";

                                echo "<td>";
                                echo '<a href="page_admin.php?id=' . $contact['id_enfant'] . '"><button  class="acceder-information-enfant">Acceder</button> </a>';
                                echo "</td>";
                                echo "</tr>";
                            }
                            echo "</table>";
                        } else {
                            echo "<p class=\"titre_recherche\">Aucun enfants trouvé.<p>";
                        }
                    }

                    ?>
                </div>
            <?php



                echo "</table>";

                ///Fermeture du curseur d'analyse des résultats
                $res->closeCursor();
            }
            ?>
        </nav>
        <nav class="right-contenu">
            <div class="section_enfant">
            </div>
            <section class="nb-systeme">
            </section>
        </nav>
    </main>
</body>

</html>