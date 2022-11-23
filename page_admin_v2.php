<!DOCTYPE html>
<html lang="fr">

<?php
    session_start();
        ///Connexion au serveur MySQL
    try {
    $linkpdo = new PDO("mysql:host=localhost;dbname=sae", "root", "");
    }
    ///Capture des erreurs éventuelles
    catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
    }

?>

<head>
    <meta charset="utf-8">
    <title> Administrateur </title>
    <link rel="stylesheet" href="style_admin-v2.css">
    <link rel="icon" href="logo/icon-admin.png">
    <script type = "text/javascript" src="script.js"></script>
</head>

<body>
    <header>
        <img class="grid_item" src="/sae/img/logo trisomie.png" alt="logo de l'association">
        <p class="grid_item" id="logo_personne">logo personne</p>
        
       <?php
       $mail =  $_SESSION['login_user'];
        try {
            $res = $linkpdo->query("SELECT nom, prenom FROM membre where courriel='$mail'");
            }
            catch (Exception $e) { // toujours faire un test de retour au cas ou ça crash
                die('Erreur : ' . $e->getMessage());
            }
    
            ///Affichage des entrées du résultat une à une
            
            $double_tab = $res -> fetchAll(); // je met le result de ma query dans un double tableau
            $nombre_ligne = $res -> rowCount(); // =2 car il y a 2 ligne dans ma base
            $liste = array();
            echo"<table>";
            
            for ($i=0; $i < $nombre_ligne; $i++) { 
                echo"<tr>";
                    for ($y=0; $y < 2; $y++) { 
                echo"<td>";
                    print_r($double_tab[$i][$y]);
                    $liste[$y] = $double_tab[$i][$y];
                echo"</td>";
               
                
                }         
                
            echo"</tr>";
        
            }
            
            echo"</table>";
        ?>
        
        <p class="grid_item"><a href="html_login.php">Déconnexion</a></p>
    </header>
    <main>
        <nav><! -- /* Le bloc suivant est la fenêtre pop-in de l'ajout d'enfant, elle est caché tant qu'on appuie pas sur le bouton "ajouter enfant" */ -- >  
            <div>                        
                <button type="button" onclick="openDialog('dialog1', this)">Ajouter un enfant</button>
                <div id="dialog_layer" class="dialogs">
                    <div role="dialog" id="dialog1" aria-labelledby="dialog1_label" aria-modal="true" class="hidden">
                        <h2 id="dialog1_label" class="dialog_label">Ajouter un enfant</h2>
                        <form action="insert_enfant.php" method="post" class="dialog_form">
                            <div class="dialog_form_item">
                                <label>
                                <span class="label_text">nom :</span>
                                <input name="nom"type="text" required="required">
                                </label>
                            </div>
                            <div class="dialog_form_item">
                                <label>
                                <span class="label_text">prenom:</span>
                                <input name="prenom" type="text" class="city_input"required="required">
                                </label>
                            </div>
                            <div class="dialog_form_item">
                                <label>
                                <span class="label_text">date de naissance:</span>
                                <input name="date_naissance" type="date" class="state_input"required="required">
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
            </div><! -- /* fin de la fenêtre popin de l'ajout d'enfant" */ -- >  
        

            <?php
                    
                    
                ///Sélection de tout le contenu de la table enfant
                try {
                    $res = $linkpdo->query('SELECT id_enfant, nom, prenom FROM enfant');
                    }
                    catch (Exception $e) { // toujours faire un test de retour en cas de crash
                        die('Erreur : ' . $e->getMessage());
                    }
            
                    ///Affichage des entrées du résultat une à une
                    
                    $double_tab = $res -> fetchAll(); // je met le result de ma query dans un double tableau
                    $nombre_ligne = $res -> rowCount();
                    $liste = array();
                    echo"<table>";
                    
                    for ($i=0; $i < $nombre_ligne; $i++) { 
                        echo"<tr>";
                            for ($y=1; $y < 3; $y++) { 
                        echo"<td>";
                            print_r($double_tab[$i][$y]);
                            $liste[$y] = $double_tab[$i][$y];
                        echo"</td>";
                    
                        
                        }
                        $identifiant = $double_tab[$i][0];
                        echo"<td>";
                            echo '<a href="page_admin_v2.php?id='.$identifiant.'">acceder</a>';
                        echo"</td>";
                    echo"</tr>";
                
                    }
                    
                    echo"</table>";
            
                    ///Fermeture du curseur d'analyse des résultats
                    $res->closeCursor();
            
            ?>
            

        </nav>
        <div> 
        <?php // affichage central de la page, avec les informations sur les enfants

            if (isset ($_GET['id']))  {
                $id = $_GET['id'];

                echo"infos sur l'enfant :";

                        ///Sélection de tout le contenu de la table carnet_adresse
                try {
                    $res = $linkpdo->query("SELECT * FROM enfant where id_enfant='$id'");
    
                    }
                    catch (Exception $e) { // toujours faire un test de retour au cas ou ça crash
                        die('Erreur : ' . $e->getMessage());
                    }
                    
                    setcookie("SelectEnfant", $id);
                
                    ///Affichage des entrées du résultat une à une
                    
                    $double_tab = $res -> fetchAll(); // je met le result de ma query dans un double tableau
                    $nombre_ligne = $res -> rowCount(); // =2 car il y a 2 ligne dans ma base
                    $liste = array();
                    
                    echo"<table>";
                    
                    for ($i=0; $i < $nombre_ligne; $i++) { 
                        echo"<tr>";
                            for ($y=0; $y < 5; $y++) { 
                        echo"<td>";
                            print_r($double_tab[$i][$y]);
                            $liste[$y] = $double_tab[$i][$y];
                        echo"</td>";
                    
                        
                            }
                        echo"</tr>";
                
                    }
                    echo"</table>";

                    echo"infos sur ses tuteurs :";

                try {
                    $res = $linkpdo->query("SELECT * FROM suivre natural join membre  where id_enfant='$id'");
    
                    }
                    catch (Exception $e) { // toujours faire un test de retour au cas ou ça crash
                        die('Erreur : ' . $e->getMessage());
                    }
                    
                
                    ///Affichage des entrées du résultat une à une
                    
                    $double_tab = $res -> fetchAll(); // je met le result de ma query dans un double tableau
                    $nombre_ligne = $res -> rowCount(); // =2 car il y a 2 ligne dans ma base
                    $liste = array();
                    
                    echo"<table>";
                    
                    for ($i=0; $i < $nombre_ligne; $i++) { 
                        echo"<tr>";
                            for ($y=0; $y < 14; $y++) { 
                        echo"<td>";
                            print_r($double_tab[$i][$y]);
                            $liste[$y] = $double_tab[$i][$y];
                        echo"</td>";
                    
                        
                            }
                        echo"</tr>";
                
                    }
                    echo"</table>";

                //echo"Supprimer cet enfant de la base de donnée (attention cette action est irreversible !)";
            ?>
            <div>                        
                <button type="button" onclick="openDialog('dialog5', this)">Supprimer cet enfant</button>
                <div id="dialog_layer" class="dialogs">
                    <div role="dialog" id="dialog5" aria-labelledby="dialog1_label" aria-modal="true" class="hidden">
                        <form action="" method="post" class="dialog_form">
                            <p>Supprimer cet enfant de la base de donnée ?  (attention cette action est irreversible !)</p>
                            <div class="dialog_form_actions">
                                <?php echo '<a href="page_admin_v2.php?id_suppr='.$identifiant.'">Valider la supression</a>'; ?>
                                <button type="button" onclick="closeDialog(this)">Annuler</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div><! -- /* fin de la fenêtre popin de l'ajout d'enfant" */ -- >
            <a href="page_creatsystem.php">creer un nouveau systeme</a>

                <?php
            }
            if (isset ($_GET['id_suppr']))  {
                $id_suppression = $_GET['id_suppr'];
                $req_suppr = "DELETE FROM enfant where id_enfant='$id_suppression'";
                try {
                    $res = $linkpdo->query($req_suppr);
                    header('Location: page_admin_v2.php');
    
                }
                catch (Exception $e) { // toujours faire un test de retour au cas ou ça crash
                    die('Erreur : ' . $e->getMessage());
                }
            
            
            }

        ?>
        

        </div>
    </main>
    <footer>
        <p>nos contact et autres mentions légales</p>
    </footer>
    </html>