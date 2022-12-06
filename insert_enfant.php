



<html>
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
            $date_naissance = $_POST['date_naissance'];
            $lien_jeton=uploadImage($_FILES['lien_jeton']);

            





            
            // requete avec le mail si, rowcount() > 0 faire fail
            $requete_verif_enfant = "SELECT count(*) FROM enfant WHERE nom='$nom' and prenom='$prenom' and date_naissance='$date_naissance';";
            // Execution de la requete
            try {
                $res = $linkpdo->query($requete_verif_enfant);
                $count = $res -> fetchColumn();
                }
                catch (Exception $e) { // toujours faire un test de retour au cas ou ça crash
                    die('Erreur : ' . $e->getMessage());
                }
                if ($count > 0 ){
                    $message_erreur = "il y déjà un enfant avec ce nom et ce prénom ";
                    echo$message_erreur;echo"<br>";echo"<br>";echo"<br>";echo"<br>";echo"<br>";echo"<br>";
                    
                }else{
                    // je creé la requete d'insertion 

                    $req = $linkpdo->prepare('INSERT INTO enfant(nom, prenom, date_naissance, lien_jeton)
                    VALUES(:nom, :prenom, :date_naissance, :lien_jeton)');

                    if ($req == false){
                        die("erreur linkpdo");
                    }   
                        ///Exécution de la requête
                    try{
                        $req->execute(array('nom' => $nom,
                                            'prenom' => $prenom,
                                            'date_naissance' => $date_naissance,
                                            'lien_jeton' => $lien_jeton
                                    ));
                        if ($req == false){
                            $req->debugDumpParams();
                            die("erreur execute");
                        }
                    }
                    
                    catch (Exception $e)
                    {die('Erreur : ' . $e->getMessage());}
                    
                    header('Location: page_admin.php');
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


