<?php
/**
 * Le template principal pour ajouter un site
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

<div class="content-wrap">

	<?php require PLUGIN_DIGIRISK_DASHBOARD_PATH . '/core/view/main-header.view.php'; ?>

	<div class="wrap wpeo-wrap digirisk-wrap">
		<?php
		 \eoxia\View_Util::exec( 'digirisk_dashboard', 'site', 'edit', array(

		) );
		 ?>
	</div>
</div>
