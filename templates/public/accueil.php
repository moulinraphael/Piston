<?php

/* *********************************************************/
/* Produit Piston : Gestion Matos et Membres ***************/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* templates/public/accueil.php ****************************/
/* Template de l'accueil ***********************************/
/* *********************************************************/
/* Dernière modification : le 06/11/14 *********************/
/* *********************************************************/


//Inclusion de l'entête de page
require DIR.'templates/_header.php';

?>
			
			<?php if (!empty($_SESSION['user_id'])) { ?>

			<div class="main">
				<h2>Bienvenue !</h2>

				<div class="alerte alerte-success">
					<div class="alerte-contenu">
						Bonjour <b><?php echo stripslashes($vendeurs[array_search($_SESSION['user_id'], $vendeurs_id) - 1]['nom']); ?></b>, utilise les onglets ci-dessus pour effectuer une action.
					</div>
				</div>
			</div>

			<?php } else { ?>

			<div class="main">
				<h2>Choix <?php echo empty($saison) ? 'd\'une saison' :  'd\'un vendeur'; ?></h2>

				<div class="alerte alerte-info">
					<div class="alerte-contenu">
						Veuillez choisir <?php echo empty($saison) ? 'une saison' : 'un vendeur'; ?> pour continuer à effectuer une action.
					</div>
				</div>
			</div>

			<?php } ?>

<?php

//Inclusion du pied de page
require DIR.'templates/_footer.php';
