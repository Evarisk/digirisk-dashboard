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
	 * Le constructeur
	 *
	 * @since 0.2.0
	 */
	protected function construct() {}

	public function display() {
		$sites     = get_option( \eoxia\Config_Util::$init['digirisk_dashboard']->site->site_key, array() );
		$duers     = $this->get();
		$new_duer  = $this->get( array( 'schema' => true ), true );

		\eoxia\View_Util::exec( 'digirisk_dashboard', 'duer', 'main', array(
			'sites'    => $sites,
			'duers'    => $duers,
			'new_duer' => $new_duer,
		) );
	}

	public function display_modal() {
		$sites     = get_option( \eoxia\Config_Util::$init['digirisk_dashboard']->site->site_key, array() );

		\eoxia\View_Util::exec( 'digirisk_dashboard', 'duer', 'modal-content', array(
			'sites'     => $sites,
		) );
	}
}

new DUER_Class();
