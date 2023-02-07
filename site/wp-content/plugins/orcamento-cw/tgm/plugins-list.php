<?php
/**
 * This file represents an example of the code that themes would use to register
 * the required plugins.
 *
 * It is expected that theme authors would copy and paste this code into their
 * functions.php file, and amend to suit.
 *
 * @see http://tgmpluginactivation.com/configuration/ for detailed documentation.
 *
 * @package    TGM-Plugin-Activation
 * @subpackage Example
 * @version    2.6.1 for plugin Orcamento Cw
 * @author     Thomas Griffin, Gary Jones, Juliette Reinders Folmer
 * @copyright  Copyright (c) 2011, Thomas Griffin
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       https://github.com/TGMPA/TGM-Plugin-Activation
 */

/**
 * Include the TGM_Plugin_Activation class.
 *
 * Depending on your implementation, you may want to change the include call:
 *
 * Parent Theme:
 * require_once get_template_directory() . '/path/to/class-tgm-plugin-activation.php';
 *
 * Child Theme:
 * require_once get_stylesheet_directory() . '/path/to/class-tgm-plugin-activation.php';
 *
 * Plugin:
 * require_once dirname( __FILE__ ) . '/path/to/class-tgm-plugin-activation.php';
 */
require_once dirname( __FILE__ ) . '/class-tgm-plugin-activation.php';

add_action( 'tgmpa_register', 'orcamento_cw_register_required_plugins' );

/**
 * Register the required plugins for this theme.
 *
 * In this example, we register five plugins:
 * - one included with the TGMPA library
 * - two from an external source, one from an arbitrary source, one from a GitHub repository
 * - two from the .org repo, where one demonstrates the use of the `is_callable` argument
 *
 * The variables passed to the `tgmpa()` function should be:
 * - an array of plugin arrays;
 * - optionally a configuration array.
 * If you are not changing anything in the configuration array, you can remove the array and remove the
 * variable from the function call: `tgmpa( $plugins );`.
 * In that case, the TGMPA default settings will be used.
 *
 * This function is hooked into `tgmpa_register`, which is fired on the WP `init` action on priority 10.
 */
function orcamento_cw_register_required_plugins() {
    /*
     * Array of plugin arrays. Required keys are name and slug.
     * If the source is NOT from the .org repo, then source is also required.
     */
    $plugins = array(

        // This is an example of how to include a plugin bundled with a theme.
        

        // This is an example of how to include a plugin from an arbitrary external source in your theme.
        

        // This is an example of how to include a plugin from a GitHub repository in your theme.
        // This presumes that the plugin code is based in the root of the GitHub repository
        // and not in a subdirectory ('/src') of the repository.
        /*
        array(
            'name'               => 'TGM Example Plugin', // The plugin name.
            'slug'               => 'tgm-example-plugin', // The plugin slug (typically the folder name).
            'source'             => dirname( __FILE__ ) . '/lib/plugins/tgm-example-plugin.zip', // The plugin source.
            'required'           => true, // If false, the plugin is only 'recommended' instead of required.
            'version'            => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher. If the plugin version is higher than the plugin version installed, the user will be notified to update the plugin.
            'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
            'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
            'external_url'       => '', // If set, overrides default API URL and points to an external URL.
            'is_callable'        => '', // If set, this callable will be be checked for availability to determine if a plugin is active.
        ),
        array(
            'name'      => 'Adminbar Link Comments to Pending',
            'slug'      => 'adminbar-link-comments-to-pending',
            'source'    => 'https://github.com/jrfnl/WP-adminbar-comments-to-pending/archive/master.zip',
        ),
        array(
            'name'         => 'TGM New Media Plugin', // The plugin name.
            'slug'         => 'tgm-new-media-plugin', // The plugin slug (typically the folder name).
            'source'       => 'https://s3.amazonaws.com/tgm/tgm-new-media-plugin.zip', // The plugin source.
            'required'     => true, // If false, the plugin is only 'recommended' instead of required.
            'external_url' => 'https://github.com/thomasgriffin/New-Media-Image-Uploader', // If set, overrides default API URL and points to an external URL.
        ),

        // This is an example of how to include a plugin from the WordPress Plugin Repository.
        array(
            'name'      => 'BuddyPress',
            'slug'      => 'buddypress',
            'required'  => true,
        ),

        // This is an example of the use of 'is_callable' functionality. A user could - for instance -
        // have WPSEO installed *or* WPSEO Premium. The slug would in that last case be different, i.e.
        // 'wordpress-seo-premium'.
        // By setting 'is_callable' to either a function from that plugin or a class method
        // `array( 'class', 'method' )` similar to how you hook in to actions and filters, TGMPA can still
        // recognize the plugin as being installed.
        array(
            'name'        => 'WordPress SEO by Yoast',
            'slug'        => 'wordpress-seo',
            'is_callable' => 'wpseo_init',
        ),
        */
        array(
            'name'      => 'ACF Photo Gallery Field',
            'slug'      => 'navz-photo-gallery',
            'required'  => true,
            'force_activation'   => true, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
        ),
        array(
            'name'      => 'Advanced Custom Fields',
            'slug'      => 'advanced-custom-fields',
            'required'  => true,
            'force_activation'   => true,
        )

    );

    /*
     * Array of configuration settings. Amend each line as needed.
     *
     * TGMPA will start providing localized text strings soon. If you already have translations of our standard
     * strings available, please help us make TGMPA even better by giving us access to these translations or by
     * sending in a pull-request with .po file(s) with the translations.
     *
     * Only uncomment the strings in the config array if you want to customize the strings.
     */
    $config = array(
        'id'           => 'orcamento_cw',                 // Unique ID for hashing notices for multiple instances of TGMPA.
        'default_path' => '',                      // Default absolute path to bundled plugins.
        'menu'         => 'tgmpa-install-plugins', // Menu slug.
        'parent_slug'  => 'plugins.php',            // Parent menu slug.
        'capability'   => 'manage_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
        'has_notices'  => true,                    // Show admin notices or not.
        'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
        'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
        'is_automatic' => false,                   // Automatically activate plugins after installation or not.
        'message'      => '',                      // Message to output right before the plugins table.

        
        'strings'      => array(
            'page_title'                      => __( 'Plugins necessários', 'orcamento_cw' ),
            'menu_title'                      => __( 'Instalar plugins', 'orcamento_cw' ),
            /* translators: %s: plugin name. */
            'installing'                      => __( 'Instalando Plugin: %s', 'orcamento_cw' ),
            /* translators: %s: plugin name. */
            'updating'                        => __( 'Atualizando Plugin: %s', 'orcamento_cw' ),
            'oops'                            => __( 'Algo deu errado com a API do plug-in.', 'orcamento_cw' ),
            'notice_can_install_required'     => _n_noop(
                /* translators: 1: plugin name(s). */
                'É necessário os seguintes plugin: %1$s.',
                'É necessário os seguintes plugins: %1$s.',
                'orcamento_cw'
            ),
            'notice_can_install_recommended'  => _n_noop(
                /* translators: 1: plugin name(s). */
                'Este tema recomenda o seguinte plugin: %1$s.',
                'Este tema recomenda os seguintes plugins: %1$s.',
                'orcamento_cw'
            ),
            'notice_ask_to_update'            => _n_noop(
                /* translators: 1: plugin name(s). */
                'O plug-in a seguir precisa ser atualizado para sua versão mais recente para garantir compatibilidade máxima com este tema: %1$s.',
                'Os seguintes plugins precisam ser atualizados para sua versão mais recente para garantir compatibilidade máxima com este tema: %1$s.',
                'orcamento_cw'
            ),
            'notice_ask_to_update_maybe'      => _n_noop(
                /* translators: 1: plugin name(s). */
                'Há uma atualização disponível para: %1$s.',
                'Há atualizações disponíveis para os seguintes plugins: %1$s.',
                'orcamento_cw'
            ),
            'notice_can_activate_required'    => _n_noop(
                /* translators: 1: plugin name(s). */
                'O plug-in requerido a seguir está inativo no momento: %1$s.',
                'Os seguintes plugins necessários estão inativos no momento: %1$s.',
                'orcamento_cw'
            ),
            'notice_can_activate_recommended' => _n_noop(
                /* translators: 1: plugin name(s). */
                'O plugin recomendado a seguir está inativo no momento: %1$s.',
                'Os seguintes plugins recomendados estão inativos no momento: %1$s.',
                'orcamento_cw'
            ),
            'install_link'                    => _n_noop(
                'Comece a instalar o plugin',
                'Comece a instalar os plugins',
                'orcamento_cw'
            ),
            'update_link'                     => _n_noop(
                'Comece a atualizar o plugin',
                'Comece a atualizar os plugins',
                'orcamento_cw'
            ),
            'activate_link'                   => _n_noop(
                'Comece a ativar o plugin',
                'Comece a ativar os plugins',
                'orcamento_cw'
            ),
            'return'                          => __( 'Voltar para instalador de Plugins necessários', 'orcamento_cw' ),
            'plugin_activated'                => __( 'Plugin ativado com sucesso.', 'orcamento_cw' ),
            'activated_successfully'          => __( 'O plugin a seguir foi ativado com sucesso:', 'orcamento_cw' ),
            /* translators: 1: plugin name. */
            'plugin_already_active'           => __( 'NEnhuma ação tomada. O Plugin %1$s já etava ativo.', 'orcamento_cw' ),
            /* translators: 1: plugin name. */
            'plugin_needs_higher_version'     => __( 'Plugin não ativado. Uma versão superior de %s é necessária para este tema. Por favor, atualize o plugin.', 'orcamento_cw' ),
            /* translators: 1: dashboard link. */
            'complete'                        => __( 'Todos os plugins instalados e ativados com sucesso. %1$s', 'orcamento_cw' ),
            'dismiss'                         => __( 'Descartar essa notificação', 'orcamento_cw' ),
            'notice_cannot_install_activate'  => __( 'Há um ou mais plug-ins obrigatórios ou recomendados para instalar, atualizar ou ativar.', 'orcamento_cw' ),
            'contact_admin'                   => __( 'Entre em contato com o administrador deste site para ajuda.', 'orcamento_cw' ),

            'nag_type'                        => '', // Determines admin notice type - can only be one of the typical WP notice classes, such as 'updated', 'update-nag', 'notice-warning', 'notice-info' or 'error'. Some of which may not work as expected in older WP versions.
        ),
        
    );

    tgmpa( $plugins, $config );
}
