<?php
/**
 * La classe principale de l'application.
 *
 * @package paypal-party
 *
 * @since 1.0.0.0
 * @version 1.0.0.0
 */

namespace digirisk_dashboard;

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Appelle la vue permettant d'afficher la navigation
 */
class Class_Digirisk_Dashboard_Core extends Singleton_Util {

	/**
	 * Le constructeur
	 *
	 * @since 1.0.0.0
	 * @version 1.0.0.0
	 */
	protected function construct() {}

	/**
	 * La méthode qui permet d'afficher la page
	 *
	 * @return void
	 *
	 * @since 1.0.0.0
	 * @version 1.0.0.0
	 */
	public function display() {
		require( PLUGIN_DIGIRISK_DASHBOARD_PATH . '/core/view/main.view.php' );
	}
}

new Class_Digirisk_Dashboard_Core();
