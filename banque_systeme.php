<?php 
 session_start() ;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Boutique</title>
    <link rel="stylesheet" href="style_banque.css">
</head>
<body>
    <!-- afficher le nombre de produit dans le panier -->
    <a href="panier.php" class="link">Voir le système<span><?=array_sum($_SESSION['panier'])?></span></a>
    <section class="products_list">


        <?php 
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

        //afficher la liste des produits
         $req = $linkpdo->query("SELECT * FROM objectif");
         while($row = $req->fetch()){
        ?>

        
        <form action="" class="product">
            <div class="image_product">
            <img src="img/project_images/<?=$row['lien_image']?>">
            </div>
            
            <div class="content">
                <h4 class="name">   <?=$row['nom']?></h4>
                <h2 class="detail"> <?=$row['intitule']?>detail</h2>
                <a href="ajouter_panier.php?id=<?=$row['id_objectif']?>" class="id_product">Selectionner ce systeme</a>
            </div>
        </form>

        <?php } 
        ?>
   
    </section>

</body>
</html>