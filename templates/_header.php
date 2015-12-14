<?php

/* *********************************************************/
/* Produit Piston : Gestion Matos et Membres ***************/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* templates/_header.php ***********************************/
/* Haut de page ********************************************/
/* *********************************************************/
/* Dernière modification : le 07/11/14 *********************/
/* *********************************************************/


?>

<!DOCTYPE html>
<html>
	<head>
		<title>Gestion du matériel et des membres - Piston Ski Club</title>
		
		<!-- Balises Meta -->
		<meta charset="utf8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
		<meta name="author" content="Raphael Kichot' Moulin" />
		<meta name="description" content="Piston Ski Club 2014 : Gestion du matériel et des membres" />
		<meta name="keywords" content="" />
			
		<!-- Feuilles de style CSS -->
		<link rel="stylesheet" media="all" href="<?php url('assets/css/global.css'); ?>" />
		<link rel="stylesheet" media="all" href="<?php url('assets/css/piston.css'); ?>" />
		<link rel="stylesheet" media="print" href="<?php url('assets/css/print.css'); ?>" />

		<!-- Icones -->
		<link rel="shortcut icon" href="<?php url('assets/images/ico/favicon.ico'); ?>" type="image/x-icon" />
		<link rel="icon" href="<?php url('assets/images/ico/favicon.ico'); ?>" type="image/x-icon" />
	
		<!-- Scripts Javascript -->
		<script type="text/javascript" src="<?php url('assets/js/jquery-1.10.2.min.js'); ?>"></script>
		<script type="text/javascript" src="<?php url('assets/js/jquery-ui-1.10.4.custom.min.js'); ?>"></script>
	</head>

	<body>
		<noscript><div>Le site nécessite JavaScript pour fonctionner</div></noscript>
		<div class="container nojs">
			<header class="noprint">

				<?php if (!empty($saison)) { ?>

				<form method="post">
					<select name="vendeur" onchange="return $('header form').submit();">
						<optgroup label="Vendeurs">

						<?php
							if (!isset($_SESSION['user_id']))
								$_SESSION['user_id'] = 0;
							
							foreach ($vendeurs as $vendeur) {
								if ($_SESSION['user_id'] == $vendeur['id'])
									$vendeur_actif = $vendeur;

								echo '<option value="'.$vendeur['id'].'"'.
									($_SESSION['user_id'] == $vendeur['id'] ? ' selected' : '').'>';
								echo stripslashes($vendeur['nom']);
								echo '</option>';
							}
						?>

						</optgroup>
						<optgroup label="Administration">
							<option value="0"<?php if ($_SESSION['user_id'] == 0)
								echo ' selected'; ?>>Déconnecté</option>
							<option value="-1">Gérer les vendeurs</option>
						</optgroup>
					</select>
				</form>

				<?php } ?>

				<a href="<?php url('accueil') ?>">
					Piston Ski Club
					<small class="nowrap">

						<?php echo !empty($saison) ? 'Saison '.stripslashes($saison['nom']) : 'Pas de saison...'; ?>

					</small>
				</a>
			</header>

			<div class="menus noprint">
				<nav>
					<ul><!--

					<?php if (!empty($_SESSION['user_id'])) { ?>

						--><li>
							<span>Location</span>
							<ul>
								<li><a href="<?php url('location'); ?>">Emprunt</a></li>
								<li><a href="<?php url('location/retour'); ?>">Retour</a></li>
								<li><a href="<?php url('location/historique'); ?>">Historique</a></li>
								<li><a href="<?php url('location/tarifs'); ?>">Tarifs</a></li>
							</ul>
						</li><!--

						--><li>
							<a href="<?php url('adherents'); ?>">Adhérents</a>
						</li><!--

						--><li>
							<span>Gestion Matériel</span>
							<ul>
								<li><a href="<?php url('materiel/reparation'); ?>">Réparation</a></li>
								<li><a href="<?php url('materiel'); ?>">Matériel</a></li>
							</ul>
						</li><!--

						--><li>
							<a href="<?php url('bilan'); ?>">Bilan</a>
						</li><!--

					<?php } ?>

						--><li>
							<a href="<?php url('saisons'); ?>">Saisons</a>
						</li><!--
					--></ul>
				</nav>
			</div>
		