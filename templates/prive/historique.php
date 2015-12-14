<?php

/* *********************************************************/
/* Produit Piston : Gestion Matos et Membres ***************/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* templates/prive/historique.php **************************/
/* Template permettant d'afficher l'historique *************/
/* *********************************************************/
/* Dernière modification : le 08/11/14 *********************/
/* *********************************************************/


//Inclusion de l'entête de page
require DIR.'templates/_header.php';

?>

			<div class="main">
				<h2>Historique d'un élèment</h2>

				<?php if (!empty($modify)) { ?>

				<div class="alerte alerte-success">
					<div class="alerte-contenu">
						La dégradation concernant le dernier emprunt a bien été enregistrée
					</div>
				</div>

				<?php } ?>

				<form method="post">
					<fieldset>
						<label for="form-type" id="label-type">
							<span>Type</span>
							
							<select name="type" id="form-type">
								<option value="" selected disabled></option>
								<option value="skis">Skis</option>
								<option value="snow">Snow</option>
								<option value="other">Autre</option>
							</select>
						</label>

						<label for="form-numero" id="label-numero" class="form-hide">
							<span>Numéro</span>
							<select name="numero" id="form-numero">
								<option value="" selected disabled></option>
							</select>
						</label>

						<label for="form-dispo" id="label-dispo" class="form-hide">
							<span>Disponibilité</span>
							<div></div>
						</label>

						<label for="form-adherent" id="label-adherent" class="form-hide">
							<span>Dernier emprunteur</span>
							<div></div>
						</label>

						<label for="form-etat" id="label-etat" class="form-hide">
							<span>Etat</span>
							<div></div>
						</label>

						<input type="submit" value="Déclarer comme détérioré" name="deteriore" id="form-submit" class="form-hide" />
					</fieldset>
				</form>

				<div id="form-table" class="form-hide">
				<table class="table-center">
					<thead>
						<tr>
							<th>Emprunteur</th>
							<th>Date</th>
							<th>Retour</th>
							<th>Etat initial</th>
							<th>Etat final</th>
						</tr>
					</thead>

					<tbody>

					</tbody>
				</table>
				</div>

				<script type="text/javascript">
			    $(function() {
			    	$('#form-type').bind('change', function() {
			    		$(this).addClass('disabled');
			    		$.ajax({
						  	url: "<?php url('ajax/tous_les_elements'); ?>",
						  	method: "POST",
						  	cache: false,
						  	dataType: 'json',
						  	data: {type: $('#form-type').val()},
						  	success:function(content) {
								$('#label-numero').removeClass('form-hide')
								$('#form-numero').removeClass('disabled');
								$("#form-numero option:gt(0)").remove();
								$("#form-numero").val($("#form-numero option:first").val());
								$.each(content,function(index,item){
							    	$('#form-numero').children('option:last').
							    		after('<option value="' + item.id + '">' + item.numero + '</option>');
							    });
								$('#label-etat').addClass('form-hide');
								$('#label-dispo').addClass('form-hide');
								$('#label-adherent').addClass('form-hide');
								$('#form-submit').addClass('form-hide');
								$('#form-table').addClass('form-hide');
								$('.main form').bind('submit', function() { return false; });
							}
						});
			    	});

			    	$('#form-numero').bind('change', function() {
			    		$(this).addClass('disabled');
			    		$.ajax({
						  	url: "<?php url('ajax/infos_element_histo'); ?>",
						  	method: "POST",
						  	cache: false,
						  	dataType: 'json',
						  	data: {element: $('#form-numero').val()},
						  	success:function(content) {
								$('#label-etat').removeClass('form-hide');
								$('#label-etat div').html(content.etat);
								$('#label-dispo').removeClass('form-hide');
								$('#label-dispo div').html(content.dispo);
								$('#label-adherent').removeClass('form-hide');
								$('#label-adherent div').html(content.adherent);
								$('.main form').bind('submit', function() { return false; });
								$('#form-submit').removeClass('form-hide');
								$('#form-submit').attr('disabled', 'disabled');
								if (content.emprunt_rendu) {
									$('#form-submit').removeAttr('disabled');
									$('.main form').unbind('submit').bind('submit', function() { });
								}
								$('#form-table tbody').html('');
								$('#form-table').removeClass('form-hide');
					    		$.ajax({
								  	url: "<?php url('ajax/historique_element'); ?>",
								  	method: "POST",
								  	cache: false,
								  	data: {element: $('#form-numero').val()},
								  	success:function(content) {
										$('#form-table tbody').html(content);
									}
								});
							}
						});
			    	});
				});
				</script>
			</div>

<?php

//Inclusion du pied de page
require DIR.'templates/_footer.php';
