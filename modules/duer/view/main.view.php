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

<table class="wpeo-table">
	<thead>
		<tr>
			<th data-title="Ref">Ref</th>
			<th data-title="Début">Début</th>
			<th data-title="Fin">Fin</th>
			<th data-title="Destinataire">Destinataire</th>
			<th data-title="Méthodologie">Méthodologie</th>
			<th data-title="Sources">Sources</th>
			<th data-title="Localisation">Localisation</th>
			<th data-title="Notes">Notes</th>
			<th data-title="Sites">Sites</th>
			<th data-title="Actions"></th>
		</tr>
	</thead>
	<tbody>
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
	</tbody>
</table>

<?php \eoxia\View_Util::exec( 'digirisk_dashboard', 'duer', 'edit-modal' ); ?>
