<?php

/* *********************************************************/
/* Produit Piston : Gestion Matos et Membres ***************/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* templates/prive/location.php ****************************/
/* Template de l'ajout d'un emprunt ************************/
/* *********************************************************/
/* Dernière modification : le 09/11/14 *********************/
/* *********************************************************/


//Inclusion de l'entête de page
require DIR.'templates/_header.php';

?>

			<div class="main">
				<h2>Ajout d'un emprunt</h2>

				<?php if (!empty($add)) { ?>

				<div class="alerte alerte-success">
					<div class="alerte-contenu">
						L'emprunt a bien été enregistré
					</div>
				</div>

				<?php } ?>


				<form method="post">
					<fieldset>
						<label for="form-vendeur">
							<span>Vendeur</span>
							<?php echo stripslashes($vendeur_actif['nom']); ?>
						</label>

						<label for="form-adherent">
							<span>Emprunteur</span>
							<input type="text" id="form-adherent-auto" value="" />
							<input type="hidden" name="adherent" id="form-adherent" value="" />
						</label>

						<label id="prevent-caution">
							<span>Caution</span>
							<div class="nondispo">Caution non payée</div>
						</label>

						<label for="form-dernier" id="label-dernier" class="form-hide">
							<span>Derniers emprunts</span>
							<div></div>
						</label>

						<label for="form-type" id="label-type" class="form-hide">
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

						<label for="form-description" id="label-description" class="form-hide">
							<span>Description</span>
							<div></div>
						</label>

						<label for="form-etat" id="label-etat" class="form-hide">
							<span>Etat</span>
							<input name="etat" id="form-etat" type="text" />
						</label>

						<label for="form-batons" id="label-batons" class="form-hide">
							<span>Bâtons</span>
							<select name="batons" id="form-batons">
								<option value="" selected disabled></option>
								<option value="1">Oui</option>
								<option value="0">Non</option>
							</select>
						</label>

						<label for="form-duree" id="label-duree" class="form-hide">
							<span>Durée</span>
							<select name="duree" id="form-duree">
								<option value="" selected disabled></option>
								<option value="we">Week-end</option>
								<option value="1s">1 semaine</option>
								<option value="2s">2 semaines</option>
							</select>
						</label>

						<label for="form-tarif" id="label-tarif" class="form-hide">
							<span>Tarif</span>
							<div></div>
						</label>

						<input type="submit" value="Ajouter l'emprunt" name="ajout" id="form-submit" class="form-hide" />
					</fieldset>
				</form>

				<script type="text/javascript">
			    $(function() {
			    	$('#prevent-caution').addClass('form-hide');

			    	$('#form-adherent').bind('change', function() {
			    		$('#form-adherent-auto').addClass('disabled');
			    		$('#prevent-caution').addClass('form-hide');
			    		
			    		if (parseInt($('#form-adherent').prop('caution')) == 0)
			    			$('#prevent-caution').removeClass('form-hide');

			    		else {
				    		$.ajax({
							  	url: "<?php url('ajax/dernier_emprunt'); ?>",
							  	method: "POST",
							  	cache: false,
							  	data: {adherent: $('#form-adherent').val()},
							  	success: function(content) {
									$('#label-dernier').removeClass('form-hide');
									$('#label-dernier div').html(content);
									$('#label-type').removeClass('form-hide')
									$('#form-type').removeClass('disabled');
									$("#form-type").val($("#form-type option:first").val());
									$('#label-numero').addClass('form-hide');
									$('#label-description').addClass('form-hide');
									$('#label-etat').addClass('form-hide');
									$('#label-batons').addClass('form-hide');
									$('#label-duree').addClass('form-hide');
									$('#label-tarif').addClass('form-hide');
									$('#form-submit').addClass('form-hide');
								}
							});
						}
			    	});

			    	$('#form-type').bind('change', function() {
			    		$(this).addClass('disabled');
			    		$.ajax({
						  	url: "<?php url('ajax/elements_dispo'); ?>",
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
								$('#label-description').addClass('form-hide');
								$('#label-etat').addClass('form-hide');
								$('#label-batons').addClass('form-hide');
								$('#label-duree').addClass('form-hide');
								$('#label-tarif').addClass('form-hide');
								$('#form-submit').addClass('form-hide');
							}
		                });
			    	});

			    	$('#form-numero').bind('change', function() {
			    		$(this).addClass('disabled');
			    		$.ajax({
						  	url: "<?php url('ajax/infos_element'); ?>",
						  	method: "POST",
						  	cache: false,
    						dataType: 'json',
						  	data: {element: $('#form-numero').val()},
						  	success: function(content) {
						  		$('#label-description').removeClass('form-hide');
								$('#label-etat').removeClass('form-hide');
								$('#label-description div').html(content.description.replace(/\n+/g, '<br />'));
								$('#label-etat input').val(content.etat);
								$('#label-batons').removeClass('form-hide')
								$('#form-batons').removeClass('disabled');
								$("#form-batons").val($("#form-batons option:first").val());
								$('#label-duree').addClass('form-hide');
								$('#label-tarif').addClass('form-hide');
								$('#form-submit').addClass('form-hide');
						  	}
						});
			    	});

			    	$('#form-batons').bind('change', function() {
			    		$(this).addClass('disabled');
			    		$('#label-duree').removeClass('form-hide')
			    		$('#form-duree').removeClass('disabled');
			    		$("#form-duree").val($("#form-duree option:first").val());
						$('#label-tarif').addClass('form-hide');
						$('#form-submit').addClass('form-hide');
			    	});

			    	$('#form-duree').bind('change', function() {
			    		$(this).addClass('disabled');
			    		$.ajax({
						  	url: "<?php url('ajax/calcul_tarif'); ?>",
						  	method: "POST",
						  	cache: false,
						  	data: {type: $('#form-type').val(), duree: $('#form-duree').val()},
						  	success: function(content) {
						  		$('#label-tarif').removeClass('form-hide');
								$('#label-tarif div').html(content);
						  		$('#form-submit').removeClass('form-hide');
						  	}
						});
			    	});

			    	$('.main form').bind('submit', function(event) {
			    		$etat = $('#form-etat');

			    		if (!$etat.val().trim()) {
			    			event.preventDefault();
			    			$etat.animate({backgroundColor:'#FBB'}, 100, function() {
			                	$(this).animate({backgroundColor:'none'}, 1000); });
			    		}
			    	});

			    	var canSearch = false;
			    	var onlyOnEnter = <?php echo APP_ONLYONENTER ? 'true' : 'false'; ?>;
				    $("#form-adherent-auto").autocomplete({
				        source: function( request, response ) {
							$.ajax({
								url: "<?php url('ajax/liste_json_adherents'); ?>",
							  	method: "POST",
							  	cache: false,
								dataType: "json",
								data:{filtre:request.term},
								success: function(data) {
									response(data);
									if ($.isEmptyObject(data))
										$("#form-adherent-auto").animate({backgroundColor:'#FBB'}, 100, function() {
			                				$(this).animate({backgroundColor:'none'}, 1000); });
								}
							});
						},
				        minLength:0,
				        select: function(e, ui) {
				            e.preventDefault();
				           	$("#form-adherent").prop('caution', ui.item.caution);
				            $("#form-adherent").val(ui.item.id).trigger('change');
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
				    	$("#form-adherent-auto").removeClass('disabled');
			        	$('#label-dernier').addClass('form-hide');
						$('#label-type').addClass('form-hide')
						$('#label-numero').addClass('form-hide');
						$('#label-description').addClass('form-hide');
						$('#label-etat').addClass('form-hide');
						$('#label-batons').addClass('form-hide');
						$('#label-duree').addClass('form-hide');
						$('#label-tarif').addClass('form-hide');
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
