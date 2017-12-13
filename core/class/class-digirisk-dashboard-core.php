<?php
/**
 * La classe principale de l'application.
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
}

new Class_Digirisk_Dashboard_Core();
