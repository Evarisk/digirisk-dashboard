<?php
/**
 * Gestion des ZIP
 *
 * @author    Evarisk <dev@evarisk.com>
 * @since     6.2.1
 * @version   7.0.0
 * @copyright 2018 Evarisk.
 * @package   DigiRisk
 */

namespace digirisk_dashboard;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Gestion des ZIP
 */
class ZIP_Class extends Document_Class {

	/**
	 * Le nom du modèle
	 *
	 * @var string
	 */
	protected $model_name = '\digirisk_dashboard\ZIP_Model';

	/**
	 * Le type du document
	 *
	 * @var string
	 */
	protected $type = 'zip';

	/**
	 * A faire
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
	protected $base = 'zip';

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
	public $element_prefix = 'ZIP';

	/**
	 * La limite des documents affichés par page
	 *
	 * @var integer
	 */
	protected $limit_document_per_page = 50;

	/**
	 * Le nom pour le resgister post type
	 *
	 * @var string
	 */
	protected $post_type_name = 'ZIP';

	/**
	 * Convertis le chemin absolu vers le fichier en URL
	 *
	 * @since 6.1.9
	 * @version 6.4.4
	 *
	 * @param  string $zip_path Le chemin absolu vers le fichier.
	 *
	 * @return string           L'URL vers le fichier
	 */
	public function get_zip_url( $zip_path ) {
		$upload_dir = wp_upload_dir();

		$basedir = str_replace( '\\', '/', $upload_dir['basedir'] );
		$baseurl = str_replace( '\\', '/', $upload_dir['baseurl'] );
		$url     = str_replace( $basedir, $baseurl, $zip_path );

		if ( ! file_exists( str_replace( '\\', '/', $zip_path ) ) ) {
			$url = '';
		}
		return $url;
	}

	/**
	 * Créé un fichier au format zip a partir d'une liste de fichiers passé en paramètres
	 *
	 * @since 6.1.9
	 * @version 6.5.0
	 *
	 * @param string $path     Le chemin vers lequel il faut sauvegarder le fichier zip.
	 * @param object $element  L'élément auquel il faut associer le fichier zip.
	 *
	 * array['status']  boolean True si tout s'est bien passé, sinon false.
	 * array['message'] string  Le message informatif du résultat de la méthode.
	 *
	 * @return array (Voir au dessus).
	 */
	public function create_zip( $path, $model_site, $filename ) {
		$zip           = new \ZipArchive();
		$files_details = get_option( \eoxia\Config_Util::$init['digirisk_dashboard']->zip->key_temporarly_files_details, array() );
		$response      = array( 'status' => false );

		if ( empty( $files_details ) ) {
			return $response;
		}
		if ( $zip->open( $path, \ZipArchive::CREATE ) !== true ) {
			$response['status']  = false;
			$response['message'] = __( 'An error occured while opening zip file to write', 'digirisk' );
			return $response;
		}

		if ( ! empty( $files_details ) ) {
			foreach ( $files_details as $file_details ) {
				if ( ! empty( $file_details['url'] ) && ! empty( $file_details['filename'] ) ) {
					$donwload_file = file_get_contents( $file_details['url'] );
					$zip->addFromString( $file_details['filename'], $donwload_file );
				}
			}
		}
		$zip->close();

		$document_revision = \eoxia\ODT_Class::g()->get_revision( 'zip', $model_site['id'] );

		// base de données.
		$document_args = array(
			'link'                    => Document_Util_Class::g()->get_digirisk_dashboard_upload_dir() . '/' . $path,
			'title'                   => basename( $filename, '.zip' ),
			'mime_type'               => 'application/zip',
			'parent_id'               => 0,
			'status'                  => 'inherit',
			'model_site_id'           => $model_site['id'],
			'list_generation_results' => $file_details,
		);

		$document = $this->update( $document_args );
		wp_set_object_terms( $document->data['id'], array( 'zip', 'printed' ), $this->attached_taxonomy_type );

		return array(
			'zip_path' => $path,
		);
	}

	/**
	 * Génères un zip et le met dans l'élément.
	 *
	 * @since 6.1.9
	 * @version 6.5.0
	 *
	 * @param Group_Model $element    Les données du groupement.
	 *
	 * @return array
	 */
	public function generate( $model_site ) {
		\eoxia\LOG_Util::log( 'DEBUT - Création ZIP', 'digirisk' );
		$version               = DUER_Class::g()->get_revision( 'zip', $model_site['id'] );
		$filename              = mysql2date( 'Ymd', current_time( 'mysql', 0 ) ) . '_' . $model_site['id'] . '_ZIP_V' . $version . '.zip';
		$zip_path              = Document_Util_Class::g()->get_digirisk_dashboard_upload_dir() . '/' . $filename;
		$zip_generation_result = $this->create_zip( $zip_path, $model_site, $filename );
		\eoxia\LOG_Util::log( 'FIN - Création ZIP', 'digirisk' );

		return array(
			'zip_path'          => $zip_path,
			'creation_response' => $zip_generation_result,
			'success'           => true,
		);
	}

	/**
	 * Supprimes l'option temporaire des fichiers à zipper.
	 *
	 * @since 6.5.0
	 * @version 6.5.0
	 *
	 * @return void
	 */
	public function clear_temporarly_files_details() {
		delete_option( \eoxia\Config_Util::$init['digirisk_dashboard']->zip->key_temporarly_files_details );
	}

	/**
	 * Met dans une meta temporaire les fichiers à zipper.
	 * Cette meta est utilisé et vidé dans la méthode create_zip.
	 *
	 * @since 6.5.0
	 * @version 6.5.0
	 *
	 * array['path']     string Le chemin vers le fichier.
	 * array['filename'] string Le nom du fichier.
	 *
	 * @param array $file_details (Voir au dessus).
	 *
	 * @return void
	 */
	public function update_temporarly_files_details( $file_details ) {
		$files_details = get_option( \eoxia\Config_Util::$init['digirisk_dashboard']->zip->key_temporarly_files_details, array() );

		$files_details[] = $file_details;
		update_option( \eoxia\Config_Util::$init['digirisk_dashboard']->zip->key_temporarly_files_details, $files_details );
	}
}

ZIP_Class::g();
