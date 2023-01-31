<?php
echo"page d'algo de modif de compte (obsolete)";
exit();

require('fonctions.php');
is_logged();
is_validateur();
is_not_admin();

   
        
    //je récupere les informations de mon formulaire
  
            $nom = htmlspecialchars($_POST['nom_membre']);
            $prenom = htmlspecialchars($_POST['prenom_membre']);
            $date_naissance = htmlspecialchars($_POST['ddn_membre']); 
            $ville = htmlspecialchars($_POST['ville']);
            $adresse = htmlspecialchars($_POST['ad_membre']);
            $Cpostal = htmlspecialchars($_POST['cpostal_membre']);
            $role=htmlspecialchars($_POST['role']);




                    
                ?>