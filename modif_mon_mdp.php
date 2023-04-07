<?php
/**
 * @file page_certif_compte.php
 * @brief Page de certification des comptes
 * @details Page de certification des comptes, permet à un administrateur de valider ou invalider un compte
 * @version 1.0
 */
require_once ('fonctions.php');
is_logged();
$linkpdo = connexionBd();
?>
<!DOCTYPE html>
<html lang="fr" style="font-family: raleway-extrabold,Helvetica,Arial,Lucida,sans-serif;">
<head>
    <meta charset="utf-8">
    <title> Changement de mot de passe </title>
    <link rel="stylesheet" href="style_css/style_page_certif_account.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script type="text/javascript" src="js/script.js"></script>
</head>

<body>
        <!--------------------------------------------------------------- header ------------------------------------------------------------------->
<?php create_header($linkpdo); ?>

    <!--------------------------------------------------------------- Contenu ------------------------------------------------------------------->

    <!--------------------------------------- menu liste membre (gauche) -------------------------------------------->
    <main>
        <nav class="left-contenu">
            <?php
if ($_SESSION["role_user"] != 2) {
    echo '
                <ul class="scrolling-tabs nav-links gl-display-flex gl-flex-grow-1 gl-w-full nav gl-tabs-nav nav gl-tabs-nav">
                    <li class="nav-item">
                        <a class="nav-link gl-tab-nav-item" data-placement="right" href="index.php">Enfant</a>
                    </li>
                    <li class="nav-item">
                        <a data-placement="" class="shortcuts-activity nav-link gl-tab-nav-item active gl-tab-nav-item-active" href="page_certif_compte.php">Mon profil</a>
                    </li>
                </ul>
                ';
}
?>
        </nav>
        <!--------------------------------------- menu information sur le membre (droite) -------------------------------------------->
        <nav class="right-contenu">
            <div class="section_membre" style=" display:grid; grid-template-columns: 30% 1fr ; grid-template-rows:1fr;">
                <?php
//<!---- menu droit information ---->
echo "
                    <div class=\"case-membre_1\"  style='display : flex; align-items: flex-end'>
                        <a href='page_certif_compte.php?idv='><button class='annuler'> Annuler &#x1F5D9;</button></a>
                    </div>
                    <form action=\"appel_fonction.php?appel=modif_mon_mdp\" method=\"post\">
                        <div class='grille_4_cases'>
                            <div class=\"case-3-infos\">
                                <div>
                                </div>
                                <div style=\"display:inline-flex; align-items: center;\">
                                    <p> Nouveau mot de passe  :</p><input required = \"required\" name=mdp_membre type=\"text\" value=\"\">
                                </div>
                            </div>
                            <div class=\"case-3-infos\">
                            </div>
                            <input class=\"valider\" type=\"submit\" value=\"Valider les modifications &#x2714;\">
                        </div>
                    </form>
                    <div class=\"case-membre_2\">
                        <p></p>
                    </div>";
?>
        </div>
        </nav>
    </main>
</body>

</html>