<?php

/* *********************************************************/
/* Produit Piston : Gestion Matos et Membres ***************/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* templates/_error.php ************************************/
/* Template affiché pour les erreurs ***********************/
/* *********************************************************/
/* Dernière modification : le 06/11/14 *********************/
/* *********************************************************/


//Inclusion de l'entête de page
require DIR.'templates/_header.php';

?>

			<div class="main">
				<div class="alerte alerte-erreur">
					<div class="alerte-contenu">
						Une erreur vient de se produire, sans doute êtes-vous sur une page inexistante...
					</div>
				</div>
			</div>

<?php

//Inclusion du pied de page
require DIR.'templates/_footer.php';
