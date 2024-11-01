<?php
/**
 * Fired during plugin deactivation
 *
 * @link       https://wtplugins.com/
 * @since      1.0.0
 *
 * @package    Wt_Web_Push_Notifications
 * @subpackage Wt_Web_Push_Notifications/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Wt_Web_Push_Notifications
 * @subpackage Wt_Web_Push_Notifications/includes
 * @author     WT Plugins <contact@wtplugins.com>
 */
class WTWPN_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function wtwpn_deactivate() {
        global $wpdb;

		$table = $wpdb->prefix."wtwpn_notificationtemplate";

		$wpdb->query("DROP TABLE IF EXISTS $table");
		$table1 = $wpdb->prefix."wtwpn_subscribers";

		$wpdb->query("DROP TABLE IF EXISTS $table1");
		$table2 = $wpdb->prefix."wtwpn_notifications";

		$wpdb->query("DROP TABLE IF EXISTS $table2");
		$table3 = $wpdb->prefix."wtwpn_subscribersgroup";

		$wpdb->query("DROP TABLE IF EXISTS $table3");
	
		$target = ABSPATH . 'firebase-messaging-sw.js';
		$target1 = ABSPATH . 'wtwpn-sw.js';
		wp_delete_file($target);
		wp_delete_file($target1);
		
	}//end pluginUninstall function

//hook into WordPress when its being deactivated:
}

$obj=new WTWPN_Deactivator();
