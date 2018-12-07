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

<div class="wp-wrap wpeo-wrap">
    <div class="wpeo-tab">
        <ul class="tab-list">
        	<li class="tab-element tab-active" data-action="digi_dashboard_load_tab" data-type="sites"><?php esc_html_e( 'Mes sites', 'digirisk-dashboard' ); ?></li>
        	<li class="tab-element" data-action="digi_dashboard_load_tab" data-type="add-site"><?php esc_html_e( 'Ajouter un site', 'digirisk-dashboard' ); ?></li>
        	<li class="tab-element" data-action="digi_dashboard_load_tab" data-type="duer"><?php esc_html_e( 'DUER', 'digirisk-dashboard' ); ?></li>
        </ul>

        <div class="tab-container">
        	<div class="tab-content tab-active"><?php Class_Site::g()->display(); ?></div>
        </div>
    </div>
</div>
