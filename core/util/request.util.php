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
class Request_Util extends \eoxia\Singleton_Util {

	/**
	 * Le constructeur
	 *
	 * @since 0.1.0
	 * @version 0.1.0
	 */
	protected function construct() {}

	public static function post( $api_url, $body, $hash = '' ) {
		$data = array();

		if ( ! empty( $hash ) ) {
			$data['token'] = $site['hash'];
		}

		$data = array_merge( $data, $body );

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
				return $response;
			} else {
				return $request;
			}
		}

		return false;
	}

	public static function get( $api_url, $hash ) {
		$api_url . '?token=' . $hash;

		$request = wp_remote_get( $api_url );

		if ( ! is_wp_error( $request ) ) {
			if ( $request['response']['code'] == 200 ) {
				$response = json_decode( $request['body'] );
				return $response;
			}
		}

		return false;
	}
}

new Request_Util();
