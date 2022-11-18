<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8">
        <!-- importer le fichier de style -->
        <link rel="stylesheet" href="carnet.css" media="screen" type="text/css" />
        <title>Carnet d'adresse</title>
    </head>
    <body>
        <h1>Carnet d'adresse </h1>

        
        <div>
        <?php

        ///Connexion au serveur MySQL
        try {
        $linkpdo = new PDO("mysql:host=localhost;dbname=sae", "root", "");
        }
        ///Capture des erreurs éventuelles
        catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
        }



        /// affichage de tous le contenu de la table :
        try {
        $res = $linkpdo->query('SELECT * FROM membre');
        }
        catch (Exception $e) { // toujours faire un test de retour au cas ou ça crash
            die('Erreur : ' . $e->getMessage());
        }

        ///Affichage des entrées du résultat une à une
        
        $double_tab = $res -> fetchAll(); // je met le result de ma query dans un double tableau
        $nombre_ligne = $res -> rowCount(); // =2 car il y a 2 ligne dans ma base
        
        
        for ($i=0; $i < $nombre_ligne; $i++) { 
            for ($y=0; $y < 6; $y++) { 
            print_r($double_tab[$i][$y]);
            echo", ";
        }echo"</br>";echo"</br>";
        }
        
        

        ///Fermeture du curseur d'analyse des résultats
        $res->closeCursor();


        ?>

        </div>
    </body>
</html>