<?php
require_once('fonctions.php');
is_logged();
is_validateur();

$linkpdo = connexionBd();
?>
<!DOCTYPE html>
<html lang="fr" style="font-family: Arial,sans-serif;">
<head>
    <meta charset="utf-8">
    <title> Menu principal </title>
    <link rel="stylesheet" href="style_css/style_index.css">
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
            if ($_SESSION["role_user"] != 2) {
                try {
                    //acces tous les enfants
                    if ($_SESSION["role_user"] == 1 or $_SESSION["role_user"] == 3) {
                        $res = $linkpdo->query('SELECT id_enfant, nom, prenom FROM enfant where visibilite = 1 ORDER BY nom');
                    } else {
                    }
                } catch (Exception $e) { 
                    die('Erreur : ' . $e->getMessage());
                }
                $double_tab = $res->fetchAll(); 
                $nombre_ligne = $res->rowCount();
                $liste = array();

                echo "
                <div class='liste-enfant'>
                    <div class=\"recherche\">
                        <form class='recherche' method=\"post\" action=\"search.php\">
                            <div>
                                <input class=\"input_recherche\" type=\"text\" placeholder=\"Mots-clés ...\" id=\"keywords\" name=\"keywords\" required> 
                            </div>
                            <input class=\"bouton_recherche\" type=\"submit\" value=\" &#x1F50E;\">
                        </form>
                    </div>
                <table >
                ";
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
                $res->closeCursor();
            }
            ?>
            </div>
        </nav>
        <?php 
        if (isset($_GET['id'])) {
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
            <div class="section_enfant">
                <?php
                if (isset($_GET['id'])) {
                    $_SESSION['id_enfant'] = $_GET['id'];
                    //<!---- menu droit information sur l'enfant ---->
                    echo "
                    <div class=\"div-photo-enfant\">
                        <img class=\"photo-enfant\" src=\"" . htmlspecialchars($photo_enfant) . "\" alt=\"photo du visage de " . htmlspecialchars($prenom_enfant) . "\">
                    </div>
                    <div class=\"case-3-infos\">
                        <p class=\"info\">  Nom :<strong> " . htmlspecialchars($nom_enfant) . "</strong></p>
                        <p class=\"info\">Date de Naissance :<strong>  " . htmlspecialchars($ddn_enfant) . " </strong></p>
                        <p class=\"info\">Activité enfant :<strong>  " . htmlspecialchars($activite) . "    </strong></p>
                    </div>
                    <div class=\"case-3-infos\">
                        <p class=\"info\">Prénom : <strong> " . htmlspecialchars($prenom_enfant) . "  </strong></p>
                        <p class=\"info\">Adresse enfant : <strong>  " . htmlspecialchars($adresse) . "    </strong> </p>
                        <p class=\"info\">Handicap enfant :<strong>  " . htmlspecialchars($handicap) . "     </strong></p>
                    </div>
                        <div class=\"div-modif-enfant\">
                    </div>
                    <center>
                        <button class=\"button_ajouter-objectif\" type=\"button\" onclick=\"openDialog('dialog6', this)\">Dé-archiver ce profil</button>
                        <div role=\"dialog\" id=\"dialog6\" aria-labelledby=\"dialog1_label\" aria-modal=\"true\" class=\"hidden\">
                            <p class='popup-txt' > Voulez-vous restaurer le compte de cet enfant ? Il sera visible par toutes les personnes l'ayant suivi </p>
                            <div style=\"display:flex; justify-content: space-evenly;\">
                                <button class=\"popup-btn\" type=\"button\" onclick=\"closeDialog(this)\">Annuler</button>
                                <a  class=\"popup-btn\"  href=\"appel_fonction.php?appel=restaure_profil_enfant&id=".$_GET['id']."\">Valider</a>
                            </div>
                        </div>
    
                        <button class=\"button_ajouter-objectif\" type=\"button\" onclick=\"openDialog('dialog7', this)\">Supprimer ce profil</button>
                        <div role=\"dialog\" id=\"dialog7\" aria-labelledby=\"dialog1_label\" aria-modal=\"true\" class=\"hidden\">
                            <p class='popup-txt' > Voulez-vous supprimer le compte de cet enfant ? Cette action est définitive, cela supprimera tous ses objetifs, tous les messagesssociés, toutes les récompenses, et les statistiques de cet enfant. </p>
                            <div style=\"display:flex; justify-content: space-evenly;\">
                                <button class=\"popup-btn\" type=\"button\" onclick=\"closeDialog(this)\">Annuler</button>
                                <a  class=\"popup-btn\"  href=\"appel_fonction.php?appel=supprime_profil_enfant&id_enfant=".$_GET["id"]."\">Valider</a>
                            </div>
                        </div>
                    </center>
                    
                </div>
            </section>
            <section class=\"nb-systeme\">";

                    // tous les systèmes de l'enfant :
                    try {
                        $res = $linkpdo->query('SELECT intitule, nb_jetons, duree, priorite, travaille, id_objectif FROM objectif where visibilite=0 and id_enfant=' . $id . ' ORDER BY priorite ');
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
                            echo "
                            <td class='sms'>
                                <center>
                                    <button class=\"\" type=\"button\" onclick=\"', this)\"> <span class=\" icon-mail\"> Messagerie &#x2709; </span></button>
                                </center>
                            </td>
                            ";
                            
                            #affiche status
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
                                        echo "
                                        <td>
                                            <center>
                                                <a href=\"\"><button class=\"status-objectif\">Ne plus utiliser</button></a>
                                            </center>
                                        </td>";
                                        break;
                                    case 0:
                                        echo "
                                        <td>
                                            <center>
                                                <a href=\"\"><button class=\"status-objectif\">Commencer l'utilisation</button></a>
                                            </center>
                                        </td>";
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
                                echo "
                                <center>
                                    <button class=\"supprimer-objectif\" type=\"button\" onclick=\"', this)\"><img class='delet-icon' src='img/delete.png'></a></button>
                                </center>
                                <div id=\"dialog_layer\" class=\"dialogs\">
                                    <div role=\"dialog\" id=\"dialog" . $double_tab[$i][5] . "\" aria-labelledby=\"dialog1_label\" aria-modal=\"true\" class=\"hidden\">
                                        <p> Attention, archiver ce système le retirera de tous les affichages et des statistiques, il ne sera accessible qu'aux coordinateur et à l'administrateur, dans l'archive</p>
                                        <div class=\"dialog_form_actions\">
                                            <a href=\"suppr_sys.php?id_sys=" . $double_tab[$i][5] . "\"><button class='sup-objectif'>Supprimer le système</button></a>
                                            <button class=\"deco\" onclick=\"closeDialog(this)\">Annuler</button>
                                        </div>";
                            }
                            echo "
                            </div>
                            </div>
                            </td>
                            </tr>";
                        }
                    }
                    echo "</table>";
                    $res->closeCursor();
                    echo "</section>";
                } else {
                    echo "</section>";
                    echo "</div>";
                    echo "<section class=\"nb-systeme\">";
                    echo "</section>";
                }
                ?>
        </nav>
    </main>
</body>
</html>