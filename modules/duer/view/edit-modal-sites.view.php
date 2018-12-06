<?php
/**
 * La popup qui contient les formulaires ainsi que les informations de la génération du DUER.
 *
 * @author    Evarisk <dev@evarisk.com>
 * @since     6.0.0
 * @version   7.0.0
 * @copyright 2018 Evarisk.
 * @package   DigiRisk
 */

namespace digirisk_dashboard;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="wpeo-modal duer-modal-site">
	<div class="modal-container">

		<!-- Entête -->
		<div class="modal-header">
			<h2 class="modal-title">Édition des sites</h2>
			<div class="modal-close"><i class="fal fa-times"></i></div>
		</div>

		<!-- Corps -->
		<div class="modal-content wpeo-form">
			<div class="form-element">
				<span class="form-label">Site modèle</span>
				<label class="form-field-container">
					<select style="padding:0;" id="site-model" class="form-field" name="model_site_id">
						<?php
						if ( ! empty( $sites ) ) :
							foreach ( $sites as $id => $site ) :
								?><option value="<?php echo esc_attr( $id ); ?>"><?php echo $site['title'] . ' (' . $site['url'] . ')'; ?></option><?php
							endforeach;
						endif;
						?>
					</select>
				</label>
			</div>

			<div class="form-element">
				<span class="form-label">Sélection des sites pour le DUER</span>
				<label class="form-field-container">
					<input type="text" class="form-field filter-site" placeholder="Tapez ici pour filtrer les sites" />
				</label>
			</div>

			<ul class="list-sites" style="width: 100%; height: 200px; overflow-y: scroll; background-color: #ececec; padding: 10px;">
				<?php
				if ( ! empty( $sites ) ) :
					foreach ( $sites as $id => $site ) :
						?>
						<li class="form-element">
							<input type="checkbox" name="sites_id[<?php echo esc_attr( $id ); ?>]" id="checkbox-<?php echo esc_attr( $id ); ?>" class="form-field">
							<label for="checkbox-<?php echo esc_attr( $id ); ?>"><?php echo $id . ' ' . $site['title'] . ' (' . $site['url'] . ')'; ?></label>
						</li>
						<?php
					endforeach;
				endif;
				?>
			</ul>
		</div>

		<!-- Footer -->
		<div class="modal-footer">
			<a class="wpeo-button button-grey button-uppercase modal-close"><span>Annuler</span></a>
			<a class="wpeo-button button-main button-uppercase modal-close"><span>Valider</span></a>
		</div>
	</div>
</div>
