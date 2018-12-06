<?php
/**
 * Les actions des sites.
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
class Class_Site_Action {

	/**
	 * Le constructeur.
	 *
	 * @since 0.1.0
	 */
	public function __construct() {
		add_action( 'wp_ajax_digi_dashboard_add_site', array( $this, 'ajax_add_site' ) );
		add_action( 'wp_ajax_digi_dashboard_delete_site', array( $this, 'ajax_delete_site' ) );
	}

	public function ajax_add_site() {
		check_ajax_referer( 'ajax_add_site' );

		$url        = ! empty( $_POST['url'] ) ? esc_url_raw( $_POST['url'] ) : '';
		$login      = ! empty( $_POST['login'] ) ? sanitize_user( $_POST['login'] ) : '';
		$unique_key = ! empty( $_POST['unique_key'] ) ? sanitize_text_field( $_POST['unique_key'] ) : '';

		if ( empty( $url ) || empty( $login ) || empty( $unique_key ) ) {
			wp_send_json_error();
		}

		$api_url = $url . '/wp-json/digi/v1/register-site';

		$data = array(
			'url'        => $url,
			'login'      => $login,
			'unique_key' => $unique_key,
		);

		$response = Request_Util::g()->post( $api_url, $data );

		if ( $response ) {
			$site_key = \eoxia\Config_Util::$init['digirisk_dashboard']->site->site_key;
			$sites    = get_option( $site_key, array() );

			$last_id = 0;

			$already_exist = false;
			if ( ! empty( $sites ) ) {
				foreach ( $sites as $id => $site ) {
					if ( $data['url'] == $site['url'] ) {
						$already_exist = true;
					}

					$last_id = $id;
				}
			}

			if ( ! $already_exist ) {
				$data_to_hash          = implode( '', $data );
				$string_to_hash        = hash( 'sha256', $data_to_hash );
				$sites[ $last_id + 1 ] = array(
					'title' => $response->title,
					'url'   => $data['url'],
					'hash'  => $string_to_hash,
				);
				update_option( $site_key, $sites );
			}
		}

		wp_send_json_success( array(
			'namespace'        => 'digiriskDashboard',
			'module'           => 'site',
			'callback_success' => 'addedSiteSuccess',
		) );
	}

	public function ajax_delete_site() {
		$id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;

		if ( empty( $id ) ) {
			wp_send_json_error();
		}

		$site_key = \eoxia\Config_Util::$init['digirisk_dashboard']->site->site_key;
		$sites    = get_option( $site_key, array() );

		if ( ! empty( $sites[ $id ] ) ) {
			unset( $sites[ $id ] );
		}

		update_option( $site_key, $sites );

		wp_send_json_success( array(
			'namespace'        => 'digiriskDashboard',
			'module'           => 'site',
			'callback_success' => 'deletedSiteSuccess',
		) );
	}

}

new Class_Site_Action();
