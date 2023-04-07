<!DOCTYPE html>
<html lang="fr" style="font-family: Arial,sans-serif;">

<?php
/**
 * @file archive_sys.php
 * @brief Page d'archivage d'un système
 * @details Page d'archivage des système, permet à l'utilisateur de mettre un système en archive ou de le remettre en ligne ou de le supprimer
 */


require_once('fonctions.php');//    utilisation des fonctions de la page fonctions.php
is_logged();//vérifie si l'utilisateur est connecté
is_validateur(); //vérifie si l'utilisateur est un validateur

$linkpdo = connexionBd();//   connexion à la base de données

if (isset($_GET['id_putback'])) {//    remettre un système en ligne

    $req = $linkpdo->prepare('UPDATE objectif SET visibilite = "0" where id_objectif = ' . $_GET['id_putback']);

    if ($req == false) {
        die("erreur linkpdo");
    }

    try {

        $req->execute(array());
        header("Location:index.php?id=" . $_SESSION['id_enfant']);

        if ($req == false) {
            $req->debugDumpParams();
            die("erreur execute");
        } else {
            echo "<a href=\"index.php?id=" . $_SESSION['id_enfant'] . "\"> recharger la page</a>";
        }
    } catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }
}
?>

<head>
    <meta charset="utf-8">
    <title> Menu principal </title>
    <link rel="stylesheet" href="style_css/style_index.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script type="text/javascript" src="js/script.js"></script>
</head>

<body>
    <!--- HEADER -->
	<?php	create_header($linkpdo); ?>


    <!--------------------------------------------------------------- Contenu ------------------------------------------------------------------->

    <!--------------------------------------- menu liste enfant (gauche) -------------------------------------------->
    <main>

        <nav class="left-contenu">
            <div style="display: flex;     margin: 3%;">

                <a class="retour" href="index.php"> Retour</a>

            </div>
            <?php
            $id = $_SESSION['id_enfant'];

            try {
                $res = $linkpdo->query("SELECT * FROM enfant where id_enfant='$id'");
            } catch (Exception $e) {
                die('Erreur : ' . $e->getMessage());
            }

            while ($data = $res->fetch()) { //affichage des données de l'enfant


                echo "<center><img class=\"photo-enfant\" src=\"$data[9]\" alt=\"Tête de l'enfant\"></center>";

                $date = strval($data[3]);
                $datefinal = new DateTime($date);
                echo (ucfirst(
                    "
                        <center>
                        <p style=\"margin:5%;\">Nom: <strong> $data[1] </strong></p>
                        <p style=\"margin:5%;\">Prénom: <strong> $data[2]  </strong></a> </div>
                        <p style=\"margin:5%;\">Date de naissance: <strong> " . date_format($datefinal, 'd/m/Y') . "</strong></p>
                        <p style=\"margin:5%;\">Adresse: <strong> $data[5] </strong> </p>
                        <p style=\"margin:5%;\">Handicap : <strong> $data[7] </strong> </p>
                        <p style=\"margin:5%;\">Activité : <strong> $data[6] </strong> </p>
                        </center>"

                )
                );
            }
            ?>

        </nav>

        <?php

        if (isset($_GET['id'])) {//    affichage des systèmes de l'enfant
            $id = $_GET['id'];
            $_SESSION["id_enfant"] = $id;




            try {
                $res = $linkpdo->query("SELECT * FROM enfant where id_enfant='$id'");
            } catch (Exception $e) {
                die('Erreur : ' . $e->getMessage());
            }

            $double_tab = $res->fetchAll();
            $nombre_ligne = $res->rowCount();
            $liste = array();


            $id_enfant = $double_tab[0][0];
            $nom_enfant = ucfirst($double_tab[0][1]);
            $prenom_enfant = ucfirst($double_tab[0][2]);
            $ddn_enfant = date_format(new DateTime(strval($double_tab[0][3])), 'd/m/Y');
            $lien_jeton_enfant = $double_tab[0][4];
            $adresse = $double_tab[0][5];
            $activite = $double_tab[0][6];
            $handicap = $double_tab[0][7];
            $info_sup = $double_tab[0][8];
            $photo_enfant = $double_tab[0][9];




            try {
                $res = $linkpdo->query("SELECT * FROM suivre natural join membre  where id_enfant='$id'");
            } catch (Exception $e) {
                die('Erreur : ' . $e->getMessage());
            }




            $double_tab_tuteur = $res->fetchAll();
            $nombre_ligne = $res->rowCount();
            $liste = array();

            echo "</div>";
        }


        ?>




        <!--------------------------------------- menu information sur l'enfant (droite) -------------------------------------------->
        <nav class="right-contenu">
            <?php
            echo "<section style=\"height: 100%;\" class=\"nb-systeme\">";


            try {
                $res = $linkpdo->query('SELECT intitule, nb_jetons, duree, priorite, travaille, id_objectif FROM objectif where visibilite=1 and id_enfant=' . $_SESSION["id_enfant"] . ' ORDER BY priorite ');
            } catch (Exception $e) {
                die('Erreur : ' . $e->getMessage());
            }




            $double_tab = $res->fetchAll();
            $nombre_ligne = $res->rowCount();
            $liste = array();

            echo "<table class='affichage-objectif'>";

            echo "<tr class='titre-objectif'>
                        <th>Nom</th>
                        <th>Jetons</th>
                        <th>Durée</th>
                        <th class='sms'>Message</th>
                        <th>Accéder</th>
                        <th class='sup'>Ré-instaurer</th>
                        <th class='sup'>Supprimer définitivement</th>
                        </tr>";

            for ($i = 0; $i < $nombre_ligne; $i++) {//affichage des objectifs
                //acces au systèmes
                if ($_SESSION["role_user"] == 1 || $double_tab[$i][4] == 1 or $_SESSION["role_user"] == 3) {
                    echo "<tr >";

                    #affiche nom
                    echo "<td>";
                    print_r(htmlspecialchars($double_tab[$i][0]));
                    echo "</td>";

                    #affiche nombre de jeton
                    echo "<td>";
                    echo "<center>";
                    print_r(htmlspecialchars($double_tab[$i][1]));
                    echo "</center>";
                    echo "</td>";

                    #affiche nombre de jour
                    echo "<td>";
                    $value = $double_tab[$i][2];
                    switch ($double_tab[$i][2]) {
                        case ($value < 24 ? $value : !$value):
                            echo "<center>";
                            print_r($double_tab[$i][2]);
                            echo " Heure(s)";
                            echo "</center>";
                            break;

                        case ($value < 24 * 7 ? $value : !$value):
                            $reste = $value % 24;
                            $jours = intdiv($value, 24);
                            echo "<center>";
                            echo $jours . " jour(s), " . $reste . " heure(s)";
                            echo "</center>";
                            break;

                        default:
                            $semaines = intdiv($value, (7 * 24));
                            $reste1 = $value % (7 * 24); // pour savoir s'il reste quoi que ce soit 
                            echo "<center>";
                            echo $semaines . " semaine(s) ";
                            echo "</center>";

                            if ($reste1 > 23) { // il reste + d'un jour
                                $restej = $value - (7 * 24); // le nombre d'heure au dela d'une semaine
                                if ($reste1 > 23) { // si ce nombre d'heure au dela d'une semaine dépasse 1 jour
                                    $restejours = intdiv($reste1, 24);
                                    echo "<center>";
                                    echo $restejours . "jour(s)";
                                    echo "</center>";
                                }
                            } elseif ($reste1 > 0) { // s'il reste entre 1 et 23heures
                                echo "<center>";
                                echo $reste1 . "heure(s)";
                                echo "</center>";
                            }
                            break;
                    }





                    echo "</td>";


                    #affiche message
                    echo "<td class='sms'>";
                    echo "<center>";
                    echo "<button style=\"background-color:gray;\" type=\"button\" onclick=\"', this)\"> <span class=\" icon-mail\"> Messagerie &#128172; </span></button>";
                    echo "</center>";
                    echo "</td>";



                    echo "<td>";
                    echo "<center>";
                    echo '<a  ><button style="background-color:gray;" class="objectif-acceder" > Acceder </button></a>';
                    echo "</center>";
                    echo "</td>";

                    echo "<td>";
                    echo " <div class=\"case-enfant\">";
                    echo "</div>";

                    echo "<center>";
                    echo '<button class="button_ajouter-objectif" type="button" onclick="openDialog(\'dialog6\', this)">Dé-archiver cet objectif</button>';
                    echo '<div role="dialog" id="dialog6" aria-labelledby="dialog1_label" aria-modal="true" class="hidden">';
                    echo "<p class='popup-txt'> Voulez vous rendre de nouveau utilisable cet objectif ? Il sera visible par tous les membres suivant cet enfant et apparaitra dans les statistiques  </p>";

                    echo ' <div style="display:flex; justify-content: space-evenly;" >';
                    echo '  <button class="popup-btn" type="button" onclick="closeDialog(this)">Annuler</button>';
                    echo '   <a  href="archive_sys.php?id_putback=' . $double_tab[$i][5] . '"> <button class="popup-btn"> Valider </button> </a>';
                    echo "</div>";

                    echo "</div>";
                    echo "</center>";



                    echo "</div>";




                    echo "</div>";
                    echo "</div>";
                    echo "</td>";
                    echo "<td>";
                    echo " <div class=\"case-enfant\">";
                    echo "</div>";

                    echo "<center>";
                    echo '<button class="button_ajouter-objectif" type="button" onclick="openDialog(\'dialog7\', this)">Supprimer cet objectif</button>';
                    echo '<div role="dialog" id="dialog7" aria-labelledby="dialog1_label" aria-modal="true" class="hidden">';
                    echo "<p class='popup-txt'> Voulez vous supprimer définitivement cet objectif ? cela le retirera des statistiques, supprimera tous ses messages, supprimera aussi toutes ses récompenses.  </p>";

                    echo ' <div style="display:flex; justify-content: space-evenly;" >';
                    echo '  <button class="popup-btn" type="button" onclick="closeDialog(this)">Annuler</button>';
                    echo '   <a href="appel_fonction.php?appel=supprime_objectif&id_sys=' . $double_tab[$i][5] . '"> <button class="popup-btn"> Valider </button></a>';
                    echo "</div>";

                    echo "</div>";
                    echo "</center>";



                    echo "</div>";




                    echo "</div>";
                    echo "</div>";
                    echo "</td>";

                    echo "</tr>";
                }
            }
            echo "</table>";


            $res->closeCursor();



            echo "</section>";


            // Popup Ajouter equipier 


            echo '<div role="dialog" id="dialog2" aria-labelledby="dialog1_label" aria-modal="true" class="hidden">';
            //echo '<form enctype="multipart/form-data" action="groupe_validation.php" method="post" class="dialog_form">';


            try {
                $res = $linkpdo->query("SELECT membre.* FROM membre LEFT JOIN suivre ON membre.id_membre = suivre.id_membre AND suivre.id_enfant = '$getid' WHERE membre.compte_valide = 1 AND suivre.id_membre IS NULL ORDER BY nom;");
            } catch (Exception $e) {
                die('Erreur : ' . $e->getMessage());
            }

            while ($tuteur = $res->fetch()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($tuteur['nom']) . "&nbsp" . "</td>";
                echo "<td>" . htmlspecialchars($tuteur['prenom']) . "</td>";
                echo "<td class='Profil'>";
                echo "<form action='groupe_validation.php?id_enfant=$getid&id_membre=$tuteur[id_membre]' method='post'>";

                echo "<button type='submit'>Ajouter</button>";
                echo "</form>";
                echo "<br>";
                echo "</td>";
                echo "</tr>";
            }

            echo "</table>";

            echo '<button type="button" onclick="closeDialog(this)">Annuler</button>';

            //echo "</form";
            echo "</div";
            ?>
        </nav>
    </main>


</body>



</html>