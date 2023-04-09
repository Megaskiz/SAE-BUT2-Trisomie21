<?php
/**
 * @file appel_fonction.php
 * @brief Ce fichier contient les appels aux fonctions php
 * @details Ce fichier contient les appels aux fonctions php, il est appelé par les pages php qui ont besoin d'appeler des fonctions
 * @version 1.0
 */
require_once ('fonctions.php');
is_logged();
/**
 * @brief if qui permet de savoir quel appel de fonction on veut faire
 * @version 1.0
 */
if (isset($_GET['appel'])) {
    $linkpdo = connexionBd();
    /**
     * @brief switch qui permet de savoir quel appel de fonction on veut faire
     * @version 1.0
     */
    switch ($_GET['appel']) {
        case 'modif_image':
            /**
             * @brief case qui permet de modifier le compte de l'enfant
             * @details recupere les informations du formulaire, et les envoie en parametre de la fonction modif_enfant
             */
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
            /**
             * @brief case qui permet de modifier le jeton de l'enfant
             * @details si l'image est bien envoyée, alors on l'upload, et on envoie en parametre de la fonction modif_jeton et on redirige vers la page index.php avec l'id de l'enfant dans l'url
             */
        case 'modif_jeton':
            if (isset($_FILES['photo_enfant'])) {
                $id = $_SESSION["id_enfant"];
                $photo_enfant = uploadImage($_FILES['photo_enfant']);
                modif_jeton($id, $photo_enfant, $linkpdo);
                header('Location: index.php?id=' . $_SESSION['id_enfant'] . '');
            }
        break;
            /**
             * @brief case qui permet de modifier la photo de l'enfant
             * @details si l'image est bien envoyée, alors on l'upload, et on envoie en parametre de la fonction modif_photo et on redirige vers la page index.php avec l'id de l'enfant dans l'url
             */
        case 'modif_photo':
            if (isset($_FILES['photo_enfant'])) {
                $id = $_SESSION["id_enfant"];
                $photo_enfant = uploadImage($_FILES['photo_enfant']);
                modif_photo($id, $photo_enfant, $linkpdo);
                header('Location: index.php?id=' . $_SESSION['id_enfant'] . '');
            }
        break;
            /**
             * @brief case qui permet de supprimer un membre de l'équipe
             * @details recupere l'id du membre a supprimer, et l'id de l'enfant, et on envoie en parametre de la fonction eject, et on redirige vers la page index.php avec l'id de l'enfant dans l'url
             */
        case 'eject_equipe':
            $id_eject = $_GET['eject'];
            $Sid = $_GET['id'];
            eject($Sid, $id_eject, $linkpdo);
            header('Location: index.php?id=' . $_SESSION['id_enfant'] . '');
        break;
            /**
             * @brief case qui permet d'archiver un enfant
             * @datails envoie linkpdo en parametre de la fonction archive_enfant
             */
        case 'archive_enfant':
            archive_enfant($linkpdo);
        break;
            /**
             * @brief case qui permet de modifier le mot de passe d'un membre
             * @details recupere le mot de passe du formulaire, et l'id du membre, et on envoie en parametre de la fonction modif_mdp, et on redirige vers la page page_certif_compte.php avec l'id du membre dans l'url
             */
        case 'modif_mdp':
            $mdp = htmlspecialchars($_POST['mdp_membre']);
            $session = $_SESSION['id_compte_modif'];
            modif_mdp($mdp, $session, $linkpdo);
            header('Location: page_certif_compte.php?idv=' . $_SESSION['id_compte_modif'] . '');
        break;
            /**
             * @brief case qui permet de modifier le mots de passe de son compte
             * @details recupere le mot de passe du formulaire, et l'id du membre, et on envoie en parametre de la fonction modif_mdp, et on redirige vers la page mon_compte.php
             */
        case 'modif_mon_mdp':
            $mdp = htmlspecialchars($_POST['mdp_membre']);
            $session = $_SESSION['logged_user'];
            modif_mdp($mdp, $session, $linkpdo);
            header('Location:mon_compte.php');
        break;
            /**
             * @brief case qui permet de modifier le compte d'un membre
             * @details recupere les informations du formulaire, et envoie en parametre de la fonction modif_compte
             */
        case 'modif_compte':
            $nom = htmlspecialchars($_POST['nom_membre']);
            $prenom = htmlspecialchars($_POST['prenom_membre']);
            $date_naissance = htmlspecialchars($_POST['ddn_membre']);
            $ville = htmlspecialchars($_POST['ville']);
            $adresse = htmlspecialchars($_POST['ad_membre']);
            $Cpostal = htmlspecialchars($_POST['cpostal_membre']);
            $role = htmlspecialchars($_POST['role']);
            $session = $_SESSION['id_compte_modif'];
            modif_compte($nom, $prenom, $adresse, $Cpostal, $ville, $date_naissance, $role, $session, $linkpdo);
        break;
            /**
             * @brief case insert_enfant qui permet d'insérer un enfant dans la base de donnée
             * @details recupere les informations du formulaire, et envoie en parametre de la fonction insert_enfant avec un try catch pour la requete
             */
        case 'insert_enfant':
            $nom = htmlspecialchars($_POST['nom']);
            $prenom = htmlspecialchars($_POST['prenom']);
            $date_naissance = htmlspecialchars($_POST['date_naissance']);
            $lien_jeton = uploadImage($_FILES['lien_jeton']); // encore a secu
            $photo_enfant = uploadImage($_FILES['photo_enfant']); // encore à secu
            //vérification sur le nom et le prénom de l'enfant et sur la ddn
            // requete avec le mail si, rowcount() > 0 faire fail
            $requete_verif_enfant = "SELECT count(*) FROM enfant WHERE nom='$nom' and prenom='$prenom' and date_naissance='$date_naissance';";
            // Execution de la requete
            try {
                $res = $linkpdo->query($requete_verif_enfant);
                $count = $res->fetchColumn();
            }
            catch(Exception $e) {
                die('Erreur : ' . $e->getMessage());
            }
            if ($count > 0) {
                header('Location: insert_erreur_nom.php');
                break;
            } else {
                insert_enfant($nom, $prenom, $date_naissance, $lien_jeton, $photo_enfant, $linkpdo);
                break;
            }
            /**
             * @brief case insert_membre qui permet d'insérer un membre dans la base de donnée
             * @details recupere les informations du formulaire, et envoie en parametre de la fonction insert_membre avec un try catch pour la requete
             */
        case 'insert_membre':
            $nom = htmlspecialchars($_POST['nom']);
            $prenom = htmlspecialchars($_POST['prenom']);
            $adresse = htmlspecialchars($_POST['adresse']);
            $code = htmlspecialchars($_POST['code_postal']);
            $ville = htmlspecialchars($_POST['ville']);
            $courriel = htmlspecialchars($_POST['courriel']);
            $ddn = htmlspecialchars($_POST['ddn']);
            $Mdp = htmlspecialchars($_POST['password']);
            //$Mdp_verif = htmlspecialchars($_POST['password_verif']);
            $pro = htmlspecialchars($_POST['pro']);
            // requete avec le mail si, rowcount() > 0 faire fail
            $requete_verif_mail = "SELECT count(*) FROM membre WHERE courriel='$courriel'";
            // Execution de la requete
            try {
                $res = $linkpdo->query($requete_verif_mail);
            }
            catch(Exception $e) {
                die('Erreur : ' . $e->getMessage());
            }
            $count = $res->fetchColumn();
            if ($count > 0) {
                header('Location: insert_erreur_nom.php');
                break;
            } else {
                insert_membre($nom, $prenom, $adresse, $code, $ville, $courriel, $ddn, $Mdp, $pro, $linkpdo);
                break;
            }
            /**
             * @brief case mise_en_route qui permet de mettre en route un objectif
             * @details recupere l'id de l'objectif et la valeur de l'objectif, et envoie en parametre de la fonction inverse_utilisation_objectif
             */
        case 'mise_en_route':
            if (isset($_GET["id_sys"]) and isset($_GET["valeur"])) {
                $sys = $_GET["id_sys"];
                $val = $_GET["valeur"];
                inverse_utilisation_objectif($sys, $val, $linkpdo);
                header('Location: index.php?id=' . $_SESSION["id_enfant"]);
            } else {
                header('Location: index?id=' . $_SESSION["logged_user"] . '.php');
            }
        break;
            /**
             * @brief case supprime_objectif qui permet de supprimer un objectif
             * @details recupere l'id de l'objectif, et envoie en parametre de la fonction supprime_objectif
             */
        case 'supprime_objectif':
            if (isset($_GET['id_sys'])) {
                $sys = $_GET['id_sys'];
                supprime_objectif($sys, $linkpdo);
                supprimer_image($linkpdo); // une fois qu'on a supprimé l'objectif, on peut supprimer les nouvelles images qui n'ont pas de lien dans la bd
                header('Location:archive_sys.php');
            } else {
                header('Location:archive_sys.php');
            }
        break;
            /**
             * @brief case supprime_profil_enfant qui permet de supprimer un profil enfant
             * @details recupere l'id du profil enfant, et envoie en parametre de la fonction supprime_profil_enfant
             */
        case 'supprime_profil_enfant':
            if (isset($_GET['id_enfant'])) {
                $sys = $_GET['id_enfant'];
                supprime_profil_enfant($sys, $linkpdo);
                supprimer_image($linkpdo); // une fois qu'on a supprimé l'objectif, on peut supprimer les nouvelles images qui n'ont pas de lien dans la bd

                header('Location:archive_profil_enfant.php');
            } else {
                header('Location:archive_profil_enfant.php');
            }
        break;
            /**
             * @brief case supprime_utilisateur qui permet de supprimer un utilisateur
             * @details recupere l'id de l'utilisateur, et envoie en parametre de la fonction supprime_utilisateur
             */
        case 'supprime_utilisateur':
            if (isset($_GET['id_user'])) {
                $id = $_GET['id_user'];
                supprime_utilisateur($id, $linkpdo);
                header('Location:archive_membre.php');
            } else {
                header('Location:archive_membre.php');
            }
        break;
            /**
             * @brief case restaure_utilisateur qui permet de restaurer un utilisateur archivé
             * @details recupere l'id de l'utilisateur, et envoie en parametre de la fonction restaure_utilisateur
             */
        case 'restaure_utilisateur':
            if (isset($_GET['id_user'])) {
                $id = $_GET['id_user'];
                restaure_utilisateur($id, $linkpdo);
                header('Location:page_certif_compte.php?idv=' . $id);
            } else {
                header('Location:archive_membre.php');
            }
        break;
            /**
             * @brief case restaure_profil_enfant qui permet de restaurer un profil enfant archivé
             * @details recupere l'id du profil enfant, et envoie en parametre de la fonction restaure_profil_enfant
             */
        case 'restaure_profil_enfant':
            if (isset($_GET['id'])) {
                $id = $_GET['id'];
                restaure_profil_enfant($id, $linkpdo);
                header('Location:index.php?id=' . $id);
            } else {
                header('Location:archive_profil_enfant.php');
            }
        break;
            /**
             * @brief case archive_membre qui permet d'archiver un utilisateur
             * @details recupere l'id de l'utilisateur, et envoie en parametre de la fonction archive_membre
             */
        case 'archive_membre':
            if (isset($_GET['id'])) {
                $id = $_GET['id'];
                archive_membre($id, $linkpdo);
                header('Location:page_certif_compte.php');
            } else {
                header('Location:page_certif_compte.php');
            }
        break;
            /**
             * @brief case valide_membre qui permet de valider un utilisateur
             * @details recupere l'id de l'utilisateur, et envoie en parametre de la fonction valide_membre
             */
        case 'valide_membre':
            if (isset($_GET['id'])) {
                $id = $_GET['id'];
                valide_membre($id, $linkpdo);
                header('Location:page_certif_compte.php');
            } else {
                header('Location:page_certif_compte.php');
            }
        break;
            /**
             * @brief case invalide_membre qui permet d'invalider un utilisateur
             * @details recupere l'id de l'utilisateur, et envoie en parametre de la fonction invalide_membre
             */
        case 'invalide_membre':
            if (isset($_GET['id'])) {
                $id = $_GET['id'];
                invalide_membre($id, $linkpdo);
                header('Location:page_certif_compte.php');
            } else {
                header('Location:page_certif_compte.php');
            }
        break;
        case 'purge_image':
            supprimer_image($linkpdo);
            header('Location: index.php');
        break;
        default:
            echo $_GET['appel'];
        break;
    }
}
?>