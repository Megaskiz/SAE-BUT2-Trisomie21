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

if(isset($_GET['id_objectif']) AND !empty($_GET['id_objectif'])){
    
    /*$getid = $_GET['id_objectif'];
    /*$recupUser = $linkpdo->prepare('SELECT * FROM membre where id_membre = ?');
    $recupUser->execute(array($getid));
    if($recupUser->rowCount() > 0){*/
    if(isset($_POST["envoie2"])){
        $message = htmlspecialchars($_POST['messages']);
        $sujet = htmlspecialchars($_POST['sujet']);
        $insererMessage = $linkpdo->prepare('INSERT into message(corps,sujet,id_membre,date_heure,id_objectif) VALUES(?, ?, ?, NOW(), ?)');
        if(!$insererMessage) {
            die ("Erreur prepare");
        }
        $insererMessage->execute(array($message, $sujet, $_SESSION['logged_user'], $_GET['id_objectif']));
        if(!$insererMessage) {
            die ("Erreur execute");
        }
    }
    /*}else{
        echo ("aucun utilisateur trouvé");
    }*/
    
 
}else{
    echo("aucun id trouvé");
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Envoie de mesage</title>
</head>
<body>
    <form method ="POST" action ="" >
        <label for="sujet">Sujet</label>
        <input type="text" id="sujet" name="sujet" required>
</br>


        <textarea name="messages"></textarea>
        <br>
        <input type="submit" name="envoie2">
    </form>
    
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
  
        while($message = $recupMessages->fetch()){
            if ($message['id_membre'] == $_SESSION['logged_user']){
                ?>
                <p> <?= "le ".(new DateTime($message["date_heure"]))->format( "d/m/Y H\hm").", ".$message["nom"]." ". $message["prenom"]." (vous) : ".$message["corps"]; ?> </p>
              
                <?php 
            }else {
                ?>
                <p> <?= "le ".(new DateTime($message["date_heure"]))->format( "d/m/Y H\hm").", ".$message["nom"]. " ". $message["prenom"].", a écrit : ".$message["corps"]; ?> </p>
                <?php
            }

            
            ?>
            
            
            <?php
        }        
    ?>


    </section>
</body>
</html>