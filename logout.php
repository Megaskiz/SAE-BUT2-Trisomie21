<?php
require('fonctions.php');
is_logged();
session_destroy();
header('Location: html_login.php');

?>