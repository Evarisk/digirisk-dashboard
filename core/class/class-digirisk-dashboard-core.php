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

	public $menu = array();

	/**
	 * Le constructeur
	 *
	 * @since 0.1.0
	 */
	protected function construct() {
		$menu_def = array(
			'digirisk-dashboard-sites' => array(
				'link'  => admin_url( 'admin.php?page=digirisk-dashboard-sites' ),
				'title' => __( 'Sites', 'digirisk' ),
				'class' => '',
			),
			'digirisk-dashboard-add' => array(
				'link'  => admin_url( 'admin.php?page=digirisk-dashboard-add' ),
				'title' => __( 'Add Site', 'digirisk' ),
				'class' => '',
			),
			'digirisk-dashboard-duer' => array(
				'link'  => admin_url( 'admin.php?page=digirisk-dashboard-duer' ),
				'title' => __( 'DUER', 'digirisk' ),
				'class' => '',
			),
			'digirisk-dashboard-model' => array(
				'link'  => admin_url( 'admin.php?page=digirisk-dashboard-model' ),
				'title' => __( 'ODT Model', 'digirisk' ),
				'class' => '',
			),
		);

		$this->menu = apply_filters( 'digi_nav_items', $menu_def );

		$menu_bottom_def = array(
			'digirisk' => array(
				'link'  => admin_url( 'admin.php?page=digirisk-welcome' ),
				'title' => __( 'Go to DigiRisk', 'digirisk' ),
				'class' => 'item-bottom',
				'right' => '',
			),
			'back-to-wp' => array(
				'link'  => admin_url( 'index.php' ),
				'title' => __( 'Go to WP Admin', 'digirisk' ),
				'class' => 'item-bottom',
				'right' => '',
			),
		);

		$this->menu_bottom = apply_filters( 'digi_nav_items_bottom', $menu_bottom_def );

	}

	/**
	 * Affichage de la page du menu "Digirisk Dashboard".
	 *
	 * @since 0.2.0
	 */
	public function display_page() {
		require_once \eoxia\Config_Util::$init['digirisk_dashboard']->core->path . 'view/main-navigation.view.php';
		require_once \eoxia\Config_Util::$init['digirisk_dashboard']->core->path . 'view/main-page.view.php';
	}

	/**
	 * Affiches la vue pour mêttre à jour les données de DigiRisk dans le réseau.
	 *
	 * @since 0.1.0
	 */
	public function display_network() {
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
