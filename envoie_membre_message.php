<!DOCTYPE html>
<script>
    window.onload = function() {
        var objDiv = document.querySelector(".chat_list_msg");
        objDiv.scrollTop = objDiv.scrollHeight;
    }
</script>
<html lang="fr">

<?php
/**
 * @file envoie_membre_message.php
 * @brief Page d'envoie de message à un membre
 * @details Page d'envoie de message à un membre, permet à l'utilisateur de pouvoir envoyer un message à un membres
 * @version 1.0
 */
require_once('fonctions.php');
is_logged();
is_validateur();
$linkpdo = connexionBd();
if (!$_SESSION['logged_user']) {
    header('Location: login.php');
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
        header('Location: envoie_membre_message.php?id_objectif=' . $_GET['id_objectif']);
        exit;
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
    <link rel="stylesheet" href="style_css/style_membre_msg.css">
    <title>Envoie de message</title>
</head>

<body>

    <main>
        <?php
        echo '<div class="message-chat"> <a href="index.php?id=' . $_SESSION['id_enfant'] . '" > <button class="chat_retour" >Retour</button> </a> </div>';
        ?>

        <div class="chat_all">
            <div class="chat_title">&#128172; Messagerie du système à jeton </div>
            <div class="chat_list_msg">
                <section id="message">
                    <?php
                    $recupMessages = $linkpdo->prepare('SELECT sujet,corps,date_heure,membre.id_membre, membre.nom, membre.prenom FROM message,membre WHERE id_objectif = ? and membre.id_membre = message.id_membre');
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
                            <div class="chat_msgR">
                                <img class="chat_img_R" src="/sae-but2-s1/img/user_logo.png" alt="tete de l'utilisateur">
                                <div class="chat_vous">
                                    <div class="chat_info">
                                        <div class="chat_nomm"><?= ucfirst($message["nom"] . " " . $message["prenom"] . " (vous) : ") ?></div>
                                        <div class="chat_datem"><?= "le " . (new DateTime($message["date_heure"]))->format("d/m/Y H\hi") ?></div>
                                    </div>
                                    <p class=""> <?= "Sujet :" . $message["sujet"] . "<br>" . $message["corps"]; ?> </p>
                                </div>
                            </div>
                        <?php
                        } else {
                        ?>
                            <div class="chat_msgL">
                                <img class="chat_img_L" src="/sae-but2-s1/img/user_logo.png" alt="tete de l'utilisateur">
                                <div class="chat_autre">
                                    <div class="chat_info">
                                        <div class="chat_nomm"><?= ucfirst($message["nom"] . " " . $message["prenom"]) ?></div>
                                        <div class="chat_datem"><?= "le " . (new DateTime($message["date_heure"]))->format("d/m/Y H\hi") ?></div>
                                    </div>
                                    <p class=""> <?= "Sujet :" . $message["sujet"] . "<br>" . $message["corps"]; ?> </p>
                                </div>
                            </div>
                        <?php
                        }
                        ?>
                    <?php
                    }
                    ?>
                    <?php
                    ?>

            </div>
            <div class="chat_envoi_msg">
                <form method="POST" action="" class="">
                    <div class="chat_sujet_msg">
                        <input type="text" id="sujet" name="sujet" class="chat_sujet" placeholder="Sujet ..." required></br>
                    </div>
                    <div class="chat_txt_msg">
                        <input class="chat_messages" name="messages" placeholder="Entrez votre message ..." required></br>

                        <input type="submit" class="chat_send" name="envoie2">
                    </div>
                </form>
            </div>
        </div>
        </div>
        </section>
</body>



</html>