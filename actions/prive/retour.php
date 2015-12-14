<?php

/* *********************************************************/
/* Produit Piston : Gestion Matos et Membres ***************/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* actions/prive/retour.php ********************************/
/* Retour d'un emprunt *************************************/
/* *********************************************************/
/* Dernière modification : le 08/11/14 *********************/
/* *********************************************************/


//On quitte quand aucun vendeur n'est connecté
if (empty($_SESSION['user_id']))
	die(header('location:'.url('', false, false)));


//Enregistrement d'un retour
if (!empty($_POST['delete']) &&
	!empty($_POST['type']) &&
	in_array($_POST['type'], array('skis', 'snow', 'other')) &&
	!empty($_POST['numero']) &&
	is_numeric($_POST['numero']) &&
	isset($_POST['deterioration']) &&
	in_array($_POST['deterioration'], array('0', '1')) &&
	!empty($_POST['etat'])) {

	//Modification de l'emprunt comme étant retourné
	$modif = $pdo->exec('UPDATE emprunts SET '.
			'date_retour = '.time().', '.
			'etat_retour = "'.secure($_POST['etat']).'" '.
		'WHERE '.
			'date_retour IS NULL AND '.
			'id_saison = '.$saison['id'].' AND '.
			'id = '.(int) $_POST['numero']);

	
	//On ajoute la dégradation si cette dernière est demandée
	if ($_POST['deterioration'] == '1' &&
		$modif > 0) 
		$pdo->exec('INSERT INTO reparations SET '.
			'id_emprunt = '.(int) $_POST['numero'].', '.
			'date_reparation = '.time());

	$add = $modif > 0;
}


//Inclusion du bon fichier de template
require DIR.'templates/prive/retour.php';