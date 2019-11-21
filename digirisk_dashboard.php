<?php
/**
 * Fichier boot du plugin
 *
 * @package digirisk-dashboard
 */

namespace digirisk_dashboard;

/**
 * Plugin Name: DigiRisk Dashboard
 * Plugin URI:  http://www.evarisk.com/document-unique-logiciel
 * Description: Gestion de DigiRisk pour les multisites de WordPress
 * Version:     0.3.0
 * Author:      Evarisk
 * Author URI:  http://www.evarisk.com
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Domain Path: /core/assets/languages
 * Text Domain: digirisk-dashboard
 */

DEFINE( 'PLUGIN_DIGIRISK_DASHBOARD_URL', str_replace( '\\', '/', plugins_url( basename( __DIR__ ) ) . '/' ) );
DEFINE( 'PLUGIN_DIGIRISK_DASHBOARD_DIR', basename( __DIR__ ) );
DEFINE( 'PLUGIN_DIGIRISK_DASHBOARD_PATH', str_replace( '\\', '/', realpath( plugin_dir_path( __FILE__ ) ) . '/' ) );
DEFINE( 'PLUGIN_DIGIRSK_DASHBOARD_DEV_MODE', true );

if ( ! PLUGIN_DIGIRSK_DASHBOARD_DEV_MODE ) {
	require_once 'core/external/eo-framework/eo-framework.php';
}

\eoxia\Init_Util::g()->exec( PLUGIN_DIGIRISK_DASHBOARD_PATH, basename( __FILE__, '.php' ) );
