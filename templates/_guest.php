<?php

/* *********************************************************/
/* Produit Piston : Gestion Matos et Membres ***************/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* templates/_guest.php ************************************/
/* Page affichée pour les non connectés ********************/
/* *********************************************************/
/* Dernière modification : le 06/11/14 *********************/
/* *********************************************************/


//Inclusion de l'entête de page
require DIR.'templates/_header.php';

?>


		<div class="main">
			<h2>Connexion requise</h2>
			
			<div class="alerte alerte-attention">
				<div class="alerte-contenu">
					Pour utiliser ce produit, nous t'invitons à te <a href="<?php url('?login'); ?>">connecter</a>.
				</div>
			</div>
		</div>

<?php

//Inclusion du pied de page
require DIR.'templates/_footer.php';
	