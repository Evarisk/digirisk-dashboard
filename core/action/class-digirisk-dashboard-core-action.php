<?php
/**
 * Initialise les actions princiaples de DigiRisk Dashboard
 *
 * @package DigiRisk_Dashboard
 *
 * @since 0.1.0
 * @version 0.1.0
 */

namespace digirisk_dashboard;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Initialise les actions princiaples de Digirisk EPI
 */
class Class_Digirisk_Dashboard_Action {
	/**
	 * Le constructeur ajoutes les actions WordPress suivantes:
	 * admin_enqueue_scripts (Pour appeller les scripts JS et CSS dans l'admin)
	 * admin_print_scripts (Pour appeler les scripts JS en bas du footer)
	 * plugins_loaded (Pour appeler le domaine de traduction)
	 *
	 * @since 0.1.0
	 * @version 0.1.0
	 */
	public function __construct() {
		// Initialises ses actions que si nous sommes sur une des pages réglés dans le fichier digirisk.config.json dans la clé "insert_scripts_pages".
		$page = ( ! empty( $_REQUEST['page'] ) ) ? sanitize_text_field( $_REQUEST['page'] ) : ''; // WPCS: CSRF ok.

		if ( in_array( $page, \eoxia\Config_Util::$init['digirisk_dashboard']->insert_scripts_pages_css, true ) ) {
			add_action( 'admin_enqueue_scripts', array( $this, 'callback_before_admin_enqueue_scripts_css' ), 10 );
			add_action( 'admin_enqueue_scripts', array( $this, 'callback_admin_enqueue_scripts_css' ), 11 );
		}

		if ( in_array( $page, \eoxia\Config_Util::$init['digirisk_dashboard']->insert_scripts_pages_js, true ) ) {
			add_action( 'admin_enqueue_scripts', array( $this, 'callback_before_admin_enqueue_scripts_js' ), 10 );
			add_action( 'admin_enqueue_scripts', array( $this, 'callback_admin_enqueue_scripts_js' ), 11 );
			add_action( 'admin_print_scripts', array( $this, 'callback_admin_print_scripts_js' ) );
		}

		add_action( 'wp_enqueue_scripts', array( $this, 'callback_enqueue_scripts_js' ) );
		add_action( 'init', array( $this, 'callback_plugins_loaded' ) );

		add_action( 'wp_ajax_apply_to_all', array( $this, 'callback_apply_to_all' ) );
	}

	/**
	 * Initialise les fichiers CSS inclus dans WordPress (jQuery, wp.media et thickbox)
	 *
	 * @return void nothing
	 *
	 * @since 0.1.0
	 * @version 0.1.0
	 */
	public function callback_before_admin_enqueue_scripts_css() {}

	/**
	 * Initialise le fichier style.min.css du plugin Digirisk-Dashboard.
	 *
	 * @return void nothing
	 *
	 * @since 0.1.0
	 * @version 0.1.0
	 */
	public function callback_admin_enqueue_scripts_css() {}

	/**
	 * Initialise les fichiers JS inclus dans WordPress (jQuery, wp.media et thickbox)
	 *
	 * @return void nothing
	 *
	 * @since 0.1.0
	 * @version 0.1.0
	 */
	public function callback_before_admin_enqueue_scripts_js() {
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery-form' );
		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_media();
	}

	/**
	 * Initialise le fichier backend.min.js du plugin Digirisk-Dashboard.
	 *
	 * @return void nothing
	 *
	 * @since 0.1.0
	 * @version 0.1.0
	 */
	public function callback_admin_enqueue_scripts_js() {
		wp_enqueue_script( 'digirisk-dashboard-script', PLUGIN_DIGIRISK_DASHBOARD_URL . 'core/assets/js/backend.min.js', array(), \eoxia\Config_Util::$init['digirisk_dashboard']->version, false );
	}

	/**
	 * Initialise le fichier Featured_Content::wp_loaded.min.js du plugin Digirisk-Dashboard.
	 *
	 * @return void nothing
	 *
	 * @since 0.1.0
	 * @version 0.1.0
	 */
	public function callback_enqueue_scripts_js() {
	}

	/**
	 * Initialise en php le fichier permettant la traduction des variables string JavaScript.
	 *
	 * @return void nothing
	 *
	 * @since 0.1.0
	 * @version 0.1.0
	 */
	public function callback_admin_print_scripts_js() {}

	/**
	 * Initialise le fichier MO du plugin
	 *
	 * @since 0.1.0
	 * @version 0.1.0
	 */
	public function callback_plugins_loaded() {}

	/**
	 * Appliques le modèle sur tous les sites de MU.
	 *
	 * @since 0.1.0
	 * @version 0.1.0
	 *
	 * @return void
	 */
	public function callback_apply_to_all() {
		check_ajax_referer( 'apply_to_all' );

		$type            = ! empty( $_POST['type'] ) ? sanitize_text_field( $_POST['type'] ) : '';
		$current_blog_id = get_current_blog_id();
		$model           = \digi\Document_Class::g()->get_model_for_element( array( $type ) );
		$file_path       = str_replace( '\\', '/', get_attached_file( $model['model_id'] ) );

		$sites = get_sites();
		if ( ! empty( $sites ) ) {
			foreach ( $sites as $site ) {
				if ( (int) $site->blog_id !== (int) $current_blog_id ) {
					switch_to_blog( $site->blog_id );
					\digi\Handle_Model_Class::g()->upload_model( $type, $file_path );
				}
			}

			restore_current_blog();
		}

		wp_send_json_success( array(
			'namespace'        => 'digiriskDashboard',
			'module'           => 'core',
			'callback_success' => 'appliedToAll',
		) );
	}

}

new Class_Digirisk_Dashboard_Action();
