<!DOCTYPE html>
<html lang="fr" style="font-family: Arial,sans-serif;">

<?php
require('fonctions.php');
is_logged();
is_validateur();
///Connexion au serveur MySQL
try {
	$linkpdo = new PDO("mysql:host=localhost;dbname=bddsae", "root", "");
}
///Capture des erreurs éventuelles
catch (Exception $e) {
	die('Erreur : ' . $e->getMessage());
}

if (isset($_GET['id_suppr'])) {
	$id_suppression = $_GET['id_suppr'];
	// faire un update dans la bd sur un champs en plus
	// il faut le faire sur :
	
	/*
	- la table enfant
	*/
	$req = $linkpdo->prepare('UPDATE enfant SET visibilite = "1" where id_enfant = '.$_SESSION["id_enfant"]);

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
?>

		<?php // affichage central de la page, avec les informations sur les enfants

		if (isset($_GET['id'])) { // si on clique sur "acceder" alors on recherche les infos d'un enfant
			$id = $_GET['id'];
			$_SESSION["id_enfant"] = $id;



		

			try {
				$res = $linkpdo->query("SELECT * FROM suivre natural join membre  where id_enfant='$id'");
			} catch (Exception $e) { // toujours faire un test de retour au cas ou ça crash
				die('Erreur : ' . $e->getMessage());
			}


			///Affichage des entrées du résultat une à une

			$double_tab_tuteur = $res->fetchAll(); // je met le result de ma query dans un double tableau
			$nombre_ligne = $res->rowCount(); // =2 car il y a 2 ligne dans ma base
			$liste = array();

			// print_r($double_tab_tuteur);
			// exit();


			echo "</div>";
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
					create_section_info_enfant($linkpdo, $id_enfant);
					//   ---- menu droit information sur les objectifs de l'enfant ---->
					create_section_info_sys($linkpdo);

					
					?>
	
					
<?php
					/// début de la section des systèmes ///
					
					echo "<section class=\"nb-systeme\">";
					//acces aux boutons -> ajouter sys, stat, stat4semaines
					if ($_SESSION["role_user"] == 1 or $_SESSION["role_user"] == 3) {
						echo' <div class="btn-objectif">';
						echo' <div class="btn-stat">';
						echo '   <a href="page_creatsystem.php"><button class="button_ajouter-objectif"> Ajouter un nouvel objectif</button></a>';
						echo '   <a href="  archive_sys.php"><button class="button_ajouter-objectif"> Objectifs archivés</button></a>';
						echo' </div>';
						echo' <div class="btn-stat">';
						echo '   <a href="statistiques.php"><button class="button_ajouter-objectif">Toutes les statistiques</button></a>';
						echo '   <a href="statistiques_quatre_semaines.php"><button class="button_ajouter-objectif">Statistiques 4 dernières semaines</button></a>';
						echo' </div>';   
						echo' </div>';
										  
					   }




					// tous les systèmes de l'enfant :

				   
					try {
						$res = $linkpdo->query('SELECT intitule, nb_jetons, duree, priorite, travaille, id_objectif FROM objectif where visibilite=0 and id_enfant=' . $id . ' ORDER BY priorite ');
					} catch (Exception $e) { // toujours faire un test de retour en cas de crash
						die('Erreur : ' . $e->getMessage());
					}


					///Affichage des entrées du résultat une à une

					$double_tab = $res->fetchAll(); // je met le result de ma query dans un double tableau
					$nombre_ligne = $res->rowCount();
					$liste = array();




					echo "<table class='affichage-objectif'>";
					echo "<colgroup class='column'></colgroup>";
					echo "<tr class='titre-objectif'>
						<th>Nom</th>
						<th>Jetons</th>
						<th>Durée</th>
						<th class='sms'>Message</th>
						<th>Statut</th>
						<th>Accéder</th>
						<th class='sup'>Archiver</th>
						</tr>";

					for ($i = 0; $i < $nombre_ligne; $i++) {
						//acces au systèmes
						if ($_SESSION["role_user"] == 1 || $double_tab[$i][4] == 1or $_SESSION["role_user"] == 3) {
							echo "<tr class='objectif_tr'>";

							#affiche nom
							echo "<td>";
							print_r(htmlspecialchars($double_tab[$i][0]));
							echo "</td>";

							#affiche nombre de jeton
							echo "<td>";
							echo "<center>";
							print_r(htmlspecialchars($double_tab[$i][1]));
							echo "</center>";
							echo "</td>";

							#affiche nombre de jour
							echo "<td>";
							$value = $double_tab[$i][2];
							switch ($double_tab[$i][2]) {
								case ($value < 24 ? $value : !$value):
									echo "<center>";
									print_r($double_tab[$i][2]);
									echo " Heure(s)";
									echo "</center>";
									break;

								case ($value < 24 * 7 ? $value : !$value):
									$reste = $value % 24;
									$jours = intdiv($value, 24);
									echo "<center>";
									echo $jours . " jour(s), " . $reste . " heure(s)";
									echo "</center>";
									break;

								default:
									$semaines = intdiv($value, (7 * 24));
									$reste1 = $value % (7 * 24); // pour savoir s'il reste quoi que ce soit 
									echo "<center>";
									echo $semaines . " semaine(s) ";
									echo "</center>";

									if ($reste1 > 23) { // il reste + d'un jour
										$restej = $value - (7 * 24); // le nombre d'heure au dela d'une semaine
										if ($reste1 > 23) { // si ce nombre d'heure au dela d'une semaine dépasse 1 jour
											$restejours = intdiv($reste1, 24);
											echo "<center>";
											echo $restejours . "jour(s)";
											echo "</center>";
										}
									} elseif ($reste1 > 0) { // s'il reste entre 1 et 23heures
										echo "<center>";
										echo $reste1 . "heure(s)";
										echo "</center>";
									}
									break;
							}





							echo "</td>";


							#affiche message
							echo "<td class='sms'>";
							echo "<center>";
							echo "<button class=\"message\" type=\"button\" onclick=\"openDialog('dialog_message" . $double_tab[$i][5] . "', this)\"> <span class=\" icon-mail\"> Messagerie &#128172; </span></button>";
							echo "</center>";
							echo "<div id=\"dialog_layer\" class=\"dialogs\">";
							echo "<div role=\"dialog\" id=\"dialog_message" . $double_tab[$i][5] . "\" aria-labelledby=\"dialog1_label\" aria-modal=\"true\" class=\"hidden\">";
							echo "<div class=\"dialog_form_actions3\">";
							echo "<button class=\"deco\" onclick=\"closeDialog(this)\">Retour</button>";
							echo "</div>";
							if (isset($double_tab[$i][5]) and !empty($double_tab[$i][5])) {

								/*$getid = $_GET['id_objectif'];
									/*$recupUser = $linkpdo->prepare('SELECT * FROM membre where id_membre = ?');
									$recupUser->execute(array($getid));
									if($recupUser->rowCount() > 0){*/
								if (isset($_POST["envoie" . $double_tab[$i][5]])) {
									$message = htmlspecialchars($_POST['messages']);
									$sujet = htmlspecialchars($_POST['sujet']);
									$insererMessage = $linkpdo->prepare('INSERT into message(corps,sujet,id_membre,date_heure,id_objectif) VALUES(?, ?, ?, NOW(), ?)');
									if (!$insererMessage) {
										die("Erreur prepare");
									}


									$insererMessage->execute(array($message, $sujet, $_SESSION['logged_user'], $double_tab[$i][5]));
									if (!$insererMessage) {
										die("Erreur execute");
									}
								}
								/*}else{
										echo ("aucun utilisateur trouvé");
									}*/
							} else {
								echo ("aucun id trouvé");
							}
							echo "<title>Envoie de mesage</title>";




				?>
							<div class="chat_all">
								<div class="chat_title">
								💬Messagerie du système à jeton
								</div>
								<div class="chat_list_msg">
									<section id="message">
										<?php
										$recupMessages = $linkpdo->prepare('SELECT sujet,corps,date_heure,membre.id_membre, membre.nom, membre.prenom FROM message,membre WHERE id_objectif = ? and membre.id_membre = message.id_membre');
										if (!$recupMessages) {
											die("Erreur prepare");
										}
										$recupMessages->execute(array($double_tab[$i][5]));
										if (!$recupMessages) {
											die("Erreur prepare");
										}

										while ($message = $recupMessages->fetch()) {
											if ($message['id_membre'] == $_SESSION['logged_user']) {
										?>
												<div class="chat_msgR">
													<img class="chat_img_R" src="/sae-but2-s1/img/user_logo.png" alt="tete de l'utilisateur">
													<div class="chat_vous">
														<div class="chat_info">
															<div class="chat_nomm"><?= ucfirst($message["nom"] . " " . $message["prenom"] . " (vous) : ") ?></div>
															<div class="chat_datem"><?= "le " . (new DateTime($message["date_heure"]))->format("d/m/Y H\hi") ?></div>
														</div>
														<p class="chat_zone_txt"> <?= "Sujet :" . $message["sujet"] . "<br>" . $message["corps"]; ?> </p>
													</div>
												</div>
											<?php
											} else {
											?>
												<div class="chat_msgL">
													<img class="chat_img_L" src="/sae-but2-s1/img/user_logo.png" alt="tête de l'utilisateur">
													<div class="chat_autre">
														<div class="chat_info">
															<div class="chat_nomm"><?= ucfirst($message["nom"] . " " . $message["prenom"]) ?></div>
															<div class="chat_datem"><?= "le " . (new DateTime($message["date_heure"]))->format("d/m/Y H\hi") ?></div>
														</div>
														<p class="chat_zone_txt"> <?= "Sujet :" . $message["sujet"] . "<br>" . $message["corps"]; ?> </p>
													</div>
												</div>
											<?php
											}
											?>
										<?php
										}
										?>
									</section>
								</div>
								<div class="chat_envoi_msg">
									<form method="POST" action="" class="">
										<div class="chat_sujet_msg">
											<input type="text" id="sujet" name="sujet" class="chat_sujet" placeholder="Sujet ..." required></br>
										</div>
										<div class="chat_txt_msg">
											<input class="chat_messages" name="messages" placeholder="Entrez votre message ..." required></br>
											<button type="submit" class="chat_send" name=<?= "envoie" . $double_tab[$i][5] ?>>Envoyer</button>
										</div>
									</form>
								</div>
							</div>

					<?php


							echo "</form>";
							echo "</td>";




					  

							//affiche bouton pour la mise en route des sys
							if ($_SESSION["role_user"] == 1 or $_SESSION["role_user"] == 3) {
								switch ($double_tab[$i][4]) {
									case 1:

										echo "<td>";
										echo "<center>";
										echo '<a href="utilisation.php?id_sys=' . $double_tab[$i][5] . '&valeur=0"><button class="status-objectif actif">Actif  &#x2714;</button></a>';
										echo "</center>";
										echo "</td>";
										break;

									case 0:
										echo "<td>";
										echo "<center>";
										echo '<a href="utilisation.php?id_sys=' . $double_tab[$i][5] . '&valeur=1"><button class="status-objectif  nonactif">Désactivé  &#x1F5D9;</button></a>';
										echo "</center>";
										echo "</td>";
										break;
								}
							}


							echo "<td>";
							echo "<center>";
							echo '<a href="choix_sys.php?id_sys=' . $double_tab[$i][5] . '"><button class="objectif-acceder"> Acceder </button></a>';
							echo "</center>";
							echo "</td>";

							echo "<td>";
							echo " <div class=\"case-enfant\">";
							//bouton supprimer un sys -> "archiver"
							if ($_SESSION["role_user"] == 1) {
								echo "<center>";
								echo "<button class=\"supprimer-objectif\" type=\"button\" onclick=\"openDialog('dialog" . $double_tab[$i][5] . "', this)\"><img class='delet-icon' src='img/archive.png'></a></button>";
								echo "</center>";
								echo "<div id=\"dialog_layer\" class=\"dialogs\">";

								echo "<div role=\"dialog\" id=\"dialog" . $double_tab[$i][5] . "\" aria-labelledby=\"dialog1_label\" aria-modal=\"true\" class=\"hidden\">";

								echo "<p class='popup-txt'> Attention, archiver ce système le retirera de tous les affichages et des statistiques, il ne sera accessible qu'aux coordinateur et à l'administrateur, dans l'archive.</p>";
								echo "<div class=\"dialog_form_actions\">";
								
								echo "<button class='popup-btn' onclick=\"closeDialog(this)\">Annuler</button>";
								echo "<a  href=\"suppr_sys.php?id_sys=" . $double_tab[$i][5] . "\"> <button> Archiver cet objectif </button> </a>";
								echo "</div>";
								
							}




							echo "</div>";
							echo "</div>";
							echo "</td>";

							echo "</tr>";
						}
					}
					echo "</table>";

					///Fermeture du curseur d'analyse des résultats
					$res->closeCursor();



					echo "</section>"; // fin de section sys
				}

				// Popup Ajouter equipier 


				echo '<div role="dialog" id="dialog2" aria-labelledby="dialog1_label" aria-modal="true" class="hidden">';
				//echo '<form enctype="multipart/form-data" action="groupe_validation.php" method="post" class="dialog_form">';

			 
				try {
					$res = $linkpdo->query("SELECT membre.* FROM membre LEFT JOIN suivre ON membre.id_membre = suivre.id_membre AND suivre.id_enfant = '$getid' WHERE membre.compte_valide = 1 AND suivre.id_membre IS NULL ORDER BY nom;");
				} catch (Exception $e) { // toujours faire un test de retour en cas de crash
					die('Erreur : ' . $e->getMessage());
				}

			 
				while ($tuteur = $res->fetch()) {
					echo "<div class='btn_ajouter'>";
					echo "<tr>";
					echo "<td>" . htmlspecialchars($tuteur['nom']) . "&nbsp" . "</td>";
					echo "<td>" . htmlspecialchars($tuteur['prenom']) . "</td>";
					echo "<td class='Profil'>";
				   
					echo "<form action='groupe_validation.php?id_enfant=$getid&id_membre=$tuteur[id_membre]' method='post'>";
					echo "<button id='ajt' type='submit'>Ajouter &#x2b;</button>";
					echo "</form>";
					echo "</div>";
					echo "<br>";
					echo "</td>";
					echo "</tr>";
				}
				
				echo "</table>";

				echo '<button class="popup-btn" type="button" onclick="closeDialog(this)">Annuler</button>';

				//echo "</form";
				echo "</div";
				?>
		</nav>
	</main>


</body>



</html>