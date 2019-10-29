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

<div class="content-wrap">
	<?php require PLUGIN_DIGIRISK_DASHBOARD_PATH . '/core/view/main-header.view.php'; ?>

	<div class="wrap wpeo-wrap digirisk-wrap">
		<div id="duer">
			<div class="wpeo-table table-flex">
				<div class="table-row table-header">
					<div class="table-cell table-50" data-title="Ref">Ref</div>
					<div class="table-cell table-150" data-title="Début">Début</div>
					<div class="table-cell table-150" data-title="Fin">Fin</div>
					<div class="table-cell table-100" data-title="Destinataire">Destinataire</div>
					<div class="table-cell table-100" data-title="Méthodologie">Méthodologie</div>
					<div class="table-cell table-100" data-title="Sources">Sources</div>
					<div class="table-cell table-100" data-title="Localisation">Localisation</div>
					<div class="table-cell table-100" data-title="Notes">Notes</div>
					<div class="table-cell" data-title="Sites">Sites</div>
					<div class="table-cell table-100 table-end" data-title="Actions"></div>
				</div>
				<?php
				if ( ! empty( $duers ) ) :
					foreach ( $duers as $duer ) :
						\eoxia\View_Util::exec( 'digirisk_dashboard', 'duer', 'item', array(
							'duer'        => $duer,
							'child_sites' => $child_sites,
						) );
					endforeach;
				endif;

				\eoxia\View_Util::exec( 'digirisk_dashboard', 'duer', 'edit', array(
					'element'     => $new_duer,
					'child_sites' => $child_sites,
				) );
				?>
			</div>

			<?php \eoxia\View_Util::exec( 'digirisk_dashboard', 'duer', 'edit-modal' ); ?>
		</div>
	</div>
</div>
