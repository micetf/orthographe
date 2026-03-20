<?php

function is_emailP_OK($email, $config)
{
    if (empty($email) || $email == $config) {
        return 'La saisie d\'une adresse de messagerie est obligatoire.';
    }
    if (!preg_match('#^[-+.\w]{1,64}@[-.\w]{1,64}\.[-.\w]{2,6}$#i', $email)) {
        return 'L\'adresse de messagerie est invalide.';
    }

    $dir = dir(DIR_PROFESSEURS);
    while ($nom = $dir->read()) {
        if (strpos($nom, md5($email).'.txt') !== false) {
            return 'Vous avez déjà un compte utilisateur.';
        }
    }
    $dir = dir(DIR_DEMANDES);
    while ($nom = $dir->read()) {
        if (strpos($nom, md5($email).'.txt') !== false) {
            unlink('datas/demandes/'.$nom);
        }
    }
    return '';
}

function is_loginP_OK($login, $config)
{
    if (empty($login) || $login == $config) {
        return 'La saisie d\'un login est obligatoire';
    }
    if (strlen($login) < 4) {
        return 'Le login doit comprendre au moins 4 caractères';
    }
    if (preg_match('#[^a-z0-9]#', $login)) {
        return 'Le login est incorrect';
    }
    $dir = dir(DIR_PROFESSEURS);
    while ($nom = $dir->read()) {
        if (strpos($nom, 'eo_'.md5($login)) !== false) {
            return 'Ce login est déjà utilisé.';
        }
    }
    return '';
}

function is_passwordP1_OK($password, $config)
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

function is_passwordP2_OK($password, $password1, $config)
{
    if (empty($password) || $password == $config) {
        return 'La confimation du mot de passe est obligatoire';
    }
    if ($password != $password1) {
        return 'La confirmation du mot de passe est erronée';
    }
    return '';
}

function is_captchaP_OK($captcha, $cle)
{
    if (empty($captcha)) {
        return 'La saisie du captcha est obligatoire';
    }
    if (md5($captcha) != $cle) {
        return 'Erreur dans la saisie du capcha';
    }
    return '';
}

function is_inscriptionP_OK($infos, $config)
{
    $erreur = is_emailP_OK($infos['email'], $config['email']);
    if ($erreur != '') {
        return $erreur;
    }
    $erreur = is_loginP_OK($infos['loginP'], $config['loginP']);
    if ($erreur != '') {
        return $erreur;
    }
    $erreur = is_passwordP1_OK($infos['passwordP1'], $config['passwordP1']);
    if ($erreur != '') {
        return $erreur;
    }
    $erreur = is_passwordP2_OK($infos['passwordP2'], $infos['passwordP1'], $config['passwordP1']);
    if ($erreur != '') {
        return $erreur;
    }
    $erreur = is_captchaP_OK($infos['captcha'], $infos['cle']);
    if ($erreur != '') {
        return $erreur;
    }

    // Nouveau format : eo_<md5login>_<md5email>.txt
    // Le hash bcrypt sera écrit dans le contenu lors de la validation
    $nomFichierDemande = 'eo_'.md5($infos['loginP']).'_'.md5($infos['email']).'.txt';
    $hash = password_hash($infos['passwordP1'], PASSWORD_BCRYPT);
    file_put_contents('datas/demandes/'.$nomFichierDemande, $hash."\n".send_mail($infos));
    return '';
}

function send_mail($infos)
{
    $retour = implode("\r\n", $infos)."\r\n";

    // Nouveau lien de validation sans paramètre p (mot de passe)
    $lienValidation  = 'http://'.$_SERVER['SERVER_NAME'].'/orthographe/validation.php?';
    $lienValidation .= 'l='.md5($infos['loginP']);
    $lienValidation .= '&e='.md5($infos['email']);
    $retour .= $lienValidation;

    $mail = $infos['email'];

    if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $mail)) {
        $passage_ligne = "\r\n";
    } else {
        $passage_ligne = "\n";
    }

    $message_txt  = "Bonjour, vous avez enregistré une demande d'inscription sur http://micetf.fr/orthographe. ".$passage_ligne;
    $message_txt .= "Afin d'activer votre abonnement, nous vous remercions de confirmer la bonne réception de ce courriel en cliquant sur le lien suivant : ".$passage_ligne;
    $message_txt .= $lienValidation.$passage_ligne;
    $message_txt .= "En cliquant sur ce lien, vous accèderez immédiatement à votre espace. Merci pour votre confiance.".$passage_ligne;
    $message_txt .= "Très cordialement,".$passage_ligne."MiCetF.";

    $message_html  = '<html><body><p>Bonjour,</p>';
    $message_html .= '<p>Vous avez enregistré une demande d\'inscription sur http://micetf.fr/orthographe.</p>';
    $message_html .= '<p>Afin d\'activer votre abonnement, nous vous remercions de confirmer la bonne réception de ce courriel en cliquant sur le lien ci-dessous :</p>';
    $message_html .= '<p style="text-align:center;"><a href="'.$lienValidation.'">Activer mon abonnement</a></p>';
    $message_html .= '<p>En cliquant sur ce lien, vous accèderez immédiatement à votre espace.</p>';
    $message_html .= '<p>Merci pour votre confiance.</p>';
    $message_html .= '<p>Très cordialement, à bientôt sur l\'espace d\'Entraînement Orthographique.</p>';
    $message_html .= '<p><a href="http://www.micetf.fr"><img src="http://'.$_SERVER['SERVER_NAME'].'/common/images/micetf.png" alt="MiCetF" title="Des Outils Pour La Classe"/></a></p></body></html>';

    $boundary = "-----=".md5(rand());
    $sujet    = 'Confirmation d\'inscription à l\'espace d\'Entraînement Orthographique.';

    $header  = "From: \"MiCetF Webmaster\"<webmaster@micetf.fr>".$passage_ligne;
    $header .= "Reply-to: \"MiCetF Webmaster\" <webmaster@micetf.fr>".$passage_ligne;
    $header .= "MIME-Version: 1.0".$passage_ligne;
    $header .= "Content-Type: multipart/alternative;".$passage_ligne." boundary=\"$boundary\"".$passage_ligne;

    $message  = $passage_ligne."--".$boundary.$passage_ligne;
    $message .= "Content-Type: text/plain; charset=\"UTF-8\"".$passage_ligne;
    $message .= "Content-Transfer-Encoding: 8bit".$passage_ligne;
    $message .= $passage_ligne.$message_txt.$passage_ligne;
    $message .= $passage_ligne."--".$boundary.$passage_ligne;
    $message .= "Content-Type: text/html; charset=\"UTF-8\"".$passage_ligne;
    $message .= "Content-Transfer-Encoding: 8bit".$passage_ligne;
    $message .= $passage_ligne.$message_html.$passage_ligne;
    $message .= $passage_ligne."--".$boundary."--".$passage_ligne;
    $message .= $passage_ligne."--".$boundary."--".$passage_ligne;

    mail($mail, $sujet, $message, $header);

    return $retour;
}

function is_modificationP_OK($infos, $session, $config)
{
    if ($infos['loginP'] == $session['loginP']) {
        $erreur = is_passwordP1_OK($infos['passwordP1'], $config['passwordP1']);
        if ($erreur != '') {
            return $erreur;
        }
        $erreur = is_passwordP2_OK($infos['passwordP2'], $infos['passwordP1'], $config['passwordP1']);
        if ($erreur != '') {
            return $erreur;
        }
    } else {
        $erreur = is_loginP_OK($infos['loginP'], $config['loginP']);
        if ($erreur != '') {
            return $erreur;
        }
        $erreur = is_passwordP1_OK($infos['passwordP1'], $config['passwordP1']);
        if ($erreur != '') {
            return $erreur;
        }
        $erreur = is_passwordP2_OK($infos['passwordP2'], $infos['passwordP1'], $config['passwordP1']);
        if ($erreur != '') {
            return $erreur;
        }
    }

    // Mise à jour du fichier professeur
    $dir = dir(DIR_PROFESSEURS);
    while ($nom = $dir->read()) {
        if (strpos($nom, 'eo_'.md5($session['loginP'])) !== false) {
            $ancien_chemin = 'datas/professeurs/'.$nom;
            $contenu       = file_get_contents($ancien_chemin);
            if ($contenu === false) {
                $contenu = '|';
            }

            // Extraire le hash email depuis le nom de fichier
            $sans_ext = substr($nom, 0, -4);
            $parties  = explode('_', $sans_ext);
            $email_hash = end($parties);

            // Nouveau nom avec nouveau login
            $nouveau_nom    = 'eo_'.md5($infos['loginP']).'_'.$email_hash.'.txt';
            $nouveau_chemin = 'datas/professeurs/'.$nouveau_nom;

            // Nouveau hash bcrypt
            $nouveau_hash = password_hash($infos['passwordP1'], PASSWORD_BCRYPT);

            // Extraire les données (sans l'éventuelle ligne de hash existante)
            $pos_nl = strpos($contenu, "\n");
            if ($pos_nl !== false) {
                $premiere_ligne = substr($contenu, 0, $pos_nl);
                if (strpos($premiere_ligne, '$2y$') === 0) {
                    $donnees = substr($contenu, $pos_nl + 1);
                } else {
                    $donnees = $contenu;
                }
            } else {
                $donnees = $contenu;
            }

            file_put_contents($nouveau_chemin, $nouveau_hash."\n".$donnees);
            if ($ancien_chemin !== $nouveau_chemin) {
                unlink($ancien_chemin);
            }

            // Mettre à jour les fichiers élèves si le login professeur change
            if ($infos['loginP'] !== $session['loginP']) {
                $dir2 = dir(DIR_ELEVES);
                while ($nom2 = $dir2->read()) {
                    if (strpos($nom2, 'eo_'.md5($session['loginP'])) !== false) {
                        $ancien_eleve = 'datas/eleves/'.$nom2;
                        $nouveau_eleve = 'datas/eleves/eo_'.md5($infos['loginP']).substr($nom2, strlen('eo_'.md5($session['loginP'])));
                        rename($ancien_eleve, $nouveau_eleve);
                    }
                }
            }
            break;
        }
    }
    return '';
}
