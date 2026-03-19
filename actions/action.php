<?php

include 'professeur.php';
include 'eleve.php';
include 'inscription.php';
include 'gestion.php';
include 'fichier.php';
ini_set('session.use_trans_sid', false);
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_samesite', 'Lax');
ini_set('session.use_strict_mode', 1);
session_start();

// Régénération de l'ID de session après connexion
if (isset($_POST['action']) && in_array($_POST['action'], array('connexionP', 'connexionE'))) {
    session_regenerate_id(true);
}
if (isset($_GET['bg']) && $_GET['bg'] == 'deconnexion') {
    session_destroy();
    unset($_SESSION);
}
if (!isset($_SESSION['etat'])) {
    $_SESSION['etat'] = 'inconnu';
}

include 'Stats.php';
include 'Activite.php';

foreach (array('loginP','loginE','email') as $cle) {
    if (isset($_POST[$cle])) {
        $_POST[$cle] = strtolower($_POST[$cle]);
    }
}

$erreur = '';
$config['loginP'] = 'login';
$config['passwordP1'] = '          ';
$config['passwordP2'] = '          ';
$config['loginE'] = 'login';
$config['passwordE1'] = '          ';
$config['passwordE2'] = '          ';
$config['email'] = 'identifiant@domaine.fr';
$config['phrase'] = 'mot(s) ou phrase(s) à mémoriser';
$config['lgPhrase'] = 140;
if (isset($_POST['action'])) {

    switch ($_POST['action']) {
        case 'connexionP':
            if (is_professeur($_POST)) {
                $_SESSION['etat'] = 'professeur';
                $_SESSION['loginP'] = $_POST['loginP'];
            } else {
                $erreur = 'L\'identifiant ou le mot de passe du professeur est incorrect.';
            }
            break;
        case 'connexionE':
            if (is_eleve($_POST)) {
                $_SESSION['etat'] = 'eleve';
                $_SESSION['loginP'] = $_POST['loginP'];
                $_SESSION['loginE'] = $_POST['loginE'];
            } else {
                $erreur = 'L\'identifiant ou le mot de passe de l\'élève est incorrect.';
            }
            break;
        default:
            break;
    }

    switch ($_POST['action']) {
        case 'inscription':
            if ($_POST['valider'] == 'OK') {
                $_POST['cle'] = $_SESSION['cle'];
                $erreur = is_inscriptionP_OK($_POST, $config);
                if ($erreur == '') {
                    $erreur = 'Inscription réussie ! Cliquez sur le lien de validation présent dans le courriel de vérification de votre adresse de messagerie';
                } else {
                    $_GET['bg'] = 'inscription';
                }
            }
            break;
        case 'modificationP':
            $erreur = is_modificationP_OK($_POST, $_SESSION, $config);
            if ($erreur == '') {
                $erreur = 'Modification acceptée !';
                $_SESSION['loginP'] = $_POST['loginP'];
            }
            break;
        case 'creationE':
            $erreur = is_creationE_OK($_SESSION['loginP'], $_POST, $config);
            if ($erreur == '') {
                $erreur = 'Création réussie !';
                unset($_GET);
                $_GET['bg'] = 'eleves';
            }
            break;
        case 'modificationE':
            $erreur = is_modificationE_OK($_SESSION['loginP'], $_POST, $config);
            if ($erreur == '') {
                $erreur = 'Modification réussie !';
                unset($_GET);
                $_GET['bg'] = 'eleves';
            }
            break;
        case 'suppressionE':
            suppressionE($_SESSION['loginP'], $_POST);
            $_GET['bg'] = 'eleves';
            break;
        case 'suppressionP':
            suppressionP($_SESSION['loginP'], $_GET['login'], $_POST);
            break;
        case 'ajoutP':
            $phrases = explode("\r\n", $_POST['phrases']);
            $nbErreurs = 0;
            foreach ($phrases as $phrase) {
                $erreur = is_phrase_OK(trim($phrase), $config);
                if ($erreur == '') {
                    $erreur = phrase_existe($_SESSION['loginP'], $_GET['login'], $phrase);
                    if ($erreur == '') {
                        ajoutP($_SESSION['loginP'], $_GET['login'], $phrase);
                    }
                }
                if ($erreur != '') {
                    $nbErreurs++;
                }
            }
            $erreur = ($nbErreurs == 0) ? '' : $nbErreurs." erreur(s) au cours de l'ajout de phrases.";
            break;
        case 'reussite':
            majResultats($_SESSION['loginP'], $_SESSION['loginE'], $_POST['reussites'], $_POST['record']);
            break;
        case 'demonstration':
            $stats = new Stats();
            $stats->maj($_POST['reussites'], $_POST['record']);
            break;
        case 'pdf':
            $_GET['bg'] = 'pdf';
            // no break
        default:
            break;
    }
}

$vue['message'] = (isset($erreur)) ? $erreur : '';
switch ($_SESSION['etat']) {
    case "professeur":
        $vue['professeur'] = $_SESSION['loginP'];
        $vue['bgx'] = 'bgp.php';

        if (isset($_GET['bg'])) {
            switch ($_GET['bg']) {
                case 'compte':
                    $vue['loginP'] = (!empty($_POST['loginP'])) ? $_POST['loginP'] : $_SESSION['loginP'];
                    $vue['passwordP1'] = $config['passwordP1'];
                    $vue['passwordP2'] = $config['passwordP2'];
                    $vue['contenu'] = 'compte.php';
                    break;
                case 'eleves':
                    $vue['eleves'] = lireEleves($_SESSION['loginP']);
                    $vue['contenu'] = 'eleves.php';
                    break;
                case 'eleve':
                    $vue['action'] = (isset($_GET['login'])) ? 'modification' : 'creation';
                    $vue['login'] = (isset($_GET['login'])) ? $_GET['login'] : '';
                    $vue['loginE'] = (!empty($_POST['loginE'])) ? $_POST['loginE'] : $config['loginE'];
                    $vue['passwordE1'] = $config['passwordE1'];
                    $vue['passwordE2'] = $config['passwordE2'];
                    $vue['contenu'] = 'eleve.php';
                    break;
                case 'phrases':
                    $vue['login'] = $_GET['login'];
                    $vue['phrase'] = $config['phrase'];
                    $vue['phrases'] = lirePhrases($_SESSION['loginP'], $_GET['login']);
                    $vue['contenu'] = 'phrases.php';
                    break;
                case 'pdf':
                    $vue['contenu'] = 'imprimer.php';
                    $vue['eleves'] = lireEleves($_SESSION['loginP'], 'pdf');
                    break;
            }
        } else {
            $vue['contenu'] = 'eleves.php';
            $vue['eleves'] = lireEleves($_SESSION['loginP']);
        }
        break;

    case "eleve":
        $vue['phrases'] = lirePhrases($_SESSION['loginP'], $_SESSION['loginE']);
        $stat = explode(',', lirePhrases($_SESSION['loginP'], $_SESSION['loginE'], 'stat'));
        $vue['reussites'] = $stat[0];
        $vue['record'] = $stat[1];
        $vue['professeur'] = $_SESSION['loginP'];
        $vue['eleve'] = $_SESSION['loginE'];
        $vue['bgx'] = 'bge.php';
        $vue['contenu'] = 'entrainement.php';
        break;

    default:
        $vue['bgx'] = 'bga.php';
        $vue['contenu'] = 'accueil.php';
        $activite = new Activite();
        if (!isset($stats)) {
            $stats = new Stats();
        }
        $vue['reussites'] = $stats->reussites;
        $vue['record'] = $stats->record;
        if (isset($_GET['bg'])) {
            switch ($_GET['bg']) {
                case 'inscription':
                    $vue['loginP'] = (!empty($_POST['loginP'])) ? $_POST['loginP'] : $config['loginP'];
                    $vue['passwordP1'] = (!empty($_POST['passwordP1'])) ? $_POST['passwordP1'] : $config['passwordP1'];
                    $vue['passwordP2'] = (!empty($_POST['passwordP2'])) ? $_POST['passwordP2'] : $config['passwordP2'];
                    $vue['email'] = (!empty($_POST['email'])) ? $_POST['email'] : $config['email'];
                    $vue['contenu'] = 'inscription.php';
                    break;
                case 'validationOK':
                    $vue['message'] = 'Félicitation, vous êtes inscrits comme PROFESSEUR sur l\'espace "Entraînement Orthographique".<br/>Désormais, vous pouvez vous connecter en utilisant votre login et votre mot de passe.';
                    break;
                case 'validationKO':
                    $vue['message'] = 'Désolé, votre inscription a échoué. Veuillez contacter le webmaster pour plus d\'informations.';
                    break;
            }
        }
        break;
}
