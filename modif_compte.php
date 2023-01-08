<?php
require('fonctions.php');
is_logged();
?>
<!DOCTYPE html>
<html lang="fr" style="font-family: Arial,sans-serif;">

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
            <?php 
            if($_SESSION["role_user"]!=2){
                echo'<ul class="scrolling-tabs nav-links gl-display-flex gl-flex-grow-1 gl-w-full nav gl-tabs-nav nav gl-tabs-nav">';
               echo'<li class="nav-item">';
               echo'<a class="nav-link gl-tab-nav-item" data-placement="right" href="page_admin.php">Enfant</a>';
               echo'</li>';
                echo'<li class="nav-item">';
                echo'<a data-placement="" class="shortcuts-activity nav-link gl-tab-nav-item active gl-tab-nav-item-active" href="page_certif_compte.php">Membre</a>';
                echo'</li>';
                echo'</ul>';
            }
                    
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
                        echo "<button class=\"acceder\" type=\"button\" onclick=\"openDialog('dialog3', this)\">Valider ce compte membre</button>";
                        echo "<div id=\"dialog_layer\" class=\"dialogs\">";
                        echo "<div role=\"dialog\" id=\"dialog3\" aria-labelledby=\"dialog1_label\" aria-modal=\"true\" class=\"hidden\">";
                        echo "<form action=\"\" method=\"post\" class=\"dialog_form\">";
                        echo "<p>Vous voulez valider ce compte membre dans l'application ?</p>";
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
            $_SESSION["id_compte_modif"]=$id;



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

            // partie sur les roles, pour s'assure que le bon sois préselectionné et ne pas faire d'erreur
            $zero="";
            $un = "";
            $deux = "";
            $trois="";

            switch ($double_tab[0][11]) {
                case '0':
                    $role = 'Utilisateur';
                    $zero="selected";
                    break;
                
                case '1':
                    $role = "Administrateur";
                    $un="selected";
                    break;

                case '2':
                    $role = "Validateur (administration)";
                    $deux="selected";
                    break;

                default:
                    $role = "jsp";
                    $trois="selected";
                    break;
            }


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
                    echo"   <button><a href='page_certif_compte.php?idv=".$_GET['id']."'>Annuler les modifications</a></button>";
                    echo "</div>";

                    echo"<form action=\"ajoute_modif_compte.php\" method=\"post\"";

                    echo"<div class='grille_4_cases'>";

                    echo "<div class=\"case-3-infos\">";
                        echo"<div style=\"display:inline-flex; align-items: center;\">";
                        echo '<p> Nom :</p><input name=nom_membre type="text" value="' . $nom_membre . '">';
                        echo"</div>";

                        echo"<div style=\"display:inline-flex; align-items: center;\">";
                        echo '<p> Prenom :</p><input name=prenom_membre type="text" value="' . $prenom_membre . '">';
                        echo"</div>";

                        echo"<div style=\"display:inline-flex; align-items: center;\">";
                        echo '<p> Date de naissance :</p><input name=ddn_membre type="date" value="' . $date_naissance_membre . '">';
                        echo"</div>";

                        echo"<div style=\"display:inline-flex; align-items: center;\">";
                        echo '<p> Role de l\'utilisateur :</p> <select name="role">
                        <option value="0"'.$zero.'>utilisateur(s)</option>
                        <option value="1"'.$un.'>Administrateur</option>
                        <option value="2"'.$deux.'>Validateur (administration)</option>
                        <option value="3"'.$trois.'>jsp, a modifier</option>
                        </select>';
                        echo"</div>";

                    echo "</div>";

                    echo "<div class=\"case-3-infos\">";
                        echo"<div style=\"display:inline-flex; align-items: center;\">";
                        echo '<p> E-mail :</p><input name=mail_membre type="text" value="' . $courriel_membre . '">';
                        echo"</div>";

                        echo"<div style=\"display:inline-flex; align-items: center;\">";
                        echo '<p> Adresse :</p><input name=ad_membre type="text" value="' . $adresse_membre . '">';
                        echo"</div>";

                        echo"<div style=\"display:inline-flex; align-items: center;\">";
                        echo '<p> Code postal :</p><input name=cpostal_membre type="text" value="' . $code_postal_membre . '">';
                        echo"</div>";

                        echo"<div style=\"display:inline-flex; align-items: center;\">";
                        echo '<p> Ville :</p><input name=ville type="text" value="' . $ville_membre . '">';
                        echo"</div>";
                    echo "</div>";

                    echo'<input class="button" type="submit" value="Valider les modifications">';



                    echo"</form>";

                    echo " <div class=\"case-membre_2\">";
                    echo "<p></p>";
                    echo "</div>";
                }
                ?>
        </div>

        </nav>
    </main>
</body>

</html>