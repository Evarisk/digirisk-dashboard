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
class DUER_Class extends Document_Class {

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
	 * Affiches les DUER
	 *
	 * @since 0.2.0
	 */
	public function display() {
		\eoxia\View_Util::exec( 'digirisk_dashboard', 'duer', 'main' );
	}

	public function display_table() {
		$sites     = get_option( \eoxia\Config_Util::$init['digirisk_dashboard']->site->site_key, array() );
		$duers     = $this->get();
		$new_duer  = $this->get( array( 'schema' => true ), true );

		if ( ! empty( $sites ) ) {
			foreach ( $sites as &$site ) {
				$url = $site['url'] . '/wp-json/digi/v1/statut';

				$site['check_connect'] = Request_Util::post( $url, array(), array(
					'auth_user'     => $site['auth_user'],
					'auth_password' => $site['auth_password'],
				), $site['hash'] );
			}
		}

		unset( $site );

		\eoxia\View_Util::exec( 'digirisk_dashboard', 'duer', 'table', array(
			'child_sites' => $sites,
			'duers'       => $duers,
			'new_duer'    => $new_duer,
		) );
	}

	/**
	 * Affiches la modal pour sélectionner les sites.
	 *
	 * @since 0.2.0
	 */
	public function display_modal() {
		$sites = get_option( \eoxia\Config_Util::$init['digirisk_dashboard']->site->site_key, array() );

		\eoxia\View_Util::exec( 'digirisk_dashboard', 'duer', 'modal-content', array(
			'sites' => $sites,
		) );
	}

	/**
	 * Récupération de la prochaine version pour un type de document pour le jour J
	 *
	 * @since 0.2.0
	 *
	 * @param string  $type       Le type de document actuellement en cours de création.
	 * @param integer $element_id L'ID de l'élément.
	 *
	 * @return integer            La version +1 du document actuellement en cours de création.
	 */
	public function get_revision( $type, $element_id ) {
		global $wpdb;

		// Récupération de la date courante.
		$today = getdate();

		// Définition des paramètres de la requête de récupération des documents du type donné pour la date actuelle.
		$args = array(
			'count'          => true,
			'posts_per_page' => -1,
			'meta_key'       => '_model_site_id',
			'meta_value'     => $element_id,
			'post_type'      => $type,
			'post_status'    => array( 'publish', 'inherit' ),
			'date_query' => array(
				array(
					'year'  => $today['year'],
					'month' => $today['mon'],
					'day'   => $today['mday'],
				),
			),
		);

		$document_revision = new \WP_Query( $args );
		return ( $document_revision->post_count + 1 );
	}
}

DUER_Class::g();
