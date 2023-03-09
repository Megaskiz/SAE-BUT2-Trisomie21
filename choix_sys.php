<?php
require('fonctions.php');
is_logged();
is_validateur();
?>
<!DOCTYPE HTML>
<html lang="fr" style="font-family: Arial,sans-serif;">

<head>
    <meta charset="utf-8">
    <!-- importer le fichier de style -->
    <link rel="stylesheet" href="style_css/style_choix_sys.css" media="screen" type="text/css" />
    <link rel="stylesheet" href="style_css/stylesheet.css" type="text/css" charset="utf-8">
    <title>bienvenue</title>
    <div id="color-picker-container">


        <div id="color-bar"> </div>

        <input type="color" id="color-picker">

    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>
    <script type="text/javascript" src="js/choix_sys.js"></script>
    <center>

<body style="background-color: <?php echo (isset($_SESSION['bg-color'])) ? $_SESSION['bg-color'] : '#afeeee'; ?>">



    <script>
        // Récupérer la valeur stockée dans sessionStorage
        var bgColor = sessionStorage.getItem("bg-color");
        // Vérifier si la valeur existe
        if (bgColor) {
            // définir la couleur de fond de la page en utilisant la valeur stockée
            document.querySelector("body").style.backgroundColor = bgColor;
        }
    </script>
    <script>
        var colorPicker = document.getElementById("color-picker");
        colorPicker.addEventListener("change", function() {
            localStorage.setItem("bg-color", colorPicker.value);
        });
    </script>



    </div>
    <script>
        ///L'animation peut être modifier///
        function startConfetti() {
            confetti({
                particleCount: 100,
                spread: 360
            });
        }
    </script>
    </head>

    <body>


        <?php
        sleep(1);

        echo '
            <a href="page_admin.php?id=' . $_SESSION['id_enfant'] . '"><button> Retour </button></a>

            <a href="page_recompense.php"><button class="droite"> Voir la/les récompenses</button></a>
            <a href="envoie_membre_message.php?id_objectif=' . $_GET['id_sys'] . '"><button class="message-objectif"> Messagerie &#128172; </button></a>';

        ///Connexion au serveur MySQL
        try {
            $linkpdo = new PDO("mysql:host=localhost;dbname=bddsae", "root", "");
        }
        ///Capture des erreurs éventuelles
        catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }



        if (isset($_GET['id_sys'])) {
            $id = $_GET['id_sys'];
            $_SESSION['id_sys'] = $_GET['id_sys'];
        ?>
            <script>
                function confirmation() {
                    if (confirm("Voulez-vous vraiment démarrer une nouvelle session ?")) {
                        window.location.href = "new_session.php?id=" + "<?php echo $_SESSION['id_sys']; ?>";
                    } else {
                        window.close();
                    }
                }
            </script>


            <?php
            try {
                $res = $linkpdo->query("SELECT * FROM objectif where id_objectif=$id");
            } catch (Exception $e) { // toujours faire un test de retour en cas de crash
                die('Erreur : ' . $e->getMessage());
            }

            ///Affichage des entrées du résultat une à une

            $double_tab = $res->fetchAll(); // je met le result de ma query dans un double tableau
            $nombre_ligne = $res->rowCount();
            $liste = array();

            $titre_systeme = $double_tab[0][1];

            ///Fermeture du curseur d'analyse des résultats
            $res->closeCursor();


            echo "<h1>" . htmlspecialchars($titre_systeme) . "</h1>";



            switch ($double_tab[0][10]) {
                case '1': {

                        // sys chargement

                        // si la session est obsolete (timer), je propose d'envoyer sur la page new_session , qui va remettre tout a 0, placer un nouveau jeton factice, mettre un nouveau timer


                        //**************** */
                        try {
                            // je recup la derniere session pour ce sys
                            $session_max_query = $linkpdo->query("SELECT max(id_session) from placer_jeton where id_objectif=" . $id);
                            //$session_max_query->debugDumpParams();

                        } catch (Exception $e) { // toujours faire un test de retour en cas de crash
                            die('Erreur : ' . $e->getMessage());
                        }

                        ///Affichage des entrées du résultat une à une

                        $double_tab = $session_max_query->fetchAll(); // je met le result de ma query dans un double tableau
                        $session_max = $double_tab[0][0];

                        if ($session_max == NULL) { // vérification du timer

                            //echo"date du premier jeton de la derniere session + durée du sys : <br> ".$secondes_premier_jeton+$duree_sys_en_seconde."<br>".time();

                            //mettre le bouton qui propose de créer une nouvelle session



            ?>
                            <p>Aucune session n'existe pour ce système, voullez vous entamer votre première session</p> <br>
                            <script>
                                function confirmation() {
                                    if (confirm("Voulez-vous vraiment démarrer une nouvelle session?")) {
                                        window.location.href = "new_session.php?id=" + "<?php echo $_SESSION['id_sys']; ?>";
                                    } else {
                                        window.close();
                                    }
                                }
                            </script>

                            <a href="#"><button class="droite" onclick="confirmation()">Démarrer une nouvelle session</button></a>
                            <?php

                            // afficher le sys, en l'état mais sans bouton cliquable

                            try {
                                $res = $linkpdo->query("SELECT nom FROM objectif where id_objectif=$id"); // le nom est la chaine que j'utilise pour construire le système
                                $res2 = $linkpdo->query("SELECT lien_jeton FROM enfant where id_enfant=" . $_SESSION['id_enfant'] . "");
                            } catch (Exception $e) { // toujours faire un test de retour au cas ou ça crash
                                die('Erreur : ' . $e->getMessage());
                            }


                            ///Affichage des entrées du résultat une à une

                            $double_tab = $res->fetchAll();
                            $talbeau_jeton = $res2->fetchAll();

                            $lien_jeton = $talbeau_jeton[0][0];

                            $chaine = $double_tab[0][0];



                            echo "<div class=\"sys\">";
                            echo "<table>";


                            $morceau = explode(":", $chaine);


                            array_pop($morceau); // je retire la partie apres le dernier ":" 
                            $compteur = 0;

                            foreach ($morceau as $ligne) {


                                $element = explode("_", $ligne);
                                $tache = $element[0];
                                $jetons = $element[1];
                                $tab_jeton = str_split($jetons);
                                echo "<tr>";
                                echo "<td class='struct'>";

                                echo "<p>" . htmlspecialchars($tache) . "</p>";
                                echo "</td>";

                                //ajout des cases de jetons
                                foreach ($tab_jeton as $case_tab) {
                                    if ($case_tab == 0) {
                                        echo "<td class='case_jeton' id=$compteur >";
                                        echo '<a style="display: block;width: 5rem;height: 5rem;"></a>';
                                        echo "</td>";
                                    } else {

                                        echo "<td class='case_jeton' id=$compteur>";
                                        echo "<center>";
                                        echo "<img class=\"jeton\" src=$lien_jeton alt=$lien_jeton>";
                                        echo "</center>";
                                        echo "</td>";
                                    }


                                    $compteur += 1;
                                }

                                echo "</tr>";
                            }
                            echo "</table>";

                            exit();
                        }

                        //echo$session_max;

                        try {
                            //je recupere la date du premier jeton placé pour cette session dans ce sys (jeton factice, témoin du début de la session)
                            $jeton_premier_query = $linkpdo->query("SELECT min(date_heure) from placer_jeton where id_session=" . $session_max . " and id_objectif=" . $id);

                            // je recupere la duree totale prevu du sys 
                            $duree_sys_query = $linkpdo->query("SELECT duree from OBJECTIF where id_objectif=" . $id);
                        } catch (Exception $e) { // toujours faire un test de retour en cas de crash
                            die('Erreur : ' . $e->getMessage());
                        }

                        ///Affichage des entrées du résultat une à une

                        $double_tab = $jeton_premier_query->fetchAll();
                        $double_tab2 = $duree_sys_query->fetchAll();

                        $jeton_premier =  $double_tab[0][0];
                        $duree_sys =  $double_tab2[0][0];

                        $duree_sys_en_seconde = $duree_sys * 3600;




                        $secondes_premier_jeton = strtotime($jeton_premier);

                        // echo$session_max;
                        // exit();

                        if ($secondes_premier_jeton + $duree_sys_en_seconde < time()) { // vérification du timer

                            //echo"date du premier jeton de la derniere session + durée du sys : <br> ".$secondes_premier_jeton+$duree_sys_en_seconde."<br>".time();

                            //mettre le bouton qui propose de créer une nouvelle session


                            ?>
                            <p>La session précédente est arrivée à son terme, voulez vous en démarrer une nouvelle?</p> <br>
                            <script>
                                function confirmation() {
                                    if (confirm("Voulez-vous vraiment démarrer une nouvelle session?")) {
                                        window.location.href = "new_session.php?id=" + "<?php echo $_SESSION['id_sys']; ?>";
                                    } else {
                                        window.close();
                                    }
                                }
                            </script>

                            <a href="#"><button class="droite" onclick="confirmation()">Démarrer une nouvelle session</button></a>
                            <?php



                            // afficher le sys, en l'état mais sans bouton cliquable

                            try {
                                $res = $linkpdo->query("SELECT nom FROM objectif where id_objectif=$id"); // le nom est la chaine que j'utilise pour construire le système
                                $res2 = $linkpdo->query("SELECT lien_jeton FROM enfant where id_enfant=" . $_SESSION['id_enfant'] . "");
                            } catch (Exception $e) { // toujours faire un test de retour au cas ou ça crash
                                die('Erreur : ' . $e->getMessage());
                            }


                            ///Affichage des entrées du résultat une à une

                            $double_tab = $res->fetchAll();
                            $talbeau_jeton = $res2->fetchAll();

                            $lien_jeton = $talbeau_jeton[0][0];

                            $chaine = $double_tab[0][0];


                            // TESTER SI IL Y A DES 0 DANS LA CHAINE, SI NON, çA VEUT DIRE QUE LE SYSTEME EST FINI
                            if (strpos($chaine, '0') == false) {
                                $feux = true;
                                echo "<h1><a href=\"page_recompense.php?id_sys=" . $_GET['id_sys'] . "&feux=" . $feux . " \">BRAVO CE SYSTEME EST COMPLET, TU PEUX CHOISIR UNE RECOMPENSE !</h1>";
                                echo "<script> startConfetti() </script>";
                                echo "</a>";
                            }


                            echo "<div class=\"sys\">";
                            echo "<table>";


                            $morceau = explode(":", $chaine);


                            array_pop($morceau); // je retire la partie apres le dernier ":" 
                            $compteur = 0;

                            foreach ($morceau as $ligne) {


                                $element = explode("_", $ligne);
                                $tache = $element[0];
                                $jetons = $element[1];
                                $tab_jeton = str_split($jetons);
                                echo "<tr>";
                                echo "<td class='struct'>";

                                echo "<p>" . htmlspecialchars($tache) . "</p>";
                                echo "</td>";

                                //ajout des cases de jetons
                                foreach ($tab_jeton as $case_tab) {
                                    if ($case_tab == 0) {
                                        echo "<td class='case_jeton' id=$compteur >";
                                        echo '<a style="display: block;width: 5rem;height: 5rem;"></a>';
                                        echo "</td>";
                                    } else {

                                        echo "<td class='case_jeton' id=$compteur>";
                                        echo "<center>";
                                        echo "<img class=\"jeton\" src=$lien_jeton alt=$lien_jeton>";
                                        echo "</center>";
                                        echo "</td>";
                                    }


                                    $compteur += 1;
                                }

                                echo "</tr>";
                            }
                            echo "</table>";

                            exit();
                        }


                        //**************** */








                        //



                        try {
                            $res = $linkpdo->query("SELECT nom FROM objectif where id_objectif=$id"); // le nom est la chaine que j'utilise pour construire le système
                            $res2 = $linkpdo->query("SELECT lien_jeton FROM enfant where id_enfant=" . $_SESSION['id_enfant'] . "");
                        } catch (Exception $e) { // toujours faire un test de retour au cas ou ça crash
                            die('Erreur : ' . $e->getMessage());
                        }


                        ///Affichage des entrées du résultat une à une

                        $double_tab = $res->fetchAll();
                        $talbeau_jeton = $res2->fetchAll();

                        $lien_jeton = $talbeau_jeton[0][0];

                        // var_dump($double_tab);
                        // exit();
                        $valeur = $double_tab[0];
                        //var_dump( $valeur);
                        //echo$_POST['chaine'];

                        $chaine = $valeur[0];


                        // TESTER SI IL Y A DES 0 DANS LA CHAINE, SI NON, çA VEUT DIRE QUE LE SYSTEME EST FINI ICI
                        if (strpos($chaine, '0') == false) {
                            $feux = true;
                            echo "<h1><a href=\"page_recompense.php?id_sys=" . $_GET['id_sys'] . "&feux=" . $feux . " \">BRAVO CE SYSTEME EST COMPLET, TU PEUX CHOISIR UNE RECOMPENSE !</h1>";
                            echo "<script> startConfetti() </script>";
                            echo "</a>";
                        }


                        echo "<div class=\"sys\">";
                        echo "<table>";

                        /* 
                   il faut maintenant 'déconstruire' la chaine pour pouvoir faire un tableau
                       début :
                       manger_1_0000000: dormir_2_0000000:
               */

                        $morceau = explode(":", $chaine);


                        array_pop($morceau); // je retire la partie apres le dernier ":" 
                        $compteur = 0;

                        foreach ($morceau as $ligne) {


                            $element = explode("_", $ligne);
                            $tache = $element[0];
                            $jetons = $element[1];
                            $tab_jeton = str_split($jetons);
                            echo "<tr>";
                            echo "<td class='struct'>";

                            echo "<p>" . htmlspecialchars($tache) . "</p>";
                            echo "</td>";

                            //ajout des cases de jetons
                            foreach ($tab_jeton as $case_tab) {
                                if ($case_tab == 0) {
                                    echo "<td class='case_jeton' id=$compteur >";
                                    echo '<a href="choix_sys_ajout.php?id=' . $id . '&amp;case=' . $compteur . '&amp;chaine=' . $chaine . '" onclick="setTimeout(startConfetti,500);" style="display: block;width: 5rem;height: 5rem;"></a>';
                                    echo "</td>";
                                } else {

                                    echo "<td class='case_jeton' id=$compteur>";
                                    echo "<center>";
                                    echo '<a href="choix_sys_remove.php?id=' . $id . '&amp;case=' . $compteur . '&amp;chaine=' . $chaine . '" style="display: block;width: 5rem;height: 5rem;"><img class=\"jeton\" src=' . $lien_jeton . ' alt=' . $lien_jeton . '></a>';
                                    echo "</center>";
                                    echo "</td>";
                                }


                                $compteur += 1;
                            }

                            echo "</tr>";
                        }
                        echo "</table>";

                        break;
                    }
                case '2': {
                    }
                    echo "sys de type 2";
                    break;

                case '3': {
                        // systeme routine 

                        //**************** */
                        try {
                            // je recup la derniere session pour ce sys
                            $session_max_query = $linkpdo->query("SELECT max(id_session) from placer_jeton where id_objectif=" . $id);
                            //$session_max_query->debugDumpParams();

                        } catch (Exception $e) { // toujours faire un test de retour en cas de crash
                            die('Erreur : ' . $e->getMessage());
                        }

                        ///Affichage des entrées du résultat une à une

                        $double_tab = $session_max_query->fetchAll(); // je met le result de ma query dans un double tableau
                        $session_max = $double_tab[0][0];

                        if ($session_max == NULL) {



                            ?>
                            <br>
                            <p>Aucune session n'existe pour ce système, voulez vous entamer votre première session</p> <br>
                            <script>
                                function confirmation() {
                                    if (confirm("Voulez-vous vraiment démarrer une nouvelle session?")) {
                                        window.location.href = "new_session.php?id=" + "<?php echo $_SESSION['id_sys']; ?>";
                                    } else {
                                        window.close();
                                    }
                                }
                            </script>

                            <a href="#"><button class="droite" onclick="confirmation()">Démarrer une nouvelle session</button></a>
                            <?php

                            // afficher le sys, en l'état mais sans bouton cliquable

                            try {
                                $res = $linkpdo->query("SELECT nom FROM objectif where id_objectif=$id"); // le nom est la chaine que j'utilise pour construire le système
                                $res2 = $linkpdo->query("SELECT lien_jeton FROM enfant where id_enfant=" . $_SESSION['id_enfant'] . "");
                            } catch (Exception $e) { // toujours faire un test de retour au cas ou ça crash
                                die('Erreur : ' . $e->getMessage());
                            }

                            ///Affichage des entrées du résultat une à une

                            $double_tab = $res->fetchAll();
                            $talbeau_jeton = $res2->fetchAll();

                            $lien_jeton = $talbeau_jeton[0][0];

                            // var_dump($double_tab);
                            // exit();
                            $valeur = $double_tab[0];
                            //var_dump( $valeur);
                            //echo$_POST['chaine'];

                            $chaine = $valeur[0];

                            // TESTER SI IL Y A DES 0 DANS LA CHAINE, SI NON, çA VEUT DIRE QUE LE SYSTEME EST FINI
                            if (strpos($chaine, '0') == false) {
                                $feux = true;
                                echo "<h1><a href=\"page_recompense.php?id_sys=" . $_GET['id_sys'] . "&feux=" . $feux . " \">BRAVO CE SYSTEME EST COMPLET, TU PEUX CHOISIR UNE RECOMPENSE !</h1>";
                                echo "<script> startConfetti() </script>";
                                echo "</a>";
                            }


                            echo "<div class=\"sys\">";
                            echo "<table>";

                            echo "<tr>";
                            echo "<td class=\"struct\">";
                            echo "<p></p>";
                            echo "</td>";
                            echo "<td class=\"struct\">";
                            echo "<p>Lundi</p>";
                            echo "</td>";
                            echo "<td class=\"struct\">";
                            echo "<p>Mardi</p>";
                            echo "</td>";
                            echo "<td class=\"struct\">";
                            echo "<p>Mercredi</p>";
                            echo "</td>";
                            echo "<td class=\"struct\">";
                            echo "<p>Jeudi</p>";
                            echo "</td>";
                            echo "<td class=\"struct\">";
                            echo "<p>Vendredi</p>";
                            echo "</td>";
                            echo "<td class=\"struct\">";
                            echo "<p>Samedi</p>";
                            echo "</td>";
                            echo "<td class=\"struct\">";
                            echo "<p>Dimanche</p>";
                            echo "</td>";
                            echo "</tr>";

                            /* 
       il faut maintenant 'déconstruire' la chaine pour pouvoir faire un tableau
           début :
           manger_1_0000000: dormir_2_0000000:
   */

                            $morceau = explode(":", $chaine);

                            array_pop($morceau); // je retire la partie apres le dernier ":" 
                            $compteur = 0;
                            foreach ($morceau as $ligne) {


                                $element = explode("_", $ligne);
                                $tache = $element[0];
                                $jetons = $element[1];
                                $tab_jeton = str_split($jetons);
                                echo "<tr>";
                                echo "<td class='struct'>";

                                echo "<p>" . htmlspecialchars($tache) . "</p>";
                                echo "</td>";

                                //ajout des cases de jetons
                                foreach ($tab_jeton as $case_tab) {
                                    if ($case_tab == 0) {
                                        echo "<td id=$compteur >";
                                        echo '<a style="display: block;width: 5rem;height: 5rem;"></a>';
                                        echo "</td>";
                                    } else {
                                        echo "<td id=$compteur>";
                                        echo "<center>";
                                        echo "<img class=\"jeton\" src=$lien_jeton alt=$lien_jeton>";
                                        echo "</center>";

                                        echo "</td>";
                                    }


                                    $compteur += 1;
                                }

                                echo "</tr>";
                            }
                            echo "</table>";

                            exit();
                        }

                        try {
                            //je recupere la date du premier jeton placé pour cette session dans ce sys
                            $jeton_premier_query = $linkpdo->query("SELECT min(date_heure) from placer_jeton where id_session=" . $session_max . " and id_objectif=" . $id);
                            // je recupere la duree totale du sys prevu
                            $duree_sys_query = $linkpdo->query("SELECT duree from OBJECTIF where id_objectif=" . $id);
                        } catch (Exception $e) { // toujours faire un test de retour en cas de crash
                            die('Erreur : ' . $e->getMessage());
                        }

                        ///Affichage des entrées du résultat une à une

                        $double_tab = $jeton_premier_query->fetchAll();
                        $double_tab2 = $duree_sys_query->fetchAll();

                        $jeton_premier =  $double_tab[0][0];
                        $duree_sys =  $double_tab2[0][0];

                        $duree_sys_en_seconde = $duree_sys * 3600;

                        $secondes_premier_jeton = strtotime($jeton_premier);

                        if ($secondes_premier_jeton + $duree_sys_en_seconde < time()) {
                            ?>
                            <p>La session précédente est arrivée à son terme, voulez vous en démarrer une nouvelle?</p> <br>
                            <script>
                                function confirmation() {
                                    if (confirm("Voulez-vous vraiment démarrer une nouvelle session?")) {
                                        window.location.href = "new_session.php?id=" + "<?php echo $_SESSION['id_sys']; ?>";
                                    } else {
                                        window.close();
                                    }
                                }
                            </script>

                            <a href="#"><button class="droite" onclick="confirmation()">Démarrer une nouvelle session</button></a>
                            <?php







                            //***************************** */

                            try {
                                $res = $linkpdo->query("SELECT nom FROM objectif where id_objectif=$id"); // le nom est la chaine que j'utilise pour construire le système
                                $res2 = $linkpdo->query("SELECT lien_jeton FROM enfant where id_enfant=" . $_SESSION['id_enfant'] . "");
                            } catch (Exception $e) { // toujours faire un test de retour au cas ou ça crash
                                die('Erreur : ' . $e->getMessage());
                            }

                            ///Affichage des entrées du résultat une à une

                            $double_tab = $res->fetchAll();
                            $talbeau_jeton = $res2->fetchAll();

                            $lien_jeton = $talbeau_jeton[0][0];

                            // var_dump($double_tab);
                            // exit();
                            $valeur = $double_tab[0];
                            //var_dump( $valeur);
                            //echo$_POST['chaine'];

                            $chaine = $valeur[0];

                            // TESTER SI IL Y A DES 0 DANS LA CHAINE, SI NON, çA VEUT DIRE QUE LE SYSTEME EST FINI
                            if (strpos($chaine, '0') == false) {
                                $feux = true;
                                echo "<h1><a href=\"page_recompense.php?id_sys=" . $_GET['id_sys'] . "&feux=" . $feux . " \">BRAVO CE SYSTEME EST COMPLET, TU PEUX CHOISIR UNE RECOMPENSE !</h1>";
                                echo "<script> startConfetti() </script>";
                                echo "</a>";
                            }


                            echo "<div class=\"sys\">";
                            echo "<table>";

                            echo "<tr>";
                            echo "<td class=\"struct\">";
                            echo "<p></p>";
                            echo "</td>";
                            echo "<td class=\"struct\">";
                            echo "<p>Lundi</p>";
                            echo "</td>";
                            echo "<td class=\"struct\">";
                            echo "<p>Mardi</p>";
                            echo "</td>";
                            echo "<td class=\"struct\">";
                            echo "<p>Mercredi</p>";
                            echo "</td>";
                            echo "<td class=\"struct\">";
                            echo "<p>Jeudi</p>";
                            echo "</td>";
                            echo "<td class=\"struct\">";
                            echo "<p>Vendredi</p>";
                            echo "</td>";
                            echo "<td class=\"struct\">";
                            echo "<p>Samedi</p>";
                            echo "</td>";
                            echo "<td class=\"struct\">";
                            echo "<p>Dimanche</p>";
                            echo "</td>";
                            echo "</tr>";

                            /* 
           il faut maintenant 'déconstruire' la chaine pour pouvoir faire un tableau
               début :
               manger_1_0000000: dormir_2_0000000:
       */

                            $morceau = explode(":", $chaine);

                            array_pop($morceau); // je retire la partie apres le dernier ":" 
                            $compteur = 0;
                            foreach ($morceau as $ligne) {


                                $element = explode("_", $ligne);
                                $tache = $element[0];
                                $jetons = $element[1];
                                $tab_jeton = str_split($jetons);
                                echo "<tr>";
                                echo "<td class='struct'>";

                                echo "<p>" . htmlspecialchars($tache) . "</p>";
                                echo "</td>";

                                //ajout des cases de jetons
                                foreach ($tab_jeton as $case_tab) {
                                    if ($case_tab == 0) {
                                        echo "<td id=$compteur >";
                                        echo '<a style="display: block;width: 5rem;height: 5rem;"></a>';
                                        echo "</td>";
                                    } else {
                                        echo "<td id=$compteur>";
                                        echo "<center>";
                                        echo "<img class=\"jeton\" src=$lien_jeton alt=$lien_jeton>";
                                        echo "</center>";

                                        echo "</td>";
                                    }


                                    $compteur += 1;
                                }

                                echo "</tr>";
                            }
                            echo "</table>";

                            exit();




                            //***************************** */

                        } //coucou


                        //**************** */




                        try {
                            $res = $linkpdo->query("SELECT nom FROM objectif where id_objectif=$id"); // le nom est la chaine que j'utilise pour construire le système
                            $res2 = $linkpdo->query("SELECT lien_jeton FROM enfant where id_enfant=" . $_SESSION['id_enfant'] . "");
                        } catch (Exception $e) { // toujours faire un test de retour au cas ou ça crash
                            die('Erreur : ' . $e->getMessage());
                        }

                        ///Affichage des entrées du résultat une à une

                        $double_tab = $res->fetchAll();
                        $talbeau_jeton = $res2->fetchAll();

                        $lien_jeton = $talbeau_jeton[0][0];

                        // var_dump($double_tab);
                        // exit();
                        $valeur = $double_tab[0];
                        //var_dump( $valeur);
                        //echo$_POST['chaine'];

                        $chaine = $valeur[0];

                        // TESTER SI IL Y A DES 0 DANS LA CHAINE, SI NON, çA VEUT DIRE QUE LE SYSTEME EST FINI
                        if (strpos($chaine, '0') == false) {
                            $feux = true;
                            echo "<h1><a href=\"page_recompense.php?id_sys=" . $_GET['id_sys'] . "&feux=" . $feux . " \">BRAVO CE SYSTEME EST COMPLET, TU PEUX CHOISIR UNE RECOMPENSE !</h1>";
                            echo "<script> startConfetti() </script>";
                            echo "</a>";
                        }

                        echo "<div class=\"sys\">";
                        echo "<table>";

                        echo "<tr>";
                        echo "<td class=\"struct\">";
                        echo "<p></p>";
                        echo "</td>";
                        echo "<td class=\"struct\">";
                        echo "<p>Lundi</p>";
                        echo "</td>";
                        echo "<td class=\"struct\">";
                        echo "<p>Mardi</p>";
                        echo "</td>";
                        echo "<td class=\"struct\">";
                        echo "<p>Mercredi</p>";
                        echo "</td>";
                        echo "<td class=\"struct\">";
                        echo "<p>Jeudi</p>";
                        echo "</td>";
                        echo "<td class=\"struct\">";
                        echo "<p>Vendredi</p>";
                        echo "</td>";
                        echo "<td class=\"struct\">";
                        echo "<p>Samedi</p>";
                        echo "</td>";
                        echo "<td class=\"struct\">";
                        echo "<p>Dimanche</p>";
                        echo "</td>";
                        echo "</tr>";

                        /* 
                   il faut maintenant 'déconstruire' la chaine pour pouvoir faire un tableau
                       début :
                       manger_1_0000000: dormir_2_0000000:
               */

                        $morceau = explode(":", $chaine);

                        array_pop($morceau); // je retire la partie apres le dernier ":" 
                        $compteur = 0;
                        foreach ($morceau as $ligne) {


                            $element = explode("_", $ligne);
                            $tache = $element[0];
                            $jetons = $element[1];
                            $tab_jeton = str_split($jetons);
                            echo "<tr>";
                            echo "<td class='struct'>";

                            echo "<p>" . htmlspecialchars($tache) . "</p>";
                            echo "</td>";

                            //ajout des cases de jetons
                            foreach ($tab_jeton as $case_tab) {
                                if ($case_tab == 0) {
                                    echo "<td id=$compteur >";
                                    echo '<a href="choix_sys_ajout.php?id=' . $id . '&amp;case=' . $compteur . '&amp;chaine=' . $chaine . '" onclick="setTimeout(startConfetti,500);" style="display: block;width: 5rem;height: 5rem;"></a>';
                                    echo "</td>";
                                } else {
                                    echo "<td id=$compteur>";
                                    echo "<center>";
                                    echo "<img class=\"jeton\" src=$lien_jeton alt=$lien_jeton>";
                                    echo "</center>";

                                    echo "</td>";
                                }


                                $compteur += 1;
                            ?>
                                <script>
                                    document.addEventListener("DOMContentLoaded", function() {
                                        var header = document.getElementById("confetti-header");
                                        if (header) {
                                            setTimeout(startConfetti, 1000);
                                        }
                                    });
                                </script>

        <?php
                            }

                            echo "</tr>";
                        }
                        echo "</table>";

                        break;
                    }
                default:
                    echo "grosse erreur";
                    break;
            }
        }






        ?>
        </div>

    </body>
    </center>