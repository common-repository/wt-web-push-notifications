<?php
class WTWPN_Notification 
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
	
	function wtwpn_add_query_vars()
	{
		$vars[] = $this->plugin_name;
		return $vars;
	}
	
	function wtwpn_my_nt_ajax_handler() {
		global $wpdb;
		$table = $wpdb->prefix."wtwpn_notificationtemplate";

		if($_REQUEST['param']=='save_nt' && empty($_REQUEST['tid'])){
			$wpdb->insert($table,array(
			"title"=>$_REQUEST['title'],
			"message"=>$_REQUEST['message'],
			"url"=>$_REQUEST['url'],
			"icon"=>$_REQUEST['image_name'],
			"status"=>$_REQUEST['active'],
			));
			
			$message = __('Notification inserted successfully' , WTWPN_TEXT_DOMAIN);

		}elseif($_REQUEST['foraction']=='edit' && isset($_REQUEST['tid'])){
			
			$id 	 = $_REQUEST['tid'];
			$title   = $_REQUEST['title'];
			$message = $_REQUEST['message'];
			$url 	 = $_REQUEST['url'];
			$icon	 = $_REQUEST['image_name'];
			$status  = $_REQUEST['active'];
			
			$wpdb->update($table, array('id'=>$id, 'title'=>$title, 'message'=>$message, 'url'=>$url, 'icon'=>$icon, 'status'=>$status), array('id'=>$id));

			$message = __('Notification updated successfully' , WTWPN_TEXT_DOMAIN);
		}
		
		$url = admin_url('admin.php?page=wtwpn-webpush-notification');
		echo json_encode(array("status1"=>2,"message"=>$message,"redirectURL"=>$url));
		wp_die();  
	
	}
}
?>
