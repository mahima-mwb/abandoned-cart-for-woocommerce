<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Abandoned_Cart_For_Woocommerce
 * @subpackage Abandoned_Cart_For_Woocommerce/includes
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
 * @package    Abandoned_Cart_For_Woocommerce
 * @subpackage Abandoned_Cart_For_Woocommerce/includes
 * @author     makewebbetter <webmaster@makewebbetter.com>
 */
class Abandoned_Cart_For_Woocommerce {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Abandoned_Cart_For_Woocommerce_Loader    $loader    Maintains and registers all hooks for the plugin.
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
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $acfw_onboard    To initializsed the object of class onboard.
	 */
	protected $acfw_onboard;

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

		if ( defined( 'ABANDONED_CART_FOR_WOOCOMMERCE_VERSION' ) ) {

			$this->version = ABANDONED_CART_FOR_WOOCOMMERCE_VERSION;
		} else {

			$this->version = '1.0.0';
		}

		$this->plugin_name = 'abandoned-cart-for-woocommerce';

		$this->abandoned_cart_for_woocommerce_dependencies();
		$this->abandoned_cart_for_woocommerce_locale();
		if ( is_admin() ) {
			$this->abandoned_cart_for_woocommerce_admin_hooks();
		} else {
			$this->abandoned_cart_for_woocommerce_public_hooks();
		}

		$this->abandoned_cart_for_woocommerce_api_hooks();
		$this->abandoned_cart_for_woocommerce_common_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Abandoned_Cart_For_Woocommerce_Loader. Orchestrates the hooks of the plugin.
	 * - Abandoned_Cart_For_Woocommerce_i18n. Defines internationalization functionality.
	 * - Abandoned_Cart_For_Woocommerce_Admin. Defines all hooks for the admin area.
	 * - Abandoned_Cart_For_Woocommerce_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function abandoned_cart_for_woocommerce_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-abandoned-cart-for-woocommerce-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-abandoned-cart-for-woocommerce-i18n.php';

		if ( is_admin() ) {

			// The class responsible for defining all actions that occur in the admin area.
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-abandoned-cart-for-woocommerce-admin.php';

			// The class responsible for on-boarding steps for plugin.
			if ( is_dir( plugin_dir_path( dirname( __FILE__ ) ) . '.onboarding' ) && ! class_exists( 'Abandoned_Cart_For_Woocommerce_Onboarding_Steps' ) ) {
				require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-abandoned-cart-for-woocommerce-onboarding-steps.php';
			}

			if ( class_exists( 'Abandoned_Cart_For_Woocommerce_Onboarding_Steps' ) ) {
				$acfw_onboard_steps = new Abandoned_Cart_For_Woocommerce_Onboarding_Steps();
			}
		} else {

			// The class responsible for defining all actions that occur in the public-facing side of the site.
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-abandoned-cart-for-woocommerce-public.php';

		}

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'common/class-abandoned-cart-for-woocommerce-common.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'package/rest-api/class-abandoned-cart-for-woocommerce-rest-api.php';

		$this->loader = new Abandoned_Cart_For_Woocommerce_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Abandoned_Cart_For_Woocommerce_I18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function abandoned_cart_for_woocommerce_locale() {

		$plugin_i18n = new Abandoned_Cart_For_Woocommerce_I18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function abandoned_cart_for_woocommerce_admin_hooks() {

		$acfw_plugin_admin = new Abandoned_Cart_For_Woocommerce_Admin( $this->acfw_get_plugin_name(), $this->acfw_get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $acfw_plugin_admin, 'acfw_admin_enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $acfw_plugin_admin, 'acfw_admin_enqueue_scripts' );

		// Add settings menu for Abandoned Cart for WooCommerce.
		$this->loader->add_action( 'admin_menu', $acfw_plugin_admin, 'acfw_options_page' );
		$this->loader->add_action( 'admin_menu', $acfw_plugin_admin, 'mwb_acfw_remove_default_submenu', 50 );

		// All admin actions and filters after License Validation goes here.
		$this->loader->add_filter( 'mwb_add_plugins_menus_array', $acfw_plugin_admin, 'acfw_admin_submenu_page', 15 );
		$this->loader->add_filter( 'acfw_template_settings_array', $acfw_plugin_admin, 'acfw_admin_template_settings_page', 10 );
		$this->loader->add_filter( 'acfw_general_settings_array', $acfw_plugin_admin, 'acfw_admin_general_settings_page', 10 );
		$this->loader->add_filter( 'acfw_supprot_tab_settings_array', $acfw_plugin_admin, 'acfw_admin_support_settings_page', 10 );

		// Saving tab settings.
		$this->loader->add_action( 'admin_init', $acfw_plugin_admin, 'acfw_admin_save_tab_settings' );

		/**Creation of the plugin has started */

		// Creating custom setting Tabs.
		$this->loader->add_filter( 'mwb_acfw_plugin_standard_admin_settings_tabs', $acfw_plugin_admin, 'mwb_abandon_setting_tabs', 15 );

		$this->loader->add_filter( 'mwb_acfw_plugin_standard_admin_settings_tabs', $acfw_plugin_admin, 'mwb_abandon_setting_tabs', 15 );

		// Saving Email tab settings.
		$this->loader->add_action( 'admin_init', $acfw_plugin_admin, 'mwb_save_email_tab_settings' );
		// functin to get id data.
		$this->loader->add_action( 'wp_ajax_nopriv_save_mail_atc', $acfw_plugin_admin, 'save_mail_atc' );
		$this->loader->add_action( 'wp_ajax_nopriv_get_exit_location', $acfw_plugin_admin, 'get_exit_location' );
		$this->loader->add_action( 'wp_ajax_abdn_cart_viewing_cart_from_quick_view', $acfw_plugin_admin, 'abdn_cart_viewing_cart_from_quick_view' );

		$this->loader->add_action( 'wp_ajax_get_some', $acfw_plugin_admin, 'get_data' );
		$this->loader->add_action( 'wp_ajax_bulk_delete', $acfw_plugin_admin, 'bulk_delete' );

	}
	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function abandoned_cart_for_woocommerce_common_hooks() {

		$acfw_plugin_common = new Abandoned_Cart_For_Woocommerce_Common( $this->acfw_get_plugin_name(), $this->acfw_get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $acfw_plugin_common, 'acfw_common_enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $acfw_plugin_common, 'acfw_common_enqueue_scripts' );
		// scheduling custom time cron for checking the cart status and updating them to 1.
		$this->loader->add_action( 'init', $acfw_plugin_common, 'mwb_schedule_check_cart_status' );
		$this->loader->add_action( 'mwb_schedule_first_cron', $acfw_plugin_common, 'mwb_check_status' );

		$this->loader->add_action( 'send_email_hook', $acfw_plugin_common, 'mwb_mail_sent', 10, 3 );
		$this->loader->add_action( 'mwb_acfw_second_cron', $acfw_plugin_common, 'abdn_cron_callback_daily' );
		$this->loader->add_action( 'init', $acfw_plugin_common, 'abdn_daily_cart_cron_schedule' );
		$this->loader->add_action( 'init', $acfw_plugin_common, 'mwb_third_abdn_daily_cart_cron_schedule' );
		$this->loader->add_action( 'init', $acfw_plugin_common, 'send_third' );

		$this->loader->add_action( 'send_second_mail_hook', $acfw_plugin_common, 'mwb_mail_sent_second', 10, 2 );
		$this->loader->add_action( 'send_third_mail_hook', $acfw_plugin_common, 'mwb_mail_sent_third', 10, 2 );

		$this->loader->add_filter( 'wp_mail_content_type', $acfw_plugin_common, 'set_type_wp_mail' );

		// scheduling custom time cron for deleting the history after some time.
		$this->loader->add_action( 'init', $acfw_plugin_common, 'mwb_delete_ac_history_limited_time' );
		// $this->loader->add_filter( 'cron_schedules', $acfw_plugin_common, 'mwb_add_cron_deletion' );
		$this->loader->add_action( 'mwb_schedule_del_cron', $acfw_plugin_common, 'mwb_del_data_of_ac' );
		$this->loader->add_filter( 'cron_schedules', $acfw_plugin_common, 'mwb_add_cron_interval' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function abandoned_cart_for_woocommerce_public_hooks() {

		$acfw_plugin_public = new Abandoned_Cart_For_Woocommerce_Public( $this->acfw_get_plugin_name(), $this->acfw_get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $acfw_plugin_public, 'acfw_public_enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $acfw_plugin_public, 'acfw_public_enqueue_scripts' );

		// Creation of plugin  hooks

		// Hook to track user's Cart.
		$this->loader->add_action( 'woocommerce_add_to_cart', $acfw_plugin_public, 'mwb_insert_add_to_cart' );

		$this->loader->add_action( 'wp_body_open', $acfw_plugin_public, 'add_tocart_popup' );

		// functin to get id data.
		// $this->loader->add_action( 'wp_ajax_save_mail_atc', $acfw_plugin_public, 'save_mail_atc' );
		// // functin to get id data.
		// $this->loader->add_action( 'wp_ajax_nopriv_save_mail_atc', $acfw_plugin_public, 'save_mail_atc' );

			// This function will be used to generate random cookies to fetch the user data.
			$this->loader->add_action( 'init', $acfw_plugin_public, 'mwb_generate_random_cookie' );

			$this->loader->add_action( 'init', $acfw_plugin_public, 'check_cart' );
			$this->loader->add_action( 'woocommerce_check_cart_items', $acfw_plugin_public, 'mwb_update_abandobed_cart' );
			$this->loader->add_action( 'woocommerce_account_content', $acfw_plugin_public, 'mwb_update_cart_while_login' );

			$this->loader->add_action( 'woocommerce_thankyou', $acfw_plugin_public, 'mwb_ac_conversion' );
			// Hook to capture mail from checkout page
			// $this->loader->add_action( 'woocommerce_after_checkout_billing_form', $acfw_plugin_public, 'mwb_get_mail_from_checkout' );
			// $this->loader->add_action( 'init', $acfw_plugin_public, 'mwb_send' );
			// $this->loader->add_action( 'send_custom_mail', $acfw_plugin_public, 'checking_cron' );
			// //JAaadu
			// $this->loader->add_action( 'wp_ajax_nopriv_save_mail_checkout', $acfw_plugin_public, 'save_mail' );

			// $this->loader->add_action( 'mwb_check_abandoned_status', $acfw_plugin_public, 'mwb_callback_abandoned_status' );
			// hok to get data from the woocommerce core

			// $this->loader->add_action( 'init', $acfw_plugin_public, 'mwb_callback_abandoned_status1' );
		// $this->loader->add_action( 'woocommerce_check_cart_items', $acfw_plugin_public, 'check_hello' );
	}


	/**
	 * Register all of the hooks related to the api functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function abandoned_cart_for_woocommerce_api_hooks() {

		$acfw_plugin_api = new Abandoned_Cart_For_Woocommerce_Rest_Api( $this->acfw_get_plugin_name(), $this->acfw_get_version() );

		$this->loader->add_action( 'rest_api_init', $acfw_plugin_api, 'mwb_acfw_add_endpoint' );

	}


	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function acfw_run() {
		$this->loader->acfw_run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function acfw_get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Abandoned_Cart_For_Woocommerce_Loader    Orchestrates the hooks of the plugin.
	 */
	public function acfw_get_loader() {
		return $this->loader;
	}


	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Abandoned_Cart_For_Woocommerce_Onboard    Orchestrates the hooks of the plugin.
	 */
	public function acfw_get_onboard() {
		return $this->acfw_onboard;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function acfw_get_version() {
		return $this->version;
	}

	/**
	 * Predefined default mwb_acfw_plug tabs.
	 *
	 * @return  Array       An key=>value pair of Abandoned Cart for WooCommerce tabs.
	 */
	public function mwb_acfw_plug_default_tabs() {

		$acfw_default_tabs = array();

		$acfw_default_tabs['abandoned-cart-for-woocommerce-general'] = array(
			'title'       => esc_html__( 'General Setting', 'abandoned-cart-for-woocommerce' ),
			'name'        => 'abandoned-cart-for-woocommerce-general',
		);
		$acfw_default_tabs = apply_filters( 'mwb_acfw_plugin_standard_admin_settings_tabs', $acfw_default_tabs );

		$acfw_default_tabs['abandoned-cart-for-woocommerce-system-status'] = array(
			'title'       => esc_html__( 'System Status', 'abandoned-cart-for-woocommerce' ),
			'name'        => 'abandoned-cart-for-woocommerce-system-status',
		);
		$acfw_default_tabs['abandoned-cart-for-woocommerce-template'] = array(
			'title'       => esc_html__( 'Templates', 'abandoned-cart-for-woocommerce' ),
			'name'        => 'abandoned-cart-for-woocommerce-template',
		);

		return $acfw_default_tabs;
	}

	/**
	 * Locate and load appropriate tempate.
	 *
	 * @since   1.0.0
	 * @param string $path path file for inclusion.
	 * @param array  $params parameters to pass to the file for access.
	 */
	public function mwb_acfw_plug_load_template( $path, $params = array() ) {

		$acfw_file_path = ABANDONED_CART_FOR_WOOCOMMERCE_DIR_PATH . $path;

		if ( file_exists( $acfw_file_path ) ) {

			include $acfw_file_path;
		} else {

			/* translators: %s: file path */
			$acfw_notice = sprintf( esc_html__( 'Unable to locate file at location "%s". Some features may not work properly in this plugin. Please contact us!', 'abandoned-cart-for-woocommerce' ), $acfw_file_path );
			$this->mwb_acfw_plug_admin_notice( $acfw_notice, 'error' );
		}
	}

	/**
	 * Show admin notices.
	 *
	 * @param  string $acfw_message    Message to display.
	 * @param  string $type       notice type, accepted values - error/update/update-nag.
	 * @since  1.0.0
	 */
	public static function mwb_acfw_plug_admin_notice( $acfw_message, $type = 'error' ) {

		$acfw_classes = 'notice ';

		switch ( $type ) {

			case 'update':
				$acfw_classes .= 'updated is-dismissible';
				break;

			case 'update-nag':
				$acfw_classes .= 'update-nag is-dismissible';
				break;

			case 'success':
				$acfw_classes .= 'notice-success is-dismissible';
				break;

			default:
				$acfw_classes .= 'notice-error is-dismissible';
		}

		$acfw_notice  = '<div class="' . esc_attr( $acfw_classes ) . ' mwb-errorr-8">';
		$acfw_notice .= '<p>' . esc_html( $acfw_message ) . '</p>';
		$acfw_notice .= '</div>';

		echo wp_kses_post( $acfw_notice );
	}


	/**
	 * Show wordpress and server info.
	 *
	 * @return  Array $acfw_system_data       returns array of all wordpress and server related information.
	 * @since  1.0.0
	 */
	public function mwb_acfw_plug_system_status() {
		global $wpdb;
		$acfw_system_status = array();
		$acfw_wordpress_status = array();
		$acfw_system_data = array();

		// Get the web server.
		$acfw_system_status['web_server'] = isset( $_SERVER['SERVER_SOFTWARE'] ) ? sanitize_text_field( wp_unslash( $_SERVER['SERVER_SOFTWARE'] ) ) : '';

		// Get PHP version.
		$acfw_system_status['php_version'] = function_exists( 'phpversion' ) ? phpversion() : __( 'N/A (phpversion function does not exist)', 'abandoned-cart-for-woocommerce' );

		// Get the server's IP address.
		$acfw_system_status['server_ip'] = isset( $_SERVER['SERVER_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['SERVER_ADDR'] ) ) : '';

		// Get the server's port.
		$acfw_system_status['server_port'] = isset( $_SERVER['SERVER_PORT'] ) ? sanitize_text_field( wp_unslash( $_SERVER['SERVER_PORT'] ) ) : '';

		// Get the uptime.
		$acfw_system_status['uptime'] = function_exists( 'exec' ) ? @exec( 'uptime -p' ) : __( 'N/A (make sure exec function is enabled)', 'abandoned-cart-for-woocommerce' );

		// Get the server path.
		$acfw_system_status['server_path'] = defined( 'ABSPATH' ) ? ABSPATH : __( 'N/A (ABSPATH constant not defined)', 'abandoned-cart-for-woocommerce' );

		// Get the OS.
		$acfw_system_status['os'] = function_exists( 'php_uname' ) ? php_uname( 's' ) : __( 'N/A (php_uname function does not exist)', 'abandoned-cart-for-woocommerce' );

		// Get WordPress version.
		$acfw_wordpress_status['wp_version'] = function_exists( 'get_bloginfo' ) ? get_bloginfo( 'version' ) : __( 'N/A (get_bloginfo function does not exist)', 'abandoned-cart-for-woocommerce' );

		// Get and count active WordPress plugins.
		$acfw_wordpress_status['wp_active_plugins'] = function_exists( 'get_option' ) ? count( get_option( 'active_plugins' ) ) : __( 'N/A (get_option function does not exist)', 'abandoned-cart-for-woocommerce' );

		// See if this site is multisite or not.
		$acfw_wordpress_status['wp_multisite'] = function_exists( 'is_multisite' ) && is_multisite() ? __( 'Yes', 'abandoned-cart-for-woocommerce' ) : __( 'No', 'abandoned-cart-for-woocommerce' );

		// See if WP Debug is enabled.
		$acfw_wordpress_status['wp_debug_enabled'] = defined( 'WP_DEBUG' ) ? __( 'Yes', 'abandoned-cart-for-woocommerce' ) : __( 'No', 'abandoned-cart-for-woocommerce' );

		// See if WP Cache is enabled.
		$acfw_wordpress_status['wp_cache_enabled'] = defined( 'WP_CACHE' ) ? __( 'Yes', 'abandoned-cart-for-woocommerce' ) : __( 'No', 'abandoned-cart-for-woocommerce' );

		// Get the total number of WordPress users on the site.
		$acfw_wordpress_status['wp_users'] = function_exists( 'count_users' ) ? count_users() : __( 'N/A (count_users function does not exist)', 'abandoned-cart-for-woocommerce' );

		// Get the number of published WordPress posts.
		$acfw_wordpress_status['wp_posts'] = wp_count_posts()->publish >= 1 ? wp_count_posts()->publish : __( '0', 'abandoned-cart-for-woocommerce' );

		// Get PHP memory limit.
		$acfw_system_status['php_memory_limit'] = function_exists( 'ini_get' ) ? (int) ini_get( 'memory_limit' ) : __( 'N/A (ini_get function does not exist)', 'abandoned-cart-for-woocommerce' );

		// Get the PHP error log path.
		$acfw_system_status['php_error_log_path'] = ! ini_get( 'error_log' ) ? __( 'N/A', 'abandoned-cart-for-woocommerce' ) : ini_get( 'error_log' );

		// Get PHP max upload size.
		$acfw_system_status['php_max_upload'] = function_exists( 'ini_get' ) ? (int) ini_get( 'upload_max_filesize' ) : __( 'N/A (ini_get function does not exist)', 'abandoned-cart-for-woocommerce' );

		// Get PHP max post size.
		$acfw_system_status['php_max_post'] = function_exists( 'ini_get' ) ? (int) ini_get( 'post_max_size' ) : __( 'N/A (ini_get function does not exist)', 'abandoned-cart-for-woocommerce' );

		// Get the PHP architecture.
		if ( PHP_INT_SIZE == 4 ) {
			$acfw_system_status['php_architecture'] = '32-bit';
		} elseif ( PHP_INT_SIZE == 8 ) {
			$acfw_system_status['php_architecture'] = '64-bit';
		} else {
			$acfw_system_status['php_architecture'] = 'N/A';
		}

		// Get server host name.
		$acfw_system_status['server_hostname'] = function_exists( 'gethostname' ) ? gethostname() : __( 'N/A (gethostname function does not exist)', 'abandoned-cart-for-woocommerce' );

		// Show the number of processes currently running on the server.
		$acfw_system_status['processes'] = function_exists( 'exec' ) ? @exec( 'ps aux | wc -l' ) : __( 'N/A (make sure exec is enabled)', 'abandoned-cart-for-woocommerce' );

		// Get the memory usage.
		$acfw_system_status['memory_usage'] = function_exists( 'memory_get_peak_usage' ) ? round( memory_get_peak_usage( true ) / 1024 / 1024, 2 ) : 0;

		// Get CPU usage.
		// Check to see if system is Windows, if so then use an alternative since sys_getloadavg() won't work.
		if ( stristr( PHP_OS, 'win' ) ) {
			$acfw_system_status['is_windows'] = true;
			$acfw_system_status['windows_cpu_usage'] = function_exists( 'exec' ) ? @exec( 'wmic cpu get loadpercentage /all' ) : __( 'N/A (make sure exec is enabled)', 'abandoned-cart-for-woocommerce' );
		}

		// Get the memory limit.
		$acfw_system_status['memory_limit'] = function_exists( 'ini_get' ) ? (int) ini_get( 'memory_limit' ) : __( 'N/A (ini_get function does not exist)', 'abandoned-cart-for-woocommerce' );

		// Get the PHP maximum execution time.
		$acfw_system_status['php_max_execution_time'] = function_exists( 'ini_get' ) ? ini_get( 'max_execution_time' ) : __( 'N/A (ini_get function does not exist)', 'abandoned-cart-for-woocommerce' );

		// Get outgoing IP address.
		$acfw_system_status['outgoing_ip'] = function_exists( 'file_get_contents' ) ? file_get_contents( 'http://ipecho.net/plain' ) : __( 'N/A (file_get_contents function does not exist)', 'abandoned-cart-for-woocommerce' );

		$acfw_system_data['php'] = $acfw_system_status;
		$acfw_system_data['wp'] = $acfw_wordpress_status;

		return $acfw_system_data;
	}

	/**
	 * Generate html components.
	 *
	 * @param  string $acfw_components    html to display.
	 * @since  1.0.0
	 */
	public function mwb_acfw_plug_generate_html( $acfw_components = array() ) {
		if ( is_array( $acfw_components ) && ! empty( $acfw_components ) ) {
			foreach ( $acfw_components as $acfw_component ) {
				switch ( $acfw_component['type'] ) {

					case 'hidden':
					case 'number':
					case 'email':
					case 'text':
						?>
					<div class="mwb-form-group mwb-acfw-<?php echo esc_attr( $acfw_component['type'] ); ?>">
						<div class="mwb-form-group__label">
							<label for="<?php echo esc_attr( $acfw_component['id'] ); ?>" class="mwb-form-label"><?php echo esc_html( $acfw_component['title'] ); // WPCS: XSS ok. ?></label>
						</div>
						<div class="mwb-form-group__control">
							<label class="mdc-text-field mdc-text-field--outlined">
								<span class="mdc-notched-outline">
									<span class="mdc-notched-outline__leading"></span>
									<span class="mdc-notched-outline__notch">
										<?php if ( 'number' != $acfw_component['type'] ) { ?>
											<span class="mdc-floating-label" id="my-label-id" style=""><?php echo esc_attr( $acfw_component['placeholder'] ); ?></span>
										<?php } ?>
									</span>
									<span class="mdc-notched-outline__trailing"></span>
								</span>
								<input
								class="mdc-text-field__input <?php echo esc_attr( $acfw_component['class'] ); ?>"
								name="<?php echo ( isset( $acfw_component['name'] ) ? esc_html( $acfw_component['name'] ) : esc_html( $acfw_component['id'] ) ); ?>"
								id="<?php echo esc_attr( $acfw_component['id'] ); ?>"
								type="<?php echo esc_attr( $acfw_component['type'] ); ?>"
								<?php if ( 'number' === $acfw_component['type'] ) { ?>
								min = "<?php echo ( isset( $acfw_component['min'] ) ? esc_html( $acfw_component['min'] ) : '' ); ?>"
								<?php } ?>
								value="<?php echo esc_attr( $acfw_component['value'] ); ?>"
								placeholder="<?php echo esc_attr( $acfw_component['placeholder'] ); ?>"
								>
							</label>
							<div class="mdc-text-field-helper-line">
								<div class="mdc-text-field-helper-text--persistent mwb-helper-text" id="" aria-hidden="true"><?php echo esc_attr( $acfw_component['description'] ); ?></div>
							</div>
						</div>
					</div>
						<?php
						break;

					case 'password':
						?>
					<div class="mwb-form-group">
						<div class="mwb-form-group__label">
							<label for="<?php echo esc_attr( $acfw_component['id'] ); ?>" class="mwb-form-label"><?php echo esc_html( $acfw_component['title'] ); // WPCS: XSS ok. ?></label>
						</div>
						<div class="mwb-form-group__control">
							<label class="mdc-text-field mdc-text-field--outlined mdc-text-field--with-trailing-icon">
								<span class="mdc-notched-outline">
									<span class="mdc-notched-outline__leading"></span>
									<span class="mdc-notched-outline__notch">
									</span>
									<span class="mdc-notched-outline__trailing"></span>
								</span>
								<input
								class="mdc-text-field__input <?php echo esc_attr( $acfw_component['class'] ); ?> mwb-form__password"
								name="<?php echo ( isset( $acfw_component['name'] ) ? esc_html( $acfw_component['name'] ) : esc_html( $acfw_component['id'] ) ); ?>"
								id="<?php echo esc_attr( $acfw_component['id'] ); ?>"
								type="<?php echo esc_attr( $acfw_component['type'] ); ?>"
								value="<?php echo esc_attr( $acfw_component['value'] ); ?>"
								placeholder="<?php echo esc_attr( $acfw_component['placeholder'] ); ?>"
								>
								<i class="material-icons mdc-text-field__icon mdc-text-field__icon--trailing mwb-password-hidden" tabindex="0" role="button">visibility</i>
							</label>
							<div class="mdc-text-field-helper-line">
								<div class="mdc-text-field-helper-text--persistent mwb-helper-text" id="" aria-hidden="true"><?php echo esc_attr( $acfw_component['description'] ); ?></div>
							</div>
						</div>
					</div>
						<?php
						break;

					case 'textarea':
						?>
					<div class="mwb-form-group">
						<div class="mwb-form-group__label">
							<label class="mwb-form-label" for="<?php echo esc_attr( $acfw_component['id'] ); ?>"><?php echo esc_attr( $acfw_component['title'] ); ?></label>
						</div>
						<div class="mwb-form-group__control">
							<label class="mdc-text-field mdc-text-field--outlined mdc-text-field--textarea"  	for="text-field-hero-input">
								<span class="mdc-notched-outline">
									<span class="mdc-notched-outline__leading"></span>
									<span class="mdc-notched-outline__notch">
										<span class="mdc-floating-label"><?php echo esc_attr( $acfw_component['placeholder'] ); ?></span>
									</span>
									<span class="mdc-notched-outline__trailing"></span>
								</span>
								<span class="mdc-text-field__resizer">
									<textarea class="mdc-text-field__input <?php echo esc_attr( $acfw_component['class'] ); ?>" rows="2" cols="25" aria-label="Label" name="<?php echo ( isset( $acfw_component['name'] ) ? esc_html( $acfw_component['name'] ) : esc_html( $acfw_component['id'] ) ); ?>" id="<?php echo esc_attr( $acfw_component['id'] ); ?>" placeholder="<?php echo esc_attr( $acfw_component['placeholder'] ); ?>"><?php echo esc_textarea( $acfw_component['value'] ); // WPCS: XSS ok. ?></textarea>
								</span>
							</label>

						</div>
					</div>

						<?php
						break;

					case 'select':
					case 'multiselect':
						?>
					<div class="mwb-form-group">
						<div class="mwb-form-group__label">
							<label class="mwb-form-label" for="<?php echo esc_attr( $acfw_component['id'] ); ?>"><?php echo esc_html( $acfw_component['title'] ); ?></label>
						</div>
						<div class="mwb-form-group__control">
							<div class="mwb-form-select">
								<select id="<?php echo esc_attr( $acfw_component['id'] ); ?>" name="<?php echo ( isset( $acfw_component['name'] ) ? esc_html( $acfw_component['name'] ) : esc_html( $acfw_component['id'] ) ); ?><?php echo ( 'multiselect' === $acfw_component['type'] ) ? '[]' : ''; ?>" id="<?php echo esc_attr( $acfw_component['id'] ); ?>" class="mdl-textfield__input <?php echo esc_attr( $acfw_component['class'] ); ?>" <?php echo 'multiselect' === $acfw_component['type'] ? 'multiple="multiple"' : ''; ?> >
									<?php
									foreach ( $acfw_component['options'] as $acfw_key => $acfw_val ) {
										?>
										<option value="<?php echo esc_attr( $acfw_key ); ?>"
											<?php
											if ( is_array( $acfw_component['value'] ) ) {
												selected( in_array( (string) $acfw_key, $acfw_component['value'], true ), true );
											} else {
												selected( $acfw_component['value'], (string) $acfw_key );
											}
											?>
											>
											<?php echo esc_html( $acfw_val ); ?>
										</option>
										<?php
									}
									?>
								</select>
								<label class="mdl-textfield__label" for="octane"><?php echo esc_html( $acfw_component['description'] ); ?></label>
							</div>
						</div>
					</div>

						<?php
						break;

					case 'checkbox':
						?>
					<div class="mwb-form-group">
						<div class="mwb-form-group__label">
							<label for="<?php echo esc_attr( $acfw_component['id'] ); ?>" class="mwb-form-label"><?php echo esc_html( $acfw_component['title'] ); ?></label>
						</div>
						<div class="mwb-form-group__control mwb-pl-4">
							<div class="mdc-form-field">
								<div class="mdc-checkbox">
									<input
									name="<?php echo ( isset( $acfw_component['name'] ) ? esc_html( $acfw_component['name'] ) : esc_html( $acfw_component['id'] ) ); ?>"
									id="<?php echo esc_attr( $acfw_component['id'] ); ?>"
									type="checkbox"
									class="mdc-checkbox__native-control <?php echo esc_attr( isset( $acfw_component['class'] ) ? $acfw_component['class'] : '' ); ?>"
									value="<?php echo esc_attr( $acfw_component['value'] ); ?>"
									<?php checked( $acfw_component['value'], '1' ); ?>
									/>
									<div class="mdc-checkbox__background">
										<svg class="mdc-checkbox__checkmark" viewBox="0 0 24 24">
											<path class="mdc-checkbox__checkmark-path" fill="none" d="M1.73,12.91 8.1,19.28 22.79,4.59"/>
										</svg>
										<div class="mdc-checkbox__mixedmark"></div>
									</div>
									<div class="mdc-checkbox__ripple"></div>
								</div>
								<label for="checkbox-1"><?php echo esc_html( $acfw_component['description'] ); // WPCS: XSS ok. ?></label>
							</div>
						</div>
					</div>
						<?php
						break;

					case 'radio':
						?>
					<div class="mwb-form-group">
						<div class="mwb-form-group__label">
							<label for="<?php echo esc_attr( $acfw_component['id'] ); ?>" class="mwb-form-label"><?php echo esc_html( $acfw_component['title'] ); ?></label>
						</div>
						<div class="mwb-form-group__control mwb-pl-4">
							<div class="mwb-flex-col">
								<?php
								foreach ( $acfw_component['options'] as $acfw_radio_key => $acfw_radio_val ) {
									?>
									<div class="mdc-form-field">
										<div class="mdc-radio">
											<input
											name="<?php echo ( isset( $acfw_component['name'] ) ? esc_html( $acfw_component['name'] ) : esc_html( $acfw_component['id'] ) ); ?>"
											value="<?php echo esc_attr( $acfw_radio_key ); ?>"
											type="radio"
											class="mdc-radio__native-control <?php echo esc_attr( $acfw_component['class'] ); ?>"
											<?php checked( $acfw_radio_key, $acfw_component['value'] ); ?>
											>
											<div class="mdc-radio__background">
												<div class="mdc-radio__outer-circle"></div>
												<div class="mdc-radio__inner-circle"></div>
											</div>
											<div class="mdc-radio__ripple"></div>
										</div>
										<label for="radio-1"><?php echo esc_html( $acfw_radio_val ); ?></label>
									</div>
									<?php
								}
								?>
							</div>
						</div>
					</div>
						<?php
						break;

					case 'radio-switch':
						?>

					<div class="mwb-form-group">
						<div class="mwb-form-group__label">
							<label for="" class="mwb-form-label"><?php echo esc_html( $acfw_component['title'] ); ?></label>
						</div>
						<div class="mwb-form-group__control">
							<div>
								<div class="mdc-switch">
									<div class="mdc-switch__track"></div>
									<div class="mdc-switch__thumb-underlay">
										<div class="mdc-switch__thumb"></div>
										<input name="<?php echo ( isset( $acfw_component['name'] ) ? esc_html( $acfw_component['name'] ) : esc_html( $acfw_component['id'] ) ); ?>" type="checkbox" id="<?php echo esc_html( $acfw_component['id'] ); ?>" value="on" class="mdc-switch__native-control" role="switch" aria-checked="
																<?php
																if ( 'on' == $acfw_component['value'] ) {
																	echo 'true';
																} else {
																	echo 'false';
																}
																?>
										"
										<?php checked( $acfw_component['value'], 'on' ); ?>
										>
									</div>
								</div>
							</div>
						</div>
					</div>
						<?php
						break;

					case 'button':
						?>
					<div class="mwb-form-group">
						<div class="mwb-form-group__label"></div>
						<div class="mwb-form-group__control">
							<button class="mdc-button mdc-button--raised" name="<?php echo ( isset( $acfw_component['name'] ) ? esc_html( $acfw_component['name'] ) : esc_html( $acfw_component['id'] ) ); ?>"
								id="<?php echo esc_attr( $acfw_component['id'] ); ?>" onclick="return check_validation()"> <span class="mdc-button__ripple"></span>
								<span class="mdc-button__label"><?php echo esc_attr( $acfw_component['button_text'] ); ?></span>
							</button>
						</div>
					</div>

						<?php
						break;

					case 'multi':
						?>
						<div class="mwb-form-group mwb-isfw-<?php echo esc_attr( $acfw_component['type'] ); ?>">
							<div class="mwb-form-group__label">
								<label for="<?php echo esc_attr( $acfw_component['id'] ); ?>" class="mwb-form-label"><?php echo esc_html( $acfw_component['title'] ); // WPCS: XSS ok. ?></label>
								</div>
								<div class="mwb-form-group__control">
								<?php
								foreach ( $acfw_component['value'] as $component ) {
									?>
										<label class="mdc-text-field mdc-text-field--outlined">
											<span class="mdc-notched-outline">
												<span class="mdc-notched-outline__leading"></span>
												<span class="mdc-notched-outline__notch">
													<?php if ( 'number' != $component['type'] ) { ?>
														<span class="mdc-floating-label" id="my-label-id" style=""><?php echo esc_attr( $component['placeholder'] ); ?></span>
													<?php } ?>
												</span>
												<span class="mdc-notched-outline__trailing"></span>
											</span>
											<input
											class="mdc-text-field__input <?php echo esc_attr( $component['class'] ); ?>"
											name="<?php echo esc_attr( $component['id'] ); ?>"
											id="<?php echo esc_attr( $component['id'] ); ?>"
											type="<?php echo esc_attr( $component['type'] ); ?>"
											value="<?php echo esc_attr( $component['value'] ); ?>"
											placeholder="<?php echo esc_attr( $component['placeholder'] ); ?>"
											<?php echo esc_attr( ( 'number' === $component['type'] ) ? 'max=10 min=0' : '' ); ?>
											>
										</label>
							<?php } ?>
								<div class="mdc-text-field-helper-line">
									<div class="mdc-text-field-helper-text--persistent mwb-helper-text" id="" aria-hidden="true"><?php echo esc_html( $acfw_component['description'] ); ?></div>
								</div>
							</div>
						</div>
							<?php
						break;
					case 'color':
					case 'date':
					case 'file':
						?>
						<div class="mwb-form-group mwb-isfw-<?php echo esc_attr( $acfw_component['type'] ); ?>">
							<div class="mwb-form-group__label">
								<label for="<?php echo esc_attr( $acfw_component['id'] ); ?>" class="mwb-form-label"><?php echo esc_html( $acfw_component['title'] ); // WPCS: XSS ok. ?></label>
							</div>
							<div class="mwb-form-group__control">
								<label class="mdc-text-field mdc-text-field--outlined">
									<input
									class="<?php echo esc_attr( $acfw_component['class'] ); ?>"
									name="<?php echo esc_attr( $acfw_component['id'] ); ?>"
									id="<?php echo esc_attr( $acfw_component['id'] ); ?>"
									type="<?php echo esc_attr( $acfw_component['type'] ); ?>"
									value="<?php echo esc_attr( $acfw_component['value'] ); ?>"
									<?php echo esc_html( ( 'date' === $acfw_component['type'] ) ? 'max=' . date( 'Y-m-d', strtotime( date( 'Y-m-d', mktime() ) . ' + 365 day' ) ) . ' ' . 'min=' . date( 'Y-m-d' ) . '' : '' ); ?>
									>
								</label>
								<div class="mdc-text-field-helper-line">
									<div class="mdc-text-field-helper-text--persistent mwb-helper-text" id="" aria-hidden="true"><?php echo esc_attr( $acfw_component['description'] ); ?></div>
								</div>
							</div>
						</div>
						<?php
						break;

					case 'submit':
						?>
					<tr valign="top">
						<td scope="row">
							<input type="submit" class="button button-primary"
							name="<?php echo ( isset( $acfw_component['name'] ) ? esc_html( $acfw_component['name'] ) : esc_html( $acfw_component['id'] ) ); ?>"
							id="<?php echo esc_attr( $acfw_component['id'] ); ?>"
							value="<?php echo esc_attr( $acfw_component['button_text'] ); ?>"
							/>
						</td>
					</tr>
						<?php
						break;

					default:
						break;
				}
			}
		}
	}
}
