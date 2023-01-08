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
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Panier</title>
    <link rel="stylesheet" href="style_banque.css">
</head>
<body class="panier">
    <a href="banque_systeme.php" class="link">Banque</a>
    <section>
        <table>
            <tr>
                <th></th>
                <th>Type d'objectif</th>
                <th>Nom</th>
                <th>Détail</th>
                <th>supprimer</th>
            </tr>




            <?php 
              $total = 0 ;
              //récupérer les clés du tableau session
              $ids = array_keys($_SESSION['panier']);
              //s'il n'y a aucune clé dans le tableau
              if(empty($ids)){
                echo "<p>Veillez selectionner un système !</p>";
              }else {
                //si oui 
                $products =  $linkpdo->query( "SELECT * FROM products WHERE id IN (".implode(',', $ids).")");
               


                //afficher les systemes
                    

                foreach($products as $product):
      
                
                ?>

                <tr class="total">
                    <th>Vous avez choisi: </th> 
                    <td><img class="systeme-icon" src="project_images/<?=$product['img']?>"></td>
                    <td><?=$product['name']?></td>
                    <td><?=$product['detail']?>ce type de systeme est..</td>
                    <td><a href="panier.php?del=<?=$product['id']?>"> <img class="delet-icon" src="img/delete.png"></a> </td>
                </tr>
                <?php endforeach ;} ?>
        </table>
    </section>

    <form action="insert_systeme_bd.php" method="post">
    <input class="valider-system" type="submit" value="Valider mon choix">
        </form>
       
</body>
</html>