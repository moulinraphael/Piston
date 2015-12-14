<?php

/* *********************************************************/
/* Produit Piston : Gestion Matos et Membres ***************/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* templates/prive/tarifs.php ******************************/
/* Template permettant de gérer les tarifs *****************/
/* *********************************************************/
/* Dernière modification : le 07/11/14 *********************/
/* *********************************************************/


//Inclusion de l'entête de page
require DIR.'templates/_header.php';

?>

			<div class="main">
				<h2>Définition des tarifs</h2>

				<?php if (!empty($error) || 
						!empty($modify)) { ?>

				<div class="alerte alerte-<?php echo !empty($error) ? 'error' : 'success'; ?>">
					<div class="alerte-contenu">
						<?php echo !empty($error) ? 'Il y a une erreur dans la syntaxe des tarifs.' : 'Les tarifs ont bien été modifiés'; ?>
					</div>
				</div>

				<?php } ?>

				<form method="post">
					<fieldset>
						<legend>Location Ski</legend>

						<label for="form-ski-we">
							<span>Week-End</span>
							<input name="ski-we" id="form-ski-we" type="number" step="any" min="0" value="<?php if ($tarifs['skis']['we'] !== false) echo $tarifs['skis']['we']; ?>" />
						</label>

						<label for="form-ski-1s">
							<span>1 semaine</span>
							<input name="ski-1s" id="form-ski-1s" type="number" step="any" min="0" value="<?php if ($tarifs['skis']['1s'] !== false) echo $tarifs['skis']['1s']; ?>" />
						</label>

						<label for="form-ski-2s">
							<span>2 semaines</span>
							<input name="ski-2s" id="form-ski-2s" type="number" step="any" min="0" value="<?php if ($tarifs['skis']['2s'] !== false) echo $tarifs['skis']['2s']; ?>" />
						</label>
					</fieldset>

					<fieldset>
						<legend>Location Snow</legend>

						<label for="form-snow-we">
							<span>Week-End</span>
							<input name="snow-we" id="form-snow-we" type="number" step="any" min="0" value="<?php if ($tarifs['snow']['we'] !== false) echo $tarifs['snow']['we']; ?>" />
						</label>

						<label for="form-snow-1s">
							<span>1 semaine</span>
							<input name="snow-1s" id="form-snow-1s" type="number" step="any" min="0" value="<?php if ($tarifs['snow']['1s'] !== false) echo $tarifs['snow']['1s']; ?>" />
						</label>

						<label for="form-snow-2s">
							<span>2 semaines</span>
							<input name="snow-2s" id="form-snow-2s" type="number" step="any" min="0" value="<?php if ($tarifs['snow']['2s'] !== false) echo $tarifs['snow']['2s']; ?>" />
						</label>

						<input type="submit" value="Mettre à jour les prix" name="maj" />
					</fieldset>
				</form>
			</div>

<?php

//Inclusion du pied de page
require DIR.'templates/_footer.php';
