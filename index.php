<?php
require('fonctions.php');
is_logged();
is_validateur();
///Connexion au serveur MySQL
$linkpdo = connexionBd();
?>
<!DOCTYPE html>
<html lang="fr" style="font-family: Arial,sans-serif;">

<head>
	<meta charset="utf-8">
	<title> Menu principal </title>
	<link rel="stylesheet" href="style_css/style_index.css">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script type="text/javascript" src="js/script.js"></script>
</head>

<body>
	<!--- HEADER -->
	<?php	create_header($linkpdo); ?>

	<!--------------------------------------------------------------- Contenu ------------------------------------------------------------------->

	<!--------------------------------------- menu liste enfant (gauche) -------------------------------------------->
	<button class="openbtn" onclick="{
    var leftContenu = document.querySelector('.left-contenu');
    if (leftContenu.classList.contains('open')) {
        leftContenu.classList.remove('open');
    } else {
        leftContenu.classList.add('open');
    }
    }">☰</button>

	<main>

		<?php //affichage de la liste de gauche, avec les profils enfants, et les sous menus, en fonction des droits 

		create_nav($linkpdo);	
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

		//echo"<a href=\"appel_fonction.php?appel=purge_image\">lkmlkj</a>"; // bouton pour faire une purge dans les images
		?>
		
		<!--------------------------------------- menu information sur l'enfant (droite) -------------------------------------------->
		<nav class="right-contenu">
			<?php
			if (!isset($_GET['id'])) { // si pas d'enfant selectioné
				//afficher les cases vides
				echo "
					<section class=\"section_enfant\">
					</section>

					<section class=\"nb-systeme\">
					</section>
					";
			} else { // si enfant selectioné

				$_SESSION['id_enfant'] = $_GET['id'];
				$id_enfant = $_SESSION['id_enfant'];

				//   ---- menu droit information sur l'enfant ---->
				
				echo "<section class=\"section_enfant\">";
				create_section_info_enfant($linkpdo, $id_enfant);
				echo "</section>";
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
