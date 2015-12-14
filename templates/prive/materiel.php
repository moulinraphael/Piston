<?php

/* *********************************************************/
/* Produit Piston : Gestion Matos et Membres ***************/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* templates/prive/materiel.php ****************************/
/* Template de la gestion du matériel **********************/
/* *********************************************************/
/* Dernière modification : le 09/11/14 *********************/
/* *********************************************************/


//Inclusion de l'entête de page
require DIR.'templates/_header.php';

?>

			<div class="main">
				<h2>Gestion du matériel</h2>

				<?php if (!empty($add) || 
						!empty($modify) ||
						!empty($delete)) { ?>

				<div class="alerte alerte-success">
					<div class="alerte-contenu">
						L'élément a bien été <?php echo !empty($add) ? 'ajouté' : (!empty($modify) ? 'modifié' : 'supprimé'); ?>
					</div>
				</div>

				<?php } ?>

				<div class="search">
					<input type="text" id="form-filtre" value="" placeholder="Filtre..." />
					<button type="button" id="form-search">
						<img src="<?php url('assets/images/actions/search.png'); ?>" alt="Search" />
					</button>
				</div>


				<form method="post">
					<table>
						<thead>
							<tr class="form">
								<td>
									<select name="type[]">
										<option value="skis">Skis</option>
										<option value="snow">Snow</option>
										<option value="other">Autre</option>
									</select>
								</td>
								<td>
									<input type="text" name="numero[]" placeholder="Numéro..." />
								</td>
								<td class="vide"></td>	
								<td>
									<input type="text" name="etat[]" placeholder="Etat..." />
								</td>
								<td>
									<textarea name="description[]" placeholder="Description..."></textarea>
								</td>
								<td class="actions">
									<button type="submit" name="add">
	    								<img src="<?php url('assets/images/actions/add.png'); ?>" alt="Add" />
									</button>
									<input type="hidden" name="id[]" />
								</td>
							</tr>

							<tr>
								<th>Type</th>
								<th>Numéro</th>
								<th>Disponibilité</th>
								<th>Etat actuel</th>
								<th>Description</th>
								<th class="actions">Actions</th>
							</tr>
						</thead>

						<tbody>
						

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
			  				$type = $first.children('select');
			  				$numero = $first.next().children('input');
			  				$etat = $first.next().next().next().children('input');
			  				$description = $first.next().next().next().next().children('textarea');

			                if ($.inArray($type.val(), ['skis', 'snow', 'other']) < 0)
			                	$type.animate({backgroundColor:'#FBB'}, 100, function() {
			                		$(this).animate({backgroundColor:'none'}, 1000); });
			                if (!$numero.val().trim())
			                	$numero.animate({backgroundColor:'#FBB'}, 100, function() {
			                		$(this).animate({backgroundColor:'none'}, 1000); });
			               	if (!$etat.val().trim())
			                	$etat.animate({backgroundColor:'#FBB'}, 100, function() {
			                		$(this).animate({backgroundColor:'none'}, 1000); });
			               	if (!$description.val().trim())
			                	$description.animate({backgroundColor:'#FBB'}, 100, function() {
			                		$(this).animate({backgroundColor:'none'}, 1000); });
			                
			                if ($numero.val().trim() &&
			                	$etat.val().trim() &&
			                	$description.val().trim() &&
			                	$.inArray($type.val(), ['skis', 'snow', 'other']) >= 0)
			                	$parent.children('.actions').children('button:first-of-type').unbind('click').click();   
			           
			            }
			        };

			    	var onlyOnEnter = <?php echo APP_ONLYONENTER ? 'true' : 'false'; ?>;
			        $search = function() {
			        	$('div.search input').addClass('loading');
			        	$.ajax({
						  	url: "<?php url('ajax/liste_materiel'); ?>",
						  	method: "POST",
						  	cache: false,
						  	data: {filtre: $('#form-filtre').val()},
						  	success: function(content) {
						  		$('tbody').html(content);
						  		$('div.search input').removeClass('loading');
						  		$('td input[type=text], td input[type=number], td select, td.actions button:first-of-type').bind('keypress', function(event) {
						            $analysis($(this), event, false) });
						        $('td.actions button:first-of-type').bind('click', function(event) {
						            $analysis($(this), event, true) });
						  	}
						});
					};

					$search();
					$('div.search input').bind('keyup', function(event) {
						if (event.keyCode == 13 && onlyOnEnter || !onlyOnEnter) $search(); });
					$('div.search button').bind('click', function() { $search(); });
			    });
				</script>
			</div>

<?php

//Inclusion du pied de page
require DIR.'templates/_footer.php';
