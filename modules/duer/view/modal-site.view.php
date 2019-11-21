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

<div class="wpeo-modal duer-modal-site">
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
		<div class="modal-content">
		</div>
		<!-- Footer -->
		<div class="modal-footer">
			<a class="wpeo-button button-main button-uppercase modal-close"><span><?php esc_html_e( 'Confirmer', 'digirisk-dashboard' ); ?></span></a>
		</div>
	</div>
</div>
