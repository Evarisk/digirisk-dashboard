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

<table class="wpeo-table">
	<thead>
		<tr>
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
					<td data-title="ID"><?php echo esc_html( $id ); ?></td>
					<td data-title="Site"><?php echo esc_html( $site['title'] ); ?></td>
					<td data-title="URL"><a href="<?php echo esc_attr( $site['url'] ); ?>" target="_blank"><?php echo esc_html( $site['url'] ); ?></a></td>
					<td data-title="Dernier DUER">
						<span>
							<?php
							if ( ! empty( $site['last_duer'] ) ) :
								echo $site['last_duer']->data['date']['rendered']['date'];
								if ( ! empty( $site['last_duer']->data['file_generated'] ) ) : ?>
									<a class="wpeo-button button-purple button-square-50 wpeo-tooltip-event"
										aria-label="<?php echo esc_attr_e( 'DUER', 'digirisk' ); ?>"
										href="<?php echo esc_attr( $site['last_duer']->data['link'] ); ?>">
										<i class="icon fas fa-file-alt"></i>
									</a>
								<?php else : ?>
									<span class="action-attribute wpeo-button button-grey button-square-50 wpeo-tooltip-event"
										data-id="<?php echo esc_attr( $site['last_duer']->data['id'] ); ?>"
										data-model="<?php echo esc_attr( $site['last_duer']->get_class() ); ?>"
										data-action="generate_document"
										data-color="red"
										data-direction="left"
										aria-label="<?php echo esc_attr_e( 'Corrompu. Cliquer pour regénérer.', 'digirisk' ); ?>">
										<i class="far fa-times icon" aria-hidden="true"></i>
									</span>
								<?php endif;
							else :
								?>
								N/A
								<?php
							endif;
							?>

						</span>
					</td>
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
