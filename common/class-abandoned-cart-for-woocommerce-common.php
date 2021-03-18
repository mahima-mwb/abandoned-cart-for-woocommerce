<?php
/**
 * The common-specific functionality of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Abandoned_Cart_For_Woocommerce
 * @subpackage Abandoned_Cart_For_Woocommerce/common
 */

/**
 * The common-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the common-specific stylesheet and JavaScript.
 *
 * @package    Abandoned_Cart_For_Woocommerce
 * @subpackage Abandoned_Cart_For_Woocommerce/common
 * @author     makewebbetter <webmaster@makewebbetter.com>
 */
class Abandoned_Cart_For_Woocommerce_Common {
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 * @param    string $hook      The plugin page slug.
	 */
	public function acfw_common_enqueue_styles( $hook ) {

		wp_enqueue_style( $this->plugin_name, ABANDONED_CART_FOR_WOOCOMMERCE_DIR_URL . 'common/src/scss/abandoned-cart-for-woocommerce-common.scss', array(), $this->version, 'all' );
		wp_enqueue_style( 'common-custom-css', ABANDONED_CART_FOR_WOOCOMMERCE_DIR_URL . 'common/src/scss/abandoned-cart-common-css.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 * @param    string $hook      The plugin page slug.
	 */
	public function acfw_common_enqueue_scripts( $hook ) {
		wp_register_script( $this->plugin_name . 'common-js', ABANDONED_CART_FOR_WOOCOMMERCE_DIR_URL . 'common/src/js/abandoned-cart-for-woocommerce-common.js', array( 'jquery', 'mwb-acfw-select2', 'mwb-acfw-metarial-js', 'mwb-acfw-metarial-js2', 'mwb-acfw-metarial-lite' ), $this->version, false );
	}

	/**
	 * Function name mwb_schedule_check_cart_status.
	 * this function will used to schedule first cron to check cart status.
	 *
	 * @return void
	 */
	public function mwb_schedule_check_cart_status() {
		if ( isset( $_POST['save_general'] ) ) {               //phpcs:ignore
			$sch = wp_next_scheduled( 'mwb_schedule_first_cron' );
			if ( $sch ) {
				wp_unschedule_event( $sch, 'mwb_schedule_first_cron' );
			}
			wp_schedule_event( time(), 'mwb_custom_time', 'mwb_schedule_first_cron' );
			$this->mwb_delete_ac_history_limited_time();
		}
	}
	/**
	 * Function name mwb_add_cron_interval
	 *
	 * @param [type] $schedules array.
	 * @return array
	 */
	public function mwb_add_cron_interval( $schedules ) {
		$time = get_option( 'mwb_cut_off_time' );
		$del_time = get_option( 'mwb_delete_time_for_ac' );
		$schedules['mwb_custom_time'] = array(
			'interval' => $time * 60 * 60,
			'display'  => esc_html__( 'Every custom time' ),
		);
		if( $del_time ) {
			$schedules['mwb_del_ac_time'] = array(
				'interval' => $del_time * 86400,
				'display'  => esc_html__( 'Delete custom time', 'mwb_schedule_del_cron' ),
			);
		}
		
			return $schedules;

	}

	/**
	 * Set mail type to html
	 *
	 * @return tyoe
	 */
	public function set_type_wp_mail() {
		return 'text/html';

	}

	/**
	 * Function name mwb_check_status.
	 * This function will Used to check the status of cart
	 *
	 * @return void
	 */
	public function mwb_check_status() {
		global $wpdb;
		$result          = $wpdb->get_results( 'SELECT id,time FROM mwb_abandoned_cart WHERE  cart_status = 0' );
		$mwb_cutoff_time = get_option( 'mwb_cut_off_time' );
		$mwb_converted_cut_off_time = $mwb_cutoff_time * 60 * 60;
		foreach ( $result as $k => $val ) {
			$mwb_db_time    = $val->time;
			$ac_id          = $val->id;
			$current_time   = time();
			$diffrence_time = $current_time - strtotime( $mwb_db_time );
			if ( $diffrence_time > $mwb_converted_cut_off_time ) {
				$wpdb->update(
					'mwb_abandoned_cart',
					array(
						'cart_status'  => 1,
					),
					array(
						'id' => $ac_id,
					)
				);
			}
		}
		$this->mwb_schedule_first_timer_cron();
	}


	/**
	 * Function name mwb_schedule_first_timer_cron
	 * Funticon TO set timer.
	 *
	 * @return void
	 */
	public function mwb_schedule_first_timer_cron() {
		update_option( 'mwb_abandon_timer', 1 );
		global $wpdb;
		$result1  = $wpdb->get_results( 'SELECT * FROM mwb_email_workflow WHERE ew_id = 1' );
		$check_enable           = $result1[0]->ew_enable;
		$fetch_time             = $result1[0]->ew_initiate_time;
		$converted_time_seconds = $fetch_time * 60 * 60;
		if ( 'on' === $check_enable ) {

			$result = $wpdb->get_results( 'SELECT * FROM mwb_abandoned_cart WHERE cart_status = 1 AND workflow_sent = 0' );

			foreach ( $result as $k => $value ) {
				$abandon_time = $value->time;
				$email        = $value->email;
				$ac_id        = $value->id;
				$cron_status  = $value->cron_status;
				$sending_time = gmdate( 'Y-m-d H:i:s', strtotime( $abandon_time ) + $converted_time_seconds );
				$this->mwb_first_mail_sending( $sending_time, $cron_status, $email, $ac_id );
			}
		}
	}
	/**
	 * Function name mwb_first_mail_sending
	 * this fucntion will sechdule the first as
	 *
	 * @param [type] $sending_time sending time.
	 * @param [type] $cron_status cron status.
	 * @param [type] $email email.
	 * @param [type] $ac_id ac_id.
	 * @return void
	 */
	public function mwb_first_mail_sending( $sending_time, $cron_status, $email, $ac_id ) {
		if ( '0' === $cron_status ) {

			as_schedule_single_action( $sending_time, 'send_email_hook', array( $email, $ac_id ) );
		}

	}
	/**
	 * Function name mwb_mail_sent
	 * this function is used to send first mail
	 *
	 * @param [type] $email get the email address.
	 * @param [type] $ac_id ac_id.
	 * @return void
	 */
	public function mwb_mail_sent( $email, $ac_id ) {
		$check = false;
		global $wpdb;
		$result1  = $wpdb->get_results( 'SELECT * FROM mwb_email_workflow WHERE ew_id = 1' );
			$content = $result1[0]->ew_content;
			$ew_id = $result1[0]->ew_id;
			$subject  = $result1[0]->ew_mail_subject;
		$email       = is_array( $email ) ? array_shift( $email ) : $email;
		$ac_id       = is_array( $ac_id ) ? array_shift( $ac_id ) : $ac_id;

		$checkout_url       = '<a href = "' . wc_get_checkout_url() . '?ac_id=' . $ac_id . '" style="background-color: #2199f5;	padding: 7px 14px; font-size: 16px;	color: #f1f1f1;	font-weight: 600; text-decoration: none; border-radius: 4px; box-shadow: 0 4px 10px #999;" >Checkout Now</a><br>';
		$time          = gmdate( 'Y-m-d H:i:s' );
		$coupon_result = $wpdb->get_results( $wpdb->prepare( ' SELECT coupon_code, cart FROM mwb_abandoned_cart WHERE id = %d ', $ac_id ) );
		$mwb_db_coupon = $coupon_result[0]->coupon_code;
		$mwb_cart = json_decode( $coupon_result[0]->cart, true );
		if ( strpos( $content, '{checkout}' ) ) {
			$sending_content = str_replace( '{checkout}', $checkout_url, $content );
		}else {
			$sending_content = $content;
		}
		if ( strpos( $sending_content, '{cart}' ) ) {
			$cart_data  = $wpdb->get_results( $wpdb->prepare( 'SELECT cart FROM mwb_abandoned_cart WHERE id = %d ', $ac_id ) );
			$dbcart = $cart_data[0]->cart;
			$decoded_cart = json_decode( $dbcart, true );
			$table_content = '<h2>Your Cart</h2><br><table style=" border-collapse: collapse; width: 50%; table-layout: fixed;"><tr> <th style="  background: #e5f4fe; border: 1px solid #000000; text-align: center;	padding: 10px 0;">Product Name</th><th style="background: #e5f4fe; border: 1px solid #000000; text-align: center;	padding: 10px 0;">Quantity</th></tr>';
			foreach ( $decoded_cart as $k => $val ) {
				$pid = $val['product_id'];
				$product = wc_get_product( $pid );
				$pname = $product->get_title();
				$quantity = $val['quantity'];
				$table_content .= '<tr><td style=" border: 1px solid #000000; text-align: center; padding: 10px 0;">' . esc_html( $pname ) . '</td> <td style="border: 1px solid #000000; text-align: center; padding: 10px 0;">' . esc_html( $quantity ) . '</td> </tr>';


			}
			$table_content .= '</table><br>';
			$sending_content_cart = str_replace( '{cart}', $table_content, $sending_content );
		}
		else{
			$sending_content_cart = $sending_content;
		}
		if ( null === $mwb_db_coupon ) {
			if ( strpos( $sending_content, '{coupon}' ) ) {
				$mwb_coupon_discount = get_option( 'mwb_coupon_discount' );
				$mwb_coupon_expiry   = get_option( 'mwb_coupon_expiry' );
				$mwb_coupon_prefix   = get_option( 'mwb_coupon_prefix' );
				$rand = substr( md5( microtime() ), wp_rand( 0, 26 ), 5 );
				$coupon_expiry_time = time() + ( $mwb_coupon_expiry * 60 * 60 );
				$mwb_coupon_name = $mwb_coupon_prefix . $rand;

				/**
				* Create a coupon for sending in email.
				*/
				$coupon_code   = $mwb_coupon_name; // Code.
				$amount        = $mwb_coupon_discount; // Amount.
				$discount_type = 'percent'; // Type: percent.

				$coupon = array(
					'post_title'   => $coupon_code,
					'post_content' => '',
					'post_status'  => 'publish',
					'post_author'  => 1,
					'post_type'    => 'shop_coupon',
				);

				$new_coupon_id = wp_insert_post( $coupon );

				update_post_meta( $new_coupon_id, 'discount_type', $discount_type );
				update_post_meta( $new_coupon_id, 'coupon_amount', $amount );
				update_post_meta( $new_coupon_id, 'individual_use', 'no' );
				foreach ( $mwb_cart as $key => $mwb_value ) {

					update_post_meta( $new_coupon_id, 'product_ids', $mwb_value['product_id'] );
				}
				update_post_meta( $new_coupon_id, 'usage_limit', '' );
				update_post_meta( $new_coupon_id, 'expiry_date', $coupon_expiry_time );
				update_post_meta( $new_coupon_id, 'apply_before_tax', 'yes' );
				update_post_meta( $new_coupon_id, 'free_shipping', 'no' );

				$db_code_coupon_mwb = wc_get_coupon_code_by_id( $new_coupon_id );
				$final_sending_coupon = '<h6 style="font-size: 16px; margin: 20px 0 0; color: red; border: 1px solid red; width: fit-content; padding: 7px;"> Your Coupon Code: ' . $db_code_coupon_mwb . '</h6>';
				$final_content = str_replace( '{coupon}', $final_sending_coupon, $sending_content_cart );
					$wpdb->update(
						'mwb_abandoned_cart',
						array(
							'coupon_code' => $db_code_coupon_mwb,
						),
						array(
							'id' => $ac_id,
						)
					);
			} else {
				$final_content = $sending_content_cart;
			}
		} else {
			$final_sending_coupon_mwb_db = '<h6 style="font-size: 16px; margin: 20px 0 0; color: red; border: 1px solid red; width: fit-content; padding: 7px;"> Your Coupon Code: ' . $mwb_db_coupon . '</h6>';
			$final_content = str_replace( '{coupon}', $final_sending_coupon_mwb_db, $sending_content_cart );
		}
		$check = wp_mail( $email, $subject, $final_content );
		if ( true === $check ) {

			$wpdb->update(
				'mwb_abandoned_cart',
				array(
					'cron_status'   => 1,
					'mail_count'    => 1,
				),
				array(
					'id' => $ac_id,
				)
			);
			$wpdb->insert(
				'mwb_cart_recovery',
				array(
					'ac_id' => $ac_id,
					'ew_id' => $ew_id,
					'time'  => $time,
				)
			);

		}
	}
	/**
	 * Function name abdn_daily_cart_cron_schedule.
	 * this fucntion will schedule second cron for sending second mail daily.
	 *
	 * @return void
	 */
	public function abdn_daily_cart_cron_schedule() {
		$cur_stamp = wp_next_scheduled( 'mwb_acfw_second_cron' );
		if ( ! $cur_stamp ) {
			wp_schedule_event( time(), 'daily', 'mwb_acfw_second_cron' );
		}
	}
	/**
	 * Function name abdn_cron_callback_daily.
	 * this fucntion is call back of second cron
	 *
	 * @return void
	 */
	public function abdn_cron_callback_daily() {
		$this->send_second();
	}

	/**
	 * Function name abdn_daily_cart_cron_schedule.
	 * this fucntion will schedule second cron for sending second mail daily.
	 *
	 * @return void
	 */
	public function mwb_third_abdn_daily_cart_cron_schedule() {
		$cur_stamp = wp_next_scheduled( 'mwb_acfw_third_cron' );
		if ( ! $cur_stamp ) {
			wp_schedule_event( time(), 'daily', 'mwb_acfw_third_cron' );
		}
	}
	/**
	 * Function name abdn_cron_callback_daily.
	 * this fucntion is call back of second cron
	 *
	 * @return void
	 */
	public function mwb_third_abdn_cron_callback_daily() {

		$this->send_third();
	}

	/**
	 * Function name .send_second
	 * This function will be used to send the second email to the customer's.
	 *
	 * @return void
	 */
	public function send_second() {
		global $wpdb;
		$result1                = $wpdb->get_results( 'SELECT * FROM mwb_email_workflow WHERE ew_id = 2' );
		$check_enable           = $result1[0]->ew_enable;
		$fetch_time             = $result1[0]->ew_initiate_time;
		$converted_time_seconds = $fetch_time * 60 * 60;
		
		if ( 'on' === $check_enable ) {

			$result  = $wpdb->get_results( 'SELECT * FROM mwb_abandoned_cart WHERE cart_status = 1 AND mail_count = 1' );
			foreach ( $result as $key => $value ) {
				$abandon_time = $value->time;
				$email        = $value->email;
				$ac_id        = $value->id;
				$sending_time = gmdate( 'Y-m-d H:i:s', strtotime( $abandon_time ) + $converted_time_seconds );
				$this->mwb_schedule_second( $sending_time, $email, $ac_id );
			}
		}
		wp_die();
	}
	/**
	 * Function to scheule the second button.
	 *
	 * @param [type] $sending_time stores the sending time.
	 * @param [type] $email stores the email of the users.
	 * @param [type] $ac_id ac_id.
	 * @return void
	 */
	public function mwb_schedule_second( $sending_time, $email, $ac_id ) {

			as_schedule_single_action( $sending_time, 'send_second_mail_hook', array( $email, $ac_id ) );

	}
	/**
	 * Function name mwb_mail_sent_second
	 * this function is used to send the second mail.
	 *
	 * @param [type] $email contains email.
	 * @param [type] $ac_id cintains ac_id.
	 * @return void
	 */
	public function mwb_mail_sent_second( $email, $ac_id ) {
		$check = false;
		global $wpdb;
			$result1  = $wpdb->get_results( 'SELECT * FROM mwb_email_workflow WHERE ew_id = 2' );
			$content = $result1[0]->ew_content;
			$ew_id   = $result1[0]->ew_id;
			$subject = $result1[0]->ew_mail_subject;
			$coupon_result = $wpdb->get_results( $wpdb->prepare( ' SELECT coupon_code, cart FROM mwb_abandoned_cart WHERE id = %d ', $ac_id ) );
		$mwb_db_coupon = $coupon_result[0]->coupon_code;
		$checkout_url       = '<a href = "' . wc_get_checkout_url() . '?ac_id=' . $ac_id . '" style="background-color: #2199f5;	padding: 7px 14px; font-size: 16px;	color: #f1f1f1;	font-weight: 600; text-decoration: none; border-radius: 4px; box-shadow: 0 4px 10px #999;" >Checkout Now</a><br>';
		$email = is_array( $email ) ? array_shift( $email ) : $email;
		$ac_id = is_array( $ac_id ) ? array_shift( $ac_id ) : $ac_id;
		$time = gmdate( 'Y-m-d H:i:s' );
		if ( strpos( $content, '{checkout}' ) ) {
			$sending_content = str_replace( '{checkout}', $checkout_url, $content );
		}else {
			$sending_content = $content;
		}
		if ( strpos( $sending_content, '{cart}' ) ) {
			$cart_data  = $wpdb->get_results( $wpdb->prepare( 'SELECT cart FROM mwb_abandoned_cart WHERE id = %d ', $ac_id ) );
			$dbcart = $cart_data[0]->cart;
			$decoded_cart = json_decode( $dbcart, true );
			$table_content = '<h2>Your Cart</h2><br><table style=" border-collapse: collapse; width: 50%; table-layout: fixed;"><tr> <th style="  background: #e5f4fe; border: 1px solid #000000; text-align: center;	padding: 10px 0;">Product Name</th><th style="background: #e5f4fe; border: 1px solid #000000; text-align: center;	padding: 10px 0;">Quantity</th></tr>';
			foreach ( $decoded_cart as $k => $val ) {
				$pid = $val['product_id'];
				$product = wc_get_product( $pid );
				$pname = $product->get_title();
				$quantity = $val['quantity'];
				$table_content .= '<tr><td style=" border: 1px solid #000000; text-align: center; padding: 10px 0;">' . esc_html( $pname ) . '</td> <td style="border: 1px solid #000000; text-align: center; padding: 10px 0;">' . esc_html( $quantity ) . '</td> </tr>';


			}
			$table_content .= '</table><br>';
			$sending_content_cart = str_replace( '{cart}', $table_content, $sending_content );
		}
		else{
			$sending_content_cart = $sending_content;
		}
		if ( null === $mwb_db_coupon ) {
			if ( strpos( $sending_content, '{coupon}' ) ) {
				$mwb_coupon_discount = get_option( 'mwb_coupon_discount' );
				$mwb_coupon_expiry   = get_option( 'mwb_coupon_expiry' );
				$mwb_coupon_prefix   = get_option( 'mwb_coupon_prefix' );
				$rand = substr( md5( microtime() ), wp_rand( 0, 26 ), 5 );
				$coupon_expiry_time = time() + ( $mwb_coupon_expiry * 60 * 60 );
				$mwb_coupon_name = $mwb_coupon_prefix . $rand;

				/**
				* Create a coupon for sending in email.
				*/
				$coupon_code   = $mwb_coupon_name; // Code.
				$amount        = $mwb_coupon_discount; // Amount.
				$discount_type = 'percent'; // Type: percent.

				$coupon = array(
					'post_title'   => $coupon_code,
					'post_content' => '',
					'post_status'  => 'publish',
					'post_author'  => 1,
					'post_type'    => 'shop_coupon',
				);

				$new_coupon_id = wp_insert_post( $coupon );

				update_post_meta( $new_coupon_id, 'discount_type', $discount_type );
				update_post_meta( $new_coupon_id, 'coupon_amount', $amount );
				update_post_meta( $new_coupon_id, 'individual_use', 'no' );
				foreach ( $mwb_cart as $key => $mwb_value ) {

					update_post_meta( $new_coupon_id, 'product_ids', $mwb_value['product_id'] );
				}
				update_post_meta( $new_coupon_id, 'usage_limit', '' );
				update_post_meta( $new_coupon_id, 'expiry_date', $coupon_expiry_time );
				update_post_meta( $new_coupon_id, 'apply_before_tax', 'yes' );
				update_post_meta( $new_coupon_id, 'free_shipping', 'no' );

				$db_code_coupon_mwb = wc_get_coupon_code_by_id( $new_coupon_id );
				$final_sending_coupon = '<h6 style="font-size: 16px; margin: 20px 0 0; color: red; border: 1px solid red; width: fit-content; padding: 7px;"> Your Coupon Code: ' . $db_code_coupon_mwb . '</h6><br>';
				$final_content = str_replace( '{coupon}', $final_sending_coupon, $sending_content_cart );
					$wpdb->update(
						'mwb_abandoned_cart',
						array(
							'coupon_code' => $db_code_coupon_mwb,
						),
						array(
							'id' => $ac_id,
						)
					);
			} else {
				$final_content = $sending_content_cart;
			}
		} else {
			$final_sending_coupon_mwb_db = '<h6 style="font-size: 16px; margin: 20px 0 0; color: red; border: 1px solid red; width: fit-content; padding: 7px;"> Your Coupon Code: ' . $mwb_db_coupon . '</h6><br>';
			$final_content = str_replace( '{coupon}', $final_sending_coupon_mwb_db, $sending_content_cart );
		}
		$check = wp_mail( $email, $subject, $final_content );
		if ( true === $check ) {
			$wpdb->update(
				'mwb_abandoned_cart',
				array(
					'mail_count' => 2,
				),
				array(
					'id' => $ac_id,
				)
			);
			$wpdb->insert(
				'mwb_cart_recovery',
				array(
					'ac_id' => $ac_id,
					'ew_id' => $ew_id,
					'time'  => $time,
				)
			);

		}
	}
	/**
	 * Fuction to send Third mail
	 *
	 * @return void
	 */
	public function send_third() {

		global $wpdb;
		$result1  = $wpdb->get_results( 'SELECT * FROM mwb_email_workflow WHERE ew_id = 3' );
		$check_enable = $result1[0]->ew_enable;
		$fetch_time = $result1[0]->ew_initiate_time;
		$converted_time_seconds = $fetch_time * 60 * 60;
		$content = $result1[0]->ew_content;
		if ( 'on' === $check_enable ) {

			$result  = $wpdb->get_results( 'SELECT * FROM mwb_abandoned_cart WHERE cart_status = 1 AND mail_count = 2' );
			foreach ( $result as $key => $value ) {
				$abandon_time = $value->time;
				$email = $value->email;
				$ac_id = $value->id;
				$sending_time = gmdate( 'Y-m-d H:i:s', strtotime( $abandon_time ) + $converted_time_seconds );
				$this->mwb_schedule_third( $sending_time, $email, $ac_id );
			}
		}

	}
	/**
	 * Function to send the third mail
	 *
	 * @param [type] $sending_time sending time.
	 * @param [type] $email email.
	 * @param [type] $ac_id ac_id.
	 * @return void
	 */
	public function mwb_schedule_third( $sending_time, $email, $ac_id ) {

			as_schedule_single_action( $sending_time, 'send_third_mail_hook', array( $email, $ac_id ) );

	}
	/**
	 * Function name mwb_mail_sent_third.
	 * this function is used to send the third mail
	 *
	 * @param [type] $email email.
	 * @param [type] $ac_id ac id.
	 * @return void
	 */
	public function mwb_mail_sent_third( $email, $ac_id ) {
		$check = false;
		global $wpdb;
		$result1  = $wpdb->get_results( 'SELECT * FROM mwb_email_workflow WHERE ew_id = 3' );
			$content = $result1[0]->ew_content;
			$ew_id = $result1[0]->ew_id;
			$subject = $result1[0]->ew_mail_subject;
		$email = is_array( $email ) ? array_shift( $email ) : $email;
		$ac_id = is_array( $ac_id ) ? array_shift( $ac_id ) : $ac_id;
		$time = gmdate( 'Y-m-d H:i:s' );
		$coupon_result = $wpdb->get_results( $wpdb->prepare( ' SELECT coupon_code, cart FROM mwb_abandoned_cart WHERE id = %d ', $ac_id ) );
		$mwb_db_coupon = $coupon_result[0]->coupon_code;
		
		$email = is_array( $email ) ? array_shift( $email ) : $email;
		$ac_id = is_array( $ac_id ) ? array_shift( $ac_id ) : $ac_id;
		$checkout_url       = '<a href = "' . wc_get_checkout_url() . '?ac_id=' . $ac_id . '" style="background-color: #2199f5;	padding: 7px 14px; font-size: 16px;	color: #f1f1f1;	font-weight: 600; text-decoration: none; border-radius: 4px; box-shadow: 0 4px 10px #999;" >Checkout Now</a><br>';
	
		$time = gmdate( 'Y-m-d H:i:s' );
		if ( strpos( $content, '{checkout}' ) ) {
			$sending_content = str_replace( '{checkout}', $checkout_url, $content );
		}else {
			$sending_content = $content;
		}
		if ( strpos( $sending_content, '{cart}' ) ) {
			$cart_data  = $wpdb->get_results( $wpdb->prepare( 'SELECT cart FROM mwb_abandoned_cart WHERE id = %d ', $ac_id ) );
			$dbcart = $cart_data[0]->cart;
			$decoded_cart = json_decode( $dbcart, true );
			$table_content = '<h2>Your Cart</h2><br><table style=" border-collapse: collapse; width: 50%; table-layout: fixed;"><tr> <th style="  background: #e5f4fe; border: 1px solid #000000; text-align: center;	padding: 10px 0;">Product Name</th><th style="background: #e5f4fe; border: 1px solid #000000; text-align: center;	padding: 10px 0;">Quantity</th></tr>';
			foreach ( $decoded_cart as $k => $val ) {
				$pid = $val['product_id'];
				$product = wc_get_product( $pid );
				$pname = $product->get_title();
				$quantity = $val['quantity'];
				$table_content .= '<tr><td style=" border: 1px solid #000000; text-align: center; padding: 10px 0;">' . esc_html( $pname ) . '</td> <td style="border: 1px solid #000000; text-align: center; padding: 10px 0;">' . esc_html( $quantity ) . '</td> </tr>';


			}
			$table_content .= '</table><br>';
			$sending_content_cart = str_replace( '{cart}', $table_content, $sending_content );
		}
		else{
			$sending_content_cart = $sending_content;
		}
		if ( null === $mwb_db_coupon ) {
			if ( strpos( $sending_content, '{coupon}' ) ) {
				$mwb_coupon_discount = get_option( 'mwb_coupon_discount' );
				$mwb_coupon_expiry   = get_option( 'mwb_coupon_expiry' );
				$mwb_coupon_prefix   = get_option( 'mwb_coupon_prefix' );
				$rand = substr( md5( microtime() ), wp_rand( 0, 26 ), 5 );
				$coupon_expiry_time = time() + ( $mwb_coupon_expiry * 60 * 60 );
				$mwb_coupon_name = $mwb_coupon_prefix . $rand;

				/**
				* Create a coupon for sending in email.
				*/
				$coupon_code   = $mwb_coupon_name; // Code.
				$amount        = $mwb_coupon_discount; // Amount.
				$discount_type = 'percent'; // Type: percent.

				$coupon = array(
					'post_title'   => $coupon_code,
					'post_content' => '',
					'post_status'  => 'publish',
					'post_author'  => 1,
					'post_type'    => 'shop_coupon',
				);

				$new_coupon_id = wp_insert_post( $coupon );

				update_post_meta( $new_coupon_id, 'discount_type', $discount_type );
				update_post_meta( $new_coupon_id, 'coupon_amount', $amount );
				update_post_meta( $new_coupon_id, 'individual_use', 'no' );
				foreach ( $mwb_cart as $key => $mwb_value ) {

					update_post_meta( $new_coupon_id, 'product_ids', $mwb_value['product_id'] );
				}
				update_post_meta( $new_coupon_id, 'usage_limit', '' );
				update_post_meta( $new_coupon_id, 'expiry_date', $coupon_expiry_time );
				update_post_meta( $new_coupon_id, 'apply_before_tax', 'yes' );
				update_post_meta( $new_coupon_id, 'free_shipping', 'no' );

				$db_code_coupon_mwb = wc_get_coupon_code_by_id( $new_coupon_id );
				$final_sending_coupon = '<h6 style="font-size: 16px; margin: 20px 0 0; color: red; border: 1px solid red; width: fit-content; padding: 7px;"> Your Coupon Code: ' . $db_code_coupon_mwb . '</h6><br>';
							$final_content = str_replace( '{coupon}', $final_sending_coupon, $sending_content_cart );
					$wpdb->update(
						'mwb_abandoned_cart',
						array(
							'coupon_code' => $db_code_coupon_mwb,
						),
						array(
							'id' => $ac_id,
						)
					);
			} else {
				$final_content = $sending_content_cart;
			}
		} else {
			$final_sending_coupon_mwb_db = '<h6 style="font-size: 16px; margin: 20px 0 0; color: red; border: 1px solid red; width: fit-content; padding: 7px;"> Your Coupon Code: ' . $mwb_db_coupon . '</h6><br>';
			$final_content = str_replace( '{coupon}', $final_sending_coupon_mwb_db, $sending_content_cart );
		}
		$check = wp_mail( $email, $subject, $final_content );
		if ( true === $check ) {
			$wpdb->update(
				'mwb_abandoned_cart',
				array(
					'mail_count' => 3,
					'workflow_sent' => 1,
				),
				array(
					'id' => $ac_id,
				)
			);
			$wpdb->insert(
				'mwb_cart_recovery',
				array(
					'ac_id' => $ac_id,
					'ew_id' => $ew_id,
					'time'  => $time,
				)
			);

		}
	}

	/**
	 * Function name mwb_delete_ac_history_limited_time
	 * this function is used to delete abandoned cart history after a given time by admin
	 *
	 * @return void
	 */
	public function mwb_delete_ac_history_limited_time() {
		$del_time = get_option( 'mwb_delete_time_for_ac' );
		if ( $del_time ) {
			$sch_del = wp_next_scheduled( 'mwb_schedule_del_cron' );
			if ( $sch_del ) {
				wp_unschedule_event( $sch_del, 'mwb_schedule_del_cron' );
			}
			wp_schedule_event( time(), 'mwb_del_ac_time', 'mwb_schedule_del_cron' );
		}

	}

	/**
	 * Function name mwb_del_data_of_ac
	 * this function is callback of del cron.
	 *
	 * @return void
	 */
	public function mwb_del_data_of_ac() {
		global $wpdb;
		$time = get_option( 'mwb_delete_time_for_ac' );
		if ( $time ) {
			$wpdb->query(
				'TRUNCATE TABLE `mwb_abandoned_cart`'
			);
			$wpdb->query(
				'TRUNCATE TABLE `mwb_cart_recovery`'
			);


		}
	}
}
