<?php
require('fonctions.php');
is_logged();
is_user();
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
    $req_add = "UPDATE `membre` SET `visibilite` = '0' WHERE `membre`.`id_membre` =$id_valider_membre ;";
    try {
        $res = $linkpdo->query($req_add);
        header('Location: page_certif_compte.php');
    } catch (Exception $e) { // toujours faire un test de retour au cas ou ça crash
        die('Erreur : ' . $e->getMessage());
    }
}
if (isset($_GET['id_invalider'])) {
    $id_invalider_membre = $_GET['id_invalider'];
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
    <link rel="stylesheet" href="style_page_certif_account.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            $res = $linkpdo->query("SELECT nom, prenom FROM membre where courriel='$mail' ORDER BY nom;");
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
            <img class="img-deco" src="img/deconnexion.png" alt="Déconnexion"> Déconnexion
        </div>
    </header>


    <!--------------------------------------------------------------- Contenu ------------------------------------------------------------------->

    <!--------------------------------------- menu liste membre (gauche) -------------------------------------------->
    <main>

        <nav class="left-contenu">
        <div style="margin:auto; width:50%  ">
                <center>
                    <button class="ajouter-membre" style="margin-top: 5%;"><a href="page_certif_compte.php">retour</a></button>
                </center>
            </div>
            
            
           
                <div class="dropdown">
                    <button onclick="myFunction()" class="dropbtn">Compte membre ☰</button>
                    <div id="myDropdown" class="dropdown-content">
                    <?php
                    
                    
                    ///Sélection de tout le contenu de la table 
                    try {
                        $res = $linkpdo->query("SELECT * FROM `membre` WHERE visibilite = 1 and compte_valide= 1");
                    } catch (Exception $e) { // toujours faire un test de retour en cas de crash
                        die('Erreur : ' . $e->getMessage());
                    }

                    ///Affichage des entrées du résultat une à une

                    $double_tab = $res->fetchAll(); // je met le result de ma query dans un double tableau
                    $nombre_ligne = $res->rowCount();
                    $liste = array();
                    echo"<div>";
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
                            echo ' <a href="archive_membre.php?idv=' . $identifiant . '">  <button  class="acceder-information-membre"> Profil </button></a>';
                            
                        echo "</td>";
                        echo "</tr>";
                    }
                    echo "</table>";

                    
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
                $res = $linkpdo->query("SELECT * FROM membre,suivre where membre.id_membre='$id' ORDER BY nom;");
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
            <div  class="section_membre">
                <?php
                if (isset($_GET['id'])) {
                    //<!---- menu droit information ---->
                    echo "<div class=\"case-membre_1\">";
                    echo "<img class=\"img-tuteur\" src=\"/sae-but2-s1/img/user_logo.png\" alt=\"tete de l'utilisateur\">";
                    echo "</div>";

                    echo "<div class='grille_2_cases'>";


                    echo "<div class=\"case-3-infos\">";
                    echo "<p class=\"info\"> Nom :<strong> ".htmlspecialchars($nom_membre)."</strong></p>";
                    echo "<p class=\"info\">Prénom : <strong>".htmlspecialchars($prenom_membre)."</strong></p>";
                    echo "<p class=\"info\">Date de naissance : <strong>".htmlspecialchars($date_naissance_membre)."</strong></p>";
                    echo "</div>";

                    echo "<div class=\"case-3-infos\">";
                    echo "<p class=\"info\">Adresse mail : <strong>".htmlspecialchars($courriel_membre)."</strong></p>";
                    echo "<p class=\"info\">Adresse : <strong>".htmlspecialchars($adresse_membre)."".htmlspecialchars($ville_membre)."</strong></p>";
                    echo "<p class=\"info\">Code postal : <strong> ".htmlspecialchars($code_postal_membre)." </strong> </p>";
                    echo "</div>";

                    echo"</div>";
                    echo " <div class=\"case-membre_2\">";
                    if($_SESSION["role_user"]!=3){
                    echo "<button class=\"certifmembre\" type=\"button\" onclick=\"openDialog('dialog".$_GET['id']."', this)\">Valider ce compte membre</button>";
                    echo "<div id=\"dialog_layer\" class=\"dialogs\">";
                    echo "<div role=\"dialog\" id=\"dialog".$_GET['id']."\" aria-labelledby=\"dialog1_label\" aria-modal=\"true\" class=\"hidden\">";
                    echo "<form action=\"\" method=\"post\" class=\"dialog_form\">";
                    echo "<p class='popup-txt'>Vous voulez valider ce compte membre dans l'application !</p>";
                    echo "<div class=\"dialog_form_actions\">";
                    echo "<button  class='popup-btn' onclick=\"closeDialog(this)\">Annuler</button>";
                    echo '<a class="popup-btn" href="page_certif_compte.php?id_valider='.$_GET['id'].'">Valider</a>';
                    echo "</div>";
                    echo "</form>";
                    echo "</div>";
                    echo "</div>";
                    }
                }
                if (isset($_GET['idv'])) {
                    $idiv=$_GET['idv'];
                    //<!---- menu droit information ---->
                    echo "<div class=\"case-membre_1\">";
                    echo "<img class=\"img-tuteur\" src=\"/sae-but2-s1/img/user_logo.png\" alt=\"tete de l'utilisateur\">";
                    echo "</div>";

                    echo "<div class='grille_2_cases'>";

                    echo "<div class=\"case-3-infos\">";
                    echo "<p class=\"info\">  Nom :<strong>".htmlspecialchars( $nom_membre)."</strong></p>";
                    echo "<p class=\"info\">Prénom : <strong>".htmlspecialchars($prenom_membre)."</strong></p>";
                    echo "<p class=\"info\">Date de naissance : <strong>".htmlspecialchars($date_naissance_membre)."</strong></p>";
                    echo "<p class=\"info\">Role de l'utilisateur : <strong>$role</strong></p>";


                    echo "</div>";

                    echo "<div class=\"case-3-infos\">";
                    echo "<p class=\"info\">Adresse mail : <strong>".htmlspecialchars($courriel_membre)."</strong></p>";
                    echo "<p class=\"info\">Adresse : <strong> ".htmlspecialchars($adresse_membre)."".htmlspecialchars($ville_membre)." </strong></p>";
                    echo "<p class=\"info\">Code postal : <strong> ".htmlspecialchars($code_postal_membre)."</strong> </p>";
                    echo "</div>";

                    echo"</div>";

                    echo " <div class=\"case-membre_2\">";
                    if($_SESSION["role_user"]!=3){
                    if ( $idiv!=$_SESSION['logged_user']){
                        if($_GET["idv"]!=1){
                        
                        
                    echo "<button  class=\"valider\" type=\"button\" onclick=\"openDialog('dialogI".$idiv."', this)\">Restaurer ce compte membre</button>";

                    echo "<div id=\"dialog_layer\" class=\"dialogs\">";
                    echo "<div role=\"dialog\" id=\"dialogI".$idiv."\" aria-labelledby=\"dialog1_label\" aria-modal=\"true\" class=\"hidden\">";
                    echo "<form action=\"\" method=\"post\" class=\"dialog_form\">";

                    echo "<p class='popup-txt'>Voulez-vous restaurer ce compte membre dans l'application ?</p>";
                    echo "<div class=\"dialog_form_actions\">";
                    echo "<button  class='popup-btn' onclick=\"closeDialog(this)\">Annuler</button>";
                    echo '<a class="popup-btn" href="archive_membre.php?id_valider='.$idiv.'">Restaurer</a>';
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
            
            ?>
        </div>

        </nav>
    </main>
</body>

</html>