<?php
require('fonctions.php');
is_logged();
session_start();
$_SESSION['logged_user']=null;
session_destroy();

header('Location: login.php');

?>