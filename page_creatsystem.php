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
    <link rel="stylesheet" href="style_admin-v2.css">
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
    <style>
            body {
                margin: 0;
                padding: 0;
                color: #333;
                background-color: #eee;
            }

            h1,
            h2,
            h3,
            h4,
            h5,
            h6 {
                font-weight: 100%;
                border-radius: 30px;
                box-shadow: 0 0 10px 0 rgba(0, 0, 0, 0.2), 0 5px 5px 0 rgba(0, 0, 0, 0.24);
                text-align: center;
            }

            h1 {
                text-align: center;
                padding-bottom: 10%;
                border-bottom: 1px solid #2fcc71;
                max-width: 40%;
                margin: 20% auto;
            }


            /* CONTAINERS */

            .container {
                max-width: 850px;
                width: 80%;
                margin: 0 auto;
            }

            .four {
                width: 32.26%;
                max-width: 32.26%;
            }


            /* COLUMNS */

            .col {
                display: block;
                float: left;
                margin: 1% 0 1% 1.6%;
            }

            .col:first-of-type {
                margin-left: 0;
            }


            /* CLEARFIX */

            .cf:before,
            .cf:after {
                content: " ";
                display: table;
            }

            .cf:after {
                clear: both;
            }

            .cf {
                *zoom: 1;
            }


            /* FORM */

            .form .plan input,
            .form .payment-plan input,
            .form .payment-type input {
                display: none;
            }

            .form label {
                position: relative;
                font: 100%;
                padding: 1%;
                height: 150px;
                display: block;
                cursor: pointer;
                border: 25px solid transparent;
                -webkit-box-sizing: border-box;
                -moz-box-sizing: border-box;
                box-sizing: border-box;
                border-radius: 10px;
                box-shadow: 0 0 10px 0 rgba(0, 0, 0, 0.2), 0 5px 5px 0 rgba(0, 0, 0, 0.24);
            }

            .form .plan input:checked+label,
            .form .payment-plan input:checked+label,
            .form .payment-type input:checked+label {
                border: 1.5px solid #333;
                padding: 3%;
                color: #fff;
                background: linear-gradient(101deg, rgb(255, 198, 76) 0%, rgb(69, 45, 253) 100%);
            }

            .form .plan input:checked+label:after,
            form .payment-plan input:checked+label:after,
            .form .payment-type input:checked+label:after {
                content: "\2713";
                width: 40px;
                height: 40px;
                line-height: 40px;
                border-radius: 100%;
                border: 1px solid #333;
                background: linear-gradient(101deg, rgb(255, 198, 76) 0%, rgb(69, 45, 253) 100%);
                vertical-align: middle;
                z-index: 999;
                position: absolute;
                top: -10px;
                right: -10px;
                text-align: center;

            }

            .submit {
                padding: 15px 60px;
                display: grid;
                border: none;
                margin-left: 80%;
                margin-top: 2%;
                margin-bottom: 2%;
                background: linear-gradient(101deg, rgb(255, 198, 76) 0%, rgb(69, 45, 253) 100%);
                color: #fff;
                border: 1.5px solid #333;
                font-size: 100%;
                -webkit-transition: transform 0.3s ease-in-out;
                -o-transition: transform 0.3s ease-in-out;
                transition: transform 0.3s ease-in-out;
                border-radius: 10px;
                box-shadow: 0 0 10px 0 rgba(0, 0, 0, 0.2), 0 5px 5px 0 rgba(0, 0, 0, 0.24);
                
            }
            .Annuler {
                padding: 1% 35%;
                display: grid;
                border: none;
                margin: auto;
                margin-top: 2%;
                background: linear-gradient(101deg, rgb(255, 198, 76) 0%, rgb(69, 45, 253) 100%);
                color: #fff;
                border: 1.5px solid #333;
                font-size: 100%;
                -webkit-transition: transform 0.3s ease-in-out;
                -o-transition: transform 0.3s ease-in-out;
                transition: transform 0.3s ease-in-out;
                border-radius: 10px;
                box-shadow: 0 0 10px 0 rgba(0, 0, 0, 0.2), 0 5px 5px 0 rgba(0, 0, 0, 0.24);
                
            }

            .Annuler:hover {
                cursor: pointer;
            }

            .submit:hover {
                cursor: pointer;
            }

            .choix22-label four col {
                text-align: center;
            }
        </style>

        <nav>
        <form action="page_admin_v2.php">
            <input type="submit" class="Annuler" value="Annuler">
        </form>
        </nav>
        <div>
            <div class="container">

                
                <?php
                if (isset($_POST["radio1"]) & isset($_POST["radio2"])) {
                    $res1 = $_POST["radio1"];
                    $res2 = $_POST["radio2"];

                    if ($res1 == "choix1") {
                        echo $res1 ." et ". $res2;
                        ?><meta http-equiv = "refresh" content = "0;url = https://www.qries.com"/><?php
                       exit();
                    } else if ($res1 == "choix2") {
                        echo $res1 ." et ". $res2;
                        ?><meta http-equiv = "refresh" content = "0;url = https://www.qries.com"/><?php
                        exit();
                    } else if ($res1 == "choix3") {
                        echo $res1 ." et ". $res2;
                        ?><meta http-equiv = "refresh" content = "0;url = https://www.qries.com"/><?php
                        exit();
                    }
                }
                ?>
                <form class="form cf" method="POST">
                    <section class="plan cf">
                        <h2>Choisir le type de système :</h2>
                        <input type="radio" name="radio1" id="choix11" value="choix1"><label class="choix11-label four col" for="choix11">Premier type de système, qui s'apparente à un chargement tout au long du temps
                            choisi</label>
                        <input type="radio" name="radio1" id="choix12" value="choix2"><label class="choix12-label four col" for="choix12">Deuxième type avec plusieurs tâches à accomplir qui rapportent des points</label>
                        <input type="radio" name="radio1" id="choix13" value="choix3"><label class="choix13-label four col" for="choix13">Troisième type un contrat, avec des tâches répétitives</label>
                    </section>
                    <section class="payment-plan cf">
                        <h2>Choisissez le type de récompense :</h2>
                        <input type="radio" name="radio2" id="choix21" value="choix1"><label class="choix21-label four col" for="choix21">l'ensemble du système est orienté vers un prix unique</label>
                        <input type="radio" name="radio2" id="choix22" value="choix2"><label class="choix22-label four col" for="choix22">le système vous permet de gagner un certain nombre de points qui donnent accès à un
                            magasin de récompenses</label>
                        <input type="radio" name="radio2" id="choix23" value="choix3"><label class="choix23-label four col" for="choix23">Une fois
                            terminé, le système propose une banque de coupons qui ont tous la même valeur, de sorte que l'enfant
                            n'en prend qu'un ou plusieurs</label>
                    </section>
                    </br></br>
                    <input class="submit" type="submit" value="Valider">
                </form>
            </div>
        </div>
        
    </main>
</html>
