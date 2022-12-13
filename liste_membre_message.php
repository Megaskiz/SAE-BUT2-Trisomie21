    <?php
    session_start();
    try {
        $linkpdo = new PDO("mysql:host=localhost;dbname=bddsae", "root", "");
        }
        ///Capture des erreurs éventuelles
        catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
        }
    if(!$_SESSION['logged_user']){
        header('Location: connection.php');
    }
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>La liste des users du site</title>
    </head>
    <body>
        
    </body>



        <?php
            echo("id : ".$_SESSION['logged_user']);
            $recupUser = $linkpdo->query("SELECT * FROM membre where id_membre<>".$_SESSION['logged_user']);
            while($user = $recupUser->fetch()){
                ?>
                <a href ="envoie_membre_message.php?id_membre=<?php echo $user['id_membre']; ?>">

                    <p><?php echo $user['prenom']; ?></p></a>
                <?php

            }
        ?>


            
    </html>