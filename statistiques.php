<?php
require("fonctions.php");
is_logged();
is_not_admin();
is_validateur();
?>

<?php

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

try {
    $res = $linkpdo->query("SELECT id_objectif, nb_jetons from objectif where id_enfant=$id_enfant");
} catch (Exception $e) { // toujours faire un test de retour au cas ou ça crash
    die('Erreur : ' . $e->getMessage());
}

///Affichage des entrées du résultat une à une

$double_tab = $res->fetchAll(); // je met le result de ma query dans un double tableau
$nombre_ligne = $res->rowCount(); // =2 car il y a 2 ligne dans ma base

$liste_sys = array();
$liste_nb_jetons = array();

for ($i = 0; $i < $nombre_ligne; $i++) {  
    array_push($liste_sys, $double_tab[$i][0]);  
    array_push($liste_nb_jetons, $double_tab[$i][1]);  
}

// print_r($liste_sys);
// print_r($liste_nb_jetons);

$data = "[ ";
$sessions = "[ ";

// pour chaque système on recup le nombre de session :

$iteration=-1;
// foreach ($liste_sys as $key => $id_sys) { // pour chaque sys, je recupere le nombre de sessions
//     $iteration++;
    try {
        $res = $linkpdo->query("SELECT MAX(id_session) from placer_jeton where id_objectif=10;");
    } catch (Exception $e) { // toujours faire un test de retour au cas ou ça crash
        die('Erreur : ' . $e->getMessage());
    }

    $double_tab = $res->fetchAll(); // je met le result de ma query dans un double tableau
    $nb_session = $double_tab[0][0];
    //echo"pour le systeme: ".$id_sys." , nombre de session : ".$nb_session."<br>";

    for ($i = 1; $i <= $nb_session; $i++) {  // pour chaque session, je recupere le nombre de jetons placés
        
            try {
                $res = $linkpdo->query("SELECT date_heure from placer_jeton where id_objectif=10 and id_session=$i");

            } catch (Exception $e) { // toujours faire un test de retour au cas ou ça crash
                die('Erreur : ' . $e->getMessage());
            }
            
            // pour chaque session je récup le nombre de jetohns placés 
            $double_tab = $res->fetchAll(); // je met le result de ma query dans un double tableau
            $nombre_jetons = $res->rowCount(); // =2 car il y a 2 ligne dans ma base

            if ($i==$nb_session){
                $nombre_jetons-=1;
            }
            $data=$data."'".$nombre_jetons."' , ";
            $sessions=$sessions."'session".$i."' , ";

            // si reussi : $nombre_jetons == $liste_nb_jetons[$iteration]
            echo" ___pour la session : ".$i.", le nombre de jetons placés : ".$nombre_jetons."sachant que le système a 3 jetons <br>"; //$liste_nb_jetons[$iteration]

    }
    $data = substr($data,0,-2);
    $data=$data."]";

    $sessions = substr($sessions,0,-2);
    $sessions=$sessions."]";
    echo$sessions;
//}

$var = "['Blue', 'Yellow', 'Green', 'Purple', 'Orange']";
?>

<div>
  <canvas id="myChart" style=" height:30% "></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
  const ctx = document.getElementById('myChart');

  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: <?php  echo$sessions ?> ,
      datasets: [{
        label: '# of Votes',
        data: <?php echo$data ?>,
        borderWidth: 1
      }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });
</script>

