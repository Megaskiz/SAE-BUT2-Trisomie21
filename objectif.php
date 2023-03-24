<?php
require('fonctions.php');
is_logged();
is_validateur();
$linkpdo = connexionBd();
?>
<!DOCTYPE HTML>
<html lang="fr" style="font-family: Arial,sans-serif;">

<head>
    <meta charset="utf-8">
    <!-- importer le fichier de style -->
    <link rel="stylesheet" href="style_css/style_objectif.css" media="screen" type="text/css" />
    <link rel="stylesheet" href="style_css/stylesheet.css" type="text/css" charset="utf-8">
    <title>bienvenue</title>
    <div id="color-picker-container">
        <div id="color-bar"></div>
        <input type="color" id="color-picker">
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>
    <script type="text/javascript" src="objectif.js"></script>
    <script>
        // Récupérer la valeur stockée dans sessionStorage
        var bgColor = sessionStorage.getItem("bg-color");
        // Vérifier si la valeur existe
        if (bgColor) {
            // définir la couleur de fond de la page en utilisant la valeur stockée
            document.querySelector("body").style.backgroundColor = bgColor;
        }

        var colorPicker = document.getElementById("color-picker");

        colorPicker.addEventListener("change", function () {
            localStorage.setItem("bg-color", colorPicker.value);
        });

        ///L'animation peut être modifier///
        function startConfetti() {
            confetti({
                particleCount: 1000,
                spread: 280
            });
        }
        function confirmation() {
            if (confirm("Voulez-vous vraiment démarrer une nouvelle session?")) {
                window.location.href = "new_session.php?id=" + "<?php echo $_SESSION['id_sys']; ?>";
            } else {
                window.close();
            }
        }
    </script>
</head>
<div>
    <body style="background-color: <?php echo (isset($_SESSION['bg-color'])) ? $_SESSION['bg-color'] : '#afeeee'; ?>">
        <?php
        if (isset($_GET['id_sys'])) {
            sleep(1); // pour ne jamais avoir 2 jetons ajoutés dans la même seconde
            echo '
                <a href="index.php?id=' . $_SESSION['id_enfant'] . '"><button>retour au menu</button></a>
                <a href="page_recompense.php"><button class="droite">voir la/les récompenses</button></a>
                <a href="envoie_membre_message.php?id_objectif=' . $_GET['id_sys'] . '"><button class="message-objectif">messagerie<span class=" icon-mail">&#x2709;</span></button></a>
                <a href="#"><button class="droite" onclick="confirmation()">Démarrer une nouvelle session</button></a>
            ';

            

            $id = $_GET['id_sys'];
            $_SESSION['id_sys'] = $_GET['id_sys'];

            try {
                $res = $linkpdo->query("SELECT * FROM objectif where id_objectif=$id");
            } catch (Exception $e) { // toujours faire un test de retour en cas de crash
                die('Erreur : ' . $e->getMessage());
            }

            $double_tab = $res->fetchAll();
            $nombre_ligne = $res->rowCount();

            echo "<h1>" . htmlspecialchars($double_tab[0][1]) . "</h1>";

            switch ($double_tab[0][10]) { // switch pour faire un traitement different pour chaque type de système
                case '1': {
                    // sys chargement
    
                    /* vérification faites : 
                    y a-t'il déjà une session?
                    si non : 
                    proposer d'en faire une
                    afficher le système en non cliquable
                    
                    si oui : 
                    la dernière session est-elle échue ?
                    oui : 
                    proposer d'en faire une nouvelle
                    afficher le système en non cliquable
                    non :
                    affichier le système cliquable 
                    */
                    // si la session est obsolete (timer), je propose d'envoyer sur la page new_session , qui va remettre tout a 0, placer un nouveau jeton factice, mettre un nouveau timer
    
                    try {
                        // je recup la derniere session pour ce sys
                        $session_max_query = $linkpdo->query("SELECT max(id_session) from placer_jeton where id_objectif=" . $id);
                        //$session_max_query->debugDumpParams();
    
                    } catch (Exception $e) {
                        die('Erreur : ' . $e->getMessage());
                    }
                    $double_tab = $session_max_query->fetchAll();
                    $session_max = $double_tab[0][0];

                    if ($session_max == NULL) { //s'il n'y a jamais eu de jetons placé sur ce système/ jamais eu de session (toute première utilisation du système)
      
                        //mettre le bouton qui propose de créer une nouvelle session
                        echo "
                            <p>Aucune session n'existe pour ce système, voullez vous entamer votre première session</p> <br>
                            <a href=\"#\"><button class=\"droite\" onclick=\"confirmation()\">Démarrer une nouvelle session</button></a>
                        ";

                        // afficher le sys, en l'état mais sans bouton cliquable
                        
                        afficher_systeme("chargement", "non_valide", $linkpdo, $id);
                        exit;
                    }elseif(verifie_session_echue($session_max, $id, $linkpdo)){ // si la session est échue
                        echo "
                            <p>La session précédente est arrivée à son terme, voulez vous en démarrer une nouvelle?</p> <br>
                            <a href=\"#\"><button class=\"droite\" onclick=\"confirmation()\">Démarrer une nouvelle session</button></a>
                        ";

                        // afficher le sys, en l'état mais sans bouton cliquable
                        afficher_systeme("chargement", "non_valide", $linkpdo, $id);
                    }else{ // dans le cas où au moins une session existe et que la dernière n'est pas échue

                        afficher_systeme("chargement", "valide", $linkpdo, $id);
                    }
                
                    break;
                }

                case '3': {
                        // systeme routine 
        
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
                            //mettre le bouton qui propose de créer une nouvelle session
        
                            echo "
                                <p>Aucune session n'existe pour ce système, voullez vous entamer votre première session</p> <br>
                                <a href=\"#\"><button class=\"droite\" onclick=\"confirmation()\">Démarrer une nouvelle session</button></a>
                            ";

                            // afficher le sys, en l'état mais sans bouton cliquable
                            afficher_systeme("routine", "non_valide", $linkpdo, $id);
                            exit;
                        }elseif(verifie_session_echue($session_max, $id, $linkpdo)) { // si la session est échue
                            echo "
                                <p>La session précédente est arrivée à son terme, voulez vous en démarrer une nouvelle?</p> <br>
                                <a href=\"#\"><button class=\"droite\" onclick=\"confirmation()\">Démarrer une nouvelle session</button></a>
                            ";

                            // afficher le sys, en l'état mais sans bouton cliquable
                            afficher_systeme("routine", "non_valide", $linkpdo, $id);
                            exit;
                            
                        }else{ // sinon, si il y a au moins une session et qu'elle n'est pas échue
                            afficher_systeme("routine", "valide", $linkpdo, $id);
                            break;
                        }                        
                    }
                default:
                    echo "grosse erreur";
                    break;
            }
        }
        ?>
    </body>
</div>