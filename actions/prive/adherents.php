<?php

/* *********************************************************/
/* Produit Piston : Gestion Matos et Membres ***************/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* actions/prive/adherents.php *****************************/
/* Gestion des adherents de la saison **********************/
/* *********************************************************/
/* Dernière modification : le 08/11/14 *********************/
/* *********************************************************/


//On quitte quand aucun vendeur n'est connecté
if (empty($_SESSION['user_id']))
	die(header('location:'.url('', false, false)));


//Ajout d'un adherent
if (isset($_POST['add']) &&
	!empty($_POST['nom'][0]) &&
	!empty($_POST['prenom'][0]) &&
	!empty($_POST['telephone'][0]) &&
	isset($_POST['caution'][0]) &&
	in_array($_POST['caution'][0], array('0', '1'))) {
	$pdo->exec($s = 'INSERT INTO adherents SET '.
		'nom = "'.secure($_POST['nom'][0]).'", '.
		'prenom = "'.secure($_POST['prenom'][0]).'", '.
		'telephone = "'.secure($_POST['telephone'][0]).'", '.
		'caution = "'.secure($_POST['caution'][0]).'", '.
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


//On edite un adherent
if (!empty($i) &&
	empty($_POST['delete']) &&
	!empty($_POST['prenom'][$i]) &&
	!empty($_POST['prenom'][$i]) &&
	!empty($_POST['telephone'][$i]) &&
	isset($_POST['caution'][$i]) &&
	in_array($_POST['caution'][$i], array('0', '1'))) {
	$pdo->exec('UPDATE adherents SET '.
		'nom = "'.secure($_POST['nom'][$i]).'", '.
		'prenom = "'.secure($_POST['prenom'][$i]).'", '.
		'telephone = "'.secure($_POST['telephone'][$i]).'", '.
		'caution = "'.secure($_POST['caution'][$i]).'" '.
		'WHERE id = '.$_POST['id'][$i]);
	$modify = true;
}


//On supprime un adherent
else if (!empty($i) &&
	!empty($_POST['delete'])) {
	$pdo->exec('DELETE FROM adherents '.
		'WHERE id = '.$_POST['id'][$i]);
	$delete = true;
}


//Inclusion du bon fichier de template
require DIR.'templates/prive/adherents.php';