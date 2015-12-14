<?php

/* *********************************************************/
/* Produit Piston : Gestion Matos et Membres ***************/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* actions/prive/bilan.php *********************************/
/* Affichage du bilan de la saison *************************/
/* *********************************************************/
/* Dernière modification : le 06/11/14 *********************/
/* *********************************************************/


//On quitte quand aucun vendeur n'est connecté
if (empty($_SESSION['user_id']))
	die(header('location:'.url('', false, false)));


//Récupération des tarifs
$tarifs_sql = $pdo->query('SELECT * FROM tarifs '.
	'WHERE id_saison = '.$saison['id'].' '.
	'ORDER BY type ASC, duree DESC')
	->fetchAll(PDO::FETCH_ASSOC);


//Création des tableaux modèles pour tous les types de tarifs, quantités et recettes
$model = array(
	'we' => false,
	'1s' => false,
	'2s' => false);

$model = array(
	'skis' => $model,
	'snow' => $model,
	'other' => $model);

$tarifs = $model;
$quantites = $model;
$recettes = $model;


//Organisation des tarifs
foreach ($tarifs_sql as $tarif)
	$tarifs[$tarif['type']][$tarif['duree']] = $tarif['tarif'];


//Récupération des tarifs
$quantites_sql = $pdo->query('SELECT m.type, e.duree, '.
    	'COUNT(e.id) AS quantite '.
	'FROM emprunts AS e '.
	'JOIN materiels AS m ON '.
		'm.id = e.id_materiel AND '.
		'm.id_saison = '.$saison['id'].' '.
	'WHERE '.
		'e.id_saison = '.$saison['id'].' AND '.
		'm.type IS NOT NULL AND '.
		'e.duree IS NOT NULL '.
	'GROUP BY m.type, e.duree')
	->fetchAll(PDO::FETCH_ASSOC);


//Organisation des quantites
foreach ($quantites_sql as $quantite)
	$quantites[$quantite['type']][$quantite['duree']] = $quantite['quantite'];


//Organisation des recettes
foreach ($tarifs as $label => $categorie)
	foreach ($categorie as $duree => $tarif)
		$recettes[$label][$duree] = $tarif === false ? false : $tarif * $quantites[$label][$duree];


//Inclusion du bon fichier de template
require DIR.'templates/prive/bilan.php';