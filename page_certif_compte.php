<?php
require('fonctions.php');
is_logged();
?>
<!DOCTYPE html>
<html lang="fr">

<?php
///Connexion au serveur MySQL
try {
    $linkpdo = new PDO("mysql:host=localhost;dbname=bddsae", "root", "");
}
///Capture des erreurs éventuelles
catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

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

?>

<head>
    <meta charset="utf-8">
    <title> Administrateur </title>
    <link rel="stylesheet" href="style_page_certif_account.css">
    <script type="text/javascript" src="script.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
</head>

<body>
    <header>
        <img class="logo-association" src="/sae-but2-s1/img/logo_trisomie.png" alt="logo de l'association">
        <img class="img-user" src="/sae-but2-s1/img/user_logo.png" alt="tete de l'utilisateur">

        <?php
        $mail =  $_SESSION['login_user'];
        try {
            $res = $linkpdo->query("SELECT nom, prenom FROM membre where courriel='$mail'");
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
        <p class="h-deconnexion"><button class="deco" onclick="window.location.href ='logout.php';">Déconnexion</button></p>
    </header>


    <!--------------------------------------------------------------- Contenu ------------------------------------------------------------------->

    <!--------------------------------------- menu liste membre (gauche) -------------------------------------------->
    <main>

        <nav class="left-contenu">
            <ul class="scrolling-tabs nav-links gl-display-flex gl-flex-grow-1 gl-w-full nav gl-tabs-nav nav gl-tabs-nav">
                <li class="nav-item">
                    <a class="nav-link gl-tab-nav-item" data-placement="right" href="page_admin.php">Enfant</a>
                </li>
                <li class="nav-item">
                    <a data-placement="" class="shortcuts-activity nav-link gl-tab-nav-item active gl-tab-nav-item-active" href="page_certif_compte.php">Membre</a>
                </li>
            </ul>
            <! -- /* Le bloc suivant est la fenêtre pop-in de l'ajout d'membre, elle est caché tant qu'on appuie pas sur le bouton "ajouter membre" */ -->
                <div class="bouton_tuteur">
                    <button class="ajouter-membre" type="button" onclick="openDialog('dialog1', this)">Ajouter un membre</button>
                    <div id="dialog_layer" class="dialogs">
                        <div role="dialog" id="dialog1" aria-labelledby="dialog1_label" aria-modal="true" class="hidden">
                            <h2 id="dialog1_label" class="dialog_label">Ajouter un membre</h2>

                            <form action="insert_membre.php" method="post" class="dialog_form">
                                <div class="dialog_form_item">
                                    <label>
                                        <span class="label_text">nom :</span>
                                        <input name='nom' type="text" required="required">
                                    </label>
                                </div>
                                <div class="dialog_form_item">
                                    <label>
                                        <span class="label_text">prenom:</span>
                                        <input name='prenom' type="text" class="city_input" required="required">
                                    </label>
                                </div>
                                <div class="dialog_form_item">
                                    <label>
                                        <span class="label_text">adresse :</span>
                                        <input name='adresse' type="text" class="state_input" required="required">
                                    </label>
                                </div>
                                <div class="dialog_form_item">
                                    <label>
                                        <span class="label_text">code postal:</span>
                                        <input name='code_postal' type="text" class="zip_input" required="required">
                                    </label>
                                </div>
                                <div class="dialog_form_item">
                                    <label>
                                        <span class="label_text">ville:</span>
                                        <input name='ville' type="text" class="zip_input" required="required">
                                    </label>
                                </div>
                                <div class="dialog_form_item">
                                    <label>
                                        <span class="label_text">courriel:</span>
                                        <input name='courriel' type="text" class="zip_input" required="required">
                                    </label>
                                </div>
                                <div class="dialog_form_item">
                                    <label>
                                        <span class="label_text">date de naissance:</span>
                                        <input name='ddn' type="date" class="state_input" required="required">
                                    </label>
                                </div>
                                <div class="dialog_form_item">
                                    <label>
                                        <span class="label_text">mots de passe :</span>
                                        <input name='password' type="text" class="state_input" required="required">
                                    </label>
                                </div>
                                <div class="dialog_form_item">
                                    <span class="label_text">Etes vous un professionel ? :</span>
                                    <div>
                                        <span class="label_text" for="oui">oui</span>
                                        <input type="radio" id="oui" name='pro' value="1">

                                        <span class="label_text" for="oui">non</span>
                                        <input type="radio" id="oui" name='pro' value="0" checked>

                                    </div>
                                </div>

                                <div class="dialog_form_actions">
                                    <button type="submit">Valider l'ajout</button>
                                    <button type="button" onclick="closeDialog(this)">Annuler</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <! -- /* fin de la fenêtre popin de l'ajout d'membre" */ -->
                    <?php
                    
                    ///Sélection de tout le contenu de la table 
                    try {
                        $res = $linkpdo->query("SELECT * FROM `membre` WHERE compte_valide= 1;");
                    } catch (Exception $e) { // toujours faire un test de retour en cas de crash
                        die('Erreur : ' . $e->getMessage());
                    }

                    ///Affichage des entrées du résultat une à une

                    $double_tab = $res->fetchAll(); // je met le result de ma query dans un double tableau
                    $nombre_ligne = $res->rowCount();
                    $liste = array();
                    echo "<table class='no-break'>";
                    

                    for ($i = 0; $i < $nombre_ligne; $i++) {
                        echo "<tr>";
                        for ($y = 1; $y < 3; $y++) {
                            echo "<td>";
                            print_r($double_tab[$i][$y]);
                            $liste[$y] = $double_tab[$i][$y];
                            $nom = $double_tab[$i][1];
                            $prenom = $double_tab[$i][2];
                            echo "</td>";
                        }
                        $identifiant = $double_tab[$i][0];

                        echo '<td>';
                            echo "</div>";
                            echo '</td>';
                            echo "<td class=\"Profil\" >";
                                echo '<a href="page_certif_compte.php?idv='.$identifiant.'"><button class="acceder">Profil</button></a>';
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
                        $res = $linkpdo->query("SELECT * FROM `membre` WHERE compte_valide= 0;");
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
                            print_r($double_tab[$i][$y]);
                            $liste[$y] = $double_tab[$i][$y];
                            $nom = $double_tab[$i][1];
                            $prenom = $double_tab[$i][2];
                            echo "</td>";
                        }
                        $identifiant = $double_tab[$i][0];

                        echo '<td>';
                        echo "<button class=\"acceder\" type=\"button\" onclick=\"openDialog('dialog5', this)\">Valider ce compte membre</button>";
                        echo "<div id=\"dialog_layer\" class=\"dialogs\">";
                        echo "<div role=\"dialog\" id=\"dialog5\" aria-labelledby=\"dialog1_label\" aria-modal=\"true\" class=\"hidden\">";
                        echo "<form action=\"\" method=\"post\" class=\"dialog_form\">";
                        echo "<p>Vous voulez valider ce compte membre dans l'application !</p>";
                        echo "<div class=\"dialog_form_actions\">";
                        echo '<a type="button" class="acceder" href="page_certif_compte.php?id_valider='.$identifiant.'">Valider</a>';
                        echo "<button class=\"deco\" onclick=\"closeDialog(this)\">Annuler</button>";
                        echo "</div>";
                        echo "</form>";
                        echo "</div>";
                        echo '</td>';
                        echo "<td class=\"Profil2\" >";
                        echo '<a href="page_certif_compte.php?id=' . $identifiant . '"><button class="acceder">Profil</button></a>';
                        echo "</td>";
                        echo "</tr>";
                    }
                    echo "</table>";

                    ///Fermeture du curseur d'analyse des résultats
                    $res->closeCursor();
                    ?>
        </nav>

        <?php // affichage central de la page, avec les informations 

        if (isset($_GET['id'])) {
            $id = $_GET['id'];



            ///Sélection de tout le contenu de la table carnet_adresse
            try {
                $res = $linkpdo->query("SELECT * FROM membre where id_membre='$id'");
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
                $res = $linkpdo->query("SELECT * FROM suivre natural join membre  where id_membre='$id'");
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
                $res = $linkpdo->query("SELECT * FROM membre where id_membre='$id'");
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
                $res = $linkpdo->query("SELECT * FROM suivre natural join membre  where id_membre='$id'");
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
                    echo "<img class=\"img-user\" src=\"/sae-but2-s1/img/user_logo.png\" alt=\"tete de l'utilisateur\">";
                    echo "</div>";

                    echo "<div class=\"case-3-infos\">";
                    echo "<p>  Nom :<strong> $nom_membre</strong></p>";
                    echo "<p>Prénom : <strong>$prenom_membre</strong></p>";
                    echo "<p>Date de naissance : <strong>$date_naissance_membre</strong></p>";
                    echo "</div>";

                    echo "<div class=\"case-3-infos\">";
                    echo "<p>Adresse mail : <strong>$courriel_membre</strong></p>";
                    echo "<p>Adresse : <strong>$adresse_membre  $ville_membre </strong></p>";
                    echo "<p>Code postal : <strong> $code_postal_membre </strong> </p>";
                    echo "</div>";

                    echo " <div class=\"case-membre_2\">";
                    echo "<button class=\"certifmembre\" type=\"button\" onclick=\"openDialog('dialog3', this)\">Valider ce compte membre</button>";
                    echo "<div id=\"dialog_layer\" class=\"dialogs\">";
                    echo "<div role=\"dialog\" id=\"dialog3\" aria-labelledby=\"dialog1_label\" aria-modal=\"true\" class=\"hidden\">";
                    echo "<form action=\"\" method=\"post\" class=\"dialog_form\">";
                    echo "<p>Vous voulez valider ce compte membre dans l'application !</p>";
                    echo "<div class=\"dialog_form_actions\">";
                    echo '<a href="page_certif_compte.php?id_valider='.$identifiant.'">Valider</a>';
                    echo "<button class=\"deco\" onclick=\"closeDialog(this)\">Annuler</button>";
                    echo "</div>";
                    echo "</form>";
                    echo "</div>";
                    echo "</div>";
                }
                if (isset($_GET['idv'])) {

                    //<!---- menu droit information ---->
                    echo "<div class=\"case-membre_1\">";
                    echo "<img class=\"img-tuteur\" src=\"/sae-but2-s1/img/user_logo.png\" alt=\"tete de l'utilisateur\">";
                    echo "</div>";

                    echo "<div class=\"case-3-infos\">";
                    echo "<p>  Nom :<strong> $nom_membre</strong></p>";
                    echo "<p>Prénom : <strong>$prenom_membre</strong></p>";
                    echo "<p>Date de naissance : <strong>$date_naissance_membre</strong></p>";
                    echo "</div>";

                    echo "<div class=\"case-3-infos\">";
                    echo "<p>Adresse mail : <strong>$courriel_membre</strong></p>";
                    echo "<p>Adresse : <strong> $adresse_membre  $ville_membre </strong></p>";
                    echo "<p>Code postal : <strong> $code_postal_membre </strong> </p>";
                    echo "</div>";

                }
                ?>
        </nav>
    </main>


    <!------------------------------------------------------- Footer -------------------------------------------------->
    <footer>

        <img class="footer-logo-association" src="/sae-but2-s1/img/logo_trisomie.png" alt="logo de l'association">

        <div class="f-contenu">
            <div class="f-menu">
                <li>
                    <p class="f-info">Qui sommes nous ?</p>
                </li>
                <li>
                    <p class="f-propos">A propos</p>
                </li>
                <li>
                    <p class="f-association">Association</p>
                </li>
            </div>
            <p class="f-copyright">© Copyright 2022 </p>
        </div>


    </footer>
</body>



</html>