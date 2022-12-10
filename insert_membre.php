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

                    $nom = $_POST['nom'];
                    $prenom = $_POST['prenom'];
                    $adresse = $_POST['adresse'];
                    $code = $_POST['code_postal'];
                    $ville = $_POST['ville'];
                    $courriel = $_POST['courriel'];
                    $ddn = $_POST['ddn'];
                    $Mdp = $_POST['password'];
                    $Mdp_verif = $_POST['password_verif'];
                    $pro = $_POST['pro'];
                

                    // requete avec le mail si, rowcount() > 0 faire fail
                    $requete_verif_mail = "SELECT count(*) FROM membre WHERE courriel='$courriel'";
                    // Execution de la requete
                    try {
                        $res = $linkpdo->query($requete_verif_mail);
                    } catch (Exception $e) { // toujours faire un test de retour au cas ou ça crash
                        die('Erreur : ' . $e->getMessage());
                    }

                    $count = $res->fetchColumn();

                    if ($count > 0) {
                        $message_erreur = "il y déjà un compte avec cette adresse mail ";
                    } else {


                        // je creé la requete d'insertion 

                        $req = $linkpdo->prepare('INSERT INTO membre(nom, prenom, adresse, code_postal, ville, courriel, date_naissance, mdp, pro, compte_valide)
                        VALUES(:nom, :prenom, :adresse, :code_postal, :ville, :courriel, :date_naissance, :mdp, :pro, :compte_valide)');

                        if ($req == false) {
                            die("erreur linkpdo");
                        }
                        ///Exécution de la requête
                        try {
                            $req->execute(array(
                                'nom' => $nom,
                                'prenom' => $prenom,
                                'adresse' => $adresse,
                                'code_postal' => $code,
                                'ville' => $ville,
                                'courriel' => $courriel,
                                'date_naissance' => $ddn,
                                'mdp' => $Mdp,
                                'pro' => $pro, // à changer
                                'compte_valide' => 1
                            ));
                            if ($req == false) {
                                $req->debugDumpParams;
                                die("erreur execute");
                            }
                        } catch (Exception $e) {
                            die('Erreur : ' . $e->getMessage());
                        }

                        header('Location: page_certif_compte.php');
                        exit();
                    }

                    ?>
                    <a href="page_admin.php">Retour à la page précédente</a>
                </form>
            </div>
        </div>
    </div>
</body>

</html>