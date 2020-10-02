<?php
/**
 * Initialise les actions princiaples de DigiRisk Dashboard
 *
 * @package DigiRisk_Dashboard
 *
 * @since 0.1.0
 */

namespace digirisk_dashboard;

use digi\Digirisk;
use eoxia\Custom_Menu_Handler;
use eoxia\Custom_Menu_Handler as CMH;

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
		add_action( 'admin_menu', array( $this, 'callback_admin_menu' ) );
		add_action( 'network_admin_menu', array( $this, 'callback_network_admin_menu' ), 99 );

		add_action( 'wp_ajax_digi_dashboard_load_tab', array( $this, 'callback_load_tab' ) );
		add_action( 'wp_ajax_apply_to_all', array( $this, 'callback_apply_to_all' ) );
	}

	/**
	 * Initialise les fichiers CSS inclus dans WordPress (jQuery, wp.media et thickbox)
	 *
	 * @since 0.1.0
	 */
	public function callback_before_admin_enqueue_scripts_css() {}

	/**
	 * Initialise le fichier style.min.css du plugin Digirisk-Dashboard.
	 *
	 * @since 0.1.0
	 */
	public function callback_admin_enqueue_scripts_css() {
		wp_enqueue_style( 'digi-style', PLUGIN_DIGIRISK_URL . 'core/assets/css/style.css', array(), \eoxia\Config_Util::$init['digirisk']->version );
		wp_enqueue_style( 'digirisk-dashboard-style', PLUGIN_DIGIRISK_DASHBOARD_URL . 'core/assets/css/style.min.css', array(), \eoxia\Config_Util::$init['digirisk_dashboard']->version );
	}

	/**
	 * Initialise les fichiers JS inclus dans WordPress (jQuery, wp.media et thickbox)
	 *
	 * @since 0.1.0
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
	 * @since 0.1.0
	 */
	public function callback_admin_enqueue_scripts_js() {
		wp_enqueue_script( 'digi-script', PLUGIN_DIGIRISK_URL . 'core/assets/js/backend.min.js', array(), \eoxia\Config_Util::$init['digirisk']->version, false );
		wp_enqueue_script( 'digi-script-owl-carousel', PLUGIN_DIGIRISK_URL . 'core/assets/js/owl.carousel.min.js', array(), \eoxia\Config_Util::$init['digirisk']->version, false );
		wp_enqueue_script( 'digi-autosize-script', PLUGIN_DIGIRISK_URL . 'core/assets/js/autosize.min.js', array(), \eoxia\Config_Util::$init['digirisk']->version, false );


		wp_enqueue_script( 'digirisk-dashboard-script', PLUGIN_DIGIRISK_DASHBOARD_URL . 'core/assets/js/backend.min.js', array(), \eoxia\Config_Util::$init['digirisk_dashboard']->version, false );
	}

	/**
	 * Initialise le fichier Featured_Content::wp_loaded.min.js du plugin Digirisk-Dashboard.
	 *
	 * @since 0.1.0
	 */
	public function callback_enqueue_scripts_js() {}

	/**
	 * Initialise en php le fichier permettant la traduction des variables string JavaScript.
	 *
	 * @since 0.1.0
	 */
	public function callback_admin_print_scripts_js() {}

	/**
	 * Initialise le fichier MO du plugin
	 *
	 * @since 0.1.0
	 */
	public function callback_plugins_loaded() {}

	/**
	 * Ajoutes le menu DigiRisk Dashboard dans l'administration de WordPress.
	 *
	 * @since 0.2.0
	 */
	public function callback_admin_menu() {
		Custom_Menu_Handler::register_container( 'DigiRisk Dashboard', 'DigiRisk Dashboard', 'manage_options', 'digirisk-dashboard', '', PLUGIN_DIGIRISK_DASHBOARD_URL . '/core/assets/images/sitemap.png', null );
		CMH::add_logo( 'digirisk-dashboard', PLUGIN_DIGIRISK_DASHBOARD_URL . '/core/assets/images/favicon_hd.png', admin_url( 'admin.php?page=digirisk-dashboard' ) );

		Custom_Menu_Handler::register_menu( "digirisk-dashboard", "Mes sites", "Mes sites", "manage_options", "digirisk-dashboard", array( Class_Digirisk_Dashboard_Core::g(), 'display_page' ), 'fa fa-sitemap' );
		Custom_Menu_Handler::register_others_menu( 'others', 'digirisk', __( 'DigiRisk Dashboard', 'digirisk' ), __( 'DigiRisk Dashboard', 'digirisk' ), 'read', 'digirisk-dashboard', array( Class_Digirisk_Dashboard_Core::g(), 'display_page' ), 'fa fa-sitemap', 'bottom' );
	}

	/**
	 * Ajoutes la page "Mêttre à jour DigiRisk sur le réseau".
	 *
	 * @since 0.2.0
	 */
	public function callback_network_admin_menu() {
		add_submenu_page( 'index.php', __( 'Mêttre à jour le réseau DigiRisk', 'digirisk-dashboard' ), __( 'Mêttre à jour le réseau DigiRisk', 'digirisk-dashboard' ), 'manage_digirisk', 'upgrade-digirisk', array( Class_Digirisk_Dashboard_Core::g(), 'display_network' ) );
	}

	/**
	 * Gestion des onglets en AJAX.
	 *
	 * @since 0.2.0
	 */
	public function callback_load_tab() {
		$type = ! empty( $_POST['type'] ) ? sanitize_text_field( $_POST['type'] ) : '';
		$view = '';

		ob_start();
		switch ( $type ) {
			case 'sites':
				Class_Site::g()->display();
				break;
			case 'add-site':
				Class_Site::g()->display_edit();
				break;
			case 'duer':
				DUER_Class::g()->display();
				break;
			case 'model':
				Model_Class::g()->display();
				break;
			default:
				Class_Site::g()->display();
				break;
		}

		$view = ob_get_clean();

		wp_send_json_success( array(
			'view' => $view,
		) );
	}

	/**
	 * Appliques le modèle sur tous les sites de MU.
	 *
	 * @since 0.1.0
	 */
	public function callback_apply_to_all() {
		check_ajax_referer( 'apply_to_all' );

		$type            = ! empty( $_POST['type'] ) ? sanitize_text_field( $_POST['type'] ) : '';
		$current_blog_id = get_current_blog_id();
		$model           = \eoxia\ODT_Class::g()->get_default_model( $type );
		$file_path       = str_replace( '\\', '/', get_attached_file( $model['id'] ) );

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
