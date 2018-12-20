<?php
/**
 * Le template principal pour générer un DUER
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

<div class="table-row">
	<div class="table-cell table-50"></div>
	<div class="table-cell table-150">
		<div class="group-date form-element">
			<input type="hidden" class="mysql-date" name="dateDebutAudit" value="<?php echo esc_attr( current_time( 'mysql' ) ); ?>" />
			<input type="text" class="date" value="<?php echo esc_attr( current_time( 'd/m/Y' ) ); ?>" />
		</div>
	</div>
	<div class="table-cell table-150">
		<div class="group-date form-element">
			<input type="hidden" class="mysql-date" name="dateFinAudit" value="<?php echo esc_attr( current_time( 'mysql' ) ); ?>" />
			<input type="text" class="date" value="<?php echo esc_attr( current_time( 'd/m/Y' ) ); ?>" />
		</div>
	</div>

	<div class="table-cell table-100">
		<textarea class="hidden textarea-content-destinataire-duer" name="destinataireDUER"><?php echo esc_html( $element->data['document_meta']['destinataireDUER'] ); ?></textarea>

		<span data-parent="tab-content"
				data-target="duer-modal"
				data-title="Édition du destinataire"
				data-src="destinataire-duer"
				class="wpeo-modal-event wpeo-button-pulse span-content-destinataire-duer">

			<i class="button-icon fas fa-user"></i>
			<span class="button-float-icon animated"><i class="fas fa-pencil"></i></span>
		</span>
	</div>

	<div class="table-cell table-100">
		<textarea class="hidden textarea-content-methodology" name="methodologie"><?php echo esc_html( $element->data['document_meta']['methodologie'] ); ?></textarea>
		<span data-parent="tab-content"
					data-target="duer-modal"
					data-title="Édition de la méthodologie"
					data-src="methodology"
					class="wpeo-modal-event wpeo-button-pulse span-content-methodology">

			<i class="button-icon fas fa-search"></i>
			<span class="button-float-icon animated"><i class="fas fa-pencil"></i></span>
		</span>
	</div>

	<div class="table-cell table-100">
		<textarea class="hidden textarea-content-sources" name="sources"><?php echo esc_html( $element->data['document_meta']['sources'] ); ?></textarea>
		<span data-parent="tab-content"
					data-target="duer-modal"
					data-title="Édition de la source"
					data-src="sources"
					class="wpeo-modal-event wpeo-button-pulse span-content-sources">

			<i class="button-icon fas fa-link"></i>
			<span class="button-float-icon animated"><i class="fas fa-pencil"></i></span>
		</span>
	</div>

	<div class="table-cell table-100">
		<textarea class="hidden textarea-content-dispo-des-plans" name="dispoDesPlans"><?php echo esc_html( $element->data['document_meta']['dispoDesPlans'] ); ?></textarea>
		<span data-parent="tab-content"
					data-target="duer-modal"
					data-title="Édition de la localisation"
					data-src="dispo-des-plans"
					class="wpeo-modal-event wpeo-button-pulse span-content-dispo-des-plans">

			<i class="button-icon fas fa-map-marker-alt"></i>
			<span class="button-float-icon animated"><i class="fas fa-pencil"></i></span>
		</span>
	</div>

	<div class="table-cell table-100">
		<textarea class="hidden textarea-content-notes-importantes" name="remarqueImportante"><?php echo esc_html( $element->data['document_meta']['remarqueImportante'] ); ?></textarea>
		<span data-parent="tab-content"
					data-target="duer-modal"
					data-title="Édition de la note importante"
					data-src="notes-importantes"
					class="wpeo-modal-event wpeo-button-pulse span-content-notes-importantes">

			<i class="button-icon fas fa-file"></i>
			<span class="button-float-icon animated"><i class="fas fa-pencil"></i></span>
		</span>
	</div>

	<div class="table-cell">
		<span data-parent="tab-content"
					data-target="duer-modal-site"
					data-title="Selectionner les sites concernés"
					class="wpeo-modal-event wpeo-button-pulse">
			<i class="button-icon fas fa-sitemap"></i>
			<span class="button-float-icon animated"><i class="fas fa-pencil"></i></span>
		</span>
		<?php \eoxia\View_Util::exec( 'digirisk_dashboard', 'duer', 'edit-modal-sites', array(
			'sites' => $sites,
		) ); ?>
	</div>

	<div class="table-cell table-100 table-padding-0">
		<div class="action wpeo-gridlayout grid-2 grid-gap-0">
			<div></div>
			<div class="action-input add wpeo-button button-blue button-square-50"
					data-parent="table-row"
					data-action="digi_dashboard_load_modal_generate_duer">
					<i class="button-icon far fa-plus"></i>
			</div>

			<?php \eoxia\View_Util::exec( 'digirisk_dashboard', 'duer', 'modal-generate-duer' ); ?>
		</div>
	</div>
</div>
