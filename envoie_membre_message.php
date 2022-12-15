<!DOCTYPE html>
<html lang="fr" style="font-family: Arial,sans-serif;">

<?php
session_start();
try {
    $linkpdo = new PDO("mysql:host=localhost;dbname=bddsae", "root", "");
}
///Capture des erreurs éventuelles
catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}
if (!$_SESSION['logged_user']) {
    header('Location: connection.php');
}

if (isset($_GET['id_objectif']) and !empty($_GET['id_objectif'])) {

    /*$getid = $_GET['id_objectif'];
        /*$recupUser = $linkpdo->prepare('SELECT * FROM membre where id_membre = ?');
        $recupUser->execute(array($getid));
        if($recupUser->rowCount() > 0){*/
    if (isset($_POST["envoie2"])) {
        $message = htmlspecialchars($_POST['messages']);
        $sujet = htmlspecialchars($_POST['sujet']);
        $insererMessage = $linkpdo->prepare('INSERT into message(corps,sujet,id_membre,date_heure,id_objectif) VALUES(?, ?, ?, NOW(), ?)');
        if (!$insererMessage) {
            die("Erreur prepare");
        }
        $insererMessage->execute(array($message, $sujet, $_SESSION['logged_user'], $_GET['id_objectif']));
        if (!$insererMessage) {
            die("Erreur execute");
        }
    }
    /*}else{
            echo ("aucun utilisateur trouvé");
        }*/
} else {
    echo ("aucun id trouvé");
}
?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style_membre_msg.css">
    <title>Envoie de mesage</title>
</head>

<body>
    <div class="all">
        <div class="list_msg">
            <section id="message">
                <?php
                $recupMessages = $linkpdo->prepare('SELECT corps,date_heure,membre.id_membre, membre.nom, membre.prenom FROM message,membre WHERE id_objectif = ? and membre.id_membre = message.id_membre');
                if (!$recupMessages) {
                    die("Erreur prepare");
                }
                $recupMessages->execute(array($_GET['id_objectif']));
                if (!$recupMessages) {
                    die("Erreur prepare");
                }

                while ($message = $recupMessages->fetch()) {
                    if ($message['id_membre'] == $_SESSION['logged_user']) {
                ?>
                        <p> <?= "le " . (new DateTime($message["date_heure"]))->format("d/m/Y H\hm") . ", " . $message["nom"] . " " . $message["prenom"] . " (vous) : " . $message["corps"]; ?> </p>
                    <?php
                    } else {
                    ?>
                        <p> <?= "le " . (new DateTime($message["date_heure"]))->format("d/m/Y H\hm") . ", " . $message["nom"] . " " . $message["prenom"] . ", a écrit : " . $message["corps"]; ?> </p>
                    <?php
                    }
                    ?>
                <?php
                }
                ?>
            </section>
        </div>
        <div class="envoi_msg">
            <form method="POST" action="" class="">
                <div class="sujet_msg">
                    <label for="sujet">Sujet</label>
                    <input type="text" id="sujet" class="sujet" name="sujet" required></br>
                </div>
                <div class="txt_msg">
                    <textarea class="messages" name="messages"></textarea></br>
                </div>
                <div class="envoi">
                    <input type="submit" name="envoie2">
                </div>
            </form>
        </div>
    </div>
</body>

</html>