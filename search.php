<!DOCTYPE html>
<html lang="fr" style="font-family: raleway-extrabold,Helvetica,Arial,Lucida,sans-serif;">

<?php
/**
 * @file search.php
 * @brief Page de recherche
 * @details Page de recherche, permet de rechercher un enfant  dans la base de données
 */
require_once ('fonctions.php');
is_logged();
is_validateur();
$linkpdo = connexionBd();
?>

<head>
    <meta charset="UTF-8">
    <title>Recherche</title>
    <link rel="stylesheet" href="style_css/style_index.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script type="text/javascript" src="js/script.js"></script>
</head>

<body>
    <!--------------------------------------------------------------- header ------------------------------------------------------------------->
    <?php create_header($linkpdo); ?>
    <a href="index.php"><button class="bouton_recherche_R">Retour</button></a>

    <main class="search">

        <nav class="left-contenu block_recherche">
            <ul class="scrolling-tabs nav-links gl-display-flex gl-flex-grow-1 gl-w-full nav gl-tabs-nav nav gl-tabs-nav">
                <li class="nav-item">
                    <a class="shortcuts-activity nav-link gl-tab-nav-item active gl-tab-nav-item-active" data-placement="" href="index.php">Enfant</a>
                </li>
                <?php
if ($_SESSION["role_user"] == 1 or $_SESSION["role_user"] == 2) {
    echo '<li class="nav-item">';
    echo '<a data-placement="" class="nav-link gl-tab-nav-item" href="page_certif_compte.php">Membre</a>';
    echo '</li>';
}
?>

            </ul>



            <?php
if ($_SESSION["role_user"] != 2) {
    echo "<table >";
?>
                <div class="resultat">
                    <?php
    echo "<div class='liste-enfant'>";
    echo "<div class=\"recherche\">
                    <form class='recherche' method=\"post\" action=\"search.php\">
                    <div>
                    <input class=\"input_recherche\" type=\"text\" placeholder=\"Mots-clés ...\" id=\"keywords\" name=\"keywords\" required> 
                    </div>
                    <input class=\"bouton_recherche\" type=\"submit\" value=\" &#x1F50E;\">
                    </form>
                    </div>";
    echo "<table >";
    if (isset($_POST['keywords'])) {
        // Préparation de la requête
        $search = implode(array_filter(str_split($_POST['keywords']), "filter_spaces"));
        $keywords = explode(" ", $search);
        $query = "SELECT * FROM enfant WHERE visibilite=0 and  ";
        for ($i = 0;$i < count($keywords);$i++) {
            $query.= "nom LIKE '%$keywords[$i]%' OR prenom LIKE '%$keywords[$i]%' and visibilite=0";
        }
        // Exécution de la requête
        $stmt = $linkpdo->query($query);
        $contacts = $stmt->fetchAll();
        // Affichage des résultats
        if (count($contacts) > 0) {
            echo "<p class=\"titre_recherche\">" . count($contacts) . " résultat(s) trouvé(s) :</p>";
            echo "<table>";
            echo "<tr>
                        </tr>";
            foreach ($contacts as $contact) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($contact['nom']) . "</td>";
                echo "<td>" . htmlspecialchars($contact['prenom']) . "</td>";
                echo "<td>";
                echo '<a href="index.php?id=' . $contact['id_enfant'] . '"><button  class="acceder-information-enfant">Acceder</button> </a>';
                echo "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p class=\"titre_recherche\">Aucun enfants trouvé.<p>";
        }
    }
?>
                </div>
            <?php
    echo "</table>";
    /// $res->closeCursor();
    
}
?>
        </nav>

    </main>
</body>

</html>