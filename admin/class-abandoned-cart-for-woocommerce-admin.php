<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Abandoned_Cart_For_Woocommerce
 * @subpackage Abandoned_Cart_For_Woocommerce/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Abandoned_Cart_For_Woocommerce
 * @subpackage Abandoned_Cart_For_Woocommerce/admin
 * @author     makewebbetter <webmaster@makewebbetter.com>
 */
class Abandoned_Cart_For_Woocommerce_Admin {

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
	public function mwb_acfw_admin_enqueue_styles( $hook ) {
		$screen = get_current_screen();
		if ( isset( $screen->id ) && 'makewebbetter_page_abandoned_cart_for_woocommerce_menu' == $screen->id ) {

			wp_enqueue_style( 'mwb-acfw-select2-css', ABANDONED_CART_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/select-2/abandoned-cart-for-woocommerce-select2.css', array(), time(), 'all' );

			wp_enqueue_style( 'mwb-acfw-meterial-css', ABANDONED_CART_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/material-design/material-components-web.min.css', array(), time(), 'all' );
			wp_enqueue_style( 'mwb-acfw-meterial-css2', ABANDONED_CART_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/material-design/material-components-v5.0-web.min.css', array(), time(), 'all' );
			wp_enqueue_style( 'mwb-acfw-meterial-lite', ABANDONED_CART_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/material-design/material-lite.min.css', array(), time(), 'all' );

			wp_enqueue_style( 'mwb-acfw-meterial-icons-css', ABANDONED_CART_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/material-design/icon.css', array(), time(), 'all' );

			wp_enqueue_style( $this->plugin_name . '-admin-global', ABANDONED_CART_FOR_WOOCOMMERCE_DIR_URL . 'admin/src/scss/mwb-acfw-abandoned-cart-for-woocommerce-admin-global.css', array( 'mwb-acfw-meterial-icons-css' ), time(), 'all' );

			wp_enqueue_style( $this->plugin_name, ABANDONED_CART_FOR_WOOCOMMERCE_DIR_URL . 'admin/src/scss/abandoned-cart-for-woocommerce-admin.scss', array(), $this->version, 'all' );

				wp_enqueue_style( 'wp-jquery-ui-dialog' );
			wp_enqueue_style( 'wp-jquery-ui-dialog' );
		}
		wp_enqueue_style( 'mwb-abandon-setting-css', ABANDONED_CART_FOR_WOOCOMMERCE_DIR_URL . 'admin/src/scss/mwb-afcw-abandoned-cart-for-woocommerce-setting.css', array(), time(), 'all' );

		wp_enqueue_style( 'chartcsss', ABANDONED_CART_FOR_WOOCOMMERCE_DIR_URL . 'admin/src/js/node_modules/chart.js/dist/Chart.css', array(), time(), 'all' );
		wp_enqueue_style( 'wp-admin' );

		wp_enqueue_style( 'chartmin', ABANDONED_CART_FOR_WOOCOMMERCE_DIR_URL . 'admin/src/js/node_modules/chart.js/dist/Chart.min.css', array(), time(), 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 * @param    string $hook      The plugin page slug.
	 */
	public function mwb_acfw_admin_enqueue_scripts( $hook ) {

		$screen = get_current_screen();
		if ( isset( $screen->id ) && 'makewebbetter_page_abandoned_cart_for_woocommerce_menu' == $screen->id ) {
			wp_enqueue_script( 'mwb-acfw-select2', ABANDONED_CART_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/select-2/abandoned-cart-for-woocommerce-select2.js', array( 'jquery' ), time(), false );

			wp_enqueue_script( 'mwb-acfw-metarial-js', ABANDONED_CART_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/material-design/material-components-web.min.js', array(), time(), false );
			wp_enqueue_script( 'mwb-acfw-metarial-js2', ABANDONED_CART_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/material-design/material-components-v5.0-web.min.js', array(), time(), false );
			wp_enqueue_script( 'mwb-acfw-metarial-lite', ABANDONED_CART_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/material-design/material-lite.min.js', array(), time(), false );

			wp_register_script( $this->plugin_name . 'admin-js', ABANDONED_CART_FOR_WOOCOMMERCE_DIR_URL . 'admin/src/js/mwb-acfw-abandoned-cart-for-woocommerce-admin.js', array( 'jquery', 'mwb-acfw-select2', 'mwb-acfw-metarial-js', 'mwb-acfw-metarial-js2', 'mwb-acfw-metarial-lite' ), $this->version, false );

			if ( 'abandoned-cart-for-woocommerce-analytics' === ( ( isset( $_GET['acfw_tab'] ) ) ? $_GET['acfw_tab'] : false ) ) {
				$tab_check = true;
			} else {
				$tab_check = false;
			}
			wp_localize_script(
				$this->plugin_name . 'admin-js',
				'acfw_admin_param',
				array(
					'ajaxurl'             => admin_url( 'admin-ajax.php' ),
					'reloadurl'           => admin_url( 'admin.php?page=abandoned_cart_for_woocommerce_menu' ),
					'acfw_gen_tab_enable' => get_option( 'acfw_radio_switch_demo' ),
					'tab'                 => $tab_check,
				)
			);

			wp_enqueue_script( $this->plugin_name . 'admin-js' );

		}
		$acfw_enable = get_option( 'mwb_enable_acfw' );
		if ( 'on' === $acfw_enable ) {
			wp_register_script( 'demo_js', ABANDONED_CART_FOR_WOOCOMMERCE_DIR_URL . 'admin/src/js/mwb-afcw-custom.js', array( 'jquery' ), $this->version, false );

					wp_localize_script(
						'demo_js',
						'demo_js_ob',
						array(
							'ajaxurl' => admin_url( 'admin-ajax.php' ),
							'nonce'   => ( wp_create_nonce( 'custom' ) ),
						)
					);

				wp_enqueue_script( 'demo_js' );
				wp_enqueue_script( 'jquery-ui-dialog' );

			// Chart.min.js.
			wp_enqueue_script( 'chart', ABANDONED_CART_FOR_WOOCOMMERCE_DIR_URL . 'admin/src/js/node_modules/chart.js/dist/Chart.js', array( 'jquery' ), $this->version, false );
			wp_enqueue_script( 'bundle', ABANDONED_CART_FOR_WOOCOMMERCE_DIR_URL . 'admin/src//js/node_modules/chart.js/dist/Chart.bundle.js', array( 'jquery' ), $this->version, false );
			wp_enqueue_script( 'bundle-min', ABANDONED_CART_FOR_WOOCOMMERCE_DIR_URL . 'admin/src//js/node_modules/chart.js/dist/Chart.bundle.min.js', array( 'jquery' ), $this->version, false );
		}
	}

	/**
	 * Adding settings menu for Abandoned Cart for WooCommerce.
	 *
	 * @since    1.0.0
	 */
	public function mwb_acfw_options_page() {
		global $submenu;
		if ( empty( $GLOBALS['admin_page_hooks']['mwb-plugins'] ) ) {
			add_menu_page( 'MakeWebBetter', 'MakeWebBetter', 'manage_options', 'mwb-plugins', array( $this, 'mwb_plugins_listing_page' ), ABANDONED_CART_FOR_WOOCOMMERCE_DIR_URL . 'admin/src/images/MWB_Grey-01.svg', 15 );
			$acfw_menus = apply_filters( 'mwb_add_plugins_menus_array', array() );
			if ( is_array( $acfw_menus ) && ! empty( $acfw_menus ) ) {
				foreach ( $acfw_menus as $acfw_key => $acfw_value ) {
					add_submenu_page( 'mwb-plugins', $acfw_value['name'], $acfw_value['name'], 'manage_options', $acfw_value['menu_link'], array( $acfw_value['instance'], $acfw_value['function'] ) );
				}
			}
		}
	}

	/**
	 * Removing default submenu of parent menu in backend dashboard
	 *
	 * @since   1.0.0
	 */
	public function mwb_acfw_remove_default_submenu() {
		global $submenu;
		if ( is_array( $submenu ) && array_key_exists( 'mwb-plugins', $submenu ) ) {
			if ( isset( $submenu['mwb-plugins'][0] ) ) {
				unset( $submenu['mwb-plugins'][0] );
			}
		}
	}


	/**
	 * Abandoned Cart for WooCommerce mwb_acfw_admin_submenu_page.
	 *
	 * @since 1.0.0
	 * @param array $menus Marketplace menus.
	 */
	public function mwb_acfw_admin_submenu_page( $menus = array() ) {
		$menus[] = array(
			'name'      => 'Abandoned Cart for WooCommerce',
			'slug'      => 'abandoned_cart_for_woocommerce_menu',
			'menu_link' => 'abandoned_cart_for_woocommerce_menu',
			'instance'  => $this,
			'function'  => 'mwb_acfw_options_menu_html',
		);
		return $menus;
	}


	/**
	 * Abandoned Cart for WooCommerce mwb_plugins_listing_page.
	 *
	 * @since 1.0.0
	 */
	public function mwb_plugins_listing_page() {
		$active_marketplaces = apply_filters( 'mwb_add_plugins_menus_array', array() );
		if ( is_array( $active_marketplaces ) && ! empty( $active_marketplaces ) ) {
			require ABANDONED_CART_FOR_WOOCOMMERCE_DIR_PATH . 'admin/partials/welcome.php';
		}
	}

	/**
	 * Abandoned Cart for WooCommerce admin menu page.
	 *
	 * @since    1.0.0
	 */
	public function mwb_acfw_options_menu_html() {

		include_once ABANDONED_CART_FOR_WOOCOMMERCE_DIR_PATH . 'admin/partials/abandoned-cart-for-woocommerce-admin-dashboard.php';
	}

	/**
	 * Function name mwb_abandon_setting_tabs
	 * this fucntion will used to craete setting tabs for admin dashboard
	 *
	 * @param [type] $acfw_default_tabs all custom setting tabs.
	 * @return array
	 * @since             1.0.0
	 */
	public function mwb_abandon_setting_tabs( $acfw_default_tabs ) {
		$acfw_default_tabs['abandoned-cart-for-woocommerce-email-workflow'] = array(
			'title' => esc_html__( 'Email Work Flow', 'abandoned-cart-for-woocommerce' ),
			'name'  => 'abandoned-cart-for-woocommerce-email-workflow',
		);
		$acfw_default_tabs['class-abandoned-cart-for-woocommerce-report'] = array(
			'title' => esc_html__( 'Abandon Cart Reports ', 'abandoned-cart-for-woocommerce' ),
			'name'  => 'class-abandoned-cart-for-woocommerce-report',
		);
		$acfw_default_tabs['abandoned-cart-for-woocommerce-analytics'] = array(
			'title' => esc_html__( 'Abandon Cart Analytics ', 'abandoned-cart-for-woocommerce' ),
			'name'  => 'abandoned-cart-for-woocommerce-analytics',
		);
		$acfw_default_tabs['abandoned-cart-for-woocommerce-overview'] = array(
			'title' => esc_html__( ' Overview', 'abandoned-cart-for-woocommerce' ),
			'name'  => 'abandoned-cart-for-woocommerce-overview',
		);

		return $acfw_default_tabs;
	}


	/**
	 * Abandoned Cart for WooCommerce admin menu page.
	 *
	 * @since    1.0.0
	 * @param array $acfw_settings_general Settings fields.
	 */
	public function mwb_acfw_admin_general_settings_page( $acfw_settings_general ) {
		$roles = wp_roles();
		$role  = $roles->role_names;

		$acfw_settings_general = array(
			array(
				'title'       => __( 'Enable plugin', 'abandoned-cart-for-woocommerce' ),
				'type'        => 'radio-switch',
				'description' => __( 'Enable plugin to start the functionality.', 'abandoned-cart-for-woocommerce' ),
				'id'          => 'mwb_enable_acfw',
				'value'       => get_option( 'mwb_enable_acfw' ),
				'class'       => 'acfw-radio-switch-class',
				'options'     => array(
					'yes' => __( 'YES', 'abandoned-cart-for-woocommerce' ),
					'no'  => __( 'NO', 'abandoned-cart-for-woocommerce' ),
				),
			),
			array(
				'title'       => __( 'Add to Cart Pop-Up', 'abandoned-cart-for-woocommerce' ),
				'type'        => 'radio-switch',
				'description' => __( 'Enable this to show pop-up at the add to cart time.', 'abandoned-cart-for-woocommerce' ),
				'id'          => 'mwb_enable_atc_popup',
				'value'       => get_option( 'mwb_enable_atc_popup' ),
				'class'       => 'm-radio-switch-class',
				'options'      => array(
					'yes' => __( 'YES', 'abandoned-cart-for-woocommerce' ),
					'no'  => __( 'NO', 'abandoned-cart-for-woocommerce' ),
				),
			),
			array(
				'title'       => __( 'Add to Cart Pop-Up Title', 'abandoned-cart-for-woocommerce' ),
				'type'        => 'text',
				'description' => __( 'Enter title here to show on add to cart pop-up.', 'abandoned-cart-for-woocommerce' ),
				'id'          => 'mwb_atc_title',
				'value'       => get_option( 'mwb_atc_title' ),
				'class'       => 'acfw-text-class',
				'placeholder' => __( 'Add to Cart title', 'abandoned-cart-for-woocommerce' ),
			),
			array(
				'title'       => __( 'Add to Cart Pop-Up Text', 'abandoned-cart-for-woocommerce' ),
				'type'        => 'text',
				'description' => __( 'Enter text here to show on add to cart pop-up.', 'abandoned-cart-for-woocommerce' ),
				'id'          => 'mwb_atc_text',
				'value'       => get_option( 'mwb_atc_text' ),
				'class'       => 'acfw-text-class',
				'placeholder' => __( 'Add to Cart text', 'abandoned-cart-for-woocommerce' ),
			),
			array(
				'title'       => __( 'Cut-off time', 'abandoned-cart-for-woocommerce' ),
				'type'        => 'number',
				'description' => __( 'Enter time in HOURS after which a cart will be treated as abandoned.', 'abandoned-cart-for-woocommerce' ),
				'id'          => 'mwb_cut_off_time',
				'value'       => get_option( 'mwb_cut_off_time' ),
				'min'         => 1,
				'class'       => 'm-number-class',
				'placeholder' => __( 'Enter Time', 'abandoned-cart-for-woocommerce' ),
			),
			array(
				'title'       => __( 'Delete abandoned cart history', 'abandoned-cart-for-woocommerce' ),
				'type'        => 'number',
				'description' => __( 'Enter number of days before which you dont want to keep history of abandoned cart. Remain blank to never delete history automatically.', 'abandoned-cart-for-woocommerce' ),
				'id'          => 'mwb_delete_time_for_ac',
				'value'       => get_option( 'mwb_delete_time_for_ac' ),
				'min'         => 0,
				'class'       => 'm-number-class',
				'placeholder' => __( 'Enter Time', 'abandoned-cart-for-woocommerce' ),
			),
			array(
				'title'       => __( 'User role for tracking ', 'abandoned-cart-for-woocommerce' ),
				'type'        => 'multiselect',
				'description' => __( 'Select user roles for which you want to track abandoned carts(Guest User Tracking BY Deault).', 'abandoned-cart-for-woocommerce' ),
				'id'          => 'mwb_user_roles',
				'value'       => get_option( 'mwb_user_roles' ),
				'class'       => 'm-multiselect-class mwb-defaut-multiselect',
				'placeholder' => '',
				'options'     => $role,
			),
			array(
				'title'       => __( 'Coupon code prefix', 'abandoned-cart-for-woocommerce' ),
				'type'        => 'text',
				'description' => __( 'Add pattern in which you want to be the coupons for abandoned cart recovery. Generated coupon will be prefix_<random_5_digit_alphanumeric>.', 'abandoned-cart-for-woocommerce' ),
				'id'          => 'mwb_coupon_prefix',
				'value'       => get_option( 'mwb_coupon_prefix' ),
				'class'       => 'm-text-class',
				'placeholder' => __( 'Enter Coupon code', 'abandoned-cart-for-woocommerce' ),
			),
			array(
				'title'       => __( 'Coupon expiry', 'abandoned-cart-for-woocommerce' ),
				'type'        => 'number',
				'description' => __( 'Enter the number of hours after which coupon will be expired if not used. Time will start at the time of coupon send.', 'abandoned-cart-for-woocommerce' ),
				'id'          => 'mwb_coupon_expiry',
				'value'       => get_option( 'mwb_coupon_expiry' ),
				'min'         => 0,
				'class'       => 'm-number-class',
				'placeholder' => __( 'Enter Time', 'abandoned-cart-for-woocommerce' ),
			),
			array(
				'title'       => __( 'Coupon Discount', 'abandoned-cart-for-woocommerce' ),
				'type'        => 'number',
				'description' => __( 'Enter the percentage discount (between 1-100) which will apply on abandoned cart.', 'abandoned-cart-for-woocommerce' ),
				'id'          => 'mwb_coupon_discount',
				'value'       => get_option( 'mwb_coupon_discount' ),
				'min'         => 0,
				'max'         => '100',
				'class'       => 'm-number-class',
				'placeholder' => __( 'Enter Time', 'abandoned-cart-for-woocommerce' ),
			),
			array(
				'type'        => 'button',
				'id'          => 'save_general',
				'button_text' => __( 'Save Settings', 'abandoned-cart-for-woocommerce' ),
				'class'       => 'm-button-class myclick',
			),
		);

		return $acfw_settings_general;
	}

	/**
	 * Abandoned Cart for WooCommerce support page tabs.
	 *
	 * @since    1.0.0
	 * @param    Array $mwb_acfw_support Settings fields.
	 * @return   Array  $mwb_acfw_support
	 */
	public function mwb_acfw_admin_support_settings_page( $mwb_acfw_support ) {
		$mwb_acfw_support = array(
			array(
				'title'       => __( 'User Guide', 'abandoned-cart-for-woocommerce' ),
				'description' => __( 'View the detailed guides and documentation to set up your plugin.', 'abandoned-cart-for-woocommerce' ),
				'link-text'   => __( 'VIEW', 'abandoned-cart-for-woocommerce' ),
				'link'        => '',
			),
			array(
				'title'       => __( 'Free Support', 'abandoned-cart-for-woocommerce' ),
				'description' => __( 'Please submit a ticket , our team will respond within 24 hours.', 'abandoned-cart-for-woocommerce' ),
				'link-text'   => __( 'SUBMIT', 'abandoned-cart-for-woocommerce' ),
				'link'        => '',
			),
		);

		return apply_filters( 'mwb_acfw_add_support_content', $mwb_acfw_support );
	}

	/**
	 * Abandoned Cart for WooCommerce save tab settings.
	 *
	 * @since 1.0.0
	 */
	public function mwb_acfw_admin_save_tab_settings() {
		global $acfw_mwb_acfw_obj;
		global $error_notice;
		global $result;
		if ( isset( $_POST['save_general'] ) ) {
			if ( wp_verify_nonce( sanitize_text_field( wp_unslash( isset( $_POST['nonce'] ) ? $_POST['nonce'] : '' ) ) ) ) {
				$mwb_acfw_gen_flag = false;
				$acfw_genaral_settings = apply_filters( 'acfw_general_settings_array', array() );
				$acfw_button_index = array_search( 'submit', array_column( $acfw_genaral_settings, 'type' ) );
				if ( isset( $acfw_button_index ) && ( null == $acfw_button_index || '' == $acfw_button_index ) ) {
					$acfw_button_index = array_search( 'button', array_column( $acfw_genaral_settings, 'type' ) );
				}
				if ( isset( $acfw_button_index ) && '' !== $acfw_button_index ) {
					unset( $acfw_genaral_settings[ $acfw_button_index ] );
					if ( is_array( $acfw_genaral_settings ) && ! empty( $acfw_genaral_settings ) ) {
						foreach ( $acfw_genaral_settings as $acfw_genaral_setting ) {
							if ( isset( $acfw_genaral_setting['id'] ) && '' !== $acfw_genaral_setting['id'] ) {
								if ( isset( $_POST[ $acfw_genaral_setting['id'] ] ) ) {
									$result = isset( $_POST ) ? map_deep( wp_unslash( $_POST ), 'sanitize_text_field' ) : '';
									update_option( $acfw_genaral_setting['id'], $result[ $acfw_genaral_setting ['id'] ] );
								} else {
									update_option( $acfw_genaral_setting['id'], '' );
								}
							} else {
								$mwb_acfw_gen_flag = true;
							}
						}
					}

					if ( $mwb_acfw_gen_flag ) {
						$mwb_acfw_error_text = esc_html__( 'Id of some field is missing', 'abandoned-cart-for-woocommerce' );
					} else {
						$error_notice = false;
						$mwb_acfw_error_text = esc_html__( 'Settings saved !', 'abandoned-cart-for-woocommerce' );
					}
				}
			} else {
				esc_html_e( ' Nonce Not Verified ', 'abandoned-cart-for-woocommerce' );
			}
		}
	}


	/**
	 * Function mwb_save_email_tab_settings
	 * This function is used to save the email settings.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function mwb_save_email_tab_settings() {
		if ( isset( $_POST['submit_workflow'] ) ) {
			if ( wp_verify_nonce( isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '' ) ) {
					global $wpdb;
					$final_checkbox_arr = array();

					$checkbox_arrs = array_key_exists( 'checkbox', $_POST ) ? map_deep( wp_unslash( $_POST['checkbox'] ), 'sanitize_text_field' ) : '';
					$time_arr     = array_key_exists( 'time', $_POST ) ? map_deep( wp_unslash( $_POST['time'] ), 'sanitize_text_field' ) : '';
					$email_arr    = array_key_exists( 'email_workflow_content', $_POST ) ? wp_unslash( $_POST['email_workflow_content'] ) : '';   // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
					$mail_subject = array_key_exists( 'subject', $_POST ) ? map_deep( wp_unslash( $_POST['subject'] ), 'sanitize_text_field' ) : '';
				if ( ! empty( $checkbox_arrs ) ) {
					$count = 0;

					if ( ! array_key_exists( 'check_0', $checkbox_arrs ) ) {
							$checkbox_arrs['check_0'][0] = 'off';
					}
					if ( ! array_key_exists( 'check_1', $checkbox_arrs ) ) {
							$checkbox_arrs['check_1'][0] = 'off';
					}
					if ( ! array_key_exists( 'check_2', $checkbox_arrs ) ) {
							$checkbox_arrs['check_2'][0] = 'off';
					}
					// WMPL .
					/**
					 * Register strings for translation.
					 */
					if ( function_exists( 'icl_register_string' ) ) {
						icl_register_string( 'Mail_subject', 'Mail subject - input field', $mail_subject );
					}
					foreach ( $checkbox_arrs as $key => $value ) {
						$count = explode( '_', $key );
						$count = $count[1];
						$enable  = $value[0];
						$time    = $time_arr[ $count ];
						$email   = $email_arr[ $count ];
						$subject = $mail_subject [ $count ];

						$wpdb->update(
							$wpdb->prefix . 'mwb_email_workflow',
							array(
								'ew_enable'        => $enable,
								'ew_mail_subject' => $subject,
								'ew_content'       => $email,
								'ew_initiate_time' => $time,

							),
							array(
								'ew_id' => ( $count + 1 ),
							)
						);

					}
				}
			} else {
				echo esc_html__( 'Nonce not verified', 'abandoned-cart-for-woocommerce' );
			}
		}
	}

	/**
	 * Callback function for ajax request handling.
	 *
	 * @return void
	 * @since             1.0.0
	 */
	public function mwb_abdn_cart_viewing_cart_from_quick_view() {
		global $wpdb;
		check_ajax_referer( 'custom', 'nonce' );
		$cart_id   = sanitize_text_field( wp_unslash( isset( $_POST['cart_id'] ) ? $_POST['cart_id'] : '' ) );
		$cart_data = $wpdb->get_results( $wpdb->prepare( ' SELECT cart FROM ' . $wpdb->prefix . 'mwb_abandoned_cart WHERE id = %d ', $cart_id ) );
		$cart      = json_decode( $cart_data[0]->cart, true );
		?>
		<table>
				<tr>
					<th>
						<?php esc_html_e( 'Product Id', 'abandoned-cart-for-woocommerce' ); ?>
					</th>
					<th>
						<?php esc_html_e( 'Product Name', 'abandoned-cart-for-woocommerce' ); ?>
					</th>
					<th>
						<?php esc_html_e( 'Quantity', 'abandoned-cart-for-woocommerce' ); ?>
					</th>
					<th>
						<?php esc_html_e( 'Total', 'abandoned-cart-for-woocommerce' ); ?>
					</th>
				</tr>
		<?php
		foreach ( $cart as $key => $value ) {
			$product_id = $value['product_id'];
			$quantity   = $value['quantity'];
			$total      = $value['line_total'];
			?>
				<tr>
					<td>
						<?php echo esc_html( $product_id ); ?>
					</td>
					<td>
						<?php
							$product = wc_get_product( $product_id );

								echo esc_html( $product->get_title() );
						?>
					</td>
					<td>
						<?php echo esc_html( $quantity ); ?>
					</td>
					<td>
						<?php echo esc_html( $total ); ?>
					</td>

				</tr>
		
		<?php } ?>
		</table>
		<?php
		wp_die();
	}

	/**
	 * Function name mwb_get_exit_location
	 * this function will store details about user from where he left the page.
	 *
	 * @return void
	 * @since             1.0.0
	 */
	public function mwb_get_exit_location() {
		check_ajax_referer( 'custom', 'nonce' );
		$left_url    = isset( $_POST['cust_url'] ) ? sanitize_text_field( wp_unslash( $_POST['cust_url'] ) ) : '';
		global $wpdb;
		$ip             = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
		$mwb_abndon_key = isset( $_COOKIE['mwb_cookie_data'] ) ? sanitize_text_field( wp_unslash( $_COOKIE['mwb_cookie_data'] ) ) : '';
		$res = $wpdb->get_results( $wpdb->prepare( 'SELECT id FROM ' . $wpdb->prefix . 'mwb_abandoned_cart WHERE mwb_abandon_key = %s AND ip_address = %s', $mwb_abndon_key, $ip ) );
		if ( ! empty( $res ) ) {
			$wpdb->update( //phpcs:ignore.
				$wpdb->prefix . 'mwb_abandoned_cart',
				array(
					'left_page' => $left_url,
				),
				array(
					'mwb_abandon_key' => $mwb_abndon_key,
					'ip_address'      => $ip,
				)
			);
		}
		wp_die();
	}


	/**
	 * Function to get the data
	 *
	 * @return void
	 * @since             1.0.0
	 */
	public function mwb_get_data() {
		global $wpdb,$wp_query;
		$data = $wpdb->get_results( 'SELECT monthname(time) as MONTHNAME,count(id) as count  FROM ' . $wpdb->prefix . 'mwb_abandoned_cart WHERE cart_status != 0 group by monthname(time) order by time ASC' );

		echo wp_json_encode( $data );
		wp_die();

	}
	/**
	 *  Function name mwb_save__guest_mail()
	 * This Function is used to save email that has been captured from the checkuot page.
	 *
	 * @return void
	 * @since             1.0.0
	 */
	public function mwb_save__guest_mail() {
		check_ajax_referer( 'custom', 'nonce' );

		global $wpdb;
		$mwb_abadoned_key = isset( $_COOKIE['mwb_cookie_data'] ) ? sanitize_text_field( wp_unslash( $_COOKIE['mwb_cookie_data'] ) ) : '';
		$mail             = ! empty( $_POST['guest_user_email'] ) ? sanitize_text_field( wp_unslash( $_POST['guest_user_email'] ) ) : '';
		$ip_address       = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
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
