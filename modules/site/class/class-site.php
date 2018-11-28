<?php
/**
 * La classe des sites.
 *
 * @package DigiRisk_Dashboard
 *
 * @since 0.2.0
 */

namespace digirisk_dashboard;

defined( 'ABSPATH' ) || exit;

/**
 * Class site class.
 */
class Class_Site extends \eoxia\Singleton_Util {

	/**
	 * Le constructeur
	 *
	 * @since 0.1.0
	 */
	protected function construct() {}

	public function display() {
		$sites = get_option( \eoxia\Config_Util::$init['digirisk_dashboard']->site->site_key, array() );

		\eoxia\View_Util::exec( 'digirisk_dashboard', 'site', 'main', array(
			'sites' => $sites,
		) );
	}

	public function display_edit() {
		\eoxia\View_Util::exec( 'digirisk_dashboard', 'site', 'edit/main' );
	}

	public function request() {

	}
}

new Class_Site();
