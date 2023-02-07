<?php
/*
Plugin Name: Orçamentos CW
Plugin URI: http://www.cicloneweb.com.br/
Description: Transforme seu site em um catalogo online. Sistema para captação de orçamentos.
Version: 2.0.1
Author: Ciclone Web
License: GPL version 2 or later - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*/

defined('ABSPATH') or die('No script kiddies please!');

function versao_cw()
{
    return '2.0.1';
}

/* Cria tabelas */
require plugin_dir_path(__FILE__) . 'cria_tabelas.php';
register_activation_hook(__FILE__, 'orcamento_cw_install');
add_action('plugins_loaded', 'verifica_versao_orcamento');

//Remove barra topo para useuarios
function orcamento_cw_admin_bar($content)
{
    return (current_user_can("administrator")) ? $content : false;
}
add_filter('show_admin_bar', 'orcamento_cw_admin_bar');
add_role('cliente', 'Cliente', 'subscriber');

function sidebar_admin_orcamento_cw()
{
?>
    <div class="branco">
        <h3>Orçamento CW</h3>
        <p>Receba orçamentos pelo seu site WordPress</p>
        <div class="row">
            <div class="col" id="respVersao"></div>
        </div>
    </div>
    <?php
}

function api_cw($array)
{
    $ch = curl_init('https://cicloneweb.com.br/Api2/');
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $array);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    return json_decode(curl_exec($ch));
}
if (!function_exists('printR')) {
    function printR($array)
    {
        if ($_COOKIE['dev'] == 'dev') {
    ?>
            <pre><?php print_r($array) ?></pre>
    <?php
        }
    }
}
function caracteresEspeciais($str)
{
    $str = preg_replace('/[áàãâä]/ui', 'a', $str);
    $str = preg_replace('/[éèêë]/ui', 'e', $str);
    $str = preg_replace('/[íìîï]/ui', 'i', $str);
    $str = preg_replace('/[óòõôö]/ui', 'o', $str);
    $str = preg_replace('/[úùûü]/ui', 'u', $str);
    $str = preg_replace('/[ç]/ui', 'c', $str);
    // $str = preg_replace('/[,(),;:|!"#$%&/=?~^><ªº-]/', '_', $str);
    $str = preg_replace('/[^a-z0-9]/i', '_', $str);
    $str = preg_replace('/_+/', '_', $str); // ideia do Bacco :)
    return $str;
}
/* Verifica atualizacao */
function notice_atualizacao()
{
    ?>
    <div class="notice notice-warning is-dismissible">
        <p><b>Atenção!</b> Foi lançada uma nova versão do plugin de Orçamento. <b><a href="<?php echo get_option('siteUrl') ?>/wp-admin/edit.php?post_type=produto&page=atualizacao">Clique aqui e atualize</a></b></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dispensar este aviso.</span></button>
    </div>
    <?php
}
if ($_GET['page'] != 'atualizacao') {
    $versao = array('action' => 'pluginOrcamento/verificaVersao', 'site' => get_option('siteUrl'));
    $api = api_cw($versao);
    if ($api->status == 'success') {
        if ($api->ultimaVersao != versao_cw()) {
            add_action('admin_notices', 'notice_atualizacao');
            function scriptAtualiazacao()
            {
                global $api;
    ?>
                <script>
                    jQuery(document).ready(function() {
                        jQuery('#menu-posts-produto [href="edit.php?post_type=produto&page=atualizacao"]').append(' <span class="update-plugins count-1"><span class="plugin-count">!</span></span>');

                        jQuery('<tr class="plugin-update-tr active" id="orcamento-cw-update" data-slug="orcamento-cw"><td colspan="3" class="plugin-update colspanchange"><div class="update-message notice inline notice-warning notice-alt"><p>Há uma nova versão do Orçamentos CW - Versão <b><?php echo $api->ultimaVersao ?></b>. <a href="<?php echo get_option('siteUrl') ?>/wp-admin/edit.php?post_type=produto&page=atualizacao" class="update-link" aria-label="Atualizar Orçamentos CW agora.">Atualize agora</a>.</p></div></td></tr>').insertAfter('[data-plugin="orcamento-cw/orcamento-cw.php"]');
                    });
                </script>
            <?php
            }
            add_action('admin_footer', 'scriptAtualiazacao');
        }
    }
}

/* Fim - Verivica atualizacao */

/** Habilita shortcode na sidebar **/
add_filter('widget_text', 'do_shortcode');

/*** ESTILOS E TEXTOS ***/
function orcamentoConfig($string)
{
    if (get_option('configOrcamento')) {
        $config = unserialize(get_option('configOrcamento'));
        return $config[$string];
    }
}


//Verifica se existe pagina "ORCAMENTOS" - se náo, notifica pra acessar
if ($_GET['page'] != 'configuracoes-orcamentos-cw') {
    $pageExiste = true;
    query_posts('post_type=page&name=orcamentos');
    if (!have_posts()) {
        $pageExiste = false;
    }
    query_posts('post_type=page&name=orcamentos');
    if (!have_posts()) {
        $pageExiste = false;
    }
    if ($pageExiste == false) {
        function sample_admin_notice_produtos()
        {
            ?>
            <div class="notice notice-error">
                <p><b>Atenção!</b> <a href="<?php echo get_option('siteUrl') ?>/wp-admin/edit.php?post_type=produto&page=configuracoes-orcamentos-cw">Clique aqui</a> para configurar seus orçamentos.</p>
            </div>
    <?php
        }
        add_action('admin_notices', 'sample_admin_notice_produtos');
    }
}
//FIM Verifica se existe pagina "ORCAMENTOS" - se náo, notifica pra acessar

function submenuProdutoAdmin()
{
    add_submenu_page(
        'edit.php?post_type=produto',
        __('Variações', 'textdomain'),
        __('Variações', 'textdomain'),
        'manage_options',
        'variacoes-orcamentos-cw',
        'requireVariacoes'
    );
    add_submenu_page(
        'edit.php?post_type=produto',
        __('Configurações - Orçamentos', 'textdomain'),
        __('Configurações', 'textdomain'),
        'manage_options',
        'configuracoes-orcamentos-cw',
        'requirePageConfig'
    );
    add_submenu_page(
        'edit.php?post_type=produto',
        __('Meus Orçamentos', 'textdomain'),
        __('Meus Orçamentos', 'textdomain'),
        'manage_options',
        'historico-orcamentos-cw',
        'requirePageOrcamentos'
    );
    add_submenu_page(
        'edit.php?post_type=produto',
        __('Atualização', 'textdomain'),
        __('Atualização', 'textdomain'),
        'manage_options',
        'atualizacao',
        'page_atualizacao'
    );
}
add_action('admin_menu', 'submenuProdutoAdmin');
function page_atualizacao()
{
    ?>
    <div class="wrap">
        <h1>Atualização</h1>
        <?php require_once(plugin_dir_path(__FILE__) . 'templates/atualizacao.php'); ?>
    </div>
<?php
}
function requirePageConfig()
{
?>
    <div class="wrap">
        <h1>Configurações - Orçamentos</h1>
        <?php require_once(plugin_dir_path(__FILE__) . 'templates/admin_config.php'); ?>
    </div>
<?php
}
function requirePageOrcamentos()
{
?>
    <div class="wrap">
        <h1>Orçamentos</h1>
        <?php require_once(plugin_dir_path(__FILE__) . 'templates/orcamentos_admin.php'); ?>
    </div>
<?php
}
function requireVariacoes()
{
?>
    <div class="wrap">
        <h1>Variações</h1>
        <?php require_once(plugin_dir_path(__FILE__) . 'templates/variacoes.php'); ?>
    </div>
    <?php
}
function orcamento_pagination()
{
    global $wp_query;
    $big = 999999999;
    return paginate_links(array(
        'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
        'format' => '?paged=%#%',
        'current' => max(1, get_query_var('paged')),
        'total' => $wp_query->max_num_pages
    ));
}

function orcamento_cw_get_imgDestaque($tamanho = '')
{
    $tamanho = ($tamanho) ? $tamanho : 'maximo';
    $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), $tamanho);
    $imgDest = ($image[0]) ? $image[0] : plugins_url('/imagens/sem-imagem.gif', __FILE__);
    return $imgDest;
}
function orcamento_cw_the_imgDestaque($tamanho = '')
{
    $tamanho = ($tamanho) ? $tamanho : 'maximo';
    $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), $tamanho);
    $imgDest = ($image[0]) ? $image[0] : plugins_url('/imagens/sem-imagem.gif', __FILE__);
    echo $imgDest;
}

//Shor
function shortcode_produtos($array = '')
{
    $conteudo .= '<div class="row-cw produtos">';

    if (get_query_var('page')) {
        $page = get_query_var('page');
    } elseif (get_query_var('paged')) {
        $page = get_query_var('paged');
    } else {
        $page = 1;
    }

    $categoria = (isset($array['categoria'])) ? '"&produto_categoria=' . $array['categoria'] : '';
    $posts_per_page = (isset($array['posts_per_page'])) ? '&posts_per_page=' . $array['posts_per_page'] : '';
    query_posts("post_type=produto&paged=" . $page . $categoria . $posts_per_page . "&orderby=title&order=ASC");

    if (have_posts()) {
        while (have_posts()) {
            the_post();

            include(plugin_dir_path(__FILE__) . 'templates/content-produto.php');
        }
    }

    $conteudo .= '</div>
	<div class="row">
	<div class="col-12 orcamentos_paginacao">
	' . orcamento_pagination() . '
	</div>
	</div>';
    wp_reset_query();
    return $conteudo;
}
add_shortcode('produtos_orcamento', 'shortcode_produtos');

//Shor
function shortcode_finaliza_orcamento_cw($array)
{
    ob_start();
    global $wpdb;

    $userId = get_current_user_id();

    $config             = unserialize(get_option('configOrcamento'));
    $modo_finalizacao     = $config['modo_finalizacao'];

    if ($modo_finalizacao == 'obr' && !$userId) {
    ?>
        <div class="alert alert-info">Faça o login pra finalizar seu pedido</div>
        <?php
        include(plugin_dir_path(__FILE__) . 'templates/form-login.php');
    } else {
        if ($modo_finalizacao == 'opc' && !$userId) {
            include(plugin_dir_path(__FILE__) . 'templates/form-login.php');
            echo '<hr>';
        }
        if ($userId) {
            $user = wp_get_current_user();
        ?>
            <div class="boasVindas">
                <p>
                    Olá <b><?php echo $user->display_name ?></b>, seja bem vindo(a)! <a class="float-right btn btn-secondary" href="<?php echo wp_logout_url(get_permalink()); ?>">Sair <i class="fa fa-sign-out-alt"></i></a>
                </p>
            </div>
    <?php
        }
        include(plugin_dir_path(__FILE__) . 'templates/finaliza_orcamento.php');
    }
    return ob_get_clean();
}
add_shortcode('finaliza_orcamento_cw', 'shortcode_finaliza_orcamento_cw');

//Shor
function shortcode_minha_conta_cw($array)
{
    ob_start();
    global $wpdb;

    include(plugin_dir_path(__FILE__) . 'templates/minha_conta.php');

    return ob_get_clean();
}
add_shortcode('minha_conta_cw', 'shortcode_minha_conta_cw');


function shortcode_carrossel($array)
{
    $caracteres = 'abcdefghijlmnopq';
    $id = substr(str_shuffle($caracteres), 0, 10);
    $conteudo = '
	<div class="carrossel produtos" id="' . $id . '" style="width:100%; position:relative; overflow: hidden;">
	<div class="swiper-wrapper">';

    $categoria = (isset($array['categoria'])) ? '&produto_categoria=' . $array['categoria'] : '';
    query_posts("post_type=produto&showposts=500" . $categoria);
    if (have_posts()) {
        while (have_posts()) {
            the_post();
            $conteudo .= '<div class="swiper-slide">';
            $style = 'style="min-height: auto;"';
            include(plugin_dir_path(__FILE__) . 'templates/content-produto.php');

            $conteudo .= '</div>';
        }
    }
    wp_reset_query();

    $conteudo .= '
	</div>
	<div class="swiper-button-prev"><i class="fa fa-chevron-left"></i></div>
	<div class="swiper-button-next"><i class="fa fa-chevron-right"></i></div>
	</div>';

    $conteudo .= '
	<script>
	jQuery(document).ready(function(){ 
		var largura = jQuery(\'#' . $id . '\').width();
		var quantSlide;
		if(largura <= 400){
			quantSlide = 1;
		}
		if(largura > 401 && largura < 600){
			quantSlide = 2;
		}
		if(largura > 601 && largura < 900){
			quantSlide = 3;
		}
		if(largura > 901 && largura < 1100){
			quantSlide = 4;
		}
		if(largura > 1100){
			quantSlide = 4;
		}

		var swiper = new Swiper(\'#' . $id . '\', {
			slidesPerView: \'auto\',
			navigation: {
				nextEl: \'#' . $id . ' .swiper-button-next\',
				prevEl: \'#' . $id . ' .swiper-button-prev\',
			},
			slidesPerView: quantSlide,
			paginationClickable: true,
			spaceBetween: 10,
			autoplay : {
				delay: 4000
			},

		});
	});

	</script>';

    return $conteudo;
}
add_shortcode('orcamento_carrossel', 'shortcode_carrossel');

function shortcode_lsta_categorias($array)
{
    $categoria = get_categories(array('taxonomy' => 'produto_categoria'));
    $menuCategorias = '';

    $categorias = get_categories(array('taxonomy' => 'produto_categoria', 'type' => 'produto', 'child_of' => 0, 'parent' => 0));
    $menuCategorias .= '<ul class="nav flex-column cw_menu-cat">';
    $cc = 0;
    if ($categorias) {
        foreach ($categorias as $category) {
            $cat_id = $category->cat_ID;
            $subCategorias = get_categories(array('taxonomy' => 'produto_categoria', 'type' => 'produto', 'child_of' => $category->term_id));
            $ativaCollapse = ($subCategorias) ? 'data-toggle="collapse" href="#collapse' . $cc . '" aria-expanded="false" aria-controls="collapse' . $cc . '"' : '';
            $menuCategorias .= '<li class="nav-item">';
            $mostraQuantidade = ($array['mostraquantidade']) ? '<span class="float-right">(' . $category->count . ')</span>' : '';
            $menuCategorias .= '<a class="nav-link" ' . $ativaCollapse . ' href="' . get_category_link($category) . '">' . $category->cat_name . ' ' . $mostraQuantidade . '</a>';
            if ($subCategorias) {
                $menuCategorias .= '<ul class="collapse nav" id="collapse' . $cc . '" style="margin-left:0">';
                foreach ($subCategorias as $subCategorias) {
                    $menuCategorias .= '<li><a role="link" href="' . get_category_link($subCategorias) . '">' . $subCategorias->name . '</a></li>';
                }
                $menuCategorias .= '</ul>';
            }
            $menuCategorias .= '</li>';
            $cc++;
        }
    } else {
        $menuCategorias .= '<li class="nav-item"><a class="nav-link" href="#">Sem categorias</a><li>';
    }
    $menuCategorias .= '</ul>';

    return $menuCategorias;
}
add_shortcode('orcamento_menu_categorias', 'shortcode_lsta_categorias');


function orcamento_cw_script_admin($hook)
{
    if ($_GET['page'] != 'configuracoes-orcamentos-cw' && $_GET['page'] != 'historico-orcamentos-cw' && $_GET['page'] != 'atualizacao' && $_GET['page'] != 'variacoes-orcamentos-cw') {
        return;
    }
    wp_enqueue_style('font-awesome.min', 'https://use.fontawesome.com/releases/v5.2.0/css/all.css');
    wp_enqueue_style('bootstrap', 'https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css');
    wp_enqueue_style('minicolors', plugins_url('/css/minicolors.css?ver=' . versao_cw(), __FILE__));
    wp_enqueue_style('style_admin', plugins_url('/css/style_admin.css?versao=' . versao_cw(), __FILE__));
    wp_enqueue_style('animate', plugins_url('/css/animate.css?versao=' . versao_cw(), __FILE__));
    wp_enqueue_style('prettyPhoto', 'http://http://metagreen.com.br/wp-content/plugins/js_composer_theme/assets/lib/prettyphoto/css/prettyPhoto.min.css?ver=6.0.5');
    wp_enqueue_script('popper', 'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js');
    wp_enqueue_script('bootstrap', 'https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js');
    wp_enqueue_script('jquery.minicolors.min', plugins_url('/js/jquery.minicolors.min.js?ver=' . versao_cw(), __FILE__));
}
add_action('admin_enqueue_scripts', 'orcamento_cw_script_admin');


/*==== Adiciona javaScript na footer da administração - INICIO =======*/
function orcamento_cw_ajax()
{
    ?>
    <script>
        jQuery.ajax({
            url: 'https://cicloneweb.com.br/wp-content/themes/cicloneweb/versao-orcamento-cw.php?versao=<?php echo versao_cw() ?>',
            complete: function(res) {
                jQuery('body').prepend(res.responseJSON);
            }
        });
    </script>
    <?php
    if ($_GET['page'] != 'configuracoes-orcamentos-cw' && $_GET['page'] != 'historico-orcamentos-cw' && $_GET['page'] != 'atualizacao') {
        return;
    }
    ?>
    <script type="text/javascript">
        jQuery.fn.modalCw = function(cls) {
            console.log(this);
            if (cls == 'close') {
                this.hide(500);
            } else {
                this.show(500);
            }
        };

        function atualiza_plugin_cw() {
            jQuery('.atualiza-plugin').html('Aguarde... <i class="fa fa-spinner fa-pulse"></i>');
            jQuery('.atualiza-plugin').attr({
                'disabled': 'disabled'
            });
            jQuery('.resp-atualizacao').html('');
            var data = {
                'action': 'atualiza_plugin_cw'
            }
            jQuery.post(ajaxurl, data, function(response) {
                jQuery('.resp-atualizacao').html(response);
                jQuery('.atualiza-plugin').html('<i class="fa fa-trash"></i> Excluir');
            });
        }

        function excluiPedido(id) {
            jQuery('#orcamento-' + id + ' button.exclui').html('Aguarde... <i class="fa fa-spinner fa-pulse"></i>');
            jQuery('#orcamento-' + id + ' .resp').html('');
            var data = {
                'action': 'excluiPedido',
                'id': id
            }
            jQuery.post(ajaxurl, data, function(response) {
                jQuery('#orcamento-' + id + ' .resp').html(response);
                jQuery('#orcamento-' + id + ' button.exclui').html('<i class="fa fa-trash"></i> Excluir');
            });
        }

        function modalResponde(id) {
            jQuery('#responde-orcamento').modal('show');
            jQuery('#responde-orcamento .modal-body').html('<h3>Aguarde... <i class="fa fa-spinner fa-pulse"></i></h3>');
            var modelo = jQuery('.modelo_' + id + ' select').val();
            var data = {
                'action': 'modalResponde',
                'modelo': modelo,
                'id': id
            }
            jQuery.post(ajaxurl, data, function(response) {
                jQuery('#responde-orcamento .modal-body').html(response);
            });
        }

        function imprimirOrcamento(id) {
            open(ajaxurl + '?action=imprimirOrcamento&id=' + id, 'nova', 'width=900,height=700');
        }

        function selectPgImportantes() {
            var data = {
                'action': 'selectPgImportantes'
            };
            jQuery.post(ajaxurl, data, function(response) {
                jQuery('#selectPgImportantes').html(response);
            });
        }
        selectPgImportantes();

        function criaPagina() {
            var textLink = jQuery('.criarPagina a').html();
            jQuery('.criarPagina a').html('Aguarde...');
            var data = {
                'action': 'criarPagina'
            };
            jQuery.post(ajaxurl, data, function(response) {
                jQuery('.criarPagina a').html(textLink);
                jQuery('.criarPagina .resp').html(response);
            });
            selectPgImportantes();
        }

        function excluiModeloResp(id) {
            var button = jQuery('.exclui_' + id).html();
            jQuery('.exclui_' + id).attr({
                disabled: 'disabled'
            });
            jQuery('.exclui_' + id).html("Aguarde... <i class='fa fa-spinner fa-pulse'></i>");
            var data = {
                'action': 'excluiModeloResp',
                id: id
            };
            jQuery.post(ajaxurl, data, function(response) {
                jQuery('#collapse_resp_' + id + ' .resp').html(response);
                jQuery('.exclui_' + id).removeAttr('disabled');
                jQuery('.exclui_' + id).html(button);
            });
        }

        function atualizarModelo(id, botao) {
            jQuery('#' + id + ' .resp').html('');
            jQuery(botao).html("Aguarde... <i class='fa fa-spinner fa-pulse'></i>");
            jQuery(botao).attr({
                disabled: 'disabled'
            });
            var conteudo = jQuery("#modelo_id_" + id + "_ifr").contents().find(".modelo_id_" + id).html();
            var data = {
                action: 'atualizarModelo',
                nome: jQuery('#' + id + ' input[name="nome"]').val(),
                id: id,
                modelo: conteudo
            }
            jQuery.post(ajaxurl, data, function(response) {
                jQuery('#' + id + ' .resp').html(response);
                jQuery(botao).removeAttr('disabled');
                jQuery(botao).html("Atualizar modelo");
            });
        }

        function criarModeloResposta(button) {
            jQuery(button).html("Aguarde... <i class='fa fa-spinner fa-pulse'></i>");
            jQuery(button).attr({
                disabled: 'disabled'
            });
            var nome = jQuery('#criarModeloResposta input[name="nome"]').val();
            var conteudo = jQuery("#novo_modelo_ifr").contents().find(".novo_modelo").html();
            var data = {
                action: 'criarModeloResposta',
                nome: nome,
                novo_modelo: conteudo
            }
            jQuery.post(ajaxurl, data, function(response) {
                jQuery('#criarModeloResposta .resp').html(response);
                jQuery(button).removeAttr('disabled');
                jQuery(button).html("Salvar");
            });
        }

        jQuery('[data-toggle="tooltip"]').tooltip();

        jQuery(document).ready(function($) {


            jQuery('.color').minicolors();
            jQuery('#cor-tema input').click(function() {
                if (jQuery(this).is(':checked') == true) {
                    jQuery('#config-cores').addClass('disabled');
                    jQuery('#config-cores input').attr('disabled');
                } else {
                    jQuery('#config-cores').removeClass('disabled');
                    jQuery('#config-cores input').removeAttr('disabled');
                }
            });
            if (jQuery('#cor-tema input').is(':checked') == true) {
                jQuery('#config-cores').addClass('disabled');
                jQuery('#config-cores input').attr('disabled');
            } else {
                jQuery('#config-cores').removeClass('disabled');
                jQuery('#config-cores input').removeAttr('disabled');
            }

            jQuery('form.shortcode').submit(function() {
                jQuery('form.shortcode .resp').html('');
                var categoria = jQuery('form.shortcode select').val();
                var selectCat = (categoria != '') ? ' categoria="' + categoria + '"' : '';
                jQuery('form.shortcode .resp').html('<div><span>[orcamento_carrossel' + selectCat + ']</span><br>Copie e cole onde quer que o carrossel apareça.</div>');
                return false;
            });

            jQuery('.configOrcamento').submit(function() {
                jQuery('.configOrcamento .resp').html('');
                jQuery('.configOrcamento button[type="submit"]').html("Aguarde... <i class='fa fa-spinner fa-pulse'></i>");

                var data = jQuery(this).serialize();

                jQuery.post(ajaxurl, data, function(response) {
                    jQuery('.configOrcamento .resp').html(response);
                    jQuery('.configOrcamento button[type="submit"]').html("Salvar");
                });
                return false;
            });

        });
    </script>
    <?php
}
add_action('admin_footer', 'orcamento_cw_ajax');
/*==== Adiciona javaScript na footer da administração - FIM   ========*/

function bc_get_wp_editor($content = '', $editor_id, $options = array())
{
    ob_start();

    wp_editor($content, $editor_id, $options);

    $temp = ob_get_clean();
    $temp .= \_WP_Editors::enqueue_scripts();
    $temp .= \_WP_Editors::editor_js();
    //$temp .= print_footer_scripts();

    return $temp;
}

/*===== Inicio - Atualiza plugin =========== */
function atualiza_plugin_cw()
{
    global $wpdb;
    $versao = array('action' => 'pluginOrcamento/verificaVersao');
    $api = api_cw($versao);
    if ($api->status == 'success') {
        if ($api->ultimaVersao == versao_cw()) {
    ?>
            <div class="alert alert-info">
                <b>Estranho!</b> Já está com a última (<?php echo $api->ultimaVersao ?>) versão.
            </div>
            <?php
        } else {
            $arquivo = WP_PLUGIN_DIR . '/orcamento_cw_' . $api->ultimaVersao . '.zip';
            if (copy($api->link, $arquivo)) {
                echo "Arquivo baixado com sucesso!<br>";
                $zip = new ZipArchive;
                if ($zip->open($arquivo) == TRUE) {
                    $zip->extractTo(WP_PLUGIN_DIR);

                    echo 'Arquivo descompactado com sucesso.<br>';

                    if (unlink($arquivo)) {
                        echo 'Arquivo .zip deletado.';
                    }

            ?>
                    <h2>Atualizando página <i class="fa fa-spinner fa-pulse"></i></h2>
                    <script>
                        location.reload();
                    </script>
    <?php
                } else {
                    echo 'O Arquivo (' . $arquivo . ') não pode ser descompactado.';
                }
                $zip->close();
            } else {
                echo "<p class='text-danger'><b>OPS!</b> Erro ao copiar a nova versao. Tente mais tarde.</p>";
            }
        }
    } else {
        echo "<p class='text-danger'><b>OPS!</b> Tente mais tarde.</p>";
    }

    wp_die();
}
add_action('wp_ajax_atualiza_plugin_cw', 'atualiza_plugin_cw');
/*===== Fim - Atualiza plugin =========== */

add_action('wp_ajax_selectPgImportantes', 'selectPgImportantes');
function selectPgImportantes()
{
    global $wpdb;
    query_posts('post_type=page&showposts=1000');
    if (have_posts()) : while (have_posts()) : the_post();

            global $post;

            $selected_pg_produtos         = (get_option('pg_produtos') == $post->ID) ? 'selected' : '';
            $options_pg_produtos         .= '<option value="' . $post->ID . '" ' . $selected_pg_produtos . '>' . get_the_title() . '</option>';

            $selected_pg_finalizacao = (get_option('pg_finalizacao') == $post->ID) ? 'selected' : '';
            $options_pg_finalizacao .= '<option value="' . $post->ID . '" ' . $selected_pg_finalizacao . '>' . get_the_title() . '</option>';

        endwhile;
    endif;
    ?>
    <div class="row">
        <div class="col">
            <div class="row">
                <div class="col-12 col-sm-6 form-group">
                    <label>Página de produtos</label>
                    <select name="pg_produtos" class="form-control" required>
                        <option value="">Selecione uma página</option>
                        <?php echo $options_pg_produtos ?>
                    </select>
                </div>
                <div class="col-12 col-sm-6 form-group">
                    <label>Página de finalização</label>
                    <select name="pg_finalizacao" class="form-control" required>
                        <option value="">Selecione uma página</option>
                        <?php echo $options_pg_finalizacao ?>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <?php
    wp_die();
}



/*===== Funcão que recebe o action "criarPagina" - INICIO ========*/
add_action('wp_ajax_criarPagina', 'criarPagina');
function criarPagina()
{
    global $wpdb; // this is how you get access to the database

    $pagina_finaliza = (get_option('pg_finalizacao') !== false) ? get_option('pg_finalizacao') : false;
    if (get_post($pagina_finaliza)->post_status != 'publish') {
        $my_post = array(
            'post_title'    => wp_strip_all_tags('Orçamentos'),
            'post_content'  => '[finaliza_orcamento_cw]',
            'post_status'   => 'publish',
            'post_type'       => 'page'
        );
        $pageId = wp_insert_post($my_post);
        if ($pageId) {

            if (get_option('pg_finalizacao') !== false) {
                update_option('pg_finalizacao', $pageId);
            } else {
                add_option('pg_finalizacao', $pageId, null, 'no');
            }

            echo '<p class="text-success">A página de <b>finalização</b> foi criada com sucesso.</p>';
        }
    } else {
        echo '<p class="text-danger">A página de <b>finalização</b> já existe, verifique se contem o shortcode <b>[finaliza_orcamento_cw]</b></p>';
    }


    $pagina_produtos = (get_option('pg_produtos') !== false) ? get_option('pg_produtos') : false;
    if (get_post($pagina_produtos)->post_status != 'publish') {
        $my_post = array(
            'post_title'    => wp_strip_all_tags('Produtos'),
            'post_content'  => '[produtos_orcamento]',
            'post_status'   => 'publish',
            'post_type'       => 'page'
        );
        $pageId = wp_insert_post($my_post);
        if ($pageId) {

            if (get_option('pg_produtos') !== false) {
                update_option('pg_produtos', $pageId);
            } else {
                add_option('pg_produtos', $pageId, null, 'no');
            }
            echo '<p class="text-success">A página de <b>produtos</b> foi criada com sucesso.</p>';
        }
    } else {
        echo '<p class="text-danger"> A página de <b>produtos</b> já existe, verifique se contem o shortcode <b>[produtos_orcamento]</b></p>';
    }


    wp_die(); // this is required to terminate immediately and return a proper response
}
/*===== Funcão que recebe o action "criarPagina" - FIM     =======*/

/*===== Exclui pedido de orcamento ======*/
add_action('wp_ajax_excluiPedido', 'excluiPedido');
function excluiPedido()
{
    global $wpdb;
    $id = $_POST['id'];
    $wpdb->get_results("DELETE FROM orcamentos_cw_pedidos WHERE id = '$id'");
    $confirma = $wpdb->get_results("SELECT * FROM orcamentos_cw_pedidos WHERE id = '$id'");
    if (!$confirma) {
    ?>
        <script>
            jQuery('#orcamento-<?php echo $id; ?>').hide('slow');
        </script>
    <?php
    } else {
    ?>
        <div class="alert alert-danger"><b>Erro!</b> Tente mais tarde.</div>
    <?php
    }
    wp_die();
}
/*===== FIM - Exclui pedido de orcamento ======*/

/*===== Abre modal pra responder ao orcamento ======*/
add_action('wp_ajax_modalResponde', 'modalResponde');
function modalResponde()
{
    global $wpdb;
    $id = $_POST['id'];
    $modeloId = $_POST['modelo'];
    $modeloResp = $wpdb->get_results("SELECT * FROM orcamento_modelo_resp WHERE id='$modeloId'");
    if ($modeloResp[0]) {
        $mensagem = $modeloResp[0]->modelo;
    } else {
        $mensagem = "<h3>Olá <b>[cw_nomeCliente]</b></h3>
		<p><b>Seu orçamento:</b></p>
		[cw_listaProdutos]
		<br>
		<h4>Resposta ao orçamento:</h4>
		(<span style='color:red'>escreva aqui a resposta</span>)
		<hr>
		<p><b>Atenciosamente</b></p>
		<p>[cw_nomeSite]</p>
		<p>[cw_siteUrl]</p>";
    }
    $orcamento = $wpdb->get_results("SELECT * FROM orcamentos_cw_pedidos WHERE id = '$id'");
    if ($orcamento[0]) {
        $orcamento = $orcamento[0];

        $mensagem = str_replace("[cw_nomeCliente]", $orcamento->nomeCliente, $mensagem);
        $mensagem = str_replace("[cw_listaProdutos]", $orcamento->produtos, $mensagem);
        $mensagem = str_replace("[cw_nomeSite]", get_option('blogname'), $mensagem);
        $mensagem = str_replace("[cw_siteUrl]", '<a href="' . get_option('siteUrl') . '">' . get_option('siteUrl') . '</a>', $mensagem);
        $mensagem = "<div style='background-color: #ccc; padding-top: 15px; padding-bottom:15px; width:100%'>
		<div style='width: 500px; background-color: #fff; margin: auto; padding: 15px'>
		$mensagem
		<span class='LINK'></span>
		</div>
		</div>";
        $args = array('tinymce'  => array('toolbar1'      => ''), 'quicktags' => false,    'textarea_rows' => 15,    'media_buttons' => false);
    ?>

        <form class="resposta_orcamento">
            <input type="hidden" name="id" value="<?php echo $orcamento->id ?>">
            <input type="hidden" name="action" value="resposta_orcamento">
            <div class="form-group">
                <input class="form-control" type="email" name="emailCliente" value="<?php echo $orcamento->emailCliente ?>">
            </div>
            <div class="form-group">
                <?php echo bc_get_wp_editor(nl2br($mensagem), 'resposta_orcamento', $args); ?>
                <br>
                <input type="file" name="arquivo" class="form-control">
                <div class="resp"></div>
                <div class="form-group text-right">
                    <button class="btn btn-lg btn-primary" type="submit">Responder</button>
                </div>
            </div>

        </form>
        <script>
            jQuery('.resposta_orcamento').submit(function() {
                jQuery('.resposta_orcamento button[type="submit"]').html("Aguarde... <i class='fa fa-pulse'></i>");
                jQuery('.resposta_orcamento button[type="submit"]').attr('disabled', 'disabled');
                jQuery('.resposta_orcamento .resp').html('');
                if (jQuery('.resposta_orcamento input[name="arquivo"').length) {

                    var form_data = new FormData(this);

                    var arquivo = jQuery('.resposta_orcamento input[name="arquivo"').val();
                    if (arquivo != '') {
                        var arquivo = jQuery('.resposta_orcamento input[name="arquivo"').prop('files')[0];
                    }
                    form_data.append('file', arquivo);
                    jQuery.ajax({
                        url: ajaxurl,
                        type: 'post',
                        contentType: false,
                        processData: false,
                        data: form_data,
                        success: function(data) {
                            jQuery('.resposta_orcamento .resp').html(data);
                            jQuery('.resposta_orcamento button[type="submit"]').removeAttr('disabled');
                            jQuery('.resposta_orcamento button[type="submit"]').html("Responder");
                        }
                    });
                } else {
                    var form_data = jQuery(this).serialize();
                    jQuery.ajax({
                        url: ajaxurl,
                        type: 'post',
                        data: form_data,
                        success: function(data) {
                            jQuery('.resposta_orcamento .resp').html(data);
                            jQuery('.resposta_orcamento button[type="submit"]').removeAttr('disabled');
                            jQuery('.resposta_orcamento button[type="submit"]').html("Responder");
                        }
                    });
                }

                return false;
            });
        </script>
    <?php
    } else {
        echo '<div class="alert alert-danger"><b>Erro!</b> Tente mais tarde</div>';
    }
    wp_die();
}
/*===== FIM - Abre modal pra responder ao orcamento ===== */

add_action('wp_ajax_imprimirOrcamento', 'imprimirOrcamento');
function imprimirOrcamento()
{
    global $wpdb;
    $id = $_GET['id'];
    $orcamento = $wpdb->get_results("SELECT * FROM orcamentos_cw_pedidos WHERE id='$id'");
    if ($orcamento[0]) { ?>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js">
        <div class="container">
            <?php
            $orca = $orcamento[0];
            echo "<h4>Pedido feito em: " . date('d/m/Y H:i', strtotime($orca->data)) . "</h4>";
            echo $orca->orcamento;

            ?>
        </div>
        <script>
            window.print();
            //window.close();
        </script>
    <?php
    }
    wp_die();
}
add_action('wp_ajax_excluiModeloResp', 'excluiModeloResp');
function excluiModeloResp()
{

    global $wpdb;
    $id = $_POST['id'];
    $wpdb->get_results("DELETE FROM orcamento_modelo_resp WHERE id ='$id'");
    if ($wpdb->last_error) {
    ?>
        <div class="alert alert-erro"><b>Erro!</b> <?php echo $wpdb->last_error ?></div>
    <?php
    } else {
    ?>
        <div class="alert alert-success"><b>OK!</b> Modelo deletado com sucesso. Atualizando a página <i class="fa fa-spinner fa-pulse"></i></div>
        <script>
            setTimeout(function() {
                location.reload();
            }, 2500);
        </script>
    <?php
    }
    wp_die();
}

add_action('wp_ajax_criarModeloResposta', 'criarModeloResposta');
function criarModeloResposta()
{
    global $wpdb;
    $nome = $_POST['nome'];
    $modelo = $_POST['novo_modelo'];
    $wpdb->get_results("INSERT INTO orcamento_modelo_resp (nome, modelo) VALUES ('$nome','$modelo')");
    if ($wpdb->insert_id) {
    ?>
        <div class="alert alert-success"><b>OK!</b> Novo modelo criado. Atualizando a página <i class="fa fa-spinner fa-pulse"></i></div>
        <script>
            setTimeout(function() {
                location.reload();
            }, 1500);
        </script>
    <?php
    } else {
        echo $wpdb->last_error;
    }
    wp_die();
}

add_action('wp_ajax_atualizarModelo', 'atualizarModelo');
function atualizarModelo()
{
    global $wpdb;
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $modelo = $_POST['modelo'];

    $wpdb->get_results("UPDATE orcamento_modelo_resp SET nome='$nome', modelo='$modelo' WHERE id = '$id'");
    if ($wpdb->last_error) {
    ?>
        <div class="alert alert-danger"><b>Erro!</b> <?php echo $wpdb->last_error ?></div>
    <?php
    } else {
    ?>
        <div class="alert alert-success"><b>OK!</b> Modelo atualizado</div>
    <?php
    }
    wp_die();
}

add_action('wp_ajax_resposta_orcamento', 'resposta_orcamento');
function resposta_orcamento()
{
    global $wpdb;
    if (!function_exists('wp_handle_upload')) {
        require_once(ABSPATH . 'wp-admin/includes/file.php');
    }

    $attachments = '';
    if (isset($_FILES['file'])) {
        $uploadedfile = $_FILES['file'];
        $upload_overrides = array('test_form' => false);
        $movefile = wp_handle_upload($uploadedfile, $upload_overrides);

        $arquivo = ($movefile['url']) ? '<b>Anexo:</b> <a target="_blanc" href="' . $movefile['url'] . '">' . $movefile['url'] . '</a>' : '';
        if ($movefile && !isset($movefile['error'])) {
            $attachments = array($movefile['file']);
        }
    }

    $idorcamento  = $_POST['id'];
    $body         = $_POST['resposta_orcamento'];
    $to           = $_POST['emailCliente'];
    $subject      = 'Resposta ao seu orçamento';
    $headers = array('Content-Type: text/html; charset=UTF-8; From: ' . get_option('blogname') . ' <' . get_option('admin_email') . '>');

    if (wp_mail($to, $subject, $body, $headers, $attachments)) {
        $wpdb->get_results("INSERT INTO orcamentos_cw_resposta (id_orcamento, resposta, data) VALUES ('$idorcamento', '$body', now())");
    ?>
        <div class="alert alert-success"><b>Sucesso!</b> Sua mensagem foi enviada.</div>
    <?php
    } else {
    ?>
        <div class="alert alert-danger"><b>Erro!</b> Sua mensagem não foi enviada..<?php echo $enviaEmail ?></div>
        <?php
    }
    wp_die();
}


/*===== Funcão que recebe o action "configOrcamento" - INICIO ========*/
/*Preeche tabela com as informações de estilo e campos na finalização*/
function configOrcamento()
{
    global $wpdb;

    if (get_option('configOrcamento') !== false) {
        update_option('configOrcamento', serialize($_POST));
    } else {
        add_option('configOrcamento', serialize($_POST), null, 'no');
    }

    if (get_option('pg_produtos') !== false) {
        update_option('pg_produtos', $_POST['pg_produtos']);
    } else {
        add_option('pg_produtos', $_POST['pg_produtos'], null, 'no');
    }

    if (get_option('pg_finalizacao') !== false) {
        update_option('pg_finalizacao', $_POST['pg_finalizacao']);
    } else {
        add_option('pg_finalizacao', $_POST['pg_finalizacao'], null, 'no');
    }

    echo '<div class="alert alert-success">OK</div>';
    wp_die();
}
add_action('wp_ajax_configOrcamento', 'configOrcamento');
/*===== Funcão que recebe o action "configOrcamento" - FIM    ========*/


/*===== Funcão que recebe o action "finaliza_orcamento" - INICIO    ========*/
add_action('wp_ajax_finaliza_orcamento', 'finaliza_orcamento');
add_action('wp_ajax_nopriv_finaliza_orcamento', 'finaliza_orcamento');
function finaliza_orcamento()
{

    global $wpdb;

    if (!function_exists('wp_handle_upload')) {
        require_once(ABSPATH . 'wp-admin/includes/file.php');
    }

    $car = isset($_COOKIE["carrinho"]) ? $_COOKIE["carrinho"] : "";
    $array = unserialize(stripslashes($car));

    if (is_array($array)) {
        $orcamento = '<table class="table" style="width: 100%; max-width: 800px; text-align: center; border: 1px solid;">
		<thead>
		<tr>
		<th>ID</th>
		<th style="text-align: left">Produto</th>
		<th>Quantidade</th>
		</tr>
		</thead>
        <tbody>';
        $produtoWhatsapp = '';
        $variacoes = $wpdb->get_results("SELECT * FROM orcamento_cw_variacoes");
        foreach ($array as $key => $value) {
            query_posts("post_type=produto&p=" . $array[$key]['id_produto']);
            if (have_posts()) :
                while (have_posts()) : the_post();
                    global $post;
                    $produto = get_the_title();
                    $quantidade = $array[$key]['quantidade'];
                    $produtoWhatsapp .= '👉 *' . strtoupper($produto) . '*%0a';
                    $produtoWhatsapp .= '  *Qnt:* ' . $quantidade . '%0a';
                    $orcamento .= '<tr>
					<td>' . $post->ID . '</td>
					<td style="text-align: left">
					<a href="' . get_the_permalink() . '" target="_blank">' . $produto . '</a><br>';
                    foreach ($variacoes as $v) {
                        if (get_field('exibe_' . caracteresEspeciais($v->nome))) {
                            $valor = $array[$key][caracteresEspeciais($v->nome)];
                            $orcamento .= '<b>' . $v->nome . ': </b>';
                            $produtoWhatsapp .= '  *' . $v->nome . '*: ';
                            if (is_array($valor)) {
                                $orcamento .= '<ul>';
                                foreach ($valor as $va) {
                                    $orcamento .= '<li>' . $va . '</li>';
                                    $produtoWhatsapp .=  $va . ', ';
                                }
                                $orcamento .= '</ul>';
                            } else {
                                $orcamento .= $valor . '<br>';
                                $produtoWhatsapp .=  $valor;
                            }
                            $produtoWhatsapp .= '%0a';
                        }
                    }
                    $orcamento .= '</td>
					<td>' . $quantidade . '</td>
                    </tr>';
                    $produtoWhatsapp .=  '%0a';
                endwhile;

            endif;
            wp_reset_postdata();
        }
        $orcamento .= '</tbody></table>';
    } else {
        setcookie('carrinho', '', (time() + (30 * 24 * 3600)), '/');
        echo "<div class='alert alert-danger' role='alert'>Erro ao enviar. Tente mais tarde.</div>";
        exit;
    }

    $dadosZap = '';
    foreach ($_POST as $key => $value) {
        $dadosCliente .= ($key != 'action' && $key != 'file') ? '<b>' . $key . ':</b> ' . $value . '<br>' : '';
        $dadosZap .= ($key != 'action' && $key != 'file') ? '*' . ucfirst($key) . ':* ' . $value . '%0a' : '';
    }

    $email = $_POST['email'];
    $nome = $_POST['nome'];

    $textWhatsapp = '--------------------------------------------%0a';
    $textWhatsapp .= '*PEDIDO DE ORÇAMENTO* %0a';
    $textWhatsapp .= '--------------------------------------------%0a';
    $textWhatsapp .= $dadosZap . '%0a';
    $textWhatsapp .= '--------------------------------------------%0a';
    $textWhatsapp .= '*PRODUTOS* %0a';
    $textWhatsapp .= '--------------------------------------------%0a';
    $textWhatsapp .= $produtoWhatsapp;
    $textWhatsapp .= '--------------------------------------------%0a';
    $textWhatsapp .= 'Aguardo retorno.';


    if ($email != '') {
        $attachments = '';
        if (isset($_FILES['file'])) {
            $uploadedfile = $_FILES['file'];
            $upload_overrides = array('test_form' => false);
            $movefile = wp_handle_upload($uploadedfile, $upload_overrides);

            $arquivo = ($movefile['url']) ? '<b>Anexo:</b> <a target="_blanc" href="' . $movefile['url'] . '">' . $movefile['url'] . '</a>' : '';
            if ($movefile && !isset($movefile['error'])) {
                $attachments = array($movefile['file']);
            }
        }

        $config = unserialize(get_option('configOrcamento'));
        $emailsEnvio = (isset($config['emailAdmin'])) ? $config['emailAdmin'] :  get_option('admin_email');

        $to = 'metagreen@metagreen.com.br';
        $subject = 'Novo orçamento - ' . get_option('blogname');
        $body = '<h3>Dados do solicitante</h3>
		' . $dadosCliente . '
		<h3>Dados do orçamento</h3>
		' . $orcamento . '
		' . $arquivo;

        $bodyEmail = '<h2>Parabéns, você acaba de receber mais outro Orçamento</h2>' . $body;

        $headers = array('Content-Type: text/html; charset=UTF-8; From: ' . get_option('blogname') . ' <' . get_option('admin_email') . '>');

        $idUser = get_current_user_id();

        $novo = $wpdb->get_results("INSERT INTO orcamentos_cw_pedidos (nomeCliente, emailCliente, idUser, orcamento, data, produtos) VALUES ('$nome', '$email', '$idUser', '$body', now(), '$orcamento')");

        $id_gerado = $wpdb->insert_id;
        if ($id_gerado != 0) {
            setcookie('carrinho', '', (time() + (30 * 24 * 3600)), '/');

            if (wp_mail($to, $subject, $bodyEmail, $headers, $attachments)) {
        ?>

                <script>
                    jQuery('#carrinho_orcamento').html('<div class=\'col-cw s12 center-align text-center\'><h1>Obrigado, <?php echo $nome ?>!</h1> <h2>Seu orçamento foi recebido. Em breve lhe responderemos.</h2></div>');
                    jQuery('html, body').animate({
                        scrollTop: jQuery('#carrinho_orcamento').offset().top
                    }, 800);
                    jQuery('#itens').html('0');
                    <?php
                    if (orcamentoConfig('num_whatsapp') != '') {
                    ?>
                        window.location.href = "<?php echo "https://api.whatsapp.com/send?phone=55" . orcamentoConfig('num_whatsapp') . "&text=" . preg_replace("/\r|\n/", "", $textWhatsapp); ?>";
                    <?php
                    }
                    ?>
                </script>
    <?php



            } else {
                echo "<script>jQuery('#carrinho_orcamento').html('<div class=\'col-md-12 col-sm-12 col-lg-12 text-center\'><h1>Obrigado, $nome!</h1> <h2>Seu orçamento foi recebido. Em breve lhe responderemos.</h2><br><i class=\'text-danger\'>O email não foi enviado, mas o orçamento foi cadastrado com sucesso.</i></div>');
				jQuery('html, body').animate({
					scrollTop: jQuery('#carrinho_orcamento').offset().top
				}, 800);
				jQuery('#itens').html('0');</script>";
            }
        } else {
            echo "<div class='alert alert-danger' role='alert'>Erro ao enviar. Tente mais tarde. " . $wpdb->last_error . "</div>";
        }
    } else {
        echo "<div class='alert alert-danger' role='alert'>Sem email.</div>";
    }

    $user = wp_get_current_user();
    if ($user) {
        foreach ($_POST as $key => $value) {
            if ($key != 'action' && $key != 'email' && $key != 'nome') {
                update_user_meta($user->ID, $key, $value);
            }
        }
    }

    wp_die();
}

/*===== Funcão que recebe o action "finaliza_orcamento" - FIM    ========*/



function registra_produto()
{
    $labels = array(
        "name" => __("Produtos"),
        "singular_name" => __("Produto"),
        "menu_name" => __("Meus produtos"),
        "all_items" => __("Todos produtos"),
        "add_new" => __("Adicionar produto"),
        "add_new_item" => __("Novo produto"),
        "edit_item" => __("Editar produto"),
        "new_item" => __("Novo produto"),
        "view_item" => __("Ver produto"),
        "view_items" => __("Ver produtos"),
        "search_items" => __("Procurar produto"),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'produto'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        "menu_icon"          => "dashicons-megaphone",
        'supports'           => array('title', 'editor', 'thumbnail'),
    );

    // Registra o custom post
    register_post_type('produto', $args);

    // Registra a categoria personalizada
    register_taxonomy(
        'produto_categoria',
        array(
            'produto'
        ),
        array(
            'hierarchical' => true,
            'label' => __('Categoria'),
            'show_ui' => true,
            'show_in_tag_cloud' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'produto_categoria'),
        )
    );
}
add_action('init', 'registra_produto');

function template_single_produto($template)
{
    global $post;
    if ($post->post_type == "produto") {
        $plugin_path = plugin_dir_path(__FILE__);
        $template_name = 'templates/single-produto.php';
        if ($template === get_stylesheet_directory() . '/' . $template_name    || !file_exists($plugin_path . $template_name)) {
            return $template;
        }
        return $plugin_path . $template_name;
    }
    return $template;
}
add_filter('single_template', 'template_single_produto');

function override_tax_template($template)
{
    if (is_tax($taxonomy_single)) {
        $template = plugin_dir_path(__FILE__) . 'templates/taxonomy-produto_categoria.php';
    }
    return $template;
}
add_filter('template_include', 'override_tax_template');

function modalfooter()
{
    ?>
    <div id="retorno"></div>
    <div class="modal-cw" id="resposta_orcamento">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Meus orçamentos</h5>
                <button type="button" class="close"><i class="fa fa-times"></i></button>
            </div>
            <div class="modal-body" id="conteudo-orcamento">

            </div>
            <div class="modal-footer">
                <?php
                if (get_the_title() != 'Produtos') {
                ?>
                    <a class="btn btn-default float-left" href="<?php echo get_option('siteUrl') ?>/produtos">Produtos</a>
                <?php
                } else {
                ?>
                    <button type="button" class="btn close" data-dismiss="modal">Fechar</button>
                <?php
                }
                ?>

                <?php
                $config = get_option('configOrcamento');
                $orcamento_text = (orcamentoConfig('finalizacao_texto')) ? orcamentoConfig('finalizacao_texto') : 'Enviar Orçamento';
                $orcamento_back_color = orcamentoConfig('finalizacao_cor_fundo');
                $orcamento_text_color = orcamentoConfig('finalizacao_cor_texto');
                ?>
                <a href="<?php the_permalink(get_option('pg_finalizacao')) ?>" class="acessaOrcamento btn <?php echo orcamentoConfig('finalizacao_class') ?>" style="background-color: <?php echo $orcamento_back_color ?>; color: <?php echo $orcamento_text_color ?>"><b>IR PARA FINALIZAÇÃO</b></a>
            </div>
        </div>
    </div>
    <div class="sombraModal"></div>
<?php
}
add_action('wp_footer', 'modalfooter');

require_once(ABSPATH . '/wp-admin/includes/plugin.php');

/* Este código chama os arquivos que exigem a instalação dos plugins necessários para o tema.*/
require_once(plugin_dir_path(__FILE__) . 'tgm/class-tgm-plugin-activation.php');
require_once(plugin_dir_path(__FILE__) . 'tgm/plugins-list.php');

// Verifica se o plugin está ativado...
if (!is_plugin_active('advanced-custom-fields/acf.php')) {
} else {
    if (function_exists('acf_add_local_field_group')) :

        acf_add_local_field_group(array(
            'key' => 'group_5ba65907eaac7',
            'title' => 'Galeria',
            'fields' => array(
                array(
                    'key' => 'field_59dfa21edd743',
                    'label' => 'Galeria',
                    'name' => 'galeria',
                    'type' => 'photo_gallery',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'fields[' => array(
                        'edit_modal' => 'Default',
                    ),
                    'edit_modal' => 'Default',
                ),
                array(
                    'key' => 'field_5a58aa863300e',
                    'label' => 'Resumo',
                    'name' => 'resumo',
                    'type' => 'textarea',
                    'instructions' => 'Esse breve resumo aparecerá na página do produto, ao lado da imagem.',
                    'default_value' => '',
                    'placeholder' => '',
                    'maxlength' => '',
                    'rows' => '',
                    'formatting' => 'br',
                ),

                array(
                    'key' => 'field_5a58015eed769',
                    'label' => 'Relacionados',
                    'name' => 'relacionados',
                    'type' => 'relationship',
                    'return_format' => 'object',
                    'post_type' => array(
                        0 => 'produto',
                    ),
                    'taxonomy' => array(
                        0 => 'all',
                    ),
                    'result_elements' => array(
                        0 => 'featured_image',
                        1 => 'post_title',
                    ),
                    'max' => '',
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'produto',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => '',
            'active' => 1,
            'description' => '',
        ));

    endif;
}

/* ADD BOTAO EDITOR DE TEXTO*/
add_action('admin_init', 'my_tinymce_button');

function my_tinymce_button()
{
    if (current_user_can('edit_posts') && current_user_can('edit_pages')) {
        add_filter('mce_buttons', 'my_register_tinymce_button');
        add_filter('mce_external_plugins', 'my_add_tinymce_button');
    }
}

function my_register_tinymce_button($buttons)
{
    array_push($buttons, "button_eek", "button_green");
    return $buttons;
}

function my_add_tinymce_button($plugin_array)
{
    $plugin_array['my_button_script'] = plugins_url('/js/botao-editor.js', __FILE__);
    return $plugin_array;
}

foreach (array('post.php', 'post-new.php') as $hook) {
    add_action("admin_head", 'modal_botao_editor');
}
function modal_botao_editor()
{
?>
    <div class="modal-cw" style="display: none;">
        <div class="fecha-modal-cw fundo"></div>
        <div class="modal-content">
            <div class="title">
                <button type="button" class="fecha-modal-cw media-modal-close">
                    <span class="media-modal-icon">
                        <span class="screen-reader-text">Fechar o painel de mídia</span>
                    </span>
                </button>
                <h3>Orçamentos CW - O que deseja inserir?</h3>
            </div>
            <div class="content">
                <p>Selecione um item dabaixo e siga os passos seguintes.</p>
                <div class="sanfona-cw">
                    <div class="item">
                        <div class="titulo">
                            <span class="dashicons dashicons-arrow-down-alt2"></span>
                            <h4>Carrossel de produtos</h4>
                        </div>
                        <div class="conteudo">
                            <p>Um carroucel será exibido na página. Selecione uma categoria:</p>
                            <select class="carroucel-cw">
                                <option value="">Todos os produtos</option>
                                <?php
                                $args = array('taxonomy' => 'produto_categoria', 'post_type' => 'produto', 'orderby' => 'title', 'order' => 'ASC');
                                $categories = get_categories($args);

                                foreach ($categories as $category) {
                                ?>
                                    <option value="<?php echo $category->slug; ?>"><?php echo $category->name; ?></option>
                                <?php
                                }
                                ?>
                            </select>
                            <button type="button" class="button button-primary button-large" onclick="add_shortcode('carrossel');">Adicionar Carroucel</button>
                        </div>
                    </div>

                    <div class="item">
                        <div class="titulo">
                            <span class="dashicons dashicons-arrow-down-alt2"></span>
                            <h4>Página de produtos</h4>
                        </div>
                        <div class="conteudo">
                            <p>Mostrar todos os seus produtos.</p>

                            <select class="produtos-categorias-cw" multiple>
                                <option value="">Todos os produtos</option>
                                <?php
                                $args = array('taxonomy' => 'produto_categoria', 'post_type' => 'produto', 'orderby' => 'title', 'order' => 'ASC');
                                $categories = get_categories($args);

                                foreach ($categories as $category) {
                                ?>
                                    <option value="<?php echo $category->slug; ?>"><?php echo $category->name; ?></option>
                                <?php
                                }
                                ?>
                            </select>
                            <p>Quantidade por página</p>
                            <input type="number" value="10" name="posts_per_page">

                            <button type="button" class="button button-primary button-large" onclick="add_shortcode('produtos');">Adicionar Produtos</button>


                        </div>
                    </div>

                    <div class="item">
                        <div class="titulo">
                            <span class="dashicons dashicons-arrow-down-alt2"></span>
                            <h4>Menu de Categorias</h4>
                        </div>
                        <div class="conteudo">
                            <p>Insira lista (menu) com a categorias. Título padrão "Categorias", pode ser alterado.</p>
                            <label for="cat-titulo">
                                <b>Titulo:</b> <br>
                                <input type="text" id="cat-titulo" value="Categorias">
                            </label>
                            <button type="button" class="button button-primary button-large" onclick="add_shortcode('menuCategorias');">Adicionar Categorias</button>
                        </div>
                    </div>
                </div>
                <hr>
                <p>Dúvidas? Acesse a <a href="https://cicloneweb.com.br" target="_blanc">página oficial</a>.</p>
            </div>
        </div>
    </div>
    <script>
        function copiarShortcode(id) {
            var copyText = jQuery(id);;
            copyText.select();
            document.execCommand("copy");
        }

        function urlPlugin() {
            return '<?php echo plugins_url('', __FILE__) ?>';
        }
        jQuery('.fecha-modal-cw').click(function() {
            jQuery('.conteudo').slideUp();
            var modal = jQuery(this).parents('.modal-cw').toggle();
        });


        function add_shortcode(tipo) {
            switch (tipo) {

                /********** CARROSSEL *********/
                case 'carrossel':
                    var categoria = jQuery('select.carroucel-cw').val();
                    if (categoria != '') {
                        categoria = ' categoria="' + categoria + '"';
                    } else {
                        categoria = '';
                    }
                    var shortcode = '[orcamento_carrossel' + categoria + ']';
                    break;

                    /********** PRODUTOS **********/
                case 'produtos':
                    var categoria = jQuery('select.produtos-categorias-cw').val();
                    var posts_per_page = jQuery('input[name="posts_per_page"]').val();
                    if (posts_per_page != null) {
                        posts_per_page = ' posts_per_page="' + posts_per_page + '"';
                    } else {
                        posts_per_page = '';
                    }
                    if (categoria != null) {
                        categoria = ' categoria="' + categoria + '"';
                    } else {
                        categoria = '';
                    }
                    var shortcode = '[produtos_orcamento' + categoria + posts_per_page + ']';
                    break;

                    /******* MENU de CATEGORIAS *******/
                case 'menuCategorias':
                    var titulo = jQuery('#cat-titulo').val();
                    var shortcode = '[orcamento_menu_categorias titulo="' + titulo + '"]';
                    break;

                default:
                    alert('Ops.. Não encontramos a função "' + tipo + '"');
                    return false;
            }


            var editor = tinymce.get('content');
            editor.selection.setContent(shortcode);
            jQuery('.modal-cw').toggle();
            return false;
        }

        jQuery('.item .titulo').click(function() {
            jQuery(this).siblings('.conteudo').slideToggle("slow");
        });
    </script>
    <style>
        .modal-cw {
            position: fixed;
            z-index: 10000;
            background-color: #00000061;
            height: 100%;
            width: 100%;
        }

        .modal-cw>.fundo {
            width: 100%;
            height: 100%;
            position: absolute;
        }

        .modal-cw .modal-content {
            max-width: 750px;
            max-height: 80%;
            width: 100%;
            background-color: #fff;
            margin: auto;
            margin-top: 40px;
            padding-top: 0;
            z-index: 1000;
            position: relative;
            overflow: auto;
        }

        .modal-cw .title {
            background-color: #f1f1f1;
            padding: 1px 1px 1px 15px;
            position: relative;
        }

        .modal-cw .content {
            padding: 15px;
        }

        /*SANFONA*/
        .sanfona-cw .item {
            border: 1px solid #efefef;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        .sanfona-cw>.item>.titulo {
            background-color: #f7f7f7;
            position: relative;
            padding: 5px;
            padding-left: 15px;
        }

        .sanfona-cw>.item>.titulo>span {
            float: right;
            margin-right: 10px;
            margin-top: 5px;
        }

        .sanfona-cw>.item>.titulo>h4 {
            margin-top: 7px;
            margin-bottom: 7px;
        }

        .sanfona-cw>.item>.conteudo {
            padding: 15px;
            display: none;
        }

        div#resposta_orcamento,
        .modal {
            position: fixed !important;
            z-index: 9999;
            background-color: #00000054;
        }

        .modal-backdrop.fade.in {
            display: none;
        }
    </style>
    <?php
}

add_action('wp_ajax_novaVariacao', 'novaVariacao');
function novaVariacao()
{
    global $wpdb;
    $tipo = $_POST['tipo'];
    $nome = $_POST['nome'];
    if ($tipo != '') {
        $variacoes = $wpdb->get_results("SELECT * FROM orcamento_cw_variacoes WHERE nome = '$nome'");
        if ($variacoes[0]) {
            echo '<p class="text-danger">Já existe uma variação com o nome <b>' . $nome . '</b>. Altere o nome.</p>';
        } else {
            $wpdb->get_results("INSERT INTO orcamento_cw_variacoes (nome, tipo) VALUES ('$nome','$tipo')");
            if ($wpdb->last_error) {
                echo '<p class="text-danger">' . $wpdb->last_error . '</p>';
            } else {
    ?>
                <p class="text-success"><b>OK!</b> Variação criada</p>
                <script>
                    templatesVariacoes();
                </script>
    <?php
            }
        }
    } else {
        echo '<p class="text-danger">Selecione o <b>tipo</b> da variação</p>';
    }


    ?>
    <script>
        setTimeout(function() {
            jQuery('#novaVariacao .resp p').slideUp('slow');
        }, 4000);
    </script>
    <?php
    wp_die();
}

add_action('wp_ajax_templatesVariacoes', 'templatesVariacoes');
function templatesVariacoes()
{
    global $wpdb;
    require_once(plugin_dir_path(__FILE__) . 'templates/templatesVariacoes.php');
    wp_die();
}

add_action('wp_ajax_excluiVariacao', 'excluiVariacao');
function excluiVariacao()
{
    global $wpdb;
    $id = $_POST['id'];
    $wpdb->get_results("DELETE FROM orcamento_cw_variacoes WHERE id ='$id'");
    if ($wpdb->last_error) {
        echo $wpdb->last_error;
    } else {
    ?>
        <script>
            jQuery('#panel_<?php echo $id; ?>').hide('slow', function() {
                jQuery('#panel_<?php echo $id; ?>').remove();
            });
        </script>
        <?php
    }
    wp_die();
}

add_action('wp_ajax_atualizaVariacao', 'atualizaVariacao');
function atualizaVariacao()
{
    global $wpdb;
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $variacao = $wpdb->get_results("SELECT * FROM orcamento_cw_variacoes WHERE id = '$id'");
    if ($variacao) {
        if (($_POST['opcao']['itens'] == '') && ($variacao[0]->tipo == 'select' or $variacao[0]->tipo == 'checkbox' or $variacao[0]->tipo == 'radio')) {
            echo '<p class="text-danger">Inclua as <b>opções</b> para esta variação</p>';
        } else {
            $opcoes = (isset($_POST['opcao'])) ? serialize($_POST['opcao']) : '';
            $wpdb->get_results("UPDATE orcamento_cw_variacoes set nome='$nome', opcoes='$opcoes' where id='$id'");
            if ($wpdb->last_error) {
                echo '<p class="text-danger">' . $wpdb->last_error . '</p>';
            } else {
                echo '<p class="text-success"><b>OK! Atualizado</b></p>';
            }
        }
    } else {
        echo '<p class="text-danger"><b>Erro!</b> Variação não encontrada</p>';
    }
    wp_die();
}

function buttonQuant($dados = [])
{
    $config = get_option('configOrcamento');
    $orcamento_text = (orcamentoConfig('single_produto_texto')) ? orcamentoConfig('single_produto_texto') : 'Enviar Orçamento';
    $orcamento_back_color = (orcamentoConfig('single_produto_cor_fundo')) ? 'background-color:' . orcamentoConfig('single_produto_cor_fundo') . '; ' : '';
    $orcamento_text_color = (orcamentoConfig('single_produto_cor_texto')) ? 'color:' . orcamentoConfig('single_produto_cor_texto') . '; ' : '';
    $classInput = (isset($dados['classInput'])) ? $dados['classInput'] : '';
    $quantidade = (isset($dados['quantidade'])) ? $dados['quantidade'] : 1;
    $attr       = (isset($dados['attr'])) ? $dados['attr'] : '';
    $style = 'style="' . $orcamento_back_color . $orcamento_text_color . '"';
    $html = '<div class="quantidade flex-center">
                    <button type="button" class="quant subtrai" onclick="alteraQuant(this)" ' . $style . '><i class="fas fa-minus"></i></button>
                    <input type="tel" class="' . $classInput . '" name="quantidade" value="' . $quantidade . '" ' . $attr . '>
                    <button type="button" class="quant soma" onclick="alteraQuant(this)" ' . $style . '><i class="fas fa-plus"></i></button>
                </div>';
    return $html;
}

if (function_exists("register_field_group")) {

    global $wpdb;
    $variacoes = $wpdb->get_results("SELECT * FROM orcamento_cw_variacoes ORDER BY id DESC");
    if ($variacoes) {

        $fields = array();
        foreach ($variacoes as $v) {
            if ($v->tipo == 'radio' or $v->tipo == 'select' or $v->tipo == 'checkbox') {
                $opcoes = unserialize($v->opcoes)['itens'];

                $default_value = (is_array($opcoes) && unserialize($v->opcoes)['marcarOpcoes']) ? array_keys($opcoes) : false;

                $array_variacao = array(
                    'key' => caracteresEspeciais($v->nome),
                    'label' => $v->nome,
                    'name' => caracteresEspeciais($v->nome),
                    'type' => 'checkbox',
                    'conditional_logic' => array(
                        'status' => 1,
                        'rules' => array(
                            array(
                                'field' => 'exibe_' . caracteresEspeciais($v->nome),
                                'operator' => '==',
                                'value' => '1',
                            ),
                        ),
                        'allorany' => 'all',
                    ),
                    'choices' => $opcoes,
                    'default_value' => $default_value,
                    'layout' => 'horizontal',
                );
            } else {
                $array_variacao = array(
                    'key' => caracteresEspeciais($v->nome),
                    'label' => $v->nome,
                    'name' => caracteresEspeciais($v->nome),
                    'instructions' => 'Placeholder',
                    'default_value' => unserialize($v->opcoes)['placeholder'],
                    'type' => 'text',
                    'conditional_logic' => array(
                        'status' => 1,
                        'rules' => array(
                            array(
                                'field' => 'exibe_' . caracteresEspeciais($v->nome),
                                'operator' => '==',
                                'value' => '1',
                            ),
                        ),
                        'allorany' => 'all',
                    )
                    //'choices' => $opcoes,
                    //'default_value' => array_keys($opcoes),
                    //'layout' => 'horizontal',
                );
            }


            $fields[] =    array(
                'key' => 'exibe_' . caracteresEspeciais($v->nome),
                'label' => 'Exibir ' . $v->nome . '?',
                'name' => 'exibe_' . caracteresEspeciais($v->nome),
                'type' => 'true_false',
                'message' => 'Sim, exibir.',
                'default_value' => 0,
            );
            $fields[] = $array_variacao;
        }
    }

    register_field_group(array(
        'id' => 'acf_variacoes',
        'title' => 'Variações',
        'fields' => $fields,
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'produto',
                    'order_no' => 0,
                    'group_no' => 0,
                ),
            ),
        ),
        'options' => array(
            'position' => 'normal',
            'layout' => 'default',
            'hide_on_screen' => array(),
        ),
        'menu_order' => 0,
    ));
}

if (!function_exists('pesquisaCep')) {
    add_action('wp_ajax_pesquisaCep', 'pesquisaCep');
    add_action('wp_ajax_nopriv_pesquisaCep', 'pesquisaCep');
    function pesquisaCep()
    {
        header('Content-type:text/json;charset=utf-8');
        $cep = $_POST['cep'];
        $ch = curl_init('http://cep.republicavirtual.com.br/web_cep.php?formato=json&cep=' . $cep);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        echo curl_exec($ch);
        wp_die();
    }
}

add_action('wp_ajax_pedirOrcamento', 'pedirOrcamento');
add_action('wp_ajax_nopriv_pedirOrcamento', 'pedirOrcamento');
function pedirOrcamento()
{
    global $wpdb;
    $dados = $_POST;
    $exclui = $_POST['exclui'];
    $keyPost = $_POST['key'];
    $origem = $_POST['origem'];
    $quant  = ($_POST['quantidade'] > 0) ? $_POST['quantidade'] : 1;
    $car = isset($_COOKIE["carrinho"]) ? $_COOKIE["carrinho"] : "";
    $carrinho  = unserialize(stripslashes($car));
    $carrinho2 = $carrinho;

    $variacoes = $wpdb->get_results("SELECT * FROM orcamento_cw_variacoes");
    /*O $carrinho2 é para não perder o campo ['quantidade'] no unset() */
    $jaTem = false;
    $jaTemId = false;

    if ($exclui != 'exclui') {
        if ($origem == '2') {
            // 2 = carrinho		
            $carrinho[$keyPost]['quantidade'] = $quant;
            if (setcookie('carrinho', serialize($carrinho), (time() + (30 * 24 * 3600)), '/')) {
            }
        } else {
            foreach ($carrinho as $key => $val) {
                if ($carrinho[$key]['id_produto'] != '') {
                    unset($dados['quantidade']);
                    unset($carrinho[$key]['quantidade']);
                    unset($carrinho[$key]['exclui']);
                    unset($dados['exclui']);
                    unset($carrinho[$key]['key']);
                    unset($dados['key']);
                    unset($carrinho[$key]['origem']);
                    unset($dados['origem']);
                    if ($carrinho[$key]['id_produto'] == $dados['id_produto']) {
                        $jaTemId = true;
                    }
                    if ($carrinho[$key] == $dados) {
                        $carrinho2[$key] = $_POST;
                        $jaTem = true;
                    }
                } else {
                    unset($carrinho2[$key]);
                }
            }
            if (!$jaTem) {
                $carrinho2[] = $_POST;
            }

            $itens = serialize($carrinho2);
            if (setcookie('carrinho', $itens, (time() + (30 * 24 * 3600)), '/')) {
                if ($jaTemId == true && $jaTem == false) {
        ?>
                    <div class="alert alert-warning">Produto duplicado no orçamento por ter escolhido opções diferentes</div>
                    <?php
                }
                foreach ($carrinho2 as $key => $val) {
                    $id_produto = $carrinho2[$key]['id_produto'];
                    if ($id_produto > 0) {
                        query_posts("post_type=produto&p=" . $id_produto);
                        if (have_posts()) {
                            while (have_posts()) {
                                the_post();
                                global $post;
                                $pisca = ($post->ID == $_POST['id_produto']) ? ' pisca' : '';
                                $id_produto_atual = $post->ID;
                                $image = orcamento_cw_get_imgDestaque('thumbnail');
                                $titulo = get_the_title();

                                $temVariacao = false;
                                foreach ($variacoes as $v) {
                                    if (get_field('exibe_' . caracteresEspeciais($v->nome))) {
                                        $temVariacao = true;
                                    }
                                }
                                $itemVar = '';
                                if ($temVariacao) {
                                    $itemVar .= '<div id="collapse' . $key . '" style="font-size: 12px">';
                                    foreach ($variacoes as $v) {
                                        if (get_field('exibe_' . caracteresEspeciais($v->nome))) {
                                            $valor = $carrinho2[$key][caracteresEspeciais($v->nome)];
                                            $itemVar .= $v->nome . ': ';
                                            if (is_array($valor)) {
                                                $vir = 1;
                                                foreach ($valor as $va) {
                                                    $virgula = ($vir < count($valor)) ? ', ' : '';
                                                    $itemVar .= $va . $virgula;
                                                    $vir++;
                                                }
                                            } else {
                                                $itemVar .= $valor . '<br>';
                                            }
                                        }
                                    }
                                    $itemVar .= '</div>';
                                }

                                $itemVar = preg_replace("/\r?\n/", "", $itemVar);

                                $itemCarrinho = '<div class="row-cw produto ' . $pisca . '" id="' . $key . '">
								<div class="col-cw s2">
								<img src="' . $image . '">
								</div>
								<div class="col-cw s4">
								<p style="margin-bottom:0"><b>"' . $titulo . '"</b></p>' . $itemVar . '
								</div>
								<div class="col-cw s4 actioAddOrcamento flex-center">
								<!--<input type="number" class="form-control quant' . $key . '" style="margin-top:8px;" name="quant" value="' . $carrinho2[$key]['quantidade'] . '">-->
                                ' . buttonQuant(array(
                                    'quantidade' => $carrinho2[$key]['quantidade'],
                                    'attr' => 'onblur="orcamento(\\\'' . $key . '\\\',\\\'' . $carrinho2[$key]['id_produto'] . '\\\',\\\'2\\\');"',
                                    'classInput' => 'quant' . $key
                                )) . '
                                </div>
								<div class="col-cw s2">
								<button id="exclui' . $key . '" class="excluiModal" onclick="orcamento(\\\'' . $key . '\\\',\\\'\\\', \\\'\\\', \\\'exclui\\\')">
								<span class="fa fa-trash"></span></button></div><div class="col-12">
								</div>
								</div>';

                                $produtoCarrinho .= preg_replace("/\r?\n/", "", $itemCarrinho);
                            }
                        } else {
                    ?>
                            <script>
                                orcamento('<?php echo $key ?>', '', '', 'exclui')
                            </script>
                <?php
                        }
                    }
                }

                ?>

                <script>
                    jQuery.noConflict();
                    jQuery('a.cw_link_carrinho span').html('<?php echo count($carrinho2) ?>');
                    jQuery("#resposta_orcamento").modalCw("show");
                    jQuery(".btn-produto").html('<b>PRODUTO ADICIONADO</b>');
                    jQuery("#conteudo-orcamento").html('<?php echo $produtoCarrinho ?>');
                    pisca();
                    desablitaSubtracao();
                </script>
                <div class="alert alert-success"><b>OK!</b> Produto adicionado</div>
            <?php
            }
        }
    } else {
        unset($carrinho[$keyPost]);
        if (setcookie('carrinho', serialize($carrinho), (time() + (30 * 24 * 3600)), '/')) {
            echo '<script>
			jQuery("#' . $keyPost . '").hide("500");
			jQuery("a.cw_link_carrinho span").html("' . count($carrinho) . '");
			if("' . count($carrinho) . '" == 0	){
				jQuery("#conteudo-orcamento").html("<p>Carrinho Vazio</p>");
			}
			</script>';
        }
        if (count($carrinho) == 0) {
            ?>
            <script>
                jQuery("#conteudo-orcamento").html('<p>Carrinho Vazio</p>');
                jQuery("#carrinho_orcamento").html('<div class="text-center vazio form-group" style="padding: 40px 0"><h2>Sua lista de orçamento está vazia</h2><br><a href="<?php the_permalink(get_option('pg_produtos')) ?>" style="background-color: <?php echo $orcamento_back_color ?>; color: <?php echo $orcamento_text_color ?>" class="btn btn-lg"><i class="fa fa-reply" aria-hidden="true"></i> <b>Produtos</b></a></div>');
            </script>
    <?php
        }
    }

    wp_die();
}

add_action('wp_ajax_quantCarrinho', 'quantCarrinho');
add_action('wp_ajax_nopriv_quantCarrinho', 'quantCarrinho');
function quantCarrinho()
{
    global $wpdb;
    $config = get_option('configOrcamento');
    $orcamento_text = (orcamentoConfig('finalizacao_texto')) ? orcamentoConfig('finalizacao_texto') : 'Enviar Orçamento';
    $orcamento_back_color = orcamentoConfig('finalizacao_cor_fundo');
    $orcamento_text_color = orcamentoConfig('finalizacao_cor_texto');

    $carrinho = unserialize(stripslashes($_COOKIE['carrinho']));
    if (isset($_COOKIE["carrinho"])) {
        $car = (isset($_COOKIE["carrinho"])) ? $_COOKIE["carrinho"] : "";
        $carrinho  = (unserialize(stripslashes($car))) ? unserialize(stripslashes($car)) : 0;
        $quantCar = (count($carrinho)) ? count($carrinho) : 0;
    } else {
        $quantCar = 0;
    }
    ?>
    <script>
        jQuery('a.cw_link_carrinho span').html('<?php echo $quantCar ?>');
        if (<?php echo $quantCar ?> == 0) {
            jQuery("#carrinho_orcamento").html('<div class="text-center vazio form-group" style="padding: 40px 0"><h2>Sua lista de orçamento está vazia</h2><br><a href="<?php the_permalink(get_option('pg_produtos')) ?>" style="background-color: <?php echo $orcamento_back_color ?>; color: <?php echo $orcamento_text_color ?>" class="btn btn-lg"><i class="fa fa-reply" aria-hidden="true"></i> <b>Produtos</b></a></div>');
        }
    </script>
    <?php
    wp_die();
}

function script_orcamento_cw()
{
    wp_register_script('jquery.maskedinput.min', plugins_url('/js/jquery.maskedinput.min.js?versao=' . versao_cw(), __FILE__), array('jquery'));
    wp_enqueue_script('jquery.maskedinput.min');

    wp_register_script('swiper', plugins_url('/js/swiper-bundle.min.js?versao=' . versao_cw(), __FILE__), array('jquery'));
    wp_enqueue_script('swiper');

    wp_register_script('prettyPhoto', 'http://metagreen.com.br/wp-content/plugins/js_composer_theme/assets/lib/prettyphoto/js/jquery.prettyPhoto.min.js?ver=6.0.5', array('jquery'));
    wp_enqueue_script('prettyPhoto');

    wp_enqueue_script('ajax-script', plugins_url('/js/script.js?versao=' . versao_cw(), __FILE__), array());
    wp_localize_script('ajax-script', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));

}
add_action('wp_enqueue_scripts', 'script_orcamento_cw');

function style_orcamento_cw()
{
    // Register the style like this for a plugin:
    wp_register_style('font-awesome.min', 'https://use.fontawesome.com/releases/v5.2.0/css/all.css');
    wp_enqueue_style('font-awesome.min');

    wp_enqueue_style('prettyPhoto', 'http://metagreen.com.br/wp-content/plugins/js_composer_theme/assets/lib/prettyphoto/css/prettyPhoto.min.css?ver=6.0.5');
    wp_enqueue_style('prettyPhoto');

    wp_register_style('animate', plugins_url('/css/animate.css?versao=' . versao_cw(), __FILE__));
    wp_enqueue_style('animate');

    wp_register_style('swiper', plugins_url('/css/swiper-bundle.min.css?versao=' . versao_cw(), __FILE__));
    wp_enqueue_style('swiper');

    wp_register_style('style-cw', plugins_url('/css/style-cw.css?versao=' . versao_cw(), __FILE__));
    wp_enqueue_style('style-cw');
}
add_action('wp_enqueue_scripts', 'style_orcamento_cw');


function menu_cat_cw_register_widget()
{
    register_widget('menu_cat_cw_widget');
}

add_action('widgets_init', 'menu_cat_cw_register_widget');

class menu_cat_cw_widget extends WP_Widget
{

    function __construct()
    {
        parent::__construct(
            /*widget ID*/
            'menu_cat_cw_widget',
            /*widget name*/
            __('Menu Categorias - CW', ' menu_cat_cw_widget_domain'),
            /*widget description*/
            array('description' => __('Plugin Orçamento CW', 'menu_cat_cw_widget_domain'),)
        );
    }
    public function widget($args, $instance)
    {
        $title = apply_filters('widget_title', $instance['title']);
        $mostraQuantidade = apply_filters('widget_title', $instance['mostraQuantidade']);
        echo $args['before_widget'];
        /*if title is present*/
        if (!empty($title))
            echo $args['before_title'] . $title . $args['after_title'];
        /*output*/
        echo do_shortcode('[orcamento_menu_categorias mostraQuantidade="' . $mostraQuantidade . '"]');
        echo $args['after_widget'];
    }
    public function form($instance)
    {
        if (isset($instance['title']))
            $title = $instance['title'];
        else
            $title = __('Default Title', 'menu_cat_cw_widget_domain');

        if (isset($instance['mostraQuantidade'])) {
            $checkedSim = ($instance['mostraQuantidade']) ? 'checked' : '';
            $checkedNao = ($instance['mostraQuantidade']) ? '' : 'checked';
        }
    ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('mostraQuantidade'); ?>"><?php _e('Mostrar quantidade por categoria:<br>'); ?></label>
            <label>
                <input class="widefat" id="<?php echo $this->get_field_id('mostraQuantidade'); ?>" name="<?php echo $this->get_field_name('mostraQuantidade'); ?>" type="radio" <?php echo $checkedSim ?> value="1" />Sim <br>
            </label>
            <label>
                <input class="widefat" id="<?php echo $this->get_field_id('mostraQuantidade'); ?>" name="<?php echo $this->get_field_name('mostraQuantidade'); ?>" type="radio" <?php echo $checkedNao ?> value="0" />Não
            </label>

        </p>
    <?php
    }
    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        $instance['mostraQuantidade'] = (!empty($new_instance['mostraQuantidade'])) ? strip_tags($new_instance['mostraQuantidade']) : '';
        return $instance;
    }
}

add_action('wp_ajax_form_login', 'form_login');
add_action('wp_ajax_nopriv_form_login', 'form_login');
function form_login()
{
    //check_ajax_referer( 'ajax-register-nonce', 'security' );
    $info = array();
    $info['user_login']     = $_POST['usuario'];
    $info['user_password']     = $_POST['senha'];
    $info['remember']         = true;

    $user_signon = wp_signon($info, false);
    if (is_wp_error($user_signon)) {
    ?>
        <p class="text-danger"><b>Erro!</b> Usuário ou senha incorretos</p>
    <?php
    } else {
    ?>
        <p class="text-success">OK! Atualizando <i class="fa fa-spinner fa-pulse"></i></p>
        <script>
            window.location.reload();
        </script>
        <?php
    }
    wp_die();
}

add_action('wp_ajax_form_cadastro', 'form_cadastro');
add_action('wp_ajax_nopriv_form_cadastro', 'form_cadastro');
function form_cadastro()
{
    //check_ajax_referer( 'ajax-register-nonce', 'security' );
    $user_login = $_POST['nome'];
    $user = get_user_by('login', $user_login);
    $i = 1;
    while ($user) {
        $novo_user_login = $user_login . '_' . $i;
        $userA = get_user_by('login', $novo_user_login);
        if (!$userA) {
            $user_login = $novo_user_login;
            break;
        }
        $i++;
    }

    $userdata = array(
        'user_login'  =>  $user_login,
        'user_email'  =>  $_POST['email'],
        'first_name'  =>  $_POST['nome'],
        'last_name'   =>  $_POST['lastname'],
        'user_pass'   =>  $_POST['senha']
    );
    $user_id = wp_insert_user($userdata);

    if (!is_wp_error($user_id)) {

        $info = array();
        $info['user_login']     = $user_login;
        $info['user_password']     = $_POST['senha'];
        $info['remember']         = true;

        $user_signon = wp_signon($info, false);
        if (is_wp_error($user_signon)) {
            print_r($user_signon->get_error_codes());
        } else {

            $to = get_option('admin_email');
            $subject = 'Novo usuário';
            $body = "Um novo usuário acabou de se cadastrar seu site. <br>DADOS DO USUÁRIO <br>Nome: " . $userdata['first_name'] . "<br>Email: " . $userdata['user_email'];
            $headers = array('Content-Type: text/html; charset=UTF-8; From: ' . get_option('blogname') . ' <' . get_option('admin_email') . '>');
            wp_mail($to, $subject, $body, $headers);

            $toUser = $userdata['user_email'];;
            $subjectUser = 'Obrigado pelo seu cadastro - ' . get_option('blogname');
            $bodyUser = "Muito obrigado por ter se cadastrado em nosso site.<br>
			Para altera sua senha <a href='" . wp_lostpassword_url() . "'>clique aqui</a><br>
			<a href='" . get_option('siteUrl') . "'>" . get_option('blogname') . "</a>";

            wp_mail($toUser, $subjectUser, $bodyUser, $headers);
        ?>
            <p class="text-success">OK! Atualizando <i class="fa fa-spinner fa-pulse"></i></p>
            <script>
                window.location.reload();
            </script>
        <?php
        }
    } else {
        $erro = $user_id->get_error_codes()[0];
        switch ($erro) {
            case 'existing_user_email':
                $erro = 'Esse email já está em uso';
                break;

            case 'existing_user_login':
                $erro = 'Esse nome de Usuário já está em uso';
                break;
        }
        echo '<p class="text-danger"><b>Erro!</b> ' . $erro . '</p>';
    }
    wp_die();
}

add_action('wp_ajax_meus_dados', 'meus_dados');
add_action('wp_ajax_nopriv_meus_dados', 'meus_dados');
function meus_dados()
{

    $user_signon = wp_signon($info, false);
    if (is_wp_error($user_signon)) {
        ?>
        <p class="text-danger"><b>Erro!</b> Link expirado. Atualize a página</p>
    <?php
    } else {
        $user = wp_get_current_user();
        if ($user) {
            foreach ($_POST as $key => $value) {
                if ($key != 'action' && $key != 'email' && $key != 'nome') {
                    update_user_meta($user->ID, $key, $value);
                }
            }
        }
    ?>
        <p class="text-success text-center">OK! Atualizado</p>
<?php
    }
    wp_die();
}

function add_contact_methods($profile_fields)
{
    $profile_fields['telefone'] = 'Telefone';
    $profile_fields['rg']         = 'RG';
    $profile_fields['cpf']         = 'CPF';
    $profile_fields['cnpj']     = 'CNPJ';
    $profile_fields['empresa']     = 'Empresa';
    $profile_fields['cep']         = 'CEP';
    $profile_fields['rua']         = 'rua';
    $profile_fields['numero']     = 'Numero';
    $profile_fields['bairro']     = 'Bairro';
    $profile_fields['cidade']     = 'Cidade';
    $profile_fields['estado']     = 'Estado';
    return $profile_fields;
}
add_filter('user_contactmethods', 'add_contact_methods');
