<?php
/**
 * Formulaire pour ajouter ou éditer un site.
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
				<input type="text" class="form-field" name="url" value="<?php echo ! empty( $edit_site ) ? esc_attr( $edit_site['url'] ) : ''; ?>" />
			</label>
		</div>

		<div class="form-element form-element-required">
			<span class="form-label">Clé unique</span>
			<label class="form-field-container">
				<input type="text" class="form-field" name="unique_key" value="<?php echo ! empty( $edit_site ) ? esc_attr( $edit_site['unique_key'] ) : ''; ?>" />
			</label>
		</div>

		<div class="form-element manage-htpasswd">
			<span class="form-label"><?php esc_html_e( 'Activer Htpasswd', 'digirisk-dashboard' ); ?></span>
			<input type="hidden" name="manage_htpasswd" class="manage-htpasswd" value="<?php echo (int) 1 === (int) ( ! empty( $edit_site ) && $edit_site['manage_htpasswd'] ) ? 'true' : 'false'; ?>" />
			<i style="font-size: 2em;" class="toggle fas fa-toggle-<?php echo (int) 1 === (int) ( ! empty( $edit_site ) && $edit_site['manage_htpasswd'] ) ? 'on' : 'off'; ?>" data-bloc="stock-field" data-input="manage_stock"></i>
		</div>

		<div class="<?php echo (int) 1 === (int) ( ! empty( $edit_site ) && $edit_site['manage_htpasswd'] ) ? '' : 'hidden'; ?> bloc-htpasswd">
			<div class="form-element">
				<span class="form-label">Htpasswd User</span>
				<label class="form-field-container">
					<input type="text" class="form-field" name="auth_user" autocomplete="off" value="<?php echo ! empty( $edit_site ) ? esc_attr( $edit_site['auth_user'] ) : ''; ?>" />
				</label>
			</div>

			<div class="form-element">
				<span class="form-label">Htpasswd Password</span>
				<label class="form-field-container">
					<input type="password" class="form-field" name="auth_password" autocomplete="off" value="<?php echo ! empty( $edit_site ) ? esc_attr( $edit_site['auth_password'] ) : ''; ?>" />
				</label>
			</div>
		</div>


		<?php
		if ( ! empty( $id ) ) :
			?>
			<div class="wpeo-button button-main action-input"
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
