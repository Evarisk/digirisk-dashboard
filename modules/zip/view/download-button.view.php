<?php
/**
 * Le bouton "Télécharger le ZIP"
 *
 * @author Evarisk <jimmy@evarisk.com>
 * @since 0.2.0
 * @copyright 2015-2017 Evarisk
 * @package DigiRisk
 */

namespace digirisk_dashboard;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<?php if ( ! empty( $zip_url ) ) : ?>
	<a class="wpeo-button button-purple button-square-50 wpeo-tooltip-event"
		href="<?php echo esc_attr( $zip_url ); ?>"
		aria-label="<?php echo esc_attr_e( 'ZIP', 'digirisk' ); ?>">
		<i class="far fa-file-archive" aria-hidden="true"></i>
	</a>
<?php else : ?>
	<span class="wpeo-button button-grey button-square-50 wpeo-tooltip-event" data-color="red" aria-label="<?php echo esc_attr_e( 'ZIP Corrompu', 'digirisk' ); ?>">
		<i class="far fa-times button-icon" aria-hidden="true"></i>
	</span>
<?php endif; ?>
