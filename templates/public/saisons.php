<?php

/* *********************************************************/
/* Produit Piston : Gestion Matos et Membres ***************/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* templates/public/vendeurs.php ***************************/
/* Template de la gestion des vendeurs de la saison ********/
/* *********************************************************/
/* Dernière modification : le 07/11/14 *********************/
/* *********************************************************/


//Inclusion de l'entête de page
require DIR.'templates/_header.php';

?>

			<div class="main">
				<h2>Gestion des saisons</h2>

				<?php if (!empty($add) || 
						!empty($modify) ||
						!empty($delete)) { ?>

				<div class="alerte alerte-success">
					<div class="alerte-contenu">
						La saison a bien été <?php echo !empty($add) ? 'ajoutée' : (!empty($modify) ? 'modifiée' : 'supprimée'); ?>
					</div>
				</div>

				<?php } ?>

				<form method="post">
					<table class="table-small">
						<thead>

							
							<tr class="form">
								<td>
									<input type="text" name="nom[]" placeholder="Nom..." />
								</td>
								<td class="actions">
									<button type="submit" name="add">
	    								<img src="<?php url('assets/images/actions/add.png'); ?>" alt="Add" />
									</button>
									<input type="hidden" name="id[]" />
								</td>
							</tr>

							<tr>
								<th>Nom</th>
								<th class="actions">Actions</th>
							</tr>
						</thead>

						<tbody>
							
							<?php if (!count($saisons)) { ?>

							<tr class="vide">
								<td colspan="2">Aucune saison</td>
							</tr>

							<?php } foreach ($saisons as $saison_i) { ?>

							<tr class="form">
								<td>
									<input type="text" name="nom[]" value="<?php echo stripslashes($saison_i['nom']); ?>" />
								</td>
								<td class="actions">
									<input type="hidden" name="id[]" value="<?php echo $saison_i['id']; ?>" />
									<button type="submit" name="edit" value="<?php echo $saison_i['id']; ?>">
	    								<img src="<?php url('assets/images/actions/edit.png'); ?>" alt="Edit" />
									</button>
									<button type="submit" name="switch" value="<?php echo $saison_i['id']; ?>">
	    								<img src="<?php url('assets/images/actions/'.(!empty($saison) && $saison['id'] == $saison_i['id'] ? 'inactive' : 'active').'.png'); ?>" alt="Switch" />
									</button>
									<button type="submit" name="delete" value="<?php echo $saison_i['id']; ?>">
	    								<img src="<?php url('assets/images/actions/delete.png'); ?>" alt="Delete" />
									</button>
								</td>
							</tr>

							<?php } ?>

						</tbody>
					</table>
				</form>

				<script type="text/javascript">
			    $(function() {
			    	$analysis = function(elem, event, force) {
			            if (event.keyCode == 13 || force) {
			                event.preventDefault();
			              	$parent = elem.parent().parent();
			              	$first = $parent.children('td:first');
			  				$nom = $first.children('input');

			                if (!$nom.val().trim())
			                	$nom.animate({backgroundColor:'#FBB'}, 100, function() {
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
