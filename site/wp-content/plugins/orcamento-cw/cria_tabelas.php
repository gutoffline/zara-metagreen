<?php 
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

function orcamento_cw_install() {
	global $wpdb;
	
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE orcamentos_cw_pedidos (
	id int(11) NOT NULL AUTO_INCREMENT,
	nomeCliente char(80) NOT NULL,
	idUser int(11) NOT NULL,
	emailCliente char(80) NOT NULL,
	orcamento text NOT NULL,
	produtos text NOT NULL,
	data timestamp NOT NULL,
	PRIMARY KEY (id) ) $charset_collate;";

	$sql .= "CREATE TABLE orcamento_cw_variacoes (
	id int(11) NOT NULL AUTO_INCREMENT,
	nome char(100) NOT NULL,
	tipo char(15) NOT NULL,
	opcoes text NOT NULL,
	PRIMARY KEY  (id) ) $charset_collate;";

	$sql .= "CREATE TABLE orcamentos_cw_resposta (
	id int(11) NOT NULL AUTO_INCREMENT,
	id_orcamento int(11) NOT NULL,
	resposta text NOT NULL,
	data timestamp NOT NULL,
	PRIMARY KEY  (id) ) $charset_collate;";

	$sql .= "CREATE TABLE orcamento_modelo_resp (
	id int(11) NOT NULL AUTO_INCREMENT,
	nome char(50) NOT NULL,
	modelo text NOT NULL,
	PRIMARY KEY  (id) ) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

	dbDelta( $sql );	
	add_option( 'orcamento_cw_version', versao_cw() );
}

function verifica_versao_orcamento(){
	if(get_option('orcamento_cw_version') != versao_cw() ){
		orcamento_cw_install();
		update_option( "orcamento_cw_version", versao_cw() );
	}
}


?>