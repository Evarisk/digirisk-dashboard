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

		<div class="form-element">
			<span class="form-label">Authentification User</span>
			<label class="form-field-container">
				<input type="text" class="form-field" name="auth_user" value="<?php echo ! empty( $edit_site ) ? esc_attr( $edit_site['auth_user'] ) : ''; ?>" />
			</label>
		</div>

		<div class="form-element">
			<span class="form-label">Authentification Password</span>
			<label class="form-field-container">
				<input type="password" class="form-field" name="auth_password" value="<?php echo ! empty( $edit_site ) ? esc_attr( $edit_site['auth_password'] ) : ''; ?>" />
			</label>
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
