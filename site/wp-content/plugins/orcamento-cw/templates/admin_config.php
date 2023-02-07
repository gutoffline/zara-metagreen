<?php defined('ABSPATH') or die('No script kiddies please!'); ?>

<div class="row">

    <div class="col-12 col-sm-9">

        <div class="branco">

            <div class="gerarShortcode">

                <h3>Gerar ShortCode Carrossel</h3>

                <p>Insira um carrossel de produtos no seu site por meio do shortcode.</p>

                <form class="row shortcode">

                    <div class="col-12 col-sm-7">

                        <div class="input-group">



                            <select class="custom-select" id="inputGroupSelect03" aria-label="Example select with button addon">

                                <option value="">Todos os produtos</option>

                                <?php

                                $args = array('taxonomy' => 'produto_categoria', 'post_type' => 'produto', 'orderby' => 'title', 'order' => 'ASC');

                                $categories = get_categories($args);



                                foreach ($categories as $category) {

                                ?>

                                    <option value="<?php echo $category->slug; ?>">Categoria: <?php echo $category->name; ?></option>

                                <?php

                                }

                                ?>

                            </select>

                            <div class="input-group-append">

                                <button class="btn btn-outline-secondary" type="submit">Gerar Shortcode</button>

                            </div>

                        </div>



                    </div>



                    <div class="resp col-12"></div>

                </form>

                <hr>

            </div>

            <?php

            $config = unserialize(get_option('configOrcamento'));

            ?>



            <ul class="nav nav-tabs" id="myTab" role="tablist">

                <li class="nav-item">

                    <a class="nav-link active" id="finalizacao-tab" data-toggle="tab" href="#finalizacao" role="tab" aria-controls="finalizacao" aria-selected="true">Finalização</a>

                </li>

                <li class="nav-item">

                    <a class="nav-link" id="paginas-importantes-tab" data-toggle="tab" href="#paginas-importantes" role="tab" aria-controls="paginas-importantes" aria-selected="false">Páginas Importantes</a>

                </li>

                <li class="nav-item">

                    <a class="nav-link" id="cores-botoes-tab" data-toggle="tab" href="#cores-botoes" role="tab" aria-controls="cores-botoes" aria-selected="false">Cores dos botões</a>

                </li>

                <li class="nav-item">

                    <a class="nav-link" id="dados-cliente-tab" data-toggle="tab" href="#dados-cliente" role="tab" aria-controls="dados-cliente" aria-selected="false">Dados do cliente</a>

                </li>

            </ul>



            <form class="configOrcamento">

                <input type="hidden" name="action" value="configOrcamento">

                <div class="tab-content" id="myTabContent">





                    <div class="tab-pane fade show active" id="finalizacao" role="tabpanel" aria-labelledby="finalizacao-tab">

                        <div class="row">

                            <div class="col-xs-12 col-md-6">

                                <div class="form-group">

                                    <h4>Login</h4>

                                    <p>Aqui você define se é ou não necessario o login</p>

                                    <select name="modo_finalizacao" class="form-control form-control-lg">

                                        <?php

                                        $modo_finalizacao     = $config['modo_finalizacao'];

                                        $login_sem             = ($modo_finalizacao == 'sem') ? 'selected' : '';

                                        $login_obr             = ($modo_finalizacao == 'obr') ? 'selected' : '';

                                        $login_opc             = ($modo_finalizacao == 'opc') ? 'selected' : '';

                                        ?>

                                        <option value="sem" <?php echo $login_sem ?>>Sem login</option>

                                        <option value="obr" <?php echo $login_obr ?>>Login obrigatório</option>

                                        <option value="opc" <?php echo $login_opc ?>>Login opcional</option>

                                    </select>

                                </div>

                            </div>

                            <div class="col-xs-12 col-md-6">

                                <h4>Whastsapp</h4>

                                <p>Enviar p/ WhatsApp. Deixe em branco para não ativar essa função.</p>

                                <div class="form-group">

                                    <input type="text" name="num_whatsapp" placeholder="(DD)999999999" class="form-control" value="<?php echo $config['num_whatsapp'] ?>">

                                </div>

                            </div>

                            <div class="col-xs-12 col-sm-12">

                                <hr>

                            </div>

                            <div class="col-xs-12 col-sm-6">

                                <h4>E-mail de recebimento</h4>

                                <p>Informe o e-mail que deseja receber os dados de orçamento. </p>

                                <div class="emailsAdmin">

                                    <?php

                                    $emailAdmin = $config['emailAdmin'];

                                    if (!isset($config['emailAdmin'])) {

                                    ?>

                                        <div class="form-group input-group">

                                            <input type="email" class="form-control" name="emailAdmin[]" value="<?php echo get_option('admin_email'); ?>">

                                            <div class="input-group-append">

                                                <button class="btn btn-danger" type="button" onclick="removeEmail(this)">x</button>

                                            </div>

                                        </div>

                                        <?php

                                    } else {

                                        foreach ($emailAdmin as $email) {

                                        ?>

                                            <div class="form-group input-group">

                                                <input type="email" class="form-control" name="emailAdmin[]" value="metagreen@metagreen.com.br">

                                                <div class="input-group-append">

                                                    <button class="btn btn-danger" type="button" onclick="removeEmail(this)">x</button>

                                                </div>

                                            </div>

                                    <?php

                                        }

                                    }



                                    ?>



                                </div>

                                <button class="btn" type="button" onclick="addEmail()">Adicionar email</button>



                            </div>

                        </div>







                    </div>



                    <div class="tab-pane fade" id="paginas-importantes" role="tabpanel" aria-labelledby="paginas-importantes-tab">



                        <h3>Páginas importantes</h3>

                        <div id="selectPgImportantes"><i class="fa fa-pulse fa-spinner"></i></div>

                        <div class="criarPagina">

                            <p>Deseja criar essas páginas importantes? <a href="#ss" onclick="criaPagina();"><b>CLIQUE AQUI.</b></a></p>

                            <div class="resp"></div>

                        </div>

                        <hr>



                        <div class="alert alert-info admin">

                            <p>Na página de "Produtos" use o shortcode: <span>[produtos_orcamento]</span></p>

                            <p>Para exibir o menu de categorias use o shortcode: <span>[orcamento_menu_categorias titulo="Categorias"]</span></p>

                            <p>Na página de "Finalização do orçamento" use o shortcode: <span>[finaliza_orcamento_cw]</span></p>

                            <p>Na página de "Minha conta" use o shortcode: <span>[minha_conta_cw]</span></p>

                        </div>



                    </div>



                    <div class="tab-pane fade" id="cores-botoes" role="tabpanel" aria-labelledby="cores-botoes-tab">



                        <h3>Botões: <small>Configure as cores e texto dos botões</small></h3>



                        <div class="row minicolors-theme-bootstrap" id="config-cores">

                            <div class="tampa"></div>

                            <div class="col-12 col-sm-4">

                                <h5>Botão (produtos e relacionados)</h5>

                                <div class="form-group">

                                    <label>Cor Fundo</label><br>

                                    <input type="text" name="produtos_cor_fundo" class="color form-control" value="<?php echo $config['produtos_cor_fundo'] ?>">

                                </div>



                                <div class="form-group">

                                    <label>Cor Texto</label><br>

                                    <input type="text" name="produtos_cor_texto" class="color form-control" value="<?php echo $config['produtos_cor_texto'] ?>">

                                </div>



                                <div class="form-group">

                                    <label>Texto</label>

                                    <input type="text" name="produtos_texto" class="form-control" placeholder="Pedir Orçamento" value="<?php echo $config['produtos_texto'] ?>">

                                </div>

                                <div class="form-group">

                                    <label>Class (atributo) <i class="fa fa-info-circle text-info" data-toggle="tooltip" data-placement="top" title="Class fixa: 'btn'"></i></label>

                                    <input type="text" name="produtos_class" class="form-control" value="<?php echo $config['produtos_class'] ?>">

                                </div>

                            </div>

                            <div class="col-12 col-sm-4">

                                <h5>Botão (page do produto)</h5>

                                <div class="form-group">

                                    <label>Cor Fundo</label><br>

                                    <input type="text" name="single_produto_cor_fundo" class="color form-control" value="<?php echo $config['single_produto_cor_fundo'] ?>">

                                </div>



                                <div class="form-group">

                                    <label>Cor Texto</label><br>

                                    <input type="text" name="single_produto_cor_texto" class="color form-control" value="<?php echo $config['single_produto_cor_texto'] ?>">

                                </div>



                                <div class="form-group">

                                    <label>Texto</label>

                                    <input type="text" name="single_produto_texto" class="form-control" placeholder="Pedir Orçamento" value="<?php echo $config['single_produto_texto'] ?>">

                                </div>

                                <div class="form-group">

                                    <label>Class (atributo) <i class="fa fa-info-circle text-info" data-toggle="tooltip" data-placement="top" title="Class fixa: 'btn btn-lg btn-block'"></i></label>

                                    <input type="text" name="single_produto_class" class="form-control" value="<?php echo $config['single_produto_class'] ?>">

                                </div>

                            </div>

                            <div class="col-12 col-sm-4">

                                <h5>Botão (página de finalização)</h5>

                                <div class="form-group">

                                    <label>Cor Fundo</label><br>

                                    <input type="text" name="finalizacao_cor_fundo" class="color form-control" value="<?php echo $config['finalizacao_cor_fundo'] ?>">

                                </div>



                                <div class="form-group">

                                    <label>Cor Texto</label><br>

                                    <input type="text" name="finalizacao_cor_texto" class="color form-control" value="<?php echo $config['finalizacao_cor_texto'] ?>">

                                </div>



                                <div class="form-group">

                                    <label>Texto</label>

                                    <input type="text" name="finalizacao_texto" class="form-control" placeholder="Enviar Orçamento" value="<?php echo $config['finalizacao_texto'] ?>">

                                </div>

                                <div class="form-group">

                                    <label>Class (atributo) <i class="fa fa-info-circle text-info" data-toggle="tooltip" data-placement="top" title="Class fixa: 'btn btn-lg btn-block'"></i></label>

                                    <input type="text" name="finalizacao_class" class="form-control" value="<?php echo $config['finalizacao_class'] ?>">

                                </div>

                            </div>

                        </div>



                    </div>



                    <div class="tab-pane fade" id="dados-cliente" role="tabpanel" aria-labelledby="dados-cliente-tab">



                        <h3>Dados do cliente: <small>Selecione quais informações deseja solicitar na finalização</small></h3>

                        <hr>

                        <div class="row">

                            <div class="col-12 col-sm-6">

                                <h4>Dados pessoais</h4>

                                <div class="form-group">

                                    <div class="checkbox">

                                        <label>

                                            <input type="checkbox" class="form-control" checked disabled> Nome (padrão)

                                        </label>

                                    </div>

                                </div>

                                <div class="form-group">

                                    <div class="checkbox">

                                        <label>

                                            <input type="checkbox" class="form-control" checked disabled> Email (padrão)

                                        </label>

                                    </div>

                                </div>

                                <div class="form-group">

                                    <div class="checkbox">

                                        <label>

                                            <input type="checkbox" class="form-control" checked disabled> Mensagem (padrão)

                                        </label>

                                    </div>

                                </div>

                                <div class="form-group">

                                    <div class="checkbox">

                                        <label>

                                            <input type="checkbox" name="orcamento-telefone" <?php if ($config['orcamento-telefone']) {

                                                                                                    echo 'checked';

                                                                                                } ?> class="form-control" checked> Telefone

                                        </label>

                                    </div>

                                </div>

                                <div class="form-group">

                                    <div class="checkbox">

                                        <label>

                                            <input type="checkbox" <?php if ($config['orcamento-rg']) {

                                                                        echo 'checked';

                                                                    } ?> name="orcamento-rg" class="form-control"> RG

                                        </label>

                                    </div>

                                </div>

                                <div class="form-group">

                                    <div class="checkbox">

                                        <label>

                                            <input type="checkbox" <?php if ($config['orcamento-arquivo']) {

                                                                        echo 'checked';

                                                                    } ?> name="orcamento-arquivo" class="form-control"> Aquivo

                                        </label>

                                    </div>

                                </div>

                                <div class="form-group">

                                    <select class="form-control" name="tipo_pessoa">

                                        <option>Selecione o tipo de pessoa</option>

                                        <option value="0">Nenhum</option>

                                        <option value="f_j" <?php if ($config['tipo_pessoa'] == 'f_j') {

                                                                echo 'selected';

                                                            } ?>>Pessoa Física ou Jurídica</option>

                                        <option value="f" <?php if ($config['tipo_pessoa'] == 'f') {

                                                                echo 'selected';

                                                            } ?>>Apenas pesssoa Física</option>

                                        <option value="j" <?php if ($config['tipo_pessoa'] == 'j') {

                                                                echo 'selected';

                                                            } ?>>Apenas pesssoa Jurídica</option>

                                    </select>

                                </div>



                            </div>

                            <div class="col-12 col-sm-6">

                                <h4>Endereço</h4>

                                <div class="form-group">

                                    <div class="checkbox">

                                        <label>

                                            <input type="checkbox" <?php if ($config['orcamento-cep']) {

                                                                        echo 'checked';

                                                                    } ?> name="orcamento-cep" class="form-control"> CEP

                                        </label>

                                    </div>

                                </div>

                                <div class="form-group">

                                    <div class="checkbox">

                                        <label>

                                            <input type="checkbox" <?php if ($config['orcamento-rua']) {

                                                                        echo 'checked';

                                                                    } ?> name="orcamento-rua" class="form-control"> Rua

                                        </label>

                                    </div>

                                </div>

                                <div class="form-group">

                                    <div class="checkbox">

                                        <label>

                                            <input type="checkbox" <?php if ($config['orcamento-numero']) {

                                                                        echo 'checked';

                                                                    } ?> name="orcamento-numero" class="form-control"> Número

                                        </label>

                                    </div>

                                </div>

                                <div class="form-group">

                                    <div class="checkbox">

                                        <label>

                                            <input type="checkbox" <?php if ($config['orcamento-bairro']) {

                                                                        echo 'checked';

                                                                    } ?> name="orcamento-bairro" class="form-control"> Bairro

                                        </label>

                                    </div>

                                </div>

                                <div class="form-group">

                                    <div class="checkbox">

                                        <label>

                                            <input type="checkbox" <?php if ($config['orcamento-cidade']) {

                                                                        echo 'checked';

                                                                    } ?> name="orcamento-cidade" class="form-control"> Cidade

                                        </label>

                                    </div>

                                </div>

                                <div class="form-group">

                                    <div class="checkbox">

                                        <label>

                                            <input type="checkbox" <?php if ($config['orcamento-estado']) {

                                                                        echo 'checked';

                                                                    } ?> name="orcamento-estado" class="form-control"> Estado

                                        </label>

                                    </div>

                                </div>

                            </div>



                        </div>

                    </div>



                    <div class="resp"></div>

                    <div class="form-group text-right">

                        <button class="btn-primary btn btn-lg" type="submit" style="margin-top: 25px">Salvar</button>

                    </div>

                </div>



            </form>





        </div>



        <div class="branco">

            <h3>Meus modelos de <b>RESPOSTA</b></h3>

            <?php

            global $wpdb;

            print_footer_scripts();

            $mensagemPadrao = "<h3>Olá <b>[cw_nomeCliente]</b></h3>

			<p><b>Seu orçamento:</b></p>

			[cw_listaProdutos]

			<br>

			<h4>Resposta ao orçamento:</h4>

			(<span style='color:red'>escreva aqui a resposta</span>)

			<hr>

			<p><b>Atenciosamente</b></p>

			<p>[cw_nomeSite]</p>

			<p>[cw_siteUrl]</p>";



            $args = array(

                'tinymce'       => array(

                    'toolbar1'      => ''

                ),

                'quicktags'     => true,

                'textarea_rows' => 15,

                'media_buttons' => false

            );



            $modeloResp = $wpdb->get_results("SELECT * FROM orcamento_modelo_resp");

            if ($modeloResp) {



                foreach ($modeloResp as $resp) {

            ?>



                    <a data-toggle="collapse" href="#collapse_resp_<?php echo $resp->id ?>" role="button" aria-expanded="false" aria-controls="collapse_resp_<?php echo $resp->id ?>">

                        <h4 class="panel-title"><?php echo $resp->nome ?></h4>

                    </a>



                    <div id="collapse_resp_<?php echo $resp->id ?>" class="collapse">

                        <div class="card card-body">

                            <div class="form_atualizar_modelo" id="<?php echo $resp->id ?>">

                                <div class="form-group">

                                    <input type="text" name="nome" value="<?php echo $resp->nome ?>" class="form-control"></input>

                                </div>

                                <?php echo bc_get_wp_editor(nl2br($resp->modelo), 'modelo_id_' . $resp->id, $args); ?>

                                <hr>

                                <div class="resp"></div>

                                <button class="btn btn-danger pull-left exclui_<?php echo $resp->id ?>" onclick="excluiModeloResp('<?php echo $resp->id ?>')">Exluir modelo <i class="fa fa-trash"></i></button>

                                <button class="btn btn-info pull-right" type="button" onclick="atualizarModelo('<?php echo $resp->id ?>',this)">Atualizar modelo</button>

                            </div>

                        </div>

                    </div>



                <?php

                }

            } else {

                ?>

                <div class="alert alert-warning"><b>Nenhum modelo encontrado.</b></div>

            <?php

            }

            ?>



            <div class="form-group">

                <button class="btn btn-primary btn-lg" type="button" data-toggle="modal" data-target="#criarModeloResposta">Criar modelo de resposta</button>

            </div>

        </div>

    </div>

    <div class="col-12 col-sm-3">

        <?php sidebar_admin_orcamento_cw(); ?>

    </div>

</div>



<div class="modal fade" id="criarModeloResposta">

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <div class="modal-header">

                <h4 class="modal-title">Crie um novo "Modelo de resposta"</h4>

                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>



            </div>

            <div class="modal-body">

                <p>Edite e salve como um novo modelo de resposta. <a data-toggle="collapse" href="#collapse_shortcodes" aria-expanded="false" aria-controls="collapse_shortcodes"><b>Inserir shortcodes</b></a></p>

                <div class="collapse" id="collapse_shortcodes">

                    <div class="row">

                        <div class="col-12 col-md-8">

                            <div class="form-group">

                                <div class="input-group">

                                    <input class="form-control" id="cw_nomeCliente" value="[cw_nomeCliente]">

                                    <span class="input-group-btn">

                                        <button type="button" class="btn btn-info" onclick="copiarShortcode('#cw_nomeCliente')">Copiar</button>

                                    </span>

                                </div>

                                <p>Exibe o nome do cliente.</p>

                            </div>

                            <div class="form-group">

                                <div class="input-group">

                                    <input class="form-control" id="cw_listaProdutos" value="[cw_listaProdutos]">

                                    <span class="input-group-btn">

                                        <button type="button" class="btn btn-info" onclick="copiarShortcode('#cw_listaProdutos')">Copiar</button>

                                    </span>

                                </div>

                                <p>Exibe a lista com os produtos que o cliente pediu para orçar.</p>

                            </div>

                            <div class="form-group">

                                <div class="input-group">

                                    <input class="form-control" id="cw_nomeSite" value="[cw_nomeSite]">

                                    <span class="input-group-btn">

                                        <button type="button" class="btn btn-info" onclick="copiarShortcode('#cw_nomeSite')">Copiar</button>

                                    </span>

                                </div>

                                <p>Exibe o nome do site (<i><?php echo get_option('blogname') ?>)</i></p>

                            </div>

                            <div class="form-group">

                                <div class="input-group">

                                    <input class="form-control" id="cw_siteUrl" value="[cw_siteUrl]">

                                    <span class="input-group-btn">

                                        <button type="button" class="btn btn-info" onclick="copiarShortcode('#cw_siteUrl')">Copiar</button>

                                    </span>

                                </div>

                                <p>Exibe a url do site (<i><?php echo get_option('siteUrl') ?></i>)</p>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="form-group">

                    <label>Nome do modelo</label>

                    <input type="text" name="nome" class="form-control" required>

                </div>

                <?php echo bc_get_wp_editor(nl2br($mensagemPadrao), 'novo_modelo', $args); ?>

                <div class="resp"></div>

            </div>

            <div class="modal-footer">

                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Fechar</button>

                <button type="button" class="btn btn-primary btn-lg" onclick="criarModeloResposta(this)">Salvar como modelo</button>

            </div>

        </div>

    </div>

</div>



<style>

    .tab-pane {

        padding-top: 20px

    }

</style>

<script>

    function addEmail() {

        var template = '<div class="form-group input-group">' +

            '<input type="email" class="form-control" name="emailAdmin[]">' +

            '<div class="input-group-append">' +

            '<button class="btn btn-danger" type="button" onclick="removeEmail(this)">x</button>' +

            '</div>' +

            '</div>';



        jQuery('.emailsAdmin').append(template);

    }



    function removeEmail(el) {

        jQuery(el).closest('.input-group').remove();

    }

</script>