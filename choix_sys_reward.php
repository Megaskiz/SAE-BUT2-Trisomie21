<!DOCTYPE html>
<html lang="fr">

<?php
    session_start();
        ///Connexion au serveur MySQL
    try {
    $linkpdo = new PDO("mysql:host=localhost;dbname=sae", "root", "");
    }
    ///Capture des erreurs éventuelles
    catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
    }

?>

<head>
    <meta charset="utf-8">
    <title> Administrateur </title>
    <link rel="stylesheet" href="style_choix_sys.css">
    <link rel="icon" href="logo/icon-admin.png">
    <script type = "text/javascript" src="script.js"></script>
</head>

<body>
    <header>
        <img class="grid_item" src="/sae/img/logo trisomie.png" alt="logo de l'association">
        <p class="grid_item" id="logo_personne">logo personne</p>
        
        <?php
            $mail =  $_SESSION['login_user'];
            try {
                $res = $linkpdo->query("SELECT nom, prenom FROM membre where courriel='$mail'");
                }
            catch (Exception $e) { // toujours faire un test de retour au cas ou ça crash
                die('Erreur : ' . $e->getMessage());
                }   
        
            ///Affichage des entrées du résultat une à une
            $double_tab = $res -> fetchAll(); // je met le result de ma query dans un double tableau
            $nombre_ligne = $res -> rowCount(); // =2 car il y a 2 ligne dans ma base
            $liste = array();
            echo"<table>";
            
            for ($i=0; $i < $nombre_ligne; $i++) { 
                echo"<tr>";
                for ($y=0; $y < 2; $y++) { 
                    echo"<td>";
                    print_r($double_tab[$i][$y]);
                    $liste[$y] = $double_tab[$i][$y];
                    echo"</td>";
                }             
                echo"</tr>";
            }
            echo"</table>";
        ?>
        <p class="grid_item"><a href="html_login.php">Déconnexion</a></p>
    </header>
    <main>
        <nav>
            <p>rappel des infos sur le kid</p>
        </nav>
        <div class="grille_choix">
            <div class="choix_sys">
                <p>choix systeme 1</p>
                <p>choix systeme 2</p>
                <p>choix systeme 3</p>
            </div>
            <div class="choix_reward">
                <p>choix récompense 1</p>
                <p>choix récompense 2</p>
                <p>choix récompense 3</p>
            </div>
            <div class="conteneur">
                <p class="contenu">bouton validation</p>    
            </div>
                 
        </div>
    </main>
    <footer>
        <p>nos contact et autres mentions légales</p>
    </footer>
    </html>