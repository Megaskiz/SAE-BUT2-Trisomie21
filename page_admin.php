<!DOCTYPE html>
<html lang="fr" style="font-family: Arial,sans-serif;">

<?php
require('fonctions.php');
is_logged();
is_validateur();
///Connexion au serveur MySQL
$linkpdo = connexionBd();

if (isset($_GET['id_suppr'])) {
	$id_suppression = $_GET['id_suppr'];
	
	$req = $linkpdo->prepare('UPDATE enfant SET visibilite="1" where id_enfant='.$_SESSION["id_enfant"]);

	if ($req == false){
		die("erreur linkpdo");
	}   
		///Exécution de la requête
	try{
		
		$req->execute(array());
		// $req->debugDumpParams();
		// exit();
		header("Location:page_admin.php");
	   
		if ($req == false){
			$req->debugDumpParams;
			die("erreur execute");
		}else{
			echo"<a href=\"page_admin.php\"> recharger la page</a>";         
		   
		}
	}
	catch (Exception $e)
	{die('Erreur : ' . $e->getMessage());}
}

if (isset($_GET['eject'])) {
	$id_eject = $_GET['eject'];
	$Sid = $_GET['id'];
	$req_eject = "DELETE FROM suivre WHERE `suivre`.`id_enfant` = $Sid AND `suivre`.`id_membre` = $id_eject";
	try {
		$res = $linkpdo->query($req_eject);
		header('Location: page_admin.php?id='.$_SESSION['id_enfant']);
	} catch (Exception $e) { // toujours faire un test de retour au cas ou ça crash
		die('Erreur : ' . $e->getMessage());
	}
}
?>
	<head>
		<meta charset="utf-8">
		<title> Menu principal </title>
		<link rel="stylesheet" href="style_css/style_admin.css">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<script type="text/javascript" src="script.js"></script>
	</head>
	<body>
		<!--------------------------------------------------------------- header ------------------------------------------------------------------->
<?php create_header($linkpdo);?>


	<!--------------------------------------------------------------- Contenu ------------------------------------------------------------------->

	<!--------------------------------------- menu liste enfant (gauche) -------------------------------------------->
		<main>
			<script>
				function openMenu() {
					document.querySelector('.left-contenu').classList.add('open');
				}

				function closeMenu() {
					document.querySelector('.left-contenu').classList.remove('open');
				}
			</script>

<?php //affichage de la liste de gauche, avec les profils enfants, et les sous menus, en fonction des droits 
	switch ($_SESSION["role_user"]) {
		case '0':
			// utilisateur User
			create_nav_user($linkpdo);
			break;  
		
		case '1':
			// utilisateur Admin
			create_nav_admin($linkpdo);
			break;
	
		default:
			// utilisateur Coordinateur
			create_nav_coordinateur($linkpdo);
			break;
}
	// affichage central de la page, avec les informations sur les enfants

	if (isset($_GET['id'])) { // si on clique sur "acceder" alors on recherche les infos d'un enfant
		$id = $_GET['id'];
		$_SESSION["id_enfant"] = $id;

		try {
			$res = $linkpdo->query("SELECT * FROM suivre natural join membre  where id_enfant='$id'");
		} catch (Exception $e) { // toujours faire un test de retour au cas ou ça crash
			die('Erreur : ' . $e->getMessage());
		}

		$double_tab_tuteur = $res->fetchAll(); // je met le result de ma query dans un double tableau
		$nombre_ligne = $res->rowCount(); // =2 car il y a 2 ligne dans ma base
		$liste = array();
		}
		?>
		<!--------------------------------------- menu information sur l'enfant (droite) -------------------------------------------->
			<nav class="right-contenu">
				<?php
				if (!isset($_GET['id'])) { // si pas d'enfant selectioné
					//afficher les cases vides
					echo"
					<section class=\"section_enfant\">
					</section>

					<section class=\"nb-systeme\">
					</section>
					";
				
				}else{ // si enfant selectioné
				
					$_SESSION['id_enfant'] = $_GET['id'];
					$id_enfant= $_SESSION['id_enfant'];

					//   ---- menu droit information sur l'enfant ---->
					echo"<section class=\"section_enfant\">";
					create_section_info_enfant($linkpdo, $id_enfant);
					echo"</section>";
					//   ---- menu droit information sur les objectifs de l'enfant ---->

					echo "<section class=\"nb-systeme\">";
					create_section_info_sys($linkpdo, $id_enfant);
					echo "</section>"; // fin de section sys
				}
				?>
			</nav>
		</main>
	</body>
</html>