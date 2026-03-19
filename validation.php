<?php
$demandes = './datas/demandes/';
$professeurs = './datas/professeurs/';
$accueil = preg_replace('#validation#','index',$_SERVER['PHP_SELF']);

if (isset($_GET['l']) && isset($_GET['p']) && isset($_GET['e'])) {

    $lpe = '_'.$_GET['l'].'_'.$_GET['p'].'_'.$_GET['e'].'.txt';

    if (file_exists($demandes.'eo'.$lpe)) {

        rename($demandes.'eo'.$lpe,$professeurs.'eo'.$lpe);
        file_put_contents($professeurs.'eo'.$lpe,'|');
        header('Location: '.$accueil.'?bg=validationOK');
        exit(0);

    }
    if (file_exists($professeurs.'eo'.$lpe)) {

        header('Location: '.$accueil.'?bg=validationOK');
        exit(0);
    }
}
header('Location: '.$accueil.'?bg=validationKO');