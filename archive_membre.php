<?php
/**
 * @file archive_membre.php
 * @brief Page d'archivage d'un membre
 * @details Page d'archivage d'un membre, permet à l'administrateur de rendre un membre invisible pour les autres utilisateurs
 * @version 1.0
 */
require_once ('fonctions.php'); //utilisation des fonctions de la page fonctions.php
is_logged(); //vérifie si l'utilisateur est connecté
is_user(); //vérifie si l'utilisateur est un administrateur

/**
 * @details connexion à la base de données avec la fonction connexionBd()
 */
$linkpdo = connexionBd();

?>

<!DOCTYPE html>
<html lang="fr" style="font-family: raleway-extrabold,Helvetica,Arial,Lucida,sans-serif;">

<head>
    <meta charset="utf-8">
    <title> Archive membre </title>
    <link rel="stylesheet" href="style_css/style_page_certif_account.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script type="text/javascript" src="js/script.js"></script>
</head>

<body>
    <?php create_header($linkpdo);
?>
    <main>
        <nav class="left-contenu">
            <div style="display: flex; margin: 3%;">
                <a class="retour" href="page_certif_compte.php"> Retour</a>
            </div>

            <div class="dropdown">
                <button onclick="myFunction()" class="dropbtn">Compte membre ☰</button>
                <div id="myDropdown" class="dropdown-content">

<?php
/**
 * @details Affichage de la liste des membres visible
 * @return Affiche la liste des membres
 * */
try { //affichage de la liste des membres
    $res = $linkpdo->query("SELECT * FROM `membre` WHERE visibilite = 1");
}
/**
 * @exception Affiche un message d'erreur si la requête ne fonctionne pas
 */
catch(Exception $e) {
    die('Erreur : ' . $e->getMessage());
};
/** 
 * @details stockage des données dans un tableau avec la fonction fetchAll() avec les informations de la requête
 */
;
$double_tab = $res->fetchAll();
/**
 * @details nombre de ligne du tableau
 */
$nombre_ligne = $res->rowCount();
/**
 * @details création d'un tableau vide avec array()
 */
$liste = array();
echo "<div>";
echo "<table class='no-break'>";
/**
 * @details boucle for pour afficher les données du tableau
 */
for ($i = 0;$i < $nombre_ligne;$i++) {
    echo "<tr>";
    for ($y = 1;$y < 3;$y++) {
        echo "<td>";
        print_r(ucfirst(htmlspecialchars($double_tab[$i][$y])));
        $liste[$y] = $double_tab[$i][$y];
        $nom = $double_tab[$i][1];
        $prenom = $double_tab[$i][2];
        echo "</td>";
    }
    $identifiant = $double_tab[$i][0];
    echo "
                        <td>
                        <a href=\"archive_membre.php?idv=" . $identifiant . "\">  <button  class=\"acceder-information-membre\"> Profil </button></a>
                        </td>
                        </tr>
                        ";
}
echo "</table>";
$res->closeCursor();
?>
                </div>
            </div>
        </nav>

        <?php
if (isset($_GET['idv'])) {
    $id = $_GET['idv'];
    try {
        $res = $linkpdo->query("SELECT * FROM membre,suivre where membre.id_membre='$id' ORDER BY nom;");
    }
    catch(Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }
    $double_tab = $res->fetchAll();
    $nombre_ligne = $res->rowCount();
    $liste = array();
    $id_membre = $double_tab[0][0];
    $nom_membre = ucfirst($double_tab[0][1]);
    $prenom_membre = ucfirst($double_tab[0][2]);
    $adresse_membre = $double_tab[0][3];
    $code_postal_membre = $double_tab[0][4];
    $ville_membre = $double_tab[0][5];
    $courriel_membre = $double_tab[0][6];
    switch ($double_tab[0][11]) {
        case '0':
            $role = 'Utilisateur';
        break;
        case '1':
            $role = "Administrateur";
        break;
        case '2':
            $role = "Validateur (administration)";
        break;
        default:
            $role = "Coordinateur";
        break;
    }
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
}
?>
        <nav class="right-contenu">
            <div class="section_membre">
                <?php
if (isset($_GET['idv'])) {
    $idiv = $_GET['idv'];
    echo "
                    <div class=\"case-membre_1\">
                        <img class=\"img-tuteur\" src=\"img/user_logo.png\" alt=\"tete de l'utilisateur\">
                    </div>
                    <div class='grille_2_cases'>
                        <div class=\"case-3-infos\">
                            <p class=\"info\">  Nom :<strong>" . htmlspecialchars($nom_membre) . "</strong></p>
                            <p class=\"info\">Prénom : <strong>" . htmlspecialchars($prenom_membre) . "</strong></p>
                            <p class=\"info\">Date de naissance : <strong>" . htmlspecialchars($date_naissance_membre) . "</strong></p>
                            <p class=\"info\">Role de l'utilisateur : <strong>$role</strong></p>
                        </div>
                        <div class=\"case-3-infos\">
                            <p class=\"info\">Adresse mail : <strong>" . htmlspecialchars($courriel_membre) . "</strong></p>
                            <p class=\"info\">Adresse : <strong> " . htmlspecialchars($adresse_membre) . "" . htmlspecialchars($ville_membre) . " </strong></p>
                            <p class=\"info\">Code postal : <strong> " . htmlspecialchars($code_postal_membre) . "</strong> </p>
                        </div>
                    </div>
                    <div class=\"case-membre_2\">";
    if ($_SESSION["role_user"] != 3) {
        if ($idiv != $_SESSION['logged_user']) {
            if ($_GET["idv"] != 1) {
                echo "
                                <button  class=\"valider\" type=\"button\" onclick=\"openDialog('dialogI" . $idiv . "', this)\">Restaurer ce compte membre</button>
                                <div id=\"dialog_layer\" class=\"dialogs\">
                                    <div role=\"dialog\" id=\"dialogI" . $idiv . "\" aria-labelledby=\"dialog1_label\" aria-modal=\"true\" class=\"hidden\">
                                        <form action=\"\" method=\"post\" class=\"dialog_form\">
                                            <p class='popup-txt'>Voulez-vous restaurer ce compte membre dans l'application ?</p>
                                            <div class=\"dialog_form_actions\">
                                                <button  class='popup-btn' onclick=\"closeDialog(this)\">Annuler</button>
                                                <a href=\"appel_fonction.php?appel=restaure_utilisateur&id_user=" . $idiv . "\"> <button class='popup-btn'> Restaurer </button></a>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <button  class=\"valider\" type=\"button\" onclick=\"openDialog('dialog_sup_membre" . $idiv . "', this)\">Supprimer ce compte membre</button>
                                <div id=\"dialog_layer\" class=\"dialogs\">
                                    <div role=\"dialog\" id=\"dialog_sup_membre" . $idiv . "\" aria-labelledby=\"dialog1_label\" aria-modal=\"true\" class=\"hidden\">
                                        <form action=\"\" method=\"post\" class=\"dialog_form\">
                                            <p class='popup-txt'>Voulez-vous supprimer ce compte membre dans l'application ? Cette action est irréversible, elle supprimera tous les messages de ce membre, et le retirera de toutes les équipes. </p>
                                            <div class=\"dialog_form_actions\">
                                                <button  class='popup-btn' onclick=\"closeDialog(this)\">Annuler</button>
                                                <a href=\"appel_fonction.php?appel=supprime_utilisateur&id_user=" . $idiv . "\"> <button class='popup-btn'> Supprimer </button> </a>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                ";
            }
        }
    }
}
?>
            </div>

        </nav>
    </main>
</body>

</html>