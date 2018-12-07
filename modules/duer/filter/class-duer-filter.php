<?php
/**
 * Les filtres relatives au DUER
 *
 * @author Evarisk <jimmy@evarisk.com>
 * @since 6.2.5
 * @version 6.4.4
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
	 * @since 6.2.5
	 */
	public function __construct() {
		parent::__construct();
		add_filter( 'digi_dashboard_duer_mu_document_data', array( $this, 'callback_digi_document_data' ), 10, 2 );
	}

	/**
	 * Ajoutes toutes les données nécessaire pour le registre des AT bénins.
	 *
	 * @since 7.0.0
	 *
	 * @param  array         $data    Les données pour le registre des AT bénins.
	 * @param  Society_Model $society Les données de la société.
	 *
	 * @return array                  Les données pour le registre des AT bénins modifié.
	 */
	public function callback_digi_document_data( $data, $args ) {
		$quotationsTotal = array();

		$url = $args['model_site']['url'] . '/wp-json/digi/v1/duer/society';

		$response = Request_Util::get( $url, $args['model_site']['hash'] );

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

		$data['sites'] = array(
			'type'  => 'segment',
			'value' => array(),
		);

		$data['sites']['value'][] = array(
			'id'    => $args['model_site']['id'],
			'url'   => $args['model_site']['url'],
			'titre' => $args['model_site']['title'],
		);

		$data['sitesComplementaire'] = array(
			'type'  => 'segment',
			'value' => array(),
		);

		// Test
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

		$args['sites'][] = $args['model_site'];

		if ( ! empty( $args['sites'] ) ) {
			foreach ( $args['sites'] as $site ) {
				if ( $args['model_site']['id'] != $site['id'] ) {
					$data['sites']['value'][] = array(
						'id'    => $site['id'],
						'url'   => $site['url'],
						'titre' => $site['title'],
					);

					$url = $site['url'] . '/wp-json/digi/v1/duer/society/tree/' . $site['id'];

					$response = Request_Util::get( $url, $site['hash'] );
					$element_per_hierarchy = json_decode( json_encode( $response->elementParHierarchie ), true );

					$data['sitesComplementaire']['value'][] = array(
						'nomEntrepriseComplementaire'        => 'D' . $site['id'],
						'elementParHierarchieComplementaire' => array(
							'type' => 'sub_segment',
							'value' => $element_per_hierarchy['value'],
						)
					);
				}

				$url = $site['url'] . '/wp-json/digi/v1/duer/risk/' . $site['id'];

				$response = Request_Util::get( $url, $site['hash'] );
				$risks = json_decode( json_encode( $response ), true );

				if ( ! empty( $risks ) ) {
					foreach ( $risks as $risk ) {
						$data[ 'risk' . $risk['scale'] ]['value'][]            = $risk;
						$data[ 'planDactionRisq' . $risk['scale'] ]['value'][] = $risk;

						if ( empty( $quotationsTotal[ $risk['nomElement'] ] ) ) {
							$quotationsTotal[ $risk['nomElement'] ] = 0;
						}
						$quotationsTotal[ $risk['nomElement'] ] += $risk['quotationRisque'];
					}
				}
			}
		}

		$data['elementParHierarchie'] = array(
			'type'  => 'segment',
			'value' => array(),
		);

		$url = $args['model_site']['url'] . '/wp-json/digi/v1/duer/society/tree/' . $args['model_site']['id'];

		$response = Request_Util::get( $url, $args['model_site']['hash'] );
		$element_per_hierarchy = json_decode( json_encode( $response->elementParHierarchie ), true );
		$data['elementParHierarchie']['value'] = $element_per_hierarchy['value'];

		if ( count( $quotationsTotal ) > 1 ) {
			uasort( $quotationsTotal, function( $a, $b ) {
				if( $a == $b ) {
					return 0;
				}
				return ( $a > $b ) ? -1 : 1;
			} );
		}

		$data['risqueFiche'] = array(
			'type'  => 'segment',
			'value' => array(),
		);

		if ( ! empty( $quotationsTotal ) ) {
			foreach ( $quotationsTotal as $key => $quotationTotal ) {
				$data['risqueFiche']['value'][] = array(
					'nomElement'      => $key,
					'quotationTotale' => $quotationTotal,
				);
			}
		}

		return $data;
	}
}

new DUER_Filter();
