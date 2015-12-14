<?php

/* *********************************************************/
/* Produit Piston : Gestion Matos et Membres ***************/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* actions/prive/materiel.php ******************************/
/* Gestion des élèments ************************************/
/* *********************************************************/
/* Dernière modification : le 08/11/14 *********************/
/* *********************************************************/


//On quitte quand aucun vendeur n'est connecté
if (empty($_SESSION['user_id']))
	die(header('location:'.url('', false, false)));


//Ajout d'un élément
if (isset($_POST['add']) &&
	isset($_POST['numero'][0]) &&
	strlen(trim($_POST['numero'][0])) &&
	!empty($_POST['etat'][0]) &&
	!empty($_POST['description'][0]) &&
	isset($_POST['type'][0]) &&
	in_array($_POST['type'][0], array('skis', 'snow', 'other'))) {
	$pdo->exec('INSERT INTO materiels SET '.
		'numero = "'.secure($_POST['numero'][0]).'", '.
		'etat_initial = "'.secure($_POST['etat'][0]).'", '.
		'type = "'.secure($_POST['type'][0]).'", '.
		'description = "'.secure($_POST['description'][0]).'", '.
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


//On edite un élément
if (!empty($i) &&
	empty($_POST['delete']) &&
	isset($_POST['numero'][$i]) &&
	strlen(trim($_POST['numero'][$i])) &&
	!empty($_POST['etat'][$i]) &&
	!empty($_POST['description'][$i]) &&
	isset($_POST['type'][$i]) &&
	in_array($_POST['type'][$i], array('skis', 'snow', 'other'))) {
	
	$last_emprunt = $pdo->query('SELECT '.
			'id, '.
			'date_retour '.
		'FROM emprunts '.
		'WHERE '.
			'id_materiel = '.$_POST['id'][$i].' AND '.
			'id_saison = '.$saison['id'].' '.
		'ORDER BY '.
			'id DESC '.
		'LIMIT 1')
		->fetch(PDO::FETCH_ASSOC);

	if (!empty($last_emprunt)) 
		$pdo->exec($d ='UPDATE emprunts SET '.
			(empty($last_emprunt['date_retour']) ? 'etat_debut' : 'etat_retour').
			' = "'.secure($_POST['etat'][$i]).'" '.
			'WHERE id = '.$last_emprunt['id']);

	$pdo->exec($d= 'UPDATE materiels SET '.
		'numero = "'.secure($_POST['numero'][$i]).'", '.
		(empty($last_emprunt) ? 'etat_initial = "'.secure($_POST['etat'][$i]).'", ' : '').
		'type = "'.secure($_POST['type'][$i]).'", '.
		'description = "'.secure($_POST['description'][$i]).'" '.
		'WHERE id = '.$_POST['id'][$i]);

	
	$modify = true;
}


//On supprime un élément
else if (!empty($i) &&
	!empty($_POST['delete'])) {

	//On sélectionne tous les emprunts pour accèder aux réparations
	$emprunts = $pdo->query('SELECT id '.
		'FROM emprunts '.
		'WHERE '.
			'id_materiel = '.(int) $_POST['id'][$i].' AND '.
			'id_saison = '.$saison['id'])
		->fetchAll(PDO::FETCH_ASSOC);


	//Prépation de la requête pour supprimer en masse les réparations
	$del = $pdo->prepare('DELETE FROM reparations WHERE '.
		'id_emprunt = :id_emprunt ');

	
	//Suppression des réparations
	foreach ($emprunts as $emprunt)
		$del->execute(array(
			':id_emprunt' => $emprunt['id']));
	
	//Suppression des emprunts
	$pdo->exec('DELETE FROM emprunts '.
		'WHERE id_materiel = '.(int) $_POST['id'][$i]);


	//Suppression de tout le matériel
	$pdo->exec('DELETE FROM materiels '.
		'WHERE id = '.(int) $_POST['id'][$i]);

	$delete = true;
}


//Inclusion du bon fichier de template
require DIR.'templates/prive/materiel.php';