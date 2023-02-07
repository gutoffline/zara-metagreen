<?php
$config = get_option('configOrcamento');
$orcamento_text = (orcamentoConfig('produtos_texto')) ? orcamentoConfig('produtos_texto') : 'Orçamento';
$orcamento_back_color = orcamentoConfig('produtos_cor_fundo');
$orcamento_text_color = orcamentoConfig('produtos_cor_texto');

global $wpdb;
$variacoes = $wpdb->get_results("SELECT * FROM orcamento_cw_variacoes");
$temOpcoes = false;
if ($variacoes) {
    foreach ($variacoes as $v) {
        if (get_field('exibe_' . caracteresEspeciais($v->nome))) {
            $temOpcoes = true;
        }
    }
}
$botaoOrcamento = (!$temOpcoes) ? '<button onclick="orcamento(\'\',\'' . get_the_ID() . '\', \'page-produto\',\'\');" style="background-color: ' . $orcamento_back_color . '; color: ' . $orcamento_text_color . '" class="btn ' . orcamentoConfig('produtos_class') . ' produto-' . get_the_ID() . '">' . $orcamento_text . '</button>' : '<a href="' . get_permalink(get_the_ID()) . '"><button style="background-color: ' . $orcamento_back_color . '; color: ' . $orcamento_text_color . '" class="btn ' . orcamentoConfig('produtos_class') . '">' . $orcamento_text . '</button></a>';

$conteudo .= '<div class="' . $class . ' col-cw">
<div class="thumbnail">							
<a href="' . get_permalink() . '" class="imagem">															

<img src="' . orcamento_cw_get_imgDestaque('medium') . '" alt="' . get_the_title() . '" title="' . get_the_title() . ' ' . $style . '">
</a>			
<div class="titulo">
<a href="' . get_permalink() . '" title="' . get_the_title() . '"><h2>' . get_the_title() . '</h2></a>
</div>
' . $botaoOrcamento . '
</div>
</div>';
