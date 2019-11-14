<?php
/**
 * Le template contenant l'arbre affichant les documents à générer.
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

<div class="wpeo-modal duer-modal-generate modal-force-display">
	<div class="modal-container">

		<!-- Entête -->
		<div class="modal-header" style="min-height: 10%; height: auto; flex-wrap: wrap;">
			<h2 class="modal-title" style="width: 90%;"><?php esc_html_e( 'Génération DUER', 'digirisk-dashboard' ); ?></h2>
			<div class="modal-close" style="width: 10%;"><i class="fas fa-times"></i></div>
			<div class="notice notice-error hidden">
				<p></p>
			</div>
		</div>

		<!-- Corps -->
		<div class="modal-content" style="height: 76%">

			<ul>
				<?php
				if ( ! empty( $sites ) ) :
					foreach ( $sites as $id => $site ) :
						?>
						<li data-id="<?php echo esc_attr( $id ); ?>">
							<?php printf( 'Génération des fiches de groupement et de poste pour le site #%d %s (%s)', $id, $site['title'], $site['url'] ); ?>
							<img src="<?php echo esc_attr( admin_url( '/images/loading.gif' ) ); ?>" alt="<?php echo esc_attr( 'Chargement...' ); ?>" />
						</li>
						<?php
					endforeach;
				endif;
				?>

				<li data-type="construct-duer-mu">
					<?php esc_html_e( 'Construction du DUER MU', 'digirisk-dashboard' ); ?>
					<img src="<?php echo esc_attr( admin_url( '/images/loading.gif' ) ); ?>" alt="<?php echo esc_attr( 'Chargement...' ); ?>" />
				</li>
				<li data-type="duer-mu">
					<?php esc_html_e( 'Génération du DUER MU', 'digirisk-dashboard' ); ?>
					<img src="<?php echo esc_attr( admin_url( '/images/loading.gif' ) ); ?>" alt="<?php echo esc_attr( 'Chargement...' ); ?>" />
				</li>

				<li data-type="zip">
					<?php esc_html_e( 'Génération du ZIP', 'digirisk-dashboard' ); ?>
					<img src="<?php echo esc_attr( admin_url( '/images/loading.gif' ) ); ?>" alt="<?php echo esc_attr( 'Chargement...' ); ?>" />
				</li>
			</ul>
		</div>

		<!-- Footer -->
		<div class="modal-footer">
			<a class="wpeo-button button-main button-uppercase modal-close close-duer-modal action-attribute"
				data-action="close_modal_duer"><span><?php esc_html_e( 'Fermer', 'digirisk-dashboard' ); ?></span></a>
		</div>
	</div>
</div>
