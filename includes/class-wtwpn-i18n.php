<?php
/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://wtplugins.com/
 * @since      1.0.0
 *
 * @package    Wt_Web_Push_Notifications
 * @subpackage Wt_Web_Push_Notifications/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Wt_Web_Push_Notifications
 * @subpackage Wt_Web_Push_Notifications/includes
 * @author     WT Plugins <contact@wtplugins.com>
 */
class WTWPN_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function wtwpn_load_plugin_textdomain() {

		load_plugin_textdomain(
			'wt-web-push-notifications',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);
	}
}
