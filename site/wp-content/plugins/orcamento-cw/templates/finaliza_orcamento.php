<?php



$car = isset($_COOKIE["carrinho"]) ? $_COOKIE["carrinho"] : "";

$car = unserialize(stripslashes($car));

if (is_array($car) && count($car)) {

    $user = wp_get_current_user();

?>

    <div id="carrinho_orcamento">

        <div class="row-cw">

            <div class="col-cw s6 d-none d-sm-block">

                <h5>Produto</h5>

            </div>

            <div class="col-cw s6 d-none d-sm-block">

                <h5>Quantidade</h5>

            </div>

        </div> <?php

                $variacoes = $wpdb->get_results("SELECT * FROM orcamento_cw_variacoes");

                foreach ($car as $key => $val) {



                    $query = new WP_Query("post_type=produto&p=" . $car[$key]['id_produto']);

                    if ($query->have_posts()) {

                        while ($query->have_posts()) {

                            $query->the_post();

                ?> <div class="produto-car" id="<?php echo $key ?>">



                        <div class="row-cw flex-center">

                            <div class="col-cw s6 flex-center">

                                <div class="imagem-car ">

                                    <a href="<?php the_permalink() ?>">

                                        <img src="<?php orcamento_cw_the_imgDestaque('thumbnail') ?>" alt="'<?php the_title() ?>">

                                    </a>

                                </div>

                                <div class="descricao-car">

                                    <a href="<?php the_permalink() ?>">

                                        <p style="margin-bottom:1px"><b><?php the_title() ?></b></p>

                                    </a>

                                    <?php

                                    $temVariacao = false;

                                    foreach ($variacoes as $v) {

                                        if (get_field('exibe_' . caracteresEspeciais($v->nome))) {

                                            $temVariacao = true;

                                        }

                                    }

                                    if ($temVariacao) {

                                    ?>

                                        <div id="collapse<?php echo $key ?>" style="font-size: 10px">

                                            <?php

                                            foreach ($variacoes as $v) {

                                                if (get_field('exibe_' . caracteresEspeciais($v->nome))) {

                                                    $valor = $car[$key][caracteresEspeciais($v->nome)];

                                                    echo '<b>' . $v->nome . ':</b><br>';

                                                    if (is_array($valor)) {

                                                        $vir = 1;

                                                        foreach ($valor as $va) {

                                                            $virgula = ($vir < count($valor)) ? ', ' : '<br>';

                                                            echo  $va . $virgula;

                                                            $vir++;

                                                        }

                                                    } else {

                                                        echo $valor . '<br>';

                                                    }

                                                }

                                            }

                                            ?>

                                        </div>

                                    <?php

                                    }

                                    ?>

                                </div>

                            </div>

                            <div class="col-cw s4 actioAddOrcamento">

                                <?php echo buttonQuant(array(

                                    'quantidade' => $car[$key]['quantidade'],

                                    'attr' => 'onblur="orcamento(\'' . $key . '\',\'' . $car[$key]['id_produto'] . '\', \'2\');"',

                                    'classInput' => 'quant' . $key

                                )); ?>

                            </div>

                            <div class="col-cw s2 excluir">

                                <button id="exclui<?php echo $key ?>" onclick="orcamento('<?php echo $key ?>','', '', 'exclui')">

                                    <i class="fa fa-trash"></i>

                                </button>

                            </div>

                        </div>

                    </div>

                <?php

                        }

                    } else {

                ?>

                <script>

                    orcamento('<?php echo $key ?>', '', '', 'exclui');

                </script>

        <?php

                    }

                    //wp_reset_postdata();

                }

        ?>

        <div class="row-cw">

            <div class="col-cw s12">

                <h3>Seus dados de contato</h3>

                <form class="row-cw form-orcamento" enctype="multipart/form-data" method="POST">

                    <input type="hidden" name="action" value="finaliza_orcamento">

                    <div class="col-cw m6 s12">

                        <div class="form-group-cw">

                            <label for="cw-nome">Nome <span>*</span></label>

                            <input type="text" id="cw-nome" name="nome" class="form-control" placeholder="Seu nome" value="<?php echo $user->display_name ?>" required>

                        </div>

                        <div class="form-group-cw">

                            <label for="cw-email">Email <span>*</span></label>

                            <input type="email" id="cw-email" name="email" class="form-control" placeholder="Seu email" value="<?php echo $user->user_email ?>" required>

                        </div>

                        <?php

                        if (orcamentoConfig('orcamento-telefone')) {

                            $tel = ($user->ID) ? get_the_author_meta('telefone', $user->ID) : '';

                        ?>

                            <div class="form-group-cw">

                                <label for="cw-tel">Tel <span>*</span></label>

                                <input type="text" id="cw-tel" name="telefone" class="form-control" placeholder="EX (99) 99999-9999" required value="<?php echo $tel ?>">

                            </div>

                            <?php

                        }



                        $tipo_pessoa = orcamentoConfig('tipo_pessoa');

                        $cpf = ($user->ID) ? get_the_author_meta('cpf', $user->ID) : '';

                        $cnpj = ($user->ID) ? get_the_author_meta('cnpj', $user->ID) : '';

                        $empresa = ($user->ID) ? get_the_author_meta('empresa', $user->ID) : '';

                        switch ($tipo_pessoa) {

                            case 'f':

                            ?>

                                <div class="form-group-cw">

                                    <label for="cw-cpf">CPF <span>*</span></label>

                                    <input type="text" id="cw-cpf" name="cpf" class="form-control" placeholder="999.999.999-99" required value="<?php echo $cpf ?>">

                                </div>

                            <?php

                                break;



                            case 'j':

                            ?>

                                <div class="form-group-cw">

                                    <label for="cw-empresa">Empresa <span>*</span></label>

                                    <input type="text" id="cw-empresa" name="empresa" class="form-control" placeholder="Nome da empresa" required value="<?php echo $empresa ?>">

                                </div>

                                <div class="form-group-cw">

                                    <label for="cw-cnpj">CNPJ <span>*</span></label>

                                    <input type="text" id="cw-cnpj" name="cnpj" class="form-control" placeholder="99.999.999/9999-99" required value="<?php echo $cnpj ?>">

                                </div>

                            <?php

                                break;



                            case 'f_j':

                            ?>

                                <div class="row-cw">

                                    <div class="col-cw m6 s12">

                                        <div class="form-check">

                                            <input class="form-check-input" type="radio" name="tipo_pessoa" id="tipo_pessoa_f" value="f" checked>

                                            <label class="form-check-label" for="tipo_pessoa_f">

                                                Pessoa Física

                                            </label>

                                        </div>

                                    </div>

                                    <div class="col-cw m6 s12">

                                        <div class="form-check">

                                            <input class="form-check-input" type="radio" name="tipo_pessoa" id="tipo_pessoa_j" value="j">

                                            <label class="form-check-label" for="tipo_pessoa_j">

                                                Pessoa Jurídica

                                            </label>

                                        </div>

                                    </div>

                                </div>

                                <div id="div_tipo_pessoa_f">

                                    <div class="form-group-cw">

                                        <label for="cw-cpf">CPF <span>*</span></label>

                                        <input type="text" id="cw-cpf" name="cpf" class="form-control" placeholder="999.999.999-99" required value="<?php echo $cpf ?>">

                                    </div>

                                </div>

                                <div id="div_tipo_pessoa_j" style="display:none">

                                    <div class="form-group-cw">

                                        <label for="cw-empresa">Empresa <span>*</span></label>

                                        <input type="text" id="cw-empresa" name="empresa" class="form-control" placeholder="Nome da empresa" value="<?php echo $empresa ?>">

                                    </div>

                                    <div class="form-group-cw">

                                        <label for="cw-cnpj">CNPJ <span>*</span></label>

                                        <input type="text" id="cw-cnpj" name="cnpj" class="form-control" placeholder="99.999.999/9999-99" value="<?php echo $cnpj ?>">

                                    </div>

                                </div>



                            <?php

                                break;



                            default:

                                break;

                        }



                        if (orcamentoConfig('orcamento-rg')) {

                            $rg = ($user->ID) ? get_the_author_meta('rg', $user->ID) : '';

                            ?>

                            <div class="form-group-cw">

                                <label for="">RG <span>*</span></label>

                                <input type="text" id="cw-rg" name="rg" class="form-control" placeholder="99.999.999-9" required value="<?php echo $rg ?>">

                            </div>

                        <?php

                        }



                        if (orcamentoConfig('orcamento-arquivo')) {

                        ?>

                            <div class="form-group-cw">

                                <label>Arquivo <span></span></label>

                                <input type="file" name="arquivo" class="form-control">

                            </div>

                        <?php

                        }

                        ?>

                    </div>



                    <div class="col-cw m6 s12">

                        <?php

                        if (orcamentoConfig('orcamento-cep')) {

                            $cep = ($user->ID) ? get_the_author_meta('cep', $user->ID) : '';

                        ?>

                            <div class="form-group-cw">

                                <label for="cw-cep">CEP <span>*</span><span id="cw-load_cep"></span></label>

                                <input type="text" id="cw-cep" name="cep" class="form-control" placeholder="99999-999" required value="<?php echo $cep ?>">

                            </div>

                        <?php

                        }

                        ?>

                        <div class="row-cw">

                            <div class="col-cw s12 m8">

                                <?php

                                if (orcamentoConfig('orcamento-rua')) {

                                    $rua = ($user->ID) ? get_the_author_meta('rua', $user->ID) : '';

                                ?>

                                    <div class="form-group-cw">

                                        <label for="cw-rua">Rua <span>*</span></label>

                                        <input type="text" id="cw-rua" name="rua" class="form-control" required value="<?php echo $rua ?>">

                                    </div>

                                <?php

                                }

                                ?>

                            </div>



                            <div class="col-cw s12 m4">

                                <?php



                                if (orcamentoConfig('orcamento-numero')) {

                                    $numero = ($user->ID) ? get_the_author_meta('numero', $user->ID) : '';

                                ?>

                                    <div class="form-group-cw">

                                        <label for="cw-numero">Num. <span>*</span></label>

                                        <input type="text" id="cw-numero" name="numero" class="form-control" required value="<?php echo $numero ?>">

                                    </div>

                                <?php

                                }

                                ?>

                            </div>



                            <?php

                            if (orcamentoConfig('orcamento-bairro')) {

                                $bairro = ($user->ID) ? get_the_author_meta('bairro', $user->ID) : '';

                            ?>

                                <div class="col-cw s12">

                                    <div class="form-group-cw">

                                        <label for="cw-bairro">Bairro <span>*</span></label>

                                        <input type="text" id="cw-bairro" name="bairro" class="form-control" required value="<?php echo $bairro ?>">

                                    </div>

                                </div>

                            <?php

                            }

                            ?>



                            <div class="col-cw s8">

                                <?php

                                if (orcamentoConfig('orcamento-cidade')) {

                                    $cidade = ($user->ID) ? get_the_author_meta('cidade', $user->ID) : '';

                                ?>

                                    <div class="form-group-cw">

                                        <label for="ce-cidade">Cidade <span>*</span></label>

                                        <input type="text" id="cw-cidade" name="cidade" class="form-control" required value="<?php echo $cidade ?>">

                                    </div>

                                <?php

                                }

                                ?>

                            </div>

                            <div class="col-cw s4">

                                <?php

                                if (orcamentoConfig('orcamento-estado')) {

                                ?>

                                    <div class="form-group-cw">

                                        <label>Estado <span>*</span></label>

                                        <?php

                                        $estado = ($user->ID) ? get_the_author_meta('estado', $user->ID) : '';

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

                                <?php

                                }

                                ?>

                            </div>

                        </div>

                        <div class="form-group-cw">

                            <label>Mensagem</label>

                            <textarea name="mensagem" class="form-control materialize-textarea" row-cws="4" placeholder="Deixe aqui sua mensagem"></textarea>

                        </div>

                    </div>

                    <div class="col-cw s12">

                        <div class="resp-orcamento"></div>

                        <div class="form-group-cw text-center enviar">

                            <div class="col-cw s12" id="resp-orcamento"></div>

                            <?php

                            $config = get_option('configOrcamento');

                            $orcamento_text = (orcamentoConfig('finalizacao_texto')) ? orcamentoConfig('finalizacao_texto') : 'Enviar Orçamento';

                            $orcamento_text = "Enviar";

                            $orcamento_back_color = orcamentoConfig('finalizacao_cor_fundo');

                            $orcamento_text_color = orcamentoConfig('finalizacao_cor_texto');

                            ?>

                            <div class="col-cw s12 center-align call-action">

                                <button type="submit" style="background-color: <?php echo $orcamento_back_color ?>; color: <?php echo $orcamento_text_color ?>" class="btn <?php echo orcamentoConfig('finalizacao_class') ?>"><?php echo $orcamento_text ?></button>

                                <br>

                                <a href="<?php the_permalink(get_option('pg_produtos')) ?>">Adicionar mais produtos +</a>

                            </div>

                        </div>

                    </div>

                </form>

            </div>



        </div>

    </div>

<?php

} else {

    $config = get_option('configOrcamento');

    $orcamento_text = (orcamentoConfig('finalizacao_texto')) ? orcamentoConfig('finalizacao_texto') : 'Enviar Orçamento';

    $orcamento_back_color = orcamentoConfig('finalizacao_cor_fundo');

    $orcamento_text_color = orcamentoConfig('finalizacao_cor_texto');

?>

    <div class="text-center vazio form-group-cw" style="padding: 40px 0">

        <h2>Sua lista de orçamento está vazia</h2><br>

        <a href="<?php the_permalink(get_option('pg_produtos')) ?>" style="background-color: <?php echo $orcamento_back_color ?>; color: <?php echo $orcamento_text_color ?>" class="btn btn-lg"><i class="fa fa-reply" aria-hidden="true"></i> <b>Produtos</b>

        </a>

    </div>

<?php

}

?>