<!DOCTYPE html>
<html lang="fr">

<?php
session_start();
///Connexion au serveur MySQL
try {
    $linkpdo = new PDO("mysql:host=localhost;dbname=bddsae", "root", "");
}
///Capture des erreurs éventuelles
catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

?>

<head>
    <meta charset="utf-8">
    <title> Administrateur </title>
    <link rel="stylesheet" href="style_admin.css">
    <script type="text/javascript" src="script.js"></script>
</head>

<body>


    <header>
        <img class="logo-association" src="/sae-but2-s1/img/logo_trisomie.png" alt="logo de l'association">
        <img class="img-user" src="/sae-but2-s1/img/user_logo.png" alt="tete de l'utilisateur">

        <?php
        $mail =  $_SESSION['login_user'];
        try {
            $res = $linkpdo->query("SELECT nom, prenom FROM membre where courriel='$mail'");
        } catch (Exception $e) { // toujours faire un test de retour au cas ou ça crash
            die('Erreur : ' . $e->getMessage());
        }

        ///Affichage des entrées du résultat une à une

        $double_tab = $res->fetchAll(); // je met le result de ma query dans un double tableau
        $nombre_ligne = $res->rowCount(); // =2 car il y a 2 ligne dans ma base
        $liste = array();
        echo "<table>";

        for ($i = 0; $i < $nombre_ligne; $i++) {
            echo "<tr>";
            for ($y = 0; $y < 2; $y++) {
                echo "<td>";
                print_r($double_tab[$i][$y]);
                $liste[$y] = $double_tab[$i][$y];
                echo "</td>";
            }

            echo "</tr>";
        }

        echo "</table>";
        ?>
        <p class="h-deconnexion"><a href="html_login.php">Déconnexion</a></p>

    </header>


    <!--------------------------------------------------------------- Contenu ------------------------------------------------------------------->

    <!--------------------------------------- menu liste enfant (gauche) -------------------------------------------->
    <main>

        <nav class="left-contenu">
            <! -- /* Le bloc suivant est la fenêtre pop-in de l'ajout d'enfant, elle est caché tant qu'on appuie pas sur le bouton "ajouter enfant" */ -->
                <div>
                    <button class="ajouter-enfant" type="button" onclick="openDialog('dialog1', this)">Ajouter un enfant</button>
                    <div id="dialog_layer" class="dialogs">
                        <div role="dialog" id="dialog1" aria-labelledby="dialog1_label" aria-modal="true" class="hidden">
                            <h2 id="dialog1_label" class="dialog_label">Ajouter un enfant</h2>
                            <form action="insert_enfant.php" method="post" class="dialog_form">
                                <div class="dialog_form_item">
                                    <label>
                                        <span class="label_text">nom :</span>
                                        <input name="nom" type="text" required="required">
                                    </label>
                                </div>
                                <div class="dialog_form_item">
                                    <label>
                                        <span class="label_text">prenom:</span>
                                        <input name="prenom" type="text" class="city_input" required="required">
                                    </label>
                                </div>
                                <div class="dialog_form_item">
                                    <label>
                                        <span class="label_text">date de naissance:</span>
                                        <input name="date_naissance" type="date" class="state_input" required="required">
                                    </label>
                                </div>
                                <div class="dialog_form_item">
                                    <label>
                                        <span class="label_text">jeton ( a faire ):</span>
                                        <input name="lien_jeton" type="text" class="zip_input" required="required">
                                    </label>
                                </div>

                                <div class="dialog_form_actions">
                                    <button type="submit">Valider l'ajout</button>
                                    <button type="button" onclick="closeDialog(this)">Annuler</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <! -- /* fin de la fenêtre popin de l'ajout d'enfant" */ -->


                    <?php
                    ///Sélection de tout le contenu de la table enfant
                    try {
                        $res = $linkpdo->query('SELECT id_enfant, nom, prenom FROM enfant');
                    } catch (Exception $e) { // toujours faire un test de retour en cas de crash
                        die('Erreur : ' . $e->getMessage());
                    }

                    ///Affichage des entrées du résultat une à une

                    $double_tab = $res->fetchAll(); // je met le result de ma query dans un double tableau
                    $nombre_ligne = $res->rowCount();
                    $liste = array();
                    echo "<table>";

                    for ($i = 0; $i < $nombre_ligne; $i++) {
                        echo "<tr>";
                        for ($y = 1; $y < 3; $y++) {
                            echo "<td>";
                            print_r($double_tab[$i][$y]);
                            $liste[$y] = $double_tab[$i][$y];
                            $nom = $double_tab[$i][1];
                            $prenom = $double_tab[$i][2];
                            $age = $double_tab[1][$y];
                            echo "</td>";
                        }
                        $identifiant = $double_tab[$i][0];
                        echo "<td>";
                        echo '<a href="page_admin.php?id='.$identifiant.'">acceder</a>';
                        echo "</td>";
                        echo "</tr>";
                    }

                    echo "</table>";

                    ///Fermeture du curseur d'analyse des résultats
                    $res->closeCursor();
                    ?>
        </nav>

        <?php // affichage central de la page, avec les informations sur les enfants

            if (isset($_GET['id'])) {
                $id = $_GET['id'];



                ///Sélection de tout le contenu de la table carnet_adresse
                try {
                    $res = $linkpdo->query("SELECT * FROM enfant where id_enfant='$id'");
                } catch (Exception $e) { // toujours faire un test de retour au cas ou ça crash
                    die('Erreur : ' . $e->getMessage());
                }

                $double_tab = $res->fetchAll(); // je met le result de ma query dans un double tableau
                $nombre_ligne = $res->rowCount(); // =1 car il y a 1 ligne dans ma requete
                $liste = array();

 
                $id_enfant = $double_tab[0][0];
                $nom_enfant = $double_tab[0][1];
                $prenom_enfant = $double_tab[0][2];
                $ddn_enfant = $double_tab[0][3];
                $lien_jeton_enfant = $double_tab[0][4];
                // echo$id_enfant;
                // echo$nom_enfant;
                // echo$prenom_enfant;
                // echo$ddn_enfant;
                // echo$lien_jeton_enfant;
                


                ///Sélection de tout le contenu de la table carnet_adresse
                try {
                    $res = $linkpdo->query("SELECT * FROM suivre natural join membre  where id_enfant='$id'");
                } catch (Exception $e) { // toujours faire un test de retour au cas ou ça crash
                    die('Erreur : ' . $e->getMessage());
                }


                ///Affichage des entrées du résultat une à une

                $double_tab_tuteur = $res->fetchAll(); // je met le result de ma query dans un double tableau
                $nombre_ligne = $res->rowCount(); // =2 car il y a 2 ligne dans ma base
                $liste = array();

                // print_r($double_tab_tuteur);
                // exit();


            }
            if (isset($_GET['id_suppr'])) {
                $id_suppression = $_GET['id_suppr'];
                $req_suppr = "DELETE FROM suivre where id_enfant='$id_suppression';DELETE FROM enfant where id_enfant='$id_suppression'";
                try {
                    $res = $linkpdo->query($req_suppr);
                    header('Location: page_admin.php');
                } catch (Exception $e) { // toujours faire un test de retour au cas ou ça crash
                    die('Erreur : ' . $e->getMessage());
                }
            }

                ?>



        <!--------------------------------------- menu information sur l'enfant (droite) -------------------------------------------->
        <nav class="right-contenu">
            <section>

                <!---- menu droit information sur l'enfant ---->

                <img class="logo-enfant" src="img/logo-enfant.png" alt="Tête de l'enfant">

                <div class="id-enfant"> <a class="id-nom">Nom : <?php echo $nom_enfant ?></a> </div>
                <div class="id-enfant"> <a class="id-prenom">Prénom : <?php echo $prenom_enfant ?></a> </div>
                <div class="id-enfant"> <a class="id-age">Date de Naissance : <?php echo $ddn_enfant ?> </a></div>


                <div class="id-enfants"> <a class="id-adresse">Adresse enfant :</a></div>
                <div class="id-enfants"> <a class="id-activite">Activité enfant :</a></div>
                <div class="id-enfants"> <a class="id-handicap">Handicap enfant :</a></div>

                <div class="text-complementaire"><textarea style="resize: none">Rajouter des informations supplémentaires sur l'enfant </textarea> </div>

                <div class="id-tuteur"> <a class="tuteur_4">Information tuteur 4:</a></div>
                <div class="id-tuteur"><p></p></div>
                <input class="button-modifie" type="submit" value="Bouton Modifier">
                <input class="button-valide" type="submit" value="Bouton cliquer">
                <div>
                    <button type="button" onclick="openDialog('dialog5', this)">Supprimer cet enfant</button>
                    <div id="dialog_layer" class="dialogs">
                        <div role="dialog" id="dialog5" aria-labelledby="dialog1_label" aria-modal="true" class="hidden">
                            <form action="" method="post" class="dialog_form">
                                <p> Attention vous enlever definitivement cet enfant du programme ! Êtes vous sur de votre choix ?</p>
                                <div class="dialog_form_actions">
                                    <?php echo '<a href="page_admin.php?id_suppr=' . $identifiant . '">Valider la supression</a>'; ?>

                                    <button type="button" onclick="closeDialog(this)">Annuler</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </section>




            <section class="nb-systeme">


            <a href="page_creatsystem.php">creer un nouveau systeme</a>

                <?php
            
            if (isset ($_GET['id_suppr']))  {
                $id_suppression = $_GET['id_suppr'];
                $req_suppr = "DELETE FROM enfant where id_enfant='$id_suppression'";
                try {
                    $res = $linkpdo->query($req_suppr);
                    header('Location: page_admin.php');
    
                }
                catch (Exception $e) { // toujours faire un test de retour au cas ou ça crash
                    die('Erreur : ' . $e->getMessage());
                }
            
            
            }

        ?>




            </section>





        </nav>
    </main>


    <!------------------------------------------------------- Footer -------------------------------------------------->
    <footer>

        <div class="f-contenu">

            <div class="bloc footer-contact">
                <h3>Nous contacter</h3>
                <p>123 rue du Chemin, Toulouse 31000 </p>
                <p>01 02 03 04 05</p>
            </div>

            <div class="bloc footer-information">
                <h3>A propos </h3>
                <ul class="liste-information">
                    <li><a  href="#">Entreprise</a></li>
                    <li><a  href="http://trisomie21-haute-garonne.org/">Association</a></li>
                    <li><a  href="#">Projet</a></li>
                </ul>
            </div>


            <div class="bloc footer-horaire">
                <h3>Les horaires</h3>
                <ul class="liste-horaire">
                    <li><p>✓ Lun 9h-19h</p></li>
                    <li><p>✓ Mar 9h-19h</p></li>
                    <li><p>✓ Mer 9h-19h</p></li>
                    <li><p>✓ Jeu 9h-19h</p></li>
                    <li><p>✓ Ven 9h-19h</p></li>
                </ul>
            </div>


           

        </div>
    </footer>
</body>



</html>