<!DOCTYPE html>
<html lang="fr">
<?php
require('fonctions.php');
is_logged();
is_validateur();
?>
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
    <title> Administrateur </title>
    <link rel="stylesheet" href="style_creatsystem.css">
    <script type="text/javascript" src="script.js"></script>
    <link rel="icon" href="logo/icon-admin.png">
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
         <div onclick="window.location.href ='logout.php';" class="h-deconnexion">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="icone_deconnexion">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
            </svg> Déconnexion
        </div>

    </header>
    <main>
        <nav class="div-info-enfant">
            <div class="info-enfant">
            <a class="retour" href="page_admin.php?id=<?php echo $_SESSION['id_enfant']  ?>"> <button class="button-retour" >Retour</button>  </a>
                <?php
                $id = $_SESSION['id_enfant'];
                ///Sélection de tout le contenu de la table carnet_adresse
                try {
                    $res = $linkpdo->query("SELECT * FROM enfant where id_enfant='$id'");
                } catch (Exception $e) { // toujours faire un test de retour au cas ou ça crash
                    die('Erreur : ' . $e->getMessage());
                }



                ///Affichage des entrées du résultat une à une
                while ($data = $res->fetch()) {
                    echo '<div class="div-photo-enfant">';
                    echo "<img class=\"photo-enfant\" src=\"$data[9]\" alt=\"Tête de l'enfant\">";
                    echo '</div>';
                    $date = strval($data[3]);
                    $datefinal = new DateTime($date);
                    echo (ucfirst(
                        "
                        <div class='id-enfant'> <a class='id-nom'>Nom: <strong> $data[1] </strong></a> </div>
                        <div class='id-enfan'> <a class='id-prenm'>Prénom: <strong> $data[2]  </strong></a> </div>
                        <div class='id-enfant'> <a class='id-age'>Date de naissance: <strong> " . date_format($datefinal, 'd/m/Y') . "</strong></a></div>
                        <div class='id-enfants'> <a class='id-adresse'>Adresse: <strong> $data[5] </strong> </a></div>
                        <div class='id-enfants'> <a class='id-activite'>Handicap : <strong> $data[7] </strong> </a></div>
                        <div class='id-enfants'> <a class='id-activite'>Activité : <strong> $data[6] </strong> </a></div>"     
                    )
                    );
                }
                ?>
            
            </div>
        </nav>
        <div class="choix-systeme">
                <form action="test.php" class="forme titre" method="POST" >

                <h2 class="titletype">Choisissez le type d'objectif :</h2>
                    <section class="choix-objectif">
                        <input   type="radio" name="radio1" id="choix11" value="1"> <label class="choix11-label four col detail1" for="choix11">  <div class="image_product"> <img class="img-systeme" src="img/project_images/img1.png"> <p><strong>Objectif Chargement</strong></p> </div></label>
                        <input type="radio" name="radio1" id="choix13" value="3"><label class="choix13-label four col detail2" for="choix13"> <div class="image_product">   <img class="img-systeme" src="img/project_images/img2.png"> <p><strong>Objectif contrat</strong></p>   </div> </label>
                    </section>


                    <h2 class="titletype">Choisissez le type de récompense :</h2>
                    <section class="choix-recompense">
                        
                        <input type="radio" name="radio2" id="choix21" value="4"> <label class="choix21-label four col product detail3" for="choix21">  <div class="image_product"> <img class="img-systeme" src="img/project_images/img3.png">  <p><strong>Récompense Unique</strong></p> </div></label>
                        <input type="radio" name="radio2" id="choix22" value="5"> <label class="choix22-label four col detail4" for="choix22"> <div class="image_product"> <img class="img-systeme" src="img/project_images/img4.png">  <p><strong>Récompense Boutique</strong></p> </div></label>
                    </section>
                    
                    <div class="bouton-objectif">
                    <button type="button" class="annuler" onclick="window.location.href='page_creatsystem.php?id=<?php echo$id ?>'"> Annuler</button>
                    <input class="valider" type="submit" value="Valider">
                    </div>
                </form>
        </div>

    </main>

</html>