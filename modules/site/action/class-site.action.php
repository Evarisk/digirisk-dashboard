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
		add_action( 'admin_menu', array( $this, 'callback_admin_menu' ) );

		add_action( 'wp_ajax_sites_child_check_statut', array( $this, 'check_statut' ) );

		add_action( 'wp_ajax_digi_dashboard_edit_site', array( $this, 'ajax_edit_site' ) );
		add_action( 'wp_ajax_digi_dashboard_load_edit_site', array( $this, 'ajax_load_edit_site' ) );
		add_action( 'wp_ajax_digi_dashboard_delete_site', array( $this, 'ajax_delete_site' ) );
	}

	/**
	 * Ajoutes le menu DigiRisk Dashboard dans l'administration de WordPress.
	 *
	 * @since 0.2.0
	 */
	public function callback_admin_menu() {
		\eoxia\Custom_Menu_Handler::register_menu( 'digirisk-dashboard', __( 'Ajouter un site', 'digirisk' ), __( 'Ajouter un site', 'digirisk' ), 'manage_options', 'digirisk-dashboard-add', array( Class_Site::g(), 'display_edit' ), 'fas fa-plus' );
	}

	public function check_statut() {
		$id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;

		if ( empty( $id ) ) {
			wp_send_json_error();
		}

		$site_key = \eoxia\Config_Util::$init['digirisk_dashboard']->site->site_key;

		$sites    = get_option( $site_key, array() );

		if ( empty( $sites[ $id ] ) ) {
			wp_send_json_success( array(
				'success' => false,
			) );
		}

		$site = $sites[ $id ];

		$blog_details = get_blog_details( $id );

		if ( $blog_details->archived == true || $blog_details->deleted == true ){
			wp_send_json_success( array(
				'success' => false,
				'error_message' => 'site deleted or archived',
			) );
		}

		$url = $site['url'] . '/wp-json/digi/v1/statut';

		$site['check_connect'] = Request_Util::post( $url, array(), array(
			'auth_user'     => $site['auth_user'],
			'auth_password' => $site['auth_password'],
		), $site['hash'] );

		wp_send_json_success( array(
			'success'       => $site['check_connect']->statut,
			'error_code'    => $site['check_connect']->error_code,
			'error_message' => $site['check_connect']->error_message,
		) );
	}

	/**
	 * Ajoutes un site dans le dashboar
	 *
	 * @since 0.2.0
	 */
	public function ajax_edit_site() {
		check_ajax_referer( 'ajax_edit_site' );

		$site_id         = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;
		$url             = ! empty( $_POST['url'] ) ? esc_url_raw( $_POST['url'] ) : '';
		$unique_key      = ! empty( $_POST['unique_key'] ) ? sanitize_text_field( $_POST['unique_key'] ) : '';
	 	$auth_user       = ! empty( $_POST['auth_user'] ) ? sanitize_text_field( $_POST['auth_user'] ) : '';
		$auth_password   = ! empty( $_POST['auth_password'] ) ? sanitize_text_field( $_POST['auth_password'] ) : '';
		$manage_htpasswd = ( isset( $_POST['manage_htpasswd'] ) && 'true' === $_POST['manage_htpasswd'] ) ? true : false;

		$error_message = '';

		if ( empty( $url ) || empty( $unique_key ) ) {
			$error_message = __( 'Veuillez saisir l\'url du site et la clé unique', 'digirisk-dashboard' );
		}

		$last_id  = 0;
		$site_key = \eoxia\Config_Util::$init['digirisk_dashboard']->site->site_key;
		$sites    = get_option( $site_key, array() );

		if ( empty( $error_message ) ) {
			$already_exist = false;

			if ( ! empty( $sites ) && empty( $site_id ) ) {
				foreach ( $sites as $id => $site ) {
					if ( $url == $site['url'] ) {
						$already_exist = true;
					}

					$last_id = $id;
				}
			}


			$api_url = $url . '/wp-json/digi/v1/register-site';

			$data = array(
				'id'         => ! empty( $site_id ) ? $site_id : $last_id,
				'url'        => $url,
				'url_parent' => get_site_url(),
				'unique_key' => $unique_key,
			);

			$response = Request_Util::g()->post( $api_url, $data,
				array(
					'auth_user'     => $auth_user,
					'auth_password' => $auth_password,
				)
			);


			if ( $response ) {
				if ( ! empty( $response->error_code ) ) {
					if ( 1 === $response->error_code ) {
						$error_message = sprintf( __( 'La clé unique ne correspond pas a l\'url %s', 'digirisk-dashboard' ), $data['url'] );
					} else if ( 2 === $response->error_code ) {
						$error_message = sprintf( __( 'Le site url %s est déjà ajouté', 'digirisk-dashboard' ), $data['url'] );
					}
				} else {
					unset( $data['url_parent'] );
					$data_to_hash = implode( '', $data );
					$hash         = hash( 'sha256', $data_to_hash );

					$tmp = array(
						'title'           => $response->title,
						'url'             => $data['url'],
						'hash'            => $hash,
						'unique_key'      => $unique_key,
						'auth_user'       => $auth_user,
						'auth_password'   => $auth_password,
						'manage_htpasswd' => $manage_htpasswd,
					);

					if ( ! $already_exist && empty( $site_id ) ) {
						$sites[ $last_id + 1 ] = $tmp;
					} else {
						$sites[ $site_id ] = $tmp;
					}

					update_option( $site_key, $sites );
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
				'message'          => sprintf( __( 'Le site %s (%s) à été %s', 'digirisk-dashboard' ), $response->title, $data['url'], ! empty( $site_id ) ? 'modifié' : 'ajouté' ),
			) );
		}
	}

	public function ajax_load_edit_site() {
		$id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;

		if ( empty( $id ) ) {
			wp_send_json_error();
		}

		$sites = get_option( \eoxia\Config_Util::$init['digirisk_dashboard']->site->site_key, array() );
		$site  = $sites[ $id ];

		ob_start();
		\eoxia\View_Util::exec( 'digirisk_dashboard', 'site', 'edit', array(
			'id'        => $id,
			'edit_site' => $site,
		) );
		$view = ob_get_clean();

		wp_send_json_success( array(
			'view'         => $view,
			'buttons_view' => '',
		) );
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

		//$hash    = $site['hash'];
		//$api_url = $site['url'] . '/wp-json/digi/v1/delete-site';

//		$response = Request_Util::g()->post( $api_url, array(), array(
//			'auth_user'     => $site['auth_user'],
//			'auth_password' => $site['auth_password'],
//		), $hash );

			update_option( $site_key, $sites );
//		if ( empty( $response['code_error'] ) ) {
////		}

		wp_send_json_success( array(
			'namespace'        => 'digiriskDashboard',
			'module'           => 'site',
			'callback_success' => 'deletedSiteSuccess',
		) );
	}

}

new Class_Site_Action();
