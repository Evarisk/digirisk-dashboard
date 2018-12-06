
<div class="wpeo-modal duer-modal-generate">
	<div class="modal-container">

		<!-- Entête -->
		<div class="modal-header">
			<h2 class="modal-title">Génération DUER</h2>
			<div class="modal-close"><i class="fal fa-times"></i></div>
		</div>

		<!-- Corps -->
		<div class="modal-content">
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
					Construction du DUER MU
					<img src="<?php echo esc_attr( admin_url( '/images/loading.gif' ) ); ?>" alt="<?php echo esc_attr( 'Chargement...' ); ?>" />
				</li>
				<li data-type="duer-mu">
					Génération du DUER MU
					<img src="<?php echo esc_attr( admin_url( '/images/loading.gif' ) ); ?>" alt="<?php echo esc_attr( 'Chargement...' ); ?>" />
				</li>
			</ul>
		</div>

		<!-- Footer -->
		<div class="modal-footer">
			<a class="wpeo-button button-grey button-uppercase modal-close"><span>Annuler</span></a>
			<a class="wpeo-button button-main button-uppercase modal-close"><span>Valider</span></a>
		</div>
	</div>
</div>
