<!DOCTYPE html>
<html lang="fr" style="font-family: Arial,sans-serif;">

<?php
require('fonctions.php');
is_logged();
///Connexion au serveur MySQL
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
    <link rel="stylesheet" href="style_admin_modif_enfant.css">
    <script type="text/javascript" src="script.js"></script>
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

        if (isset($_FILES['photo_enfant'])) {
            $id = $_SESSION["id_enfant"];
            $photo_enfant = uploadImage($_FILES['photo_enfant']);
            $reqM = "UPDATE enfant SET photo_enfant = '$photo_enfant' WHERE enfant.id_enfant = $id;";
            try {
                $res = $linkpdo->query($reqM);
                echo $reqM;
            } catch (Exception $e) { // toujours faire un test de retour au cas ou ça crash
                die('Erreur : ' . $e->getMessage());
            }
            header("Refresh:0");
        }
        ?>

         <div onclick="window.location.href ='logout.php';" class="h-deconnexion">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="icone_deconnexion">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
            </svg> Déconnexion
        </div>

    </header>


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
                        <button class="ajouter-enfant" type="button" onclick="openDialog('dialog1', this)">Ajouter un enfant <svg  class="icone-ajouter-enfant" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z" />
                            </svg>
                        </button>
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
                        $res = $linkpdo->query('SELECT id_enfant, nom, prenom FROM enfant');
                    } catch (Exception $e) { // toujours faire un test de retour en cas de crash
                        die('Erreur : ' . $e->getMessage());
                    }

                    ///Affichage des entrées du résultat une à une

                    $double_tab = $res->fetchAll(); // je met le result de ma query dans un double tableau
                    $nombre_ligne = $res->rowCount();
                    $liste = array();
                    echo "<table>";


                    for ($i = 0; $i < $nombre_ligne; $i++) {
                        for ($y = 1; $y < 3; $y++) {
                            echo "<td>";
                            print_r($double_tab[$i][$y]);
                            $liste[$y] = $double_tab[$i][$y];
                            $nom = $double_tab[$i][1];
                            $prenom = $double_tab[$i][2];
                            $age = $double_tab[0][$y];
                            echo "</td>";
                        }
                        $identifiant = $double_tab[$i][0];
                        echo "<td>";
                        echo '<a href="page_admin.php?id=' . $identifiant . '">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="icone_info">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5zm6-10.125a1.875 1.875 0 11-3.75 0 1.875 1.875 0 013.75 0zm1.294 6.336a6.721 6.721 0 01-3.17.789 6.721 6.721 0 01-3.168-.789 3.376 3.376 0 016.338 0z" />
                        </svg>
                        </a>';
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




        ///Sélection de tout le contenu de la table carnet_adresse
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






        ?>



        <!--------------------------------------- menu information sur l'enfant (droite) -------------------------------------------->
        <nav class="right-contenu">
            <div class="section_enfant">

                <?php


                //<!---- menu droit information sur l'enfant ---->


                echo "<div class='grille2cases'>"; //morceau de gauche (logo enfant + boutons) :



                // case du logo de l'enfant
                echo "<div class=\"case-enfant\">";
                //--------
                echo "<button class=\"ajouter-photo\" type=\"button\" onclick=\"openDialog('dialog11', this)\">modifier la photo</button>";
                echo "<div id=\"dialog_layer\" class=\"dialogs\">";

                echo "<div role=\"dialog\" id=\"dialog11\" aria-labelledby=\"dialog11_label\" aria-modal=\"true\" class=\"hidden\">";

                echo "<h2 id=\"dialog11_label\" class=\"dialog_label\">modifier la photo</h2>";

                echo "<form enctype=\"multipart/form-data\" action=\"\" method=\"POST\" class=\"dialog_form\">";
                echo "<div class=\"dialog_form_item\">";
                echo "<label><span class=\"label_text\">photo:</span><input name=\"photo_enfant\" type=\"file\" class=\"zip_input\" required=\"required\"></label>";
                echo "</div><div class=\"dialog_form_actions\">";
                echo "<button type=\"submit\">Valider l'ajout</button>";


                echo "<button type=\"button\" onclick=\"closeDialog(this)\">Annuler</button></div></form></div></div>";

                //--------
                echo "<img class=\"logo-enfant\" src=\"$photo_enfant\" alt=\"Tête de l'enfant\">";
                echo "</div>";

                // case 3 boutons : supprimer, modifier, creer systeme
                echo " <div class=\"case-enfant\">";
                // dialog suppression
               
                echo "<div id=\"dialog_layer\" class=\"dialogs\">";
                echo "<div role=\"dialog\" id=\"dialog5\" aria-labelledby=\"dialog1_label\" aria-modal=\"true\" class=\"hidden\">";
                echo "<form action=\"\" method=\"post\" class=\"dialog_form\">";
                echo "<p> Attention vous enlever definitivement cet enfant du programme ! Êtes vous sur de votre choix ?</p>";
                echo "<div class=\"dialog_form_actions\">";
                echo "<a class=\"s\" href=\"page_admin.php?id_suppr='$identifiant'\">Valider la suppression</a>";


                echo "<button class=\"deco\" onclick=\"closeDialog(this)\">Annuler</button>";
                echo "</div>";
                echo "</form>";
                echo "</div>";
                echo "</div>";

               
                echo "<button class=\"spprmrenfant\" type=\"button\" onclick=\"openDialog('dialog5', this)\">Supprimer cet enfant</button>";

                

                echo "</div>";

                echo "</div>";


                echo "<form action=\"ajoute_modif_enfant_bd.php\" method=\"post\">";

                echo "<div class=\"grille_4_cases\" >"; // partie de droite 'le form' -> 2* '3infos' + tuteurs + info sup


                // case 3 infos : nom, ddn, activité
                echo "<div class=\"case-3-infos\">";
                echo "<div style=\"display:inline-flex; align-items: center;\">";
                echo '<p> Nom :</p><input name=nom_enfant type="text" value="' . $nom_enfant . '">';
                echo "</div>";

                echo "<div style=\"display:inline-flex; align-items: center;\">";
                echo "<p>Date de Naissance :</p><input name=date_naissance type=\"date\" value=" . $double_tab[0][3] . ">";
                echo "</div>";



                echo "<div style=\"display:inline-flex; align-items: center;\">";
                echo "<p>Activité enfant :</p><input name=activite type=\"text-area\" value='$activite'>";
                echo "</div>";

                echo "</div>";

                // case 3 infos : prenom, adresse, handicap
                echo "<div class=\"case-3-infos\">";

                echo "<div style=\"display:inline-flex; align-items: center;\">";
                echo "<p>Prénom :</p><input name=prenom_enfant type=\"text\" value='" . $prenom_enfant . "'>";
                echo "</div>";

                echo "<div style=\"display:inline-flex; align-items: center;\">";
                echo "<p>Adresse enfant :</p><input name=adresse type=\"text\" value='$adresse'>";
                echo "</div>";

                echo "<div style=\"display:inline-flex; align-items: center;\">";
                echo "<p>Handicap enfant :</p><input name=handicap type=\"text\" value='$handicap'>";
                echo "</div>";

                echo "</div>";



                // case tuteurs
                echo "<div class=\"case\">";
                echo "<a class=\"tuteur_4\"></a>";
                //$getid = $_GET['id'];
                //echo "<p>";
                //$q = "SELECT membre.nom, prenom, role FROM suivre, membre WHERE suivre.id_enfant= ".$getid." AND suivre.id_membre = ".$_SESSION['logged_user'];
                //echo$q;
                //exit();
                // $allTuteurs = $linkpdo->query($q);
                // while($tuteur = $allTuteurs->fetch()) {
                //     echo $tuteur['nom']." ".$tuteur['prenom']." il est : ".$tuteur['role']. "<br>";
                // }
                // echo "</p>";
                echo "</div>";

                //case info supp
                echo "<div class=\"case-enfant\">";
                echo "<textarea name=info_sup style=\"resize: none\">$info_sup</textarea>";
                echo "<button  >valider</button>";
                echo "</div>";

                echo "</div>";

                echo "</form>";

                echo "</div>"; // fin de la div "section-enfant"

                echo "<section class=\"nb-systeme\">";
                echo '<a href="page_creatsystem.php"><button class="button_acceder">creer un nouveau systeme</button></a>';



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
                echo "<table>";

                for ($i = 0; $i < $nombre_ligne; $i++) {
                    echo "<tr>";
                    echo "<td>";

                    print_r($double_tab[$i][0]);
                    echo "</td>";
                    echo "<td>";
                    echo "nombre de jetons : ";
                    print_r($double_tab[$i][1]);
                    echo "</td>";
                    echo "<td>";
                    echo "durée de l'objectif (en semaine(s)) : ";
                    print_r(1);
                    echo "</td>";
                    echo "<td>";
                    echo "niveau de priorité  : ";
                    print_r($double_tab[$i][3]);
                    echo "</td>";
                    echo "<td>";
                    echo "statut : ";
                    if ($double_tab[$i][4] == 1) {
                        print_r("En Utilisation");
                    } else {
                        print_r("Pas en utilisation");
                    }
                    echo "</td>";
                    echo "<td>";
                    echo '<a href="choix_sys.php?id_sys=' . $double_tab[$i][5] . '"><button class="acceder">acceder</button></a>';
                    echo "</td>";
                    switch ($double_tab[$i][4]) {
                        case 1:
                            echo "<td>";
                            echo '<a href="utilisation.php?id_sys=' . $double_tab[$i][5] . '&valeur=0"><button class="acceder">Ne plus utiliser</button></a>';
                            echo "</td>";
                            break;

                        case 0:
                            echo "<td>";
                            echo '<a href="utilisation.php?id_sys=' . $double_tab[$i][5] . '&valeur=1"><button class="acceder">Commencer l\'utilisation</button></a>';
                            echo "</td>";
                            break;
                    }
                    echo "<td>";
                    echo " <div class=\"case-enfant\">";
                    echo "<button class=\"spprmrenfant\" type=\"button\" onclick=\"openDialog('dialog7', this)\">Supprimer ce système</button>";
                    echo "<div id=\"dialog_layer\" class=\"dialogs\">";
                    echo "<div role=\"dialog\" id=\"dialog7\" aria-labelledby=\"dialog1_label\" aria-modal=\"true\" class=\"hidden\">";
                    echo "<form action=\"\" method=\"post\" class=\"dialog_form\">";
                    echo "<p> Attention, supprimer ce système est définitif, et supprimera aussi tous les messages associés, plus personne n'y aura accces. ?</p>";
                    echo "<div class=\"dialog_form_actions\">";
                    echo "<button class=\"acceder\"><a href=\"suppr_sys.php?id_sys=" . $double_tab[$i][5] . "\">Supprimer le système</button></a>";
                    echo "<button class=\"deco\" onclick=\"closeDialog(this)\">Annuler</button>";
                    echo "</div>";
                    echo "</form>";
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


    <!------------------------------------------------------- Footer -------------------------------------------------->
    <footer>

        <img class="footer-logo-association" src="/sae-but2-s1/img/logo_trisomie.png" alt="logo de l'association">

        <div class="f-contenu">
            <div class="f-menu">
                <li>
                    <p class="f-association"> <a href="http://trisomie21-haute-garonne.org/">Qui sommes nous ?</a> </p>
                </li>
                <li>
                <p class="f-propos"> <a href="http://trisomie21-haute-garonne.org/les-services/sessad/"> Les services</a> </p>
                </li>
                <li>
                    <p class="f-info"> <a href="http://trisomie21-haute-garonne.org/lassociation/historique-de-lassociation/"> Association</a> </p>
                </li>
            </div>
            <p class="f-copyright">© Copyright 2022 </p>
        </div>

        <div class="f_icone">
            
            <div class="f_facebook">
                <svg fill="currentColor" width="20" viewBox="0 0 7 16" class="icone_fb">
                    <path d="M4.563 4.964h2.295l-0.268 2.536h-2.027v7.357h-3.045v-7.357h-1.518v-2.536h1.518v-1.527q0-1.625 0.768-2.46t2.527-0.835h2.027v2.536h-1.268q-0.348 0-0.558 0.058t-0.304 0.21-0.121 0.308-0.027 0.442v1.268z"></path>
                </svg>
                 <a href="https://fr-fr.facebook.com/t21hg/">FaceBook</a>
            </div>

            <div class="f_tel">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="icone_tel">
                    <path d="M8 16.25a.75.75 0 01.75-.75h2.5a.75.75 0 010 1.5h-2.5a.75.75 0 01-.75-.75z" />
                    <path fill-rule="evenodd" d="M4 4a3 3 0 013-3h6a3 3 0 013 3v12a3 3 0 01-3 3H7a3 3 0 01-3-3V4zm4-1.5v.75c0 .414.336.75.75.75h2.5a.75.75 0 00.75-.75V2.5h1A1.5 1.5 0 0114.5 4v12a1.5 1.5 0 01-1.5 1.5H7A1.5 1.5 0 015.5 16V4A1.5 1.5 0 017 2.5h1z" clip-rule="evenodd" />
                </svg>
                05 61 54 34 47
            </div>

            <div class="f_mail">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="icone_mail">
                    <path fill-rule="evenodd" d="M2.106 6.447A2 2 0 001 8.237V16a2 2 0 002 2h14a2 2 0 002-2V8.236a2 2 0 00-1.106-1.789l-7-3.5a2 2 0 00-1.788 0l-7 3.5zm1.48 4.007a.75.75 0 00-.671 1.342l5.855 2.928a2.75 2.75 0 002.46 0l5.852-2.926a.75.75 0 10-.67-1.342l-5.853 2.926a1.25 1.25 0 01-1.118 0l-5.856-2.928z" clip-rule="evenodd" />
                </svg>
                Trisomi21@mail.com
            </div>

        </div>


    </footer>
</body>



</html>