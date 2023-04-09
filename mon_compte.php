<?php
/**
 * @file mon_compte.php
 * @brief Page de mon compte
 * @details Page de mon compte, permet à l'utilisateur de voir ses informations personnelles quand il est connecté
 * @version 1.0
 */
require_once ('fonctions.php');
is_logged();
?>
<!DOCTYPE html>
<html lang="fr" style="font-family: raleway-extrabold,Helvetica,Arial,Lucida,sans-serif;">

<?php
$linkpdo = connexionBd();
if (isset($_GET['id_valider'])) {
    $id_valider_membre = $_GET['id_valider'];
    $req_add = "UPDATE `membre` SET `compte_valide` = '1' WHERE `membre`.`id_membre` =$id_valider_membre ;";
    try {
        $res = $linkpdo->query($req_add);
        header('Location: page_certif_compte.php');
    }
    catch(Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }
}
if (isset($_GET['id_invalider'])) {
    $id_invalider_membre = $_GET['id_invalider'];
    $req_add = "UPDATE `membre` SET `compte_valide` = '0' WHERE `membre`.`id_membre` =$id_invalider_membre ;";
    try {
        $res = $linkpdo->query($req_add);
        header('Location: page_certif_compte.php');
    }
    catch(Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }
}
?>

<head>
    <meta charset="utf-8">
    <title> Administrateur </title>
    <link rel="stylesheet" href="style_css/style_page_certif_account.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script type="text/javascript" src="js/script.js"></script>
</head>

<body>
        <!--HEADER -->
<?php create_header($linkpdo); ?>


    <!--------------------------------------------------------------- Contenu ------------------------------------------------------------------->

    <!--------------------------------------- menu liste membre (gauche) -------------------------------------------->
    <main>

        <nav class="left-contenu">
            <?php
if ($_SESSION["role_user"] != 2) {
    echo '<ul class="scrolling-tabs nav-links gl-display-flex gl-flex-grow-1 gl-w-full nav gl-tabs-nav nav gl-tabs-nav">';
    echo '<li class="nav-item">';
    echo '<a class="nav-link gl-tab-nav-item" data-placement="right" href="index.php">Affichage Enfant</a>';
    echo '</li>';
    echo '<li class="nav-item">';
    echo '<a data-placement="" class="shortcuts-activity nav-link gl-tab-nav-item active gl-tab-nav-item-active" href="page_certif_compte.php">Mon profil</a>';
    echo '</li>';
    echo '</ul>';
}
?>
            

        <div class="dropdown">
            <button onclick="myFunction()" class="dropbtn">Compte membre ☰</button>
        <div id="myDropdown" class="dropdown-content">
        </nav>

        <?php // affichage central de la page, avec les informations
$id = $_SESSION['logged_user'];
try {
    $res = $linkpdo->query("SELECT * FROM membre where id_membre='$id' ORDER BY nom;");
}
catch(Exception $e) {
    die('Erreur : ' . $e->getMessage());
}
$double_tab = $res->fetchAll();
$nombre_ligne = $res->rowCount();
$liste = array();
$id_membre = $double_tab[0][0];
$nom_membre = $double_tab[0][1];
$prenom_membre = $double_tab[0][2];
$adresse_membre = $double_tab[0][3];
$code_postal_membre = $double_tab[0][4];
$ville_membre = $double_tab[0][5];
$courriel_membre = $double_tab[0][6];
$date_naissance_membre = date_format(new DateTime(strval($double_tab[0][7])), 'd/m/Y');
try {
    $res = $linkpdo->query("SELECT * FROM suivre natural join membre  where id_membre='$id' ORDER BY nom;");
}
catch(Exception $e) {
    die('Erreur : ' . $e->getMessage());
}
$double_tab_tuteur = $res->fetchAll();
$nombre_ligne = $res->rowCount();
$liste = array();
?>
        <!--------------------------------------- menu information sur le membre (droite) -------------------------------------------->
        <nav class="right-contenu">
            <div  class="section_membre">
                <?php
//<!---- menu droit information ---->
echo "<div class=\"case-membre_1\">";
echo "<img class=\"img-tuteur\" src=\"img/user_logo.png\" alt=\"tete de l'utilisateur\">";
echo "</div>";
echo "<div class='grille_2_cases'>";
echo "<div class=\"case-3-infos\">";
echo "<p class=\"info\"> Nom :<strong> " . htmlspecialchars($nom_membre) . "</strong></p>";
echo "<p class=\"info\">Prénom : <strong>" . htmlspecialchars($prenom_membre) . "</strong></p>";
echo "<p class=\"info\">Date de naissance : <strong>" . htmlspecialchars($date_naissance_membre) . "</strong></p>";
echo "</div>";
echo "<div class=\"case-3-infos\">";
echo "<p class=\"info\">Adresse mail : <strong>" . htmlspecialchars($courriel_membre) . "</strong></p>";
echo "<p class=\"info\">Adresse : <strong>" . htmlspecialchars($adresse_membre) . "" . htmlspecialchars($ville_membre) . "</strong></p>";
echo "<p class=\"info\">Code postal : <strong> " . htmlspecialchars($code_postal_membre) . " </strong> </p>";
echo "</div>";
echo "</div>";
echo " <div class=\"case-membre_2\">";
?>
            
            <div class="case-membre_2">
                <?php
echo '<button class="modif-certif" type="button" onclick="window.location.href=\'modif_mon_mdp.php\'">Modifier mon mot de passe</button>';
?>
            </div>
            </div>

        </nav>
    </main>
</body>

</html>