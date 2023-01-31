<?php
function connexionBd(){
        return new PDO("mysql:host=localhost;dbname=bddsae", "root", "");
}
function filter_spaces($var){
    return $var != ' ';
}


function modif_enfant($nom, $prenom, $date_naissance, $adresse, $activite, $handicap, $info_sup, $session, $linkpdo){           
    

    $req = $linkpdo->prepare("UPDATE enfant  SET nom=? ,prenom= ?,date_naissance= ?,adresse= ?,activite= ?,handicap= ?, info_sup= ? WHERE id_enfant= ?");

    if ($req == false){
        die("erreur linkpdo");
    }   
        ///Exécution de la requête
    try{
        $req->execute([$nom, $prenom, $date_naissance, $adresse, $activite, $handicap, $info_sup, $_SESSION['id_enfant']]);

        if ($req == false){
            $req->debugDumpParams();
            die("erreur execute");
        }
    }
    
    catch (Exception $e)
    {die('Erreur : ' . $e->getMessage());}
    
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