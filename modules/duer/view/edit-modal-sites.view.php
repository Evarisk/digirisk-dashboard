<?php
/**
 * La popup qui contient les formulaires ainsi que les informations de la génération du DUER.
 *
 * @author    Evarisk <dev@evarisk.com>
 * @since     0.2.0
 * @copyright 2018 Evarisk.
 * @package   DigiRisk
 */

namespace digirisk_dashboard;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$model_id = null; ?>

<div class="wpeo-modal duer-modal-site">
	<div class="modal-container">

		<!-- Entête -->
		<div class="modal-header">
			<h2 class="modal-title">Édition des sites</h2>
			<div class="modal-close"><i class="fas fa-times"></i></div>
		</div>

		<!-- Corps -->
		<div class="modal-content wpeo-form">
			<?php
			if ( empty( $child_sites ) ) :
				?>
				<p>Aucun site ajouté au dashboard</p>
				<p>Veuillez ajouter un ou plusieurs sites avant de générer un DUER</p>
				<p>Pour ce faire, aller dans l'onglet "Ajouter un site"</p>
				<?php
			else:
				?>
				<div class="form-element">
					<span class="form-label">Site générique</span>
					<label class="form-field-container">
						<select style="padding:0;" id="site-model" class="form-field" name="model_site_id">
							<option>Sélectionner un site</option>
							<?php
							if ( ! empty( $child_sites ) ) :
								foreach ( $child_sites as $id => $site ) :
									if ( $model_id == null ) :
										$model_id = $id;
									endif;

									?><option <?php echo ! $site['check_connect'] ? 'disabled' : ''; ?> value="<?php echo esc_attr( $id ); ?>">
										<?php echo $site['title'] . ' (' . $site['url'] . ')'; ?>
										<?php echo ! $site['check_connect'] ? 'Site désynchronisé' : ''; ?>
									</option><?php
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
					if ( ! empty( $child_sites ) ) :
						foreach ( $child_sites as $id => $site ) :
							?>
							<li class="form-element <?php echo ( ! $site['check_connect'] ) ? 'disabled' : ''; ?>" data-id="<?php echo esc_attr( $id ); ?>">
								<input type="checkbox" <?php echo ( ! $site['check_connect'] ) ? 'disabled' : ''; ?> name="sites_id[<?php echo esc_attr( $id ); ?>]" id="checkbox-<?php echo esc_attr( $id ); ?>" class="form-field">
								<label for="checkbox-<?php echo esc_attr( $id ); ?>"><?php echo $id . ' ' . $site['title'] . ' (' . $site['url'] . ')'; ?>
									<span style="display: none;">Site générique</span>
									<span style="<?php echo ! $site['check_connect'] ? 'display: inline-block;' : 'display: none;'; ?>">Site désynchronisé</span>
								</label>
							</li>
							<?php
						endforeach;
					endif;
					?>
				</ul>
				<?php
			endif;
			?>
		</div>

		<!-- Footer -->
		<div class="modal-footer">
			<a class="wpeo-button button-grey button-uppercase modal-close"><span>Annuler</span></a>
			<a class="wpeo-button button-main button-uppercase modal-close"><span>Valider</span></a>
		</div>
	</div>
</div>
