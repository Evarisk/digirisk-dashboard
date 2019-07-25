<?php
/**
 * Le template principal pour ajouter un site
 *
 * @author    Evarisk <dev@evarisk.com>
 * @copyright (c) 2017-2018 Evarisk <dev@evarisk.com>.
 *
 * @license   AGPLv3 <https://spdx.org/licenses/AGPL-3.0-or-later.html>
 *
 * @package   DigiRisk_Dashboard\Templates
 *
 * @since     0.2.0
 */

namespace digirisk_dashboard;

defined( 'ABSPATH' ) || exit; ?>

<div class="edit-site">
	<div class="notice notice-success hidden">
		<p></p>
	</div>

	<div class="notice notice-error hidden">
		<p></p>
	</div>

	<div class="wpeo-form">
		<input type="hidden" name="action" value="digi_dashboard_edit_site" />
		<?php wp_nonce_field( 'ajax_edit_site' ); ?>
		<input type="hidden" name="id" value="<?php echo ! empty( $id ) ? esc_attr( $id ) : 0; ?>" />

		<div class="form-element form-element-required">
			<span class="form-label">URL du site</span>
			<label class="form-field-container">
				<input type="text" class="form-field" name="url" value="<?php echo ! empty( $site ) ? esc_attr( $site['url'] ) : ''; ?>" />
			</label>
		</div>

		<div class="form-element form-element-required">
			<span class="form-label">Clé unique</span>
			<label class="form-field-container">
				<input type="text" class="form-field" name="unique_key" value="<?php echo ! empty( $site ) ? esc_attr( $site['unique_key'] ) : ''; ?>" />
			</label>
		</div>

		<div class="form-element">
			<span class="form-label">Authentification User</span>
			<label class="form-field-container">
				<input type="text" class="form-field" name="auth_user" value="<?php echo ! empty( $site ) ? esc_attr( $site['auth_user'] ) : ''; ?>" />
			</label>
		</div>

		<div class="form-element">
			<span class="form-label">Authentification Password</span>
			<label class="form-field-container">
				<input type="password" class="form-field" name="auth_password" value="<?php echo ! empty( $site ) ? esc_attr( $site['auth_password'] ) : ''; ?>" />
			</label>
		</div>


		<?php
		if ( ! empty( $site ) ) :
			?>
			<div class="wpeo-button button-main button-disable action-input"
				data-parent="wpeo-form">
				<span>Modifier le site</span>
			</div>
			<?php
		else:
			?>
			<div class="wpeo-button button-main button-disable action-input"
				data-parent="wpeo-form">
				<span>Ajouter le site</span>
			</div>
			<?php
		endif;

		?>

	</div>
</div>
