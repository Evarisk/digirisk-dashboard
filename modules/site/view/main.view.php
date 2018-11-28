<?php
/**
 * Le template principal pour la page "dirigirsk-dashboard"
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

<div class="wpeo-gridlayout grid-4 grid-gap-0 wpeo-form">
	<div class="form-element form-align-horizontal">
		<label class="form-field-container">
			<div class="form-field-inline">
				<input type="text" class="form-field" />
			</div>
			<div class="form-field-inline">
				<div class="wpeo-button button-main">Appliquer</div>
			</div>
		</label>
	</div>

	<div class="form-element form-align-horizontal">
		<label class="form-field-container">
			<div class="form-field-inline">
				<input type="text" class="form-field" />
			</div>
			<div class="form-field-inline">
				<div class="wpeo-button button-main">Filtrer</div>
			</div>
		</label>
	</div>

	<div class="form-element form-align-horizontal">
		<label class="form-field-container">
			<div class="form-field-inline">
				<input type="text" class="form-field" />
			</div>
			<div class="form-field-inline">
				<div class="wpeo-button button-main">Recherche un site</div>
			</div>
		</label>
	</div>
</div>

<table class="wpeo-table">
	<thead>
		<tr>
			<th data-title="checkbox"><input type="checkbox" /></th>
			<th data-title="ID">ID</th>
			<th data-title="Site">Site</th>
			<th data-title="URL">URL</th>
			<th data-title="Dernier DUER">Dernier DUER</th>
			<th data-title="Actions"></th>
		</tr>
	</thead>
	<tbody>
		<?php
		if ( ! empty( $sites ) ) :
			foreach ( $sites as $id => $site ) :
				?>
				<tr>
					<td data-title="checkbox"><input type="checkbox" /></td>
					<td data-title="ID"><?php echo esc_html( $id ); ?></td>
					<td data-title="Site"><?php echo esc_html( $site['title'] ); ?></td>
					<td data-title="URL"><?php echo esc_html( $site['url'] ); ?></td>
					<td data-title="Dernier DUER"></td>
					<td data-title="Actions">
						<div 	class="wpeo-button button-square-50 button-transparent w50 delete action-delete"
						data-id="<?php echo esc_attr( $id ); ?>"
						data-nonce="<?php echo esc_attr( wp_create_nonce( 'ajax_delete_site' ) ); ?>"
						data-message-delete="<?php esc_attr_e( 'Êtes-vous sûr(e) de vouloir supprimer ce site ?', 'digirisk' ); ?>"
						data-action="digi_dashboard_delete_site"><i class="button-icon far fa-times"></i></div>
					</td>
				</tr>
				<?php
			endforeach;
		endif;
		?>
	</tbody>
</table>
