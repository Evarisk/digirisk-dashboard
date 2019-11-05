<?php
/**
 * Le menu principale
 *
 * @author Evarisk <dev@evarisk.com>
 * @since 7.5.0
 * @copyright 2015-2019 Evarisk
 * @package DigiRisk
 */

namespace digirisk_dashboard;

defined( 'ABSPATH' ) || exit; ?>

<div class="nav-wrap">
	<div id="logo">
		<h1><a href="<?php echo admin_url( 'admin.php?page=digirisk-dashboard-sites' ); ?>"><img src="<?php echo PLUGIN_DIGIRISK_URL . '/core/assets/images/favicon_hd.png'; ?>" alt="DigiRisk" /></a></h1>
	</div>

	<div class="nav-menu">
		<?php
		if ( ! empty( Class_Digirisk_Dashboard_Core::g()->menu ) ) :
			foreach ( Class_Digirisk_Dashboard_Core::g()->menu as $key => $item ) :
				$active = "";

				if ( $key == $_REQUEST['page'] ) :
					$active = "item-active";
				endif;


					?>
					<div class="item <?php echo esc_attr( $item['class'] ); ?> <?php echo esc_attr( $active ); ?>">
						<a href="<?php echo esc_url( $item['link'] ); ?>"><?php echo esc_html( $item['title'] ); ?></a>
					</div>
					<?php
			endforeach;
		endif;
		?>
		<div class="nav-menu item-bottom">
			<?php
			if ( ! empty( Class_Digirisk_Dashboard_Core::g()->menu_bottom ) ) :
				foreach ( Class_Digirisk_Dashboard_Core::g()->menu_bottom as $key => $item ) :
					?>
					<div class="item">
						<a href="<?php echo esc_url( $item['link'] ); ?>"><?php echo esc_html( $item['title'] ); ?></a>
					</div>
					<?php
				endforeach;
			endif;
			?>
		</div>
	</div>
</div>
