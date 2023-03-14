<?php
require('fonctions.php');
is_logged();
?>

<?php

if(isset($_GET['appel'])){
    $linkpdo = connexionBd();

    switch ($_GET['appel']) {
        
        case 'modif_image':
            

        case 'modif_enfant':
            $nom = $_POST['nom_enfant'];
            $prenom = $_POST['prenom_enfant'];
            $activite = $_POST['activite'];
            $adresse = $_POST['adresse'];
            $handicap = $_POST['handicap'];
            $info_sup = $_POST['info_sup'];
            $date_naissance = $_POST['date_naissance'];
            $session = $_SESSION['id_enfant'];
        
            modif_enfant($nom, $prenom, $date_naissance, $adresse, $activite, $handicap, $info_sup, $session, $linkpdo);
            break;

        case 'modif_jeton':
            if (isset($_FILES['photo_enfant'])) {
                $id = $_SESSION["id_enfant"];
                $photo_enfant = uploadImage($_FILES['photo_enfant']);
                modif_jeton($id, $photo_enfant, $linkpdo);
                header('Location: page_admin.php?id='.$_SESSION['id_enfant'].'');
            }
            break;

        case 'modif_photo':
            if (isset($_FILES['photo_enfant'])) {
                $id = $_SESSION["id_enfant"];
                $photo_enfant = uploadImage($_FILES['photo_enfant']);
                modif_photo($id, $photo_enfant, $linkpdo);
                header('Location: page_admin.php?id='.$_SESSION['id_enfant'].'');
            }
            break;

        case 'eject_equipe':
            $id_eject = $_GET['eject'];
            $Sid = $_GET['id'];
            eject($Sid,$id_eject, $linkpdo);
            header('Location: page_admin.php?id='.$_SESSION['id_enfant'].'');
            break;


        case 'archive_enfant':
            archive_enfant($linkpdo);
            break;

        case 'modif_mdp':
            $mdp=htmlspecialchars($_POST['mdp_membre']);
            $session = $_SESSION['id_compte_modif'];
    
            modif_mdp($mdp, $session, $linkpdo);
            header('Location: page_certif_compte.php?idv='.$_SESSION['id_compte_modif'].'');
            break;


        case 'modif_mon_mdp':
            $mdp=htmlspecialchars($_POST['mdp_membre']);
            $session = $_SESSION['logged_user'];

            modif_mdp($mdp, $session, $linkpdo);
            header('Location:mon_compte.php');
            break;


        case 'modif_compte':
            $nom = htmlspecialchars($_POST['nom_membre']);
            $prenom = htmlspecialchars($_POST['prenom_membre']);
            $date_naissance = htmlspecialchars($_POST['ddn_membre']); 
            $ville = htmlspecialchars($_POST['ville']);
            $adresse = htmlspecialchars($_POST['ad_membre']);
            $Cpostal = htmlspecialchars($_POST['cpostal_membre']);
            $role=htmlspecialchars($_POST['role']);
            $session = $_SESSION['id_compte_modif'];
        
            modif_compte($nom, $prenom, $adresse, $Cpostal, $ville, $date_naissance,$role, $session, $linkpdo);
            break; 

        // faire case d'insert :

        case 'insert_enfant':
            
            $nom = htmlspecialchars($_POST['nom']);
            $prenom =htmlspecialchars($_POST['prenom']);
            $date_naissance = htmlspecialchars($_POST['date_naissance']);
            $lien_jeton = uploadImage($_FILES['lien_jeton']); // encore a secu
            $photo_enfant = uploadImage($_FILES['photo_enfant']); // encore à secu

            insert_enfant($nom, $prenom, $date_naissance, $lien_jeton, $photo_enfant, $linkpdo);
            break;
            //insert enfant done
            //insert membre
            //insert récompense
            //insert systeme

            // pour pouvoir retirer 4 autres fichiers
            
            
            
        default:
            echo"autre";
            exit();
            break;
    
    }
}





?>