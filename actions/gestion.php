<?php
function loginE_existe($professeur, $login) {
  $dir = dir('./datas/eleves/');
  while ($nom = $dir->read()) {
    if (strpos($nom,'eo_'.md5($professeur).'_'.md5($login)) === 0) {
      return 'Ce login est déjà utilisé.';
    }
  }
  return '';
}

function is_loginE_OK($login, $config) {
  if (empty($login) || $login == $config) return 'La saisie d\'un login est obligatoire';
  if (strlen($login) < 4) return 'Le login doit comprendre au moins 4 caractères';
  if (preg_match('#[^a-z0-9]#',$login)) return 'Le login est incorrect';
  return '';
}

function is_passwordE1_OK($password, $config) {
  if (empty($password) || $password == $config) return 'La saisie d\'un mot de passe est obligatoire';
  if (strlen($password) < 4) return 'Le mot de passe doit comprendre au moins 4 caractères';
  if (preg_match('#[^a-zA-Z0-9]#',$password)) return 'Le mot de passe est incorrect';
  return '';
}

function is_passwordE2_OK($password, $password1, $config) {
  if (empty($password) || $password == $config) return 'La confimation du mot de passe est obligatoire';
  if ($password != $password1) return 'La confirmation du mot de passe est erronée';
  return '';
}

function is_creationE_OK($professeur, $infos, $config) {
  $erreur = is_loginE_OK($infos['loginE'],$config['loginE']);
  if  ($erreur != '') return $erreur;
  $erreur = loginE_existe($professeur, $infos['loginE']);
  if  ($erreur != '') return $erreur;
  $erreur = is_passwordE1_OK($infos['passwordE1'],$config['passwordE1']);
  if  ($erreur != '') return $erreur;
  $erreur = is_passwordE2_OK($infos['passwordE2'],$infos['passwordE1'],$config['passwordE1']);
  if  ($erreur != '') return $erreur;

  $dir = dir('./datas/professeurs/');
  while ($nom = $dir->read()) {
    if (strpos($nom,'eo_'.md5($professeur))!== false) {
      $fp = fopen('datas/professeurs/'.$nom,'a+');
      $fl = filesize('datas/professeurs/'.$nom);
      $c = ($fl==0) ? '|' : '';
      fwrite($fp,$c.$infos['loginE'].'|');
      fclose($fp);
      break;
    }
  }
  file_put_contents('datas/eleves/eo_'.md5($professeur).'_'.md5($infos['loginE']).'_'.md5($infos['passwordE1']).'.txt','0,3600#');
  return '';
}
function is_modificationE_OK($professeur, $infos, $config)
{
  $erreur = is_loginE_OK($infos['loginE'],$config['loginE']);
  if  ($erreur != '') return $erreur;
  $erreur = is_passwordE1_OK($infos['passwordE1'],$config['passwordE1']);
  if  ($erreur != '') return $erreur;
  $erreur = is_passwordE2_OK($infos['passwordE2'],$infos['passwordE1'],$config['passwordE1']);
  if  ($erreur != '') return $erreur;

  $dir = dir('./datas/professeurs/');
  while ($nom = $dir->read()) {
    if (strpos($nom,'eo_'.md5($professeur))!== false) {
      $s = file_get_contents('datas/professeurs/'.$nom);
      $s = str_replace('|'.$infos['login'].'|','|'.$infos['loginE'].'|',$s);
      file_put_contents('datas/professeurs/'.$nom,$s);
      break;
    }
  }
  $dir = dir('./datas/eleves/');
  while ($nom = $dir->read()) {
    if (strpos($nom,'eo_'.md5($professeur).'_'.md5($infos['login']))!== false) {
      $old_name = 'datas/eleves/'.$nom;
      $new_name = 'datas/eleves/eo_'.md5($professeur).'_'.md5($infos['loginE']).'_'.md5($infos['passwordE1']).'.txt';
      rename($old_name, $new_name);
      break;
    }
  }
  return '';
}
function suppressionE($professeur, $infos)
{
  $dir = dir('./datas/professeurs/');
  while ($nom = $dir->read()) {
    if (strpos($nom,'eo_'.md5($professeur))!== false) {
      $s = file_get_contents('datas/professeurs/'.$nom);
      $s = str_replace('|'.$infos['loginE'].'|','|',$s);
      file_put_contents('datas/professeurs/'.$nom,$s);
      break;
    }
  }
  $dir = dir('./datas/eleves/');
  while ($nom = $dir->read()) {
    if (strpos($nom,'eo_'.md5($professeur).'_'.md5($infos['loginE']))!== false) {
      unlink('datas/eleves/'.$nom);
      break;
    }
  }
}
function suppressionP($professeur, $eleve, $infos)
{
  $dir = dir('./datas/eleves/');
  while ($nom = $dir->read()) {
    if (strpos($nom,'eo_'.md5($professeur).'_'.md5($eleve))!== false) {
      $s = file_get_contents('datas/eleves/'.$nom);
      if (!empty($infos['phrase'])) {
        $s = preg_replace('/^[^#]*/','0,3600',$s);
        $s = str_replace('#'.stripcslashes($infos['phrase']).'#','#',$s);
      } else {
        $s='0,3600#';
      }
      file_put_contents('datas/eleves/'.$nom,$s);
      break;
    }
  }
}
function ajoutP($professeur, $eleve, $phrase)
{
  $dir = dir('./datas/eleves/');
  while ($nom = $dir->read()) {
    if (strpos($nom,'eo_'.md5($professeur).'_'.md5($eleve))!== false) {
      $s = file_get_contents('datas/eleves/'.$nom);
      $s = preg_replace('/^[^#]*/','0,3600',$s);
      $s .= stripcslashes(trim($phrase)).'#';
      file_put_contents('datas/eleves/'.$nom,$s);
      break;
    }
  }
}
function majResultats($professeur, $eleve, $reussites, $chrono)
{
  $dir = dir('./datas/eleves/');
  while ($nom = $dir->read()) {
    if (strpos($nom,'eo_'.md5($professeur).'_'.md5($eleve))!== false) {
      $s = file_get_contents('datas/eleves/'.$nom);
      $s = preg_replace('/^[^#]*/',$reussites.','.$chrono,$s);
      file_put_contents('datas/eleves/'.$nom,$s);
      break;
    }
  }
}
function phrase_existe($professeur, $eleve, $phrase)
{
  $dir = dir('./datas/eleves/');
  while ($nom = $dir->read()) {
    if (strpos($nom,'eo_'.md5($professeur).'_'.md5($eleve))!== false) {
      $s = file_get_contents('datas/eleves/'.$nom);
      if (strpos($s,'#'.trim($phrase).'#')!==false) return 'Ce texte a déjà été saisi.';
      break;
    }
  }
  return '';
}
function is_phrase_OK($phrase, $config)
{
  if (strlen($phrase) < 2 || $phrase == $config['phrase']) return 'La saisie d\'un texte est obligatoire';
  if (strlen($phrase) > $config['lgPhrase']) return 'La phrase est trop longue.';
  if (preg_match("#[^a-zA-Zàâéèêëïîôùûç.,;:!? '\-]#",stripcslashes($phrase))) return 'Le texte contient des caratères incorrects.';
  return '';
}
function lireEleves($professeur,$option=null)
{
  $dir = dir('./datas/professeurs/');
  $s='';
  while ($nom = $dir->read()) {
    if (strpos($nom,'eo_'.md5($professeur))!== false) {
      $s = file_get_contents('datas/professeurs/'.$nom);
/*
      if (!preg_match("/\A\|/",$s)) {
        file_put_contents('datas/professeurs/old_'.$nom, $s);
        $s = preg_replace("/\A[^\|]*\|/","|",$s);
        file_put_contents('datas/professeurs/'.$nom, $s);
      }
*/
      break;
    }
  }
  $l=array();
  if ($s != '' && $s != '|') {
    $l = explode('|',trim($s));
    array_pop($l);
    array_shift($l);
  }
  $r=array();
  foreach ($l as $eleve) {
    if ($option=='pdf') {
      $r[]=array($eleve,lirePhrases($professeur,$eleve));
    } else {
      $stat = explode(',',lirePhrases($professeur,$eleve,'stat'));
      $r[]=array($eleve,$stat[0],$stat[1]);
    }
  }
  return $r;
}
function lirePhrases($professeur,$eleve, $option=null)
{
  $dir = dir('./datas/eleves/');
  $s='';
  while ($nom = $dir->read()) {
    if (strpos($nom,'eo_'.md5($professeur).'_'.md5($eleve))!== false) {
      $s = file_get_contents('datas/eleves/'.$nom);
      break;
    }
  }
  $phrases = explode('#',trim($s));
  $stats = array_shift($phrases);
  if (count($phrases) != 0) array_pop($phrases);
  if ($option==null) return $phrases;
  return $stats;
}