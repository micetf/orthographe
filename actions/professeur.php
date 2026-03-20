<?php

function logErreurConnexion($login)
{
    $s = file_get_contents('./datas/log.txt');
    file_put_contents('./datas/log.txt', $s.PHP_EOL.date('Y-m-d H:i:s').' LOGIN_ECHOUE: '.$login);
}

/**
 * Migre un fichier professeur de l'ancien format (hash MD5 dans le nom)
 * vers le nouveau format (hash bcrypt dans le contenu, nom simplifié).
 */
function migrer_professeur_vers_bcrypt($ancien_nom, $login, $password)
{
    $ancien_chemin = 'datas/professeurs/'.$ancien_nom;

    // Extraire le hash email depuis l'ancien nom
    // Format ancien : eo_<md5login>_<md5pwd>_<md5email>.txt
    $sans_ext = substr($ancien_nom, 0, -4);
    $parties  = explode('_', $sans_ext);
    $email_hash = end($parties);

    // Nouveau nom : eo_<md5login>_<md5email>.txt
    $nouveau_nom    = 'eo_'.md5($login).'_'.$email_hash.'.txt';
    $nouveau_chemin = 'datas/professeurs/'.$nouveau_nom;

    // Lire le contenu existant (liste des élèves)
    $contenu = file_get_contents($ancien_chemin);
    if ($contenu === false) {
        $contenu = '|';
    }

    // Écrire le nouveau fichier avec hash bcrypt en première ligne
    $hash = password_hash($password, PASSWORD_BCRYPT);
    file_put_contents($nouveau_chemin, $hash."\n".$contenu);

    // Supprimer l'ancien fichier
    unlink($ancien_chemin);
}

function is_professeur($infos)
{
    if (empty($infos['loginP']) || empty($infos['passwordP'])) {
        logErreurConnexion(isset($infos['loginP']) ? $infos['loginP'] : '');
        return false;
    }

    $prefixe = 'eo_'.md5($infos['loginP']).'_';
    $dir = dir('./datas/professeurs/');
    while ($nom = $dir->read()) {
        if (strpos($nom, $prefixe) !== 0) {
            continue;
        }
        if (strpos($nom, '.txt') === false) {
            continue;
        }

        $chemin = 'datas/professeurs/'.$nom;
        $contenu = file_get_contents($chemin);
        if ($contenu === false) {
            continue;
        }

        $pos_nl       = strpos($contenu, "\n");
        $premiere_ligne = ($pos_nl !== false) ? substr($contenu, 0, $pos_nl) : $contenu;

        // Nouveau format : hash bcrypt en première ligne
        if (strpos($premiere_ligne, '$2y$') === 0) {
            if (password_verify($infos['passwordP'], $premiere_ligne) ||
                password_verify(strtolower($infos['passwordP']), $premiere_ligne)) {
                @touch($chemin);
                return true;
            }
            continue;
        }

        // Ancien format : hash MD5 dans le nom du fichier
        // Format : eo_<md5login>_<md5pwd>_<md5email>.txt
        $sans_ext = substr($nom, 0, -4);
        $parties  = explode('_', $sans_ext);
        if (count($parties) < 4) {
            continue;
        }
        $md5_stocke = $parties[2];

        if ($md5_stocke === md5($infos['passwordP']) ||
            $md5_stocke === md5(strtolower($infos['passwordP']))) {
            migrer_professeur_vers_bcrypt($nom, $infos['loginP'], $infos['passwordP']);
            return true;
        }
    }

    logErreurConnexion($infos['loginP']);
    return false;
}
