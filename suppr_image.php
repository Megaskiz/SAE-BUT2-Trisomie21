<!DOCTYPE html>
<html>
    <head>
        <title>Cours PHP & MySQL</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="cours.css">
    </head>
    
    <body>
        <h1>Titre principal</h1>
        <?php
            $f = 'exemple2.txt';
            
            /*On fait deux opérations en une : on exécute unlink() et on effectue
             *notre test sur la valeur renvoyée. Si la fonctoiin renvoie true,
             *on indique que le fichier a bien été effacé*/ 
            if(unlink($f)){
                echo 'Le fichier ' .$f. ' a bien été effacé';
            }else{
                echo 'Le fichier ' .$f. ' n\'a pas pu être effacé';
            }
        ?>
        
    </body>
</html>