<?php
require('fonctions.php');
is_logged();
?>
<!DOCTYPE HTML>
<html>

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

    echo"<br>";echo"<br>";echo"<br>";
                    
    ///Sélection de tout le contenu de la table enfant
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

                echo"<div class=\"sys\">";
                echo"<table>";
                      
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
                           
                           echo"<img class=\"jeton\" src=$lien_jeton alt=$lien_jeton>";
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
                echo"<div class=\"sys\">";
                echo"<table>";
           
                    echo"<tr>";
                        echo"<td class=\"struct\">";
                            echo"<p></p>";
                        echo"</td>";
                        echo"<td class=\"struct\">";
                            echo"<p>lundi</p>";
                        echo"</td>";
                        echo"<td class=\"struct\">";
                            echo"<p>mardi</p>";
                        echo"</td>";
                        echo"<td class=\"struct\">";
                            echo"<p>mercredi</p>";
                        echo"</td>";
                        echo"<td class=\"struct\">";
                            echo"<p>jeudi</p>";
                        echo"</td>";
                        echo"<td class=\"struct\">";
                            echo"<p>vendredi</p>";
                        echo"</td>";
                        echo"<td class=\"struct\">";
                            echo"<p>samedi</p>";
                        echo"</td>";
                        echo"<td class=\"struct\">";
                            echo"<p>dimanche</p>";
                       echo"</td>";
                   echo"</tr>";
           
           
           
           
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
                           echo"<img class=\"jeton\" src=$lien_jeton alt=$lien_jeton>";
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
