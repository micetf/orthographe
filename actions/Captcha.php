<?php 
session_start();

class Captcha
{
  var $cle;
  var $image;
  
  function Captcha()
  {
    $choix = array();
    for ($c=0;$c<10;$c++) $choix[] = $c;
    for ($c=97;$c<122;$c++) $choix[] = chr($c);
    
    $largeur = 130;
    $hauteur = 40;
    $image = imagecreate($largeur, $hauteur);
    $rose = imagecolorallocate($image,255,0,255);
    $noir = imagecolorallocate($image,0,0,0);
    
    $texte = "";
    for ($i=0;$i<6;$i++) {
      shuffle($choix);
      $texte .= $choix[0];
      imagestring($image,5,10+(20*$i),mt_rand(5,15),$choix[0], $noir);
    }
    $this->image = $image;
    $this->cle = md5($texte);
  }
}
$captcha = new Captcha();
$_SESSION['cle'] = $captcha->cle;
header("Content-Type: image/png");
imagepng($captcha->image);
imagedestroy($captcha->image);