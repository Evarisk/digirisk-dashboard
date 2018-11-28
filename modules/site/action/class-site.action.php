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

		$request = wp_remote_post( $api_url, array(
			'method' => 'POST',
			'blocking' => true,
			'headers' => array(
				'Content-Type' => 'application/json',
			),
			'sslverify' > false,
			'body' => json_encode( $data ),
		) );

		if ( ! is_wp_error( $request ) ) {
			if ( $request['response']['code'] == 200 ) {
				$response = json_decode( $request['body'] );

				$site_key = \eoxia\Config_Util::$init['digirisk_dashboard']->site->site_key;
				$sites    = get_option( $site_key, array() );

				$already_exist = false;
				if ( ! empty( $sites ) ) {
					foreach ( $sites as $site ) {
						if ( $data['url'] == $site['url'] ) {
							$already_exist = true;
							break;
						}
					}
				}

				if ( ! $already_exist ) {
					$data_to_hash          = implode( '', $data );
					$string_to_hash        = hash( 'sha256', $data_to_hash );
					$sites[ count( $sites ) + 1 ] = array(
						'title' => $response->title,
						'url'   => $data['url'],
						'hash'  => $string_to_hash,
					);
					update_option( $site_key, $sites );
				}
			}
		}

		wp_send_json_success( array(
			'namespace'        => 'digiriskDashboard',
			'module'           => 'site',
			'callback_success' => 'addedSiteSuccess',
		) );
	}

}

new Class_Site_Action();
