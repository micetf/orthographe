<?php
function is_emailP_OK($email, $config) {
  if (empty($email) || $email == $config) return 'La saisie d\'une adresse de messagerie est obligatoire.';
  if (!preg_match('#^[-+.\w]{1,64}@[-.\w]{1,64}\.[-.\w]{2,6}$#i',$email)) return 'L\'adresse de messagerie est invalide.';

  $dir = dir('./datas/professeurs/');
  while ($nom = $dir->read()) {
    if (strpos($nom,md5($email).'.txt')!== false) {
      return 'Vous avez déjà un compte utilisateur.';
    }
  }
  $dir = dir('./datas/demandes/');
  while ($nom = $dir->read()) {
    if (strpos($nom,md5($email).'.txt')!== false) {
      unlink('datas/demandes/'.$nom);
    }
  }
  return '';
}

function is_loginP_OK($login, $config) {
  if (empty($login) || $login == $config) return 'La saisie d\'un login est obligatoire';
  if (strlen($login) < 4) return 'Le login doit comprendre au moins 4 caractères';
  if (preg_match('#[^a-z0-9]#',$login)) return 'Le login est incorrect';
  $dir = dir('./datas/professeurs/');
  while ($nom = $dir->read()) {
    if (strpos($nom,'eo_'.md5($login))!== false) {
      return 'Ce login est déjà utilisé.';
    }
  }
  return '';
}

function is_passwordP1_OK($password, $config) {
  if (empty($password) || $password == $config) return 'La saisie d\'un mot de passe est obligatoire';
  if (strlen($password) < 4) return 'Le mot de passe doit comprendre au moins 4 caractères';
  if (preg_match('#[^a-zA-Z0-9]#',$password)) return 'Le mot de passe est incorrect';
  return '';
}

function is_passwordP2_OK($password, $password1, $config) {
  if (empty($password) || $password == $config) return 'La confimation du mot de passe est obligatoire';
  if ($password != $password1) return 'La confirmation du mot de passe est erronée';
  return '';
}

function is_captchaP_OK($captcha, $cle) {
  if (empty($captcha)) return 'La saisie du captcha est obligatoire';
  if (md5($captcha) != $cle) return 'Erreur dans la saisie du capcha';
  return '';
}

function is_inscriptionP_OK($infos, $config) {
  $erreur = is_emailP_OK($infos['email'],$config['email']);
  if  ($erreur != '') return $erreur;
  $erreur = is_loginP_OK($infos['loginP'],$config['loginP']);
  if  ($erreur != '') return $erreur;
  $erreur = is_passwordP1_OK($infos['passwordP1'],$config['passwordP1']);
  if  ($erreur != '') return $erreur;
  $erreur = is_passwordP2_OK($infos['passwordP2'],$infos['passwordP1'],$config['passwordP1']);
  if  ($erreur != '') return $erreur;
  $erreur = is_captchaP_OK($infos['captcha'],$infos['cle']);
  if  ($erreur != '') return $erreur;

  $nomFichierDemande='eo_'.md5($infos['loginP']).'_'.md5($infos['passwordP1']).'_'.md5($infos['email']).'.txt';
#  touch('datas/demandes/'.$nomFichierDemande);
  file_put_contents('datas/demandes/'.$nomFichierDemande,send_mail($infos));
  return '';
}

function envoyer_mail($infos) {
  $retour=implode("\r\n",$infos)."\r\n";
  $destinataire = $infos['email'];
  $expediteur = 'webmaster@micetf.fr';
  $sujet = 'Confirmation d\'inscription à l\'espace d\'Entraînement Orthographique.';

  $micetf = $_SERVER['SERVER_NAME'];
  $message = '<html><body><p>Bonjour,</p>';
  $message .= '<p>'.htmlentities('Vous avez enregistré une demande d\'inscription sur http://www.micetf.fr/orthographe.', ENT_QUOTES,'UTF-8').'</p>';
  $message .= '<p>'.htmlentities('Afin d\'activer votre abonnement, nous vous remercions de confirmer la bonne réception de ce courriel en cliquant sur le lien ci-dessous :', ENT_QUOTES,'UTF-8').'</p>';

  $lienValidation='http://'.$micetf.'/orthographe/validation.php?';
  $lienValidation .= 'l='. md5($infos['loginP']);
  $lienValidation .= '&p='. md5($infos['passwordP1']);
  $lienValidation .= '&e='. md5($infos['email']);
  $retour.=$lienValidation;

  $message .= '<p style="text-align:center;"><a href="'.$lienValidation.'">Activer mon abonnement</a></p>';
  $message .= '<p>'.htmlentities('En cliquant sur ce lien, vous accéderez immédiatement à votre espace.', ENT_QUOTES,'UTF-8').'</p>';
  $message .= '<p>'.htmlentities('Merci pour votre confiance.', ENT_QUOTES,'UTF-8').'</p>';
  $message .= '<p>'.htmlentities('Très cordialement, à bientôt sur l\'espace d\'Entraînement Orthographique.', ENT_QUOTES,'UTF-8').'</p>';
  $message .= '<p><a href="http://www.micetf.fr"><img src="http://'.$micetf.'/common/images/micetf.png" alt="MiCetF" title="Des Outils Pour La Classe"/></a></p></body></html>';
  $content = "From: $expediteur\r\nReply-To: $expediteur\r\nContent-Type: text/html\r\n";
  mail($destinataire, $sujet,  $message, $content);


  return $retour;
}

function send_mail($infos) {
  $retour=implode("\r\n",$infos)."\r\n";

  $lienValidation='http://'.$_SERVER['SERVER_NAME'].'/orthographe/validation.php?';
  $lienValidation .= 'l='. md5($infos['loginP']);
  $lienValidation .= '&p='. md5($infos['passwordP1']);
  $lienValidation .= '&e='. md5($infos['email']);
  $retour.=$lienValidation;

  $mail = $infos['email'];

  if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $mail)) {
    $passage_ligne = "\r\n";
  } else {
    $passage_ligne = "\n";
  }

  $message_txt = "Bonjour, vous avez enregistré une demande d'inscription sur http://micetf.fr/orthographe. ".$passage_ligne;
  $message_txt .= "Afin d'activer votre abonnement, nous vous remercions de cinfirmer la bonne réception de ce courriel en cliquant sur le lien suivant : ".$passage_ligne;
  $message_txt .= $lienValidation.$passage_ligne;
  $message_txt .= "En cliquant sur ce lien, vous accèderez immédiatement à votre espace. Merci pour votre confiance.".$passage_ligne;
  $message_txt .= "Très cordialement,".$passage_ligne."MiCetF.";

  $message_html = '<html><body><p>Bonjour,</p>';
  $message_html .= '<p>Vous avez enregistré une demande d\'inscription sur http://micetf.fr/orthographe.</p>';
  $message_html .= '<p>Afin d\'activer votre abonnement, nous vous remercions de confirmer la bonne réception de ce courriel en cliquant sur le lien ci-dessous :</p>';

  $message_html .= '<p style="text-align:center;"><a href="'.$lienValidation.'">Activer mon abonnement</a></p>';
  $message_html .= '<p>En cliquant sur ce lien, vous accéderez immédiatement à votre espace.</p>';
  $message_html .= '<p>Merci pour votre confiance.</p>';
  $message_html .= '<p>Très cordialement, à bientôt sur l\'espace d\'Entraînement Orthographique.</p>';
  $message_html .= '<p><a href="http://www.micetf.fr"><img src="http://'.$_SERVER['SERVER_NAME'].'/common/images/micetf.png" alt="MiCetF" title="Des Outils Pour La Classe"/></a></p></body></html>';

  $boundary = "-----=".md5(rand());

  $sujet = 'Confirmation d\'inscription à l\'espace d\'Entraînement Orthographique.';

  $header = "From: \"MiCetF Webmaster\"<webmaster@micetf.fr>".$passage_ligne;
  $header.= "Reply-to: \"MiCetF Webmaster\" <webmaster@micetf.fr>".$passage_ligne;
  $header.= "MIME-Version: 1.0".$passage_ligne;
  $header.= "Content-Type: multipart/alternative;".$passage_ligne." boundary=\"$boundary\"".$passage_ligne;

  //==========

  //=====Création du message.
  $message = $passage_ligne."--".$boundary.$passage_ligne;
  //=====Ajout du message au format texte.
  $message.= "Content-Type: text/plain; charset=\"UTF-8\"".$passage_ligne;
  $message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
  $message.= $passage_ligne.$message_txt.$passage_ligne;
  //==========
  $message.= $passage_ligne."--".$boundary.$passage_ligne;
  //=====Ajout du message au format HTML
  $message.= "Content-Type: text/html; charset=\"UTF-8\"".$passage_ligne;
  $message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
  $message.= $passage_ligne.$message_html.$passage_ligne;
  //==========
  $message.= $passage_ligne."--".$boundary."--".$passage_ligne;
  $message.= $passage_ligne."--".$boundary."--".$passage_ligne;
  //==========

  //=====Envoi de l'e-mail.
  mail($mail,$sujet,$message,$header);
  //==========

  return $retour;
}

function is_modificationP_OK($infos, $session, $config) {
  if ($infos['loginP'] == $session['loginP']) {
    $erreur = is_passwordP1_OK($infos['passwordP1'],$config['passwordP1']);
    if  ($erreur != '') return $erreur;
    $erreur = is_passwordP2_OK($infos['passwordP2'],$infos['passwordP1'],$config['passwordP1']);
    if  ($erreur != '') return $erreur;
  } else {
    $erreur = is_loginP_OK($infos['loginP'],$config['loginP']);
    if  ($erreur != '') return $erreur;
    $erreur = is_passwordP1_OK($infos['passwordP1'],$config['passwordP1']);
    if  ($erreur != '') return $erreur;
    $erreur = is_passwordP2_OK($infos['passwordP2'],$infos['passwordP1'],$config['passwordP1']);
    if  ($erreur != '') return $erreur;
  }

  $dir = dir('./datas/professeurs/');
  while ($nom = $dir->read()) {
    if (strpos($nom,'eo_'.md5($session['loginP']))!== false) {
      $new_nom = preg_replace('#eo_[^_]*_[^_]*#','eo_'.md5($infos['loginP']).'_'.md5($infos['passwordP1']),$nom);
      rename('datas/professeurs/'.$nom,'datas/professeurs/'.$new_nom);
      break;
    }
  }
  $dir = dir('./datas/eleves/');
  while ($nom = $dir->read()) {
    if (strpos($nom,'eo_'.md5($session['loginP']))!== false) {
      $new_nom = preg_replace('#eo_(.*)_(.*)_(.*)#','eo_'.md5($infos['loginP'])."_$2_$3",$nom);
      rename('datas/eleves/'.$nom,'datas/eleves/'.$new_nom);
    }
  }
  return '';
}
