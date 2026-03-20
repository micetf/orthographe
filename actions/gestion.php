<?php

function loginE_existe($professeur, $login)
{
    $dir = dir(DIR_ELEVES);
    while ($nom = $dir->read()) {
        if (strpos($nom, 'eo_'.md5($professeur).'_'.md5($login)) === 0) {
            return 'Ce login est déjà utilisé.';
        }
    }
    return '';
}

function is_loginE_OK($login, $config)
{
    if (empty($login) || $login == $config) {
        return 'La saisie d\'un login est obligatoire';
    }
    if (strlen($login) < 4) {
        return 'Le login doit comprendre au moins 4 caractères';
    }
    if (preg_match('#[^a-z0-9]#', $login)) {
        return 'Le login ne doit contenir que des lettres minuscules et des chiffres, sans caractères spéciaux.';
    }
    return '';
}

function is_passwordE1_OK($password, $config)
{
    if (empty($password) || $password == $config) {
        return 'La saisie d\'un mot de passe est obligatoire';
    }
    if (strlen($password) < 4) {
        return 'Le mot de passe doit comprendre au moins 4 caractères';
    }
    if (preg_match('#[^a-zA-Z0-9]#', $password)) {
        return 'Le mot de passe est incorrect';
    }
    return '';
}

function is_passwordE2_OK($password, $password1, $config)
{
    if (empty($password) || $password == $config) {
        return 'La confimation du mot de passe est obligatoire';
    }
    if ($password != $password1) {
        return 'La confirmation du mot de passe est erronée';
    }
    return '';
}

/**
 * Lit la partie données d'un fichier en sautant la ligne de hash bcrypt si présente.
 * Nouveau format : ligne 1 = hash bcrypt, ligne 2+ = données
 * Ancien format  : tout le contenu = données
 */
function lire_donnees_fichier($chemin)
{
    $s = file_get_contents($chemin);
    if ($s === false) {
        return '';
    }
    $pos_nl = strpos($s, "\n");
    if ($pos_nl !== false) {
        $premiere_ligne = substr($s, 0, $pos_nl);
        if (strpos($premiere_ligne, '$2y$') === 0) {
            return substr($s, $pos_nl + 1);
        }
    }
    return $s;
}

/**
 * Écrit les données en préservant la ligne de hash bcrypt si présente.
 */
function ecrire_donnees_fichier($chemin, $donnees)
{
    $s = file_get_contents($chemin);
    if ($s !== false) {
        $pos_nl = strpos($s, "\n");
        if ($pos_nl !== false) {
            $premiere_ligne = substr($s, 0, $pos_nl);
            if (strpos($premiere_ligne, '$2y$') === 0) {
                file_put_contents($chemin, $premiere_ligne."\n".$donnees);
                return;
            }
        }
    }
    file_put_contents($chemin, $donnees);
}

function is_creationE_OK($professeur, $infos, $config)
{
    $erreur = is_loginE_OK($infos['loginE'], $config['loginE']);
    if ($erreur != '') {
        return $erreur;
    }
    $erreur = loginE_existe($professeur, $infos['loginE']);
    if ($erreur != '') {
        return $erreur;
    }
    $erreur = is_passwordE1_OK($infos['passwordE1'], $config['passwordE1']);
    if ($erreur != '') {
        return $erreur;
    }
    $erreur = is_passwordE2_OK($infos['passwordE2'], $infos['passwordE1'], $config['passwordE1']);
    if ($erreur != '') {
        return $erreur;
    }

    // Ajout du login élève dans le fichier professeur
    $dir = dir(DIR_PROFESSEURS);
    while ($nom = $dir->read()) {
        if (strpos($nom, 'eo_'.md5($professeur)) !== false) {
            $chemin = 'datas/professeurs/'.$nom;
            $donnees = lire_donnees_fichier($chemin);
            if (empty($donnees)) {
                $donnees = '|';
            }
            $donnees .= $infos['loginE'].'|';
            ecrire_donnees_fichier($chemin, $donnees);
            break;
        }
    }

    // Création du fichier élève au nouveau format
    $hash = password_hash($infos['passwordE1'], PASSWORD_BCRYPT);
    $chemin_eleve = 'datas/eleves/eo_'.md5($professeur).'_'.md5($infos['loginE']).'.txt';
    file_put_contents($chemin_eleve, $hash."\n".'0,3600#');
    return '';
}

function is_modificationE_OK($professeur, $infos, $config)
{
    $erreur = is_loginE_OK($infos['loginE'], $config['loginE']);
    if ($erreur != '') {
        return $erreur;
    }
    $erreur = is_passwordE1_OK($infos['passwordE1'], $config['passwordE1']);
    if ($erreur != '') {
        return $erreur;
    }
    $erreur = is_passwordE2_OK($infos['passwordE2'], $infos['passwordE1'], $config['passwordE1']);
    if ($erreur != '') {
        return $erreur;
    }

    // Mise à jour du login élève dans le fichier professeur
    $dir = dir(DIR_PROFESSEURS);
    while ($nom = $dir->read()) {
        if (strpos($nom, 'eo_'.md5($professeur)) !== false) {
            $chemin = 'datas/professeurs/'.$nom;
            $donnees = lire_donnees_fichier($chemin);
            $donnees = str_replace('|'.$infos['login'].'|', '|'.$infos['loginE'].'|', $donnees);
            ecrire_donnees_fichier($chemin, $donnees);
            break;
        }
    }

    // Mise à jour du fichier élève
    $dir = dir(DIR_ELEVES);
    while ($nom = $dir->read()) {
        if (strpos($nom, 'eo_'.md5($professeur).'_'.md5($infos['login'])) !== false) {
            $old_chemin = DIR_ELEVES.$nom;
            $donnees    = lire_donnees_fichier($old_chemin);
            $nouveau_hash = password_hash($infos['passwordE1'], PASSWORD_BCRYPT);
            $new_chemin = 'datas/eleves/eo_'.md5($professeur).'_'.md5($infos['loginE']).'.txt';
            file_put_contents($new_chemin, $nouveau_hash."\n".$donnees);
            if ($old_chemin !== $new_chemin) {
                unlink($old_chemin);
            }
            break;
        }
    }
    return '';
}

function suppressionE($professeur, $infos)
{
    // Suppression du login élève dans le fichier professeur
    $dir = dir(DIR_PROFESSEURS);
    while ($nom = $dir->read()) {
        if (strpos($nom, 'eo_'.md5($professeur)) !== false) {
            $chemin  = 'datas/professeurs/'.$nom;
            $donnees = lire_donnees_fichier($chemin);
            $donnees = str_replace('|'.$infos['loginE'].'|', '|', $donnees);
            ecrire_donnees_fichier($chemin, $donnees);
            break;
        }
    }

    // Suppression du fichier élève
    $dir = dir(DIR_ELEVES);
    while ($nom = $dir->read()) {
        if (strpos($nom, 'eo_'.md5($professeur).'_'.md5($infos['loginE'])) !== false) {
            unlink(DIR_ELEVES.$nom);
            break;
        }
    }
}

function suppressionP($professeur, $eleve, $infos)
{
    $dir = dir(DIR_ELEVES);
    while ($nom = $dir->read()) {
        if (strpos($nom, 'eo_'.md5($professeur).'_'.md5($eleve)) !== false) {
            $chemin  = DIR_ELEVES.$nom;
            $donnees = lire_donnees_fichier($chemin);
            if (!empty($infos['phrase'])) {
                $donnees = preg_replace('/^[^#]*/', '0,3600', $donnees);
                $donnees = str_replace('#'.stripcslashes($infos['phrase']).'#', '#', $donnees);
            } else {
                $donnees = '0,3600#';
            }
            ecrire_donnees_fichier($chemin, $donnees);
            break;
        }
    }
}

function ajoutP($professeur, $eleve, $phrase)
{
    $dir = dir(DIR_ELEVES);
    while ($nom = $dir->read()) {
        if (strpos($nom, 'eo_'.md5($professeur).'_'.md5($eleve)) !== false) {
            $chemin  = DIR_ELEVES.$nom;
            $donnees = lire_donnees_fichier($chemin);
            $donnees = preg_replace('/^[^#]*/', '0,3600', $donnees);
            $donnees .= stripcslashes(trim($phrase)).'#';
            ecrire_donnees_fichier($chemin, $donnees);
            break;
        }
    }
}

function majResultats($professeur, $eleve, $reussites, $chrono)
{
    $dir = dir(DIR_ELEVES);
    while ($nom = $dir->read()) {
        if (strpos($nom, 'eo_'.md5($professeur).'_'.md5($eleve)) !== false) {
            $chemin  = DIR_ELEVES.$nom;
            $donnees = lire_donnees_fichier($chemin);
            $donnees = preg_replace('/^[^#]*/', $reussites.','.$chrono, $donnees);
            ecrire_donnees_fichier($chemin, $donnees);
            break;
        }
    }
}

function phrase_existe($professeur, $eleve, $phrase)
{
    $dir = dir(DIR_ELEVES);
    while ($nom = $dir->read()) {
        if (strpos($nom, 'eo_'.md5($professeur).'_'.md5($eleve)) !== false) {
            $donnees = lire_donnees_fichier(DIR_ELEVES.$nom);
            if (strpos($donnees, '#'.trim($phrase).'#') !== false) {
                return 'Ce texte a déjà été saisi.';
            }
            break;
        }
    }
    return '';
}

function is_phrase_OK($phrase, $config)
{
    if (strlen($phrase) < 2 || $phrase == $config['phrase']) {
        return 'La saisie d\'un texte est obligatoire';
    }
    if (strlen($phrase) > $config['lgPhrase']) {
        return 'La phrase est trop longue.';
    }
    if (preg_match("#[^a-zA-Zàâéèêëïîôùûç.,;:!? '\-]#", stripcslashes($phrase))) {
        return 'Le texte contient des caratères incorrects.';
    }
    return '';
}

function lireEleves($professeur, $option = null)
{
    $dir = dir(DIR_PROFESSEURS);
    $donnees = '';
    while ($nom = $dir->read()) {
        if (strpos($nom, 'eo_'.md5($professeur)) !== false) {
            $donnees = lire_donnees_fichier('datas/professeurs/'.$nom);
            break;
        }
    }

    $l = array();
    if ($donnees != '' && $donnees != '|') {
        $l = explode('|', trim($donnees));
        array_pop($l);
        array_shift($l);
    }

    $r = array();
    foreach ($l as $eleve) {
        if ($option == 'pdf') {
            $r[] = array($eleve, lirePhrases($professeur, $eleve));
        } else {
            $stat = explode(',', lirePhrases($professeur, $eleve, 'stat'));
            $r[]  = array($eleve, $stat[0], $stat[1]);
        }
    }
    return $r;
}

function lirePhrases($professeur, $eleve, $option = null)
{
    $dir = dir(DIR_ELEVES);
    $donnees = '';
    while ($nom = $dir->read()) {
        if (strpos($nom, 'eo_'.md5($professeur).'_'.md5($eleve)) !== false) {
            $donnees = lire_donnees_fichier(DIR_ELEVES.$nom);
            break;
        }
    }

    $phrases = explode('#', trim($donnees));
    $stats   = array_shift($phrases);
    if (count($phrases) != 0) {
        array_pop($phrases);
    }
    if ($option == null) {
        return $phrases;
    }
    return $stats;
}
