<?php // la partie de la connexion
///Connexion au serveur MySQL
try {
    $linkpdo = new PDO("mysql:host=localhost;dbname=bddsae", "root", "");
}
///Capture des erreurs éventuelles
catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

require('fonctions.php');

$nom = $_POST['nom'];
$prenom = $_POST['prenom'];
$adresse = $_POST['adresse'];
$code = $_POST['code_postal'];
$ville = $_POST['ville'];
$courriel = $_POST['courriel'];
$ddn = $_POST['ddn'];
$Mdp = $_POST['password'];
$Mdp_verif = $_POST['password_verif'];
$pro = $_POST['pro'];


// requete avec le mail si, rowcount() > 0 faire fail
$requete_verif_mail = "SELECT count(*) FROM membre WHERE courriel='$courriel'";
// Execution de la requete
try {
    $res = $linkpdo->query($requete_verif_mail);
} catch (Exception $e) { // toujours faire un test de retour au cas ou ça crash
    die('Erreur : ' . $e->getMessage());
}

$count = $res->fetchColumn();

if ($count > 0) {
    $message_erreur = "il y déjà un compte avec cette adresse mail ";
} else {


    // je creé la requete d'insertion 

    $req = $linkpdo->prepare('INSERT INTO membre(nom, prenom, adresse, code_postal, ville, courriel, date_naissance, mdp, pro, compte_valide)
    VALUES(:nom, :prenom, :adresse, :code_postal, :ville, :courriel, :date_naissance, :mdp, :pro, :compte_valide)');

    if ($req == false) {
        die("erreur linkpdo");
    }
    ///Exécution de la requête
    try {
        $req->execute(array(
            'nom' => $nom,
            'prenom' => $prenom,
            'adresse' => $adresse,
            'code_postal' => $code,
            'ville' => $ville,
            'courriel' => $courriel,
            'date_naissance' => $ddn,
            'mdp' => $Mdp,
            'pro' => $pro, // à changer
            'compte_valide' => 1
        ));
        if ($req == false) {
            $req->debugDumpParams;
            die("erreur execute");
        }
    } catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }

    header('Location: page_certif_compte.php');
    exit();
}
