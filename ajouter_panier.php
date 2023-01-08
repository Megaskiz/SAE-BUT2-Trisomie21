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



 //creer la session
 if(!isset($_SESSION['panier'])){
    //s'il nexiste pas une session on créer une et on mets un tableau a l'intérieur 
    $_SESSION['panier'] = array();
 }
 //récupération de l'id dans le lien
  if(isset($_GET['id'])){//si un id a été envoyé alors :
    $id = $_GET['id'] ;

    //verifier grace a l'id si le système existe dans la base de  données
    if(empty($_POST['id'])){
        //si ce systeme n'existe pas
        die("Ce systeme n'existe pas");
    }
    //ajouter le systeme dans le panier ( Le tableau)





    



    if(isset($_SESSION['panier'][$id])){// si le systeme est déjà dans le panier 
        $_SESSION['panier']; //Représente la quantité
    }else {
        //si non on ajoute le systeme
        $_SESSION['panier'][$id]= 1 ;
    }

   //redirection vers la page banque_systeme.php
   header("Location:banque_systeme.php");


  }
?>