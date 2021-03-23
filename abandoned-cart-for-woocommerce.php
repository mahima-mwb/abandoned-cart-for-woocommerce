<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://makewebbetter.com/
 * @since             1.0.0
 * @package           Abandoned_Cart_For_Woocommerce
 *
 * @wordpress-plugin
 * Plugin Name:       Abandoned Cart for WooCommerce
 * Plugin URI:        https://wordpress.org/plugins/search/abandoned-cart-for-woocommerce/
 * Description:       This Plugin Will Track abandoned carts of woocommerce shop's for both guest and registered user's and it will help them to Successfully conversion of the abandoned cart.
 * Version:           1.0.0
 * Author:            MakeWebBetter
 * Author URI:        https://makewebbetter.com/
 * Text Domain:       abandoned-cart-for-woocommerce
 * Domain Path:       /languages
 *
 * Requires at least: 4.6
 * Tested up to:      5.7
 *
 * WC requires at least: 3.0.0
 * WC tested up to:    5.1.0
 *
 * License:           GNU General Public License v3.0
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.html
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}
$mwb_abn_cart_activated = false;

/**
 * Checking for activation of main extensions of HubSpot on singlesite
 */

if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) ) {

	$mwb_abn_cart_activated = true;
}

if ( $mwb_abn_cart_activated ) {

	require_once plugin_dir_path( __FILE__ ) . 'mwb-acwf-gdpr.php';

	/**
	 * Define plugin constants.
	 *
	 * @since             1.0.0
	 */
	function define_abandoned_cart_for_woocommerce_constants() {

		abandoned_cart_for_woocommerce_constants( 'ABANDONED_CART_FOR_WOOCOMMERCE_VERSION', '1.0.0' );
		abandoned_cart_for_woocommerce_constants( 'ABANDONED_CART_FOR_WOOCOMMERCE_DIR_PATH', plugin_dir_path( __FILE__ ) );
		abandoned_cart_for_woocommerce_constants( 'ABANDONED_CART_FOR_WOOCOMMERCE_DIR_URL', plugin_dir_url( __FILE__ ) );
		abandoned_cart_for_woocommerce_constants( 'ABANDONED_CART_FOR_WOOCOMMERCE_SERVER_URL', 'https://makewebbetter.com' );
		abandoned_cart_for_woocommerce_constants( 'ABANDONED_CART_FOR_WOOCOMMERCE_ITEM_REFERENCE', 'Abandoned Cart for WooCommerce' );
	}


	/**
	 * Callable function for defining plugin constants.
	 *
	 * @param   String $key    Key for contant.
	 * @param   String $value   value for contant.
	 * @since             1.0.0
	 */
	function abandoned_cart_for_woocommerce_constants( $key, $value ) {

		if ( ! defined( $key ) ) {

			define( $key, $value );
		}
	}

	/**
	 * The code that runs during plugin activation.
	 * This action is documented in includes/class-abandoned-cart-for-woocommerce-activator.php
	 */
	function activate_abandoned_cart_for_woocommerce() {
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-abandoned-cart-for-woocommerce-activator.php';
		Abandoned_Cart_For_Woocommerce_Activator::abandoned_cart_for_woocommerce_activate();
		$mwb_acfw_active_plugin = get_option( 'mwb_all_plugins_active', false );
		if ( is_array( $mwb_acfw_active_plugin ) && ! empty( $mwb_acfw_active_plugin ) ) {
			$mwb_acfw_active_plugin['abandoned-cart-for-woocommerce'] = array(
				'plugin_name' => __( 'Abandoned Cart for WooCommerce', 'abandoned-cart-for-woocommerce' ),
				'active' => '1',
			);
		} else {
			$mwb_acfw_active_plugin = array();
			$mwb_acfw_active_plugin['abandoned-cart-for-woocommerce'] = array(
				'plugin_name' => __( 'Abandoned Cart for WooCommerce', 'abandoned-cart-for-woocommerce' ),
				'active' => '1',
			);
		}
		update_option( 'mwb_all_plugins_active', $mwb_acfw_active_plugin );
	}

	/**
	 * The code that runs during plugin deactivation.
	 * This action is documented in includes/class-abandoned-cart-for-woocommerce-deactivator.php
	 */
	function deactivate_abandoned_cart_for_woocommerce() {
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-abandoned-cart-for-woocommerce-deactivator.php';
		Abandoned_Cart_For_Woocommerce_Deactivator::abandoned_cart_for_woocommerce_deactivate();
		$mwb_acfw_deactive_plugin = get_option( 'mwb_all_plugins_active', false );
		if ( is_array( $mwb_acfw_deactive_plugin ) && ! empty( $mwb_acfw_deactive_plugin ) ) {
			foreach ( $mwb_acfw_deactive_plugin as $mwb_acfw_deactive_key => $mwb_acfw_deactive ) {
				if ( 'abandoned-cart-for-woocommerce' === $mwb_acfw_deactive_key ) {
					$mwb_acfw_deactive_plugin[ $mwb_acfw_deactive_key ]['active'] = '0';
				}
			}
		}
		update_option( 'mwb_all_plugins_active', $mwb_acfw_deactive_plugin );
	}

	register_activation_hook( __FILE__, 'activate_abandoned_cart_for_woocommerce' );
	register_deactivation_hook( __FILE__, 'deactivate_abandoned_cart_for_woocommerce' );

	/**
	 * The core plugin class that is used to define internationalization,
	 * admin-specific hooks, and public-facing site hooks.
	 */
	require plugin_dir_path( __FILE__ ) . 'includes/class-abandoned-cart-for-woocommerce.php';


	/**
	 * Begins execution of the plugin.
	 *
	 * Since everything within the plugin is registered via hooks,
	 * then kicking off the plugin from this point in the file does
	 * not affect the page life cycle.
	 *
	 * @since    1.0.0
	 */
	function run_abandoned_cart_for_woocommerce() {
		define_abandoned_cart_for_woocommerce_constants();

		$acfw_plugin_standard = new Abandoned_Cart_For_Woocommerce();
		$acfw_plugin_standard->acfw_run();
		$GLOBALS['acfw_mwb_acfw_obj'] = $acfw_plugin_standard;
		$GLOBALS['error_notice']      = true;

	}
	run_abandoned_cart_for_woocommerce();


	// Add settings link on plugin page.
	add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'abandoned_cart_for_woocommerce_settings_link' );

	/**
	 * Settings link.
	 *
	 * @since    1.0.0
	 * @param   Array $links    Settings link array.
	 */
	function abandoned_cart_for_woocommerce_settings_link( $links ) {

		$my_link = array(
			'<a href="' . admin_url( 'admin.php?page=abandoned_cart_for_woocommerce_menu' ) . '">' . __( 'Settings', 'abandoned-cart-for-woocommerce' ) . '</a>',
		);
		return array_merge( $my_link, $links );
	}
	add_action( 'woocommerce_after_checkout_billing_form', 'mwb_get_mail_from_checkout' );
	add_action( 'wp_ajax_nopriv_save_mail_checkout', 'mwb_save__guest_mail' );
	/**
	 * Function name mwb_get_mail_from_checkout
	 * this function will be used for capturing email form the checkout page.
	 *
	 * @return void
	 * @since             1.0.0
	 */
	function mwb_get_mail_from_checkout() {
		if ( ! is_user_logged_in() ) {
			?>
		<script type="text/javascript">
			function setCookie(cname, cvalue, exdays) {
				var d = new Date();
				d.setTime(d.getTime() + (exdays*24*60*60*1000));
				var expires = "expires="+ d.toUTCString();
				document.cookie = cname + "=" + cvalue + ";" + expires + "; path=/";
			}
			jQuery( 'input#billing_email' ).on( 'change', function() {
				var guest_user_email = jQuery( 'input#billing_email' ).val();
				setCookie( 'guest_checkout_mail', guest_user_email, 1 );
				var ajaxUrl = "<?php echo esc_html( admin_url() ); ?>admin-ajax.php";
				jQuery.ajax({
						url: ajaxUrl,
						type: 'POST',
						data: {
							action: 'save_mail_checkout',
							guest_user_email : guest_user_email,
						},
						success: function(data) {
							console.log( data);

						}
					});
				});

		</script>
			<?php
		}

	}
	if ( ! function_exists( 'mwb_save__guest_mail' ) ) {

		/**
		 *  Function name mwb_save__guest_mail()
		 * This Function is used to save email that has been captured from the checkuot page.
		 *
		 * @return void
		 * @since             1.0.0
		 */
		function mwb_save__guest_mail() {

			global $wpdb;
			$mwb_abadoned_key = wp_unslash( isset( $_COOKIE['mwb_cookie_data'] ) ? sanitize_text_field( $_COOKIE['mwb_cookie_data'] ): '' );
			$mail           = sanitize_text_field( wp_unslash( ! empty( $_POST['guest_user_email'] ) ? $_POST['guest_user_email'] : '' ) );
			$ip_address     = $_SERVER['REMOTE_ADDR'];

			$wpdb->update(
				$wpdb->prefix . 'mwb_abandoned_cart',
				array(
					'email' => $mail,
				),
				array(
					'ip_address' => $ip_address,
					'mwb_abandon_key' => $mwb_abadoned_key,
				)
			);
			wp_die();
		}
	}
} else {

	add_action( 'admin_notices', 'mwb_abn_cart_plugin_error_notice' );
	add_action( 'admin_init', 'mwb_abn_cart_plugin_deactivate' );


	// Checking the existance of the same name function in this file.
	if ( ! function_exists( 'mwb_abn_cart_plugin_error_notice' ) ) {
		/**
		 * Function name  mwb_abn_cart_plugin_error_notice
		 * This function will show notice while deactivating without woocommerce
		 *
		 * @return void
		 * @since             1.0.0
		 */
		function mwb_abn_cart_plugin_error_notice() {
			?>
		<div class="error notice is-dismissible">
		<p><?php esc_html_e( 'Oops! You tried activating the ABANDONED CART FOR WOOCOMMERCE. Please activate WooCommerce and then try again.', 'abandoned-cart-for-woocommerce' ); ?></p>
		</div>
			<?php
		}
	}

	// Checking the Existance of the same name funciton in the file.
	if ( ! function_exists( 'mwb_abn_cart_plugin_deactivate' ) ) {
		/**
		 * Function Name : mwb_abn_cart_plugin_deactivate.
		 * This Function will Be called at the deactivation time.
		 *
		 * @return void
		 * @since             1.0.0
		 */
		function mwb_abn_cart_plugin_deactivate() {
			deactivate_plugins( plugin_basename( __FILE__ ) );
			unset( $_GET['activate'] );
		}
	}
}

