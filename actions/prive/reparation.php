<?php

/* *********************************************************/
/* Produit Piston : Gestion Matos et Membres ***************/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* actions/prive/reparation.php ****************************/
/* Gestion des réparations *********************************/
/* *********************************************************/
/* Dernière modification : le 06/11/14 *********************/
/* *********************************************************/


//On quitte quand aucun vendeur n'est connecté
if (empty($_SESSION['user_id']))
	die(header('location:'.url('', false, false)));


//On récupère l'indice du champ concerné
if ((!empty($_POST['delete']) || 
	!empty($_POST['edit'])) &&
	isset($_POST['id']) &&
	is_array($_POST['id']))
	$i = array_search(empty($_POST['delete']) ?
		$_POST['edit'] :
		$_POST['delete'],
		$_POST['id']);


//On edite une dégradation (on ne peut éditer d'une dégradation non remboursée)
if (isset($i) &&
	empty($_POST['delete']) &&
	isset($_POST['prix'][$i]) && (
		is_numeric($_POST['prix'][$i]) ||
		trim($_POST['prix'][$i]) == '') &&
	isset($_POST['remboursement'][$i]) &&
	isset($_POST['repare'][$i]) &&
	in_array($_POST['remboursement'][$i], array('0', '1')) && 
	in_array($_POST['repare'][$i], array('0', '1'))) {
	$pdo->exec('UPDATE reparations SET '.
		'prix = '.(trim($_POST['prix'][$i]) == '' ? 'NULL' : abs((float) secure($_POST['prix'][$i]))).', '.
		'rembourse = '.secure($_POST['remboursement'][$i]).', '.
		'repare = '.secure($_POST['repare'][$i]).' '.
		'WHERE '.
			'id = '.$_POST['id'][$i].' AND ('.
				'repare <> 1 OR '.
				'repare IS NULL OR '.
				'rembourse <> 1 OR '.
				'rembourse IS NULL)') or die(print_r($pdo->errorInfo()));

	$modify = true;
}


//On supprime une dégradation
else if (isset($i) &&
	!empty($_POST['delete'])) {
	$pdo->exec('DELETE FROM reparations '.
		'WHERE id = '.$_POST['id'][$i]);
	$delete = true;
}


//Récupération des réparations
$degradations = $pdo->query('SELECT r.id, m.type, m.numero, a.nom, a.prenom, r.prix, r.rembourse, r.repare, r.date_reparation '.
	'FROM reparations AS r '.
	'JOIN emprunts AS e ON '.
		'e.id = r.id_emprunt '.
		//' AND e.id_saison = '.$saison['id'].' '.
	'JOIN materiels AS m ON '.
		'm.id = e.id_materiel '.
		//' AND m.id_saison = '.$saison['id'].' '.
	'LEFT JOIN adherents AS a ON '.
		'a.id = e.id_adherent '.
		//' AND a.id_saison = '.$saison['id'].' '.
	'WHERE '.
		'r.repare <> 1 OR '.
		'r.repare IS NULL OR '.
		'r.rembourse <> 1 OR '.
		'r.rembourse IS NULL '.
	'ORDER BY r.id DESC') 
	->fetchAll(PDO::FETCH_ASSOC);


//Récupération des réparations
$archives = $pdo->query('SELECT r.id, m.type, m.numero, a.nom, a.prenom, r.prix, r.rembourse, r.repare, r.date_reparation '.
	'FROM reparations AS r '.
	'JOIN emprunts AS e ON '.
		'e.id = r.id_emprunt AND '.
		'e.id_saison = '.$saison['id'].' '.
	'JOIN materiels AS m ON '.
		'm.id = e.id_materiel AND '.
		'm.id_saison = '.$saison['id'].' '.
	'LEFT JOIN adherents AS a ON '.
		'a.id = e.id_adherent AND '.
		'a.id_saison = '.$saison['id'].' '.
	'WHERE '.
		'r.repare = 1 AND '.
		'r.rembourse = 1 '.
	'ORDER BY r.id DESC')
	->fetchAll(PDO::FETCH_ASSOC);


//Inclusion du bon fichier de template
require DIR.'templates/prive/reparation.php';