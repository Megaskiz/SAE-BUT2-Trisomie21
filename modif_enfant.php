<?php
echo"on ne devrait pas etre sur cette page";
exit();
?>
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
    <link rel="stylesheet" href="style_css/style_admin_modif_enfant.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script type="text/javascript" src="script.js"></script>
</head>

<body>


        <!--------------------------------------------------------------- header ------------------------------------------------------------------->
<?php create_header($linkpdo);?>


    <!--------------------------------------------------------------- Contenu ------------------------------------------------------------------->

    <!--------------------------------------- menu liste enfant (gauche) -------------------------------------------->
    <main>

        <nav class="left-contenu">
            <ul class="scrolling-tabs nav-links gl-display-flex gl-flex-grow-1 gl-w-full nav gl-tabs-nav nav gl-tabs-nav">
                <li class="nav-item">
                    <a class="shortcuts-activity nav-link gl-tab-nav-item active gl-tab-nav-item-active" data-placement="" href="page_admin.php">Enfant</a>
                </li>
                <li class="nav-item">
                    <a data-placement="" class="nav-link gl-tab-nav-item" href="page_certif_compte.php">Membre</a>
                </li>
            </ul>
            <! -- /* Le bloc suivant est la fenêtre pop-in de l'ajout d'enfant, elle est caché tant qu'on appuie pas sur le bouton "ajouter enfant" */ -->
                <div class="bouton_enfant">

                    <div id="dialog_layer" class="dialogs">
                        <div role="dialog" id="dialog1" aria-labelledby="dialog1_label" aria-modal="true" class="hidden">
                            <h2 id="dialog1_label" class="dialog_label">Ajouter un enfant</h2>
                            <form enctype="multipart/form-data" action="insert_enfant.php" method="post" class="dialog_form">
                                <div class="dialog_form_item">
                                    <label>
                                        <span class="label_text">nom :</span>
                                        <input name="nom" type="text" required="required">
                                    </label>
                                </div>
                                <div class="dialog_form_item">
                                    <label>
                                        <span class="label_text">prenom:</span>
                                        <input name="prenom" type="text" class="city_input" required="required">
                                    </label>
                                </div>
                                <div class="dialog_form_item">
                                    <label>
                                        <span class="label_text">date de naissance:</span>
                                        <input name="date_naissance" type="date" class="state_input" required="required">
                                    </label>
                                </div>
                                <div class="dialog_form_item">
                                    <label>
                                        <span class="label_text">jeton:</span>
                                        <input name="lien_jeton" type="file" class="zip_input" required="required">
                                    </label>
                                </div>

                                <div class="dialog_form_actions">
                                    <button type="submit">Valider l'ajout</button>
                                    <button type="button" onclick="closeDialog(this)">Annuler</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <! -- /* fin de la fenêtre popin de l'ajout d'enfant" */ -->


                    <?php
                    ///Sélection de tout le contenu de la table enfant
                    try {
                        $res = $linkpdo->query('SELECT id_enfant, nom, prenom FROM enfant where visibilite = 0');
                    } catch (Exception $e) { // toujours faire un test de retour en cas de crash
                        die('Erreur : ' . $e->getMessage());
                    }

                    ///Affichage des entrées du résultat une à une

                    $double_tab = $res->fetchAll(); // je met le result de ma query dans un double tableau
                    $nombre_ligne = $res->rowCount();
                    $liste = array();
                    echo "<div class='liste-enfant'>";
                    echo "<table>";


                    for ($i = 0; $i < $nombre_ligne; $i++) {



                        for ($y = 1; $y < 3; $y++) {
                            echo "<td>";
                            print_r(htmlspecialchars($double_tab[$i][$y]));
                            $liste[$y] = $double_tab[$i][$y];
                            $nom = $double_tab[$i][1];
                            $prenom = $double_tab[$i][2];
                            $age = $double_tab[0][$y];
                            echo "</td>";
                        }

                        $identifiant = $double_tab[$i][0];
                        echo "<td>";
                        echo '<a href="page_admin.php?id=' . $identifiant . '"><button  class="acceder-information-enfant">Acceder </button> </a>';
                        echo "</td>";
                        echo "</tr>";
                    }



                    echo "</table>";

                    ///Fermeture du curseur d'analyse des résultats
                    $res->closeCursor();
                    ?>
        </nav>

        <?php // affichage central de la page, avec les informations sur les enfants

        $id = $_SESSION["id_enfant"];




        ///Sélection de tout le contenu de la table 
        try {
            $res = $linkpdo->query("SELECT * FROM enfant where id_enfant='$id'");
        } catch (Exception $e) { // toujours faire un test de retour au cas ou ça crash
            die('Erreur : ' . $e->getMessage());
        }

        $double_tab = $res->fetchAll(); // je met le result de ma query dans un double tableau
        $nombre_ligne = $res->rowCount(); // =1 car il y a 1 ligne dans ma requete
        $liste = array();




        $id_enfant = $double_tab[0][0];
        $nom_enfant = ($double_tab[0][1]);
        $prenom_enfant = $double_tab[0][2];
        $ddn_enfant = date_format(new DateTime(strval($double_tab[0][3])), 'd/m/Y');
        $lien_jeton_enfant = $double_tab[0][4];
        $adresse = $double_tab[0][5];
        $activite = $double_tab[0][6];
        $handicap = $double_tab[0][7];
        $info_sup = $double_tab[0][8];
        $photo_enfant = $double_tab[0][9];
        // echo$id_enfant;
        // echo$nom_enfant;
        // echo$prenom_enfant;
        // echo$ddn_enfant;
        // echo$lien_jeton_enfant;



        try {
            $res = $linkpdo->query("SELECT * FROM suivre natural join membre  where id_enfant='$id'");
        } catch (Exception $e) { // toujours faire un test de retour au cas ou ça crash
            die('Erreur : ' . $e->getMessage());
        }


        ///Affichage des entrées du résultat une à une

        $double_tab_tuteur = $res->fetchAll(); // je met le result de ma query dans un double tableau
        $nombre_ligne = $res->rowCount(); // =2 car il y a 2 ligne dans ma base
        $liste = array();

        // print_r($double_tab_tuteur);
        // exit();

        echo "</div>";




        ?>



        <!--------------------------------------- menu information sur l'enfant (droite) -------------------------------------------->
        <nav class="right-contenu">
            <div class="section_enfant">
                

                <?php


                //<!---- menu droit information sur l'enfant ---->


                echo "<div class='grille2cases'>"; //morceau de gauche (logo enfant + boutons) :



                // case du logo de l'enfant
                echo "<div class='div-modif-photo'>";
                //--------
                echo "<button class='modifier-photo' type=\"button\" onclick=\"openDialog('dialog11', this)\">Modifier la photo</button>";
                echo "<div id=\"dialog_layer\" class=\"dialogs\">";

                echo "<div role=\"dialog\" id=\"dialog11\" aria-labelledby=\"dialog11_label\" aria-modal=\"true\" class=\"hidden\">";

                echo "<h2 id=\"dialog11_label\" class=\"dialog_label\">Modifier la photo</h2>";

                echo "<form enctype=\"multipart/form-data\" action=\"\" method=\"POST\" class=\"dialog_form\">";
                echo "<div class=\"dialog_form_item\">";
                echo "<label><span class=\"label_text\">photo:</span><input name=\"photo_enfant\" type=\"file\" class=\"zip_input\" required=\"required\"></label>";
                echo "</div><div class=\"dialog_form_actions\">";
                echo "  <button class='popup-btn' type=\"button\" onclick=\"closeDialog(this)\">Annuler</button>";
                echo "<button class='popup-btn' type=\"submit\">Valider </button></div></form></div></div>";

                //--------
                echo "<img class=\"logo-enfant\" src=\"$photo_enfant\" alt=\"Photo du visage de ".htmlspecialchars($prenom_enfant)."\">";
                echo "</div>";

                // case 3 boutons : supprimer, modifier, creer systeme
                echo " <div class='div-supprimer-enfant'>";
                // dialog suppression

                echo "<div id=\"dialog_layer\" class=\"dialogs\">";
                echo "<div role=\"dialog\" id=\"dialog5\" aria-labelledby=\"dialog1_label\" aria-modal=\"true\" class=\"hidden\">";
                echo "<p  class='popup-txt'> Attention vous allez masquer l'affichage de cet enfant de l'application ! Il sera accesible seulement par les coordinateurs et adminstrateur ! Êtes vous sur de votre choix ? </p>";
                echo "<div class=\"dialog_form_actions\">";
                echo "<button class='popup-btn' onclick=\"closeDialog(this)\">Annuler</button>";
                echo "<a class='popup-btn' href=\"page_admin.php?id_suppr='$identifiant'\">Valider</a>";
                echo "</div>";
                echo "</div>";
                echo "</div>";


                echo "<button class=\"spprmrenfant\" type=\"button\" onclick=\"openDialog('dialog5', this)\">archiver le profil de l'enfant</button>";



                echo "</div>";

                echo "</div>";


                echo "<form action=\"appel_fonction.php?appel=modif_enfant\" method=\"post\">";

                echo "<div class=\"grille_4_cases\" >"; // partie de droite 'le form' -> 2* '3infos' + tuteurs + info sup


                // case 3 infos : nom, ddn, activité
                echo "<div class=\"case-3-infos\">";
                echo "<div class='element_style' style=\"display:inline-flex; align-items: center;\">";
                echo '<p> Nom :</p><input name=nom_enfant type="text" value="' . htmlspecialchars($nom_enfant) . '">';
                echo "</div>";

                echo "<div  class='element_style' style=\"display:inline-flex; align-items: center;\">";
                echo "<p>Date de Naissance :</p><input name=date_naissance type=\"date\" value=" . htmlspecialchars($double_tab[0][3]) . ">";
                echo "</div>";



                echo "<div  class='element_style' style=\"display:inline-flex; align-items: center;\">";
                echo "<p>Activité enfant :</p><input name=activite type=\"text-area\" value='".htmlspecialchars($activite)."'>";
                echo "</div>";

                echo "</div>";

                // case 3 infos : prenom, adresse, handicap
                echo "<div class=\"case-3-infos\">";

                echo "<div  class='element_style' style=\"display:inline-flex; align-items: center;\">";
                echo "<p>Prénom :</p><input name=prenom_enfant type=\"text\" value='" . htmlspecialchars($prenom_enfant) . "'>";
                echo "</div>";

                echo "<div  class='element_style' style=\"display:inline-flex; align-items: center;\">";
                echo "<p>Adresse enfant :</p><input name=adresse type=\"text\" value='".htmlspecialchars($adresse)."'>";
                echo "</div>";

                echo "<div  class='element_style' style=\"display:inline-flex; align-items: center;\">";
                echo "<p>Handicap enfant :</p><input name=handicap type=\"text\" value='".htmlspecialchars($handicap)."'>";
                echo "</div>";

                echo "</div>";



                // case tuteurs
                echo "<div   class='bouton-valider'>";
                echo "<button class='button-valider-modification' >valider les modifications</button>";
                echo "</div>";

                //case info supp
                echo "<div  class='zone-texte'>";
                echo "<textarea name=info_sup style=\"resize: none\">".htmlspecialchars($info_sup)."</textarea>";
                echo "</div>";


                echo "</div>";

                echo "</form>";

                echo "</div>"; // fin de la div "section-enfant"

                echo "<section class=\"nb-systeme\">";



                // tous les systèmes de l'enfant :

                ///Sélection de tout le contenu de la table enfant
                try {
                    $res = $linkpdo->query('SELECT intitule, nb_jetons, duree, priorite, travaille, id_objectif FROM objectif where id_enfant=' . $id);
                } catch (Exception $e) { // toujours faire un test de retour en cas de crash
                    die('Erreur : ' . $e->getMessage());
                }

                ///Affichage des entrées du résultat une à une

                $double_tab = $res->fetchAll(); // je met le result de ma query dans un double tableau
                $nombre_ligne = $res->rowCount();
                $liste = array();

                echo "<table class='affichage-objectif'>";
                echo "<colgroup class='column'></colgroup>";
                echo "<tr class='titre-objectif'>
                <th>Nom</th>
                <th>Jetons</th>
                <th>Durée</th>
                <th>Priorité</th>
                <th>Statut</th>
                <th>Archiver</th>
                </tr>";

                for ($i = 0; $i < $nombre_ligne; $i++) {
                    echo "<tr class='objectif_tr'>";

                    echo "<td>";
                    print_r(htmlspecialchars($double_tab[$i][0]));
                    echo "</td>";

                    echo "<td><center>";
                    print_r(htmlspecialchars($double_tab[$i][1]));
                    echo " Jetons";
                    echo "</center></td>";

                    echo "<td><center>";
                    print_r(1);
                    echo " semaine";
                    echo "</center></td>";

                    echo "<td><center>";
                    echo "niveau de priorité  : ";
                    print_r(htmlspecialchars($double_tab[$i][3]));
                    echo "</center></td>";

                    echo "<td><center>";
                    echo "Statut : ";
                    if ($double_tab[$i][4] == 1) {
                        print_r("En Utilisation");
                    } else {
                        print_r("Pas en utilisation");
                    }
                    echo "</center></td>";








                    echo "<td>";
                    echo " <div class=\"case-enfant\">";
                    if ($_SESSION["role_user"] == 1) {
                        echo "<button class=\"supprimer-objectif\" type=\"button\" onclick=\"openDialog('dialog" . $double_tab[$i][5] . "', this)\"><img class='delet-icon' src='img/archive.png'></a></button>";
                        echo "<div id=\"dialog_layer\" class=\"dialogs\">";
                        echo "<div role=\"dialog\" id=\"dialog7\" aria-labelledby=\"dialog1_label\" aria-modal=\"true\" class=\"hidden\">";
                        echo "<form action=\"\" method=\"post\" class=\"dialog_form\">";
                        echo "<p> Attention, supprimer ce système est définitif, et supprimera aussi tous les messages associés, plus personne n'y aura accces. ?</p>";

                        echo "<div class=\"dialog_form_actions\">";
                        echo "<button class=\"acceder\"><a href=\"suppr_sys.php?id_sys=" . $double_tab[$i][5] . "\">Supprimer le système</button></a>";
                        echo "<button class=\"deco\" onclick=\"closeDialog(this)\">Annuler</button>";

                        echo "</div>";
                        echo "</form>";
                    }
                    echo "</div>";
                    echo "</div>";
                    echo "</td>";

                    echo "</tr>";
                }

                echo "</table>";

                ///Fermeture du curseur d'analyse des résultats
                $res->closeCursor();



                echo "</section>";
                ?>
        </nav>
    </main>



</body>



</html> 
