<!DOCTYPE HTML>
<?php // la partie de la connexion

///Connexion au serveur MySQL
$linkpdo = connexionBd();



// je récupere les informations de mon formulaire
if (
    !empty($_POST['nom'])
    && !empty($_POST['prenom'])
    && !empty($_POST['adresse'])
    && !empty($_POST['code_postal'])
    && !empty($_POST['ville'])
    && !empty($_POST['courriel'])
    && !empty($_POST['ddn'])
    && !empty($_POST['password'])
    && !empty($_POST['password_verif'])
) {
    $nom = htmlspecialchars($_POST['nom']);
    $prenom =  htmlspecialchars($_POST['prenom']);
    $adresse =  htmlspecialchars($_POST['adresse']);
    $code =  htmlspecialchars($_POST['code_postal']);
    $ville =  htmlspecialchars($_POST['ville']);
    $courriel =  htmlspecialchars($_POST['courriel']);
    $ddn =  htmlspecialchars($_POST['ddn']);
    $Mdp =  htmlspecialchars($_POST['password']);
    $Mdp_verif =  htmlspecialchars($_POST['password_verif']);
    $pro =  htmlspecialchars($_POST['pro']);

    // fonction qui hash le mot de passe
    $mot = "ZEN02anWobA4ve5zxzZz" . $Mdp; // je rajoute une chaine que je vais ajouter au mot de passe
    $insert_mdp = hash('sha256', $mot);

    if ($Mdp == $Mdp_verif) {
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
                    'nom' => htmlspecialchars($nom),
                    'prenom' => htmlspecialchars($prenom),
                    'adresse' => htmlspecialchars($adresse),
                    'code_postal' => htmlspecialchars($code),
                    'ville' => htmlspecialchars($ville),
                    'courriel' => htmlspecialchars($courriel),
                    'date_naissance' => htmlspecialchars($ddn),
                    'mdp' => htmlspecialchars($insert_mdp),
                    'compte_valide' => 0
                ));
                if ($req == false) {
                    die("erreur execute");
                }
            } catch (Exception $e) {
                die('Erreur : ' . $e->getMessage());
            }

            header('Location: compte_cree.php');
            exit();
        }
    } else {
        $message_erreur = " Les mots de passe ne correspondent pas.";
    }
} else {

    $nom = "";
    $prenom = "";
    $adresse =  "";
    $code =  "";
    $ville =  "";
    $courriel =  "";
    $ddn = "";
    $Mdp = "";
    $Mdp_verif =  "";
}

?>
<html lang="fr" style="font-family: Arial,sans-serif;">

<head>
    <meta charset="utf-8">
    <!-- importer le fichier de style -->
    <link rel="stylesheet" href="style_css/style_setup.css" media="screen" type="text/css" />
    <title>Créer un compte</title>
</head>

<body>
    <div class="login-page">
        <div class="form">
            <p><?php if (isset($message_erreur)) {
                    echo $message_erreur;
                }  ?></p>
            <div class="grille">
                <img class="logo" src="/sae-but2-s1/img/logo_trisomie.png" alt="Logo de l'association Trisomie 21">
                <form action="" method="post" class="login-form">
                    <input required type="text" name="nom" placeholder="Nom" value=<?php echo htmlspecialchars($nom) ?>>
                    <input required type="text" name="prenom" placeholder="Prénom" value=<?php echo htmlspecialchars($prenom) ?>>
                    <input required type="text" name="adresse" placeholder="Adresse" value=<?php echo htmlspecialchars($adresse) ?>>
                    <input required type="number" name="code_postal" placeholder="Code postal" value=<?php echo htmlspecialchars($code) ?>>
                    <input required type="text" name="ville" placeholder="Ville" value=<?php echo htmlspecialchars($ville) ?>>
                    <input required type="email" name="courriel" placeholder="Adresse e-mail" value=<?php echo htmlspecialchars($courriel) ?>>
                    <input required type="date" name="ddn" placeholder="date de naissance" value=<?php echo htmlspecialchars($ddn) ?>>
                    <input required type="text" name="password" placeholder="Mot de passe" value=<?php echo htmlspecialchars($Mdp) ?>>
                    <input required type="text" name="password_verif" placeholder="Confirmer le Mot de passe" value=<?php echo htmlspecialchars($Mdp_verif) ?>>

                    <input class="button" type="submit" value="Valider l'inscription">
                    <p class="message">Déjà un compte ? <a href="login.php">S'identifer</a></p>

                </form>
            </div>
        </div>
    </div>
</body>

</html>