<?php
require_once('fonctions.php');
is_logged();
is_validateur();
?>
<?php


///Connexion au serveur MySQL
$linkpdo = connexionBd();

// fichier qui retire un jeton dans le système
if (isset($_GET['case'])) {
    $case_tableau = $_GET['case'];
    $chaine = $_GET['chaine'];
    $id = $_GET['id'];

    $tab_string = str_split($chaine);
    $compteur_de_cases = 0;

    for ($i = 0; $i < count($tab_string); ++$i) {
        if ($tab_string[$i] == '1') {
            if ($compteur_de_cases == $case_tableau) {
                $tab_string[$i] = 0;
                $compteur_de_cases += 1;
                echo "la modif est faite";
                echo "<br>";
            } else {
                $compteur_de_cases += 1;
            }
        } else if ($tab_string[$i] == '0') {
            $compteur_de_cases += 1;
        }
    }



    $tableau_final = join("", $tab_string);
    // echo"letabstring: ";echo"<br>";
    // print_r($tab_string);
    // echo"<br>";
    // echo"onvaenvoyerça: $tableau_final";
    // exit();


    // FAIRE EN SORTE DE RECUP LA DERNIERE SESSION QUI A ETE UTILISE POUR SE SYSTEME
    // AJOUTER 1 A CE CHIFFRE SI LA DUREE DU SYSTEME DEPASSE LA DATE DU PREMIER JETON PLACE + LA DUREE DU SYS

    try {
        $session_max_query = $linkpdo->query("SELECT max(id_session) from placer_jeton where id_objectif=" . $id);
        // $session_max_query->debugDumpParams();
        // exit();
    } catch (Exception $e) { // toujours faire un test de retour en cas de crash
        die('Erreur : ' . $e->getMessage());
    }

    ///Affichage des entrées du résultat une à une

    $double_tab = $session_max_query->fetchAll(); // je met le result de ma query dans un double tableau
    $session_max = $double_tab[0][0];

    if ($session_max == NULL) {
        $session_max = 0;
    }

    try {

        // $data=[
        //     'session_max '=>$session_max,
        //     'id'=>$id,
        //     ];
        // $sql="DELETE FROM placer_jeton WHERE id_objectif= :id and  id_session= :session_max ORDER BY date_heure DESC LIMIT 1";
        // $jeton_max_query=$linkpdo->prepare($sql);
        // $jeton_max_query->bindParam(':session_max', $session_max);
        // $jeton_max_query->bindParam(':id', $id);
        // $jeton_max_query->execute();
        // $jeton_max_query ->debugDumpParams();
        // exit();


        $jeton_max_query = $linkpdo->prepare("DELETE FROM `placer_jeton` WHERE id_session=" . $session_max . " and id_objectif=" . $id . " ORDER BY date_heure DESC LIMIT 1");
        $jeton_max_query->execute();
        //$jeton_max_query -> debugDumpParams();
        //exit();

    } catch (Exception $e) { // toujours faire un test de retour en cas de crash
        die('Erreur : ' . $e->getMessage());
    }







    // modif dans le nom

    $req = $linkpdo->prepare('UPDATE objectif SET nom = :intit where id_objectif = :id ');

    if ($req == false) {
        die("erreur linkpdo");
    }
    ///Exécution de la requête
    try {

        $req->execute(array('intit' => $tableau_final, 'id' => $id,));
        header("Location:objectif.php?id_sys=$id");



        if ($req == false) {
            die("erreur execute");
        } else {
            echo "<a href=\"objectif.php?id_sys=$id\"> recharger la page</a>";
        }
    } catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }
}
?>
