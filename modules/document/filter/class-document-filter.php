<?php
/**
 * Classe gérant les fitres principaux des documents.
 *
 * Génères le nom du fichier.
 * Génères la version du fichier.
 * Gestion de l'ajout du mime type.

 * @author    Evarisk <dev@evarisk.com>
 * @copyright (c) 2006-2018 Evarisk <dev@evarisk.com>.
 *
 * @license   AGPLv3 <https://spdx.org/licenses/AGPL-3.0-or-later.html>
 *
 * @package   DigiRisk\Classes
 *
 * @since     7.0.0
 */

namespace digirisk_dashboard;

defined( 'ABSPATH' ) || exit;

/**
 * Document util class.
 */
class Document_Filter extends \eoxia\Singleton_Util {

	/**
	 * Constructeur.
	 *
	 * @since 7.0.0
	 */
	protected function construct() {
		add_filter( 'eo_model_duer_mu_before_post', array( $this, 'before_save_doc' ), 10, 2 );
	}

	/**
	 * Ajoutes le titre du document ainsi que le GUID et le chemin vers celui-ci.
	 *
	 * Cette méthode est appelée avant l'ajout du document en base de donnée.
	 *
	 * @since 7.0.0
	 *
	 * @param  array $data Les données du document.
	 * @param  array $args Les données de la requête.
	 *
	 * @return mixed
	 */
	public function before_save_doc( $data, $args ) {

		$upload_dir = wp_upload_dir();

		$data['title']  = current_time( 'Ymd' ) . '_';
		$data['title'] .= 'D' . $data['document_meta']['model_site']['id'] . '_' . sanitize_title( $data['type'] ) . '_';
		$data['title'] .= sanitize_title(  $data['document_meta']['model_site']['title'] ) . '_';
		$data['title'] .= 'V' . DUER_Class::g()->get_revision( $data['type'], $data['document_meta']['model_site']['id'] );
		$data['title']  = str_replace( '-', '_', $data['title'] );

		$data['guid'] = $upload_dir['baseurl'] . '/digirisk-dashboard/' . sanitize_title( $data['title'] ) . '.odt';
		$data['path'] = $upload_dir['basedir'] . '/digirisk-dashboard/' . sanitize_title( $data['title'] ) . '.odt';
		$data['path'] = str_replace( '\\', '/', $data['path'] );

		$data['_wp_attached_file'] = '/digirisk-dashboard/' . sanitize_title( $data['title'] ) . '.odt';

		return $data;
	}
}

Document_Filter::g();
