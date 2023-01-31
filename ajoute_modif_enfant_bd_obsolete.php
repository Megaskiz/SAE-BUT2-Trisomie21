<?php
echo"on ne devrait pas être la";
exit();
require('fonctions.php');
is_logged();
is_validateur();
is_not_admin();

 
$nom = $_POST['nom_enfant'];
$prenom = $_POST['prenom_enfant'];
$activite = $_POST['activite'];
$adresse = $_POST['adresse'];
$handicap = $_POST['handicap'];
$info_sup = $_POST['info_sup'];
$date_naissance = $_POST['date_naissance'];
$session = $_SESSION['id_enfant'];

modif_enfant($nom, $prenom, $date_naissance, $adresse, $activite, $handicap, $info_sup, $session);





               
?>