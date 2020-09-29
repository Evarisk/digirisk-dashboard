<?php
/**
 * Les actions des DUER.
 *
 * @package DigiRisk_Dashboard
 *
 * @since 0.2.0
 */

namespace digirisk_dashboard;

use digi\Digirisk;
use eoxia\Config_Util;
use eoxia\Custom_Menu_Handler;
use task_manager\Task_Manager_Class;

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
		Custom_Menu_Handler::register_menu( 'digirisk-dashboard', __( 'Modèles ODT', 'digirisk' ), __( 'Modèles ODT', 'digirisk' ), 'manage_options', 'digirisk-dashboard-model', array( Model_Class::g(), 'display' ), 'fas fa-file-alt' );
		if( isset( Config_Util::$init['task-manager'] ) ){
			Custom_Menu_Handler::register_menu( 'digirisk-dashboard', __( 'Task Manager', 'task-manager' ), __( 'Task Manager', 'task-manager' ), 'manage_task_manager', 'wpeomtm-dashboard', array( Task_Manager_Class::g(), 'display' ),  PLUGIN_TASK_MANAGER_URL . '/core/assets/images/icone-16-16-couleur.png' );
		}
		if( isset( Config_Util::$init['digirisk'] ) ){
			Custom_Menu_Handler::register_menu( 'digirisk-dashboard', __( 'DigiRisk', 'digirisk' ), __( 'DigiRisk', 'digirisk' ), 'read', 'digirisk', array( Digirisk::g(), 'display' ), PLUGIN_DIGIRISK_URL . '/core/assets/images/favicon2.png' );
		}
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
