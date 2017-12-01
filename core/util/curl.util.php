<?php
/**
 * Gestion CURL
 *
 * @package Evarisk\Plugin
 */

namespace digirisk_dashboard;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Gestion des tableaux
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
 * @version 1.0.0.0
 */
class CURL_Util {
	private $ch;
	private $url;
	private $data;
	private $string_data;
	private $response;

	public function __construct( $url, $data ) {
		$this->data = $data;
		$this->url = $url;
		$this->string_data = '';

		$this->ch = curl_init( $this->url );
		curl_setopt( $this->ch, CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $this->ch, CURLOPT_SSL_VERIFYHOST, false );
		curl_setopt( $this->ch, CURLOPT_POST, true );
		curl_setopt( $this->ch, CURLOPT_HTTPGET, false );
	}

	private function prepare_data() {
		foreach ( $this->data as $key => $value ) {
			$this->string_data .= $key . '=' . urlencode( $value ) . '&';
		}

		rtrim( $this->string_data, '&' );
	}

	public function add_data( $data ) {
		$this->data = array_merge( $this->data, $data );
	}

	public function exec() {
		$this->prepare_data();

		curl_setopt( $this->ch, CURLOPT_POSTFIELDS, $this->string_data );

		ob_start();
		curl_exec( $this->ch );
		$result = ob_get_clean();

		$result = explode( '&', $result );

		$this->response = array();
		foreach ( $result as $param_paypal ) {
			list( $name, $value ) = explode( "=", $param_paypal );

			$this->response[ $name ] = urldecode( $value );
		}

		return $this->response;
	}
}
