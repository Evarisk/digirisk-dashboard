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
		<input type="hidden" name="action" value="digi_dashboard_add_site" />
		<?php wp_nonce_field( 'ajax_add_site' ); ?>

		<div class="form-element form-element-required">
			<span class="form-label">URL du site </span>
			<label class="form-field-container">
				<input type="text" class="form-field" name="url" />
			</label>
		</div>

		<div class="form-element form-element-required">
			<span class="form-label">Clé unique</span>
			<label class="form-field-container">
				<input type="text" class="form-field" name="unique_key" />
			</label>
		</div>

		<div class="wpeo-button button-main button-disable action-input"
			data-parent="wpeo-form">
			<span>Ajouter le site</span>
		</div>
	</div>
</div>
