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


?>

<head>
    <meta charset="utf-8">
    <title> changement de mot de passe </title>
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
            $res = $linkpdo->query("SELECT nom, prenom FROM membre where courriel='$mail'");
        } catch (Exception $e) { // toujours faire un test de retour au cas ou ça crash
            die('Erreur : ' . $e->getMessage());
        }

        ///Affichage des entrées du résultat une à une

        $double_tab = $res->fetchAll(); // je met le result de ma query dans un double tableau
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
        ?>
         <div onclick="window.location.href ='logout.php';" class="h-deconnexion">
            <img class="img-deco" src="img/deconnexion.png" alt="Déconnexion"> Déconnexion
        </div>
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
                echo'<a data-placement="" class="shortcuts-activity nav-link gl-tab-nav-item active gl-tab-nav-item-active" href="page_certif_compte.php">Mon profil</a>';
                echo'</li>';
                echo'</ul>';
            }
            ?>
        </nav>

        <?php // affichage central de la page, avec les informations 

        ?>
        <!--------------------------------------- menu information sur le membre (droite) -------------------------------------------->
        <nav class="right-contenu">
            <div class="section_membre" style=" display:grid; grid-template-columns: 30% 1fr ; grid-template-rows:1fr;">
                <?php
                

                    //<!---- menu droit information ---->
                    echo "<div class=\"case-membre_1\"  style='display : flex; align-items: flex-end'>";
                    echo"   <a href='page_certif_compte.php?idv='><button class='annuler'> Annuler &#x1F5D9;</button></a>";
                    echo "</div>";

                    echo"<form action=\"ajoute_modif_mon_mdp.php\" method=\"post\"";

                    echo"<div class='grille_4_cases'>";

                    echo "<div class=\"case-3-infos\">";
                        echo"<div>"; 
                        echo"</div>";
                        echo"<div style=\"display:inline-flex; align-items: center;\">";

                        echo '<p> Nouveau mot de passe  :</p><input required = "required" name=mdp_membre type="text" value="">';
                        echo"</div>";

                    echo "</div>";

                    echo "<div class=\"case-3-infos\">";
                        
                    echo "</div>";

                    echo'<input class="valider" type="submit" value="Valider les modifications &#x2714;">';



                    echo"</form>";

                    echo " <div class=\"case-membre_2\">";
                    echo "<p></p>";
                    echo "</div>";
                
                ?>
        </div>

        </nav>
    </main>
</body>

</html>