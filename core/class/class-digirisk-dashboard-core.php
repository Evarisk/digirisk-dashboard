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
		if ( is_multisite() ) {
			$version = (int) str_replace( '.', '', \eoxia\Config_Util::$init['digirisk']->version );
			if ( 3 === strlen( $version ) ) {
				$version *= 10;
			}
			
			$sites = get_sites();

			if ( ! empty( $sites ) ) {
				foreach ( $sites as $site ) {
					switch_to_blog( $site->blog_id );
					
					$digirisk_core = get_option( \eoxia\Config_Util::$init['digirisk']->core_option );
					$last_update_version = get_option( '_digirisk_last_update_version', true );

					if ( (int) $version > (int) $last_update_version && ! empty( $digirisk_core['installed'] ) ) {
						delete_option( \eoxia\Config_Util::$init['digirisk']->key_waiting_updates );
						$url = admin_url( 'admin.php?page=digirisk-update' );
						break;
					}
				}

				restore_current_blog();
			}
		}
		
		echo '<a href="' . $url . '">Lancer la MAJ</a>';
	}
}

new Class_Digirisk_Dashboard_Core();
