<?php
/**
 * Les filtres principale de l'application.
 *
 * @package DigiRisk_Dashboard
 *
 * @since 0.1.0
 */

namespace digirisk_dashboard;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Appelle la vue permettant d'afficher la navigation
 */
class Class_Digirisk_Dashboard_Filter {

	/**
	 * Le constructeur
	 *
	 * @since 0.1.0
	 */
	public function __construct() {
		add_filter( 'digi_handle_model_actions_end', array( $this, 'callback_digi_handle_model_actions_end' ), 10, 2 );
	}

	/**
	 * Ajoutes le bouton "Appliquer sur tous les sites".
	 *
	 * @since 0.2.0
	 *
	 * @param  string $content le contenu du filtre.
	 * @param  string $key     La clé du modèle.
	 *
	 * @return string          Le contenu du filtre avec le nouveau contenu.
	 */
	public function callback_digi_handle_model_actions_end( $content, $key ) {
		ob_start();
		require( PLUGIN_DIGIRISK_DASHBOARD_PATH . '/core/view/main.view.php' );
		$content .= ob_get_clean();
		return $content;
	}

}

new Class_Digirisk_Dashboard_Filter();
