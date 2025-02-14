<?php
/**
 * @file insert_erreur_nom.php
 * @brief Page d'erreur lors de la création d'un profil
 * @details Page d'erreur lors de la création d'un profil, permet d'afficher une erreur lors de la création d'un profil qui existe déjà
 * @version 1.0
 */
?>
<!DOCTYPE HTML>
<html lang="fr" style="font-family: raleway-extrabold,Helvetica,Arial,Lucida,sans-serif;">

<head>
    <meta charset="utf-8">
    <!-- importer le fichier de style -->
    <link rel="stylesheet" href="style_css/style_login.css" media="screen" type="text/css" />
    <title>Erreur</title>
</head>
<!-- à changer pour faire en sorte de gérer plusieurs erreurs -->
<body>
    <div class="login-page">
        <div class="form">
            <div class="grille">
                <img class="logo" src="/sae-but2-s1/img/logo_trisomie.png" alt="Logo de l'association Trisomie 21">
                <form>
                    <p>Il est impossible d'ajouter ce profil, car un profil avec les mêmes informations existe déjà.</p>
                    <a href="index.php">Retour sur la page principale</a>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
