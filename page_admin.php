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
if(isset($_GET['eject'])){
    $id_eject = $_GET['eject'];
    $Sid = $_GET['id'];
    $req_eject = "DELETE FROM suivre WHERE `suivre`.`id_enfant` = $Sid AND `suivre`.`id_membre` = $id_eject";
    try {
        $res = $linkpdo->query($req_eject);
        header('Location: page_admin.php');
    } catch (Exception $e) { // toujours faire un test de retour au cas ou ça crash
        die('Erreur : ' . $e->getMessage());
    }
}

?>

<head>
    <meta charset="utf-8">
    <title> Administrateur </title>
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
                <?php
                if ($_SESSION["role_user"] == 1 or $_SESSION["role_user"]== 2 ) {

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
            if($_SESSION["role_user"]!=2){

            
            try {
                if ($_SESSION["role_user"]==1) {
                    $res = $linkpdo->query('SELECT id_enfant, nom, prenom FROM enfant');
                }else{
                    $res = $linkpdo->query('SELECT id_enfant, nom, prenom FROM enfant where id_enfant in (select id_enfant from suivre where id_membre='.$_SESSION["logged_user"].')');
                }

            } catch (Exception $e) { // toujours faire un test de retour en cas de crash
                die('Erreur : ' . $e->getMessage());
            }

            ///Affichage des entrées du résultat une à une
            $double_tab = $res->fetchAll(); // je met le result de ma query dans un double tableau
            $nombre_ligne = $res->rowCount();
            $liste = array();

            echo "<div class='liste-enfant'>";
            echo "<table >";




             
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
                echo '<button  class="acceder-information-enfant"> <a href="page_admin.php?id=' . $identifiant . '"> Acceder &#x1F59D; </a> </button>';
                echo "</td>";
                echo "</tr>";
            }
            
            echo "</table>";

            ///Fermeture du curseur d'analyse des résultats
            $res->closeCursor();
            }
            ?>
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
            $nom_enfant = $double_tab[0][1];
            $prenom_enfant = $double_tab[0][2];
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
                    echo "<img class=\"photo-enfant\" src=\"$photo_enfant\" alt=\"photo du visage de $prenom_enfant\">";
                    echo "</div>";


                    
                    echo "<div class=\"case-3-infos\">";
                    echo "<p>  Nom :<strong> $nom_enfant </strong></p>";
                    echo "<p>Date de Naissance :<strong> $ddn_enfant </strong></p>";
                    echo "<p>Activité enfant :<strong> $activite     </strong></p>";
                    echo "</div>";

                    echo "<div class=\"case-3-infos\">";
                    echo "<p>Prénom : <strong>$prenom_enfant  </strong></p>";
                    echo "<p>Adresse enfant : <strong> $adresse     </strong> </p>";
                    echo "<p>Handicap enfant :<strong> $handicap     </strong></p>";
                    echo "</div>";
               

                    echo " <div class=\"div-modif-enfant\">";
                    if ($_SESSION["role_user"] == 1) {
                        echo '<a href="modif_enfant.php"> 
                        <button class="bouton-modif-enfant"> <span class="icon">&#x270E</span>   Modifer </button> </a>';
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
                    }
                    echo "</div>";


                    echo "<div class='div-liste-equipe'>";
                    
                    echo "<div class='button-equipe'>";
                    echo '<button class="bouton-equipe" type="button" onclick="openDialog(\'dialog2\', this)">Ajout Equipier</button>';
                    

                        echo "</div>";
                    //Le bloc suivant est la fenêtre pop-in de l'ajout d'enfant, elle est caché tant qu'on appuie pas sur le bouton "ajouter enfant"
            

                    echo '<button class="list_equipier" type="button" onclick="openDialog(\'dialog8\', this)">Equipe</button>';

                    echo '<div id="dialog_layer" class="dialogs">';
                    echo '<div role="dialog" id="dialog8" aria-labelledby="dialog1_label" aria-modal="true" class="hidden">';
                    echo '<h2 id="dialog1_label" class="dialog_label">Equipe</h2>';

                    echo "<a class=\"tuteur_4\"></a>";
                    $getid = $_GET['id'];
                    echo "<p>";
                    $allTuteurs = $linkpdo->query('SELECT membre.id_membre, membre.nom, prenom, role FROM suivre, membre WHERE id_enfant= ' . $getid . " AND suivre.id_membre = membre.id_membre;");
                    while ($tuteur = $allTuteurs->fetch()) {
                        echo "<img class=\"img_equipe\" src=\"/sae-but2-s1/img/user_logo.png\" alt=\"Photo du visage de l'utilisateur\">    ";
                        echo " <b>" . $tuteur['nom'] . " " . $tuteur['prenom'] . "</b> role : " . $tuteur['role'] . "    ";
                        echo '<button class="acceder-information-enfant"><a class="equipier" href="page_certif_compte.php?idv='.$tuteur['id_membre'] . '">information</a></button><br>';
                        echo '<button class="acceder-information-enfant"><a class="equipier" href="page_admin.php?id='.$getid.'&eject='.$tuteur['id_membre'] . '">Retirer de l\'équipe</a></button><br>';
                        }
                    if ($allTuteurs = null) {
                        echo "Suivie par aucun tuteur";
                    }
                    echo "</p>";
                    echo '<button type="button" onclick="closeDialog(this)">Annuler</button>';
                    
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                    /* fin de la fenêtre popin de l'ajout d'enfant" */
                 

                    echo "<div class='div-zone-texte'>";
                    echo "<textarea style=\"resize: none\">Informations supplémentaires sur " . $prenom_enfant . " : " . $info_sup . " </textarea>";
                    echo "</div>";

                    echo "</div>";



                    echo "</section>";
                    echo "<section class=\"nb-systeme\">";
                    if ($_SESSION["role_user"] == 1) {
                        echo '   <button class="button_ajouter-objectif"> <a href="page_creatsystem.php">  Ajouter un nouvel objectif</a></button>';
                    }

                    


                    // tous les systèmes de l'enfant :

                    ///Sélection de tout le contenu de la table enfant
                    try {
                        $res = $linkpdo->query('SELECT intitule, nb_jetons, duree, priorite, travaille, id_objectif FROM objectif where id_enfant=' . $id . ' ORDER BY priorite ');
                    } catch (Exception $e) { // toujours faire un test de retour en cas de crash
                        die('Erreur : ' . $e->getMessage());
                    }


                    ///Affichage des entrées du résultat une à une

                    $double_tab = $res->fetchAll(); // je met le result de ma query dans un double tableau
                    $nombre_ligne = $res->rowCount();
                    $liste = array();

                    

                    
                    echo "<table class='affichage-objectif'>";

                    echo "<tr class='titre-objectif'>
                        <th class='test'>Nom</th>
                        <th>Jetons</th>
                        <th>Durée</th>
                        <th>Message</th>
                        <th>Statut</th>
                        <th>Bouton</th>
                        <th>Accéder</th>
                        <th>Supprimer</th>
                        </tr>";
    
                    for ($i = 0; $i < $nombre_ligne; $i++) {
                        if($_SESSION["role_user"]==1 || $double_tab[$i][4]==1){
                        echo "<tr >";

                        #affiche nom
                        echo "<td>";
                        print_r($double_tab[$i][0]);
                        echo "</td>";

                        #affiche nombre de jeton
                        echo "<td>";
                        print_r($double_tab[$i][1]);
                        echo "</td>";

                        #affiche nombre de jour
                        echo "<td>";
                        $value = $double_tab[$i][2];
                        switch ($double_tab[$i][2]) {
                            case ($value < 24?$value:!$value):
                                print_r($double_tab[$i][2]);
                                echo" Heure(s)";
                                break;
                            
                            case ($value < 24*7?$value:!$value):
                                $reste=$value%24;
                                $jours= intdiv($value,24);
                                echo$jours." jour(s), ".$reste." heure(s)";
                                break;
                            
                            default:
                                $semaines= intdiv($value,(7*24));
                                $reste1=$value%(7*24); // pour savoir s'il reste quoi que ce soit 
                                echo$semaines." semaine(s) ";
                                
                                if($reste1>23){ // il reste + d'un jour
                                    $restej=$value-(7*24); // le nombre d'heure au dela d'une semaine
                                    if($reste1>23){ // si ce nombre d'heure au dela d'une semaine dépasse 1 jour
                                        $restejours=intdiv($reste1,24);
                                        echo$restejours."jour(s)";
                                    }

                                }elseif($reste1>0){// s'il reste entre 1 et 23heures
                                    echo$reste1."heure(s)";
                                }
                                break;
                                }
                                
                                
                                
                        
                        
                        echo "</td>";


                        #affiche message
                        echo "<td>";
                        echo '<a href="envoie_membre_message.php?id_objectif=' . $double_tab[$i][5] . '"><button class="message-objectif"> <span class=" icon-mail">&#x2709;</span></button></a>';
                        echo "</td>";

                        #affiche statu
                        echo "<td>";
                        if ($double_tab[$i][4] == 1) {
                             print_r("En Utilisation");
                        } else {
                             print_r("Pas en utilisation");
                        }
                        echo "</td>";

                        #affiche bouton
                        if ($_SESSION["role_user"] == 1) {
                            switch ($double_tab[$i][4]) {
                                case 1:

                                    echo "<td>";
                                    echo '<a href="utilisation.php?id_sys=' . $double_tab[$i][5] . '&valeur=0"><button class="status-objectif">Ne plus utiliser</button></a>';
                                    echo "</td>";
                                    break;

                                case 0:
                                    echo "<td>";
                                    echo '<a href="utilisation.php?id_sys=' . $double_tab[$i][5] . '&valeur=1"><button class="status-objectif">Commencer l\'utilisation</button></a>';
                                    echo "</td>";
                                    break;
                            }
                        }


                        echo "<td>";
                        echo '<a href="choix_sys.php?id_sys=' . $double_tab[$i][5] . '"><button class="objectif-acceder"> Acceder </button></a>';
                        echo "</td>";
                        
                        echo "<td>";
                        echo " <div class=\"case-enfant\">";
                        if ($_SESSION["role_user"] == 1) {  
                            echo "<button class=\"supprimer-objectif\" type=\"button\" onclick=\"openDialog('dialog".$double_tab[$i][5]."', this)\"><img class='delet-icon' src='img/delete.png'></a></button>";
                            echo "<div id=\"dialog_layer\" class=\"dialogs\">";

                            echo "<div role=\"dialog\" id=\"dialog".$double_tab[$i][5]."\" aria-labelledby=\"dialog1_label\" aria-modal=\"true\" class=\"hidden\">";
                            echo "<form action=\"\" method=\"post\" class=\"dialog_form\">";

                            echo "<p> Attention, supprimer ce système est définitif, et supprimera aussi tous les messages associés, plus personne n'y aura accces. ?</p>";
                            echo "<div class=\"dialog_form_actions\">";

                            echo "<button class='sup-objectif'>  <a href=\"suppr_sys.php?id_sys=" . $double_tab[$i][5] . "\">Supprimer le système</button></a>";
                            echo "<button class=\"deco\" onclick=\"closeDialog(this)\">Annuler</button>";
                            echo "</div>";
                            echo "</form>";
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
                    echo "<p></p>";
                    echo "<p></p>";
                    echo "<p></p>";
                    echo "<p></p>";
                    echo "<p></p>";
                    echo "<p></p>";
                    echo "</section>";
                    echo "</div>";
                    echo "<section class=\"nb-systeme\">";
                    echo "</section>";
                }

                // Popup equipier 
                    

                echo '<div role="dialog" id="dialog2" aria-labelledby="dialog1_label" aria-modal="true" class="hidden">';
                //echo '<form enctype="multipart/form-data" action="groupe_validation.php" method="post" class="dialog_form">';
                
                
                try {
                    $res = $linkpdo->query("SELECT * FROM `membre` WHERE compte_valide= 1;");
                } catch (Exception $e) { // toujours faire un test de retour en cas de crash
                    die('Erreur : ' . $e->getMessage());
                }
                
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
                                            //echo "</div>";
                                            echo '</td>';
                                            echo "<td class=\"Profil\" >";
                                            ?>
                                            <form action="groupe_validation.php?id_enfant=<?=$_GET['id']?>&id_membre=<?php echo $double_tab[$i][0]; ?>" method="post">
                                                <button type="submit" >Ajouter</button>
                                            </form>
                                            <?php
                            
                                            echo "</td>";
                                        echo "</tr>";
                                    }
                                    echo "</table>";
                                    
                                    echo '<button type="button" onclick="closeDialog(this)">Annuler</button>';
                                   
                //echo "</form";
            echo "</div";
                ?>
                
                ?>
        </nav>
    </main>


</body>



</html>