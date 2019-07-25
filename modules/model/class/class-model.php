<?php
/**
 * Gestion des modèles ODT personnalisés.
 *
 * @author    Evarisk <dev@evarisk.com>
 * @copyright (c) 2006 2019 Evarisk <dev@evarisk.com>.
 *
 * @license   AGPLv3 <https://spdx.org/licenses/AGPL-3.0-or-later.html>
 *
 * @package   DigiRisk_Dashboard\Classes
 *
 * @since     0.3.0
 */

namespace digirisk_dashboard;

defined( 'ABSPATH' ) || exit;

/**
 * Gestion des modèles personnalisés
 */
class Model_Class extends \eoxia\Singleton_Util {

	/**
	 * La liste des documents personnalisables avec leur titre
	 *
	 * @var array
	 */
	private $list_type_document = array(
		'duer_mu' => array(
			'title' => 'Document unique Mu',
			'class' => '\digirisk_dashboard\DUER_Class',
		),
	);

	/**
	 * Le constructeur
	 */
	protected function construct() {}

	/**
	 * Appelle la vue main.view.php pour afficher la gestion des modèles personnalisés.
	 *
	 * @since 6.1.0
	 */
	public function display() {
		$list_document_default = array();

		if ( ! empty( $this->list_type_document ) ) {
			foreach ( $this->list_type_document as $key => $element ) {
				$list_document_default[ $key ] = $element['class']::g()->get_default_model( $key );
			}
		}

		\eoxia\View_Util::exec( 'digirisk_dashboard', 'model', 'main', array(
			'list_type_document'    => $this->list_type_document,
			'list_document_default' => $list_document_default,
		) );
	}
}

Model_Class::g();
