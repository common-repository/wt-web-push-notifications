<?php
/**
 * Fired during plugin activation
 *
 * @link       https://wtplugins.com/
 * @since      1.0.0
 *
 * @package    Wt_Web_Push_Notifications
 * @subpackage Wt_Web_Push_Notifications/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Wt_Web_Push_Notifications
 * @subpackage Wt_Web_Push_Notifications/includes
 * @author     WT Plugins <contact@wtplugins.com>
 */
 
class WTWPN_Activator {

		/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function wtwpn_activate()
	{
		global $wpdb;
		
		$charset_collate = $wpdb->get_charset_collate();
		
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		
		$sql="CREATE TABLE `" . $wpdb->prefix."wtwpn_notificationtemplate" . "` (
		`id` INT(11) NOT NULL AUTO_INCREMENT,
		`title` varchar(225) NOT NULL,
		`message` varchar(225) NOT NULL,
		`url` varchar(225) NOT NULL,
		`icon` text,
		`status` varchar(225) NOT NULL,
		PRIMARY KEY (`id`)
		) $charset_collate";
		dbDelta($sql);

		$sql1 = "CREATE TABLE `" . $wpdb->prefix."wtwpn_subscribers" . "`(
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`key` varchar(255) NOT NULL,
		`user_id` int(11) NOT NULL,
		`userrole` varchar(50) NOT NULL,
		`browser` varchar(50) NOT NULL,
		`type` varchar(50) NOT NULL,
		`created by` int(11) NOT NULL,
		`createdon` date NOT NULL,
		PRIMARY KEY (`id`)
		) $charset_collate";
		dbDelta($sql1);
		
		$sql2 = "CREATE TABLE `" . $wpdb->prefix."wtwpn_notifications" . "`(
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`subscribers_id` int(11) NOT NULL,
		`group_id` int(11) NOT NULL,
		`template_id` int(11) NOT NULL,
		`sent` tinyint(11) NOT NULL,
		`isread` tinyint(1) NOT NULL,
		`sent_by` int(11) NOT NULL,
		`sent_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
		`client` int(11) NOT NULL,
		`client_id` int(11) NOT NULL,
		`created_on` int(11) NOT NULL,
		`created_by` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
		PRIMARY KEY (`id`)
		) $charset_collate";
		dbDelta($sql2);
		
		$sql3 = "CREATE TABLE `" . $wpdb->prefix."wtwpn_subscribersgroup" . "`(	
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`status` tinyint(1) NOT NULL,
		`title` varchar(250) NOT NULL,
		`description` text NOT NULL,
		`is_default` tinyint(1) NOT NULL,
		`user_role` varchar(250) NOT NULL,
		PRIMARY KEY (`id`)
		) $charset_collate";
		dbDelta($sql3);

		$wtwpn_subscribersgroup = $wpdb->prefix . "wtwpn_subscribersgroup";
	   
		$wpdb->insert($wtwpn_subscribersgroup,  
			 array(
			'id' => '',
			'status' => '1',
			'title' => 'default group',
			'description' => 'default group',
			'is_default' => '1',
			 'user_role' => 'All',
		));
		
		$default_group_id = $wpdb->insert_id;
		
		if ($default_group_id)
		{
			update_option('WTWPN_DEFAULT_GROUP_ID', $default_group_id);
		}
		
		global $wp_filesystem;
		
		$target = ABSPATH . 'firebase-messaging-sw.js';
		$target1 = ABSPATH . 'wtwpn-sw.js';
		 
		if( is_null( $wp_filesystem ) )
		{
			WP_Filesystem();
		}
		
		$wp_filesystem->put_contents($target, '');
		$wtwpn_sw_data = 'self.addEventListener("push",function(i){var t=i.data.json(),n=t.notification,o=n.title,a=n.body,c=n.icon,l=n.title,t={url:{clickurl:n.click_action}};i.waitUntil(self.registration.showNotification(o,{body:a,icon:c,tag:l,data:t}))}),self.addEventListener("notificationclick",function(i){i.notification.close();var t=i.notification.data.url,n=t.clickurl;i.waitUntil(clients.openWindow(n))});';
		$wp_filesystem->put_contents($target1, $wtwpn_sw_data);
		
		
		$mainfest_data = array();
		$mainfest_data['name']			= (string) get_bloginfo( 'name' );
		$mainfest_data['gcm_sender_id'] = (string) 103953800507;
		
		$mainfest_path =  WTWPN_PLUGIN_DIR_PATH . 'public/js/manifest.json'; 
		$mainfest_string = json_encode($mainfest_data);
		
		$wp_filesystem->put_contents($mainfest_path, $mainfest_string);
	}
}

$obj=new WTWPN_Activator();




