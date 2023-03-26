<!DOCTYPE html>
<html lang="fr" style="font-family: Arial,sans-serif;">

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="style_css/style_objectif.css" media="screen" type="text/css" />
    <title>récompenses</title>
    <div id="color-picker-container">


        <div id="color-bar"> </div>

        <input type="color" id="color-picker">

    </div>
    <script src="js/jquery.js"></script>
    <script src="js/jquery_ui.js"></script>
    <script src="js/confetti.js"></script>
    <script type="text/javascript" src="objectif.js"></script>


<body style="background-color: <?php echo (isset($_SESSION['bg-color'])) ? $_SESSION['bg-color'] : '#afeeee'; ?>">



    <script>
        // Récupérer la valeur stockée dans sessionStorage
        var bgColor = sessionStorage.getItem("bg-color");
        // Vérifier si la valeur existe
        if (bgColor) {
            // définir la couleur de fond de la page en utilisant la valeur stockée
            document.querySelector("body").style.backgroundColor = bgColor;
        }
    </script>
    <script>
        var colorPicker = document.getElementById("color-picker");
        colorPicker.addEventListener("change", function () {
            localStorage.setItem("bg-color", colorPicker.value);
        });
    </script>
    <?php
    if (isset($_GET['feux'])) {
        if ($_GET['feux'] == '1') {
            ?>

            <script>

                function fire(ratio, opt) {
                    confetti(Object.assign({}, opt, {
                        origin: { y: .6 },
                        particleCount: Math.floor(200 * ratio)
                    }));
                }

                fire(0.25, {
                    spread: 26,
                    startVelocity: 55,
                });
                fire(.2, { spread: 60 });
                fire(.35, {
                    spread: 100,
                    decay: .91,
                    scalar: .8

                });
                fire(.1, {
                    spread: 120,
                    startVelocity: 25,
                    decay: .92,
                    scalar: 1.2
                });
                fire(2.5, {
                    spread: 120,
                    startVelocity: 45,
                });
                fire();
            </script>
            <?php
        }

    }
    ?>
    <script>
        fire();
    </script>
    </div>
    </head>
    <?php
    require_once('fonctions.php');
    is_logged();
    is_validateur();
    $linkpdo = connexionBd();
    echo "<a href=\"objectif.php?id_sys=" . $_SESSION['id_sys'] . "\"><button>retour</button></a>";
    ?>
    <h1>Les récompenses</h1>
    <?php
    
    try {
        $res = $linkpdo->query("select * from recompense where id_recompense in (SELECT id_recompense FROM lier where id_objectif=" . $_SESSION['id_sys'] . ")");
    } catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }
    $double_tab = $res->fetchAll();
    $lignes = $res->rowCount();
    ?>

    <table style="margin: auto;">
    <caption></caption>
        <tr>
            <th>
                <p>Nom de la récompense</p>
            </th>
            <th>
                <p>Description de la récompense</p>
            </th>
            <th>
                <p>image de la récompense</p>
            </th>
        </tr>
        <?php
        for ($i = 0; $i < $lignes; $i++) { //affichage des récompenses ATTENTION il faut faire l'affichage de l'image de la récompense
            echo "
    <tr>
    <td><p>" . $double_tab[$i][1] . "</p></td>
    <td><p>" . $double_tab[$i][2] . "</p></td>
    <td>
        <center>
            <img src=" . $double_tab[$i][3] . " alt=\"image de la récompense\" >
        </center>
    </td>
</tr>";
        }
        ?>
    </table>