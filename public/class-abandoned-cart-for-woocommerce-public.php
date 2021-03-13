<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Abandoned_Cart_For_Woocommerce
 * @subpackage Abandoned_Cart_For_Woocommerce/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 * namespace abandoned_cart_for_woocommerce_public.
 *
 * @package    Abandoned_Cart_For_Woocommerce
 * @subpackage Abandoned_Cart_For_Woocommerce/public
 * @author     makewebbetter <webmaster@makewebbetter.com>
 */
class Abandoned_Cart_For_Woocommerce_Public {

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
	 * @param      string $plugin_name       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function acfw_public_enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, ABANDONED_CART_FOR_WOOCOMMERCE_DIR_URL . 'public/src/scss/abandoned-cart-for-woocommerce-public.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'wp-jquery-ui-dialog' );

		wp_enqueue_style( 'mwb_acfw_custom', ABANDONED_CART_FOR_WOOCOMMERCE_DIR_URL . 'public/src/scss/mwb_acfw_custom_css.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function acfw_public_enqueue_scripts() {

		wp_register_script( $this->plugin_name, ABANDONED_CART_FOR_WOOCOMMERCE_DIR_URL . 'public/src/js/abandoned-cart-for-woocommerce-public.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( $this->plugin_name, 'acfw_public_param', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ), 'nonce' => ( wp_create_nonce( 'custom' ) ) ) );
		wp_enqueue_script( $this->plugin_name );
		wp_enqueue_script( 'jquery-ui-dialog' );
	}

	/**
	 * Function mwb_get_session_cart
	 * Function to get Cart Data from Session.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function mwb_insert_add_to_cart() {
		global $wpdb;

			$session_cart = WC()->session->cart;
			// echo '<pre>'; print_r( $session_cart ); echo '</pre>';
			// die;
		if ( ! empty( $session_cart ) ) {
			$atcemail    = isset( $_COOKIE['mwb_atc_email'] ) ? sanitize_text_field( wp_unslash( $_COOKIE['mwb_atc_email'] ) ) : '';
			$mwb_abndon_key = isset( $_COOKIE['mwb_cookie_data'] ) ? sanitize_text_field( wp_unslash( $_COOKIE['mwb_cookie_data'] ) ) : '';

			$time        = gmdate( 'Y-m-d H:i:s' );
			$total = WC()->session->cart_totals['total'];

			$ip              = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
			$encoded_data    = wp_json_encode( $session_cart );
			$guest_cart_data = $encoded_data;
			$mwb_data_result = $wpdb->get_results( "SELECT cart FROM  mwb_abandoned_cart WHERE mwb_abandon_key = '" . $mwb_abndon_key . "' AND  mail_count != 3 AND ip_address = '" . $ip . "' " );

			if ( ! empty( $mwb_data_result ) ) {
					$wpdb->update(
						'mwb_abandoned_cart',
						array(
							'cart' => $guest_cart_data,
							'time' => $time,
							'total' => $total,
						),
						array(
							'mwb_abandon_key' => $mwb_abndon_key,
							'ip_address'      => $ip,
						)
					);
			} else {
				$insert_array = array(
					'email'         => $atcemail,
					'cart'          => $guest_cart_data,
					'time'          => $time,
					'total'         => $total,
					'cart_status'   => 0,
					'workflow_sent' => 0,
					'cron_status'   => 0,
					'mail_count'    => 0,
					'ip_address'    => $ip,
					'mwb_abandon_key' => $mwb_abndon_key,
				);
				$wpdb->insert(
					'mwb_abandoned_cart',
					$insert_array
				);
			}
			if ( is_user_logged_in() ) {

				$session_cart       = WC()->session->cart;
				$role               = wp_get_current_user();
				$current_user_role  = $role->roles[0];
				$mwb_selected_roles = get_option( 'mwb_user_roles' );

				if ( in_array( $current_user_role, $mwb_selected_roles, true ) ) {

					$session_cart = WC()->session->cart;
					$cus          = WC()->session->customer;
					$uid          = $cus['id'];
					$uemail       = $cus['email'];
					$time         = gmdate( 'Y-m-d H:i:s' );
					$total        = WC()->session->cart_totals['total'];
					$encoded_data = json_encode( $session_cart );
					$cart_data = $encoded_data;

					$wpdb->update(
						'mwb_abandoned_cart',
						array(
							'u_id' => $uid,
							'email' => $uemail,
							'cart' => $cart_data,
							'time' => $time,
							'total' => $total,
						),
						array(
							'ip_address' => $ip,
							'mwb_abandon_key' => $mwb_abndon_key,
						)
					);
				} else {
					$wpdb->delete(
						'mwb_abandoned_cart',
						array(
							'ip_address' => $ip,
							'mwb_abandon_key' => $mwb_abndon_key,
						)
					);
				}
			}
		}
	}
	/**
	 * Function to show exit-intent popup to user while abandon the cart
	 *
	 * @return void
	 */
	public function add_tocart_popup() {

		if ( ! is_user_logged_in() ) {
			?>


<div id="dialog" title="Enter Email to Add to Cart">
<div class="mwb-dialog">
<div class="mwb-dialog__img">
<img src="<?php echo ABANDONED_CART_FOR_WOOCOMMERCE_DIR_URL . 'public/src/images/cart.svg'; ?>" alt="">
</div>
<div class="mwb-dialog__text">
<p>Do you want to Buy?</p>
</div>
</div>
<form action="" method="get" accept-charset="utf-8" class="mwb-dialog__form">
<label class="mwb-dialog__form-label">Please enter email</label>
<input type="email" id="email_atc" placeholder=" Please Enter Your Email Here. "> <br>
<input type="button" id="subs" value="Add to Cart" class="button button-danger">
</form>
</div>
			<?php
		}

	}
	// public function mwb_send() {
	// 	as_schedule_single_action( ( time() + 30 ), 'send_custom_mail' );
	// }
	// /** */
	// public function checking_cron() {
	// 	wp_mail( 'shaileshkumardubey@makewebbetter.com', 'Helllo Mail', 'Hello sir ye mail public s ja rha h yhi setup common m kra dd.!!!!!!!!!!!!!!!!!!!!!!!!!!' );

	// }
	/**
	 * Function name mwb_generate_random_cookie
	 * this function will generate random cookie
	 *
	 * @return void
	 */
	public function mwb_generate_random_cookie() {
		if ( ! isset( $_COOKIE['mwb_cookie_data'] ) ) {
			$random_cookie = substr( md5( microtime() ), wp_rand( 0, 26 ), 15 );
			setcookie( 'mwb_cookie_data', $random_cookie, time() + 86400, '/' );
		}
	}
	/**
	 * Function name mwb_update_abandobed_cart
	 * this function will be used to update cart data after add to cart.
	 *
	 * @return void
	 */
	public function mwb_update_abandobed_cart() {

		global $wpdb;

			$session_cart = WC()->session->cart;
		if ( ! empty( $session_cart ) ) {

			$atcemail    = isset( $_COOKIE['mwb_atc_email'] ) ? sanitize_text_field( wp_unslash( $_COOKIE['mwb_atc_email'] ) ) : '';
			$mwb_abndon_key = isset( $_COOKIE['mwb_cookie_data'] ) ? sanitize_text_field( wp_unslash( $_COOKIE['mwb_cookie_data'] ) ) : '';

			$time        = gmdate( 'Y-m-d H:i:s' );
			$total = WC()->session->cart_totals['total'];

			$ip              = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
			$encoded_data    = wp_json_encode( $session_cart );
			$guest_cart_data = $encoded_data;
			$mwb_data_result = $wpdb->get_results( "SELECT cart FROM  mwb_abandoned_cart WHERE mwb_abandon_key = '" . $mwb_abndon_key . "' AND  mail_count != 3 AND ip_address = '" . $ip . "' " );

			if ( ! empty( $mwb_data_result ) ) {
					$wpdb->update(
						'mwb_abandoned_cart',
						array(
							'cart' => $guest_cart_data,
							'time' => $time,
							'total' => $total,
						),
						array(
							'mwb_abandon_key' => $mwb_abndon_key,
							'ip_address'      => $ip,
						)
					);
			} else {
				$insert_array = array(
					'email'           => $atcemail,
					'cart'            => $guest_cart_data,
					'time'            => $time,
					'total'           => $total,
					'cart_status'     => 0,
					'workflow_sent'   => 0,
					'cron_status'     => 0,
					'mail_count'      => 0,
					'ip_address'      => $ip,
					'mwb_abandon_key' => $mwb_abndon_key,
				);
				$wpdb->insert(
					'mwb_abandoned_cart',
					$insert_array
				);
			}
			if ( is_user_logged_in() ) {

				$role               = wp_get_current_user();
				$current_user_role  = $role->roles[0];
				$mwb_selected_roles = get_option( 'mwb_user_roles' );

				if ( in_array( $current_user_role, $mwb_selected_roles, true ) ) {

					$session_cart = WC()->session->cart;
					$cus          = WC()->session->customer;
					$uid          = $cus['id'];
					$uemail       = $cus['email'];
					$time         = gmdate( 'Y-m-d H:i:s' );
					$total        = WC()->session->cart_totals['total'];
					$encoded_data = json_encode( $session_cart );
					$cart_data = $encoded_data;

					$wpdb->update(
						'mwb_abandoned_cart',
						array(
							'u_id'  => $uid,
							'email' => $uemail,
							'cart'  => $cart_data,
							'time'  => $time,
							'total' => $total,
						),
						array(
							'ip_address'      => $ip,
							'mwb_abandon_key' => $mwb_abndon_key,
						)
					);
				} else {
					$wpdb->delete(
						'mwb_abandoned_cart',
						array(
							'ip_address'      => $ip,
							'mwb_abandon_key' => $mwb_abndon_key,
						)
					);
				}
			}
		}
	}
	/**
	 * Fucntion to update data while login
	 *
	 * @return void
	 */
	public function mwb_update_cart_while_login() {

		global $wpdb;
		if ( is_user_logged_in() ) {
			$mwb_update_ip  = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
			$mwb_abndon_key = isset( $_COOKIE['mwb_cookie_data'] ) ? sanitize_text_field( wp_unslash( $_COOKIE['mwb_cookie_data'] ) ) : '';

				$role               = wp_get_current_user();
				$current_user_role  = $role->roles[0];
				$mwb_selected_roles = get_option( 'mwb_user_roles' );

			if ( in_array( $current_user_role, $mwb_selected_roles, true ) ) {

				$session_cart = WC()->session->cart;
				$cus          = WC()->session->customer;
				// echo '<pre>'; print_r( wp_get_current_user() ); echo '</pre>';
				// die;
				$uid          = $cus['id'];
				$uemail       = $cus['email'];
				$time         = gmdate( 'Y-m-d H:i:s' );
				$total        = WC()->session->cart_totals['total'];
				$encoded_data = json_encode( $session_cart );
				$cart_data = $encoded_data;

				$wpdb->update(
					'mwb_abandoned_cart',
					array(
						'u_id' => $uid,
						'email' => $uemail,
						'cart' => $cart_data,
						'time' => $time,
						'total' => $total,
					),
					array(
						'ip_address' => $mwb_update_ip,
						'mwb_abandon_key' => $mwb_abndon_key,
					)
				);
			} else {
				$wpdb->delete(
					'mwb_abandoned_cart',
					array(
						'ip_address' => $mwb_update_ip,
						'mwb_abandon_key' => $mwb_abndon_key,
					)
				);
			}
		}

	}

	// /**
	//  * Function name mwb_callback_abandoned_status
	//  * This function is callback of as which check the status of the cart that is abndoned or not
	//  *
	//  * @return void
	//  */
	// public function mwb_callback_abandoned_status1() {
	// 	global $wpdb;
	// 	$result          = $wpdb->get_results( 'SELECT id,time FROM mwb_abandoned_cart WHERE  cart_status = 0' );
	// 	$mwb_cutoff_time = get_option( 'mwb_cut_off_time' );
	// 	$mwb_converted_cut_off_time = $mwb_cutoff_time * 60 * 60;
	// 	foreach ( $result as $k => $val ) {
	// 		$mwb_db_time    = $val->time;
	// 		$ac_id          = $val->id;
	// 		$current_time   = time();
	// 		$diffrence_time = $current_time - strtotime( $mwb_db_time );
	// 		if ( $diffrence_time > $mwb_converted_cut_off_time ) {
	// 			$wpdb->update(
	// 				'mwb_abandoned_cart',
	// 				array(
	// 					'cart_status'  => 1,
	// 				),
	// 				array(
	// 					'id' => $ac_id,
	// 				)
	// 			);
	// 		}
	// 	}

	// }
	// /**
	//  * Function name mwb_schedule_status_check
	//  * This function is used to schedule the action.
	//  *
	//  * @return void
	//  */
	// public function mwb_schedule_status_check() {
	// 	as_schedule_single_action( ( time() + 3600 ), 'mwb_check_abandoned_status' );
	// }
	
	public function check_cart(){
		if ( isset( $_GET['ac_id'] ) ) {
			global $wpdb;
			$id = $_GET['ac_id'];
			$mwb_data_result = $wpdb->get_results( 'SELECT cart FROM mwb_abandoned_cart WHERE id = ' . $id .'' );
			if( !empty($mwb_data_result) ){
				$cartdata = json_decode( $mwb_data_result[0]->cart, true );
				WC()->session->set( 'cart', $cartdata );
				$check_status = $id;
				WC()->session->set( 'track_recovery', $check_status );
				// echo '<pre>'; print_r( WC()->session ); echo '</pre>';
				// die;
					wp_safe_redirect( wc_get_checkout_url() );
				exit;
			}			
		}
	}
	/**
	 * Function name mwb_ac_conversion
	 * This function will be used to track Converted abandoned carts.
	 *
	 * @return void
	 */
	public function mwb_ac_conversion() {
		global $wpdb;
			// echo '<pre>'; print_r( WC()->session ); echo '</pre>';
			if( isset( WC()->session->track_recovery ) ) {
				// $id = $_GET['ac_id'];
				$id = WC()->session->track_recovery;
				$wpdb->update(
					'mwb_abandoned_cart',
					array(
						'cart_status' => 2,
					),
					array(
						'id' => $id,
					)
				);
				// echo "CArt recovered";
				WC()->session->__unset( 'track_recovery' );
			}

	}

}
