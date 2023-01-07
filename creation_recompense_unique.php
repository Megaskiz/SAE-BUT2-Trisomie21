<?php
require('fonctions.php');
is_logged();
is_validateur();
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
        <p class="h-deconnexion"><button class="deco" onclick="window.location.href ='logout.php';">Déconnexion</button></p>
    </header>


    <!--------------------------------------------------------------- Contenu ------------------------------------------------------------------->

    <main>


        

        <form action="insert_recompense_bd.php" enctype="multipart/form-data" method="post">
            <h1 class="flex-simple">Création d'une récompense de type : "unique"</h1>
            <div class="flex_simple">
                <label>Quel sera le nom de la récompense : </label>
                <input type="text" width=100% name="intitule" placeholder="ecrivez ce que l'enfant doit faire">
            </div>  
            <div class="flex_simple">
                <label>Quelle sera la déscription de la récompense  : </label>
                <input type="text" name="descriptif" placeholder="ecrivez le nom ici">
            </div>

            <div class="flex_simple">
            <label>Quelle sera l'image associée à cette récompense : </label>
            <input name="photo_recompense" type="file" class="zip_input" required="required">
            </div>

            <input style="float:right;"class="button" type="submit" value="Valider la récompense et enregistrer le système">
        </form>
        <div>
        <button class="button_annuler_sys"><a href="page_admin.php?id=<?php echo$_SESSION['id_enfant']?>">annuler la création de cette récompense</a></button>
        </div>
    </main>
</body>



</html>