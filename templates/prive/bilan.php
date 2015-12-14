<?php

/* *********************************************************/
/* Produit Piston : Gestion Matos et Membres ***************/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* templates/prive/bilan.php *******************************/
/* Template de l'affichage du bilan de la saison ***********/
/* *********************************************************/
/* Dernière modification : le 06/11/14 *********************/
/* *********************************************************/


//Inclusion de l'entête de page
require DIR.'templates/_header.php';

?>

			<div class="main">
				<h2>Bilan "Skis" de la saison</h2>

				<table class="table-small table-center">
					<thead>
						<tr>
							<th>Durée</th>
							<th>Quantité</th>
							<th>Recette</th>
						</tr>
					</thead>

					<tbody>
						<tr>
							<td>Week-End</td>
							<td><?php echo (int) $quantites['skis']['we']; ?></td>
							<td><?php echo $recettes['skis']['we'] === false ? '<i>Aucun tarif</i>' : sprintf('%.2f €', $recettes['skis']['we']); ?></td>
						</tr>

						<tr>
							<td>1 Semaine</td>
							<td><?php echo (int) $quantites['skis']['1s']; ?></td>
							<td><?php echo $recettes['skis']['1s'] === false ? '<i>Aucun tarif</i>' : sprintf('%.2f €', $recettes['skis']['1s']); ?></td>
						</tr>

						<tr>
							<td>2 Semaines</td>
							<td><?php echo (int) $quantites['skis']['2s']; ?></td>
							<td><?php echo $recettes['skis']['2s'] === false ? '<i>Aucun tarif</i>' : sprintf('%.2f €', $recettes['skis']['2s']); ?></td>
						</tr>

						<tr>
							<td><b>TOTAL</b></td>
							<td class="vide"></td>
							<td><?php echo $recettes['skis']['2s'] === false || $recettes['skis']['1s'] === false || $recettes['skis']['we'] === false ? '<i>Aucun tarif</i>' : sprintf('%.2f €', $recettes['skis']['2s'] + $recettes['skis']['1s'] + $recettes['skis']['we']); ?></td>
						</tr>
					</tbody>
				</table>

				<h2>Bilan "Snow" de la saison</h2>

				<table class="table-small table-center">
					<thead>
						<tr>
							<th>Durée</th>
							<th>Quantité</th>
							<th>Recette</th>
						</tr>
					</thead>

					<tbody>
						<tr>
							<td>Week-End</td>
							<td><?php echo (int) $quantites['snow']['we']; ?></td>
							<td><?php echo $recettes['snow']['we'] === false ? '<i>Aucun tarif</i>' : sprintf('%.2f €', $recettes['snow']['we']); ?></td>
						</tr>

						<tr>
							<td>1 Semaine</td>
							<td><?php echo (int) $quantites['snow']['1s']; ?></td>
							<td><?php echo $recettes['snow']['1s'] === false ? '<i>Aucun tarif</i>' : sprintf('%.2f €', $recettes['snow']['1s']); ?></td>
						</tr>

						<tr>
							<td>2 Semaines</td>
							<td><?php echo (int) $quantites['snow']['2s']; ?></td>
							<td><?php echo $recettes['snow']['2s'] === false ? '<i>Aucun tarif</i>' : sprintf('%.2f €', $recettes['snow']['2s']); ?></td>
						</tr>

						<tr>
							<td><b>TOTAL</b></td>
							<td class="vide"></td>
							<td><?php echo $recettes['snow']['2s'] === false || $recettes['snow']['1s'] === false || $recettes['snow']['we'] === false ? '<i>Aucun tarif</i>' : sprintf('%.2f €', $recettes['snow']['2s'] + $recettes['snow']['1s'] + $recettes['snow']['we']); ?></td>
						</tr>
					</tbody>
				</table>


			</div>

<?php

//Inclusion du pied de page
require DIR.'templates/_footer.php';
