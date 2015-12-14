<?php

/* *********************************************************/
/* Produit Piston : Gestion Matos et Membres ***************/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* templates/prive/reparation.php **************************/
/* Template du listing et de gestion des réparations *******/
/* *********************************************************/
/* Dernière modification : le 07/11/14 *********************/
/* *********************************************************/


//Inclusion de l'entête de page
require DIR.'templates/_header.php';

?>

			<div class="main">
				<h2>Gestion des réparations en attente</h2>

				<?php if (!empty($add) || 
						!empty($modify) ||
						!empty($delete)) { ?>

				<div class="alerte alerte-success">
					<div class="alerte-contenu">
						La réparation a bien été <?php echo !empty($add) ? 'ajoutée' : (!empty($modify) ? 'modifiée' : 'supprimée'); ?>
					</div>
				</div>

				<?php } ?>


				<form method="post">
					<table>
						<thead>
							<tr>
								<th>Type / N°</th>
								<th>Détériorateur</th>
								<th>Prix</th>
								<th style="width:120px">Remboursement</th>
								<th style="width:120px">Réparé</th>
								<th class="actions">Actions</th>
							</tr>
						</thead>

						<tbody>
							
							<?php if (!count($degradations)) { ?>

							<tr class="vide">
								<td colspan="6">Aucune réparation en attente</td>
							</tr>

							<?php } foreach ($degradations as $degradation) { ?>

							<tr class="form">
								<td>
									<div><?php echo $types[$degradation['type']].' / '.$degradation['numero']; ?></div>
								</td>
								<td>
									<div><?php echo stripslashes(strtoupper($degradation['nom']).' '.$degradation['prenom']); ?></div>
								</td>
								<td>
									<input type="number" step="any" min="0" name="prix[]" value="<?php echo $degradation['prix']; ?>" />
								</td>

								<td>
									<select name="remboursement[]">
										<option value="0"<?php if (empty($degradation['rembourse'])) echo ' selected'; ?>>Non</option>
										<option value="1"<?php if (!empty($degradation['rembourse'])) echo ' selected'; ?>>Oui</option>
									</select>
								</td>

								<td>
									<select name="repare[]">
										<option value="0"<?php if (empty($degradation['repare'])) echo ' selected'; ?>>Non</option>
										<option value="1"<?php if (!empty($degradation['repare'])) echo ' selected'; ?>>Oui</option>
									</select>
								</td>

								<td class="actions">
									<input type="hidden" name="id[]" value="<?php echo $degradation['id']; ?>" />
									<button type="submit" name="edit" value="<?php echo $degradation['id']; ?>">
	    								<img src="<?php url('assets/images/actions/edit.png'); ?>" alt="Edit" />
									</button>
									<button type="submit" name="delete" value="<?php echo $degradation['id']; ?>">
	    								<img src="<?php url('assets/images/actions/delete.png'); ?>" alt="Delete" />
									</button>
								</td>
							</tr>

							<?php } ?>

						</tbody>
					</table>
				</form>

				<h2>Archives des réparations</h2>


				<table>
					<thead>
						<tr>
							<th>Type / N°</th>
							<th>Détériorateur</th>
							<th>Prix</th>
							<th style="width:120px">Remboursement</th>
							<th style="width:120px">Réparé</th>
							<th class="actions">Actions</th>
						</tr>
					</thead>

					<tbody>
						
						<?php if (!count($archives)) { ?>

						<tr class="vide">
							<td colspan="6">Aucune réparation remboursée</td>
						</tr>

						<?php } foreach ($archives as $degradation) { ?>

						<tr>
							<td>
								<div><?php echo ucfirst($degradation['type']).' / '.$degradation['numero']; ?></div>
							</td>
							<td>
								<div><?php echo stripslashes(strtoupper($degradation['nom']).' '.$degradation['prenom']); ?></div>
							</td>
							<td>
								<div><?php echo $degradation['prix']; ?></div>
							</td>

							<td>
								<div>Oui</div>
							</td>

							<td>
								<div>Oui</div>
							</td>

							<td class="actions">
								
							</td>
						</tr>

						<?php } ?>

					</tbody>
				</table>

				<script type="text/javascript">
			    $(function() {
			    	$analysis = function(elem, event, force) {
			            if (event.keyCode == 13 || force) {
			                event.preventDefault();
			              	$parent = elem.parent().parent();
			              	$first = $parent.children('td:first');
			  				$prix = $first.next().next().children('input');
			  				$rembourse = $first.next().next().next().children('select');

			                if ($.inArray($rembourse.val(), ['0', '1']) < 0)
			                	$rembourse.animate({backgroundColor:'#FBB'}, 100, function() {
			                		$(this).animate({backgroundColor:'none'}, 1000); });
			                
			                else
			                	$parent.children('.actions').children('button:first-of-type').unbind('click').click();   
			           
			            }
			        };

			        $('td input[type=text], td input[type=number], td select, td.actions button:first-of-type').bind('keypress', function(event) {
			            $analysis($(this), event, false) });
			        $('td.actions button:first-of-type').bind('click', function(event) {
			            $analysis($(this), event, true) });
			    });
				</script>
			</div>

<?php

//Inclusion du pied de page
require DIR.'templates/_footer.php';
