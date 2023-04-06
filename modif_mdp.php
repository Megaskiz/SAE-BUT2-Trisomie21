<?php
require_once('fonctions.php');
is_logged();

?>
<!DOCTYPE html>
<html lang="fr" style="font-family: Arial,sans-serif;">

<?php

$linkpdo = connexionBd();

if (isset($_GET['id_valider'])) {
    $id_valider_membre = $_GET['id_valider'];
    $req_add = "UPDATE `membre` SET `compte_valide` = '1' WHERE `membre`.`id_membre` =$id_valider_membre ;";
    try {
        $res = $linkpdo->query($req_add);
        header('Location: page_certif_compte.php');
    } catch (Exception $e) { 
        die('Erreur : ' . $e->getMessage());
    }
}
if (isset($_GET['id_invalider'])) {
    $id_invalider_membre = $_GET['id_invalider'];
    $req_add = "UPDATE `membre` SET `compte_valide` = '0' WHERE `membre`.`id_membre` =$id_invalider_membre ;";
    try {
        $res = $linkpdo->query($req_add);
        header('Location: page_certif_compte.php');
    } catch (Exception $e) { 
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
       <!--------------------------------------------------------------- header ------------------------------------------------------------------->
<?php create_header($linkpdo);?>


    <!--------------------------------------------------------------- Contenu ------------------------------------------------------------------->

    <!--------------------------------------- menu liste membre (gauche) -------------------------------------------->
    <main>

        <nav class="left-contenu">
            <?php 
            if($_SESSION["role_user"]!=2){
                echo'<ul class="scrolling-tabs nav-links gl-display-flex gl-flex-grow-1 gl-w-full nav gl-tabs-nav nav gl-tabs-nav">';
               echo'<li class="nav-item">';
               echo'<a class="nav-link gl-tab-nav-item" data-placement="right" href="index.php">Enfant</a>';
               echo'</li>';
                echo'<li class="nav-item">';
                echo'<a data-placement="" class="shortcuts-activity nav-link gl-tab-nav-item active gl-tab-nav-item-active" href="page_certif_compte.php">Membre</a>';
                echo'</li>';
                echo'</ul>';
            }
                    
                     
                    try {
                        $res = $linkpdo->query("SELECT * FROM `membre` WHERE compte_valide= 1;");
                    } catch (Exception $e) { 
                        die('Erreur : ' . $e->getMessage());
                    }

                    

                    $double_tab = $res->fetchAll(); 
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
                                echo '<button class="acceder-information-membre"> <a href="page_certif_compte.php?idv='.$identifiant.'">Profil</a> </button>';
                            echo "</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                    
                    
                    $res->closeCursor();
                    ///--------------------------------------------------------------------membre non valide-------------------------------------------
                    
                    echo "<div class='divider'><span></span><span>Demande de compte membre</span><span></span></div>";
                     
                    try {
                        $res = $linkpdo->query("SELECT * FROM `membre` WHERE compte_valide= 0;");
                    } catch (Exception $e) { 
                        die('Erreur : ' . $e->getMessage());
                    }

                    

                    $double_tab = $res->fetchAll(); 
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
                        
                        echo "</form>";
                        echo "</div>";
                        echo '</td>';
                        echo "<td class=\"Profil2\" >";
                        echo '<a href="page_certif_compte.php?id=' . $identifiant . '"><button class="acceder-information-membre">Profil</button></a>';
                        echo "</td>";
                        echo "</tr>";
                    }
                    echo "</table>";

                    
                    $res->closeCursor();
                    ?>
        </nav>

        <?php // affichage central de la page, avec les informations 

        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $_SESSION["id_compte_modif"]=$id;




            


            

// ne sert a rien je crois : 
            // try {
            //     $res = $linkpdo->query("SELECT * FROM suivre natural join membre  where id_membre='$id'");
            // } catch (Exception $e) { 
            //     die('Erreur : ' . $e->getMessage());
            // }


            // 

            // $double_tab_tuteur = $res->fetchAll(); 
            // $nombre_ligne = $res->rowCount(); 
            // $liste = array();
        }
        ?>
        <!--------------------------------------- menu information sur le membre (droite) -------------------------------------------->
        <nav class="right-contenu">
            <div class="section_membre" style=" display:grid; grid-template-columns: 30% 1fr ; grid-template-rows:1fr;">
                <?php
                if (isset($_GET['id'])) {

                    //<!---- menu droit information ---->
                    echo "<div class=\"case-membre_1\"  style='display : flex; align-items: flex-end'>";
                    echo"   <a href='page_certif_compte.php?idv=".$_GET['id']."'><button class='annuler'> Annuler &#x1F5D9;</button></a>";
                    echo "</div>";

                    echo"<form action=\"appel_fonction.php?appel=modif_mdp\" method=\"post\"";

                    echo"<div class='grille_4_cases'>";

                    echo "<div class=\"case-3-infos\">";
                        echo"<div>"; 
                        echo"</div>";
                        echo"<div style=\"display:inline-flex; align-items: center;\">";

                        echo '<p> Nouveau mot de passe  :</p><input name=mdp_membre type="text" value="">';
                        echo"</div>";

                    echo "</div>";

                    echo "<div class=\"case-3-infos\">";
                        
                    echo "</div>";

                    echo'<input class="valider" type="submit" value="Valider &#x2714;">';



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