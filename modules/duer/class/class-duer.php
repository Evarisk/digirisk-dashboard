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
class Class_DUER extends Document_Class {

	/**
	 * Le nom du modèle
	 *
	 * @var string
	 */
	protected $model_name = '\digirisk_dashboard\DUER_Model';
	/**
	 * Le type du document
	 *
	 * @var string
	 */
	protected $type = 'duer_mu';
	/**
	 * La taxonomy du post
	 *
	 * @todo
	 * @var string
	 */
	public $attached_taxonomy_type = 'attachment_category';
	/**
	 * La clé principale de l'objet
	 *
	 * @var string
	 */
	protected $meta_key = '_wpdigi_document';
	/**
	 * La base de l'URI pour la Rest API
	 *
	 * @var string
	 */
	protected $base = 'document-unique';
	/**
	 * La version pour la Rest API
	 *
	 * @var string
	 */
	protected $version = '0.1';
	/**
	 * Le préfixe pour le champs "unique_key" de l'objet
	 *
	 * @var string
	 */
	public $element_prefix = 'DU';
	/**
	 * Le nom pour le resgister post type
	 *
	 * @var string
	 */
	protected $post_type_name = 'DUER';
	/**
	 * Le nom de l'ODT sans l'extension; exemple: document_unique
	 *
	 * @var string
	 */
	protected $odt_name = 'document_unique_mu';

	/**
	 * Le constructeur
	 *
	 * @since 0.2.0
	 */
	protected function construct() {}

	public function display() {
		\eoxia\View_Util::exec( 'digirisk_dashboard', 'duer', 'main' );
	}

	public function display_modal() {
		$sites     = get_option( \eoxia\Config_Util::$init['digirisk_dashboard']->site->site_key, array() );
		$societies = array();

		if ( ! empty( $sites ) ) {
			foreach ( $sites as $id => $site ) {
				$api_url = $site['url'] . '/wp-json/digi/v1/duer/tree';

				$request = wp_remote_get( $api_url );

				if ( ! is_wp_error( $request ) ) {
					if ( $request['response']['code'] == 200 ) {
						$response = json_decode( $request['body'] );

						if ( ! empty( $response ) ) {
							foreach ( $response as $element ) {
								$societies[ $id ][] = $element->data;
							}
						}
					}
				}
			}
		}

		echo '<pre>'; print_r( $societies ); echo '</pre>';

		\eoxia\View_Util::exec( 'digirisk_dashboard', 'duer', 'modal-content', array(
			'sites'     => $sites,
			'societies' => $societies,
		) );
	}

	public function generate() {

	}
}

new Class_DUER();
