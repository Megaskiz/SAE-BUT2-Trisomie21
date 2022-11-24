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

?>

<head>
    <title> Administrateur </title>
    <link rel="stylesheet" href="style_creatsystem.css">
    <link rel="icon" href="logo/icon-admin.png">
</head>

<body>
    <header>
        <img class="grid_item" src="/sae/img/logo trisomie.png" alt="logo de l'association">
        <p class="grid_item" id="logo_personne">logo personne</p>

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

        <p class="grid_item"><a href="page_login.php">Déconnexion</a></p>
    </header>
    <main>
        <nav>
            <form action="page_admin_v2.php">
                <input type="submit" class="annuler" value="Annuler">
            </form></br>
            <div class="info-enfant">
                <div class="photo-enfant">
                    <h2>Photo</h2>
                </div>

                <?php
                $string = $_SERVER['HTTP_REFERER'];
                $id = substr($string, -1);
                ///Sélection de tout le contenu de la table carnet_adresse
                try {
                    $res = $linkpdo->query("SELECT * FROM enfant where id_enfant='$id'");
                } catch (Exception $e) { // toujours faire un test de retour au cas ou ça crash
                    die('Erreur : ' . $e->getMessage());
                }

                ///Affichage des entrées du résultat une à une
                while ($data = $res->fetch()) {
                    $date = strval($data[3]);
                    $datefinal = new DateTime($date);
                    echo (ucfirst("<div class='id-enfant'> <a class='id-nom'>Nom enfant : $data[1]</a> </div>
                        <div class='id-enfan'> <a class='id-prenm'>Prénom enfant : $data[2]</a> </div>
                        <div class='id-enfant'> <a class='id-age'>Date de naissance enfant : " . date_format($datefinal, 'd/m/Y') . "</a></div>
                        <div class='id-enfants'> <a class='id-adresse'>Adresse enfant : ???</a></div>
                        <div class='id-enfants'> <a class='id-activite'>Activité enfant : ???</a></div>")
                    );
                }
                ?>
            </div>
        </nav>
        <div>
            <div class="container">

                <?php
                if (isset($_POST["radio1"]) & isset($_POST["radio2"])) {
                    $res1 = $_POST["radio1"];
                    $res2 = $_POST["radio2"];

                    if ($res1 == "choix1") {
                        echo $res1 . " et " . $res2;
                ?>
                        <meta http-equiv="refresh" content="0;url = https://www.qries.com" /><?php
                                                                                                exit();
                                                                                            } else if ($res1 == "choix2") {
                                                                                                echo $res1 . " et " . $res2;
                                                                                                ?>
                        <meta http-equiv="refresh" content="0;url = https://www.qries.com" /><?php
                                                                                                exit();
                                                                                            } else if ($res1 == "choix3") {
                                                                                                echo $res1 . " et " . $res2;
                                                                                                ?>
                        <meta http-equiv="refresh" content="0;url = https://www.qries.com" /><?php
                                                                                                exit();
                                                                                            }
                                                                                        }
                                                                                                ?>
                <form class="forme titre" method="POST">
                    <section class="plan titre">
                        <h2 class="titletype">Choisir le type de système :</h2>
                        <input type="radio" name="radio1" id="choix11" value="choix1"><label class="choix11-label four col" for="choix11">Premier type de système, qui s'apparente à un chargement tout au long du temps
                            choisi</label>
                        <input type="radio" name="radio1" id="choix12" value="choix2"><label class="choix12-label four col" for="choix12">Deuxième type avec plusieurs tâches à accomplir qui rapportent des points</label>
                        <input type="radio" name="radio1" id="choix13" value="choix3"><label class="choix13-label four col" for="choix13">Troisième type un contrat, avec des tâches répétitives</label>
                    </section>
                    <section class="payment-plan titre">
                        <h2 class="titletype">Choisissez le type de récompense :</h2>
                        <input type="radio" name="radio2" id="choix21" value="choix1"><label class="choix21-label four col" for="choix21">l'ensemble du système est orienté vers un prix unique</label>
                        <input type="radio" name="radio2" id="choix22" value="choix2"><label class="choix22-label four col" for="choix22">le système vous permet de gagner un certain nombre de points qui donnent accès à un
                            magasin de récompenses</label>
                        <input type="radio" name="radio2" id="choix23" value="choix3"><label class="choix23-label four col" for="choix23">Une fois
                            terminé, le système propose une banque de coupons qui ont tous la même valeur, de sorte que l'enfant
                            n'en prend qu'un ou plusieurs</label>
                    </section>
                    </br>
                    </br>
                    <input class="submit" type="submit" value="Valider">
                </form>
            </div>
        </div>

    </main>

</html>