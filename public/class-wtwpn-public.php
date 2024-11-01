<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://wtplugins.com/
 * @since      1.0.0
 *
 * @package    Wt_Web_Push_Notifications
 * @subpackage Wt_Web_Push_Notifications/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Wt_Web_Push_Notifications
 * @subpackage Wt_Web_Push_Notifications/public
 * @author     WT Plugins <contact@wtplugins.com>
 */
class WTWPN_Public {

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

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wtwpn-public.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wtwpn-public.js', array( 'jquery' ), $this->version, false );
	}
	
	public function wtwpn_initial()
	{ 
		$u_agent = $_SERVER['HTTP_USER_AGENT'];
		
		if(preg_match('/Edge/i',$u_agent)) {
			$bname = 'Edge';
			$ub = "MSIE";
		} elseif(preg_match('/Firefox/i',$u_agent)) {
			$bname = 'Mozilla Firefox';
			$ub = "Firefox";
		} elseif(preg_match('/Chrome/i',$u_agent)) {
			$bname = 'Google Chrome';
			$ub = "Chrome";
		} elseif(preg_match('/Safari/i',$u_agent)) {
			$bname = 'Apple Safari';
			$ub = "Safari";
		} elseif(preg_match('/Opera/i',$u_agent)) {
			$bname = 'Opera';
			$ub = "Opera";
		} elseif(preg_match('/Netscape/i',$u_agent)) {
			$bname = 'Netscape';
			$ub = "Netscape";
		}
		
		if( ($bname == 'Google Chrome' || $bname == 'Opera' || $bname == 'Mozilla Firefox' || $bname == 'Edge'))
		{
			
			wp_enqueue_script( 'wtwpn-firebase-app', 'https://www.gstatic.com/firebasejs/6.2.4/firebase-app.js' );
			wp_enqueue_script( 'wtwpn-firebase', 'https://www.gstatic.com/firebasejs/6.2.4/firebase-messaging.js' );
			wp_enqueue_script( 'wtwpn-initial', plugin_dir_url( __FILE__ ) . 'js/wtwpn-initial.js', array( 'jquery' ), $this->version, false );
			wp_register_script( 'wtwpn-initial', plugin_dir_url( __FILE__ ) . 'js/wtwpn-initial.js');
			
			$wtwpnValue = array(
				'WTWPN_Setting_server' => get_option( 'WTWPN_Setting_server' ),
				'WTWPN_Setting_api' => get_option( 'WTWPN_Setting_api' ),
				'WTWPN_Setting_sender' => get_option( 'WTWPN_Setting_sender' ),
				'sw_url' => get_site_url() . '/wtwpn-sw.js',
				'baseurl' => admin_url("admin-ajax.php"),
				'userid' => get_current_user_id(),
				'browser' => $bname
			);
			wp_localize_script( "wtwpn-initial", "wtwpnfirebase", $wtwpnValue );
			wp_register_script('wtwpn-firebase-messaging-sw', site_url() . '/firebase-messaging-sw.js');
			wp_enqueue_script('wtwpn-firebase-messaging-sw');
			wp_register_script('wtwpn-sw', site_url() . '/wtwpn-sw.js');
			wp_enqueue_script('wtwpn-sw');
		}
	}
}
