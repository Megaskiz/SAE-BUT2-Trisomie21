<?php
require('fonctions.php');
is_logged();
?>
<?php

// fichier qui ajoute chaque jeton dans le système


if (isset ($_GET['case']))  {
    $case_tableau = $_GET['case'];
    $chaine = $_GET['chaine'];
    $id = $_GET['id'];

    $tab_string = str_split($chaine);
    $compteur_de_0 = 0;

    for($i = 0; $i < count($tab_string);++$i){
        if($tab_string[$i]=='0'){
            if ($compteur_de_0==$case_tableau){
                $tab_string[$i]=1;
                $compteur_de_0+=1;
                echo"la modif est faite";echo"<br>";
            }
            else{
                $compteur_de_0+=1;
            }
        }else if($tab_string[$i]=='1'){
            $compteur_de_0+=1;
        }
    }

        ///Connexion au serveur MySQL
    try {
        $linkpdo = new PDO("mysql:host=localhost;dbname=bddsae","root","");
        }
    ///Capture des erreurs éventuelles
    catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
        }

    $tableau_final = join("",$tab_string);
    /*echo"letabstring: ";echo"<br>";
    print_r($tab_string);
    echo"<br>";
    echo"onvaenvoyerça: $tableau_final";
    exit();*/
    
    // connexion bd 

    // modif dans le nom

    $req = $linkpdo->prepare('UPDATE objectif SET nom = :intit where id_objectif = :id ');

    if ($req == false){
        die("erreur linkpdo");
    }   
        ///Exécution de la requête
    try{
        $req->execute(array('intit' => $tableau_final, 'id' => $id,));
        header("Location:choix_sys.php?id_sys=$id");
        if ($req == false){
            $req->debugDumpParams;
            die("erreur execute");
        }else{
            echo"<a href=\"choix_sys.php?id_sys=$id\"> recharger la page</a>";         
           
        }
    }
    catch (Exception $e)
    {die('Erreur : ' . $e->getMessage());}
}
?>
