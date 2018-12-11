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
} ?>

<h3>Site modèle</h3>

<?php if ( ! empty( $duer->data['model_site'] ) ) : ?>
	<span><?php echo $duer->data['model_site']['id'] . ' ' . $duer->data['model_site']['title'] . ' (' . $duer->data['model_site']['url'] . ')'; ?></span>
<?php else: ?>
	<span>Aucun site modèle sélectionné</span>
<?php endif; ?>

<h3>Sélection des sites pour le DUER</h3>

<ul>
	<?php
	if ( ! empty( $duer->data['sites'] ) ) :
		foreach ( $duer->data['sites'] as $site ) :
			?>
			<li><span><?php echo $site['id'] . ' ' . $site['title'] . ' (' . $site['url'] . ')'; ?></span></li>
			<?php
		endforeach;
	else:
		?>
		<li><span>Aucun site sélectionné</span></li>
		<?php
	endif;
	?>
</ul>
