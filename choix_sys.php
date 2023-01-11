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
        <link rel="stylesheet" href="style_choix_sys.css" media="screen" type="text/css" />
        <link rel="stylesheet" href="stylesheet.css" type="text/css" charset="utf-8">
        <title>bienvenue</title>
    </head>
<body>


<?php
    echo'<button>
            <a href="page_admin.php?id='.$_SESSION['id_enfant'].'">retour au menu</a>
        </button>';

        echo'<button class="droite">
                <a href="page_recompense.php">voir la/les récompenses</a>
            </button>';
    
    #affiche message
    echo '<a href="envoie_membre_message.php?id_objectif=' .$_GET['id_sys']. '"><button class="message-objectif">messagerie<span class=" icon-mail">&#x2709;</span></button></a>';
    




///Connexion au serveur MySQL
try {
    $linkpdo = new PDO("mysql:host=localhost;dbname=bddsae","root","");
    }
    ///Capture des erreurs éventuelles
catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
    }





                    
                    
    

if (isset ($_GET['id_sys']))  {
    $id = $_GET['id_sys'];
    $_SESSION['id_sys']=$_GET['id_sys'];
                    
    try {
        $res = $linkpdo->query("SELECT * FROM objectif where id_objectif=$id");
        }
        catch (Exception $e) { // toujours faire un test de retour en cas de crash
            die('Erreur : ' . $e->getMessage());
        }

        ///Affichage des entrées du résultat une à une
        
        $double_tab = $res -> fetchAll(); // je met le result de ma query dans un double tableau
        $nombre_ligne = $res -> rowCount();
        $liste = array();
        
        $titre_systeme = $double_tab[0][1];

        ///Fermeture du curseur d'analyse des résultats
        $res->closeCursor();


        echo"<h1>$titre_systeme</h1>";



        switch ($double_tab[0][10]) {
            case '1':{

                
                      
               try {
                   $res = $linkpdo->query("SELECT nom FROM objectif where id_objectif=$id"); // le nom est la chaine que j'utilise pour construire le système
                   $res2 = $linkpdo->query("SELECT lien_jeton FROM enfant where id_enfant=".$_SESSION['id_enfant']."");
                }
               catch (Exception $e) { // toujours faire un test de retour au cas ou ça crash
                       die('Erreur : ' . $e->getMessage());
                   }
           
                   ///Affichage des entrées du résultat une à une
                   
                   $double_tab = $res -> fetchAll();
                   $talbeau_jeton = $res2 -> fetchAll();

                   $lien_jeton = $talbeau_jeton[0][0];
                   
                   // var_dump($double_tab);
                   // exit();
                   $valeur = $double_tab[0];
                   //var_dump( $valeur);
               //echo$_POST['chaine'];
           
               $chaine=$valeur[0];

               // TESTER SI IL Y A DES 0 DANS LA CHAINE, SI NON, çA VEUT DIRE QUE LE SYSTEME EST FINI
               $val = strpos($chaine,'0');
               if ( $val==false){
                    // echo$val;
                    // echo"<br>";
                    // echo$chaine;
                    echo"<h1><a href=\"page_recompense.php \">BRAVO CE SYSTEME EST COMPLET, TU PEUX CHOISIR UNE RECOMPENSE !</h1>";
                    
               }

               echo"<div class=\"sys\">";
                echo"<table>";
           
               /* 
                   il faut maintenant 'déconstruire' la chaine pour pouvoir faire un tableau
                       début :
                       manger_1_0000000: dormir_2_0000000:
               */
           
               $morceau = explode(":", $chaine);
           
               array_pop($morceau); // je retire la partie apres le dernier ":" 
               $compteur=0;
               foreach ($morceau as $ligne){
                   
                   
                   $element = explode("_", $ligne);
                   $tache = $element[0];
                   $jetons = $element[1];
                   $tab_jeton = str_split($jetons);
                   echo"<tr>";
                   echo"<td class='struct'>";
                   
                   echo"<p>$tache</p>";
                   echo"</td>";
               
                   //ajout des cases de jetons
                   foreach($tab_jeton as $case_tab){
                       if($case_tab==0){
                           echo"<td class='case_jeton' id=$compteur >";
                           echo'<a href="choix_sys_ajout.php?id='.$id.'&amp;case='.$compteur.'&amp;chaine='.$chaine.'" style="display: block;width: 5rem;height: 5rem;"></a>';
                           echo"</td>";
           
                       }else{
                            
                           echo"<td class='case_jeton' id=$compteur>";
                           echo"<center>";
                           echo"<img class=\"jeton\" src=$lien_jeton alt=$lien_jeton>";
                           echo"</center>";
                           echo"</td>";
                       }
           
                       
                       $compteur+=1;
                   }
           
                   echo"</tr>";
               }echo"</table>";

                break;
            }
            case '2':{}
                echo"sys de type 2";
                break;

            case '3':{   
           
           
               try {
                   $res = $linkpdo->query("SELECT nom FROM objectif where id_objectif=$id"); // le nom est la chaine que j'utilise pour construire le système
                   $res2 = $linkpdo->query("SELECT lien_jeton FROM enfant where id_enfant=".$_SESSION['id_enfant']."");
   
                }
               catch (Exception $e) { // toujours faire un test de retour au cas ou ça crash
                       die('Erreur : ' . $e->getMessage());
                   }
           
                   ///Affichage des entrées du résultat une à une
                   
                   $double_tab = $res -> fetchAll();
                   $talbeau_jeton = $res2 -> fetchAll();

                   $lien_jeton = $talbeau_jeton[0][0];
           
                   // var_dump($double_tab);
                   // exit();
                   $valeur = $double_tab[0];
                   //var_dump( $valeur);
               //echo$_POST['chaine'];
           
               $chaine=$valeur[0];

               // TESTER SI IL Y A DES 0 DANS LA CHAINE, SI NON, çA VEUT DIRE QUE LE SYSTEME EST FINI
               if ( strpos($chaine, 0)==false){
                echo"<h1><a href=\"page_recompense.php \">BRAVO CE SYSTEME EST COMPLET, TU PEUX CHOISIR UNE RECOMPENSE !</h1>";
                
           }

           echo"<div class=\"sys\">";
                echo"<table>";
           
                    echo"<tr>";
                        echo"<td class=\"struct\">";
                            echo"<p></p>";
                        echo"</td>";
                        echo"<td class=\"struct\">";
                            echo"<p>Lundi</p>";
                        echo"</td>";
                        echo"<td class=\"struct\">";
                            echo"<p>Mardi</p>";
                        echo"</td>";
                        echo"<td class=\"struct\">";
                            echo"<p>Mercredi</p>";
                        echo"</td>";
                        echo"<td class=\"struct\">";
                            echo"<p>Jeudi</p>";
                        echo"</td>";
                        echo"<td class=\"struct\">";
                            echo"<p>Vendredi</p>";
                        echo"</td>";
                        echo"<td class=\"struct\">";
                            echo"<p>Samedi</p>";
                        echo"</td>";
                        echo"<td class=\"struct\">";
                            echo"<p>Dimanche</p>";
                       echo"</td>";
                   echo"</tr>";
           
               /* 
                   il faut maintenant 'déconstruire' la chaine pour pouvoir faire un tableau
                       début :
                       manger_1_0000000: dormir_2_0000000:
               */
           
               $morceau = explode(":", $chaine);
           
               array_pop($morceau); // je retire la partie apres le dernier ":" 
               $compteur=0;
               foreach ($morceau as $ligne){
                   
                   
                   $element = explode("_", $ligne);
                   $tache = $element[0];
                   $jetons = $element[1];
                   $tab_jeton = str_split($jetons);
                   echo"<tr>";
                   echo"<td class='struct'>";
                   
                   echo"<p>$tache</p>";
                   echo"</td>";
               
                   //ajout des cases de jetons
                   foreach($tab_jeton as $case_tab){
                       if($case_tab==0){
                           echo"<td id=$compteur >";
                           echo'<a href="choix_sys_ajout.php?id='.$id.'&amp;case='.$compteur.'&amp;chaine='.$chaine.'" style="display: block;width: 5rem;height: 5rem;"></a>';
                           echo"</td>";
           
                       }else{
                           echo"<td id=$compteur>";
                           echo"<center>";
                           echo"<img class=\"jeton\" src=$lien_jeton alt=$lien_jeton>";
                           echo"</center>";

                           echo"</td>";
                       }
           
                       
                       $compteur+=1;
                   }
           
                   echo"</tr>";
               }echo"</table>";

                break;
                }
                default:
                echo"grosse erreur";
                break;
                
            }
        
            
        }

        




?>      
</div>
</body>
