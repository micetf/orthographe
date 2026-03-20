<?php

include 'actions/config.php';

$demandes   = DIR_DEMANDES;
$professeurs = DIR_PROFESSEURS;
$accueil    = preg_replace('#validation#', 'index', $_SERVER['PHP_SELF']);

if (isset($_GET['l']) && isset($_GET['e'])) {

    $le = '_'.$_GET['l'].'_'.$_GET['e'].'.txt';

    // Nouveau format : eo_<md5login>_<md5email>.txt (sans paramètre p)
    if (file_exists($demandes.'eo'.$le)) {
        $contenu = file_get_contents($demandes.'eo'.$le);
        file_put_contents($professeurs.'eo'.$le, $contenu);
        unlink($demandes.'eo'.$le);
        header('Location: '.$accueil.'?bg=validationOK');
        exit(0);
    }

    if (file_exists($professeurs.'eo'.$le)) {
        header('Location: '.$accueil.'?bg=validationOK');
        exit(0);
    }

    // Ancien format : eo_<md5login>_<md5pwd>_<md5email>.txt (paramètre p présent)
    // Maintenu pour compatibilité avec les liens déjà envoyés par email
    if (isset($_GET['p'])) {
        $lpe = '_'.$_GET['l'].'_'.$_GET['p'].'_'.$_GET['e'].'.txt';

        if (file_exists($demandes.'eo'.$lpe)) {
            $contenu = file_get_contents($demandes.'eo'.$lpe);
            // Migrer vers le nouveau format
            $hash = password_hash('', PASSWORD_BCRYPT); // hash temporaire, l'utilisateur devra se connecter
            file_put_contents($professeurs.'eo'.$le, $hash."\n".$contenu);
            unlink($demandes.'eo'.$lpe);
            header('Location: '.$accueil.'?bg=validationOK');
            exit(0);
        }

        if (file_exists($professeurs.'eo'.$lpe)) {
            header('Location: '.$accueil.'?bg=validationOK');
            exit(0);
        }
    }
}

header('Location: '.$accueil.'?bg=validationKO');
