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

	/**
	 * Ajoutes un site dans le dashboar
	 *
	 * @since 0.2.0
	 */
	public function ajax_add_site() {
		check_ajax_referer( 'ajax_add_site' );

		$url           = ! empty( $_POST['url'] ) ? esc_url_raw( $_POST['url'] ) : '';
		$unique_key    = ! empty( $_POST['unique_key'] ) ? sanitize_text_field( $_POST['unique_key'] ) : '';
	 	$auth_user     = ! empty( $_POST['auth_user'] ) ? sanitize_text_field( $_POST['auth_user'] ) : '';
		$auth_password = ! empty( $_POST['auth_password'] ) ? sanitize_text_field( $_POST['auth_password'] ) : '';

		$error_message = '';

		if ( empty( $url ) || empty( $unique_key ) ) {
			$error_message = __( 'Veuillez saisir l\'url du site et la clé unique', 'digirisk-dashboard' );
		}

		$last_id  = 0;
		$site_key = \eoxia\Config_Util::$init['digirisk_dashboard']->site->site_key;
		$sites    = get_option( $site_key, array() );

		if ( empty( $error_message ) ) {
			$already_exist = false;
			if ( ! empty( $sites ) ) {
				foreach ( $sites as $id => $site ) {
					if ( $data['url'] == $site['url'] ) {
						$already_exist = true;
					}

					$last_id = $id;
				}
			}

			$api_url = $url . '/wp-json/digi/v1/register-site';

			$data = array(
				'url'        => $url,
				'url_parent' => get_site_url(),
				'unique_key' => $unique_key,
			);

			$response = Request_Util::g()->post( $api_url, $data, array(
				'auth_user'     => $auth_user,
				'auth_password' => $auth_password,
			) );

			if ( $response ) {
				if ( ! empty( $response->error_code ) ) {
					if ( 1 === $response->error_code ) {
						$error_message = sprintf( __( 'La clé unique ne correspond pas a l\'url %s', 'digirisk-dashboard' ), $data['url'] );
					} else if ( 2 === $response->error_code ) {
						$error_message = sprintf( __( 'Le site url %s est déjà ajouté', 'digirisk-dashboard' ), $data['url'] );
					}
				} else {
					if ( ! $already_exist ) {
						unset( $data['url_parent'] );
						$data_to_hash          = implode( '', $data );
						$string_to_hash        = hash( 'sha256', $data_to_hash );
						$sites[ $last_id + 1 ] = array(
							'title'         => $response->title,
							'url'           => $data['url'],
							'hash'          => $string_to_hash,
							'auth_user'     => $auth_user,
							'auth_password' => $auth_password,
						);
						update_option( $site_key, $sites );
					}
				}
			} else {
				$error_message = sprintf( __( 'L\'url %s est inaccessible', 'digirisk-dashboard' ), $data['url'] );
			}
		}

		if ( ! empty( $error_message ) ) {
			wp_send_json_success( array(
				'namespace'        => 'digiriskDashboard',
				'module'           => 'site',
				'callback_success' => 'addedSiteError',
				'message'          => $error_message,
			) );
		} else {
			wp_send_json_success( array(
				'namespace'        => 'digiriskDashboard',
				'module'           => 'site',
				'callback_success' => 'addedSiteSuccess',
				'message'          => sprintf( __( 'Le site %s (%s) à été ajouté', 'digirisk-dashboard' ), $response->title, $data['url'] ),
			) );
		}
	}

	/**
	 * Supprimes un site du dashboard
	 *
	 * @since 0.2.0
	 */
	public function ajax_delete_site() {
		$id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;

		if ( empty( $id ) ) {
			wp_send_json_error();
		}

		$site_key = \eoxia\Config_Util::$init['digirisk_dashboard']->site->site_key;
		$sites    = get_option( $site_key, array() );

		$site = ! empty( $sites[ $id ] ) ? $sites[ $id ] : null;

		if ( ! empty( $site ) ) {
			unset( $sites[ $id ] );
		}

		$hash    = $site['hash'];
		$api_url = $site['url'] . '/wp-json/digi/v1/delete-site';

		$response = Request_Util::g()->post( $api_url, array(), array(
			'auth_user'     => $site['auth_user'],
			'auth_password' => $site['auth_password'],
		), $hash );

		if ( empty( $response['code_error'] ) ) {
			update_option( $site_key, $sites );
		}

		wp_send_json_success( array(
			'namespace'        => 'digiriskDashboard',
			'module'           => 'site',
			'callback_success' => 'deletedSiteSuccess',
		) );
	}

}

new Class_Site_Action();
