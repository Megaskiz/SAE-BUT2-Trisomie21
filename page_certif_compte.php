<?php
require_once('fonctions.php');
is_logged();
is_user();
?>
<!DOCTYPE html>
<html lang="fr" style="font-family: Arial,sans-serif;">

<?php
///Connexion au serveur MySQL
$linkpdo = connexionBd();

// faire des fonctions : 


if (isset($_GET['id_valider'])) {
    $id_valider_membre = $_GET['id_valider'];
    $req_add = "UPDATE `membre` SET `compte_valide` = '1' WHERE `membre`.`id_membre` =$id_valider_membre ;";
    try {
        $res = $linkpdo->query($req_add);
        header('Location: page_certif_compte.php');
    } catch (Exception $e) { // toujours faire un test de retour au cas ou ça crash
        die('Erreur : ' . $e->getMessage());
    }
}
if (isset($_GET['id_invalider'])) {
    $id_invalider_membre = $_GET['id_invalider'];
    $req_add = "UPDATE `membre` SET `compte_valide` = '0' WHERE `membre`.`id_membre` =$id_invalider_membre ;";
    try {
        $res = $linkpdo->query($req_add);
        header('Location: page_certif_compte.php');
    } catch (Exception $e) { // toujours faire un test de retour au cas ou ça crash
        die('Erreur : ' . $e->getMessage());
    }
}
if (isset($_GET['id_archiver'])) {
    $id_invalider_membre = $_GET['id_archiver'];
    $req_add = "UPDATE `membre` SET `visibilite` = '1' WHERE `membre`.`id_membre` =$id_invalider_membre ;";
    try {
        $res = $linkpdo->query($req_add);
        header('Location: page_certif_compte.php');
    } catch (Exception $e) { // toujours faire un test de retour au cas ou ça crash
        die('Erreur : ' . $e->getMessage());
    }
}

?>

<head>
    <meta charset="utf-8">
    <title> Administrateur </title>
    <link rel="stylesheet" href="style_css/style_page_certif_account.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script type="text/javascript" src="js/script.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
</head>

<body>

    <!--- HEADER -->
    <?php create_header($linkpdo); ?>


    <!--------------------------------------------------------------- Contenu ------------------------------------------------------------------->

    <!--------------------------------------- menu liste membre (gauche) -------------------------------------------->

    <main>

        <nav class="left-contenu">
            <?php
            if ($_SESSION["role_user"] != 2) {
                echo '<ul class="scrolling-tabs nav-links gl-display-flex gl-flex-grow-1 gl-w-full nav gl-tabs-nav nav gl-tabs-nav">';
                echo '<li class="nav-item">';
                echo '<a class="nav-link gl-tab-nav-item" data-placement="right" href="index.php">Affichage Enfant</a>';
                echo '</li>';
                echo '<li class="nav-item">';
                echo '<a data-placement="" class="shortcuts-activity nav-link gl-tab-nav-item active gl-tab-nav-item-active" href="page_certif_compte.php">Affichage Membre</a>';
                echo '</li>';
                echo '</ul>';
            }

            ?>

            <! -- /* Le bloc suivant est la fenêtre pop-in de l'ajout d'membre, elle est caché tant qu'on appuie pas sur le bouton "ajouter membre" */ -->
                <div class="bouton_tuteur">
                    <?php
                    if ($_SESSION["role_user"] != 3) {
                        echo '<button class="ajouter-membre" type="button" onclick="openDialog(\'dialogNEW1\', this)">Ajouter un membre <img class="icone-ajouter-membre" src="img/ajouter-utilisateur.png" ></button>';
                    }

                    if ($_SESSION["role_user"] == 1 or $_SESSION["role_user"] == 2) {
                        echo "<a href=\"archive_membre.php \"><button class=\"btn_archive\">Membres archivés</button></a>";
                    }
                    ?>
                    <div id="dialog_layer" class="dialogs">
                        <div role="dialog" id="dialogNEW1" aria-labelledby="dialog1_label" aria-modal="true" class="hidden">
                            <h2 id="dialog1_label" class="dialog_label">Ajouter un membre</h2>

                            <form action="appel_fonction.php?appel=insert_membre" method="post" class="dialog_form">
                                <div class="dialog_form_item">
                                    <label>
                                        <span class="label_text">Nom :</span>
                                        <input name='nom' type="text" required="required">
                                    </label>
                                </div>
                                <div class="dialog_form_item">
                                    <label>
                                        <span class="label_text">Prenom:</span>
                                        <input name='prenom' type="text" class="city_input" required="required">
                                    </label>
                                </div>

                                <div class="dialog_form_item">
                                    <label>
                                        <span class="label_text">Date de naissance:</span>
                                        <input name='ddn' type="date" class="state_input" required="required">
                                    </label>
                                </div>

                                <div class="dialog_form_item">
                                    <label>
                                        <span class="label_text">Adresse :</span>
                                        <input name='adresse' type="text" class="state_input" required="required">
                                    </label>
                                </div>
                                <div class="dialog_form_item">
                                    <label>
                                        <span class="label_text">Code Postal:</span>
                                        <input name='code_postal' type="text" class="zip_input" required="required">
                                    </label>
                                </div>
                                <div class="dialog_form_item">
                                    <label>
                                        <span class="label_text">Ville:</span>
                                        <input name='ville' type="text" class="zip_input" required="required">
                                    </label>
                                </div>
                                <div class="dialog_form_item">
                                    <label>
                                        <span class="label_text">Courriel:</span>
                                        <input name='courriel' type="text" class="zip_input" required="required">
                                    </label>
                                </div>

                                <div class="dialog_form_item">
                                    <label>
                                        <span class="label_text">mots de passe :</span>
                                        <input name='password' type="text" class="state_input" required="required">
                                    </label>
                                </div>



                                <div class="dialog_form_actions">
                                    <button class="popup-btn" type="button" onclick="closeDialog(this)">Annuler</button>
                                    <button class="popup-btn" type="submit">Valider</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <! -- /* fin de la fenêtre popin de l'ajout d'membre" */ -->
                    <div class="dropdown">
                        <button onclick="myFunction()" class="dropbtn">Compte membre ☰</button>
                        <div id="myDropdown" class="dropdown-content">
                            <?php


                            ///Sélection de tout le contenu de la table 
                            try {
                                $res = $linkpdo->query("SELECT * FROM `membre` WHERE  visibilite = 0  and compte_valide= 1");
                            } catch (Exception $e) { // toujours faire un test de retour en cas de crash
                                die('Erreur : ' . $e->getMessage());
                            }

                            ///Affichage des entrées du résultat une à une

                            $double_tab = $res->fetchAll(); // je met le result de ma query dans un double tableau
                            $nombre_ligne = $res->rowCount();
                            $liste = array();
                            echo "<div>";
                            echo "<table class='no-break'>";


                            for ($i = 0; $i < $nombre_ligne; $i++) {
                                echo "<tr>";
                                for ($y = 1; $y < 3; $y++) {
                                    echo "<td>";
                                    print_r(ucfirst(htmlspecialchars($double_tab[$i][$y])));
                                    $liste[$y] = $double_tab[$i][$y];
                                    $nom = $double_tab[$i][1];
                                    $prenom = $double_tab[$i][2];
                                    echo "</td>";
                                }
                                $identifiant = $double_tab[$i][0];

                                echo "<td>";
                                echo ' <a href="page_certif_compte.php?idv=' . $identifiant . '">  <button  class="acceder-information-membre"> Profil </button></a>';

                                echo "</td>";
                                echo "</tr>";
                            }
                            echo "</table>";


                            ///Fermeture du curseur d'analyse des résultats
                            $res->closeCursor();
                            ///--------------------------------------------------------------------membre non valide-------------------------------------------

                            echo "<div class='divider'><span></span><span>Demande de compte membre</span><span></span></div>";
                            ///Sélection de tout le contenu de la table 
                            try {
                                $res = $linkpdo->query("SELECT * FROM `membre` WHERE visibilite = 0 and compte_valide= 0 ORDER BY nom;");
                            } catch (Exception $e) { // toujours faire un test de retour en cas de crash
                                die('Erreur : ' . $e->getMessage());
                            }

                            ///Affichage des entrées du résultat une à une

                            $double_tab = $res->fetchAll(); // je met le result de ma query dans un double tableau
                            $nombre_ligne = $res->rowCount();
                            $liste = array();

                            echo "<table>";

                            for ($i = 0; $i < $nombre_ligne; $i++) {
                                echo "<tr>";
                                for ($y = 1; $y < 3; $y++) {
                                    echo "<td>";
                                    print_r(ucfirst(htmlspecialchars($double_tab[$i][$y])));
                                    $liste[$y] = $double_tab[$i][$y];
                                    $nom = $double_tab[$i][1];
                                    $prenom = $double_tab[$i][2];
                                    echo "</td>";
                                }
                                $identifiant = $double_tab[$i][0];
                                $name = ucfirst($nom . " " . $prenom);


                                echo "<td class=\"Profil2\" >";
                                echo '<a href="page_certif_compte.php?id=' . $identifiant . '"><button  class="acceder-information-membre"> Profil</button></a>';
                                echo "</td>";
                                echo "</tr>";
                            }
                            echo "</table>";
                            echo "</div>";

                            ///Fermeture du curseur d'analyse des résultats
                            $res->closeCursor();
                            ?>
                        </div>
                    </div>


        </nav>

        <?php // affichage central de la page, avec les informations 

        if (isset($_GET['id'])) {
            $id = $_GET['id'];



            ///Sélection de tout le contenu de la table carnet_adresse
            try {
                $res = $linkpdo->query("SELECT * FROM membre where id_membre='$id' ORDER BY nom;");
            } catch (Exception $e) { // toujours faire un test de retour au cas ou ça crash
                die('Erreur : ' . $e->getMessage());
            }

            $double_tab = $res->fetchAll(); // je met le result de ma query dans un double tableau
            $nombre_ligne = $res->rowCount(); // =1 car il y a 1 ligne dans ma requete
            $liste = array();


            $id_membre = $double_tab[0][0];
            $nom_membre = $double_tab[0][1];
            $prenom_membre = $double_tab[0][2];
            $adresse_membre = $double_tab[0][3];
            $code_postal_membre = $double_tab[0][4];
            $ville_membre = $double_tab[0][5];
            $courriel_membre = $double_tab[0][6];
            $date_naissance_membre =  date_format(new DateTime(strval($double_tab[0][7])), 'd/m/Y');


            try {
                $res = $linkpdo->query("SELECT * FROM suivre natural join membre  where id_membre='$id' ORDER BY nom;");
            } catch (Exception $e) { // toujours faire un test de retour au cas ou ça crash
                die('Erreur : ' . $e->getMessage());
            }


            ///Affichage des entrées du résultat une à une

            $double_tab_tuteur = $res->fetchAll(); // je met le result de ma query dans un double tableau
            $nombre_ligne = $res->rowCount(); // =2 car il y a 2 ligne dans ma base
            $liste = array();
        }

        if (isset($_GET['idv'])) {
            $id = $_GET['idv'];



            ///Sélection de tout le contenu de la table carnet_adresse
            try {
                $res = $linkpdo->query("SELECT * FROM membre where membre.id_membre='$id' ORDER BY nom;");
                //$res->debugDumpParams();
            } catch (Exception $e) { // toujours faire un test de retour au cas ou ça crash
                die('Erreur : ' . $e->getMessage());
            }

            $double_tab = $res->fetchAll(); // je met le result de ma query dans un double tableau
            $nombre_ligne = $res->rowCount(); // =1 car il y a 1 ligne dans ma requete
            $liste = array();


            $id_membre = $double_tab[0][0];
            $nom_membre = ucfirst($double_tab[0][1]);
            $prenom_membre = ucfirst($double_tab[0][2]);
            $adresse_membre = $double_tab[0][3];
            $code_postal_membre = $double_tab[0][4];
            $ville_membre = $double_tab[0][5];
            $courriel_membre = $double_tab[0][6];

            switch ($double_tab[0][11]) {
                case '0':
                    $role = 'Utilisateur';
                    break;

                case '1':
                    $role = "Administrateur";
                    break;

                case '2':
                    $role = "Validateur (administration)";
                    break;

                default:
                    $role = "Coordinateur";
                    break;
            }

            $date_naissance_membre =  date_format(new DateTime(strval($double_tab[0][7])), 'd/m/Y');


            try {
                $res = $linkpdo->query("SELECT * FROM suivre natural join membre  where id_membre='$id' ORDER BY nom;");
            } catch (Exception $e) { // toujours faire un test de retour au cas ou ça crash
                die('Erreur : ' . $e->getMessage());
            }


            ///Affichage des entrées du résultat une à une

            $double_tab_tuteur = $res->fetchAll(); // je met le result de ma query dans un double tableau
            $nombre_ligne = $res->rowCount(); // =2 car il y a 2 ligne dans ma base
            $liste = array();
        }


        ?>
        <!--------------------------------------- menu information sur le membre (droite) -------------------------------------------->
        <nav class="right-contenu">
            <div class="section_membre">
                <?php
                if (isset($_GET['id'])) {
                    //<!---- menu droit information ---->
                    echo "<div class=\"case-membre_1\">";
                    echo "<img class=\"img-tuteur\" src=\"/sae-but2-s1/img/user_logo.png\" alt=\"tete de l'utilisateur\">";
                    echo "</div>";

                    echo "<div class='grille_2_cases'>";


                    echo "<div class=\"case-3-infos\">";
                    echo "<p class=\"info\"> Nom :<strong> " . htmlspecialchars($nom_membre) . "</strong></p>";
                    echo "<p class=\"info\">Prénom : <strong>" . htmlspecialchars($prenom_membre) . "</strong></p>";
                    echo "<p class=\"info\">Date de naissance : <strong>" . htmlspecialchars($date_naissance_membre) . "</strong></p>";
                    echo "</div>";

                    echo "<div class=\"case-3-infos\">";
                    echo "<p class=\"info\">Adresse mail : <strong>" . htmlspecialchars($courriel_membre) . "</strong></p>";
                    echo "<p class=\"info\">Adresse : <strong>" . htmlspecialchars($adresse_membre) . ", " . htmlspecialchars($ville_membre) . "</strong></p>";
                    echo "<p class=\"info\">Code postal : <strong> " . htmlspecialchars($code_postal_membre) . " </strong> </p>";
                    echo "</div>";

                    echo "</div>";
                    echo " <div class=\"case-membre_2\">";
                    if ($_SESSION["role_user"] != 3) {
                        echo "<button class=\"certifmembre\" type=\"button\" onclick=\"openDialog('dialog" . $_GET['id'] . "', this)\">Valider le compte de ce membre</button>";
                        echo "<div id=\"dialog_layer\" class=\"dialogs\">";
                        echo "<div role=\"dialog\" id=\"dialog" . $_GET['id'] . "\" aria-labelledby=\"dialog1_label\" aria-modal=\"true\" class=\"hidden\">";
                        echo "<form action=\"\" method=\"post\" class=\"dialog_form\">";
                        echo "<p class='popup-txt'>Vous voulez valider le compte de ce membre dans l'application !</p>";
                        echo "<div class=\"dialog_form_actions\">";
                        echo "<button  class='popup-btn' onclick=\"closeDialog(this)\">Annuler</button>";
                        echo '<a class="popup-btn" href="page_certif_compte.php?id_valider=' . $_GET['id'] . '">Valider</a>';
                        echo "</div>";
                        echo "</form>";
                        echo "</div>";
                        echo "</div>";
                    }
                }
                if (isset($_GET['idv'])) {
                    $idiv = $_GET['idv'];
                    //<!---- menu droit information ---->
                    echo "<div class=\"case-membre_1\">";
                    echo "<img class=\"img-tuteur\" src=\"/sae-but2-s1/img/user_logo.png\" alt=\"tete de l'utilisateur\">";
                    echo "</div>";

                    echo "<div class='grille_2_cases'>";

                    echo "<div class=\"case-3-infos\">";
                    echo "<p class=\"info\">  Nom :<strong>" . htmlspecialchars($nom_membre) . "</strong></p>";
                    echo "<p class=\"info\">Prénom : <strong>" . htmlspecialchars($prenom_membre) . "</strong></p>";
                    echo "<p class=\"info\">Date de naissance : <strong>" . htmlspecialchars($date_naissance_membre) . "</strong></p>";
                    echo "<p class=\"info\">Role de l'utilisateur : <strong>$role</strong></p>";


                    echo "</div>";

                    echo "<div class=\"case-3-infos\">";
                    echo "<p class=\"info\">Adresse mail : <strong>" . htmlspecialchars($courriel_membre) . "</strong></p>";
                    echo "<p class=\"info\">Adresse : <strong> " . htmlspecialchars($adresse_membre) . ", " . htmlspecialchars($ville_membre) . " </strong></p>";
                    echo "<p class=\"info\">Code postal : <strong> " . htmlspecialchars($code_postal_membre) . "</strong> </p>";
                    echo "</div>";

                    echo "</div>";

                    echo " <div class=\"case-membre_2\">";
                    if ($_SESSION["role_user"] != 3) {
                        if ($idiv != $_SESSION['logged_user']) {
                            if ($_GET["idv"] != 1) {


                                echo "<button class=\"invalider\" type=\"button\" onclick=\"openDialog('dialogI" . $idiv . "', this)\">Invalider le compte de ce membre</button>";

                                echo "<div id=\"dialog_layer\" class=\"dialogs\">";
                                echo "<div role=\"dialog\" id=\"dialogI" . $idiv . "\" aria-labelledby=\"dialog1_label\" aria-modal=\"true\" class=\"hidden\">";
                                echo "<form action=\"\" method=\"post\" class=\"dialog_form\">";

                                echo "<p class='popup-txt'>Voulez-vous invalider le compte de ce membre dans l'application ?</p>";
                                echo "<div class=\"dialog_form_actions\">";
                                echo "<button  class='popup-btn' onclick=\"closeDialog(this)\">Annuler</button>";
                                echo '<a class="popup-btn" href="page_certif_compte.php?id_invalider=' . $idiv . '">Invalider</a>';
                                echo "</div>";
                                echo "</form>";
                                echo "</div>";
                                echo "</div>";
                            }
                        }
                    }
                }
                ?>
            </div>
            <div class="case-membre_2">
                <?php
                if (isset($_GET["idv"])) {
                    if ($_SESSION["role_user"] == 1) {
                        echo '<button class="modif-certif" type="button" onclick="window.location.href=\'modif_compte.php?id=' . $_GET["idv"] . '\'">Modifier le compte de ce membre</button>';
                        echo '<button class="modif-certif" type="button" onclick="window.location.href=\'modif_mdp.php?id=' . $_GET["idv"] . '\'">Modifier le mot de passe de ce membre</button>';

                        if ($idiv != 1) {

                            echo "<button class=\"invalider\" type=\"button\" onclick=\"openDialog('dialogT" . $idiv . "', this)\">Archiver le compte de ce membre</button>";
                        }

                        echo "<div id=\"dialog_layer\" class=\"dialogs\">";
                        echo "<div role=\"dialog\" id=\"dialogT" . $idiv . "\" aria-labelledby=\"dialog1_label\" aria-modal=\"true\" class=\"hidden\">";
                        echo "<form action=\"\" method=\"post\" class=\"dialog_form\">";

                        echo "<p class='popup-txt'>Voulez-vous archiver le compte de ce membre dans l'application ?</p>";
                        echo "<div class=\"dialog_form_actions\">";
                        echo "<button  class='popup-btn' onclick=\"closeDialog(this)\">Annuler</button>";
                        echo '<a class="popup-btn" href="page_certif_compte.php?id_archiver=' . $idiv . '">Archiver</a>';
                        echo "</div>";
                        echo "</form>";
                        echo "</div>";
                        echo "</div>";
                    } elseif ($_SESSION["role_user"] == 2) { // validateur
                        echo '<button class="modif-certif" type="button" onclick="window.location.href=\'modif_compte.php?id=' . $_GET["idv"] . '\'">Modifier le compte de ce membre</button>';

                        if ($idiv != 1) {
                            if ($idiv != $_SESSION["role_user"]) {


                                echo "<button class=\"invalider\" type=\"button\" onclick=\"openDialog('dialogT" . $idiv . "', this)\">Archiver le compte de ce membre</button>";


                                echo "<div id=\"dialog_layer\" class=\"dialogs\">";
                                echo "<div role=\"dialog\" id=\"dialogT" . $idiv . "\" aria-labelledby=\"dialog1_label\" aria-modal=\"true\" class=\"hidden\">";
                                echo "<form action=\"\" method=\"post\" class=\"dialog_form\">";

                                echo "<p class='popup-txt'>Voulez-vous archiver le compte de ce membre dans l'application ?</p>";
                                echo "<div class=\"dialog_form_actions\">";
                                echo "<button  class='popup-btn' onclick=\"closeDialog(this)\">Annuler</button>";
                                echo '<a class="popup-btn" href="page_certif_compte.php?id_archiver=' . $idiv . '">Archiver</a>';
                                echo "</div>";
                                echo "</form>";
                                echo "</div>";
                                echo "</div>";
                            }
                        }



                        if ($id_membre == $_SESSION['logged_user']) {
                            echo '<button class="modif-certif" type="button" onclick="window.location.href=\'modif_mdp.php?id=' . $_GET["idv"] . '\'">Modifier le mot de passe de ce membre</button>';
                        }
                    }
                }

                ?>
                <?php

                ?>
            </div>

        </nav>
    </main>
</body>

</html>