<?php

/* *********************************************************/
/* Produit Piston : Gestion Matos et Membres ***************/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* includes/_piston.php ************************************/
/* Actions relatives à l'application du Piston *************/
/* *********************************************************/
/* Dernière modification : le 08/11/14 *********************/
/* *********************************************************/


//On cherche à passer en mode déconnecté
if (isset($_POST['vendeur']) &&
	$_POST['vendeur'] == '-1')
	die(header('location:'.url('vendeurs', false, false)));


//Récupération de la saison en cours
$saison = $pdo->query('SELECT id, nom, active '.
	'FROM saisons '.
	'WHERE active = 1 '.
	'ORDER BY id DESC '.
	'LIMIT 1')
	->fetch(PDO::FETCH_ASSOC);


//Recupération des vendeurs
$vendeurs = !empty($saison) ? $pdo->query('SELECT id, nom '.
	'FROM vendeurs '.
	'WHERE id_saison = '.$saison['id'].' '.
	'ORDER BY nom ASC')
	->fetchAll(PDO::FETCH_ASSOC) : array();


//Récupération de id des vendeurs
$vendeurs_id = [0];
foreach ($vendeurs as $vendeur)
	$vendeurs_id[] = $vendeur['id'];


//Changement du vendeur et retour à l'accueil
if (isset($_POST['vendeur']) &&
	in_array($_POST['vendeur'], $vendeurs_id)) {
	$_SESSION['user_id'] = $_POST['vendeur'];

	if (empty($_SESSION['user_id']))
		die(header('location:'.url('', false, false)));
}


//Définitions des durées
$durees = [
	'we' => 'Week-End',
	'1s' => '1 semaine',
	'2s' => '2 semaines'
];

//Définitions des types
$types = [
	'skis' => 'Skis',
	'snow' => 'Snow',
	'other' => 'Autre'
];


//Si il y a une erreur on ne choisit pas de vendeur (mode hors ligne)
if (!isset($_SESSION['user_id']) ||
	!in_array($_SESSION['user_id'], $vendeurs_id))
	$_SESSION['user_id'] = 0;


