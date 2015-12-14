<?php

/* *********************************************************/
/* Produit Piston : Gestion Matos et Membres ***************/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* includes/_routes.php ************************************/
/* Déclaration de toutes les routes de l'application *******/
/* *********************************************************/
/* Dernière modification : le 06/11/14 *********************/
/* *********************************************************/


define('GET', '/?(?:\?.*)?');

$routes = !defined('SITE_ENABLED') || !SITE_ENABLED ? array(
	'' 									=> 'public/accueil.php',
) : array(
	'' 									=> 'public/accueil.php',
	'accueil' 							=> 'public/accueil.php',
	'vendeurs'							=> 'public/vendeurs.php',
	'adherents'							=> 'prive/adherents.php',
	'location'							=> 'prive/location.php',
	'location/retour'					=> 'prive/retour.php',
	'location/historique'				=> 'prive/historique.php',
	'location/tarifs'					=> 'prive/tarifs.php',
	'materiel/reparation'				=> 'prive/reparation.php',
	'materiel'							=> 'prive/materiel.php',
	'bilan'								=> 'prive/bilan.php',
	'saisons'							=> 'public/saisons.php',
	'ajax/([^/]+)'						=> 'prive/ajax.php',

);


foreach ($routes as $route => $action) {
	$routes[$route.GET] = $action;
	unset($routes[$route]);
}
