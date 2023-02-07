<?php
/**
 * As configurações básicas do WordPress
 *
 * O script de criação wp-config.php usa esse arquivo durante a instalação.
 * Você não precisa usar o site, você pode copiar este arquivo
 * para "wp-config.php" e preencher os valores.
 *
 * Este arquivo contém as seguintes configurações:
 *
 * * Configurações do MySQL
 * * Chaves secretas
 * * Prefixo do banco de dados
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Configurações do MySQL - Você pode pegar estas informações com o serviço de hospedagem ** //
/** O nome do banco de dados do WordPress */
define( 'DB_NAME', 'metagreen_wp' );


/** Usuário do banco de dados MySQL */
define( 'DB_USER', 'metagreen_wp' );


/** Senha do banco de dados MySQL */
define( 'DB_PASSWORD', 'm*_6mJX2mWXq@W' );


/** Nome do host do MySQL */
define( 'DB_HOST', 'metagreen_wp.mysql.dbaas.com.br' );


/** Charset do banco de dados a ser usado na criação das tabelas. */
define( 'DB_CHARSET', 'utf8mb4' );


/** O tipo de Collate do banco de dados. Não altere isso se tiver dúvidas. */
define( 'DB_COLLATE', '' );

/**#@+
 * Chaves únicas de autenticação e salts.
 *
 * Altere cada chave para um frase única!
 * Você pode gerá-las
 * usando o {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org
 * secret-key service}
 * Você pode alterá-las a qualquer momento para invalidar quaisquer
 * cookies existentes. Isto irá forçar todos os
 * usuários a fazerem login novamente.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '6$9pJp`7Vv#z^;AJL8&KR2g0e}9YH o*QNU_t4l~Z1:{0_#ep{FAE5~d}!J10[P2' );

define( 'SECURE_AUTH_KEY',  'z8Xm|+c.~{P4$VB >iDYi{wI2T.T;nm`,>f?klz/VD8HH0y@F:8NNePe:&oJcX6a' );

define( 'LOGGED_IN_KEY',    'PT,;r3@ >#Qwm8blG^@ZsglCzm6i2w?S#oX65F=7##,F1^b6h T0iT Ji6~= =+7' );

define( 'NONCE_KEY',        'tiAPh0MRdXK}j^`PY7<(]Msa9p DO6Qzlb4%q^=Dz,#UK(w%GpVQI%d_8:Gr$mix' );

define( 'AUTH_SALT',        'w|]Hy>7h+UC<AAaJ/OP`*:m(6W,ZL?IN3Ld)w9AG{T^5ogQ#@TXQ9+m~Sjf7$S,$' );

define( 'SECURE_AUTH_SALT', '(Lr&HE6q9~]jRoy?VYH6@Y+3 `$&s}`B4.S)Oq }_h[b|?gY@k]e{Hi]-QtE@.dX' );

define( 'LOGGED_IN_SALT',   '&M[:5<+QM`AD!rD})=-18))p:h%%k7_ c,jLm_%Dv&-~L-S7d+a+p9UgLx!ikr}~' );

define( 'NONCE_SALT',       'H}j*vNA9Xn,p)34Ps8X_Chii<36hq|-q}PhdsZZ/A:W*4gc5[ObTlsy8A28Elv`g' );


/**#@-*/

/**
 * Prefixo da tabela do banco de dados do WordPress.
 *
 * Você pode ter várias instalações em um único banco de dados se você der
 * um prefixo único para cada um. Somente números, letras e sublinhados!
 */
$table_prefix = 'wp_';


/**
 * Para desenvolvedores: Modo de debug do WordPress.
 *
 * Altere isto para true para ativar a exibição de avisos
 * durante o desenvolvimento. É altamente recomendável que os
 * desenvolvedores de plugins e temas usem o WP_DEBUG
 * em seus ambientes de desenvolvimento.
 *
 * Para informações sobre outras constantes que podem ser utilizadas
 * para depuração, visite o Codex.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Isto é tudo, pode parar de editar! :) */

/** Caminho absoluto para o diretório WordPress. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Configura as variáveis e arquivos do WordPress. */
require_once ABSPATH . 'wp-settings.php';
