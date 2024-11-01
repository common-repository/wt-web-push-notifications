<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://wtplugins.com/
 * @since      1.0.0
 *
 * @package    Wt_Web_Push_Notifications
 * @subpackage Wt_Web_Push_Notifications/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wt_Web_Push_Notifications
 * @subpackage Wt_Web_Push_Notifications/admin
 * @author     WT Plugins <contact@wtplugins.com>
 */
 

if(!defined("ABSPATH"))
exit;

if(!defined("WTWPN_PLUGIN_ADMIN_DIR_PATH"))
define("WTWPN_PLUGIN_ADMIN_DIR_PATH",plugin_dir_path(__FILE__));

class WTWPN_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $option_name = 'WTWPN_Setting';
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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	 private $plugin_text_domain;
	 /**
	 * WP_List_Table object
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      user_list_table    $user_list_table
	 */
	 
	 
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		//add_action( 'wp_ajax_myntlibrary', array($this,'wtwpn_my_nt_ajax_handler'));
	}


	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function wtwpn_enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wt_Web_Push_Notifications_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wt_Web_Push_Notifications_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
$current_screen = get_current_screen();

		if ( strpos($current_screen->base, 'wtwpn') == true) {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wtwpn-admin.css', array(), $this->version, 'all' );
		wp_enqueue_style( "bootstrap", plugin_dir_url( __FILE__ ) . 'css/bootstrap.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( "style", plugin_dir_url( __FILE__ ) . 'css/style.css', array(), $this->version, 'all' );
		wp_enqueue_style( "datatable", plugin_dir_url( __FILE__ ) . 'css/jquery.dataTables.min.css', array(), $this->version, 'all' );
	    wp_register_style( 'fontawesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css', array(), '4.2.0' );
		wp_enqueue_style( 'fontawesome' );
	}
}
	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function wtwpn_enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wt_Web_Push_Notifications_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wt_Web_Push_Notifications_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script('jquery');
		
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wtwpn-admin.js',array( 'jquery' ), $this->version, false );
		wp_enqueue_script( "bootstrap.min.js", plugin_dir_url( __FILE__ ) . 'js/bootstrap.min.js',array( 'jquery' ), $this->version, false );
		wp_enqueue_script( "datatable.min.js", plugin_dir_url( __FILE__ ) . 'js/jquery.dataTables.min.js',array( 'jquery' ), $this->version, false );
		wp_enqueue_script("jquery.validate.min.js", plugin_dir_url( __FILE__ ) . 'js/jquery.validate.min.js',array( 'jquery' ), $this->version, false );
		wp_enqueue_script( "script.js", plugin_dir_url( __FILE__ ) . 'js/script.js',array( 'jquery' ), $this->version, false );
		wp_localize_script("script.js","ntajaxurl",admin_url("admin-ajax.php"));
	}
				
	public function wtwpn_add_options_page() {
	
		$this->plugin_screen_hook_suffix = add_options_page(
			__( 'Settings', WTWPN_TEXT_DOMAIN ),
			__( 'Settings', WTWPN_TEXT_DOMAIN ),
			'manage_options',
			$this->plugin_name,
			array( $this, 'wt_webpush_setting' )
		);
	
	}
	public function wtwpn_MenuSection()
	{
		add_menu_page( 
		"WT Web Push Notifications",
		"WT Web Push Notifications",
		"manage_options",
		 "wtwpn-webpush", 
		 array( $this, 'wtwpn_dashboard' ),
		plugins_url( 'images/wtwpn.png', __FILE__ ), 
		30);
	
	
		add_submenu_page( 
		"wtwpn-webpush",
		"Dashboard",
		"Dashboard",
		"manage_options",
		 "wtwpn-webpush", 
		 array( $this, 'wtwpn_dashboard' ),
		"dashicons-admin-plugins", 
		30);
		
		add_submenu_page( 
		"wtwpn-webpush",
		"Notifications",
		"Notifications Template",
		"manage_options",
		 "wtwpn-webpush-notification", 
		 array( $this, 'wtwpn_notification_template' ),
		"dashicons-admin-plugins", 
		30);
		add_submenu_page( 
		"",
		"Add New",
		"Add New Notifications Template",
		"manage_options",
		 "wtwpn-webpush-add-new-notification", 
		 array( $this, 'wtwpn_add_new_notification' ),
		"dashicons-admin-plugins", 
		30);
		add_submenu_page( 
		"wtwpn-webpush",
		"Subscribers",
		"Subscribers",
		"manage_options",
		 "wtwpn-webpush-subscriber", 
		 array( $this, 'wtwpn_subscribers' ),
		"dashicons-admin-plugins", 
		30);
		add_submenu_page( 
		"wtwpn-webpush",
		"Settings",
		"Settings",
		"manage_options",
		 "wtwpn-webpush-setting", 
		 array( $this, 'wtwpn_setting' ),
		"dashicons-admin-plugins", 
		30);
		add_submenu_page( 
		"",
		"Send Notification",
		"Send Notification",
		"manage_options",
		 "wtwpn-web-push-notifications-send", 
		 array( $this, 'wtwpn_send' ),
		"dashicons-admin-plugins", 
		30);
		
	}
	public function wtwpn_notification_template()
	{
		include_once( WTWPN_PLUGIN_DIR_PATH . 'admin/partials/wtwpn-list.php' );
	}
	public function wtwpn_send()
	{
		include_once( WTWPN_PLUGIN_DIR_PATH . 'admin/partials/wtwpn-send.php' );
	}
	public function wtwpn_add_new_notification()
	{
		include_once( WTWPN_PLUGIN_DIR_PATH . 'admin/partials/wtwpn-list-add.php' );
	}
	public function wtwpn_dashboard()
	{
		include_once( WTWPN_PLUGIN_DIR_PATH . 'admin/partials/wtwpn-dashboard.php' );
	}
	public function wtwpn_subscribers()
	{
		include_once( WTWPN_PLUGIN_DIR_PATH . 'admin/partials/wtwpn-subscriber.php' );
	}
	public function wtwpn_setting()
	{
		include_once( WTWPN_PLUGIN_DIR_PATH . 'admin/partials/wtwpn-setting.php' );
	}
	

	public function wtwpn_register_setting() {
		add_settings_section(
			$this->option_name . '_general',
			__( 'google firebase', WTWPN_TEXT_DOMAIN ),
			array( $this, $this->option_name . '_general_cb' ),
			$this->plugin_name
		);
		add_settings_field(
			$this->option_name . '_server',
			__( 'Server Key', WTWPN_TEXT_DOMAIN ),
			array( $this, $this->option_name . '_server_cb' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array( 'label_for' => $this->option_name . '_server' )
		);
		add_settings_field(
			$this->option_name . '_api',
			__( 'API Key', WTWPN_TEXT_DOMAIN ),
			array( $this, $this->option_name . '_api_cb' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array( 'label_for' => $this->option_name . '_api' )
		);
		add_settings_field(
			$this->option_name . '_sender',
			__( 'Sender ID', WTWPN_TEXT_DOMAIN ),
			array( $this, $this->option_name . '_sender_cb' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array( 'label_for' => $this->option_name . '_sender' )
		);add_settings_field(
			$this->option_name . '_project',
			__( 'Project ID', WTWPN_TEXT_DOMAIN ),
			array( $this, $this->option_name . '_project_cb' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array( 'label_for' => $this->option_name . '_Project ID' )
		);add_settings_field(
			$this->option_name . '_icon',
			__( 'Default Push Notification Icon', WTWPN_TEXT_DOMAIN ),
			array( $this, $this->option_name . '_icon_cb' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array( 'label_for' => $this->option_name . '_Default Push Notification Icon' )
		);add_settings_field(
			$this->option_name . '_url',
			__( 'Default Push Notification Url', WTWPN_TEXT_DOMAIN ),
			array( $this, $this->option_name . '_url_cb' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array( 'label_for' => $this->option_name . '_Default Push Notification Url' )
		);
		
		register_setting( $this->plugin_name, $this->option_name . '_server', '' );
		register_setting( $this->plugin_name, $this->option_name . '_api', '' );
		register_setting( $this->plugin_name, $this->option_name . '_sender', '' );
		register_setting( $this->plugin_name, $this->option_name . '_project', '' );
		register_setting( $this->plugin_name, $this->option_name . '_icon', '' );
		register_setting( $this->plugin_name, $this->option_name . '_url', '' );

	}
	public function wtwpn_Setting_server_cb() {
		$server = get_option( $this->option_name . '_server' );
		echo '<input type="text" name="' . $this->option_name . '_server' . '" id="' . $this->option_name . '_server' . '" value="' . $server . '"> ' . __( '', WTWPN_TEXT_DOMAIN );
	}
	public function wtwpn_Setting_api_cb() {
		$api = get_option( $this->option_name . '_api' );
		echo '<input type="text" name="' . $this->option_name . '_api' . '" id="' . $this->option_name . '_api' . '" value="' . $api . '"> ' . __( '', WTWPN_TEXT_DOMAIN );
	}
	public function wtwpn_Setting_sender_cb() {
		$sender = get_option( $this->option_name . '_sender' );
		echo '<input type="text" name="' . $this->option_name . '_sender' . '" id="' . $this->option_name . '_sender' . '" value="' . $sender . '"> ' . __( '', WTWPN_TEXT_DOMAIN );
	}
	public function wtwpn_Setting_project_cb() {
		$project = get_option( $this->option_name . '_project' );
		echo '<input type="text" name="' . $this->option_name . '_project' . '" id="' . $this->option_name . '_project' . '" value="' . $project . '"> ' . __( '', WTWPN_TEXT_DOMAIN );
	}
	//icon url needed
	public function wtwpn_Setting_icon_cb() {
		$icon = get_option( $this->option_name . '_icon' ) ? get_option( $this->option_name . '_icon' ) : plugins_url( 'images/wtwpn_icon.png', __FILE__ );
		echo '<input type="text" name="' . $this->option_name . '_icon' . '" id="' . $this->option_name . '_icon' . '" value="' . $icon . '"> ' . __( 'Please Enter Image Url. <b>Ex: http://example.com/upload/xyz.png</b>', WTWPN_TEXT_DOMAIN );
	}
	public function wtwpn_Setting_url_cb() {
		$url = get_option( $this->option_name . '_url' );
		echo '<input type="text" name="' . $this->option_name . '_url' . '" id="' . $this->option_name . '_url' . '" value="' . $url . '"> ' . __( '', WTWPN_TEXT_DOMAIN );
	}
	public function wtwpn_Setting_general_cb() {
		echo '<p>' . __( 'Please change the settings accordingly.', WTWPN_TEXT_DOMAIN ) . '</p>';
	}

	//~ function wtwpn_my_nt_ajax_handler() {
	  //~ global $wpdb;
	   //~ $table = $wpdb->prefix."wtwpn_notificationtemplate";

	  //~ if($_REQUEST['param']=='save_nt'){
		//~ $wpdb->insert($table,array(
			//~ "title"=>$_REQUEST['title'],
			//~ "message"=>$_REQUEST['message'],
			//~ "url"=>$_REQUEST['url'],
			//~ "icon"=>$_REQUEST['image_name'],
			//~ "status"=>$_REQUEST['active'],
		//~ ));
		//~ $url = admin_url('admin.php?page=wt-webpush-notification');
		//~ echo json_encode(array("status1"=>1,"message"=>"Notification inserted successfully","redirectURL"=>$url));
		
	  //~ }
	  //~ wp_die();  
	
	//~ }
}
