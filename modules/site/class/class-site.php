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
		global $wpdb;
		
		if ( ! empty( $sites ) ) {
			foreach( $sites as $id => &$site ) {
				$site['last_duer'] = DUER_Class::g()->get( array(
					'meta_key'       => '_model_site_id',
					'meta_value'     => $id,
					'posts_per_page' => 1,
				), true );

				$parse_url = parse_url( $site['url'] );

				$test = $parse_url['path'];

				$results = $wpdb->query(
					$wpdb->prepare(
						'SELECT PM.post_id FROM {$wpdb->postmeta} AS PM
					JOIN {$wpdb->posts} AS P ON P.ID=PM.post_id
				WHERE PM.meta_key="_wpdigi_unique_identifier"
					AND PM.meta_value LIKE %s
					AND P.post_type IN(%s)', array( '%' . $term . '%', implode( $posts_type, ',' ) )
					)
				);

				echo '<pre>';
				print_r($parse_url);
				echo '</pre>';
				exit;
			}
		}

		unset( $site );

		\eoxia\View_Util::exec( 'digirisk_dashboard', 'site', 'main', array(
			'childs_site' => $sites,
		) );
	}

	/**
	 * Affiches le formulaire pour ajouter un site.
	 *
	 * @since 0.2.0
	 */
	public function display_edit() {
		$site = array(
			'title'         => '',
			'url'           => '',
			'unique_key'    => '',
			'auth_user'     => '',
			'auth_password' => '',
		);

		\eoxia\View_Util::exec( 'digirisk_dashboard', 'site', 'main-edit', array(
			'edit_site' => $site,
		) );
	}
}

Class_Site::g();
