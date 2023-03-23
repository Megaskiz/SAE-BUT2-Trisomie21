<!DOCTYPE html>
<html lang="fr" style="font-family: Arial,sans-serif;">

<?php
require('fonctions.php');
is_logged();
is_validateur();
///Connexion au serveur MySQL
$linkpdo = connexionBd();


if (isset($_GET['id_putback'])) {

    $req = $linkpdo->prepare('UPDATE enfant SET visibilite = "0" where id_enfant = ' . $_GET['id_putback']);

    if ($req == false) {
        die("erreur linkpdo");
    }
    ///Exécution de la requête
    try {

        $req->execute(array());
        // $req->debugDumpParams();
        // exit();
        header("Location:index.php");

        if ($req == false) {
            $req->debugDumpParams();
            die("erreur execute");
        } else {
            echo "<a href=\"index.php\"> recharger la page</a>";
        }
    } catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }
}


?>

<head>
    <meta charset="utf-8">
    <title> Menu principal </title>
    <link rel="stylesheet" href="style_css/style_admin.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script type="text/javascript" src="js/script.js"></script>
</head>

<body>


    <!--------------------------------------------------------------- header ------------------------------------------------------------------->
    <?php create_header($linkpdo); ?>


    <!--------------------------------------------------------------- Contenu ------------------------------------------------------------------->

    <!--------------------------------------- menu liste enfant (gauche) -------------------------------------------->
    <main>

        <nav class="left-contenu">
            <div style="display: flex; margin: 3%;">
                <a class="retour" href="index.php"> Retour</a>
            </div>



            <?php
            ///Sélection de tout le contenu de la table enfant
            if ($_SESSION["role_user"] != 2) {


                try {
                    //acces tous les enfants
                    if ($_SESSION["role_user"] == 1 or $_SESSION["role_user"] == 3) {
                        $res = $linkpdo->query('SELECT id_enfant, nom, prenom FROM enfant where visibilite = 1 ORDER BY nom');
                    } else {
                    }
                } catch (Exception $e) { // toujours faire un test de retour en cas de crash
                    die('Erreur : ' . $e->getMessage());
                }

                ///Affichage des entrées du résultat une à une
                $double_tab = $res->fetchAll(); // je met le result de ma query dans un double tableau
                $nombre_ligne = $res->rowCount();
                $liste = array();

                echo "<div class='liste-enfant'>";
                echo "<div class=\"recherche\">
                <form class='recherche' method=\"post\" action=\"search.php\">
                <div>
                <input class=\"input_recherche\" type=\"text\" placeholder=\"Mots-clés ...\" id=\"keywords\" name=\"keywords\" required> 
                </div>
                <input class=\"bouton_recherche\" type=\"submit\" value=\" &#x1F50E;\">
                </form>
                </div>";
                echo "<table >";

                for ($i = 0; $i < $nombre_ligne; $i++) {

                    for ($y = 1; $y < 3; $y++) {
                        echo "<td>";
                        print_r(ucfirst(htmlspecialchars($double_tab[$i][$y])));
                        $liste[$y] = ucfirst($double_tab[$i][$y]);
                        $nom = ucfirst($double_tab[$i][1]);
                        $prenom = ucfirst($double_tab[$i][2]);
                        $age = $double_tab[0][$y];
                        echo "</td>";
                    }

                    $identifiant = $double_tab[$i][0];
                    echo "<td>";
                    echo '<a href="archive_profil_enfant.php?id=' . $identifiant . '"><button  class="acceder-information-enfant">Acceder</button> </a>';
                    echo "</td>";
                    echo "</tr>";
                }

                echo "</table>";

                ///Fermeture du curseur d'analyse des résultats
                $res->closeCursor();
            }
            ?>
            </div>
        </nav>

        <?php // affichage central de la page, avec les informations sur les enfants

        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $_SESSION["id_enfant"] = $id;



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
        }


        ?>




        <!--------------------------------------- menu information sur l'enfant (droite) -------------------------------------------->
        <nav class="right-contenu">
            <div class="section_enfant">
                <?php
                if (isset($_GET['id'])) {
                    $_SESSION['id_enfant'] = $_GET['id'];


                    //<!---- menu droit information sur l'enfant ---->
                    echo "<div class=\"div-photo-enfant\">";
                    echo "<img class=\"photo-enfant\" src=\"" . htmlspecialchars($photo_enfant) . "\" alt=\"photo du visage de " . htmlspecialchars($prenom_enfant) . "\">";
                    echo "</div>";



                    echo "<div class=\"case-3-infos\">";
                    echo "<p class=\"info\">  Nom :<strong> " . htmlspecialchars($nom_enfant) . "</strong></p>";
                    echo "<p class=\"info\">Date de Naissance :<strong>  " . htmlspecialchars($ddn_enfant) . " </strong></p>";
                    echo "<p class=\"info\">Activité enfant :<strong>  " . htmlspecialchars($activite) . "    </strong></p>";
                    echo "</div>";

                    echo "<div class=\"case-3-infos\">";
                    echo "<p class=\"info\">Prénom : <strong> " . htmlspecialchars($prenom_enfant) . "  </strong></p>";
                    echo "<p class=\"info\">Adresse enfant : <strong>  " . htmlspecialchars($adresse) . "    </strong> </p>";
                    echo "<p class=\"info\">Handicap enfant :<strong>  " . htmlspecialchars($handicap) . "     </strong></p>";
                    echo "</div>";


                    echo " <div class=\"div-modif-enfant\">";
                    // acces modif enfant
                    if ($_SESSION["role_user"] == 1) {
                    }

                    echo "</div>";

                    echo "<center>";
                    echo '<button class="button_ajouter-objectif" type="button" onclick="openDialog(\'dialog6\', this)">Dé-archiver ce profil</button>';
                    echo '<div role="dialog" id="dialog6" aria-labelledby="dialog1_label" aria-modal="true" class="hidden">';
                    echo "<p class='popup-txt' > Voulez-vous restaurer le compte de cet enfant ? Il sera visible par toutes les personnes l'ayant suivi </p>";

                    echo ' <div style="display:flex; justify-content: space-evenly;">';
                    echo '  <button class="popup-btn" type="button" onclick="closeDialog(this)">Annuler</button>';
                    echo '   <a  class="popup-btn"  href="archive_profil_enfant.php?id_putback=' . $_GET["id"] . '">Valider</a>';
                    echo "</div>";

                    echo "</div>";

                    echo '<button class="button_ajouter-objectif" type="button" onclick="openDialog(\'dialog7\', this)">Supprimer ce profil</button>';
                    echo '<div role="dialog" id="dialog7" aria-labelledby="dialog1_label" aria-modal="true" class="hidden">';
                    echo "<p class='popup-txt' > Voulez-vous supprimer le compte de cet enfant ? Cette action est définitive, cela supprimera tous ses objetifs, tous les messages associés, toutes les récompenses, et les statistiques de cet enfant. </p>";

                    echo ' <div style="display:flex; justify-content: space-evenly;">';
                    echo '  <button class="popup-btn" type="button" onclick="closeDialog(this)">Annuler</button>';
                    echo '   <a  class="popup-btn"  href="appel_fonction.php?appel=supprime_profil_enfant&id_enfant=' . $_GET["id"] . '">Valider</a>';
                    echo "</div>";

                    echo "</div>";


                    echo "</center>";

                    

                    
                    

                    echo "</div>";
                    


                    echo "</section>";
                    echo "<section class=\"nb-systeme\">";
                    //acces aux boutons -> ajouter sys, stat, stat4semaines
                    if ($_SESSION["role_user"] == 1 or $_SESSION["role_user"] == 3) {
                        echo ' <div style="display:flex">';
                        echo '   <a style="display:none;"><button class="button_ajouter-objectif">Ajouter un nouvel objectif</button></a>';
                        echo '   <a style="display:none;"><button class="button_ajouter-objectif">Toutes les statistiques</button></a>';
                        echo '   <a style="display:none;"><button class="button_ajouter-objectif">Statistiques 4 dernières semaines</button></a>';
                        echo ' </div>';
                    }




                    // tous les systèmes de l'enfant :


                    try {
                        $res = $linkpdo->query('SELECT intitule, nb_jetons, duree, priorite, travaille, id_objectif FROM objectif where visibilite=0 and id_enfant=' . $id . ' ORDER BY priorite ');
                    } catch (Exception $e) { // toujours faire un test de retour en cas de crash
                        die('Erreur : ' . $e->getMessage());
                    }


                    ///Affichage des entrées du résultat une à une

                    $double_tab = $res->fetchAll(); // je met le result de ma query dans un double tableau
                    $nombre_ligne = $res->rowCount();
                    $liste = array();




                    echo "<table class='affichage-objectif'>";

                    echo "<tr class='titre-objectif'>
                        <th>Nom</th>
                        <th>Jetons</th>
                        <th>Durée</th>
                        <th class='sms'>Message</th>
                        <th class='sms'>Statut</th>
                        <th>Statut</th>
                        <th>Accéder</th>
                        <th class='sup'>Archiver</th>
                        </tr>";

                    for ($i = 0; $i < $nombre_ligne; $i++) {
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
                            echo "<button class=\"\" type=\"button\" onclick=\"', this)\"> <span class=\" icon-mail\"> Messagerie &#x2709; </span></button>";
                            echo "</center>";
                            echo "<div id=\"dialog_layer\" class=\"dialogs\">";
                            echo "<div role=\"dialog\" id=\"dialog_message" . $double_tab[$i][5] . "\" aria-labelledby=\"dialog1_label\" aria-modal=\"true\" class=\"hidden\">";
                            echo "<div class=\"dialog_form_actions3\">";
                            echo "<button class=\"deco\" onclick=\"closeDialog(this)\">Retour</button>";
                            echo "</div>";
                            if (isset($double_tab[$i][5]) and !empty($double_tab[$i][5])) {

                                /*$getid = $_GET['id_objectif'];
                                    /*$recupUser = $linkpdo->prepare('SELECT * FROM membre where id_membre = ?');
                                    $recupUser->execute(array($getid));
                                    if($recupUser->rowCount() > 0){*/
                                if (isset($_POST["envoie" . $double_tab[$i][5]])) {
                                    $message = htmlspecialchars($_POST['messages']);
                                    $sujet = htmlspecialchars($_POST['sujet']);
                                    $insererMessage = $linkpdo->prepare('INSERT into message(corps,sujet,id_membre,date_heure,id_objectif) VALUES(?, ?, ?, NOW(), ?)');
                                    if (!$insererMessage) {
                                        die("Erreur prepare");
                                    }


                                    $insererMessage->execute(array($message, $sujet, $_SESSION['logged_user'], $double_tab[$i][5]));
                                    if (!$insererMessage) {
                                        die("Erreur execute");
                                    }
                                }
                                /*}else{
                                        echo ("aucun utilisateur trouvé");
                                    }*/
                            } else {
                                echo ("aucun id trouvé");
                            }
                            echo "<title>Envoie de mesage</title>";




                ?>
                            <div class="chat_all">
                                <div class="chat_title">
                                    <svg class="chat_svg" aria-hidden="true" data-prefix="fas" data-icon="comment-alt" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">
                                        <path fill="currentColor" d="M448 0H64C28.7 0 0 28.7 0 64v288c0 35.3 28.7 64 64 64h96v84c0 9.8 11.2 15.5 19.1 9.7L304 416h144c35.3 0 64-28.7 64-64V64c0-35.3-28.7-64-64-64z"></path>
                                    </svg>
                                    Messagerie du système à jeton
                                </div>
                                <div class="chat_list_msg">
                                    <section id="message">
                                        <?php
                                        $recupMessages = $linkpdo->prepare('SELECT sujet,corps,date_heure,membre.id_membre, membre.nom, membre.prenom FROM message,membre WHERE id_objectif = ? and membre.id_membre = message.id_membre');
                                        if (!$recupMessages) {
                                            die("Erreur prepare");
                                        }
                                        $recupMessages->execute(array($double_tab[$i][5]));
                                        if (!$recupMessages) {
                                            die("Erreur prepare");
                                        }

                                        while ($message = $recupMessages->fetch()) {
                                            if ($message['id_membre'] == $_SESSION['logged_user']) {
                                        ?>
                                                <div class="chat_msgR">
                                                    <img class="chat_img_R" src="/sae-but2-s1/img/user_logo.png" alt="tete de l'utilisateur">
                                                    <div class="chat_vous">
                                                        <div class="chat_info">
                                                            <div class="chat_nomm"><?= ucfirst($message["nom"] . " " . $message["prenom"] . " (vous) : ") ?></div>
                                                            <div class="chat_datem"><?= "le " . (new DateTime($message["date_heure"]))->format("d/m/Y H\hi") ?></div>
                                                        </div>
                                                        <p class="chat_zone_txt"> <?= "Sujet :" . $message["sujet"] . "<br>" . $message["corps"]; ?> </p>
                                                    </div>
                                                </div>
                                            <?php
                                            } else {
                                            ?>
                                                <div class="chat_msgL">
                                                    <img class="chat_img_L" src="/sae-but2-s1/img/user_logo.png" alt="tête de l'utilisateur">
                                                    <div class="chat_autre">
                                                        <div class="chat_info">
                                                            <div class="chat_nomm"><?= ucfirst($message["nom"] . " " . $message["prenom"]) ?></div>
                                                            <div class="chat_datem"><?= "le " . (new DateTime($message["date_heure"]))->format("d/m/Y H\hi") ?></div>
                                                        </div>
                                                        <p class="chat_zone_txt"> <?= "Sujet :" . $message["sujet"] . "<br>" . $message["corps"]; ?> </p>
                                                    </div>
                                                </div>
                                            <?php
                                            }
                                            ?>
                                        <?php
                                        }
                                        ?>
                                    </section>
                                </div>
                                <div class="chat_envoi_msg">
                                    <form method="POST" action="" class="">
                                        <div class="chat_sujet_msg">
                                            <input type="text" id="sujet" name="sujet" class="chat_sujet" placeholder="Sujet ..." required></br>
                                        </div>
                                        <div class="chat_txt_msg">
                                            <input class="chat_messages" name="messages" placeholder="Entrez votre message ..." required></br>
                                            <button type="submit" class="chat_send" name=<?= "envoie" . $double_tab[$i][5] ?>>Envoyer</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                <?php


                            echo "</form>";
                            echo "</td>";




                            #affiche statu
                            echo "<td class='sms'>";
                            if ($double_tab[$i][4] == 1) {
                                echo "<center>";
                                print_r("En Utilisation");
                                echo "</center>";
                            } else {
                                echo "<center>";
                                print_r("Pas en utilisation");
                                echo "</center>";
                            }
                            echo "</td>";

                            //affiche bouton pour la mise en route des sys
                            if ($_SESSION["role_user"] == 1 or $_SESSION["role_user"] == 3) {
                                switch ($double_tab[$i][4]) {
                                    case 1:

                                        echo "<td>";
                                        echo "<center>";
                                        echo '<a href=""><button class="status-objectif">Ne plus utiliser</button></a>';
                                        echo "</center>";
                                        echo "</td>";
                                        break;

                                    case 0:
                                        echo "<td>";
                                        echo "<center>";
                                        echo '<a href=""><button class="status-objectif">Commencer l\'utilisation</button></a>';
                                        echo "</center>";
                                        echo "</td>";
                                        break;
                                }
                            }


                            echo "<td>";
                            echo "<center>";
                            echo '<a href=""><button class="objectif-acceder"> Acceder </button></a>';
                            echo "</center>";
                            echo "</td>";

                            echo "<td>";
                            echo " <div class=\"case-enfant\">";
                            //bouton supprimer un sys -> "archiver"
                            if ($_SESSION["role_user"] == 1) {
                                echo "<center>";
                                echo "<button class=\"supprimer-objectif\" type=\"button\" onclick=\"', this)\"><img class='delet-icon' src='img/delete.png'></a></button>";
                                echo "</center>";
                                echo "<div id=\"dialog_layer\" class=\"dialogs\">";

                                echo "<div role=\"dialog\" id=\"dialog" . $double_tab[$i][5] . "\" aria-labelledby=\"dialog1_label\" aria-modal=\"true\" class=\"hidden\">";

                                echo "<p> Attention, archiver ce système le retirera de tous les affichages et des statistiques, il ne sera accessible qu'aux coordinateur et à l'administrateur, dans l'archive</p>";
                                echo "<div class=\"dialog_form_actions\">";

                                echo "<a href=\"suppr_sys.php?id_sys=" . $double_tab[$i][5] . "\"><button class='sup-objectif'>Supprimer le système</button></a>";
                                echo "<button class=\"deco\" onclick=\"closeDialog(this)\">Annuler</button>";
                                echo "</div>";
                            }




                            echo "</div>";
                            echo "</div>";
                            echo "</td>";

                            echo "</tr>";
                        }
                    }
                    echo "</table>";

                    ///Fermeture du curseur d'analyse des résultats
                    $res->closeCursor();



                    echo "</section>";
                } else {

                    echo "</section>";
                    echo "</div>";
                    echo "<section class=\"nb-systeme\">";
                    echo "</section>";
                }

                // Popup Ajouter equipier 


                echo '<div role="dialog" id="dialog2" aria-labelledby="dialog1_label" aria-modal="true" class="hidden">';
                //echo '<form enctype="multipart/form-data" action="groupe_validation.php" method="post" class="dialog_form">';


                try {
                    $res = $linkpdo->query("SELECT membre.* FROM membre LEFT JOIN suivre ON membre.id_membre = suivre.id_membre AND suivre.id_enfant = ".$getid." WHERE membre.compte_valide = 1 AND suivre.id_membre IS NULL ORDER BY nom;");
                } catch (Exception $e) { // toujours faire un test de retour en cas de crash
                    die('Erreur : ' . $e->getMessage());
                }

                while ($tuteur = $res->fetch()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($tuteur['nom']) . "&nbsp" . "</td>";
                    echo "<td>" . htmlspecialchars($tuteur['prenom']) . "</td>";
                    echo "<td class='Profil'>";
                    echo "<form action='groupe_validation.php?id_enfant=".$getid."&id_membre=".$tuteur."$[id_membre]' method='post'>";

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