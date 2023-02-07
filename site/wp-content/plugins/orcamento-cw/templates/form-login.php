
<div class="row">
	<div class="col-12 col-md-6">
		<form class="cw_form_ajax" id="cw_form_login">
			<?php wp_nonce_field('ajax-login-nonce', 'security'); ?> 
			<input type="hidden" name="action" value="form_login">
			<h3>Login <br><small>Entre com seu login e senha.</small></h3>
			<div class="form-group">
				<label>Usuário ou email</label>
				<input type="text" name="usuario" placeholder="Seu email" class="form-control input-lg">
			</div>
			<div class="form-group">
				<label>Senha</label>
				<input type="password" name="senha" placeholder="Senha" class="form-control input-lg">
			</div>
			<div class="resp"></div>
			<div class="form-group">
				<button type="submit" class="btn btn-primary btn-block btn-lg">ENTRAR <i class="fa fa-chevron-right"></i></button>
			</div>
			<p><a href="<?php echo wp_lostpassword_url(); ?>">Esqueceu a senha?</a></p>
		</form>
	</div>
	<div class="col-12 col-md-6">
		<form class="cw_form_ajax" id="cw_form_cadastro">
			<?php wp_nonce_field('ajax-register-nonce', 'signonsecurity'); ?> 
			<input type="hidden" name="action" value="form_cadastro">
			<h3>Cadastro <br><small>Faça o seu cadastro.</small></h3>						
			<div class="form-group">
				<label>Seu nome</label>
				<input type="text" name="nome" placeholder="Nome" class="form-control input-lg" required>
			</div>
			<div class="form-group">
				<label>Email</label>
				<input type="email" name="email" placeholder="Seu email" class="form-control input-lg" required>
			</div>
			<div class="form-group">
				<label>Senha</label>
				<input type="password" id="password" name="senha" placeholder="Crie uma senha" class="form-control input-lg" required>
			</div>
			<div class="resp"></div>
			<div class="form-group">
				<button type="submit" class="btn btn-primary btn-block btn-lg">CADASTRAR <i class="fa fa-chevron-right"></i></button>
			</div>
		</form>
	</div>

</div>

