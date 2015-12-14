<?php

/* *********************************************************/
/* Produit Piston : Gestion Matos et Membres ***************/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* actions/prive/location.php ******************************/
/* Ajout d'une location, d'un emprunt **********************/
/* *********************************************************/
/* Dernière modification : le 08/11/14 *********************/
/* *********************************************************/


//On quitte quand aucun vendeur n'est connecté
if (empty($_SESSION['user_id']))
	die(header('location:'.url('', false, false)));


//Récupération des adherents
$adherents = $pdo->query('SELECT id, nom, prenom, telephone, caution '.
	'FROM adherents '.
	'WHERE id_saison = '.$saison['id'].' AND '.
	'caution = 1 '.
	'ORDER BY nom ASC, prenom ASC')
	->fetchAll(PDO::FETCH_ASSOC);


//Ajout d'un emprunt
if (!empty($_POST['ajout']) &&
	!empty($_POST['adherent']) &&
	!empty($_POST['type']) &&
	in_array($_POST['type'], array('skis', 'snow', 'other')) &&
	!empty($_POST['etat']) &&
	!empty($_POST['numero']) &&
	isset($_POST['batons']) &&
	in_array($_POST['batons'], array('0', '1')) &&
	!empty($_POST['duree']) &&
	in_array($_POST['duree'], array('we', '1s', '2s'))) {

	//Recherche de l'élément à emprunté, est-il disponible ???
	$element = $pdo->query($e = 'SELECT m.id, m.numero '.
		'FROM materiels AS m '.
		'WHERE '.
			'm.type = "'.$_POST['type'].'" AND '.
			//'m.id_saison = '.$saison['id'].' AND '.
			'm.id NOT IN (SELECT id_materiel '.
				'FROM emprunts AS e '.
				'WHERE '.
					//'e.id_saison = '.$saison['id'].' AND '.
					'e.date_retour IS NULL) AND '.
			'm.id NOT IN (SELECT id_materiel '.
				'FROM emprunts AS e '.
				'JOIN reparations AS r ON '.
					'r.id_emprunt = e.id AND '.
					'(r.repare IS NULL OR r.repare = 0) '.
				'WHERE '.
					//'e.id_saison = '.$saison['id'].' AND '.
					'e.date_retour IS NOT NULL) AND '.
			'm.id = '.(int) $_POST['numero'])->fetch(PDO::FETCH_ASSOC);


	//Il est bien dispo, donc on peut le prêter à qqn
	if (!empty($element)) {
		$pdo->exec('INSERT INTO emprunts SET '.
			'id_saison = '.$saison['id'].', '.
			'date_emprunt = '.time().', '.
			'id_adherent = '.(int) $_POST['adherent'].', '.
			'id_materiel = '.(int) $_POST['numero'].', '.
			'etat_debut = "'.secure($_POST['etat']).'", '.
			'id_vendeur = '.(int) $_SESSION['user_id'].', '.
			'duree = "'.$_POST['duree'].'", '.
			'batons = '.(int) $_POST['batons']) or die('test');

		$add = true;
	}

}


//Inclusion du bon fichier de template
require DIR.'templates/prive/location.php';