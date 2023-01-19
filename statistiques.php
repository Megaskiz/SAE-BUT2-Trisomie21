<?php
require("fonctions.php");
is_logged();
is_not_admin();
is_validateur();
?>

<!DOCTYPE html>
<html lang="fr" style="font-family: Arial,sans-serif;">
<head>
  <meta charset="utf-8">
  <title> Statistiques</title>
  <link rel="stylesheet" href="style_stat.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<?php
echo '<a href="page_admin.php?id=' . $_SESSION['id_enfant'] . '"><button>retour au menu</button></a>';

try {
    $linkpdo = new PDO("mysql:host=localhost;dbname=bddsae", "root", "");
}
///Capture des erreurs éventuelles
catch (Exception $e) {
    die('Erreur : ' . $e->getMessage()); 
}

//var_dump($_SESSION);

$id_enfant = $_SESSION["id_enfant"];


/* 
faire une séléction de tous les systèmes (+ leuurs nombre de jetons) dont fait parti l'enfant

select id_objectif, nb_jetons from objectif where id_enfant=$id_enfant

pour chaque sys faire :
    recup son nombre de sessions
        pour chaque session :
            compter le nombre de jetons ( pour la derniere on retire un jeton)
            faire le ratio des jetons sur le nombre total de jetons du sys
            afficher si c'est une reussite ou un echec
            compter les deux ( echec + reussites) si + de 80% de reussites, système réussi



*/

// je récupere tous le systèmes d'un enfant
try {
    $res = $linkpdo->query("SELECT id_objectif, nb_jetons from objectif where visibilite=0 and id_enfant=$id_enfant");
} catch (Exception $e) { // toujours faire un test de retour au cas ou ça crash
    die('Erreur : ' . $e->getMessage());
}

///Affichage des entrées du résultat une à une
$double_tab = $res->fetchAll(); // je met le result de ma query dans un double tableau
$nombre_ligne = $res->rowCount(); // =2 car il y a 2 ligne dans ma base

$liste_sys = array();
$liste_nb_jetons = array();

for ($i = 0; $i < $nombre_ligne; $i++) {  
    array_push($liste_sys, $double_tab[$i][0]);  // liste de tous les systèmes
    array_push($liste_nb_jetons, $double_tab[$i][1]);  // listes des nombres de jetons par systèmes
}


// print_r($liste_sys);
// print_r($liste_nb_jetons);

// faire une div html pour l'affichage 




$iteration=-1;
// je fais une boucle ppour tous les systèmes

foreach ($liste_sys as $key => $id_sys) { // pour chaque sys, je recupere le nombre de sessions
  $iteration++;
  
  echo"<div class=\"st_sys\"  id=sys_num".$iteration.">";

$data = "[ "; // la liste du nombre de jetons
$sessions = "[ "; // la liste des dessions 
$color = "[ "; // la liste des couleurs, (vert ou rouge)
$win = 0; // compteur de sessions réussies
$lose =0; // compteur de sessions non réussies


// pour chaque système on recup le nombre de session :
//echo($liste_sys[$iteration]);


    try {
        $res = $linkpdo->query("SELECT MAX(id_session) from placer_jeton where id_objectif= $liste_sys[$iteration] ;");
        //$res->debugDumpParams();
        
    } catch (Exception $e) { // toujours faire un test de retour au cas ou ça crash
        die('Erreur : ' . $e->getMessage());
    }

    $double_tab = $res->fetchAll(); // je met le result de ma query dans un double tableau
    $nb_session = $double_tab[0][0];

    //echo"pour le systeme: ".$id_sys." , nombre de session : ".$nb_session."<br>";

    if ($nb_session!=null){
      //echo"je rentre dans la boucle car il a ".$nb_session."  sessions dans le système ".$liste_sys[$iteration]."<br>";



    

    for ($i = 1; $i <= $nb_session; $i++) {  // pour chaque session, je recupere le nombre de jetons placés
        
            try {
                $res = $linkpdo->query("SELECT date_heure from placer_jeton where id_objectif=$liste_sys[$iteration] and id_session=$i");
                //$res->debugDumpParams();

            } catch (Exception $e) { // toujours faire un test de retour au cas ou ça crash
                die('Erreur : ' . $e->getMessage());
            }
            
            // pour chaque session je récup le nombre de jetohns placés 
            $double_tab = $res->fetchAll(); // je met le result de ma query dans un double tableau
            $nombre_jetons = $res->rowCount();

            //echo"Voici le nombre de jetons pour la session n°".$i." : ".$nombre_jetons;echo"<br>";

            if ($i==$nb_session){
                $nombre_jetons-=1;
            }
            $data=$data."'".$nombre_jetons."' , ";
            $sessions=$sessions."'session".$i."' , ";

            if($nombre_jetons == $liste_nb_jetons[$iteration]){//$nombre_jetons == $liste_nb_jetons[$iteration]
              $color=$color."'rgba(0,200,0,0.6)', "; // vert
              $win+=1;
            }else{
              $color=$color."'rgba(200,0,0,0.6)', "; // rouge
              $lose+=1;
            }
            //echo" ___pour la session : ".$i.", le nombre de jetons placés : ".$nombre_jetons." sachant que le système a ".$liste_nb_jetons[$iteration]." jetons <br>"; //$liste_nb_jetons[$iteration]

    }
    $data = substr($data,0,-2); // je retire la derniere ',' 
    $data=$data."]";            // j'ajoute le ']' fermant

    $sessions = substr($sessions,0,-2);
    $sessions=$sessions."]";

    $color = substr($color,0,-2); 
    $color=$color."]";

    $total_win = ($win/($win+$lose)*100);

    if (strlen($data)!=1){

    /* ajouter :

      le nombre total de sessions,
      le nombre moyens de jetons placés sur une session
      si oui ou non c'est une réussite


    
    */

    try {
      $res = $linkpdo->query("SELECT intitule from objectif where id_objectif=$liste_sys[$iteration]");
      //$res->debugDumpParams();

    } catch (Exception $e) { // toujours faire un test de retour au cas ou ça crash
        die('Erreur : ' . $e->getMessage());
    }
    
    // pour chaque session je récup le nombre de jetohns placés 
    $double_tab = $res->fetchAll();
    $nom = $double_tab[0][0];
echo'

<center class="titre_stat">
<h1>Objectif : '.$nom.'</h1>

</center>
<div class="case_stat" style="display:flex">
  <div class="left_stat" style="width:45%">
    <canvas id="myChart'.$iteration.'"></canvas>
  </div>
  <div style="width:10%">
  </div>
  <div class="right_stat" style="width:45%">
    <canvas id="myChart2'.$iteration.'"></canvas>
  </div>
</div>
';



echo'<script src="https://cdn.jsdelivr.net/npm/chart.js">import Chart from \'chart.js/auto\';</script>';

echo"



<script>

  const ctx".$iteration." = document.getElementById('myChart".$iteration."');

  new Chart(ctx".$iteration.", {
    type: 'bar',
    data: {
      labels:".$sessions.",
      datasets: [{
        label: 'nombres de jetons',
        data:".$data.",
        backgroundColor:".$color.",
        borderWidth:1,
        borderColor:'#777',
        hoverBorderWidth:3,
        hoverBorderColor:'#000'
      }],
    },
    options: {
      maintainAspectRatio: false,
      scales:{
        y:{
          max:$liste_nb_jetons[$iteration],
          stepSize: 1,
          ticks:{
            stepSize: 1
          }
        }

          
      },
      plugins:{
        title:{
          display:true,
          text:'Le nombre de jetons placés'
        }
      }
    }
  });



</script>

<script>
    const ctx2".$iteration." = document.getElementById('myChart2".$iteration."');

  
  new Chart(ctx2".$iteration.", {
      type: 'pie',
      data:{
        labels: [
          'Réussite : ".(round($total_win))." %',
          'Echec :".(100-round($total_win))." %'
        ],
        datasets: [{
          label: 'Quantité',
          
          data: [".$win.",".$lose."],
          backgroundColor: [
            'rgba(0,200,0,0.6)',
            'rgba(200,0,0,0.6)'
          ],
          hoverOffset: 4
        }]
      },
      options:{
        offset:60
      },
    });
</script>



";
  }
}
echo"</div>";
}

?>