<?php
require('fonctions.php');
is_logged();
is_validateur();

try {
  $linkpdo = new PDO("mysql:host=localhost;dbname=bddsae", "root", "");
} catch (Exception $e) {
  die('Erreur : ' . $e->getMessage());
}

$id_enfant = $_POST['id_enfant'];
$id_membre = $_POST['id_membre'];
$date_demande_equipe = date('Y-m-d H:i:s');
$role = 'membre';

$sql = "INSERT INTO suivre (id_enfant, id_membre, date_demande_equipe, role) VALUES (:id_enfant, :id_membre, :date_demande_equipe, :role)";
$stmt = $linkpdo->prepare($sql);
$stmt->bindParam(':id_enfant', $id_enfant);
$stmt->bindParam(':id_membre', $id_membre);
$stmt->bindParam(':date_demande_equipe', $date_demande_equipe);
$stmt->bindParam(':role', $role);
$stmt->execute();


header('Location: page_admin.php');
?>
