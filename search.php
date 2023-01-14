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
    <title>Administrateur²</title>
    <link rel="stylesheet" href="style_admin.css">
    <script type="text/javascript" src="script.js"></script>
</head>

<body>


    <header>
        <img class="logo-association" src="/sae-but2-s1/img/logo_trisomie.png" alt="logo de l'association">
        <img class="img-user" src="/sae-but2-s1/img/user_logo.png" alt="photo du visage de l'utilisateur">

        <?php
        $mail =  $_SESSION['login_user'];
        try {
            $res = $linkpdo->query("SELECT nom, prenom FROM membre where courriel='$mail' ORDER BY nom;");
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
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="icone_deconnexion">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
            </svg> Déconnexion
        </div>

    </header>
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
                echo '<button class="ajouter-enfant" type="button" onclick="openDialog(\'dialog1\', this)">Ajouter un profil
                 <svg  class="icone-ajouter-enfant" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6" onclick="openDialog(\'dialog1\', this)">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z" />
                </svg> </button>';





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
            echo "<div class=\"recherche\"><form method=\"post\" action=\"search.php\">
            <div class=\"\">
            <input class=\"input_recherche\" type=\"text\" placeholder=\"Mots-clés ...\" id=\"keywords\" name=\"keywords\" required> 
            </div>
            <input class=\"bouton_recherche\" type=\"submit\" value=\"Rechercher\">
            </form>
            </div>";
            echo "<table >";

            if (isset($_POST['keywords'])) {
                // Préparation de la requête
                $search = implode(array_filter(str_split($_POST['keywords']),"filter_spaces"));
                $keywords = explode(" ",$search );
                $query = "SELECT * FROM enfant WHERE ";

                for ($i = 0; $i < count($keywords); $i++) {
                    $query .= "nom LIKE '%$keywords[$i]%' OR prenom LIKE '%$keywords[$i]%'; ";
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
                        echo "<td>" . $contact['nom'] . "</td>";
                        echo "<td>" . $contact['prenom'] . "</td>";
    
                        echo "<td>";
                        echo '<a href="page_admin.php?id=' . $contact['id_enfant']. '"><button  class="acceder-information-enfant">  Acceder &#x1F59D; </button> </a>';
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