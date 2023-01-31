<?php
require('fonctions.php');
is_logged();
?>

<?php
if($_GET['appel']=='modif_enfant'){
    $nom = $_POST['nom_enfant'];
    $prenom = $_POST['prenom_enfant'];
    $activite = $_POST['activite'];
    $adresse = $_POST['adresse'];
    $handicap = $_POST['handicap'];
    $info_sup = $_POST['info_sup'];
    $date_naissance = $_POST['date_naissance'];
    $session = $_SESSION['id_enfant'];

    modif_enfant($nom, $prenom, $date_naissance, $adresse, $activite, $handicap, $info_sup, $session);

}

if($_GET['appel']=='modif_compte'){

    $nom = htmlspecialchars($_POST['nom_membre']);
    $prenom = htmlspecialchars($_POST['prenom_membre']);
    $date_naissance = htmlspecialchars($_POST['ddn_membre']); 
    $ville = htmlspecialchars($_POST['ville']);
    $adresse = htmlspecialchars($_POST['ad_membre']);
    $Cpostal = htmlspecialchars($_POST['cpostal_membre']);
    $role=htmlspecialchars($_POST['role']);
    $session = $_SESSION['id_compte_modif'];


    modif_compte($nom, $prenom, $adresse, $Cpostal, $ville, $date_naissance,$role, $session);

}



?>