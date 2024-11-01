<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://wtplugins.com/
 * @since      1.0.0
 *
 * @package    Wt_Web_Push_Notifications
 * @subpackage Wt_Web_Push_Notifications/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Wt_Web_Push_Notifications
 * @subpackage Wt_Web_Push_Notifications/includes
 * @author     WT Plugins <contact@wtplugins.com>
 */
class WTWPN {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      WTWPN_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'WTWPN_VERSION' ) ) {
			$this->version = WTWPN_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'wt-web-push-notifications';

		$this->wtwpn_load_dependencies();
		$this->wtwpn_set_locale();
		$this->wtwpn_define_admin_hooks();
		$this->wtwpn_define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - WTWPN_Loader. Orchestrates the hooks of the plugin.
	 * - WTWPN_i18n. Defines internationalization functionality.
	 * - WTWPN_Admin. Defines all hooks for the admin area.
	 * - WTWPN_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function wtwpn_load_dependencies() {
		
		require_once WTWPN_PLUGIN_DIR_PATH . 'includes/class-wtwpn-notification.php';
		require_once WTWPN_PLUGIN_DIR_PATH . 'includes/class-wtwpn-subscriber.php';
		
		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once WTWPN_PLUGIN_DIR_PATH . 'includes/class-wtwpn-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once WTWPN_PLUGIN_DIR_PATH . 'includes/class-wtwpn-i18n.php';
		

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once WTWPN_PLUGIN_DIR_PATH . 'admin/class-wtwpn-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once WTWPN_PLUGIN_DIR_PATH . 'public/class-wtwpn-public.php';

		$this->loader = new WTWPN_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the WTWPN_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function wtwpn_set_locale() {

		$plugin_i18n = new WTWPN_i18n();

		$this->loader->wtwpn_add_action( 'plugins_loaded', $plugin_i18n, 'wtwpn_load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function wtwpn_define_admin_hooks() {

		$plugin_admin = new WTWPN_Admin( $this->wtwpn_get_plugin_name(), $this->wtwpn_get_version() );

		$this->loader->wtwpn_add_action( 'admin_enqueue_scripts', $plugin_admin, 'wtwpn_enqueue_styles' );
		$this->loader->wtwpn_add_action( 'admin_enqueue_scripts', $plugin_admin, 'wtwpn_enqueue_scripts' );
		$this->loader->wtwpn_add_action( 'admin_menu', $plugin_admin, 'wtwpn_MenuSection' );
		$this->loader->wtwpn_add_action( 'admin_init', $plugin_admin, 'wtwpn_register_setting' ); 
			  
			  $plugin_notification_admin = new WTWPN_Notification( $this->wtwpn_get_plugin_name(), $this->wtwpn_get_version() );
		
		$this->loader->wtwpn_add_action( 'wp_ajax_myntlibrary', $plugin_notification_admin, 'wtwpn_my_nt_ajax_handler' );
		$this->loader->wtwpn_add_action( 'wp_ajax_nopriv_myntlibrary', $plugin_notification_admin, 'wtwpn_my_nt_ajax_handler' );
		
		$plugin_subscriber_admin = new WTWPN_Subscriber( $this->wtwpn_get_plugin_name(), $this->wtwpn_get_version() );
		
		$this->loader->wtwpn_add_action( 'wp_ajax_wtwpn_subscriber_send', $plugin_subscriber_admin, 'wtwpn_subscriber_send' );
		$this->loader->wtwpn_add_action( 'wp_ajax_nopriv_wtwpn_subscriber_send', $plugin_subscriber_admin, 'wtwpn_send_subscriber' );
		
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function wtwpn_define_public_hooks() {

		$plugin_public = new WTWPN_Public( $this->wtwpn_get_plugin_name(), $this->wtwpn_get_version() );

		$this->loader->wtwpn_add_action( 'wp_enqueue_scripts', $plugin_public, 'wtwpn_enqueue_styles' );
		$this->loader->wtwpn_add_action( 'wp_enqueue_scripts', $plugin_public, 'wtwpn_enqueue_scripts' );
		$this->loader->wtwpn_add_action( 'wp_enqueue_scripts', $plugin_public, 'wtwpn_initial' );
		$this->loader->wtwpn_add_action( 'init', $plugin_public, 'wtwpn_initial' );
		
		$plugin_subscriber_public = new WTWPN_Subscriber( $this->wtwpn_get_plugin_name(), $this->wtwpn_get_version() );
		
		$this->loader->wtwpn_add_action( 'wp_ajax_wtwpn_subscriber_save', $plugin_subscriber_public, 'wtwpn_save_subscriber' );
		$this->loader->wtwpn_add_action( 'wp_ajax_nopriv_wtwpn_subscriber_save', $plugin_subscriber_public, 'wtwpn_save_subscriber' );
		
		$this->loader->wtwpn_add_filter( 'query_vars',$plugin_subscriber_public, 'wtwpn_add_query_vars' );
		$this->loader->wtwpn_add_action('template_redirect', $plugin_subscriber_public, 'wtwpn_send_notification' );
		
		
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function wtwpn_run() {
		$this->loader->wtwpn_run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function wtwpn_get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    WTWPN_Loader    Orchestrates the hooks of the plugin.
	 */
	public function wtwpn_get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function wtwpn_get_version() {
		return $this->version;
	}
}
