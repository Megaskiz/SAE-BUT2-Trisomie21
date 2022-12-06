<!DOCTYPE html>
<html lang="fr">

<?php
session_start();
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
    <link rel="stylesheet" href="style_creation_systemes.css">
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
        ?>
        <p class="h-deconnexion"><button class="deco" onclick="window.location.href ='html_login.php';">Déconnexion</button></p>
    </header>


    <!--------------------------------------------------------------- Contenu ------------------------------------------------------------------->

    <main>
<style>
    table, td {
    border-collapse:collapse;
    border:solid black 1px;
    padding: 1rem;
    }
    form {
        max-width:1000px;
        margin:auto;
    }
    label {
        display:block;
        background: rgb(212, 210, 210);
        padding:10px;
    }
</style>           
        </form>
        <form action="insert_systeme__bd.php" method="post">
            <div class="flex_simple">
                <label>combien de cases voulez vous inserer ? : </label>
                <input type="number" name="rows"  >
            </div>
            <div class="flex_simple">
                <label>Quelle sera la condition d'ajout d'un jeton : </label>
                <input type="text" width=100% name="prio" placeholder="ecrivez ce que l'enfant doit faire">
            </div>  
            <div class="flex_simple">
                <label>Quel est le nom de ce système ?  : </label>
                <input type="text" name="nom" placeholder="ecrivez le nom ici">
            </div>
            <div class="flex_simple">
                <label>Quel est la durée de ce système ?  : </label>
                <input type="text" name="duree" placeholder="ecrivez la duree ici">
            </div>
            <div class="flex_simple">
                <label>Quel est la priorité de ce système ?  : </label>
                <input type="text" name="prio" placeholder="ecrivez le niveau de priorite ici">
            </div>

            <div class="flex_simple">
            <label>Quelle sera l'image pour le jeton : </label>
            <input type="text" name="prio" placeholder="ecrivez le niveau de priorite ici">
            </div>

            <input class="button" type="submit" value="Valider le système">
        </form>
        <br>
        <br>
        <div style="float:right;">
            <button class="annuler"><a href="page_admin.php">annuler la creation de système</a></button>
        </div>
    </main>

    <!------------------------------------------------------- Footer -------------------------------------------------->
    <footer>

        <img class="footer-logo-association" src="/sae-but2-s1/img/logo_trisomie.png" alt="logo de l'association">

        <div class="f-contenu">
            <div class="f-menu">
                <li><p class="f-info">Qui sommes nous ?</p></li>
                <li><p class="f-propos">A propos</p></li>
                <li><p class="f-association">Association</p></li>
            </div>
            <p class="f-copyright">© Copyright 2022 </p>
        </div>
       
        
    </footer>
</body>



</html>