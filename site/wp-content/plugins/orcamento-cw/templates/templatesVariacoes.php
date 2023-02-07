<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
	<?php 
	$variacoes = $wpdb->get_results("SELECT * FROM orcamento_cw_variacoes ORDER BY id DESC");
	if($variacoes){


		$i = 1;
		foreach ($variacoes as $v){
			$in = ($i == 1) ? 'in' : '';
			?>
			<div class="panel panel-default" id="panel_<?php echo $v->id ?>">
				<div class="panel-heading" role="tab" id="<?php echo $v->id ?>">
					<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse_<?php echo $v->id ?>" aria-expanded="true" aria-controls="collapse_<?php echo $v->id ?>">
						<h4 class="panel-title">
							<b><?php echo $v->nome ?></b> <i class="fa fa-eye float-right"></i>
						</h4>
					</a>
				</div>
				<div id="collapse_<?php echo $v->id ?>" class="panel-collapse collapse <?php echo $in ?>" role="tabpanel" aria-labelledby="<?php echo $v->id ?>">
					<div class="panel-body">
						<form class="row atualizaVariacao" id="variacao-<?php echo $v->id ?>">
							<input type="hidden" name="action" value="atualizaVariacao">
							<input type="hidden" name="id" value="<?php echo $v->id ?>">
							<div class="col-12 col-md-8" variacao="<?php echo $v->id ?>">
								<div class="form-group">
									<label for="">Nome</label>
									<input type="text" class="form-control" name="nome" value="<?php echo $v->nome ?>" onkeyup="nomeVariacao('<?php echo $v->id ?>')">
								</div>

								<?php 
								if($v->tipo == 'radio' or $v->tipo == 'select' or $v->tipo == 'checkbox'){
									?>
									<div class="form-group">
										<label>Deixar marcado as opções nos produtos? 
											<button class="btn btn-sm btn-info" type="button" data-toggle="popover" title="Opções da variação" data-content="Selecione se deseja já deixar marcado as opções para o produto, ou não"><i class="fa fa-info"></i></button>
										</label>
										<div class="checkbox" style="margin-top: 0">
											<?php $checked = (unserialize($v->opcoes)['marcarOpcoes']) ? 'checked' : ''; ?>
											<label><input type="checkbox" <?php echo $checked ?> value="1" name="opcao[marcarOpcoes]"> Sim, deixar marcado</label>
										</div>
									</div>
									<label for="">Opções</label>
									<div class="opcoes">
										<?php
										if($v->opcoes){
											$opcoes = unserialize($v->opcoes);
												//printR($opcoes);
											$op = 0;
											foreach ($opcoes['itens'] as $opcao) {
												?>
												<div class="form-group input-group" id='opcao_<?php echo $v->id.'_'.$op ?>'>
													<input type="text" name="opcao[itens][]" class="form-control" value="<?php echo $opcao ?>">
													<div class="input-group-btn">
														<button type="button" class="btn btn-danger" onclick="excluiOpcao('opcao_<?php echo $v->id.'_'.$op ?>')"><i class="fa fa-trash"></i></button>
													</div>				
												</div>
												<?php
												$op++;
											}
										}
										?>
									</div>
									<div class="form-group input-group">
										<input type="text" class="form-control novaVariacao" placeholder="Nova opção">
										<div class="input-group-btn">
											<button type="button" class="btn btn-info" onclick="novaOpcao('<?php echo $v->id ?>')"><i class="fa fa-plus"></i></button>
										</div>				
									</div>
									<?php
								}else{
									?>
									<div class="form-group">
										<label>Placeholder padrão</label>
										<input type="text" class="form-control" name="opcao[placeholder]" value="<?php echo unserialize($v->opcoes)['placeholder']; ?>">
									</div>
									<?php
								}
								?>

								<div class="resp"></div>
								<div class="form-group">
									<button class="btn btn-primary btn-lg btn-block" type="submit">Atualizar</button>
								</div>

							</div>
							<div class="col-12 col-md-4">
								<div class="form-group">
									<label>Deseja marcar como "Obrigatório"?</label>
									<div class="checkbox">
										<label>
											<?php $checked = (unserialize($v->opcoes)['required']) ? 'checked' : ''; ?>
											<input type="checkbox" name="opcao[required]" <?php echo $checked ?> value="required"> Sim, obrigatório
										</label>
									</div>
								</div>
								<button class="btn btn-danger float-right" id="excluiVariacao_<?php echo $v->id ?>" type="button" onclick="excluiVariacao(<?php echo $v->id ?>)">Excluir <i class="fa fa-trash"></i></button>
							</div>

						</form>
					</div>
				</div>
			</div>
			<?php
			$i++;	
		}
	}else{
		echo '<p class="text-info">Nenhuma variação encontrada</p>';
	}

	?>
</div>
<script>
	jQuery('[data-toggle="popover"]').popover();
	jQuery('.atualizaVariacao').submit(function(){
		jQuery('#'+id+' .resp').html('');
		var id = jQuery(this).attr('id');
		jQuery('.atualizaVariacao button[type="submit"]').html('Aguarde <i class="fa fa-spinner fa-pulse"></i>'); 
		var data = jQuery(this).serialize();
		jQuery.post(ajaxurl, data, function(response) {
			jQuery('#'+id+' .resp').html(response);
			jQuery('#'+id+' .resp').animateCss('shake');
			jQuery('.atualizaVariacao button[type="submit"]').html("Atualizar"); 
		});

		return false;
	});
</script>