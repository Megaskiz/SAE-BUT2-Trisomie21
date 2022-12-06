<?php
session_start();
if (isset($_POST["radio1"]) & isset($_POST["radio2"])) {
    $res1 = $_POST["radio1"];
    $res2 = $_POST["radio2"];
    echo$res1;
    echo$res2;
    echo$_SESSION['id_enfant'];
}
?>