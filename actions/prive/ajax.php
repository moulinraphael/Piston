<?php

/* *********************************************************/
/* Produit Piston : Gestion Matos et Membres ***************/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* actions/prive/ajax.php **********************************/
/* Gestion des réponses AJAX *******************************/
/* *********************************************************/
/* Dernière modification : le 09/11/14 *********************/
/* *********************************************************/


//On quitte quand aucun vendeur n'est connecté
if (empty($_SESSION['user_id']))
	die(header('location:'.url('', false, false)));


//Définition des routes accessibles grâce à AJAX
$routes = array(
	'dernier_emprunt',
	'elements_dispo',
	'infos_element',
	'calcul_tarif',
	'elements_json_empruntes',
	'infos_emprunt',
	'tous_les_elements',
	'infos_element_histo',
	'historique_element',
	'liste_adherents',
	'liste_materiel',
	'liste_json_adherents',
);


//Si la route n'existe pas, on affiche une erreur
if (empty($args[1][0]) ||
	!in_array($args[1][0], $routes))
	die(require DIR.'templates/_error.php');


//Lors d'un emprunt, on affiche les derniers emprunts liés à l'adhérent
if ($args[1][0] == 'dernier_emprunt' &&
	!empty($_POST['adherent']) &&
	is_numeric($_POST['adherent'])) {

	//Sélectionne l'adhérent
	$adherent = $pdo->query('SELECT * '.
		'FROM adherents '.
		'WHERE id_saison = '.$saison['id'].' AND '.
			'id = '.(int) $_POST['adherent'])
		->fetch(PDO::FETCH_ASSOC);


	//Si l'adhérent n'existe pas on arrête l'exécution
	if (empty($adherent))
		die;


	//Liste des emprunts non rendus
	$emprunts_non_rendus = $pdo->query('SELECT '.
			'm.type, '.
			'm.numero, '.
			'e.date_retour, '.
			'e.date_emprunt '.
		'FROM emprunts AS e '.
		'LEFT JOIN materiels AS m ON '.
			'm.id = e.id_materiel '.
			//' AND m.id_saison = '.$saison['id'].' '. //On conserve tous les matériels
		'WHERE '.
			'e.id_saison = '.$saison['id'].' AND '.
			'e.date_retour IS NULL AND '.
			'e.id_adherent = '.$adherent['id'].' '.
		'ORDER BY e.date_emprunt DESC')
		->fetchAll(PDO::FETCH_ASSOC);


	//Dernier emprunt rendu (notamment pour les dégradations)
	$dernier_emprunt_rendu = $pdo->query('SELECT '.
			'm.type, '.
			'm.numero, '.
			'e.date_retour, '.
			'e.date_emprunt, '.
			'r.date_reparation, '.
			'r.rembourse, '.
			'r.repare, '.
			'r.id AS rid '.
		'FROM emprunts AS e '.
		'JOIN materiels AS m ON '.
			'm.id = e.id_materiel '.
			//' AND m.id_saison = '.$saison['id'].' '. //On conserve tous les matériels
		'LEFT JOIN reparations AS r ON '.
			'r.id_emprunt = e.id '.
		'WHERE '.
			'e.id_saison = '.$saison['id'].' AND '.
			'e.date_retour IS NOT NULL AND '.
			'e.id_adherent = '.$adherent['id'].' '.
		'ORDER BY e.date_retour DESC, r.id DESC '.
		'LIMIT 1')
		->fetch(PDO::FETCH_ASSOC);


	//Quelque soit le type, il n'y a aucun emprunt
	if (empty($emprunts_non_rendus) &&
		empty($dernier_emprunt_rendu))
		echo '<i>Aucun emprunt jusqu\'à présent</i>';


	//On rajoute le dernier emprunt rendu à la liste des emprunts
	if (!empty($dernier_emprunt_rendu))
		$emprunts_non_rendus[] = $dernier_emprunt_rendu;


	//Listing de tous les emprunts
	foreach ($emprunts_non_rendus as $emprunt) {
		echo '<b>'.$types[$emprunt['type']].' : </b> '.$emprunt['numero'];
		echo '<br />'.(empty($emprunt['date_retour']) ?
			'Depuis '.printDate($emprunt['date_emprunt']) :
			('Rendu '.printDate($emprunt['date_retour'], true)).
				(!empty($emprunt['rid']) ? ('<br />'.
					(empty($emprunt['repare']) ? 
						(empty($emprunt['rembourse']) ? '<div class="degrade">En réparation (non remboursé)</div>' :
							'<div class="degrade">En réparation (remboursé)</div>') :
						(empty($emprunt['rembourse']) ? '<div class="nondispo">Réparation non remboursée</div>' : 
							'<div class="dispo">Réparation remboursée</div>'))) : null)).
			(end($emprunts_non_rendus) === $emprunt ? null : '<br /><br />');
	}

	exit;
}


//Recherche des élements disponibles pour l'emprunt
else if ($args[1][0] == 'elements_dispo' &&
	!empty($_POST['type']) &&
	in_array($_POST['type'], array('skis', 'snow', 'other'))) {

	//Récupération des élements
	$elements = $pdo->query('SELECT m.id, m.numero '.
		'FROM materiels AS m '.
		'WHERE '.
			'm.type = "'.$_POST['type'].'" AND '.
			//'m.id_saison = '.$saison['id'].' AND '. //On conserve tous les matériels
			'm.id NOT IN (SELECT id_materiel '.
				'FROM emprunts AS e '.
				'WHERE '.
					'e.id_saison = '.$saison['id'].' AND '.
					'e.date_retour IS NULL) AND '.
			'm.id NOT IN (SELECT id_materiel '.
				'FROM emprunts AS e '.
				'JOIN reparations AS r ON '.
					'r.id_emprunt = e.id AND '.
					'(r.repare IS NULL OR r.repare = 0) '.
				'WHERE '.
					'e.id_saison = '.$saison['id'].' AND '.
					'e.date_retour IS NOT NULL) '.
		'ORDER BY m.numero ASC')
		->fetchAll(PDO::FETCH_ASSOC);


	//Envoi des données récupérées en JSON
    header('Content-Type: application/json', true);
	echo json_encode($elements);
	exit;
}


//Pour l'emprunt on récupère les infos de l'élément sélectionné
else if ($args[1][0] == 'infos_element' &&
	!empty($_POST['element']) &&
	is_numeric($_POST['element'])) {

	//Récupération des données
	$data = $pdo->query('SELECT e.id, m.description, m.etat_initial, e.etat_debut, e.etat_retour '.
		'FROM materiels AS m '.
		'LEFT JOIN emprunts AS e ON '.
			'e.id_materiel = m.id '.
		'WHERE '.
			'm.id = '.(int) $_POST['element'].' '.
			//' AND m.id_saison = '.$saison['id'].' '. //On conserve tous les matériels
		'ORDER BY e.id DESC')
		->fetch(PDO::FETCH_ASSOC);


	//Si aucune donnée existe, on arrête l'exécution
	if (empty($data))
		die;


	//Rénovation des données
	$data['etat'] = html_entity_decode(empty($data['id']) ? $data['etat_initial'] :
		(empty($data['etat_retour']) ? $data['etat_debut'] : $data['etat_retour']));
	unset($data['id']);
	unset($data['etat_initial']);
	unset($data['etat_debut']);
	unset($data['etat_retour']);


	//Envoi des données récupérées en JSON
    header('Content-Type: application/json', true);
	echo json_encode($data);
	exit;
}


//Pour l'emprunt, on calcule le tarif 
else if ($args[1][0] == 'calcul_tarif' &&
	!empty($_POST['type']) &&
	!empty($_POST['duree']) &&
	in_array($_POST['type'], array('skis', 'snow', 'other')) &&
	in_array($_POST['duree'], array('we', '1s', '2s'))) {

	//Récupération du tarif en lien avec les choix
	$tarif = $pdo->query('SELECT tarif '.
		'FROM tarifs '.
		'WHERE '.
			'duree = "'.$_POST['duree'].'" AND '.
			'type = "'.$_POST['type'].'" AND '.
			'id_saison = '.$saison['id'])
		->fetch(PDO::FETCH_ASSOC);


	//Affichage du prix, ou de l'information si le prix n'est pas fixé
	echo empty($tarif) ? '<i>Tarif non fixé</i>' : sprintf("%.2f €", (float) $tarif['tarif']);
    exit;
}


//Informations liées à l'emprunt choisi lors d'un retour
else if ($args[1][0] == 'infos_emprunt' &&
	!empty($_POST['emprunt']) &&
	is_numeric($_POST['emprunt'])) {

	//Récupération des données concernant l'emprunt sélectionné
	$data = $pdo->query('SELECT e.etat_debut AS etat, v.nom, e.batons, e.duree '.
		'FROM emprunts AS e '.
		'LEFT JOIN vendeurs AS v ON '.
			'v.id = e.id_vendeur AND '.
			'v.id_saison = '.$saison['id'].' '.
		'WHERE '.
			'e.id = '.(int) $_POST['emprunt'].' AND '.
			'e.id_saison = '.$saison['id'].' AND '.
			'e.date_retour IS NULL')
		->fetch(PDO::FETCH_ASSOC);


	//Si aucune donnée n'est viable, on arrête l'exécution du script
	if (empty($data))
		die;	


	//Rénovation des données
	$data['nom'] = empty($data['nom']) ? '<i>Inconnu</i>' : $data['nom'];
	$data['etat'] = html_entity_decode($data['etat']);
	$data['duree'] = empty($data['duree']) ? '<i>Inconnue</i>' :
		(empty($durees[$data['duree']]) ? $data['duree'] : $durees[$data['duree']]);


	//Envoi des données récupérées en JSON
    header('Content-Type: application/json', true);
	echo json_encode($data);
	exit;
}


//Liste de tous les élements (dispo ou non) pour l'historique
else if ($args[1][0] == 'tous_les_elements' &&
	!empty($_POST['type']) &&
	in_array($_POST['type'], array('skis', 'snow', 'other'))) {

	//Récupération des éléments avec le type choisi
	$elements = $pdo->query('SELECT m.id, m.numero '.
		'FROM materiels AS m '.
		'WHERE '.
			//'m.id_saison = '.$saison['id'].' AND '. //On conserve tous les matériels
			'm.type = "'.$_POST['type'].'" '.
		'ORDER BY m.numero ASC')
		->fetchAll(PDO::FETCH_ASSOC);


	//Envoi des données récupérées en JSON
    header('Content-Type: application/json', true);
	echo json_encode($elements);
	exit;
}


//Informations liées à l'élement choisi pour l'historique
else if ($args[1][0] == 'infos_element_histo' &&
	!empty($_POST['element']) &&
	is_numeric($_POST['element'])) {

	//Récupération du dernier emprunt lié à cet emprunt
	$dernier = $pdo->query('SELECT '.
			'e.id AS eid, '.
			'e.etat_debut, '.
			'e.etat_retour, '.
			'm.etat_initial, '.
			'e.duree, '.
			'a.nom, '.
			'a.prenom, '.
			'r.date_reparation, '.
			'r.id, '.
			'r.rembourse, '.
			'r.repare, '.
			'e.date_retour, '.
			'e.date_emprunt '.
		'FROM emprunts AS e '.
		'JOIN materiels AS m ON '.
			'm.id = e.id_materiel '.
		'LEFT JOIN reparations AS r ON '.
			'r.id_emprunt = e.id '.
		'LEFT JOIN adherents AS a ON '.
			'a.id = e.id_adherent '.
			//' AND a.id_saison = '.$saison['id'].' '.
		'WHERE '.
			//'e.id_saison = '.$saison['id'].' AND '.
			'e.id_materiel = "'.$_POST['element'].'" '.
		'ORDER BY e.id DESC, r.id DESC '.
		'LIMIT 1')
		->fetch(PDO::FETCH_ASSOC);


	//Mise en place des données à transmettre
	$data = [
		'dispo' => !empty($dernier['id']) ? 
			(empty($dernier['repare']) ?
				'<div class="degrade">En réparation</div>, depuis '.printDate($dernier['date_reparation']) :
				'<div class="dispo">Disponible (réparé)</div>') :
			(!empty($dernier['date_retour']) || empty($dernier['date_emprunt']) ?
				'<div class="dispo">Disponible</div>' :
				'<div class="nondispo">Emprunté ('.(empty($durees[$dernier['duree']]) ? 
					(empty($dernier['duree']) ? 
						'<i>Durée inconnue</i>' :
						$dernier['duree']) :
					$durees[$dernier['duree']]).')</div>, depuis '.printDate($dernier['date_emprunt'])),

		'adherent' => empty($dernier['nom']) ?
			'<i>Inconnu</i>' :
			stripslashes(strtoupper($dernier['nom']).' '.$dernier['prenom']),

		'etat' => stripslashes(empty($dernier['eid']) ?
			$dernier['etat_initial'] :
			(empty($dernier['etat_retour']) ?
				$dernier['etat_debut'] :
				$dernier['etat_retour'])),

		'emprunt_rendu' => (!empty($dernier['id']) && !empty($dernier['repare']) || empty($dernier['id'])) && !empty($dernier['date_retour'])
	];


	//Envoi des données récupérées en JSON
    header('Content-Type: application/json', true);
	echo json_encode($data);
	exit;
}


//Liste du tableau des emprunts liés à l'élement
else if ($args[1][0] == 'historique_element' &&
	!empty($_POST['element']) &&
	is_numeric($_POST['element'])) {

	//Récupération des emprunts
	$emprunts = $pdo->query('SELECT '.
			'e.id, '.
			'r.id AS rid, '.
			'r.date_reparation, '.
			'r.prix, '.
			'r.rembourse, '.
			'r.repare, '.
			'e.etat_debut, '.
			'e.date_retour, '.
			'e.date_emprunt, '.
			'a.nom, '.
			'a.prenom, '.
			'e.etat_retour '.
		'FROM emprunts AS e '.
		'JOIN materiels AS m ON '.
			'm.id = e.id_materiel '.
			//' AND m.id_saison = '.$saison['id'].' './/On conserve tous les matériels
		'LEFT JOIN adherents AS a ON '.
			'a.id = e.id_adherent '.
			//' AND a.id_saison = '.$saison['id'].' '.
		'LEFT JOIN reparations AS r ON '.
			'r.id_emprunt = e.id '.
		'WHERE '.
			'e.id_materiel = '.(int) $_POST['element'].' '.
			//' AND e.id_saison = '.$saison['id'].' '.
		'ORDER BY '.
			'e.id DESC, '.
			'r.id DESC')
		->fetchAll(PDO::FETCH_ASSOC);


	//Si aucun emprunt, on l'affiche
	if (!count($emprunts))
		echo '<tr class="vide"><td colspan="5">Aucun emprunt pour le moment</td></tr>';


	//Listing des emprunts
	$idActuel = false;
	foreach ($emprunts as $emprunt) {

		//On affiche les infos vraiment liées à l'emprunt
		if ($idActuel != $emprunt['id']) {
			$idActuel = $emprunt['id'];
			echo '<tr>';
			echo '<td>'.(empty($emprunt['nom']) ?
				'<i>Inconnu</i>' :
				stripslashes(strtoupper($emprunt['nom']).' '.$emprunt['prenom'])).'</td>';
			echo '<td>'.printDate($emprunt['date_emprunt'], true).'</td>';
			echo '<td'.(empty($emprunt['date_retour']) ?
				' class="vide">' :
				'>'.printDate($emprunt['date_retour'], true)).'</td>';
			echo '<td>'.stripslashes($emprunt['etat_debut']).'</td>';
			echo '<td>'.(empty($emprunt['date_retour']) ? 
				null :
				stripslashes($emprunt['etat_retour'])).'</td>';
			echo '</tr>';
		}

		//Pour chaque réparation on rajoute une ligne en dessous de l'emprunt concerné
		if (!empty($emprunt['rid'])) {
			echo '<tr>';
			echo '<td class="vide"></td>';
			echo '<td>'.printDate($emprunt['date_reparation'], true).'</td>';
			echo '<td colspan="3">';
			echo '<div class="'.(empty($emprunt['repare']) ? 'degrade' : 'dispo').'">Réparation '.
				(empty($emprunt['repare']) ? 'en attente' : 'effectuée').' / <div style="display:inline" class="'.
				(empty($emprunt['rembourse']) ? 'nondispo">Non ' : 'dispo">').'remboursée</div></div>';
			echo $emprunt['prix'] !== null ? sprintf(' : %.2f €', $emprunt['prix']) : null;
			echo '</td>';
			echo '</tr>';
		}
	}

	exit;
}


//Liste des adhérents avec filtrage
else if ($args[1][0] == 'liste_adherents' &&
	isset($_POST['filtre'])) {

	//On récupère et met en forme le filtrage
	$filtres = make_filtres($_POST['filtre']);


	//Mise à jour des adherents
	$adherents = $pdo->query('SELECT id, nom, prenom, telephone, caution '.
		'FROM adherents '.
		'WHERE '.
			'id_saison = '.$saison['id'].' '.
		'ORDER BY nom ASC, prenom ASC')
		->fetchAll(PDO::FETCH_ASSOC);
	

	//On liste les adhérents en regardant s'ils sont en accord avec le filtrage
	$count = 0;
	foreach ($adherents as $adherent) {

		//Comparaison entre le filtre et la fiche
		if (!search_filtres(strtolower($adherent['nom'].' '.$adherent['prenom'].' '.$adherent['telephone']), $filtres))
			continue;

		$count++;

	?>

							<tr class="form">
								<td>
									<input type="text" name="nom[]" value="<?php echo stripslashes($adherent['nom']); ?>" />
								</td>
								<td>
									<input type="text" name="prenom[]" value="<?php echo stripslashes($adherent['prenom']); ?>" />
								</td>
								<td>
									<input type="text" name="telephone[]" value="<?php echo stripslashes($adherent['telephone']); ?>" />
								</td>

								<td>
									<select name="caution[]">
										<option value="1"<?php echo $adherent['caution'] ? ' selected' : null; ?>>Oui</option>
										<option value="0"<?php echo !$adherent['caution'] ? ' selected' : null; ?>>Non</option>
									</select>
								</td>
								<td class="actions">
									<input type="hidden" name="id[]" value="<?php echo $adherent['id']; ?>" />
									<button type="submit" name="edit" value="<?php echo $adherent['id']; ?>">
	    								<img src="<?php url('assets/images/actions/edit.png'); ?>" alt="Add" />
									</button>
									<button type="submit" name="delete" value="<?php echo $adherent['id']; ?>">
	    								<img src="<?php url('assets/images/actions/delete.png'); ?>" alt="Add" />
									</button>
								</td>
							</tr>

	<?php }

	//Aucune des fiches n'est en accord avec le filtre
	if (!$count) {
		echo '<tr class="vide">';
		echo '<td colspan="5">Aucun adhérent'.(!empty($_POST['filtre']) ? ' pour cette recherche' : null).'</td>';
		echo '</tr>';
	} 

}


//Liste du matériel avec filtrage
else if ($args[1][0] == 'liste_materiel' &&
	isset($_POST['filtre'])) {

	//On récupère et met en forme le filtrage
	$filtres = make_filtres($_POST['filtre']);


	//Récupération de la liste du matériel
	$materiels = $pdo->query('SELECT '.
			'm.id AS mid, '.
			'm.*, '.
			'e.id AS eid, '.
			'e.etat_retour, '.
			'e.etat_debut, '.
			'e.date_retour, '.
			'r.id AS rid, '.
			'r.repare '.
		'FROM materiels AS m '.
		'LEFT JOIN emprunts AS e ON '.
			'e.id_materiel = m.id AND '.
			'e.id = (SELECT MAX(eb.id) FROM emprunts AS eb WHERE eb.id_materiel = m.id) '.
		'LEFT JOIN reparations AS r ON '.
			'r.id_emprunt = e.id '.
		//'WHERE '.
		//	'm.id_saison = '.$saison['id'].' './/On conserve tous les matériels
		'ORDER BY '.
			'm.type ASC, '.
			'm.numero ASC')
		->fetchAll(PDO::FETCH_ASSOC);
	

	//On liste les adhérents en regardant s'ils sont en accord avec le filtrage
	$count = 0;
	foreach ($materiels as $materiel) {

		if (!empty($materiel['eid']))
			$materiel['etat_initial'] = $materiel[empty($materiel['date_retour']) ? 'etat_debut' : 'etat_retour'];


		//Comparaison entre le filtre et la fiche
		if (!search_filtres(strtolower($materiel['type'].' '.$materiel['numero'].' '.
				$materiel['etat_initial'].' '.$materiel['description']), $filtres))
			continue;

		$count++;
		
	?>

							<tr class="form">
								<td>
									<select name="type[]">
										<option value="skis"<?php echo $materiel['type'] == 'skis' ? ' selected' : null; ?>>Skis</option>
										<option value="snow"<?php echo $materiel['type'] == 'snow' ? ' selected' : null; ?>>Snow</option>
										<option value="other"<?php echo $materiel['type'] == 'other' ? ' selected' : null; ?>>Autre</option>
									</select>
								</td>
								<td>
									<input type="text" name="numero[]" value="<?php echo stripslashes($materiel['numero']); ?>" />
								</td>
								<td class="vide">
									<div class="<?php 
									echo !empty($materiel['eid']) && empty($materiel['date_retour']) ? 'nondispo">Emprunté' : 
										(!empty($materiel['rid']) && empty($materiel['repare']) ? 'degrade">En réparation' : 'dispo">Disponible'); ?></div>
								</td>
								<td>
									<input type="text" name="etat[]" value="<?php echo stripslashes($materiel['etat_initial']); ?>" />
								</td>
								<td>
									<textarea name="description[]"><?php echo stripslashes($materiel['description']); ?></textarea>
								</td>
								<td class="actions">
									<input type="hidden" name="id[]" value="<?php echo $materiel['id']; ?>" />
									<button type="submit" name="edit" value="<?php echo $materiel['id']; ?>">
	    								<img src="<?php url('assets/images/actions/edit.png'); ?>" alt="Edit" />
									</button>
									<button type="submit" name="delete" value="<?php echo $materiel['id']; ?>">
	    								<img src="<?php url('assets/images/actions/delete.png'); ?>" alt="Delete" />
									</button>
								</td>
							</tr>

	<?php }

	//Aucune des fiches n'est en accord avec le filtre
	if (!$count) {
		echo '<tr class="vide">';
		echo '<td colspan="6">Aucun élément'.(!empty($_POST['filtre']) ? ' pour cette recherche' : null).'</td>';
		echo '</tr>';
	} 

}


//Liste des adhérents avec filtrage pour l'auto-complétion
else if ($args[1][0] == 'liste_json_adherents' &&
	isset($_POST['filtre'])) {

	//On récupère et met en forme le filtrage
	$filtres = make_filtres($_POST['filtre']);


	//Mise à jour des adherents
	$adherents = $pdo->query('SELECT id, nom, prenom, telephone, caution '.
		'FROM adherents '.
		'WHERE '.
			'id_saison = '.$saison['id'].' '.
		'ORDER BY nom ASC, prenom ASC')
		->fetchAll(PDO::FETCH_ASSOC);
	

	//Filtrage et mise en forme des adhérents
	$adherents_json = array();
	$count = 0;
	$limit = 100;

	foreach ($adherents as $adherent) {
		
		//Comparaison entre le filtre et la fiche
		if (!search_filtres(strtolower($adherent['nom'].' '.$adherent['prenom'].' '.$adherent['telephone']), $filtres))
			continue;

		$count++;

		//On limite à 100 personnes pour ne pas alourdir
		if ($count > $limit)
			break;


		//Ajout de l'adhérent au JSON
		$adherents_json[] = array(
			'value' => stripslashes(strtoupper($adherent['nom']).' '.$adherent['prenom']),
			'caution' => $adherent['caution'],
			'id' => $adherent['id']);
	}


	//Envoi des données récupérées en JSON
    header('Content-Type: application/json', true);
	echo json_encode($adherents_json);
	exit;
}


//Liste des éléments empruntés avec filtrage pour l'auto-complétion du retour
else if ($args[1][0] == 'elements_json_empruntes' &&
	!empty($_POST['type']) &&
	in_array($_POST['type'], array('skis', 'snow', 'other')) &&
	isset($_POST['filtre'])) {

	//On récupère et met en forme le filtrage
	$filtres = make_filtres($_POST['filtre']);


	//Récupération des éléments empruntés
	$elements = $pdo->query('SELECT e.id, m.numero, a.prenom, a.nom '.
		'FROM emprunts AS e '.
		'JOIN materiels AS m ON '.
			'm.id = e.id_materiel AND '.
			//'m.id_saison = '.$saison['id'].' AND './/On conserve tous les matériels
			'm.type = "'.$_POST['type'].'" '.
		'LEFT JOIN adherents AS a ON '.
			'a.id = e.id_adherent AND '.
			'a.id_saison = '.$saison['id'].' '.
		'WHERE '.
			'e.id_saison = '.$saison['id'].' AND '.
			'e.date_retour IS NULL '.
		'ORDER BY m.numero ASC')
		->fetchAll(PDO::FETCH_ASSOC);
	

	//Filtrage et mise en forme des éléments empruntés
	$elements_json = array();
	$count = 0;
	$limit = 100;

	foreach ($elements as $element) {
		
		//Comparaison entre le filtre et la fiche
		if (!search_filtres(strtolower($element['numero'].' / '.$element['nom'].' '.$element['prenom']), $filtres))
			continue;

		$count++;

		//On limite à 100 personnes pour ne pas alourdir
		if ($count > $limit)
			break;

		//Ajout de l'élément au JSON
		$elements_json[] = array(
			'value' => $element['numero'].' / '.(empty($element['nom']) ? 'Inconnu' : 
					stripslashes(strtoupper($element['nom']).' '.$element['prenom'])),
			'id' => $element['id']);
	}

	//Envoi des données récupérées en JSON
    header('Content-Type: application/json', true);
	echo json_encode($elements_json);
	exit;
}
