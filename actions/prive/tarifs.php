<?php

/* *********************************************************/
/* Produit Piston : Gestion Matos et Membres ***************/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* actions/prive/tarifs.php ********************************/
/* Définition des tarifs des différentes locations *********/
/* *********************************************************/
/* Dernière modification : le 07/11/14 *********************/
/* *********************************************************/


//On quitte quand aucun vendeur n'est connecté
if (empty($_SESSION['user_id']))
	die(header('location:'.url('', false, false)));


//Enregistrement des prix
if (!empty($_POST['maj'])) {

	//Analyse des données transmises pour éviter les erreurs
	if (isset($_POST['ski-we']) &&
		trim($_POST['ski-we']) !== '' && (
		!is_numeric($_POST['ski-we']) ||
		$_POST['ski-we'] < 0) ||
		isset($_POST['ski-1s']) && 
		trim($_POST['ski-1s']) !== '' && (
		!is_numeric($_POST['ski-1s']) ||
		$_POST['ski-1s'] < 0) ||
		isset($_POST['ski-2s']) && 
		trim($_POST['ski-2s']) !== '' && (
		!is_numeric($_POST['ski-2s']) ||
		$_POST['ski-2s'] < 0) ||
		isset($_POST['snow-we']) && 
		trim($_POST['snow-we']) !== '' && (
		!is_numeric($_POST['snow-we']) ||
		$_POST['snow-we'] < 0) ||
		isset($_POST['snow-1s']) && 
		trim($_POST['snow-1s']) !== '' && (
		!is_numeric($_POST['snow-1s']) ||
		$_POST['snow-1s'] < 0) ||
		isset($_POST['snow-2s']) && 
		trim($_POST['snow-2s']) !== '' && (
		!is_numeric($_POST['snow-2s']) ||
		$_POST['snow-2s'] < 0))
		$error = true;


	//Pas d'erreur, on peut mettre à jour les données
	else {

		//On commence par supprimer tous les anciens tarifs
		$pdo->exec('DELETE FROM tarifs WHERE id_saison = '.$saison['id']);


		//Préparation de la requête d'ajout en masse
		$req = '';
		if (isset($_POST['ski-we']) && trim($_POST['ski-we']) !== '')
			$req.= '('.$saison['id'].', "skis", "we", '.abs((float) $_POST['ski-we']).'),';

		if (isset($_POST['ski-1s']) && trim($_POST['ski-1s']) !== '')
			$req.= '('.$saison['id'].', "skis", "1s", '.abs((float) $_POST['ski-1s']).'),';

		if (isset($_POST['ski-2s']) && trim($_POST['ski-2s']) !== '')
			$req.= '('.$saison['id'].', "skis", "2s", '.abs((float) $_POST['ski-2s']).'),';

		if (isset($_POST['snow-we']) && trim($_POST['snow-we']) !== '')
			$req.= '('.$saison['id'].', "snow", "we", '.abs((float) $_POST['snow-we']).'),';

		if (isset($_POST['snow-1s']) && trim($_POST['snow-1s']) !== '')
			$req.= '('.$saison['id'].', "snow", "1s", '.abs((float) $_POST['snow-1s']).'),';

		if (isset($_POST['snow-2s']) && trim($_POST['snow-2s']) !== '')
			$req.= '('.$saison['id'].', "snow", "2s", '.abs((float) $_POST['snow-2s']).'),';
		

		//Si la requête n'est pas vide, on peut ajouter tous les tarifs en une seule requête
		if (!empty($req)) {
			$req = 'INSERT INTO tarifs (id_saison, type, duree, tarif) VALUES '.$req;
			$pdo->exec(substr($req, 0, -1).';');
		}

		$modify = true;
	}

}


//Récupération des tarifs
$tarifs_sql = $pdo->query('SELECT * FROM tarifs '.
	'WHERE id_saison = '.$saison['id'].' '.
	'ORDER BY type ASC, duree DESC')
	->fetchAll(PDO::FETCH_ASSOC);


//Utilisation d'un modèle pour organiser les tarifs
$model = array(
	'we' => false,
	'1s' => false,
	'2s' => false);

$tarifs = array(
	'skis' => $model,
	'snow' => $model);


//Organisation des tarifs
foreach ($tarifs_sql as $tarif)
	$tarifs[$tarif['type']][$tarif['duree']] = $tarif['tarif'];


//Inclusion du bon fichier de template
require DIR.'templates/prive/tarifs.php';