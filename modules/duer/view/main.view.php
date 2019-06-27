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
					'duer'  => $duer,
					'sites' => $sites,
				) );
			endforeach;
		endif;

		\eoxia\View_Util::exec( 'digirisk_dashboard', 'duer', 'edit', array(
			'element' => $new_duer,
			'sites'   => $sites,
		) );
		?>
	</table>

	<?php \eoxia\View_Util::exec( 'digirisk_dashboard', 'duer', 'edit-modal' ); ?>
</div>
