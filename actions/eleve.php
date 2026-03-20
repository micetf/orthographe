<?php

/**
 * Migre un fichier élève de l'ancien format (hash MD5 dans le nom)
 * vers le nouveau format (hash bcrypt dans le contenu, nom simplifié).
 */
function migrer_eleve_vers_bcrypt($ancien_nom, $login_prof, $login_eleve, $password)
{
    $ancien_chemin = 'datas/eleves/'.$ancien_nom;

    // Nouveau nom : eo_<md5loginP>_<md5loginE>.txt
    $nouveau_nom    = 'eo_'.md5($login_prof).'_'.md5($login_eleve).'.txt';
    $nouveau_chemin = 'datas/eleves/'.$nouveau_nom;

    // Lire le contenu existant (stats + phrases)
    $contenu = file_get_contents($ancien_chemin);
    if ($contenu === false) {
        $contenu = '0,3600#';
    }

    // Écrire le nouveau fichier avec hash bcrypt en première ligne
    $hash = password_hash($password, PASSWORD_BCRYPT);
    file_put_contents($nouveau_chemin, $hash."\n".$contenu);

    // Supprimer l'ancien fichier
    unlink($ancien_chemin);
}

function is_eleve($infos)
{
    if (!isset($infos['loginP']) || !isset($infos['loginE']) || !isset($infos['passwordE'])) {
        return false;
    }

    $prefixe = 'eo_'.md5($infos['loginP']).'_'.md5($infos['loginE']);
    $dir = dir('./datas/eleves/');
    while ($nom = $dir->read()) {
        if (strpos($nom, $prefixe) !== 0) {
            continue;
        }
        if (strpos($nom, '.txt') === false) {
            continue;
        }

        $chemin  = 'datas/eleves/'.$nom;
        $contenu = file_get_contents($chemin);
        if ($contenu === false) {
            continue;
        }

        $pos_nl         = strpos($contenu, "\n");
        $premiere_ligne = ($pos_nl !== false) ? substr($contenu, 0, $pos_nl) : $contenu;

        // Nouveau format : hash bcrypt en première ligne
        if (strpos($premiere_ligne, '$2y$') === 0) {
            if (password_verify($infos['passwordE'], $premiere_ligne) ||
                password_verify(strtolower($infos['passwordE']), $premiere_ligne)) {
                return true;
            }
            continue;
        }

        // Ancien format : hash MD5 dans le nom du fichier
        // Format : eo_<md5loginP>_<md5loginE>_<md5pwd>.txt
        $sans_ext = substr($nom, 0, -4);
        $parties  = explode('_', $sans_ext);
        if (count($parties) < 4) {
            continue;
        }
        $md5_stocke = $parties[3];

        if ($md5_stocke === md5($infos['passwordE']) ||
            $md5_stocke === md5(strtolower($infos['passwordE']))) {
            migrer_eleve_vers_bcrypt($nom, $infos['loginP'], $infos['loginE'], $infos['passwordE']);
            return true;
        }
    }

    return false;
}
