<!-- Adiciona o cabeçalho (header.php) -->
<?php get_header();

if (function_exists('migalhas')) {
    migalhas(get_the_archive_title());
}

$ss = single_cat_title("", false);
$tituloCat = single_cat_title("", false);
?>

<section class="produtos module content-area" id="primary">
    <div class="container wrap">
        <header class="page-header">
            <?php
            if (!function_exists('migalhas')) {
                the_archive_title('<h1 class="entry-title">', '</h1>');
            }
            ?>
        </header>
        <div class="row">
            <div class="col-12 col-md-3">
                <div class="widget widget_nav_menu mk-in-viewport">
                    <?php wp_nav_menu('categorias'); ?>
                </div>
            </div>

            <!-- PRODUTOS EM DESTAQUE -->

            <div class="col-12 col-md-9">
                <?php the_archive_description('<div class="taxonomy-description">', '</div>'); ?>
                <div class="row">
                    <?php
                    global $query_string;
                    $paged = (get_query_var('page')) ? get_query_var('page') : 1;
                    query_posts($query_string . '&page=$paged');
                    if (have_posts()) : while (have_posts()) : the_post();
                            $class = 'col-12 col-sm-6 col-md-4';
                            require(plugin_dir_path(__FILE__) . 'content-produto.php');
                        endwhile;
                    endif;
                    wp_reset_query();
                    echo $conteudo;
                    ?>
                    <div class="col-12 orcamentos_paginacao">
                        <?php echo orcamento_pagination(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php get_sidebar() ?>
<!-- Adiciona o rodapé (footer.php) -->
<?php get_footer(); ?>