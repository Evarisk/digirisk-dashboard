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

		add_action( 'network_admin_menu', array( $this, 'callback_network_admin_menu' ) );
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
	 * Initialise le fichier style.min.css du plugin Digirisk-EPI.
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
	 * Initialise le fichier backend.min.js du plugin Digirisk-EPI.
	 *
	 * @return void nothing
	 *
	 * @since 0.1.0
	 * @version 0.1.0
	 */
	public function callback_admin_enqueue_scripts_js() {
		wp_enqueue_script( 'paypal-party-script', PLUGIN_DIGIRISK_DASHBOARD_URL . 'core/assets/js/backend.min.js', array(), Config_Util::$init['digirisk-dashboard']->version, false );
	}

	/**
	 * Initialise le fichier Featured_Content::wp_loaded.min.js du plugin Digirisk-EPI.
	 *
	 * @return void nothing
	 *
	 * @since 0.1.0
	 * @version 0.1.0
	 */
	public function callback_enqueue_scripts_js() {
		if ( !empty( $_GET['order_step'] ) && 6 == $_GET['order_step'] ) {

			wp_enqueue_script( 'paypal-checkout-js', 'https://www.paypalobjects.com/api/checkout.js', array(), Config_Util::$init['digirisk-dashboard']->version, true );

			wp_enqueue_script( 'paypal-party-frontend-script', PLUGIN_DIGIRISK_DASHBOARD_URL . 'core/assets/js/frontend.min.js', array(), Config_Util::$init['digirisk-dashboard']->version, false );
		}
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

	public function callback_network_admin_menu() {}

}

new Class_Digirisk_Dashboard_Action();
