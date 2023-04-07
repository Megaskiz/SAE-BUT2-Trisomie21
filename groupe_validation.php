<?php
/**
 * @file groupe_validation.php
 * @brief Page de validation d'une demande d'adhésion à une équipe
 * @details Page de validation d'une demande d'adhésion à une équipe, permet à un validateur de valider une demande d'adhésion à une équipe
 */
require_once('fonctions.php');
is_logged();
is_validateur();

$linkpdo = connexionBd();


$id_enfant = $_GET['id_enfant'];
$id_membre = $_GET['id_membre'];
$date_demande_equipe = date('Y-m-d H:i:s');
$role = 'membre';
$sql = "INSERT INTO suivre (id_enfant, id_membre, date_demande_equipe, role) VALUES (:id_enfant, :id_membre, :date_demande_equipe, :role)";
$stmt = $linkpdo->prepare($sql);
if(!$stmt){
  die("Error prepare");
}
$stmt->bindParam(':id_enfant', $id_enfant);
$stmt->bindParam(':id_membre', $id_membre);
$stmt->bindParam(':date_demande_equipe', $date_demande_equipe);
$stmt->bindParam(':role', $role);
$stmt->execute();
if(!$stmt){
  die("Error Execute");
}


header('Location: index.php?id='.$_SESSION['id_enfant'].'');
?>
