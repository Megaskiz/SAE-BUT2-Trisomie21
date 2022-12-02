<style>
table,tr{
    border-collapse:collapse;
    border:solid black 1px;
    padding: 1rem;
}

td{
    border-collapse:collapse;
    border:solid black 1px;
    width:auto;
    height:auto;
}
</style>

<?php

///Connexion au serveur MySQL
try {
    $linkpdo = new PDO("mysql:host=localhost;dbname=bddsae","root","");
    }
    ///Capture des erreurs éventuelles
catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
    }





                    
                    
    

if (isset ($_GET['id']))  {
    $id = $_GET['id'];  

    echo"<br>";echo"<br>";echo"<br>";
                    
    ///Sélection de tout le contenu de la table enfant
    try {
        $res = $linkpdo->query("SELECT * FROM objectif where id_objectif=$id");
        }
        catch (Exception $e) { // toujours faire un test de retour en cas de crash
            die('Erreur : ' . $e->getMessage());
        }

        ///Affichage des entrées du résultat une à une
        
        $double_tab = $res -> fetchAll(); // je met le result de ma query dans un double tableau
        $nombre_ligne = $res -> rowCount();
        $liste = array();
        

        ///Fermeture du curseur d'analyse des résultats
        $res->closeCursor();
        


?>





    <table>

        <tr>
            <td>
                <p></p>
            </td>
            <td>
                <p>lundi</p>
            </td>
            <td>
                <p>mardi</p>
            </td>
            <td>
                <p>mercredi</p>
            </td>
            <td>
                <p>jeudi</p>
            </td>
            <td>
                <p>vendredi</p>
            </td>
            <td>
                <p>samedi</p>
            </td>
            <td>
                <p>dimanche</p>
            </td>
        </tr>




<?php

    //connexion a la bd et ajout dans la table objectif

    try {
        $res = $linkpdo->query("SELECT nom FROM objectif where id_objectif=$id");
        }
    catch (Exception $e) { // toujours faire un test de retour au cas ou ça crash
            die('Erreur : ' . $e->getMessage());
        }

        ///Affichage des entrées du résultat une à une
        
        $double_tab = $res -> fetchAll();
        //var_dump($double_tab);
        $valeur = $double_tab[0];
        //var_dump( $valeur);
    //echo$_POST['chaine'];

    $chaine=$valeur[0];

    /* il faut maintenant 'déconstruire' la chaine pour pouvoir faire un tableau

    début :


    manger_1_0000000: dormir_2_0000000:



    */

    $morceau = explode(":", $chaine);

    array_pop($morceau); // je retire la partie apres le dernier ":" 
    $compteur=0;
    foreach ($morceau as $ligne){
        
        
        $element = explode("_", $ligne);
        $tache = $element[0];
        $jetons = $element[1];
        $tab_jeton = str_split($jetons);
        echo"<tr>";
        echo"<td>";
        echo$tache;
        echo"</td>";
    
        //ajout des cases de jetons
        foreach($tab_jeton as $case_tab){
            if($case_tab==0){
                echo"<td id=$compteur >";
                echo'<a href="choix_sys_ajout.php?id='.$id.'&amp;case='.$compteur.'&amp;chaine='.$chaine.'" style="display: block;width: 5rem;height: 5rem;"></a>';
                echo"</td>";

            }else{
                echo"<td id=$compteur bgcolor=\"green\" style=\"width: 5rem;height: 5rem;\">";
                echo"</td>";
            }

            
            $compteur+=1;
        }

        echo"</tr>";
    }
}

echo"</table>";
?>