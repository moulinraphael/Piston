<?php

/* *********************************************************/
/* Produit Piston : Gestion Matos et Membres ***************/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* actions/prive/historique.php ****************************/
/* Afficher l'historique d'un élement **********************/
/* *********************************************************/
/* Dernière modification : le 08/11/14 *********************/
/* *********************************************************/


//On quitte quand aucun vendeur n'est connecté
if (empty($_SESSION['user_id']))
	die(header('location:'.url('', false, false)));


//Insertion d'une dégradation
if (!empty($_POST['deteriore']) && 
	!empty($_POST['type']) &&
	isset($_POST['numero']) &&
	is_numeric($_POST['numero']) &&
	in_array($_POST['type'], array('skis', 'snow', 'other'))) {

	$dernier = $pdo->query('SELECT e.id AS eid, a.nom, a.prenom, r.date_reparation, r.id, r.rembourse, r.repare, e.date_retour, e.date_emprunt '.
		'FROM emprunts AS e '.
		'LEFT JOIN reparations AS r ON '.
			'r.id_emprunt = e.id '.
		'LEFT JOIN adherents AS a ON '.
			'a.id = e.id_adherent AND '.
			'a.id_saison = '.$saison['id'].' '.
		'WHERE '.
			'e.id_saison = '.$saison['id'].' AND '.
			'e.id_materiel = "'.$_POST['numero'].'" '.
		'ORDER BY e.id DESC '.
		'LIMIT 1')
		->fetch(PDO::FETCH_ASSOC);


	//Il y a un dernier emprunt et ce dernier a été rendu
	//Par ailleurs si réparation il y a eu le remboursement a bien eu lieu
	if ((!empty($dernier['id']) && !empty($dernier['repare']) || empty($dernier['id'])) && !empty($dernier['date_retour'])) {
		$pdo->exec('INSERT INTO reparations SET '.
			'id_emprunt = '.$dernier['eid'].', '.
			'date_reparation = '.time());

		$modify = true;
	}
}


//Inclusion du bon fichier de template
require DIR.'templates/prive/historique.php';