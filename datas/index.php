<?php
$accueil = preg_replace('#datas/#','',$_SERVER['PHP_SELF']);
header('Location: '.$accueil);   
