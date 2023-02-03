<?php
function connexionBd(){
        return new PDO("mysql:host=localhost;dbname=bddsae", "root", "");
}
function filter_spaces($var){
    return $var != ' ';
}

// ------------------------------------- fonctions pour les "blocs" html -----------------------------------------------------------

function create_header($linkpdo){ // fonction qui affiche le header
    
    echo'<header>
        <img class="logo-association" src="/sae-but2-s1/img/logo_trisomie.png" alt="logo de l\'association">
        <img class="img-user" src="/sae-but2-s1/img/user_logo.png" alt="tete de l\'utilisateur">';

        $mail =  $_SESSION['login_user'];
        try {
            $res = $linkpdo->query("SELECT nom, prenom FROM membre where courriel='$mail'");
        } catch (Exception $e) { // toujours faire un test de retour au cas ou ça crash
            die('Erreur : ' . $e->getMessage());
        }

        ///Affichage des entrées du résultat une à une

        $double_tab = $res->fetchAll(); // je met le result de ma query dans un double tableau
        $nombre_ligne = $res->rowCount(); // =2 car il y a 2 ligne dans ma base
        $liste = array();
        echo "<table>";

        for ($i = 0; $i < $nombre_ligne; $i++) {
            echo "<tr>";
            for ($y = 0; $y < 2; $y++) {
                echo "<td>";
                print_r($double_tab[$i][$y]);
                $liste[$y] = $double_tab[$i][$y];
                echo "</td>";
            }

            echo "</tr>";
        }

        echo "</table>";
        echo'
        <div onclick="window.location.href =\'logout.php\';" class="h-deconnexion">
        <img class="img-deco" src="img/deconnexion.png" alt="Déconnexion"> Déconnexion
        </div>
    </header>';
}


function create_nav_user($linkpdo){ // fonction qui affiche le nav (partie de gauche) pour les utilisateurs sans privilèges
    echo'
    <div  class="open" onclick="openMenu()"> ☰</div>

            <nav  class="left-contenu">
                <div class="close" onclick="closeMenu()"> &#x1F5D9;</div>
                    <ul class="scrolling-tabs nav-links gl-display-flex gl-flex-grow-1 gl-w-full nav gl-tabs-nav nav gl-tabs-nav">
                        <li class="nav-item">
                            <a class="shortcuts-activity nav-link gl-tab-nav-item active gl-tab-nav-item-active" data-placement="" href="page_admin.php">Affichage Enfant</a>
                        </li>
                        <li class="nav-item"> 
                            <a data-placement="" class="nav-link gl-tab-nav-item" href="mon_compte.php">Mon profil</a>
                        </li>                    
                    </ul>
    ';
                ///Sélection de tout le contenu de la table enfant
                
                try {
                    //acces aux profils enfant suivis
                    $res = $linkpdo->query('SELECT id_enfant, nom, prenom FROM enfant where id_enfant in (select id_enfant from suivre where visibilite = 0  and id_membre=' . $_SESSION["logged_user"] . ')');
                    
                } catch (Exception $e) { // toujours faire un test de retour en cas de crash
                    die('Erreur : ' . $e->getMessage());
                }

                ///Affichage des entrées du résultat une à une
                $double_tab = $res->fetchAll(); // je met le result de ma query dans un double tableau
                $nombre_ligne = $res->rowCount();
                $liste = array();

                echo "
                    <div class='liste-enfant'>
                        <div class=\"recherche\">
                            <form class='recherche' method=\"post\" action=\"search.php\">
                                <div>
                                    <input class=\"input_recherche\" type=\"text\" placeholder=\"Mots-clés ...\" id=\"keywords\" name=\"keywords\" required> 
                                </div>
                                <input class=\"bouton_recherche\" type=\"submit\" value=\" &#x1F50E;\">
                            </form>
                        </div>
                    <table >
                ";
                for ($i = 0; $i < $nombre_ligne; $i++) {

                    for ($y = 1; $y < 3; $y++) {
                        echo "<td>";
                        print_r(ucfirst(htmlspecialchars($double_tab[$i][$y])));
                        $liste[$y] = ucfirst($double_tab[$i][$y]);
                        $nom = ucfirst($double_tab[$i][1]);
                        $prenom = ucfirst($double_tab[$i][2]);
                        $age = $double_tab[0][$y];
                        echo "</td>";
                    }

                    $identifiant = $double_tab[$i][0];
                    echo "
                    <td>
                    <a href=\"page_admin.php?id=' . $identifiant . '\"><button  class=\"acceder-information-enfant\">Acceder</button> </a>
                    </td>
                    </tr>
                    ";
                }
                echo "</table>";
                ///Fermeture du curseur d'analyse des résultats
                $res->closeCursor();
                echo"
                </div>
            </nav>";


};

function create_nav_admin($linkpdo){ // fonction qui affiche le nav (partie de gauche) pour les utilisateurs avec  tous les privilèges
    echo'
    <div  class="open" onclick="openMenu()"> ☰</div>

            <nav  class="left-contenu">
            <div class="close" onclick="closeMenu()"> &#x1F5D9;</div>
                    <ul class="scrolling-tabs nav-links gl-display-flex gl-flex-grow-1 gl-w-full nav gl-tabs-nav nav gl-tabs-nav">
                    <li class="nav-item">
                        <a class="shortcuts-activity nav-link gl-tab-nav-item active gl-tab-nav-item-active" data-placement="" href="page_admin.php">Affichage Enfant</a>
                    </li>';

                    //acces à la page de membre
                    if ($_SESSION["role_user"] !=0) {
                        echo '
                        <li class="nav-item">
                            <a data-placement="" class="nav-link gl-tab-nav-item" href="page_certif_compte.php">Affichage Membre</a>
                        </li>
                    ';
                    }else{
                        echo '
                        <li class="nav-item">
                            <a data-placement="" class="nav-link gl-tab-nav-item" href="mon_compte.php">Mon profil</a>
                        </li>
                        ';
                    }

                    ?>
                </ul>
                <?php
                //acces à l'ajout de profil d'enfant
                if ($_SESSION["role_user"] == 1 or $_SESSION["role_user"] == 3) {

                    //Le bloc suivant est la fenêtre pop-in de l'ajout d'enfant, elle est caché tant qu'on appuie pas sur le bouton "ajouter enfant"
                    echo'
                    <div class="bouton_enfant">
                        <button class="ajouter-enfant" type="button" onclick="openDialog(\'dialog1\', this)">Ajouter un profil  <img class="icone-ajouter-membre" src="img/ajouter-utilisateur.png" > </button>
                        <a href="archive_profil_enfant.php"><button class="button_ajouter-objectif">Profils enfants archivés</button></a>
                        <div id="dialog_layer" class="dialogs">
                            <div role="dialog" id="dialog1" aria-labelledby="dialog1_label" aria-modal="true" class="hidden">
                                <h2 id="dialog1_label" class="dialog_label">Ajouter un profil d\'enfant</h2>
                                <form enctype="multipart/form-data" action="insert_enfant.php" method="post" class="dialog_form">
                                    <div class="dialog_form_item">
                                        <label>
                                            <span class="label_text">Nom :</span>
                                            <input name="nom" type="text" required="required">
                                        </label>
                                    </div>
                                    <div class="dialog_form_item">
                                        <label>
                                            <span class="label_text">Prenom:</span>
                                            <input name="prenom" type="text" class="city_input" required="required">
                                        </label>
                                    </div>
                                    <div class="dialog_form_item">
                                        <label>
                                            <span class="label_text">Date de naissance:</span>
                                            <input name="date_naissance" type="date" class="state_input" required="required">
                                        </label>
                                    </div>
                                    <div class="dialog_form_item">
                                        <label>
                                            <span class="label_text">Jeton:</span>
                                            <input name="lien_jeton" type="file" class="zip_input" required="required">
                                        </label>
                                        <label>
                                            <span class="label_text">Enfant:</span>
                                            <input name="photo_enfant" type="file" class="zip_input" required="required">
                                        </label>
                                    </div>
                                    <div class="dialog_form_actions">
                                        <button  class="popup-btn" type="button" onclick="closeDialog(this)">Annuler</button>
                                        <button class="popup-btn" type="submit">Valider l\'ajout</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    ';

                    /* fin de la fenêtre popin de l'ajout d'enfant" */
                }
                ?>


                <?php
                ///Sélection de tout le contenu de la table enfant
                if ($_SESSION["role_user"] != 2) {


                    try {
                        //acces tous les enfants
                        if ($_SESSION["role_user"] == 1 or $_SESSION["role_user"] == 3) {
                            $res = $linkpdo->query('SELECT id_enfant, nom, prenom FROM enfant where visibilite = 0 ORDER BY nom');
                        } else {
                            $res = $linkpdo->query('SELECT id_enfant, nom, prenom FROM enfant where id_enfant in (select id_enfant from suivre where visibilite = 0  and id_membre=' . $_SESSION["logged_user"] . ')');
                        }
                    } catch (Exception $e) { // toujours faire un test de retour en cas de crash
                        die('Erreur : ' . $e->getMessage());
                    }

                    ///Affichage des entrées du résultat une à une
                    $double_tab = $res->fetchAll(); // je met le result de ma query dans un double tableau
                    $nombre_ligne = $res->rowCount();
                    $liste = array();

                    echo "
                    <div class='liste-enfant'>
                    <div class=\"recherche\">
                    <form class='recherche' method=\"post\" action=\"search.php\">
                    <div>
                    <input class=\"input_recherche\" type=\"text\" placeholder=\"Mots-clés ...\" id=\"keywords\" name=\"keywords\" required> 
                    </div>
                    <input class=\"bouton_recherche\" type=\"submit\" value=\" &#x1F50E;\">
                    </form>
                    </div>";
                    echo "<table >";

                    for ($i = 0; $i < $nombre_ligne; $i++) {

                        for ($y = 1; $y < 3; $y++) {
                            echo "<td>";
                            print_r(ucfirst(htmlspecialchars($double_tab[$i][$y])));
                            $liste[$y] = ucfirst($double_tab[$i][$y]);
                            $nom = ucfirst($double_tab[$i][1]);
                            $prenom = ucfirst($double_tab[$i][2]);
                            $age = $double_tab[0][$y];
                            echo "</td>";
                        }

                        $identifiant = $double_tab[$i][0];
                        echo "<td>";
                        echo '<a href="page_admin.php?id=' . $identifiant . '"><button  class="acceder-information-enfant">Acceder</button> </a>';
                        echo "</td>";
                        echo "</tr>";
                    }

                    echo "</table>";

                    ///Fermeture du curseur d'analyse des résultats
                    $res->closeCursor();
                }
                echo"
                </div>
            </nav>";
};

function create_nav_coordinateur($linkpdo){ // fonction qui affiche le nav (partie de gauche) pour les coordinateurs
        echo'
    <div  class="open" onclick="openMenu()"> ☰</div>

            <nav  class="left-contenu">
            <div class="close" onclick="closeMenu()"> &#x1F5D9;</div>
                    <ul class="scrolling-tabs nav-links gl-display-flex gl-flex-grow-1 gl-w-full nav gl-tabs-nav nav gl-tabs-nav">
                    <li class="nav-item">
                        <a class="shortcuts-activity nav-link gl-tab-nav-item active gl-tab-nav-item-active" data-placement="" href="page_admin.php">Affichage Enfant</a>
                    </li>';
                    //acces à la page de membre
                    echo '
                    <li class="nav-item">
                        <a data-placement="" class="nav-link gl-tab-nav-item" href="page_certif_compte.php">Affichage Membre</a>
                    </li>
                    </ul>
                    ';
                    ?>
                
                <?php
                //acces à l'ajout de profil d'enfant
                    //Le bloc suivant est la fenêtre pop-in de l'ajout d'enfant, elle est caché tant qu'on appuie pas sur le bouton "ajouter enfant"
                    echo'
                    <div class="bouton_enfant">
                        <button class="ajouter-enfant" type="button" onclick="openDialog(\'dialog1\', this)">Ajouter un profil  <img class="icone-ajouter-membre" src="img/ajouter-utilisateur.png" > </button>
                        <a href="archive_profil_enfant.php"><button class="button_ajouter-objectif">Profils enfants archivés</button></a>
                        <div id="dialog_layer" class="dialogs">
                            <div role="dialog" id="dialog1" aria-labelledby="dialog1_label" aria-modal="true" class="hidden">
                                <h2 id="dialog1_label" class="dialog_label">Ajouter un profil d\'enfant</h2>
                                <form enctype="multipart/form-data" action="insert_enfant.php" method="post" class="dialog_form">
                                    <div class="dialog_form_item">
                                        <label>
                                            <span class="label_text">Nom :</span>
                                            <input name="nom" type="text" required="required">
                                        </label>
                                    </div>
                                    <div class="dialog_form_item">
                                        <label>
                                            <span class="label_text">Prenom:</span>
                                            <input name="prenom" type="text" class="city_input" required="required">
                                        </label>
                                    </div>
                                    <div class="dialog_form_item">
                                        <label>
                                            <span class="label_text">Date de naissance:</span>
                                            <input name="date_naissance" type="date" class="state_input" required="required">
                                        </label>
                                    </div>
                                    <div class="dialog_form_item">
                                        <label>
                                            <span class="label_text">Jeton:</span>
                                            <input name="lien_jeton" type="file" class="zip_input" required="required">
                                        </label>
                                        <label>
                                            <span class="label_text">Enfant:</span>
                                            <input name="photo_enfant" type="file" class="zip_input" required="required">
                                        </label>
                                    </div>
                                    <div class="dialog_form_actions">
                                        <button  class="popup-btn" type="button" onclick="closeDialog(this)">Annuler</button>
                                        <button class="popup-btn" type="submit">Valider l\'ajout</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    ';
                    /* fin de la fenêtre popin de l'ajout d'enfant" */
                    ///Sélection de tout le contenu de la table enfant


                    try {
                        //acces tous les enfants
                        $res = $linkpdo->query('SELECT id_enfant, nom, prenom FROM enfant where visibilite = 0 ORDER BY nom');
                    } catch (Exception $e) { // toujours faire un test de retour en cas de crash
                        die('Erreur : ' . $e->getMessage());
                    }

                    ///Affichage des entrées du résultat une à une
                    $double_tab = $res->fetchAll(); // je met le result de ma query dans un double tableau
                    $nombre_ligne = $res->rowCount();
                    $liste = array();

                    echo "<div class='liste-enfant'>";
                    echo "<div class=\"recherche\">
                    <form class='recherche' method=\"post\" action=\"search.php\">
                    <div>
                    <input class=\"input_recherche\" type=\"text\" placeholder=\"Mots-clés ...\" id=\"keywords\" name=\"keywords\" required> 
                    </div>
                    <input class=\"bouton_recherche\" type=\"submit\" value=\" &#x1F50E;\">
                    </form>
                    </div>";
                    echo "<table >";

                    for ($i = 0; $i < $nombre_ligne; $i++) {

                        for ($y = 1; $y < 3; $y++) {
                            echo "<td>";
                            print_r(ucfirst(htmlspecialchars($double_tab[$i][$y])));
                            $liste[$y] = ucfirst($double_tab[$i][$y]);
                            $nom = ucfirst($double_tab[$i][1]);
                            $prenom = ucfirst($double_tab[$i][2]);
                            $age = $double_tab[0][$y];
                            echo "</td>";
                        }

                        $identifiant = $double_tab[$i][0];
                        echo "<td>";
                        echo '<a href="page_admin.php?id=' . $identifiant . '"><button  class="acceder-information-enfant">Acceder</button> </a>';
                        echo "</td>";
                        echo "</tr>";
                    }

                    echo "</table>";

                    ///Fermeture du curseur d'analyse des résultats
                    $res->closeCursor();
                
                echo"
                </div>
            </nav>";
};






function modif_enfant($nom, $prenom, $date_naissance, $adresse, $activite, $handicap, $info_sup, $session, $linkpdo){           
    
    if($nom != null or $prenom != null or $date_naissance != null or $adresse != null or $activite != null or $handicap != null or $info_sup ){
    $liste = array();
    $data = array();


    if ($nom != null) {
        $liste += [ "nom" =>$nom ];
    }

    if ($prenom != null) {
    $liste += [ "prenom" =>$prenom ];
    }

    if ($date_naissance != null) {
    $liste += [ "date_naissance" =>$date_naissance ];
    }

    if ($adresse != null) {
    $liste += [ "adresse" =>$adresse ];
    }

    if ($activite != null) {
    array_push($liste, $activite);
    $liste += [ "activite" =>$activite ];
    }

    if ($handicap != null) {
    $liste += [ "handicap" =>$handicap ];
    }

    if ($info_sup != null) {
    $liste += [ "info_sup" =>$info_sup ];
    }


    // faire un for dans la liste et créer la requête
    // faire le éxecute aussi avec une liste

    $req="UPDATE enfant SET ";



    

    foreach($liste as $key => $value){
            if(!is_numeric($key)){
            $req.=$key;
            $req.='=? ';
           array_push($data, $value);
        }
    }
    
    $req.="where id_enfant=?";
    array_push($data,$_SESSION['id_enfant']);
    echo"<br>";

    // echo$req;
    // echo"<br>";
    // var_dump($data);
    // echo"<br>";



    $query = $linkpdo->prepare($req);

    if ($query == false){
        die("erreur linkpdo");
    }   
        ///Exécution de la requête
    try{
        $query->execute($data);
        
        $query->debugDumpParams();
        if ($query == false){
            die("erreur execute");
        }
    }
    
    catch (Exception $e)
    {die('Erreur : ' . $e->getMessage());}

    }
    
    header('Location: page_admin.php?id='.$_SESSION['id_enfant'].'');
    exit();

}

function modif_compte($nom, $prenom, $adresse, $Cpostal, $ville, $date_naissance,$role,$session, $linkpdo){

    if ($role==NULL){
        $role = '1';
    }
    $req = $linkpdo->prepare("UPDATE membre  SET nom=? ,prenom= ?,adresse= ?,code_postal= ?,ville= ?, date_naissance= ?, role_user=? WHERE id_membre= ?");

    if ($req == false){
        die("erreur linkpdo");
    }   
        ///Exécution de la requête
    try{
        $req->execute([$nom, $prenom, $adresse, $Cpostal, $ville, $date_naissance,$role, $session]);

        if ($req == false){
            die("erreur execute");
        }
    }
    
    catch (Exception $e)
    {die('Erreur : ' . $e->getMessage());}
    
    header('Location: page_certif_compte.php?idv='.$_SESSION['id_compte_modif'].'');
    exit();
}

function modif_mdp($mdp, $session, $linkpdo){
    
    // fonction qui hash le mot de passe
    $mot = "ZEN02anWobA4ve5zxzZz".$mdp; // je rajoute une chaine que je vais ajouter au mot de passe
    $nouveau_mdp = hash('sha256', $mot);
    

    $req = $linkpdo->prepare("UPDATE membre  SET mdp=? WHERE id_membre= ?");

    if ($req == false){
        die("erreur linkpdo");
    }   
        ///Exécution de la requête
    try{
        $req->execute([$nouveau_mdp, $session]);
        //$req->debugDumpParams();
        //exit();

        if ($req == false){
            die("erreur execute");
        }
    }
    
    catch (Exception $e)
    {die('Erreur : ' . $e->getMessage());}
    
    
    }





    function uploadVisage($photo){

    if (isset($photo)) {
        $tmpName = $photo['tmp_name'];
        $name = $photo['name'];
        $size = $photo['size'];
        $error = $photo['error'];

        $tabExtension = explode('.', $name);
        $extension = strtolower(end($tabExtension));

        $extensions = ['jpg', 'png', 'jpeg', 'gif', 'svg', 'webp', 'bmp'];
        $maxSize = 400000000000;

        if (in_array($extension, $extensions) && $size <= $maxSize && $error == 0) {

            $uniqueName = uniqid('', true);
            //uniqid génère quelque chose comme ca : 5f586bf96dcd38.73540086
            $file = $uniqueName . "." . $extension;
            $chemin = "images/";
            //$file = 5f586bf96dcd38.73540086.jpg
            move_uploaded_file($tmpName, 'visage/' . $file);
            $result = $chemin . $file;
        }
    } else {
        echo '<h1>erreur</h1>';
    }
    return $result;
}


function uploadImage($photo){

    if (isset($photo)) {
        $tmpName = $photo['tmp_name'];
        $name = $photo['name'];
        $size = $photo['size'];
        $error = $photo['error'];

        $tabExtension = explode('.', $name);
        $extension = strtolower(end($tabExtension));

        $extensions = ['jpg', 'png', 'jpeg', 'gif', 'svg', 'webp', 'bmp'];
        $maxSize = 400000000000;

        if (in_array($extension, $extensions) && $size <= $maxSize && $error == 0) {

            $uniqueName = uniqid('', true);
            //uniqid génère quelque chose comme ca : 5f586bf96dcd38.73540086
            $file = $uniqueName . "." . $extension;
            $chemin = "images/";
            //$file = 5f586bf96dcd38.73540086.jpg
            move_uploaded_file($tmpName, 'images/' . $file);
            $result = $chemin . $file;
        }
    } else {
        echo '<h1>erreur</h1>';
    }
    return $result;
}




function is_logged(){
    session_start();
 
    if( !isset($_SESSION['logged_user']) ){
       echo"vous n'etes pas connecté : ";
       echo'<a href="html_login.php">aller vers la page de connexion</a>';
       header("location: html_login.php");
       exit();
    }
}

function is_user(){ 
    if($_SESSION['role_user']==0 ){
       echo"vous ne devriez pas etre la : ";
       echo'<a href="page_admin.php">aller vers la page de connexion</a>';
       header("location: page_admin.php");
       exit();
    }
}

function is_validateur(){ 
    if($_SESSION['role_user']==2 ){
       echo"vous ne devriez pas etre la : ";
       echo'<a href="page_certif_compte.php">aller vers la page de connexion</a>';
       header("location: page_certif_compte.php");
       exit();
    }
}

function is_coordinateur(){ 
    if($_SESSION['role_user']==3 ){
       echo"vous ne devriez pas etre la : ";
       echo'<a href="page_admin.php">aller vers la page de connexion</a>';
       header("location: page_admin.php");
       exit();
    }
}


function is_not_admin(){ 
    if($_SESSION['role_user']!=1 ){
       echo"vous ne devriez pas etre la : ";
       echo'<a href="page_admin.php">aller vers la page de connexion</a>';
       header("location: page_admin.php");
       exit();
    }
}

?>