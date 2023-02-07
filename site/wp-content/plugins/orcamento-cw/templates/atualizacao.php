<div class="welcome-panel col-xs-12">
	<?php 
	$versao = array('action' => 'pluginOrcamento/verificaVersao');
	$api = api_cw($versao);
	if($api->status == 'success'){
		if($api->ultimaVersao == versao_cw() ){
			/*Ultima versao OK*/
			?>
			<div class="alert alert-success">
				<h2 style="margin-top: 0">É isso ai!</h2>
				<p>Você está usando a última versão (<b><?php echo $api->ultimaVersao ?></b>).</p>
			</div>
			<h4>Descrição - <?php echo $api->ultimaVersao ?></b></h4>
			<p><?php echo $api->info ?></p>
			<?php
		}else{
			/*Atualizacao pendente*/
			?>
			<div class="alert alert-warning">
				<p>Temos novidades!</p>
				<p>Bora melhorar?? Então atualiza para nova versão - <b><?php echo $api->ultimaVersao ?></b></p>
			</div>
			<div class="resp-atualizacao"></div>
			<div class="row">
				<div class="col-xs-12 col-sm-8">
					<h4>Descrição</h4>
					<p><?php echo $api->info ?></p>
				</div>
				<div class="col-xs-12 col-sm-4 text-right">
					<button class="btn btn-primary btn-lg atualiza-plugin" onclick="atualiza_plugin_cw();"><b>ATUALIZAR <i class="fa fa-chevron-right"></i></b></button>
				</div>

				<div class="col-xs-12">
					<div class="panel-group" id="accordion" role="tablist">
						<div class="panel panel-default">
							<div class="panel-heading" role="tab" id="headingTwo">
								<h4 class="panel-title">
									<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#detalhesAtualizacao" aria-expanded="false" aria-controls="detalhesAtualizacao">
										Arquivos <i class="fa fa-chevron-down pull-right"></i>
									</a>
								</h4>
							</div>
							<div id="detalhesAtualizacao" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
								<div class="panel-body">
									<ul>
										<?php 
										foreach ($api->arquivos as $arquivo) {
											echo '<li>'.$arquivo.'</li>';
										}
										?>					
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
		}
	}else{
		?>
		<div class="alert alert-danger"><b>Ops!</b> Ocorreu um erro: <?php echo $api->motivo ?>.</div>
		<?php
	}
	?>
</div>