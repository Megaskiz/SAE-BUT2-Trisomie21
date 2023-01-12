<?php
require('fonctions.php');
is_logged();
is_validateur();
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
                //echo"la modif est faite";echo"<br>";
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


    // FAIRE EN SORTE DE RECUP LA DERNIERE SESSION QUI A ETE UTILISE POUR SE SYSTEME
    // AJOUTER 1 A CE CHIFFRE SI LA DUREE DU SYSTEME DEPASSE LA DATE DU PREMIER JETON PLACE + LA DUREE DU SYS

    try {
        $session_max_query =$linkpdo->query("SELECT max(id_session) from placer_jeton where id_objectif=".$id);
        //$session_max_query->debugDumpParams();
        
        }
        catch (Exception $e) { // toujours faire un test de retour en cas de crash
            die('Erreur : ' . $e->getMessage());
        }

        ///Affichage des entrées du résultat une à une
        
        $double_tab = $session_max_query -> fetchAll(); // je met le result de ma query dans un double tableau
        $session_max = $double_tab[0][0];

        if($session_max==NULL){
            $session_max=0;     
        }

    try {
        $jeton_max_query =$linkpdo->query("SELECT max(date_heure) from placer_jeton where id_session=".$session_max." and id_objectif=".$id);
   
        }
        catch (Exception $e) { // toujours faire un test de retour en cas de crash
            die('Erreur : ' . $e->getMessage());
        }

        ///Affichage des entrées du résultat une à une
        
        $double_tab = $jeton_max_query -> fetchAll();

        $jeton_max =  $double_tab[0][0];

        if($jeton_max==NULL){
            $jeton_max=0;
        }

        echo$session_max;
        echo$jeton_max;

        if($session_max ==0){
            $session_actuelle=1;
        }else{
            echo$jeton_max;
            $secondes_dernier_jeton=strtotime($jeton_max);
            echo$secondes_dernier_jeton;
            // exit();
            // $jeton_max
            $session_actuelle=$session_max; // je garde la même session 
            
        }

        
    
    // connexion bd 

    // modif dans le nom

    $req = $linkpdo->prepare('UPDATE objectif SET nom = :intit where id_objectif = :id ');
    $req2 = $linkpdo->prepare('insert into placer_jeton values ( :id_objectif, :time, :id_membre, :session)  ');

    if ($req == false){
        die("erreur linkpdo");
    }   
        ///Exécution de la requête
    try{
        
        $req->execute(array('intit' => $tableau_final, 'id' => $id,));
        $req2->execute(array('id_objectif' => $id, 'time' => date("Y/m/d H:i:s"), 'id_membre' => $_SESSION['logged_user'], 'session' => $session_actuelle));
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
