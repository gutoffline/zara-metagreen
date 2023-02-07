<div class="row">
    <div class="col-12 col-md-9">
        <?php
        global $wpdb;

        $orcamentos = $wpdb->get_results("SELECT * FROM orcamentos_cw_pedidos order by id DESC");
        if ($orcamentos) {
            foreach ($orcamentos as $orcamento) {
        ?>
                <div class="orcamento" id="orcamento-<?php echo $orcamento->id; ?>">
                    <a role="button" data-toggle="collapse" href="#collapse<?php echo $orcamento->id ?>" aria-expanded="false" aria-controls="collapse<?php echo $orcamento->id ?>">
                        <h2>
                            <i class="fa fa-chevron-down float-right"></i> #<?php echo $orcamento->id ?> <?php echo $orcamento->nomeCliente; ?> <small><?php echo date('d/m/Y h:i', strtotime($orcamento->data)); ?></small>
                        </h2>
                    </a>
                    <div class="collapse" id="collapse<?php echo $orcamento->id ?>">
                        <div class="well">
                            <?php echo $orcamento->orcamento; ?>
                            <div class="form-group">
                                <button class="btn btn-info float-right" onclick="imprimirOrcamento('<?php echo $orcamento->id ?>')"> <i class="fa fa-print"></i> Imprimir</button>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-12 resp"></div>
                                <div class="col-12">
                                    <div style="width: 180px" class="modelo_<?php echo $orcamento->id ?>">
                                        <?php
                                        $modeloResp = $wpdb->get_results("SELECT * FROM orcamento_modelo_resp");
                                        if ($modeloResp) {
                                        ?>
                                            <label>Modelo Resposta</label>
                                            <select class="form-control">
                                                <?php
                                                foreach ($modeloResp as $resp) {
                                                    echo '<option value="' . $resp->id . '">' . $resp->nome . '</option>';
                                                }
                                                ?>

                                            </select>
                                        <?php
                                        }
                                        ?>

                                        <button class="float-left btn-lg btn btn-success btn-block" onclick="modalResponde(<?php echo $orcamento->id ?>)"><i class="fa fa-envelope-o"></i> Responder</button>
                                    </div>
                                    <button class="float-right btn-lg btn btn-danger exclui" onclick="excluiPedido(<?php echo $orcamento->id ?>);"><i class="fa fa-trash"></i> Excluir</button>
                                </div>
                            </div>

                            <?php
                            $respostas = $wpdb->get_results("SELECT * FROM orcamentos_cw_resposta WHERE id_orcamento = '$orcamento->id' order by id DESC");
                            if ($respostas) {
                            ?>
                                <hr>
                                <h3>Respostas</h3>
                                <?php
                                foreach ($respostas as $resposta) {
                                ?>
                                    <a role="button" data-toggle="collapse" href="#resposta<?php echo $resposta->id ?>" aria-expanded="false" aria-controls="resposta<?php echo $resposta->id ?>">
                                        <h4 style="background-color: #7498aa;
										padding: 10px;
										color: #fff;
										margin-bottom: 0px;">
                                            <i class="fa fa-chevron-down float-right"></i> Resposta em: <?php echo date('d/m/Y h:i', strtotime($resposta->data)); ?>
                                        </h4>
                                    </a>
                                    <div class="collapse" id="resposta<?php echo $resposta->id ?>">
                                        <div class="well">
                                            <?php echo $resposta->resposta; ?>
                                        </div>
                                    </div>
                            <?php
                                }
                            }
                            ?>

                        </div>
                    </div>
                </div>
            <?php
            }
        } else {
            ?>
            <div class="alert alert-warning">
                Nenhum orçamento encontrado.
            </div>
        <?php
        }

        ?>
    </div>
    <div class="col-12 col-sm-3">
        <?php sidebar_admin_orcamento_cw(); ?>
    </div>
</div>


<div class="modal fade" id="responde-orcamento">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Responder ao orçamento</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h3>Aguarde... <i class="fa fa-spinner fa-pulse"></i></h3>
            </div>
        </div>
    </div>
</div>