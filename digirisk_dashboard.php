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
 * Version:     0.1.0-alpha
 * Author:      Evarisk
 * Author URI:  http://www.evarisk.com
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Domain Path: /core/assets/languages
 * Text Domain: digirisk-dashboard
 */

DEFINE( 'PLUGIN_DIGIRISK_DASHBOARD_PATH', str_replace( '\\', '/', realpath( plugin_dir_path( __FILE__ ) ) . '/' ) );
DEFINE( 'PLUGIN_DIGIRISK_DASHBOARD_URL', str_replace( '\\', '/', plugins_url( basename( __DIR__ ) ) . '/' ) );
DEFINE( 'PLUGIN_DIGIRISK_DASHBOARD_DIR', basename( __DIR__ ) );

require_once 'core/util/singleton.util.php';
require_once 'core/util/init.util.php';

Init_util::g()->exec();
