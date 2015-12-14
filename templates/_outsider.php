<?php

/* *********************************************************/
/* Produit Piston : Gestion Matos et Membres ***************/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* templates/_outsider.php *********************************/
/* Template affiché pour les accès interdits même connecté */
/* *********************************************************/
/* Dernière modification : le 06/11/14 *********************/
/* *********************************************************/

//Inclusion de l'entête de page
require DIR.'templates/_header.php';

?>

		<div class="main">
			<h2>Accès interdit</h2>
			
			<div class="alerte alerte-attention">
				<div class="alerte-contenu">
					Cette partie est privée, tu ne peux pas y accèder.
				</div>
			</div>
		</div>

<?php

//Inclusion du pied de page
require DIR.'templates/_footer.php';
	