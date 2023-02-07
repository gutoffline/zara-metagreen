<!-- Adiciona o cabeçalho (header.php) -->
<?php get_header();
if (have_posts()) : while (have_posts()) : the_post(); ?>
        <section class="orcamento-cw module">
            <div class="container wrap">
                <div class="row-cw single-produto">
                    <div class="col-cw s12 m3">
                        <div class="widget widget_nav_menu mk-in-viewport">
                            <?php wp_nav_menu('categorias'); ?>
                        </div>
                    </div>
                    <div class="col-cw s12 m4 galeria ">
                        <?php
                        $galeria = acf_photo_gallery('galeria', $post->ID);
                        if ($galeria) {                            
                            foreach ($galeria as $imagem) {
                                $imageGaleria = $imagem['full_image_url'];
                                $imageThumbnail = $imagem['full_image_url'];
                                $title = $imagem['title'];
                                $imgGrande .= '

                            <div class="swiper-slide center-align">
                            <a rel="prettyPhoto" href="'.$imageGaleria.'" target="_self">
                            <img src="' . $imageGaleria . '" alt="' . $attr . '">
                            </a>
							</div>';
                                $miniatura .= '
							<div class="swiper-slide">
							<img src="' . $imageThumbnail . '" alt="' . $attr . '" style="width:100%">
							</div>';

                            }

                        }
                        ?>

                        <div class="swiper-container gallery-top">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide center-align">
                                <a rel="prettyPhoto" href="<?php orcamento_cw_the_imgDestaque('full_image_url') ?>" target="_self">
                                    <img src="<?php orcamento_cw_the_imgDestaque('full_image_url') ?>" alt="<?php the_title() ?>">
                                    </a>
                                </div>
                                <?php echo $imgGrande ?>
                            </div>
                            <!-- Add Arrow-cws -->
                            <div class="swiper-button-next"></div>
                            <div class="swiper-button-prev"></div>
                        </div>

                        <div class="swiper-container gallery-thumbs">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide">
                                    <img src="<?php orcamento_cw_the_imgDestaque('thumbnail'); ?>" alt="<?php the_title() ?>" style="width:100%">
                                </div>
                                <?php
                                if ($miniatura) {
                                    echo $miniatura;
                                }
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-cw s12 m5">
                        <div class="row-cw">
                            <div class="col-cw s12">
                                <h1 class="title"><?php the_title() ?></h1>
                                <?php
                                if (get_field('resumo')) {
                                    the_field('resumo');
                                }



                                $config = get_option('configOrcamento');

                                $orcamento_text = (orcamentoConfig('single_produto_texto')) ? orcamentoConfig('single_produto_texto') : 'Enviar Orçamento';

                                $orcamento_back_color = (orcamentoConfig('single_produto_cor_fundo')) ? 'background-color:' . orcamentoConfig('single_produto_cor_fundo') . '; ' : '';

                                $orcamento_text_color = (orcamentoConfig('single_produto_cor_texto')) ? 'color:' . orcamentoConfig('single_produto_cor_texto') . '; ' : '';



                                $car = (isset($_COOKIE["carrinho"])) ? $_COOKIE["carrinho"] : "";

                                if (isset($_COOKIE["carrinho"]) && $car != '') {
                                    $array = unserialize(stripslashes($car));
                                }

                                $quantCar = ($array[$post->ID] != '') ? $array[$post->ID] : 1;

                                ?>

                                <form class="pedirOrcamento">

                                    <input type="hidden" name="action" value="pedirOrcamento">

                                    <input type="hidden" name="id_produto" value="<?php echo $post->ID ?>">

                                    <?php

                                    $variacoes = $wpdb->get_results("SELECT * FROM orcamento_cw_variacoes ORDER BY id DESC");
                                    if ($variacoes) {
                                        foreach ($variacoes as $v) {
                                            if (get_field('exibe_' . caracteresEspeciais($v->nome))) {
                                                $itensSele = get_field(caracteresEspeciais($v->nome));
                                                $required = unserialize($v->opcoes)['required'];

                                    ?>

                                                <div class="form-group">
                                                    <label for=""><?php echo $v->nome ?></label>
                                                    <?php
                                                    if ($v->tipo == 'radio') {
                                                        if ($v->opcoes) {
                                                            $opcoes = unserialize($v->opcoes)['itens'];
                                                            foreach ($opcoes as $key => $value) {
                                                                if (in_array($key, $itensSele)) {
                                                    ?>
                                                                    <div class="radio">
                                                                        <label>
                                                                            <input type="radio" name="<?php echo caracteresEspeciais($v->nome) ?>" id="optionsRadios<?php echo $key ?>" value="<?php echo $value ?>" <?php echo $required ?>> <?php echo $value ?>

                                                                        </label>
                                                                    </div>
                                                                <?php
                                                                }
                                                            }
                                                        }
                                                    } elseif ($v->tipo == 'checkbox') {
                                                        if ($v->opcoes) {
                                                            $opcoes = unserialize($v->opcoes)['itens'];
                                                            foreach ($opcoes as $key => $value) {
                                                                if (in_array($key, $itensSele)) {
                                                                ?>
                                                                    <div class="checkbox">
                                                                        <label>
                                                                            <input type="checkbox" name="<?php echo caracteresEspeciais($v->nome) ?>[]" id="optionsCheckbox<?php echo $key ?>" value="<?php echo $value ?>" <?php echo $required ?>> <?php echo $value ?>
                                                                        </label>
                                                                    </div>
                                                        <?php
                                                                }
                                                            }
                                                        }
                                                    } elseif ($v->tipo == 'select') {
                                                        ?>
                                                        <select class="form-control" name="<?php echo caracteresEspeciais($v->nome) ?>" <?php echo $required ?>>
                                                            <?php
                                                            if ($v->opcoes) {
                                                                $opcoes = unserialize($v->opcoes)['itens'];
                                                                foreach ($opcoes as $key => $value) {
                                                                    if (in_array($key, $itensSele)) {
                                                                        echo '<option value="' . $value . '">' . $value . '</option>';
                                                                    }
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    <?php
                                                    } elseif ($v->tipo == 'text') {
                                                    ?>
                                                        <input class="form-control" type="text" name="<?php echo caracteresEspeciais($v->nome) ?>" placeholder="<?php echo unserialize($v->opcoes)['placeholder'] ?>" <?php echo $required ?>>
                                                    <?php
                                                    } elseif ($v->tipo == 'textarea') {
                                                    ?>
                                                        <textarea name="<?php echo caracteresEspeciais($v->nome) ?>" class="form-control" placeholder="<?php echo unserialize($v->opcoes)['placeholder'] ?>" <?php echo $required ?>></textarea>
                                                    <?php
                                                    }
                                                    ?>
                                                </div>
                                    <?php
                                            }
                                        }
                                    }
                                    ?>
                                    <div class="actioAddOrcamento flex-center">
                                        <?php echo buttonQuant(array(
                                            'quantidade' => $quantCar
                                        )) ?>
                                        <button style="<?php echo $orcamento_back_color . $orcamento_text_color ?>" class='btn-large btn-produto <?php echo orcamentoConfig('single_produto_class') ?> produto-<?php echo get_the_ID(); ?>' type="submit"><?php echo $orcamento_text ?></button>
                                    </div>
                                    <div class="resp"></div>
                                </form>

                                <div class="compartilhe">

                                    <p>Compartilhe:</p>

                                    <a id="comp-twitter" href="http://twitter.com/home?status=<?php the_title(); ?>+<?php the_permalink(); ?>" title="Share on Twitter" onclick="window.open(this.href,'galeria','width=680,height=470'); return false;" title="Twitter"><i class="fab fa-twitter"></i></a>

                                    <a id="comp-facebook" href="http://www.facebook.com/share.php?u=<?php the_permalink(); ?>&title=<?php the_title(); ?>" title="Compartilhe no Facebook" onclick="window.open(this.href,'galeria','width=680,height=470'); return false;"><i class="fab fa-facebook-f"></i></a>

                                    <?php
                                    if (strripos($_SERVER['HTTP_USER_AGENT'], 'Windows') === false) {
                                        $link = urlencode(get_the_title() . ' ' . get_permalink());
                                    } else {
                                        $link = get_the_title() . ' ' . get_permalink();
                                    }
                                    ?>

                                    <a id="comp-whatsapp" target="_blanc" href="https://api.whatsapp.com/send?text=<?php echo $link; ?>" title="Compartilhe no WhatsApp"><i class="fab fa-whatsapp"></i></a>
                                </div>
                                <div class="compartilhe">
                                    <p>
                                        <a href="#" onclick="window.history.back();" style="font-weight: bold; text-decoration: underline; margin-left: 9px; font-size: 16px; margin-right: 0; margin-left: 0; margin-top: 10px; background: #000099; color: white; padding: 3px; border: 0;">Voltar</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container wrap">
                <div class="row-cw single-produto">
                    <div class="col-cw s12 m3">
                      &nbsp;
                    </div>
                    <div class="col-cw s12 m9 galeria ">
                        <?php the_content() ?>
                    </div>
                </div>
            </div>
        </section>
<?php
    endwhile;
endif;
?>
<!-- Adiciona o rodapé (footer.php) -->
<?php get_footer(); ?>