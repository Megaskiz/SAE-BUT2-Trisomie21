<?php


// ------------------------------------- fonctions diverses -----------------------------------------------------------

function connexionBd()
{
    return new PDO("mysql:host=localhost;dbname=sae", "sae", "XkQQCQUD0azqRP7R");
}


function filter_spaces($var)
{
    return $var != ' ';
}

// ------------------------------------- fonctions pour les "blocs" html -----------------------------------------------------------

function create_header($linkpdo)
{

    echo '<header>
        <img class="logo-association" src="/sae-but2-s1/img/logo_trisomie.png" alt="logo de l\'association">
        <img class="img-user" src="/sae-but2-s1/img/user_logo.png" alt="tete de l\'utilisateur">
        ';

    $mail =  $_SESSION['login_user'];
    try {
        $res = $linkpdo->query("SELECT nom, prenom FROM membre where courriel='$mail'");
    } catch (Exception $e) { 
        die('Erreur : ' . $e->getMessage());
    }

    

    $double_tab = $res->fetchAll(); 
    $nombre_ligne = $res->rowCount(); 
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
    echo '
        <div onclick="window.location.href =\'logout.php\';" class="h-deconnexion">
            <img class="img-deco" src="img/deconnexion.png" alt="Déconnexion"> Déconnexion 
        </div>
    </header>';
}

function create_nav($linkpdo)
{
    echo '
    <div  class="open" onclick="openMenu()"> ☰</div>
    
            <nav  class="left-contenu">
            <div class="close" onclick="closeMenu()"> &#x1F5D9;</div>
                    <ul class="scrolling-tabs nav-links gl-display-flex gl-flex-grow-1 gl-w-full nav gl-tabs-nav nav gl-tabs-nav">
                    <li class="nav-item">
                        <a class="shortcuts-activity nav-link gl-tab-nav-item active gl-tab-nav-item-active" data-placement="" href="index.php">Affichage Enfant</a>
                    </li>';

    //acces à la page de membre
    if ($_SESSION["role_user"] != 0) {
        echo '<li class="nav-item">';
        echo '<a data-placement="" class="nav-link gl-tab-nav-item" href="page_certif_compte.php">Affichage Membre</a>';
        echo '</li>';
    } else {
        echo '<li class="nav-item">';
        echo '<a data-placement="" class="nav-link gl-tab-nav-item" href="mon_compte.php">Mon profil</a>';
        echo '</li>';
    }

?>
    </ul>
    <?php
    //acces à l'ajout de profil d'enfant
    if ($_SESSION["role_user"] == 1 or $_SESSION["role_user"] == 3) {

        //Le bloc suivant est la fenêtre pop-in de l'ajout d'enfant, elle est caché tant qu'on appuie pas sur le bouton "ajouter enfant"
        echo '<div class="bouton_enfant">';

        echo '<button class="ajouter-enfant" type="button" onclick="openDialog(\'dialog1\', this)">Ajouter un profil  <img class="icone-ajouter-membre" src="img/ajouter-utilisateur.png" > </button>';

        echo '<a href="archive_profil_enfant.php"><button class="button_ajouter-objectif">Profils enfants archivés</button></a>';
        echo '<div id="dialog_layer" class="dialogs">';
        echo '<div role="dialog" id="dialog1" aria-labelledby="dialog1_label" aria-modal="true" class="hidden">';
        echo '<h2 id="dialog1_label" class="dialog_label">Ajouter un profil d\'enfant</h2>';
        echo '<form enctype="multipart/form-data" action="insert_enfant.php" method="post" class="dialog_form">';
        echo '<div class="dialog_form_item">';
        echo '<label>';
        echo '<span class="label_text">Nom :</span>';
        echo '<input name="nom" type="text" required="required">';
        echo '</label>';
        echo '</div>';
        echo '<div class="dialog_form_item">';
        echo '<label>';
        echo '<span class="label_text">Prenom:</span>';
        echo '<input name="prenom" type="text" class="city_input" required="required">';
        echo '</label>';
        echo '</div>';
        echo '<div class="dialog_form_item">';
        echo '<label>';
        echo '<span class="label_text">Date de naissance:</span>';
        echo '<input name="date_naissance" type="date" class="state_input" required="required">';
        echo '</label>';
        echo '</div>';
        echo '<div class="dialog_form_item">';
        echo '<label>';
        echo '<span class="label_text">Jeton:</span>';
        echo '<input name="lien_jeton" type="file" class="zip_input" required="required">';
        echo '</label>';
        echo '<label>';
        echo '<span class="label_text">Enfant:</span>';
        echo '<input name="photo_enfant" type="file" class="zip_input" required="required">';
        echo '</label>';
        echo '</div>';
        echo '<div class="dialog_form_actions">';
        echo '<button  class="popup-btn" type="button" onclick="closeDialog(this)">Annuler</button>';
        echo '<button class="popup-btn" type="submit">Valider l\'ajout</button>';
        echo '</div>';
        echo '</form>';
        echo '</div>';
        echo '</div>';
        echo '</div>';

        /* fin de la fenêtre popin de l'ajout d'enfant" */
    }
    ?>


    <?php
    if ($_SESSION["role_user"] != 2) {


        try {
            //acces tous les enfants
            if ($_SESSION["role_user"] == 1 or $_SESSION["role_user"] == 3) {
                $res = $linkpdo->query('SELECT id_enfant, nom, prenom FROM enfant where visibilite = 0 ORDER BY nom');
            } else {
                $res = $linkpdo->query('SELECT id_enfant, nom, prenom FROM enfant where id_enfant in (select id_enfant from suivre where visibilite = 0  and id_membre=' . $_SESSION["logged_user"] . ')');
            }
        } catch (Exception $e) { 
            die('Erreur : ' . $e->getMessage());
        }

        
        $double_tab = $res->fetchAll(); 
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
            echo '<a href="index.php?id=' . $identifiant . '"><button  class="acceder-information-enfant">Acceder</button> </a>';
            echo "</td>";
            echo "</tr>";
        }

        echo "</table>";

        
        $res->closeCursor();
    }
    echo "
                </div>
            </nav>";
};

function create_section_info_enfant($linkpdo, $id_enfant)
{

    
    try {
        $res = $linkpdo->query("SELECT * FROM enfant where id_enfant='$id_enfant'");
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
				<div class=\"div-modif-enfant\">";
    if ($_SESSION["role_user"] == 1) {
        // acces modif enfant     
        // seuls les admins on accès au formulaire de modification d'un profil d'enfant

        pop_in_archive_enfant($id_enfant);
        echo "<div class='button_edit'>";
        pop_in_modif_enfant($nom_enfant, $prenom_enfant, $ddn_enfant, $activite, $adresse, $handicap, $info_sup);
        pop_in_modif_jeton($lien_jeton_enfant, $prenom_enfant, $photo_enfant);
        "</div>";
    }
    echo "
				</div>
				<div class='div-liste-equipe'>
					<div class='button-equipe'>
						<button class=\"bouton-equipe\" type=\"button\" onclick=\"openDialog('dialog2', this)\">Ajout Equipier</button>
                        <div role=\"dialog\" id=\"dialog2\" aria-labelledby=\"dialog1_label\" aria-modal=\"true\" class=\"hidden\">";

    try {
        $res = $linkpdo->query("SELECT membre.* FROM membre LEFT JOIN suivre ON membre.id_membre = suivre.id_membre AND suivre.id_enfant = '$id_enfant' WHERE membre.compte_valide = 1 AND suivre.id_membre IS NULL ORDER BY nom;");
    } catch (Exception $e) { 
        die('Erreur : ' . $e->getMessage());
    }

    while ($tuteur = $res->fetch()) {
        echo "
                            <div class='btn_ajouter'>
                                <tr>
                                    <td>" . htmlspecialchars($tuteur['nom']) . "&nbsp" . "</td>
                                    <td>" . htmlspecialchars($tuteur['prenom']) . "</td>
                                    <td class='Profil'>
                                        <form action='groupe_validation.php?id_enfant=$id_enfant&id_membre=$tuteur[id_membre]' method='post'>
                                            <button id='ajt' type='submit'>Ajouter &#x2b;</button>
                                        </form>
                                    </td>
                                </tr>
                            </div>
                        <br>
                            ";
    }

    echo "
                        <button class=\"popup-btn\" type=\"button\" onclick=\"closeDialog(this)\">Annuler &#x1F5D9;</button>
                        </div>    
                    </div>
                <button class=\"list_equipier\" type=\"button\" onclick=\"openDialog('dialog8', this)\">Equipe</button>
            <div id=\"dialog_layer\" class=\"dialogs\">
                <div role=\"dialog\" id=\"dialog8\" aria-labelledby=\"dialog1_label\" aria-modal=\"true\" class=\"hidden\">
                    <h2 id=\"dialog1_label\" class=\"dialog_label\">Equipe</h2>
					";

    echo "
								<a class=\"tuteur_4\"></a>
					";
    $id_enfant = $_GET['id'];
    echo "
								<p>
					";
    $allTuteurs = $linkpdo->query('SELECT membre.id_membre, membre.nom, prenom, role_user FROM suivre, membre WHERE id_enfant= ' . $id_enfant . " AND suivre.id_membre = membre.id_membre ORDER BY nom;");
    while ($tuteur = $allTuteurs->fetch()) {
        switch ($tuteur['role_user']) {
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
        echo "
								<div class='popup_info'>
									<img class=\"img_equipe\" src=\"/sae-but2-s1/img/user_logo.png\" alt=\"Photo du visage de l'utilisateur\">
									<p>" . $tuteur['nom'] . " " . $tuteur['prenom'] . "</p> Rôle : " .  $role . "    
									<a class=\"equipier\" href=\"page_certif_compte.php?idv=" . $tuteur['id_membre'] . "\"><button class=\"acceder-information-enfant\">Information</button></a>
									<a class=\"equipier\" href=\"appel_fonction.php?appel=eject_equipe&id=" . $id_enfant . "&eject=" . $tuteur['id_membre'] . "\"><button class=\"acceder-information-enfant\" style= \" background-color: rgb(206, 205, 205); color:black;;\">Retirer de l\'équipe</button> </a>
								</div>
						";
    }
    echo "
								</p>
								<button class=\"popup-btn\" type=\"button\" onclick=\"closeDialog(this)\">Annuler</button>
							</div>
						</div>
					</div>
                    </div>
					<div class='div-zone-texte'>
						<textarea style=\"resize: none\">Informations supplémentaires sur " . htmlspecialchars($prenom_enfant) . " : " . htmlspecialchars($info_sup) . " </textarea>
					</div>
                    ";
}

function create_section_info_sys($linkpdo, $id_enfant)
{
    /// début de la section des systèmes ///

    //acces aux boutons -> ajouter sys, stat, stat4semaines
    if ($_SESSION["role_user"] == 1 or $_SESSION["role_user"] == 3) {
        echo ' 
                        <div class="btn-objectif">
					        <div class="btn-stat">
                                <a href="page_creatsystem.php"><button class="button_ajouter-objectif"> Ajouter un nouvel objectif</button></a>
                                <a href="  archive_sys.php"><button class="button_ajouter-objectif"> Objectifs archivés</button></a>
                            </div>
					        <div class="btn-stat">
						        <a href="statistiques.php"><button class="button_ajouter-objectif">Toutes les statistiques</button></a>
						        <a href="statistiques_quatre_semaines.php"><button class="button_ajouter-objectif">Statistiques 4 dernières semaines</button></a>
					        </div>
					    </div>';
    }
    // tous les systèmes de l'enfant :  
    try {
        $res = $linkpdo->query('SELECT intitule, nb_jetons, duree, priorite, travaille, id_objectif FROM objectif where visibilite=0 and id_enfant=' . $id_enfant . ' ORDER BY priorite ');
    } catch (Exception $e) { 
        die('Erreur : ' . $e->getMessage());
    }
    
    $double_tab = $res->fetchAll(); 
    $nombre_ligne = $res->rowCount();
    $liste = array();


    echo "
                        <table class='affichage-objectif'>
					        <colgroup class='column'></colgroup>
                            <tr class='titre-objectif'>
                                <th>Nom</th>
                                <th>Jetons</th>
                                <th>Durée</th>
                                <th class='sms'>Message</th>
                                <th>Statut</th>
                                <th>Accéder</th>
                                <th class='sup'>Archiver</th>
                            </tr>";

    for ($i = 0; $i < $nombre_ligne; $i++) {
        //acces au systèmes
        if ($_SESSION["role_user"] == 1 || $double_tab[$i][4] == 1 or $_SESSION["role_user"] == 3) {
            echo "<tr class='objectif_tr'>";

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
            echo "<button class=\"message\" type=\"button\" onclick=\"openDialog('dialog_message" . $double_tab[$i][5] . "', this)\"> <span class=\" icon-mail\"> Messagerie &#128172; </span></button>";
            echo "</center>";
            echo "<div id=\"dialog_layer\" class=\"dialogs\">";
            echo "<div role=\"dialog\" id=\"dialog_message" . $double_tab[$i][5] . "\" aria-labelledby=\"dialog1_label\" aria-modal=\"true\" class=\"hidden\">";
            echo "<div class=\"dialog_form_actions3\">";
            echo "<button class=\"deco\" onclick=\"closeDialog(this)\">Retour</button>";
            echo "</div>";
            if (isset($double_tab[$i][5]) and !empty($double_tab[$i][5])) {
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
            } else {
                echo ("aucun id trouvé");
            }
            echo "<title>Envoie de mesage</title>";
    ?>
            <div class="chat_all">
                <div class="chat_title">
                    💬Messagerie du système à jeton
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
            echo "
                            </form>
							</td>
                            ";
            //affiche bouton pour la mise en route des sys
            if ($_SESSION["role_user"] == 1 or $_SESSION["role_user"] == 3) {
                switch ($double_tab[$i][4]) {
                    case 1:
                        echo "
                                        <td>
										    <center>
                                                <a href=\"appel_fonction.php?appel=mise_en_route&id_sys=" . $double_tab[$i][5] . "&valeur=0\"><button class=\"status-objectif actif\">Actif  &#x2714;</button></a>
										    </center>
										</td>";
                        break;
                    case 0:
                        echo "
                                        <td>
                                            <center>
                                                <a href=\"appel_fonction.php?appel=mise_en_route&id_sys=" . $double_tab[$i][5] . "&valeur=1 \"><button class=\"status-objectif nonactif\">Désactivé  &#x1F5D9;</button></a>
                                            </center>
										</td>
                                        ";
                        break;
                }
            }


            echo "
                            <td>
                                <center>
                                    <a href=\"objectif.php?id_sys=" . $double_tab[$i][5] . "\"><button class=\"objectif-acceder\"> Acceder </button></a>
                                </center>
							</td>
							<td>
							    <div class=\"case-enfant\">";
            //bouton supprimer un sys -> "archiver"
            if ($_SESSION["role_user"] == 1) {
                echo "
                                <center>
								    <button class=\"supprimer-objectif\" type=\"button\" onclick=\"openDialog('dialog" . $double_tab[$i][5] . "', this)\"><img class='delet-icon' src='img/archive.png'></a></button>
								</center>
								<div id=\"dialog_layer\" class=\"dialogs\">
                                    <div role=\"dialog\" id=\"dialog" . $double_tab[$i][5] . "\" aria-labelledby=\"dialog1_label\" aria-modal=\"true\" class=\"hidden\">
                                        <p class='popup-txt'> Attention, archiver ce système le retirera de tous les affichages et des statistiques, il ne sera accessible qu'aux coordinateur et à l'administrateur, dans l'archive.</p>
                                        <div class=\"dialog_form_actions\">
                                            <button class='popup-btn' onclick=\"closeDialog(this)\">Annuler</button>
                                                <a  href=\"suppr_sys.php?id_sys=" . $double_tab[$i][5] . "\"> <button class='popup-btn'> Archiver cet objectif </button> </a>
								        </div>
                                    </div>
                                </div>";
            }

            echo "
                            </td>
						</tr>";
        }
    }
    echo "</table>";
    
    $res->closeCursor();
}

// ------------------------------------- fonctions pour les pop-in -----------------------------------------------------------


function pop_in_modif_enfant($nom_enfant, $prenom_enfant, $ddn_enfant, $activite, $adresse, $handicap, $info_sup)
{
    echo "
    <button class=\"bouton-modif-enfant\" type=\"button\" onclick=\"openDialog('dialog50', this)\">Modifier profil</button>
    <div id=\"dialog_layer\" class=\"dialogs\">
        <div role=\"dialog\" id=\"dialog50\" aria-labelledby=\"dialog1_label\" aria-modal=\"true\" class=\"hidden\">
            <form action=\"appel_fonction.php?appel=modif_enfant\" method=\"post\">
                <div class=\"grille_4_cases\" >
                <div class=\"case-3-infos\">
                    <div class='element_style' style=\"display: flex; justify-content: flex-start;\">
                        <p> Nom :</p><input name=nom_enfant type=\"text\" placeholder='" . htmlspecialchars($nom_enfant) . "' value='" . htmlspecialchars($nom_enfant) . "'>
                    </div>

                    <div  class='element_style' style=\"display: flex; justify-content: flex-start;\">
                        <p>Prénom :</p><input name=prenom_enfant type=\"text\" placeholder='" . htmlspecialchars($prenom_enfant) . "' value='" . htmlspecialchars($prenom_enfant) . "'>
                    </div>

                    <div class='element_style' style=\"display: flex; justify-content: flex-start;\">
                        <p>Date de Naissance :</p><input name=date_naissance type=\"date\" placeholder=" . htmlspecialchars($ddn_enfant) . " value=" . htmlspecialchars($ddn_enfant) . ">
                    </div>
                    
                </div>
                <div class=\"case-3-infos\">
                    
                    <div  class='element_style' style=\"display: flex; justify-content: flex-start;\">
                        <p>Adresse enfant :</p><input name=adresse type=\"text\" placeholder='" . htmlspecialchars($adresse) . "' value='" . htmlspecialchars($adresse) . "'>
                    </div>

                    <div  class='element_style' style=\"display: flex; justify-content: flex-start;\">
                        <p>Activité enfant :</p><input name=activite type=\"text-area\" placeholder='" . htmlspecialchars($activite) . "' value='" . htmlspecialchars($activite) . "'>
                    </div>

                    <div  class='element_style' style=\"display: flex; justify-content: flex-start;\">
                        <p>Handicap enfant :</p><input name=handicap type=\"text\" placeholder='" . htmlspecialchars($handicap) . "' value='" . htmlspecialchars($handicap) . "'>
                    </div>
                </div>
                <div  class='zone-texte' >
                    <p style='font-size: 16px;
                    font-weight: bold;'>Informations supplémentaires :</p><textarea name=info_sup style=\"resize: none\">" . htmlspecialchars($info_sup) . "</textarea>
                </div>
                <div   class='bouton-valider'>
                    <button class=\"popup-btn\" type=\"button\" onclick=\"closeDialog(this)\">Annuler &#x1F5D9;</button>
                    <button class='button-valider-modification actif'> Valider &#x2714; </button>
                </div>
                
                </div>
            </form>
        </div>
    </div>";
};

function pop_in_modif_jeton($lien_jeton_enfant, $prenom_enfant, $photo_enfant)
{
    echo "
    <button  class=\"bouton-modif-enfant\" type=\"button\" onclick=\"openDialog('dialog5', this)\">Modifier images</button>
    <div id=\"dialog_layer\" class=\"dialogs\">
        <div role=\"dialog\" id=\"dialog5\" aria-labelledby=\"dialog1_label\" aria-modal=\"true\" class=\"hidden\">
    <div class='popup_photo'>
            <div>
            <h2 id=\"dialog11_label\" class=\"dialog_label\">Modifier le jeton</h2>
            <img class=\"photo-jeton\" src=\"" . htmlspecialchars($lien_jeton_enfant) . "\" alt=\"jeton de " . htmlspecialchars($prenom_enfant) . "\">
            <form enctype=\"multipart/form-data\" action=\"appel_fonction.php?appel=modif_jeton\" method=\"POST\" class=\"dialog_form\">
                <div class=\"dialog_form_item\">
                    <label><span class=\"label_text\">photo:</span><input name=\"photo_enfant\" type=\"file\" class=\"zip_input\" required=\"required\"></label>
                </div>
                <div class=\"dialog_form_actions\">
                    <button class='popup-btn' onclick=\"closeDialog(this)\">Annuler &#x1F5D9;</button>
                    <button class='popup-btn actif' type=\"submit\">Valider &#x2714;</button>
                </div>
            </form>
            </div>

            <div class=\"separateur\"></div>


            <div>
            <h2 id=\"dialog11_label\" class=\"dialog_label\">Modifier la photo</h2>
            <img class=\"photo-jeton\" src=\"" . htmlspecialchars($photo_enfant) . "\" alt=\"jeton de " . htmlspecialchars($prenom_enfant) . "\">
            <form enctype=\"multipart/form-data\" action=\"appel_fonction.php?appel=modif_photo\" method=\"POST\" class=\"dialog_form\">
                <div class=\"dialog_form_item\">
                    <label><span class=\"label_text\">Photo:</span><input name=\"photo_enfant\" type=\"file\" class=\"zip_input\" required=\"required\"></label>
                </div>
                <div class=\"dialog_form_actions\">
                    <button class='popup-btn' type=\"button\" onclick=\"closeDialog(this)\">Annuler &#x1F5D9;</button>
                    <button class='popup-btn' type=\"submit\">Valider &#x2714;</button></div>
                </div>
            </form>
            </div>

            </div>
        </div>
        
    </div>
    ";
}

function pop_in_archive_enfant($id_enfant)
{
    echo "
    <button  class=\"bouton-modif-enfant\" type=\"button\" onclick=\"openDialog('dialog7', this)\">Archiver le profil de l'enfant</button>
    <div role=\"dialog\" id=\"dialog7\" aria-labelledby=\"dialog1_label\" aria-modal=\"true\" class=\"hidden\">
        <p class='popup-txt'> Attention vous allez masquer l'affichage de cet enfant de l'application ! Il sera accesible seulement par les coordinateurs et adminstrateur ! Êtes vous sur de votre choix ? </p>
        <div class=\"dialog_form_actions\">
            <button class='popup-btn' onclick=\"closeDialog(this)\">Annuler &#x1F5D9;</button>
            <a href=\"appel_fonction.php?appel=archive_enfant\"> <button class='popup-btn'>  Valider &#x2714; </button> </a>
        </div>
    </div>
    ";
}
// ------------------------------------- fonctions pour l'administration des enfant / membres -----------------------------------------------------------


function archive_enfant($linkpdo)
{
    $req = $linkpdo->prepare('UPDATE enfant SET visibilite="1" where id_enfant=' . $_SESSION["id_enfant"]);
    
    if ($req == false) {
        die("erreur linkpdo");
    }
    
    try {
        
        $req->execute(array());
        // $req->debugDumpParams();
        // exit();
        header("Location:index.php");
        
        if ($req == false) {
            $req->debugDumpParams;
            die("erreur execute");
        } else {
            echo "<a href=\"index.php\"> recharger la page</a>";
        }
    } catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }
}

function eject($Sid, $id_eject, $linkpdo)
{
    $req_eject = "DELETE FROM suivre WHERE `suivre`.`id_enfant` = $Sid AND `suivre`.`id_membre` = $id_eject";
    try {
        $res = $linkpdo->query($req_eject);
        header('Location: index.php?id=' . $_SESSION['id_enfant']);
    } catch (Exception $e) { 
        die('Erreur : ' . $e->getMessage());
    }
}

function restaure_utilisateur($id, $linkpdo){

        $req = "UPDATE `membre` SET `visibilite` = '0' WHERE `membre`.`id_membre` =$id ;";
        try {
            $linkpdo->query($req);
        } catch (Exception $e) { 
            die('Erreur : ' . $e->getMessage());
        }
}

function restaure_profil_enfant($id, $linkpdo){
    $req = "UPDATE `enfant` SET `visibilite` = '0' WHERE `id_enfant` =$id ;";
    try {
        $linkpdo->query($req);
    } catch (Exception $e) { 
        die('Erreur : ' . $e->getMessage());
    }
}

function archive_membre($id, $linkpdo){

        $req = "UPDATE `membre` SET `visibilite` = '1' WHERE `membre`.`id_membre` =$id ;";
        try {
            $linkpdo->query($req);
        } catch (Exception $e) { 
            die('Erreur : ' . $e->getMessage());
        }
}

function valide_membre($id, $linkpdo){
        $req = "UPDATE `membre` SET `compte_valide` = '1' WHERE `membre`.`id_membre` =$id ;";
        try {
            $linkpdo->query($req);
        } catch (Exception $e) { 
            die('Erreur : ' . $e->getMessage());
        }
}

function invalide_membre($id, $linkpdo){

        $req = "UPDATE `membre` SET `compte_valide` = '0' WHERE `membre`.`id_membre` =$id ;";
        try {
            $linkpdo->query($req);
        } catch (Exception $e) { 
            die('Erreur : ' . $e->getMessage());
        }
}



// ------------------------------------- fonctions pour les modifications -----------------------------------------------------------
function modif_enfant($nom, $prenom, $date_naissance, $adresse, $activite, $handicap, $info_sup, $session, $linkpdo)
{

    if ($nom != null or $prenom != null or $date_naissance != null or $adresse != null or $activite != null or $handicap != null or $info_sup) {
        $liste = array();
        $data = array();


        if ($nom != null) {
            $liste += ["nom" => $nom];
        }

        if ($prenom != null) {
            $liste += ["prenom" => $prenom];
        }

        if ($date_naissance != null) {
            $liste += ["date_naissance" => $date_naissance];
        }

        if ($adresse != null) {
            $liste += ["adresse" => $adresse];
        }

        if ($activite != null) {
            array_push($liste, $activite);
            $liste += ["activite" => $activite];
        }

        if ($handicap != null) {
            $liste += ["handicap" => $handicap];
        }

        if ($info_sup != null) {
            $liste += ["info_sup" => $info_sup];
        }


        $req = "UPDATE enfant SET ";

        foreach ($liste as $key => $value) {
            if (!is_numeric($key)) {
                $req .= $key;
                $req .= '=? ,';
                array_push($data, $value);
            }
        }

        $req = substr($req, 0, -1);

        $req .= "where id_enfant=?";
        array_push($data, $_SESSION['id_enfant']);

        $query = $linkpdo->prepare($req);

        if ($query == false) {
            die("erreur linkpdo");
        }
        
        try {
            $query->execute($data);
            $query->debugDumpParams();


            if ($query == false) {
                die("erreur execute");
            }
        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    header('Location: index.php?id=' . $_SESSION['id_enfant'] . '');
    exit();
}

function modif_compte($nom, $prenom, $adresse, $Cpostal, $ville, $date_naissance, $role, $session, $linkpdo)
{

    if ($role == NULL) {
        $role = '1';
    }
    $req = $linkpdo->prepare("UPDATE membre  SET nom=? ,prenom= ?,adresse= ?,code_postal= ?,ville= ?, date_naissance= ?, role_user=? WHERE id_membre= ?");

    if ($req == false) {
        die("erreur linkpdo");
    }
    
    try {
        $req->execute([$nom, $prenom, $adresse, $Cpostal, $ville, $date_naissance, $role, $session]);

        if ($req == false) {
            die("erreur execute");
        }
    } catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }

    header('Location: page_certif_compte.php?idv=' . $_SESSION['id_compte_modif'] . '');
    exit();
}

function modif_jeton($id, $photo_enfant, $linkpdo)
{

    $reqM = "UPDATE enfant SET lien_jeton = '$photo_enfant' WHERE enfant.id_enfant = $id;";
    try {
        $res = $linkpdo->query($reqM);
        //echo $reqM;
    } catch (Exception $e) { 
        die('Erreur : ' . $e->getMessage());
    }
}

function modif_photo($id, $photo_enfant, $linkpdo)
{
    $reqM = "UPDATE enfant SET photo_enfant = '$photo_enfant' WHERE enfant.id_enfant = $id;";
    try {
        $res = $linkpdo->query($reqM);
        //echo $reqM;
    } catch (Exception $e) { 
        die('Erreur : ' . $e->getMessage());
    }
}

function modif_mdp($mdp, $session, $linkpdo)
{

    // fonction qui hash le mot de passe
    $mot = "ZEN02anWobA4ve5zxzZz" . $mdp; // je rajoute une chaine que je vais ajouter au mot de passe
    $nouveau_mdp = hash('sha256', $mot);


    $req = $linkpdo->prepare("UPDATE membre  SET mdp=? WHERE id_membre= ?");

    if ($req == false) {
        die("erreur linkpdo");
    }
    
    try {
        $req->execute([$nouveau_mdp, $session]);
        //$req->debugDumpParams();
        //exit();

        if ($req == false) {
            die("erreur execute");
        }
    } catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }
}

// ------------------------------------- fonctions pour les insert -----------------------------------------------------------

function insert_enfant($nom, $prenom, $date_naissance, $lien_jeton, $photo_enfant, $linkpdo)
{


    // je creé la requete d'insertion 

    $req = $linkpdo->prepare('INSERT INTO enfant(nom, prenom, date_naissance, lien_jeton,photo_enfant)
    VALUES(:nom, :prenom, :date_naissance, :lien_jeton, :photo_enfant)');

    if ($req == false) {
        die("erreur linkpdo");
    }
    
    try {
        $req->execute(array(
            'nom' => htmlspecialchars($nom),
            'prenom' => htmlspecialchars($prenom),
            'date_naissance' => htmlspecialchars($date_naissance),
            'lien_jeton' => htmlspecialchars($lien_jeton),
            'photo_enfant' => htmlspecialchars($photo_enfant)
        ));
        // $req->debugDumpParams();
        // exit();

        if ($req == false) {
            $req->debugDumpParams();
            die("erreur execute");
        }
    } catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }

    header('Location: index.php');
    exit();
}

function insert_membre($nom, $prenom, $adresse, $code, $ville, $courriel, $ddn, $Mdp, $pro, $linkpdo)
{
    // je creé la requete d'insertion 

    $req = $linkpdo->prepare('INSERT INTO membre(nom, prenom, adresse, code_postal, ville, courriel, date_naissance, mdp, pro, compte_valide)
        VALUES(:nom, :prenom, :adresse, :code_postal, :ville, :courriel, :date_naissance, :mdp, :pro, :compte_valide)');

    if ($req == false) {
        die("erreur linkpdo");
    }
    
    try {
        $req->execute(array(
            'nom' => $nom,
            'prenom' => $prenom,
            'adresse' => $adresse,
            'code_postal' => $code,
            'ville' => $ville,
            'courriel' => $courriel,
            'date_naissance' => $ddn,
            'mdp' => $Mdp,
            'pro' => $pro, // à changer
            'compte_valide' => 1
        ));
        if ($req == false) {
            $req->debugDumpParams();
            die("erreur execute");
        }
    } catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }

    header('Location: page_certif_compte.php');
    exit();
}

// ------------------------------------- fonctions pour les images -----------------------------------------------------------

function uploadVisage($photo)
{

    if (isset($photo)) {
        $tmpName = $photo['tmp_name'];
        $name = $photo['name'];
        $size = $photo['size'];
        $error = $photo['error'];

        $tabExtension = explode('.', $name);
        $extension = strtolower(end($tabExtension));

        $extensions = ['jpg', 'png', 'jpeg', 'gif', 'svg', 'webp', 'bmp'];
        $maxSize = 400000000000;

        if (in_array($extension, $extensions) && $size <= $maxSize && $error == 0) {

            $uniqueName = uniqid('', true);
            //uniqid génère quelque chose comme ca : 5f586bf96dcd38.73540086
            $file = $uniqueName . "." . $extension;
            $chemin = "images/";
            //$file = 5f586bf96dcd38.73540086.jpg
            move_uploaded_file($tmpName, 'visage/' . $file);
            $result = $chemin . $file;
        }
    } else {
        echo '<h1>erreur</h1>';
    }
    return $result;
}

function uploadImage($photo)
{

    if (isset($photo)) {
        $tmpName = $photo['tmp_name'];
        $name = $photo['name'];
        $size = $photo['size'];
        $error = $photo['error'];

        $tabExtension = explode('.', $name);
        $extension = strtolower(end($tabExtension));

        $extensions = ['jpg', 'png', 'jpeg', 'gif', 'svg', 'webp', 'bmp'];
        $maxSize = 400000000000;

        if (in_array($extension, $extensions) && $size <= $maxSize && $error == 0) {

            $uniqueName = uniqid('', true);
            //uniqid génère quelque chose comme ca : 5f586bf96dcd38.73540086
            $file = $uniqueName . "." . $extension;
            $chemin = "images/";
            //$file = 5f586bf96dcd38.73540086.jpg
            move_uploaded_file($tmpName, 'images/' . $file);
            $result = $chemin . $file;
        }
    } else {
        echo '<h1>erreur</h1>';
    }
    return $result;
}


// ------------------------------------- fonctions de vérification de droit -----------------------------------------------------------
function is_logged()
{
    session_start();

    if (!isset($_SESSION['logged_user'])) {
        echo "vous n'etes pas connecté : ";
        echo '<a href="login.php">aller vers la page de connexion</a>';
        header("location: login.php");
        exit();
    }
}

function is_user()
{
    if ($_SESSION['role_user'] == 0) {
        echo "vous ne devriez pas etre la : ";
        echo '<a href="index.php">aller vers la page de connexion</a>';
        header("location: index.php");
        exit();
    }
}

function is_validateur()
{
    if ($_SESSION['role_user'] == 2) {
        echo "vous ne devriez pas etre la : ";
        echo '<a href="page_certif_compte.php">aller vers la page de connexion</a>';
        header("location: page_certif_compte.php");
        exit();
    }
}

function is_coordinateur()
{
    if ($_SESSION['role_user'] == 3) {
        echo "vous ne devriez pas etre la : ";
        echo '<a href="index.php">aller vers la page de connexion</a>';
        header("location: index.php");
        exit();
    }
}

function is_not_admin()
{
    if ($_SESSION['role_user'] != 1) {
        echo "vous ne devriez pas etre la : ";
        echo '<a href="index.php">aller vers la page de connexion</a>';
        header("location: index.php");
        exit();
    }
}


// ------------------------------------- fonctions de suppression dans la bd -----------------------------------------------------------

function supprime_objectif($id_objectif, $linkpdo)
{
    // preparation de la Requête sql
    $req = $linkpdo->prepare("
    
    Delete from lier where id_objectif=:id;

    Delete from recompense where id_recompense not in (select id_recompense from lier);
    
    Delete from message where id_objectif=:id;

    Delete from placer_jeton where id_objectif=:id;
    
    Delete from objectif where id_objectif=:id;
    ");
    if ($req == false) {
        $req->debugDumpParams();
        return false;
    }
    // execution de la Requête sql
    $req->execute(array('id' => $id_objectif));
    if ($req == false) {
        $req->debugDumpParams();
        return false;
    }
    supprimer_image($linkpdo); // une fois qu'on a supprimé l'objectif, on peut supprimer les nouvelles images qui n'ont pas de lien dans la bd
    return true;
}

function supprime_profil_enfant($id_enfant, $linkpdo)
{
    // il faut supprimer tous ses objectifs : 

    $req = $linkpdo->prepare("select id_objectif from objectif where id_enfant = :id ");
    if ($req == false) {
        $req->debugDumpParams();
        return false;
    }
    // execution de la Requête sql
    $req->execute(array('id' => $id_enfant));
    if ($req == false) {
        $req->debugDumpParams();
        return false;
    }

    $double_tab = $req->fetchAll();

    $i = 0;
    for ($i; $i < sizeof($double_tab); $i++) {
        supprime_objectif($double_tab[0][$i], $linkpdo); // suppression de tous les objectifs de cet enfant
    }
    // preparation de la Requête sql

    $req = $linkpdo->prepare("
    
    Delete from suivre where id_enfant=:id;
    
    Delete from enfant where id_enfant =:id;
    ");
    if ($req == false) {
        $req->debugDumpParams();
        return false;
    }
    // execution de la Requête sql
    $req->execute(array('id' => $id_enfant));
    if ($req == false) {
        $req->debugDumpParams();
        return false;
    }
    supprimer_image($linkpdo); // une fois qu'on a supprimé le profil enfant, on peut supprimer les nouvelles images qui n'ont pas de lien dans la bd
    return true;
}

function supprime_utilisateur($id_utilisateur, $linkpdo)
{
    // preparation de la Requête sql

    /* Pour supprimer un membre de la base de donnée il faut :

    Table        :  action 
    _______________________
    placer jeton :  mettre un id_membre factice
    message      :  mettre un id_factice
    objectif     :  mettre un id_factice
    suivre       :  supprimer
    membre       :  supprimer
    */

    $req = $linkpdo->prepare("
    
        UPDATE `placer_jeton` SET `id_membre` = '-1' WHERE `placer_jeton`.`id_membre` = :id ; 


        UPDATE `message` SET `id_membre` = '-1' where `id_membre` = :id;


        UPDATE `objectif` SET `id_membre` = '-1' where `id_membre` = :id;


        DELETE from `suivre` where `id_membre`= :id;


        DELETE from `membre` where `id_membre`= :id;
    
    ");
    if ($req == false) {
        $req->debugDumpParams();
        return false;
    }
    // execution de la Requête sql
    $req->execute(array('id' => $id_utilisateur));
    if ($req == false) {
        $req->debugDumpParams();
        return false;
    }
    return true;
}

function supprimer_image($linkpdo)
{
    // recuperer toutes les images qui sont reliés dans la bd

    // récompense = lien 
    // enfant : lien_jeton 
    // enfant : photo_enfant 

    $liste = array();

    $req = $linkpdo->prepare("
    select lien_image, photo_enfant, lien_jeton from recompense, enfant;
    
    ");
    if ($req == false) {
        $req->debugDumpParams();
        return false;
    }
    // execution de la Requête sql
    $req->execute(array());
    if ($req == false) {
        $req->debugDumpParams();
        return false;
    }

    $double_tab = $req->fetchAll();
    for ($i = 0; $i < sizeof($double_tab); $i++) {
        for ($y = 0; $y < 3; $y++) {
            if ($double_tab[$i][$y]) { // pour ne pas ajouter les NULL
                $liste[] = $double_tab[$i][$y];
            }
        }
    }
    $files1 = scandir("./images");

    unset($files1[0]); // on retire le . du $files1
    unset($files1[1]); // on retire le .. du $files1
    unset($files1[2]); // on retire le .gitignore du $files1


    foreach ($files1 as $key => $value) {
        if (!in_array("images/" . $value, $liste)) {
            unlink("./images/" . $value); // suppression de tous les objectifs de cet enfant
        }
    }
}


// ------------------------------------- fonctions par rapport aux systèmes/objectifs -----------------------------------------------------------
 

function afficher_systeme($type, $param, $linkpdo, $id)
{

    /* valeur possible de $type :
            routine
            chaargement
    */

    /* valeur possible de $param :
            valide
            non_valide
    */


    try {
        $res = $linkpdo->query("SELECT nom FROM objectif where id_objectif=$id"); // le nom est la chaine que j'utilise pour construire le système
        $res2 = $linkpdo->query("SELECT lien_jeton FROM enfant where id_enfant=" . $_SESSION['id_enfant'] . "");
    } catch (Exception $e) { 
        die('Erreur : ' . $e->getMessage());
    }

    $double_tab = $res->fetchAll();
    $talbeau_jeton = $res2->fetchAll();

    $lien_jeton = $talbeau_jeton[0][0];
    $chaine = $double_tab[0][0];

    if ($param == "valide") {
        // TESTER SI IL Y A DES 0 DANS LA CHAINE, SI NON, çA VEUT DIRE QUE LE SYSTEME EST FINI ICI
        if (strpos($chaine, '0') == false) {
            $feux = true;
            echo "<h1><a href=\"page_recompense.php?id_sys=" . $_GET['id_sys'] . "&feux=" . $feux . " \">BRAVO CE SYSTEME EST COMPLET, TU PEUX CHOISIR UNE RECOMPENSE !</h1>";
            echo "<script> startConfetti() </script>";
            echo "</a>";
        }
    }
    echo "<div class=\"sys\" style=\"margin-left: auto;margin-right: auto;\">";
    echo "<table>";
    if ($type == "routine") {
        echo "<tr>";
        echo "<td class=\"struct\">";
        echo "<p></p>";
        echo "</td>";
        echo "<td class=\"struct\">";
        echo "<p>Lundi</p>";
        echo "</td>";
        echo "<td class=\"struct\">";
        echo "<p>Mardi</p>";
        echo "</td>";
        echo "<td class=\"struct\">";
        echo "<p>Mercredi</p>";
        echo "</td>";
        echo "<td class=\"struct\">";
        echo "<p>Jeudi</p>";
        echo "</td>";
        echo "<td class=\"struct\">";
        echo "<p>Vendredi</p>";
        echo "</td>";
        echo "<td class=\"struct\">";
        echo "<p>Samedi</p>";
        echo "</td>";
        echo "<td class=\"struct\">";
        echo "<p>Dimanche</p>";
        echo "</td>";
        echo "</tr>";
    }


    $morceau = explode(":", $chaine);
    array_pop($morceau); // je retire la partie apres le dernier ":" 
    $compteur = 0;

    foreach ($morceau as $ligne) {
        $element = explode("_", $ligne);
        $tache = $element[0];
        $jetons = $element[1];
        $tab_jeton = str_split($jetons);
        echo "<tr>";
        echo "<td class='struct'>";

        echo "<p>" . htmlspecialchars($tache) . "</p>";
        echo "</td>";
        //ajout des cases de jetons
        foreach ($tab_jeton as $case_tab) {
            if ($case_tab == 0) {
                echo "<td class='case_jeton' id=$compteur >";

                if ($param == "valide") {
                    echo '<a href="objectif_ajout.php?id=' . $id . '&amp;case=' . $compteur . '&amp;chaine=' . $chaine . '" onclick="setTimeout(startConfetti,500);" style="display: block;width: 5rem;height: 5rem;"></a>';
                } else {
                    echo '<a style="display: block;width: 5rem;height: 5rem;"></a>';
                }


                echo "</td>";
            } else {
                echo "<td class='case_jeton' id=$compteur>";
                echo "<center>";

                if ($param == "valide") {
                    echo '<a href="objectif_remove.php?id=' . $id . '&amp;case=' . $compteur . '&amp;chaine=' . $chaine . '" style="display: block;width: 5rem;height: 5rem;"><img class=\"jeton\" src=' . $lien_jeton . ' alt=' . $lien_jeton . '></a>';
                } else {
                    echo "<img class=\"jeton\" src=$lien_jeton alt=$lien_jeton>";
                }
                echo "</center>";
                echo "</td>";
            }
            $compteur += 1;
        }
        echo "</tr>";
    }
    echo "</div>";
    echo "</table>";
    echo "</div>";
}

function verifie_session_echue($session_max, $id, $linkpdo)
{
    try {
        //je recupere la date du premier jeton placé pour cette session dans ce sys
        $jeton_premier_query = $linkpdo->query("SELECT min(date_heure) from placer_jeton where id_session=" . $session_max . " and id_objectif=" . $id);
        // je recupere la duree totale du sys prevu
        $duree_sys_query = $linkpdo->query("SELECT duree from OBJECTIF where id_objectif=" . $id);
    } catch (Exception $e) { 
        die('Erreur : ' . $e->getMessage());
    }

    

    $double_tab = $jeton_premier_query->fetchAll();
    $double_tab2 = $duree_sys_query->fetchAll();

    $jeton_premier = $double_tab[0][0];
    $duree_sys = $double_tab2[0][0];

    $duree_sys_en_seconde = $duree_sys * 3600;

    $secondes_premier_jeton = strtotime($jeton_premier);

    return $secondes_premier_jeton + $duree_sys_en_seconde < time();
}

function inverse_utilisation_objectif($sys, $val, $linkpdo)
{
    $req = $linkpdo->prepare('UPDATE objectif SET travaille = :invers where id_objectif = :id ');

    if ($req == false) {
        die("erreur linkpdo");
    }
    
    try {
        $req->execute(array('invers' => $val, 'id' => $sys));
    } catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }
}

?>