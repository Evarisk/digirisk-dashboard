<?php
/**
 * La classe principale de l'application.
 *
 * @package DigiRisk_Dashboard
 *
 * @since 0.1.0
 * @version 0.2.0
 */

namespace digirisk_dashboard;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Appelle la vue permettant d'afficher la navigation
 */
class Class_Digirisk_Dashboard_Core extends \eoxia\Singleton_Util {

	/**
	 * Le constructeur
	 *
	 * @since 0.1.0
	 * @version 0.1.0
	 */
	protected function construct() {}

		/**
		 * Affiches la vue /core/view/upgrade.view.php
		 *
		 * @since 0.1.0
		 * @version 0.1.0
		 *
		 * @return void
		 */

	/**
	 * Affiches la vue pour mêttre à jour les données de DigiRisk dans le réseau.
	 *
	 * @since 0.2.0
	 * @version 0.2.0
	 *
	 * @return void
	 */
	public function display() {
		$done = ( ! empty( $_GET['done'] ) && 'true' == $_GET['done'] ) ? true : false;

		require_once PLUGIN_DIGIRISK_DASHBOARD_PATH . '/core/view/upgrade.view.php';
	}
}

new Class_Digirisk_Dashboard_Core();
