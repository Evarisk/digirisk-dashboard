<?php
/**
 * Les actions des DUER.
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
class Class_DUER_Action {

	/**
	 * Le constructeur.
	 * Alors, comment te dire que c'est pas le bon commentaire hein !
	 *
	 * @since 0.1.0
	 */
	public function __construct() {
		add_action( 'wp_ajax_digi_dashboard_load_modal_generate_duer', array( $this, 'callback_load_modal_generate_duer' ) );
		add_action( 'wp_ajax_digi_dashboard_load_modal_duer_site', array( $this, 'callback_load_modal_duer_site' ) );
		add_action( 'wp_ajax_digi_dashboard_generate', array( $this, 'ajax_generate' ) );
	}

	/**
	 * Prépares l'arbre d'affichage pour générer le DUER et les sites enfants.
	 *
	 * @since 0.2.0
	 *
	 * @return void
	 */
	public function callback_load_modal_generate_duer() {
		$model_site_id       = ! empty( $_POST['model_site_id'] ) ? (int) $_POST['model_site_id'] : 0;
		$sites_id            = ! empty( $_POST['sites_id'] ) ? (array) $_POST['sites_id'] : array();
		$date_debut_audit    = ! empty( $_POST['dateDebutAudit'] ) ? sanitize_text_field( wp_unslash( $_POST['dateDebutAudit'] ) ) : ''; // WPCS: input var ok.
		$date_fin_audit      = ! empty( $_POST['dateFinAudit'] ) ? sanitize_text_field( wp_unslash( $_POST['dateFinAudit'] ) ) : ''; // WPCS: input var ok.
		$destinataire_duer   = ! empty( $_POST['destinataireDUER'] ) ? sanitize_textarea_field( wp_unslash( $_POST['destinataireDUER'] ) ) : ''; // WPCS: input var ok.
		$methodologie        = ! empty( $_POST['methodologie'] ) ? sanitize_textarea_field( wp_unslash( $_POST['methodologie'] ) ) : ''; // WPCS: input var ok.
		$sources             = ! empty( $_POST['sources'] ) ? sanitize_textarea_field( wp_unslash( $_POST['sources'] ) ) : ''; // WPCS: input var ok.
		$dispo_des_plans     = ! empty( $_POST['dispoDesPlans'] ) ? sanitize_textarea_field( wp_unslash( $_POST['dispoDesPlans'] ) ) : ''; // WPCS: input var ok.
		$remarque_importante = ! empty( $_POST['remarqueImportante'] ) ? sanitize_textarea_field( wp_unslash( $_POST['remarqueImportante'] ) ) : ''; // WPCS: input var ok.

		if ( empty( $model_site_id ) ) {
			wp_send_json_success( array(
				'namespace'        => 'digiriskDashboard',
				'module'           => 'duer',
				'callback_success' => 'loadedModalGenerateDuerSuccess',
				'error_code'       => 1,
			) );
		}

		if ( ! empty( $sites_id ) ) {
			foreach ( $sites_id as $id => $value ) {
				if ( $value == 'true' ) {
					$sites_checked_id[] = $id;
				}
			}
		}

		$sites                             = get_option( \eoxia\Config_Util::$init['digirisk_dashboard']->site->site_key, array() );
		$available_sites                   = array();
		$model_site                        = $sites[ $model_site_id ];
		$available_sites[ $model_site_id ] = $model_site;


		if ( ! empty( $sites_checked_id ) ) {
			foreach ( $sites_checked_id as $id ) {
				$available_sites[ $id ] = $sites[ $id ];
			}
		}

		ZIP_Class::g()->clear_temporarly_files_details();

		ob_start();
		\eoxia\View_Util::exec( 'digirisk_dashboard', 'duer', 'modal-generate-duer', array(
			'sites' => $available_sites,
		) );
		$view = ob_get_clean();

		wp_send_json_success( array(
			'namespace'        => 'digiriskDashboard',
			'module'           => 'duer',
			'callback_success' => 'loadedModalGenerateDuerSuccess',
			'view'             => $view,
			'args'             => array(
				'model_site_id'      => $model_site_id,
				'sites_id'           => ! empty( $sites_checked_id ) ? implode( ',', $sites_checked_id ) : array(),
				'dateDebutAudit'     => $date_debut_audit,
				'dateFinAudit'       => $date_fin_audit,
				'destinataireDUER'   => $destinataire_duer,
				'methodologie'       => $methodologie,
				'sources'            => $sources,
				'dispoDesPlans'      => $dispo_des_plans,
				'remarqueImportante' => $remarque_importante,
			),
		) );
	}

	/**
	 * La modal qui affiches les sites selectionné lors de la génération.
	 *
	 * @since 0.2.0
	 */
	public function callback_load_modal_duer_site() {
		$id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;

		if ( empty( $id ) ) {
			wp_send_json_error();
		}

		$duer  = DUER_Class::g()->get( array( 'id' => $id ), true );

		if ( empty( $duer->data['sites'][0]['id'] ) ) {
			$duer->data['sites'] = array();
		}

		ob_start();
		\eoxia\View_Util::exec( 'digirisk_dashboard', 'duer', 'item-modal-sites', array(
			'duer' => $duer,
		) );
		$view = ob_get_clean();
		wp_send_json_success( array(
			'view' => $view,
		) );
	}

	/**
	 * Généres le DUER, le ZIP et les sites enfants.
	 *
	 * @since 0.2.0
	 */
	public function ajax_generate() {
		$id                  = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;
		$type                = ! empty( $_POST['type'] ) ? sanitize_text_field( $_POST['type'] ) : '';
		$duer_id             = ! empty( $_POST['args']['duer_id'] ) ? (int) $_POST['args']['duer_id'] : 0;
		$model_site_id       = ! empty( $_POST['args']['model_site_id'] ) ? (int) $_POST['args']['model_site_id'] : 0;
		$date_debut_audit    = ! empty( $_POST['args']['dateDebutAudit'] ) ? sanitize_text_field( wp_unslash( $_POST['args']['dateDebutAudit'] ) ) : ''; // WPCS: input var ok.
		$date_fin_audit      = ! empty( $_POST['args']['dateFinAudit'] ) ? sanitize_text_field( wp_unslash( $_POST['args']['dateFinAudit'] ) ) : ''; // WPCS: input var ok.
		$destinataire_duer   = ! empty( $_POST['args']['destinataireDUER'] ) ? sanitize_textarea_field( wp_unslash( $_POST['args']['destinataireDUER'] ) ) : ''; // WPCS: input var ok.
		$methodologie        = ! empty( $_POST['args']['methodologie'] ) ? sanitize_textarea_field( wp_unslash( $_POST['args']['methodologie'] ) ) : ''; // WPCS: input var ok.
		$sources             = ! empty( $_POST['args']['sources'] ) ? sanitize_textarea_field( wp_unslash( $_POST['args']['sources'] ) ) : ''; // WPCS: input var ok.
		$dispo_des_plans     = ! empty( $_POST['args']['dispoDesPlans'] ) ? sanitize_textarea_field( wp_unslash( $_POST['args']['dispoDesPlans'] ) ) : ''; // WPCS: input var ok.
		$remarque_importante = ! empty( $_POST['args']['remarqueImportante'] ) ? sanitize_textarea_field( wp_unslash( $_POST['args']['remarqueImportante'] ) ) : ''; // WPCS: input var ok.
		$sites_id            = ! empty( $_POST['args']['sites_id'] ) ? sanitize_text_field( $_POST['args']['sites_id'] ) : null;
		$sites_id            = ! empty( $sites_id ) ? explode( ',', $sites_id ) : null;

		$sites      = get_option( \eoxia\Config_Util::$init['digirisk_dashboard']->site->site_key, array() );

		if ( ! empty( $id ) ) {

			if ( empty( $sites[ $id ] ) ) {
				wp_send_json_error();
			}

			$site = $sites[ $id ];


			$url = $site['url'] . '/wp-json/digi/v1/duer/generate';

			$response = Request_Util::post( $url, array(), array(
				'auth_user'     => $site['auth_user'],
				'auth_password' => $site['auth_password'],
			), $site['hash'] );

			if ( $response ) {
				foreach ( $response as $file ) {
					ZIP_Class::g()->update_temporarly_files_details( array(
						'filename'      => $file->title . '.odt',
						'url'           => $file->link,
						'auth_user'     => $site['auth_user'],
						'auth_password' => $site['auth_password'],
					) );
				}
			} else {
				\eoxia\LOG_Util::log( sprintf( 'Erreur lors de la génération des documents du site enfant: #%d %s (%s): Le token est invalide.', $id, $site['title'], $site['url'] ), 'digirisk-dashboard' );
				// Log erreur lors de la génération
				wp_send_json_success( array(
					'namespace'        => 'digiriskDashboard',
					'module'           => 'duer',
					'callback_success' => 'generatedError',
					'error_site'       => $id,
					'error_message'    => sprintf( __( 'Erreur lors de la génération des documents du site enfant: #%d %s (%s): Le token est invalide.', 'digirisk-dashboard' ), $id, $site['title'], $site['url'] ),
				) );
			}
		}

		if ( ! empty( $type ) ) {
			$model_site = null;

			if ( ! empty( $sites[ $model_site_id ] ) ) {
				$sites[ $model_site_id ]['id'] = $model_site_id;
				$model_site = $sites[ $model_site_id ];
			}

			if ( 'construct-duer-mu' === $type ) {

				$sites_data = array();

				if ( ! empty( $sites_id ) ) {
					foreach ( $sites_id as $site_id ) {
						$site_id                 = (int) $site_id;
						$sites[ $site_id ]['id'] = $site_id;
						$sites_data[]  = $sites[ $site_id ];
					}
				}

				$data_document = array(
					'parent_id'           => 0,
					'date_debut_audit'    => $date_debut_audit,
					'date_fin_audit'      => $date_fin_audit,
					'destinataire_duer'   => $destinataire_duer,
					'methodologie'        => $methodologie,
					'sources'             => $sources,
					'dispo_des_plans'     => $dispo_des_plans,
					'remarque_importante' => $remarque_importante,
					'model_site'          => $model_site,
					'sites'               => $sites_data,
				);

				$data = DUER_Class::g()->prepare_document( null, $data_document );

				if ( isset( $data['status'] ) && ! $data['status'] ) {
					wp_send_json_success( array(
						'namespace'        => 'digiriskDashboard',
						'module'           => 'duer',
						'callback_success' => 'generatedError',
						'error_message'    => $data['error_message'],
					) );
				}

				$duer_id = $data['document']->data['id'];
				$duer = DUER_Class::g()->get( array( 'id' => $duer_id ), true );

				$duer->data['model_site']    = $model_site;
				$duer->data['sites']         = null;

				if ( ! empty( $sites_id ) ) {
					$duer->data['sites'] = $sites_data;
				}
				$duer->data['model_site_id'] = $model_site_id;
				DUER_Class::g()->update( $duer->data );
			} else if ( 'duer-mu' === $type ) {
				$generation_status = DUER_Class::g()->create_document( $duer_id );
				ZIP_Class::g()->update_temporarly_files_details( array(
					'filename'      => $generation_status['document']->data['title'] . '.odt',
					'url'           => $generation_status['document']->data['link'],
					'auth_user'     => '',
					'auth_password' => '',
				) );
			} else if ( 'zip' === $type ) {
				$generation_status = ZIP_Class::g()->generate( $model_site );

				$duer = DUER_Class::g()->get( array( 'id' => $duer_id ), true );
				$duer->data['zip_path'] = $generation_status['zip_path'];
				DUER_Class::g()->update( $duer->data );
			}
		}

		wp_send_json_success( array(
			'namespace'        => 'digiriskDashboard',
			'module'           => 'duer',
			'callback_success' => 'generatedSuccess',
			'args' => array(
				'duer_id'            => ! empty( $duer_id ) ? $duer_id : 0,
				'dateDebutAudit'     => $date_debut_audit,
				'dateFinAudit'       => $date_fin_audit,
				'destinataireDUER'   => $destinataire_duer,
				'methodologie'       => $methodologie,
				'sources'            => $sources,
				'dispoDesPlans'      => $dispo_des_plans,
				'remarqueImportante' => $remarque_importante,
				'model_site_id'      => $model_site_id,
				'sites_id'           => ! empty( $sites_id ) ? implode( ',', $sites_id ) : array(),
			)
		) );
	}
}

new Class_DUER_Action();
