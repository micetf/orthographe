<?php

// ============================================================
// Configuration centrale de l'application
// ============================================================

// Chemins des répertoires de données
define('DIR_PROFESSEURS', './datas/professeurs/');
define('DIR_ELEVES', './datas/eleves/');
define('DIR_DEMANDES', './datas/demandes/');
define('DIR_STATS', './datas/stats.txt');
define('DIR_LOG', './datas/log.txt');

// Contraintes métier
define('LG_PHRASE_MAX', 140);
define('LOGIN_MIN_LENGTH', 4);
define('PASSWORD_MIN_LENGTH', 4);

// Valeurs par défaut des champs de formulaire
define('DEFAULT_LOGIN_P', 'login');
define('DEFAULT_PASSWORD_P', '          ');
define('DEFAULT_LOGIN_E', 'login');
define('DEFAULT_PASSWORD_E', '          ');
define('DEFAULT_EMAIL', 'identifiant@domaine.fr');
define('DEFAULT_PHRASE', 'mot(s) ou phrase(s) à mémoriser');
