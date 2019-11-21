<?php
/**
 * Les actions des DUER.
 *
 * @package DigiRisk_Dashboard
 *
 * @since 0.2.0
 */

namespace digirisk_dashboard;

defined( 'ABSPATH' ) || exit;

/**
 * Initialise les actions princiaples de Digirisk EPI
 */
class Class_Model_Action {

	/**
	 * Le constructeur.
	 * Alors, comment te dire que c'est pas le bon commentaire hein !
	 *
	 * @since 0.1.0
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_menu' ) );
		add_action( 'digirisk_set_model', array( $this, 'set_model' ), 10, 3 );
	}

	public function add_menu() {
		\eoxia\Custom_Menu_Handler::register_menu( 'digirisk-dashboard', __( 'Modèles ODT', 'digirisk' ), __( 'Modèles ODT', 'digirisk' ), 'manage_options', 'digirisk-dashboard-model', array( Model_Class::g(), 'display' ), 'fas fa-file-alt' );
	}

	public function set_model( $type, $file_id, $file_path ) {
		if ( 'duer_mu' === $type ) {
			ob_start();
			Model_Class::g()->display();
			wp_send_json_success( array(
				'view' => ob_get_clean(),
				'dashboard' => true,
			) );
		}
	}

}

new Class_Model_Action();
