<?php

/* *********************************************************/
/* Produit Piston : Gestion Matos et Membres ***************/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* actions/public/vendeurs.php *****************************/
/* Gestion des vendeurs de la saison ***********************/
/* *********************************************************/
/* Dernière modification : le 07/11/14 *********************/
/* *********************************************************/


//S'il n'y a pas de saison on affiche une erreur
if (empty($saison))
	die(require DIR.'templates/_error.php');


//Ajout d'un vendeur
if (isset($_POST['add']) &&
	!empty($_POST['nom'][0])) {
	$pdo->exec($s = 'INSERT INTO vendeurs SET '.
		'nom = "'.secure($_POST['nom'][0]).'", '.
		'id_saison = '.$saison['id']);
	$add = true;
}


//On récupère l'indice du champ concerné
if ((!empty($_POST['delete']) || 
	!empty($_POST['edit'])) &&
	isset($_POST['id']) &&
	is_array($_POST['id']))
	$i = array_search(empty($_POST['delete']) ?
		$_POST['edit'] :
		$_POST['delete'],
		$_POST['id']);


//On edite un vendeur
if (!empty($i) &&
	empty($_POST['delete']) &&
	!empty($_POST['nom'][$i])) {
	$pdo->exec('UPDATE vendeurs SET '.
		'nom = "'.secure($_POST['nom'][$i]).'" '.
		'WHERE id = '.$_POST['id'][$i]);
	$modify = true;
}


//On supprime un vendeur
else if (!empty($i) &&
	!empty($_POST['delete'])) {
	$pdo->exec('DELETE FROM vendeurs '.
		'WHERE id = '.$_POST['id'][$i]);
	$delete = true;
}


//Mise à jour des vendeurs
$vendeurs = !empty($saison) ? $pdo->query('SELECT id, nom '.
	'FROM vendeurs '.
	'WHERE id_saison = '.$saison['id'].' '.
	'ORDER BY nom ASC')
	->fetchAll(PDO::FETCH_ASSOC) : array();


//Vendeurs triés par ordre d'ajout
$vendeurs_non_tries = !empty($saison) ? $pdo->query('SELECT id, nom '.
	'FROM vendeurs '.
	'WHERE id_saison = '.$saison['id'].' '.
	'ORDER BY id ASC')
	->fetchAll(PDO::FETCH_ASSOC) : array();


//Inclusion du bon fichier de template
require DIR.'templates/public/vendeurs.php';