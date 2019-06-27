<?php
/**
 * Les filtres relatives au DUER
 *
 * @author Evarisk <jimmy@evarisk.com>
 * @since 0.2.0
 * @copyright 2015-2017 Evarisk
 * @package DigiRisk
 */

namespace digirisk_dashboard;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Les filtres relatives au DUER
 */
class DUER_Filter extends Identifier_Filter {

	/**
	 * Le constructeur ajoute le filtre society_header_end
	 *
	 * @since 0.2.0
	 */
	public function __construct() {
		parent::__construct();
		add_filter( 'digi_dashboard_duer_mu_document_data', array( $this, 'callback_digi_header' ), 10, 2 );
		add_filter( 'digi_dashboard_duer_mu_document_data', array( $this, 'callback_digi_site' ), 11, 2 );
	}

	/**
	 * Ajoutes toutes les données nécessaire pour le registre des AT bénins.
	 *
	 * @since 0.2.0
	 *
	 * @param  array         $data    Les données pour le registre des AT bénins.
	 * @param  Society_Model $society Les données de la société.
	 *
	 * @return array                  Les données pour le registre des AT bénins modifié.
	 */
	public function callback_digi_header( $data, $args ) {
		$url = $args['model_site']['url'] . '/wp-json/digi/v1/duer/society';
		$response = Request_Util::post( $url, array(), array(
			'auth_user'     => $args['model_site']['auth_user'],
			'auth_password' => $args['model_site']['auth_password'],
		), $args['model_site']['hash'] );

		if ( ! $response ) {
			remove_all_filters( 'digi_dashboard_duer_mu_document_data' );
			return array(
				'status'        => false,
				'error_message' => sprintf( __( 'Erreur lors de la génération du DUER: #%d %s (%s): Le token est invalide.', 'digirisk-dashboard' ), $args['model_site']['id'], $args['model_site']['title'], $args['model_site']['url'] ),
			);
		}

		$data['model_site']         = $args['model_site'];
		$data['nomEntreprise']      = $response->title;
		$data['emetteurDUER']       =  '';
		$data['destinataireDUER']   = $args['destinataire_duer'];
		$data['telephone']          = ! empty( $response->data['contact']['phone'] ) ? end( $response->data['contact']['phone'] ) : '';
		$data['portable']           = '';
		$data['methodologie']       = $args['methodologie'];
		$data['sources']            = $args['sources'];
		$data['remarqueImportante'] = $args['remarque_importante'];
		$data['dispoDesPlans']      = $args['dispo_des_plans'];
		$data['dateGeneration']     = mysql2date( get_option( 'date_format' ), current_time( 'mysql', 0 ), true );
		$data['dateDebutAudit']     = $args['date_debut_audit'];
		$data['dateFinAudit']       = $args['date_fin_audit'];

		$audit_date = '';

		if ( ! empty( $args['date_debut_audit'] ) ) {
			$audit_date .= mysql2date( 'd/m/Y', $args['date_debut_audit'] );
		}
		if ( ! empty( $args['date_fin_audit'] ) && $audit_date != $args['date_fin_audit'] ) {
			if ( ! empty( $audit_date ) ) {
				$audit_date .= ' - ';
			}
			$audit_date .= mysql2date( 'd/m/Y', $args['date_fin_audit'] );
		}

		$data['dateAudit'] = $audit_date;

		return $data;
	}

	public function callback_digi_site( $data, $args ) {
		$level_risk = array( '1', '2', '3', '4' );
		foreach ( $level_risk as $level ) {
			$data[ 'risk' . $level ] = array(
				'type'  => 'segment',
				'value' => array(),
			);
			$data[ 'planDactionRisq' . $level ] = array(
				'type'  => 'segment',
				'value' => array(),
			);
		}

		$data['risqueFiche'] = array(
			'type'  => 'segment',
			'value' => array(),
		);

		$data['sites'] = array(
			'type'  => 'segment',
			'value' => array(),
		);

		$data['sitesComplementaire'] = array(
			'type'  => 'segment',
			'value' => array(),
		);

		$data['quotationsTotal'] = array();

		$data['sites']['value'][] = array(
			'id'    => 'S' . $args['model_site']['id'],
			'url'   => $args['model_site']['url'],
			'titre' => $args['model_site']['title'],
		);

		$args['sites'][] = $args['model_site'];

		if ( ! empty( $args['sites'] ) ) {
			foreach ( $args['sites'] as $site ) {
				if ( $args['model_site']['id'] != $site['id'] ) {
					$data['sites']['value'][] = array(
						'id'    => 'S' . $site['id'],
						'url'   => $site['url'],
						'titre' => $site['title'],
					);

					$url = $site['url'] . '/wp-json/digi/v1/duer/society/tree/' . $site['id'];

					$response              = Request_Util::post( $url, array(), array(
						'auth_user'     => $site['auth_user'],
						'auth_password' => $site['auth_password'],
					), $site['hash'] );
					if ( ! $response ) {
						remove_all_filters( 'digi_dashboard_duer_mu_document_data' );
						\eoxia\LOG_Util::log( sprintf( 'Erreur pour récupérer les sociétées lors de la génération du DUER pour le site enfant: #%d %s (%s): Le token est invalide.', $site['model_site']['id'], $site['model_site']['title'], $site['model_site']['url'] ), 'digirisk-dashboard' );
						return array(
							'status'        => false,
							'error_message' => sprintf( __( 'Erreur pour récupérer les sociétées lors de la génération du DUER pour le site enfant: #%d %s (%s): Le token est invalide.', 'digirisk-dashboard' ), $site['model_site']['id'], $site['model_site']['title'], $site['model_site']['url'] ),
						);
					}

					if ( $response ) {
						$element_per_hierarchy = json_decode( json_encode( $response->elementParHierarchie ), true );

						$data['sitesComplementaire']['value'][] = array(
							'nomEntrepriseComplementaire'        => 'S' . $site['id'],
							'elementParHierarchieComplementaire' => array(
								'type' => 'sub_segment',
								'value' => $element_per_hierarchy['value'],
							)
						);
					}
				}

				$data = $this->callback_digi_risks( $data, $site );
			}
		}

		arsort( $data['quotationsTotal'] );

		if ( ! empty( $data['quotationsTotal'] ) ) {
			foreach ( $data['quotationsTotal'] as $key => $quotationTotal ) {
				$data['risqueFiche']['value'][] = array(
					'nomElement'      => $key,
					'quotationTotale' => $quotationTotal,
				);
			}
		}

		$data['elementParHierarchie'] = array(
			'type'  => 'segment',
			'value' => array(),
		);

		$url = $args['model_site']['url'] . '/wp-json/digi/v1/duer/society/tree/' . $args['model_site']['id'];

		$response = Request_Util::post( $url, array(), array(
			'auth_user'     => $args['model_site']['auth_user'],
			'auth_password' => $args['model_site']['auth_password'],
		), $args['model_site']['hash'] );
		if ( ! $response ) {
			remove_all_filters( 'digi_dashboard_duer_mu_document_data' );
			\eoxia\LOG_Util::log( sprintf( 'Erreur pour récupérer les sociétées lors de la génération du DUER pour le site générique: #%d %s (%s): Le token est invalide.', $args['model_site']['id'], $args['model_site']['title'], $args['model_site']['url'] ), 'digirisk-dashboard' );
			return array(
				'status'        => false,
				'error_message' => sprintf( __( 'Erreur pour récupérer les sociétées lors de la génération du DUER pour le site générique: #%d %s (%s): Le token est invalide.', 'digirisk-dashboard' ), $args['model_site']['id'], $args['model_site']['title'], $args['model_site']['url'] ),
			);
		}

		if ( $response ) {
			// $element_per_hierarchy = json_decode( json_encode( $response->elementParHierarchie ), true );
			// $data['elementParHierarchie']['value'] = $element_per_hierarchy['value'];
		}

		foreach ( $level_risk as $level ) {
			usort( $data[ 'planDactionRisq' . $level ]['value'], function( $a, $b ) {
				if ( $a['quotationRisque'] == $b['quotationRisque'] ) {
					return 0;
				}

				return ( $a['quotationRisque'] > $b['quotationRisque'] ) ? -1 : 1;
			} );
		};

		return $data;
	}

	public function callback_digi_risks( $data, $site ) {
		$quotationsTotal = array();

		$url = $site['url'] . '/wp-json/digi/v1/duer/risk/' . $site['id'];

		$response = Request_Util::post( $url, array(), array(
			'auth_user'     => $site['auth_user'],
			'auth_password' => $site['auth_password'],
		), $site['hash'] );

		if ( $response === false ) {
			remove_all_filters( 'digi_dashboard_duer_mu_document_data' );
			\eoxia\LOG_Util::log( sprintf( 'Erreur pour récupérer les risques lors de la génération du DUER pour le site enfant: #%d %s (%s): Le token est invalide.', $site['id'], $site['title'], $site['url'] ), 'digirisk-dashboard' );
			return array(
				'status'        => false,
				'error_message' => sprintf( __( 'Erreur pour récupérer les risques lors de la génération du DUER pour le site enfant: #%d %s (%s): Le token est invalide.', 'digirisk-dashboard' ), $site['id'], $site['title'], $site['url'] ),
			);
		}

		if ( $response ) {
			$risks = json_decode( json_encode( $response ), true );

			if ( ! empty( $risks ) ) {
				foreach ( $risks as $risk ) {
					// $data[ 'risk' . $risk['scale'] ]['value'][]            = $risk;
					$data[ 'planDactionRisq' . $risk['scale'] ]['value'][] = $risk;

					if ( empty( $quotationsTotal[ $risk['nomElement'] ] ) ) {
						$quotationsTotal[ $risk['nomElement'] ] = 0;
					}
					$quotationsTotal[ $risk['nomElement'] ] += $risk['quotationRisque'];
				}
			}

			$data['quotationsTotal'] = array_merge( $data['quotationsTotal'], $quotationsTotal );
		}

		return $data;
	}
}

new DUER_Filter();
