<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://wtplugins.com/
 * @since             1.0.0
 * @package           Wt_Web_Push_Notifications
 *
 * @wordpress-plugin
 * Plugin Name:       WT Web Push Notifications Lite
 * Plugin URI:        https://wtplugins.com/plugins/wt-web-push-notifications
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.4
 * Author:            WT Plugins
 * Author URI:        https://wtplugins.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wt-web-push-notifications
 * Domain Path:       /languages
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WTWPN_VERSION', '1.0.4' );
define( 'WTWPN_PLUGIN_DIR_PATH', plugin_dir_path(__FILE__) );
define( 'WTWPN_PLUGIN_URL', plugins_url() );
define( 'WTWPN_TEXT_DOMAIN', 'wt-web-push-notifications' );

if(!defined("ABSPATH"))
 exit;
if(!defined("WTWPN_PLUGIN_DIR_PATH"))
	define("WTWPN_PLUGIN_DIR_PATH",plugin_dir_path(__FILE__));
	
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wtwpn-activator.php
 */
function wtwpn_activate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wtwpn-activator.php';
	WTWPN_Activator::wtwpn_activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wtwpn-deactivator.php
 */
function wtwpn_deactivate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wtwpn-deactivator.php';
	WTWPN_Deactivator::wtwpn_deactivate();
}

register_activation_hook( __FILE__, 'wtwpn_activate' );
register_deactivation_hook( __FILE__, 'wtwpn_deactivate' );

require plugin_dir_path( __FILE__ ) . 'includes/class-wtwpn.php';

function run_wtwpn() {

	$plugin = new WTWPN();
	$plugin->wtwpn_run();

}
run_wtwpn();
