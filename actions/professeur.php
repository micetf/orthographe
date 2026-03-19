<?php

function logErreurConnexion($login)
{
    $s = file_get_contents('./datas/log.txt');
    file_put_contents('./datas/log.txt', $s.PHP_EOL.date('Y-m-d H:i:s').' LOGIN_ECHOUE: '.$login);
}

function is_professeur($infos)
{
    if (!empty($infos['loginP']) && !empty($infos['passwordP'])) {
        $dir = dir('./datas/professeurs/');
        while ($nom = $dir->read()) {
            if (strpos($nom, 'eo_'.md5($infos['loginP']).'_'.md5($infos['passwordP']).'_') === 0) {
                touch('./datas/professeurs/'.$nom);
                return true;
            } elseif (strpos($nom, 'eo_'.md5($infos['loginP']).'_'.md5(strtolower($infos['passwordP'])).'_') === 0) {
                touch('./datas/professeurs/'.$nom);
                return true;
            }
        }
    }
    logErreurConnexion($infos['loginP']);
    return false;
}
