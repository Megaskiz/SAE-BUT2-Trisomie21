<?php
/**
 * @file logout.php
 * @brief Page de Déconnexion
 * @details Page de Déconnexion, permet à l'utilisateur de se déconnecter de son compte
 * @version 1.0
 */
require_once('fonctions.php');//utilisation des fonctions de la page fonctions.php
is_logged();//vérifie si l'utilisateur est connecté
session_start();//démarre une session
$_SESSION['logged_user']=null;//détruit la variable de session
session_destroy();//détruit la session

header('Location: login.php');//redirige vers la page de connexion

?>