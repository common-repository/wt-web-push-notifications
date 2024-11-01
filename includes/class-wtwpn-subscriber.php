<?php
class WTWPN_Subscriber 
{
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {  
		
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}
	
	function wtwpn_add_query_vars($vars)
	{
		$vars[] = $this->plugin_name;
		return $vars;
	}
	
	function wtwpn_send_notification($template)
	{
		global $wp_query;

		if(!isset( $wp_query->query[$this->plugin_name] )) 
			return $template;
	
		if($wp_query->query[$this->plugin_name] == 'sendNotification')
		{
			require_once 'class-wtwpn-helper.php';
			
			$Wt_Web_Push_Notifications_Helper = new WTWPN_Helper;
			
			
			global $wpdb;
			
			$notification = $wpdb->prefix."wtwpn_notifications";
			$subscriber = $wpdb->prefix."wtwpn_subscribers";
			$notification_template = $wpdb->prefix."wtwpn_notificationtemplate";
			
			$notification_data = $wpdb->get_results("SELECT a.*, b.key, c.title, c.message, c.url, c.icon FROM " . $notification . " as a INNER JOIN " . $subscriber . " as b ON a.subscribers_id = b.id INNER JOIN " . $notification_template . " as c ON a.template_id = c.id WHERE a.sent = 0  LIMIT 0, 50");
			
			if ( $notification_data )
			{
				$sent_ids = array();
				$remove_ids = array();
				
				foreach($notification_data as $result)
				{
					$jpush_result =  $Wt_Web_Push_Notifications_Helper::wtwpn_push($result);
					$result_body = json_decode($jpush_result);
					
					if ($result_body && $result_body->multicast_id 	&& $result_body->success)
					{
						$sent_ids[] = $result->id;
					}
					else
					{
						$remove_ids[] = $result->id;
					}
				}
				
				if( $sent_ids )
				{
					$sent_ids =  implode(',' , $sent_ids);
					
					$wpdb->query( $wpdb->prepare( "UPDATE $notification SET sent = %d WHERE ID IN ( %s )", 1, $sent_ids) );
				}
				
				if( $remove_ids )
				{
					$remove_ids = implode(',' , $remove_ids);
					
					$wpdb->query($wpdb->prepare("DELETE FROM $notification WHERE id IN (%s)", $remove_ids) );
				}
			}
		}

		wp_die();
	}
	
	function wtwpn_save_subscriber()
	{
		if ($_POST['key'])
		{
			if ($_POST['Userid']) {
				$user_id = $_POST['Userid'];
				$user = new WP_User( 1 );
				$userrole =  wp_sprintf_l( '%l', $user->roles );
			}
			else {
				$user_id = 0;
				$userrole = '';
			}
			
			$browser = $_POST['browser'];
			$current_date = current_time('Y-m-d');
			
			global $wpdb;
			
			$table = $wpdb->prefix."wtwpn_subscribers";
			
			$wpdb->insert($table,array(
				'key' => $_POST['key'],
				'user_id' =>intval($user_id),
				'userrole' => sanitize_text_field($userrole),
				'browser' => sanitize_text_field($browser),
				'type' => 1,
				'created by' => intval($user_id),
				'createdon' => $current_date
			));	
			
			$subscriber_id = $wpdb->insert_id;
			
			if ($subscriber_id)
			{
				$group_id = get_option('WTWPN_DEFAULT_GROUP_ID');
				
				require_once 'class-wtwpn-helper.php';
			
				$Wt_Web_Push_Notifications_Helper = new WTWPN_Helper;
				
				$Wt_Web_Push_Notifications_Helper::wtwpn_addTopicSubscription($group_id, array($_POST['key']));
				
				echo $wpdb->insert_id;
			}
		}
		
		wp_die();
	}
	
	function wtwpn_subscriber_send()
	{
		$tid = $_POST['tid'];
		$sid = $_POST['sid'];
		$sid = explode(" ",$sid);
		
		global $wpdb;
		
		if ($sid)
		{
			$current_date = current_time('Y-m-d H:i:s');
			
			foreach($sid as $data)
			{
				$table = $wpdb->prefix."wtwpn_notifications";
			
				$wpdb->insert($table,array(
					'subscribers_id' =>intval($_POST['sid']), 
					'template_id' => intval( $_POST['tid']),
					'sent' => 0,
					'isread' => 0,
					'sent_by' => 0,
					'sent_on' => $current_date,
					'client' => 'WTWPN',
					'client_id' => 0,
					'created_on' => 0,
					'created_by' => $current_date
				));	
			}
			
			echo $wpdb->insert_id;
		}
		wp_die();
	}
}
?>
