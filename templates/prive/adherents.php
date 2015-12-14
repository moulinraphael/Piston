<?php

/* *********************************************************/
/* Produit Piston : Gestion Matos et Membres ***************/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* templates/prive/adherents.php ***************************/
/* Template de la gestion des adherents de la saison *******/
/* *********************************************************/
/* Dernière modification : le 07/11/14 *********************/
/* *********************************************************/


//Inclusion de l'entête de page
require DIR.'templates/_header.php';

?>

			<div class="main">
				<h2>Gestion des adhérents de la saison</h2>

				<?php if (!empty($add) || 
						!empty($modify) ||
						!empty($delete)) { ?>

				<div class="alerte alerte-success">
					<div class="alerte-contenu">
						L'adhérent a bien été <?php echo !empty($add) ? 'ajouté' : (!empty($modify) ? 'modifié' : 'supprimé'); ?>
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
									<input type="text" name="nom[]" placeholder="Nom..." />
								</td>
								<td>
									<input type="text" name="prenom[]" placeholder="Prénom..." />
								</td>
								<td>
									<input type="text" name="telephone[]" placeholder="Téléphone..." />
								</td>

								<td>
									<select name="caution[]">
										<option value="1">Oui</option>
										<option value="0">Non</option>
									</select>
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
								<th>Prénom</th>
								<th>Téléphone</th>
								<th>Caution</th>
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
			  				$nom = $first.children('input');
			  				$prenom = $first.next().children('input');
			  				$telephone = $first.next().next().children('input');
			  				$caution = $first.next().next().next().children('select');

			                if (!$nom.val().trim())
			                	$nom.animate({backgroundColor:'#FBB'}, 100, function() {
			                		$(this).animate({backgroundColor:'none'}, 1000); });
			                if (!$prenom.val().trim())
			                	$prenom.animate({backgroundColor:'#FBB'}, 100, function() {
			                		$(this).animate({backgroundColor:'none'}, 1000); });
			               	if (!$telephone.val().trim())
			                	$telephone.animate({backgroundColor:'#FBB'}, 100, function() {
			                		$(this).animate({backgroundColor:'none'}, 1000); });
			                if ($.inArray($caution.val(), ['1', '0']) < 0)
			                	$caution.animate({backgroundColor:'#FBB'}, 100, function() {
			                		$(this).animate({backgroundColor:'none'}, 1000); });
			                
			                if ($nom.val().trim() &&
			                	$prenom.val().trim() &&
			                	$telephone.val().trim() &&
			                	$.inArray($caution.val(), ['1', '0']) >= 0)
			                	$parent.children('.actions').children('button:first-of-type').unbind('click').click();   
			           
			            }
			        };

			    	var onlyOnEnter = <?php echo APP_ONLYONENTER ? 'true' : 'false'; ?>;
			        $search = function() {
			        	$('div.search input').addClass('loading');
			        	$.ajax({
						  	url: "<?php url('ajax/liste_adherents'); ?>",
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
