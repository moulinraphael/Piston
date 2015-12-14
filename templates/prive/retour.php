<?php

/* *********************************************************/
/* Produit Piston : Gestion Matos et Membres ***************/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* templates/prive/retour.php ******************************/
/* Template de la gestion des adherents de la saison *******/
/* *********************************************************/
/* Dernière modification : le 09/11/14 *********************/
/* *********************************************************/


//Inclusion de l'entête de page
require DIR.'templates/_header.php';

?>

			<div class="main">
				<h2>Retour d'un emprunt</h2>

				<?php if (!empty($add)) { ?>

				<div class="alerte alerte-success">
					<div class="alerte-contenu">
						Le retour a bien été enregistré
					</div>
				</div>

				<?php } ?>


				<form method="post">
					<fieldset>
						<label for="form-type">
							<span>Type</span>
							<select name="type" id="form-type">
								<option value="" selected disabled></option>
								<option value="skis">Skis</option>
								<option value="snow">Snow</option>
								<option value="other">Autre</option>
							</select>
						</label>

						<label for="form-numero" id="label-numero" class="form-hide">
							<span>Numéro / Emprunteur</span>
							<input type="text" id="form-numero-auto" value="" />
							<input type="hidden" name="numero" id="form-numero" value="" />
						</label>

						<label for="form-etat" id="label-etat" class="form-hide">
							<span>Etat</span>
							<div></div>
						</label>

						<label for="form-vendeur" id="label-vendeur" class="form-hide">
							<span>Vendeur</span>
							<div></div>
						</label>

						<label for="form-batons" id="label-batons" class="form-hide">
							<span>Bâtons</span>
							<div></div>
						</label>

						<label for="form-duree" id="label-duree" class="form-hide">
							<span>Durée</span>
							<div></div>
						</label>

						<label for="form-deterioration" id="label-deterioration" class="form-hide">
							<span>Déterioration</span>
							<select name="deterioration" id="form-deterioration">
								<option value="" selected disabled></option>
								<option value="1">Oui</option>
								<option value="0">Non</option>
							</select>
						</label>

						<label for="form-nouveletat" id="label-nouveletat" class="form-hide">
							<span>Nouvel Etat</span>
							<input name="etat" id="form-nouveletat" type="text" />
						</label>

						<input type="submit" id="form-submit" value="Enregistrer le retour" name="delete" class="form-hide" />
					</fieldset>
				</form>

				<script type="text/javascript">
			    $(function() {
			    	$('#form-type').bind('change', function() {
			    		$(this).addClass('disabled');
			    		$('#label-numero').removeClass('form-hide')
						$('#form-numero-auto').removeClass('disabled');
						$('#label-etat').addClass('form-hide');
						$('#label-duree').addClass('form-hide');
						$('#label-batons').addClass('form-hide');
						$('#label-vendeur').addClass('form-hide');
						$('#label-deterioration').addClass('form-hide');
						$('#label-nouveletat').addClass('form-hide');
						$('#form-submit').addClass('form-hide');
			    	});

					$('#form-numero').bind('change', function() {
			    		$('#form-numero-auto').addClass('disabled');
			    		$.ajax({
						  	url: "<?php url('ajax/infos_emprunt'); ?>",
						  	method: "POST",
						  	cache: false,
						  	dataType: 'json',
						  	data: {emprunt: $('#form-numero').val()},
						  	success:function(content) {
								$('#label-etat').removeClass('form-hide');
								$('#label-duree').removeClass('form-hide');
								$('#label-batons').removeClass('form-hide');
								$('#label-batons div').html(content.batons ? 'Oui' : 'None');
								$('#label-duree div').html(content.duree);
								$('#label-etat div').html(content.etat);
								$('#label-vendeur').removeClass('form-hide');
								$('#label-vendeur div').html(content.nom);
								$('#label-deterioration').removeClass('form-hide');
								$('#form-deterioration').removeClass('disabled');
								$("#form-deterioration").val($("#form-deterioration option:first").val());
								$('#form-nouveletat').val(content.etat);
								$('#form-submit').addClass('form-hide');
							}
						});
			    	});

			    	$('#form-deterioration').bind('change', function() {
			    		$(this).addClass('disabled');
						$('#form-submit').removeClass('form-hide');
						$('#label-nouveletat').removeClass('form-hide');
			    	});

			    	$('.main form').bind('submit', function(event) {
			    		$etat = $('#form-nouveletat');
			    		$deterioration = $('#form-deterioration');

			    		if (!$etat.val().trim() ||
			    			$.inArray($deterioration.val(), ['1', '0']) < 0 ||
			    			$deterioration.val() === null) {
			    			event.preventDefault();
			    			$etat.animate({backgroundColor:'#FBB'}, 100, function() {
			                	$(this).animate({backgroundColor:'none'}, 1000); });
			    		}
			    	});

			    	var canSearch = false;
			    	var onlyOnEnter = <?php echo APP_ONLYONENTER ? 'true' : 'false'; ?>;
			    	$("#form-numero-auto").autocomplete({
				        source: function( request, response ) {
							$.ajax({
								url: "<?php url('ajax/elements_json_empruntes'); ?>",
							  	method: "POST",
							  	cache: false,
								dataType: "json",
								data:{filtre:request.term,type: $('#form-type').val()},
								success: function(data) {
									response(data);
									if ($.isEmptyObject(data))
										$("#form-numero-auto").animate({backgroundColor:'#FBB'}, 100, function() {
			                				$(this).animate({backgroundColor:'none'}, 1000); });
								}
							});
						},
				        minLength:0,
				        select: function(e, ui) {
				            e.preventDefault();
				            $("#form-numero").val(ui.item.id).trigger('change');
				            $("#form-adherent-auto").removeAttr('style').addClass('disabled');
				            $(this).val(ui.item.value);
				            $(':focus').blur();
				        },
				        search: function (e, ui) {
				        	var canTempSearch = canSearch;
				        	canSearch = false;
				        	return !onlyOnEnter || onlyOnEnter && canTempSearch;
				        }
				    }).bind('keyup', function(e) {
				    	$("#form-numero-auto").removeClass('disabled');
				    	$('#label-etat').addClass('form-hide');
						$('#label-duree').addClass('form-hide');
						$('#label-batons').addClass('form-hide');
						$('#label-vendeur').addClass('form-hide');
						$('#label-deterioration').addClass('form-hide');
						$('#form-submit').addClass('form-hide');

				    	if (e.keyCode == 13) {
				    		canSearch = true;
				    		$(this).autocomplete("search", $(this).val());
				    	}
				    }).focus(function(){
				    	if (!onlyOnEnter)
				    		$(this).autocomplete("search");        
			        });
			    });
				</script>
			</div>

<?php

//Inclusion du pied de page
require DIR.'templates/_footer.php';
