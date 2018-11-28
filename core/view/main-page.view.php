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
        	<li class="tab-element tab-active" data-target="my-sites"><?php esc_html_e( 'Mes sites', 'digirisk-dashboard' ); ?></li>
        	<li class="tab-element" data-target="add-site"><?php esc_html_e( 'Ajouter un site', 'digirisk-dashboard' ); ?></li>
        	<li class="tab-element" data-target="risks"><?php esc_html_e( 'Risques', 'digirisk-dashboard' ); ?></li>
        	<li class="tab-element" data-target="duer"><?php esc_html_e( 'DUER', 'digirisk-dashboard' ); ?></li>
        </ul>

        <div class="tab-container">
        	<div id="my-sites" class="tab-content"><?php Class_Site::g()->display(); ?></div>
        	<div id="add-site" class="tab-content"><?php Class_Site::g()->display_edit(); ?></div>
        	<div id="risks" class="tab-content">Je suis le contenu du deuxieme onglet</div>
        	<div id="duer" class="tab-content tab-active"><?php Class_DUER::g()->display(); ?></div>
        </div>
    </div>
</div>
