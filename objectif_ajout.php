



<?php
require('fonctions.php');
is_logged();
is_validateur();
?>
<?php
// fichier qui ajoute chaque jeton dans le système

///Connexion au serveur MySQL
$linkpdo = connexionBd();

if (isset($_GET['case'])) {

    $case_tableau = $_GET['case']; // la case que je souhaite modifier
    $chaine = $_GET['chaine']; // la chaine qui caractérise le les cases de l'objectif
    $id = $_GET['id']; // l'id de l'objectif en cours


    // pour savoir si la session actuelle est échue, il faut récupere la date du premier jeton de la derniere session puis si cette date + la 
    // durée de l'objectif, alors il ne faut pas ajouter de nouveau jeton, mais plutot demander à l'utilisateur s'il veut faire une nouvelle session

    try {
        //je recupere la date du premier jeton placé pour cette session dans ce sys
        $jeton_premier_query = $linkpdo->query("SELECT min(date_heure) from placer_jeton where id_session = (SELECT max(id_session) from placer_jeton where id_objectif=$id) and id_objectif=$id");
        // je recupere la duree totale du sys prevu
        $duree_sys_query = $linkpdo->query("SELECT duree from OBJECTIF where id_objectif=" . $id);

    } catch (Exception $e) { // toujours faire un test de retour en cas de crash
        die('Erreur : ' . $e->getMessage());
    }

    $double_tab = $jeton_premier_query->fetchAll();
    $double_tab2 = $duree_sys_query->fetchAll();

    $jeton_premier = $double_tab[0][0];
    $duree_sys = $double_tab2[0][0];

    $duree_sys_en_seconde = $duree_sys * 3600;

    $secondes_premier_jeton = strtotime($jeton_premier);

    if ($secondes_premier_jeton + $duree_sys_en_seconde > time()) { // si la sesssion n'est pas encore échue
        
        $tab_string = str_split($chaine);
        $compteur_de_case = 0;

        for ($i = 0; $i < count($tab_string); ++$i) {
            if ($tab_string[$i] == '0') {
                if ($compteur_de_case == $case_tableau) {
                    // j'ai trouvé la case que je voulais
                    $tab_string[$i] = 1; // je change son nom de 0 à 1
                    $i = count($tab_string); // je fais en sorte de ne pas faire plus de tour de boucle
                } else {
                    $compteur_de_case += 1;
                }
            } else if ($tab_string[$i] == '1') {
                $compteur_de_case += 1;
            }
        }

        $tableau_final = join("", $tab_string); // je re-crée la chaine qui caractérise toutes les cases

        try {
            $session_max_query = $linkpdo->query("SELECT max(id_session) from placer_jeton where id_objectif=$id");
        } catch (Exception $e) { // toujours faire un test de retour en cas de crash
            die('Erreur : ' . $e->getMessage());
        }
        $double_tab = $session_max_query->fetchAll();
        $session_max = $double_tab[0][0]; // je recupere la derniere session

        // modif dans le nom
        $req = $linkpdo->prepare('UPDATE objectif SET nom = :intit where id_objectif = :id ');
        // ajout du jeton dans la table placer_jeton
        $req2 = $linkpdo->prepare('insert into placer_jeton values ( :id_objectif, :time, :id_membre, :session)  ');

        if ($req == false or $req2 == false) {
            die("erreur linkpdo");
        }
        //? Exécution de la requête
        try {
            $req->execute(array('intit' => $tableau_final, 'id' => $id, ));
            $req2->execute(array('id_objectif' => $id, 'time' => date("Y/m/d H:i:s"), 'id_membre' => $_SESSION['logged_user'], 'session' => $session_max));
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

}
header("Location:objectif.php?id_sys=$id");
?>