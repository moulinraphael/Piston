<?php

/* *********************************************************/
/* Produit Piston : Gestion Matos et Membres ***************/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* actions/public/saisons.php ******************************/
/* Gestion des saisons *************************************/
/* *********************************************************/
/* Dernière modification : le 07/11/14 *********************/
/* *********************************************************/



//Ajout d'une saison
if (isset($_POST['add']) &&
	!empty($_POST['nom'][0])) {
	$pdo->exec($s = 'INSERT INTO saisons SET '.
		'nom = "'.secure($_POST['nom'][0]).'", '.
		'active = 0');
	$add = true;
}


//On récupère l'indice du champ concerné
if ((!empty($_POST['delete']) || 
	!empty($_POST['edit']) || 
	!empty($_POST['switch'])) &&
	isset($_POST['id']) &&
	is_array($_POST['id'])) 
	$i = array_search(empty($_POST['delete']) ?
		(empty($_POST['edit']) ? $_POST['switch'] : $_POST['edit']) : 
		$_POST['delete'],
		$_POST['id']); 


//On edite une saison
if (!empty($i) &&
	!empty($_POST['edit']) &&
	!empty($_POST['nom'][$i])) {
	$pdo->exec('UPDATE saisons SET '.
		'nom = "'.secure($_POST['nom'][$i]).'" '.
		'WHERE id = '.$_POST['id'][$i]);
	$modify = true;
}


//On supprime une saison
else if (!empty($i) &&
	!empty($_POST['delete'])) {

	//Récupération de tous les emprunts de la saison pour supprimer les réparations
	$emprunts = $pdo->query('SELECT id '.
		'FROM emprunts '.
		'WHERE '.
			'id_saison = '.$saison['id'])
		->fetchAll(PDO::FETCH_ASSOC);


	//On prépare la requête de suppression des réparations
	$del = $pdo->prepare('DELETE FROM reparations WHERE '.
		'id_emprunt = :id_emprunt ');

	
	//Suppression des réparations
	foreach ($emprunts as $emprunt)
		$del->execute(array(
			':id_emprunt' => $emprunt['id']));
	

	//Suppressions des emprunts
	$pdo->exec('DELETE FROM emprunts '.
		'WHERE id_saison = '.(int) $_POST['id'][$i]);


	//Suppression du matériel
	$pdo->exec('DELETE FROM materiels '.
		'WHERE id_saison = '.(int) $_POST['id'][$i]);


	//Suppression des tarifs
	$pdo->exec('DELETE FROM tarifs '.
		'WHERE id_saison = '.(int) $_POST['id'][$i]);


	//Suppression des adhérents
	$pdo->exec('DELETE FROM adherents '.
		'WHERE id_saison = '.(int) $_POST['id'][$i]);


	//Supressions des vendeurs
	$pdo->exec('DELETE FROM vendeurs '.
		'WHERE id_saison = '.(int) $_POST['id'][$i]);


	//Suppressions de la saison en elle-même
	$pdo->exec('DELETE FROM saisons '.
		'WHERE id = '.$_POST['id'][$i]);

	$delete = true;
}


//On switch l'activation d'une (ou deux) saison(s)
else if (!empty($i) &&
	!empty($_POST['switch'])) {
	
	//On désactive toutes les saisons
	$pdo->exec('UPDATE saisons SET '.
		'active = 0');


	//On active la bonne saison
	if (empty($saison) ||
		$_POST['id'][$i] != $saison['id'])
		$pdo->exec('UPDATE saisons SET '.
			'active = 1 '.
			'WHERE id = '.$_POST['id'][$i]);


	//On change de saison, donc le vendeur n'est plus !
	$modify = true;
	unset($_SESSION['user_id']);
}


//Mise à jour des saisons
$saisons = $pdo->query('SELECT id, nom, active '.
	'FROM saisons '.
	'ORDER BY nom ASC')
	->fetchAll(PDO::FETCH_ASSOC);


//Récupération de la saison actuelle
$saison = $pdo->query('SELECT id, nom, active '.
	'FROM saisons '.
	'WHERE active = 1 '.
	'ORDER BY id DESC '.
	'LIMIT 1')
	->fetch(PDO::FETCH_ASSOC);


//Recupération des vendeurs (mise à jour en cas de changement)
$vendeurs = !empty($saison) ? $pdo->query('SELECT id, nom '.
	'FROM vendeurs '.
	'WHERE id_saison = '.$saison['id'].' '.
	'ORDER BY nom ASC')
	->fetchAll(PDO::FETCH_ASSOC) : array();


//Récupération de id des vendeurs
$vendeurs_id = [0];
foreach ($vendeurs as $vendeur)
	$vendeurs_id[] = $vendeur['id'];


//Inclusion du bon fichier de template
require DIR.'templates/public/saisons.php';