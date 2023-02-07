<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' ); ?>
<div class="row">
	<div class="col-xs-12 col-sm-9">

		<?php 
		global $wpdb;
		$variacoes = $wpdb->get_results("SELECT * FROM orcamento_cw_variacoes ORDER BY id DESC");
		if($variacoes){

			$fields = [];
			foreach ($variacoes as $v) {
				if($v->tipo == 'radio' or $v->tipo == 'select' or $v->tipo == 'checkbox'){
					$opcoes = unserialize($v->opcoes)['itens'];

					$array_variacao = array (
						'key' => caracteresEspeciais($v->nome),
						'label' => $v->nome,
						'name' => caracteresEspeciais($v->nome),
						'type' => 'checkbox',
						'conditional_logic' => array (
							'status' => 1,
							'rules' => array (
								array (
									'field' => 'exibe_'.caracteresEspeciais($v->nome),
									'operator' => '==',
									'value' => '1',
								),
							),
							'allorany' => 'all',
						),
						'choices' => $opcoes,
						'default_value' => array_keys($opcoes),
						'layout' => 'vertical',
					);
				}else{
					$array_variacao = '';
				}


				$fields[] =	array (
					'key' => 'exibe_'.caracteresEspeciais($v->nome),
					'label' => 'Exibe '.$v->nome,
					'name' => 'exibe_'.caracteresEspeciais($v->nome),
					'type' => 'true_false',
					'message' => '',
					'default_value' => 0,
				);
				$fields[] = $array_variacao;

			}
		}

		//printR($fields);
		?>


		<div class="branco">
			<h4>Crie uma nova variação</h4>
			<form id="novaVariacao">
				<input type="hidden" value="novaVariacao" name="action">
				<div class="input-group">
					<div class="input-group-prepend">
						<button type="button" class="btn-lg btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class='tipoVariacao'>Selecione o tipo</span> <span class="caret"></span></button>
						<ul class="dropdown-menu">
							<div class="col-12">
								<li><label style="font-weight: normal;"><input type="radio" name="tipo" value="radio" texto="Única escolha"> Única escolha (radio)</label></li>
								<li><label style="font-weight: normal;"><input type="radio" name="tipo" value="select" texto="Única escolha"> Única escolha (select)</label></li>
								<li><label style="font-weight: normal;"><input type="radio" name="tipo" value="checkbox" texto="Multipla escolha"> Multipla escolha (checkbox)</label></li>
								<li><label style="font-weight: normal;"><input type="radio" name="tipo" value="text" texto="Texto simples"> Texto simples (text)</label></li>
								<li><label style="font-weight: normal;"><input type="radio" name="tipo" value="textarea" texto="Texto longo"> Texto longo (textarea)</label></li>
							</div>
						</ul>
					</div><!-- /btn-group -->
					<input type="text" name="nome" class="form-control form-control-lg" placeholder="Nome variacao" required>
					<div class="input-group-append">
						<button type="submit" class="btn btn-primary form-control-lg">Criar variação</button>
					</div>				
				</div>
				<div class="resp"></div>
			</form>
			<hr>
			<h4>Minhas variações</h4>
			<div id="templatesVariacoes"></div>
		</div>
	</div>
	<div class="col-xs-12 col-sm-3">
		<?php sidebar_admin_orcamento_cw(); ?>
	</div>
</div>

<script>
	function templatesVariacoes(){
		jQuery('#templatesVariacoes').html('<h3><i class="fa fa-spinner fa-pulse"></i></h3>');
		var data = {action : 'templatesVariacoes'};
		jQuery.post(ajaxurl, data, function(response) {
			jQuery('#templatesVariacoes').html(response);
			jQuery('#templatesVariacoes').animateCss('fadeInUp');
		});
	}
	templatesVariacoes();

	jQuery('#novaVariacao input').change(function(){
		var texto = jQuery(this).attr('texto');
		jQuery('#novaVariacao .tipoVariacao').html(texto);
	});

	jQuery.fn.extend({
		animateCss: function (animationName) {
			var animationEnd = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
			jQuery(this).addClass('animated ' + animationName).one(animationEnd, function() {
				jQuery(this).removeClass('animated ' + animationName);
			});
		}
	});

	jQuery('#novaVariacao').submit(function(){
		jQuery('#novaVariacao button[type="submit"]').html('Aguarde <i class="fa fa-spinner fa-pulse"></i>'); 
		var data = jQuery(this).serialize();
		jQuery.post(ajaxurl, data, function(response) {
			jQuery('#novaVariacao .resp').html(response);
			jQuery('#novaVariacao .resp').animateCss('shake');
			jQuery('#novaVariacao button[type="submit"]').html("Nova variacao"); 
		});

		return false;
	});

	function excluiVariacao(id){
		jQuery('#excluiVariacao_'+id).html('Aguarde <i class="fa fa-spinner fa-pulse"></i>');
		var data = {action : 'excluiVariacao', id : id};
		jQuery.post(ajaxurl, data, function(response) {
			jQuery('#collapse_'+id+' .resp').html(response);
		});
	}

	function textoAleatorio(tamanho){
		var letras = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz';
		var aleatorio = '';
		for (var i = 0; i < tamanho; i++) {
			var rnum = Math.floor(Math.random() * letras.length);
			aleatorio += letras.substring(rnum, rnum + 1);
		}
		return aleatorio;
	}


	function novaOpcao(id){
		var nova = jQuery("[variacao='"+id+"'] .novaVariacao").val();
		if(nova != ''){
			var novoId = textoAleatorio(15);
			var elVariacao = '<div class="form-group input-group" id="opcao_'+novoId+'" style="display:none">'+
			'<input type="text" name="opcao[itens][]" class="form-control" value="'+nova+'">'+
			'<div class="input-group-btn">'+
			'<button type="button" class="btn btn-danger" onclick="excluiOpcao(\'opcao_'+novoId+'\')"><i class="fa fa-trash"></i></button>'+
			'</div>'+				
			'</div>';
			jQuery("[variacao='"+id+"'] .novaVariacao").val('');
			jQuery("[variacao='"+id+"'] .opcoes").append(elVariacao);
			jQuery('#opcao_'+novoId).show('slow');
			jQuery("[variacao='"+id+"'] .novaVariacao").focus();
		}else{
			alert('Insira um nome para a opcao');
		}
	}

	function excluiOpcao(id){
		jQuery('#'+id).hide('slow', function(){
			jQuery('#'+id).remove();
		});
	}

	function nomeVariacao(id){
		var nome = jQuery("[variacao='"+id+"'] input[name='nome']").val();
		jQuery('#'+id+' a h4 b').html(nome);
	}

	
</script>