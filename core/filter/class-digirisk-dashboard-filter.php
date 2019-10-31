<?php
/**
 * Les filtres principale de l'application.
 *
 * @package DigiRisk_Dashboard
 *
 * @since 0.1.0
 */

namespace digirisk_dashboard;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Appelle la vue permettant d'afficher la navigation
 */
class Class_Digirisk_Dashboard_Filter {

	/**
	 * Le constructeur
	 *
	 * @since 0.1.0
	 */
	public function __construct() {
		add_filter( 'digi_handle_model_actions_end', array( $this, 'callback_digi_handle_model_actions_end' ), 10, 2 );

		add_filter( 'digirisk_dashboard_main_header_ul_after', array( $this, 'add_header_multisite' ) );

		add_filter( 'http_request_timeout', array( $this, 'upgrade_timeout_request' ) );
	}

	/**
	 * Ajoutes le bouton "Appliquer sur tous les sites".
	 *
	 * @since 0.2.0
	 *
	 * @param  string $content le contenu du filtre.
	 * @param  string $key     La clé du modèle.
	 *
	 * @return string          Le contenu du filtre avec le nouveau contenu.
	 */
	public function callback_digi_handle_model_actions_end( $content, $key ) {
		ob_start();
		require( PLUGIN_DIGIRISK_DASHBOARD_PATH . '/core/view/main.view.php' );
		$content .= ob_get_clean();
		return $content;
	}

	public function add_header_multisite( $content ) {
		$current_site = get_blog_details( get_current_blog_id() );

		$sites = get_sites();

		usort( $sites, function( $a, $b ) {
			$al = strtolower($a->blogname);
			$bl = strtolower($b->blogname);

			if ($al == $bl) {
				return 0;
			}
			return ($al > $bl) ? +1 : -1;
		} );

		if ( ! empty( $sites ) ) {
			foreach ( $sites as $key => $site ) {
				if ( ! is_super_admin( get_current_user_id() ) &&
					( $site->blog_id == $current_site->blog_id
						|| empty( get_user_meta( get_current_user_id(), 'wp_' . $site->blog_id . '_user_level', true ) ) ) ) {
					unset( $sites[ $key ] );
				} else {
					$sites[$key]->site_info = get_blog_details( $sites[ $key ]->blog_id );
				}
			}
		}

		ob_start();
		require( PLUGIN_DIGIRISK_DASHBOARD_PATH . '/core/view/header-multisite.view.php' );
		$content .= ob_get_clean();

		return $content;
	}

	public function upgrade_timeout_request( $time ) {
		return 100;
	}
}

new Class_Digirisk_Dashboard_Filter();
