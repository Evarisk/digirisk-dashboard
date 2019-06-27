<?php
/**
 * La vue principale de la page "EPI"
 *
 * @author Jimmy Latour <jimmy@evarisk.com>
 * @since 0.1.0
 * @copyright 2017 Evarisk
 * @package DigiRisk_Dashboard
 */

namespace digirisk_dashboard;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<li>
	<a href="#" class="action-attribute"
		data-action="apply_to_all"
		data-nonce="<?php echo esc_attr( wp_create_nonce( 'apply_to_all' ) ); ?>"
		data-type="<?php echo esc_attr( $key ); ?>"
		data-confirm="<?php echo esc_attr( 'Êtes vous sur de vouloir appliquer ce modèle sur tous vos sites ?', 'digirisk' ); ?>">
		<span class="dashicons dashicons-networking"></span>
		<?php esc_html_e( 'Appliquer ce modèle sur tous les sites', 'digirisk' ); ?>
	</a>
</li>
