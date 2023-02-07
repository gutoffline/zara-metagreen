<?php
$userId = get_current_user_id();
if (!$userId) {
    include(plugin_dir_path(__FILE__) . 'form-login.php');
} else {
    $user = wp_get_current_user();
?>
    <div id="cw_minha_conta">
        <div class="boasVindas">
            <p>
                Olá <b><?php echo $user->display_name ?> <a href="<?php the_permalink() ?>?meus_dados=true">(editar)</a></b>, seja bem vindo(a)! <a class="float-right btn btn-secondary" href="<?php echo wp_logout_url(get_permalink()); ?>">Sair <i class="fa fa-sign-out-alt"></i></a>
            </p>
        </div>
        <?php
        if (isset($_GET['orcamento'])) {
        ?>
            <a href="javascript: history.go(-1);" class="btn btn-light"><i class="fa fa-arrow-left"></i> Voltar</a>
            <br>
            <br>
            <?php
            $id_orcamento = $_GET['orcamento'];
            $orcamento = $wpdb->get_results("SELECT * FROM orcamentos_cw_pedidos WHERE idUser = '$userId' and id = '$id_orcamento'")[0];
            if ($orcamento) {
                echo '<h2>Orçamento #' . $id_orcamento . '</h2>';

                echo $orcamento->orcamento;

                $respostas = $wpdb->get_results("SELECT * FROM orcamentos_cw_resposta WHERE id_orcamento = '$id_orcamento'");

                if ($respostas) {
                    echo '<h4>Respostas</h4>';
                    foreach ($respostas as $resposta) {
                        echo $resposta->resposta;
                    }
                } else {
            ?>
                    <div class="alert alert-info">Aguardando resposta</div>
                <?php
                }
            } else {
                ?>
                <div class="alert alert-danger"><b>Erro!</b> Orçamento não encontrado.</div>
            <?php
            }
        } elseif (isset($_GET['meus_dados'])) {
            ?>
            <form class="cw_form_ajax" id="meus_dados">
                <input type="hidden" name="action" value="meus_dados">
                <?php wp_nonce_field('ajax-login-nonce', 'security'); ?>

                <div class="form-group text-right">
                    <a href="<?php echo wp_lostpassword_url(); ?>" class="btn btn-info"><i class="fa fa-lock"></i> Atualizar senha</a>
                </div>
                <div class="row">
                    <div class="col-12 col-md-6">
                        <h3>Meus dados</h3>

                        <div class="form-group">
                            <label>Nome</label>
                            <input type="text" class="form-control" name="nome" value="<?php echo $user->display_name ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="text" class="form-control" name="email" value="<?php echo $user->user_email ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Telefone</label>
                            <input type="tel" class="form-control" name="telefone" value="<?php echo get_the_author_meta('telefone', $userId) ?>">
                        </div>
                        <div class="form-group">
                            <label>Empresa</label>
                            <input type="text" class="form-control" name="empresa" value="<?php echo get_the_author_meta('empresa', $userId) ?>">
                        </div>
                        <div class="form-group">
                            <label>CNPJ</label>
                            <input type="text" class="form-control" name="cnpj" value="<?php echo get_the_author_meta('cnpj', $userId) ?>">
                        </div>
                        <div class="form-group">
                            <label>CPF</label>
                            <input type="text" class="form-control" name="cpf" value="<?php echo get_the_author_meta('cpf', $userId) ?>">
                        </div>
                        <div class="form-group">
                            <label>RG</label>
                            <input type="text" class="form-control" name="rg" value="<?php echo get_the_author_meta('rg', $userId) ?>">
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <h3>Endereço</h3>
                        <div class="form-group">
                            <label>CEP</label>
                            <input type="text" id="cw-cep" name="cep" class="form-control" placeholder="99999-999" value="<?php echo get_the_author_meta('cep', $userId) ?>">
                        </div>
                        <div class="row">
                            <div class="col-12 col-sm-9">
                                <div class="form-group">
                                    <label>Rua</label>
                                    <input type="text" id="cw-rua" name="rua" class="form-control" value="<?php echo get_the_author_meta('rua', $userId) ?>">
                                </div>
                            </div>

                            <div class="col-12 col-sm-3">
                                <div class="form-group">
                                    <label>Num.</label>
                                    <input type="text" id="cw-numero" name="numero" class="form-control" value="<?php echo get_the_author_meta('numero', $userId) ?>">
                                </div>

                            </div>
                        </div>
                        <div class="form-group">
                            <label>Bairro</label>
                            <input type="text" id="cw-bairro" name="bairro" class="form-control" value="<?php echo get_the_author_meta('bairro', $userId) ?>">
                        </div>
                        <div class="row">
                            <div class="col-8">
                                <div class="form-group">
                                    <label>Cidade</label>
                                    <input type="text" id="cw-cidade" name="cidade" class="form-control" value="<?php echo get_the_author_meta('cidade', $userId) ?>">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label>Estado</label>
                                    <?php
                                    $estado = get_the_author_meta('estado', $userId);
                                    $estadosArray = ['AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA', 'MT', 'MS', 'MG', 'PA', 'PB', 'PR', 'PE', 'PI', 'RJ', 'RN', 'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO'];
                                    ?>
                                    <select name="estado" id="cw-estado" class="form-control">
                                        <option>Selecione o estado</option>
                                        <?php
                                        foreach ($estadosArray as $uf) {
                                            $selected = ($uf == $estado) ? 'selected' : '';
                                            echo '<option ' . $selected . '>' . $uf . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="resp"></div>
                <div class="form-group text-center">
                    <button class="btn btn-lg btn-primary" type="submit">Atualizar <i class="fa fa-sync-alt"></i></button>
                </div>
            </form>
            <a href="javascript: history.go(-1);" class="btn btn-light"><i class="fa fa-arrow-left"></i> Voltar</a>
            <?php
        } else {
            $orcamentos = $wpdb->get_results("SELECT * FROM orcamentos_cw_pedidos WHERE idUser = '$userId'");
            if ($orcamentos) {
            ?>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Data</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($orcamentos as $orcamento) {
                            $id_orcamento = $orcamento->id;
                            $resposta = $wpdb->get_results("SELECT * FROM orcamentos_cw_resposta WHERE id_orcamento = '$id_orcamento'");
                            $status  = ($resposta) ? '<b class="text-success">Respondido</b>' : '<span class="text-warning">Aguardando</span>';
                        ?>
                            <tr>
                                <td><?php echo $orcamento->id ?></td>
                                <td><?php echo date('d/m/Y', strtotime($orcamento->data)); ?></td>
                                <td><?php echo $status ?></td>
                                <td class="text-center"><a href="<?php the_permalink() ?>?orcamento=<?php echo $id_orcamento ?>" class="btn btn-info">Ver</a></td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            <?php
            } else {
            ?>
                <div class="alert alert-info">Você ainda não tem nenhum orçamento cadastrado</div>
        <?php
            }
        }
        ?>
    </div>
<?php
}
?>