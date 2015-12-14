<?php

/* *********************************************************/
/* Produit Piston : Gestion Matos et Membres ***************/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* includes/_constantes.php ********************************/
/* Toutes les constantes du site ***************************/
/* *********************************************************/
/* Dernière modification : le 06/11/14 *********************/
/* *********************************************************/


//Constantes non scalaires définissant les admins et super-admins de la partie
$SUPER_ADMINS =  				array('rmoulin');
$ADMINS = 						array();


//Constantes base de données
//Constantes pour les essais en local
if (!defined('LOCAL'))
	define('LOCAL',				$_SERVER['SERVER_NAME'] == 'localhost');

if (LOCAL) {
	define('DB_HOST', 			'127.0.0.1');
	define('DB_NAME', 			'gala');
	define('DB_USER', 			'root');
	define('DB_PASS', 			'');
}

//On est sur le serveur de production
else {
	define('DB_HOST', 			'');
	define('DB_NAME', 			'');
	define('DB_USER', 			'');
	define('DB_PASS', 			'');
}

//Debug
define('DEBUG_ACTIVE_LOCAL',	true);
define('DEBUG_ACTIVE_ONLINE',	true);
define('DEBUG_ACTIVE',			!LOCAL && DEBUG_ACTIVE_ONLINE || LOCAL && DEBUG_ACTIVE_LOCAL);


//Configuration pour l'envoi d'emails
define('EMAIL_ACTIVE',			false);
define('EMAIL_SMTP', 			'smtp.ec-lyon.fr');
define('EMAIL_PORT',			587);
define('EMAIL_SECURE',			'tls');
define('EMAIL_AUTH',			true);
define('EMAIL_USER',			''); //Login Centralien
define('EMAIL_PASS',			''); //Mot de passe Centralien
define('EMAIL_MAIL',			'contact@example.com'); //Adresse affichée
define('EMAIL_NAME',			'Email Model');
define('EMAIL_FORCED', 			false); 


//Configuration d'application
define('SITE_ENABLED_LOCAL',	true);
define('SITE_ENABLED_ONLINE',	true);
define('SITE_ENABLED',			!LOCAL && SITE_ENABLED_ONLINE || LOCAL && SITE_ENABLED_LOCAL);
define('APP_ONLYONENTER', 		true);
define('APP_MAXAUTOCOMPLETE', 	100);

