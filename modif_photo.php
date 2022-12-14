<head>
    <meta charset="utf-8">
    <!-- importer le fichier de style -->
    <link rel="stylesheet" href="style_login.css" media="screen" type="text/css" />
    <title>bienvenue</title>
</head>

<body>
    <div class="login-page">
        <div class="form">
            <div class="grille">
                <img class="logo" src="/sae-but2-s1/img/logo_trisomie.png" alt="Logo de l'association Trisomie 21">
                <form enctype="multipart/form-data" action="" method="post" class="login-form">
                    <?php // la partie de la connexion
                    ///Connexion au serveur MySQL
                    try {
                        $linkpdo = new PDO("mysql:host=localhost;dbname=bddsae", "root", "");
                    }
                    ///Capture des erreurs éventuelles
                    catch (Exception $e) {
                        die('Erreur : ' . $e->getMessage());
                    }

                    require('fonctions.php');

                    // je récupere les informations de mon formulaire
                    // var_dump($_POST);
                    // var_dump($_FILES);
                    // exit();
                    $photo_enfant = uploadImage($_FILES['photo_enfant']);
                    $_GET['id'];




                    // je creé la requete d'insertion 


                    $req = $linkpdo->prepare("UPDATE `enfant` SET `photo_enfant` = '$photo_enfant' WHERE `enfant`.`id_enfant` = $id;");

                    try {
                        
                        $count = $res->fetchColumn();
                    } catch (Exception $e) { // toujours faire un test de retour au cas ou ça crash
                        die('Erreur : ' . $e->getMessage());
                    }

                    header('Location: page_admin.php');
                    exit();
                    ?>
                    <a href="page_admin.php">Retour à la page précédente</a>
                </form>
            </div>
        </div>
    </div>
</body>

</html>