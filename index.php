<?php

/* *********************************************************/
/* Produit Piston : Gestion Matos et Membres ***************/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* index.php ***********************************************/
/* Fichier principal de l'application, redirige la requete */
/* vers la bonne action ************************************/
/* *********************************************************/
/* Dernière modification : le 06/11/14 *********************/
/* *********************************************************/


//Quelques constantes pour les routes et les inclusions
define('DIR', dirname(__FILE__).'/');
define('DIR_APP', substr(dirname($_SERVER['PHP_SELF']), -1) == '/' ? '' : dirname($_SERVER['PHP_SELF']));
if (!defined('PATH'))
	define('PATH', preg_replace('`^'.DIR_APP.'/`', '', $_SERVER['REQUEST_URI']));
define('DIR_ASSETS', 'assets');
define('IS_ASSET', preg_match('`^'.DIR_ASSETS.'`', PATH));


//La requete est-elle un fichier de style?
if (IS_ASSET || PATH == 'favicon.ico')
	die(require DIR.'actions/retournerAsset.php');


//Est-ce le fichier robots.txt qui est demandé?
if (PATH == 'robots.txt') {
	header('Content-type:text/plain');
	die(readfile(DIR.'robots.txt'));
}


//Peut-etre est-ce le fichier sitemap.xml
if (PATH == 'sitemap.xml') {
	header('Content-type:application/xml');
	die(readfile(DIR.'sitemap.xml'));
}


//Inclusion des fichiers nécessaires au fonctionnement général du projet
require dirname(__FILE__).'/includes/_includes.php';


//On cherche si une des routes correspond à l'adresse donnée
foreach ($routes as $route => $action) {
	if (preg_match_all('`^'.$route.'$`', PATH, $args)) {
		$match = $action;
		break;
	}
}


//Si non, on affiche une erreur sommaire
if (!isset($match) ||
	!file_exists(DIR.'actions/'.$match))
	die(require DIR.'templates/_error.php');


//Si oui, on inclut le fichier controlleur
else
	require DIR.'actions/'.$match;

