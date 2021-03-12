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
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 * @param    string $hook      The plugin page slug.
	 */
	public function acfw_admin_enqueue_styles( $hook ) {
		$screen = get_current_screen();
		if ( isset( $screen->id ) && 'makewebbetter_page_abandoned_cart_for_woocommerce_menu' == $screen->id ) {

			wp_enqueue_style( 'mwb-acfw-select2-css', ABANDONED_CART_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/select-2/abandoned-cart-for-woocommerce-select2.css', array(), time(), 'all' );

			wp_enqueue_style( 'mwb-acfw-meterial-css', ABANDONED_CART_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/material-design/material-components-web.min.css', array(), time(), 'all' );
			wp_enqueue_style( 'mwb-acfw-meterial-css2', ABANDONED_CART_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/material-design/material-components-v5.0-web.min.css', array(), time(), 'all' );
			wp_enqueue_style( 'mwb-acfw-meterial-lite', ABANDONED_CART_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/material-design/material-lite.min.css', array(), time(), 'all' );

			wp_enqueue_style( 'mwb-acfw-meterial-icons-css', ABANDONED_CART_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/material-design/icon.css', array(), time(), 'all' );

			wp_enqueue_style( $this->plugin_name . '-admin-global', ABANDONED_CART_FOR_WOOCOMMERCE_DIR_URL . 'admin/src/scss/abandoned-cart-for-woocommerce-admin-global.css', array( 'mwb-acfw-meterial-icons-css' ), time(), 'all' );

			wp_enqueue_style( $this->plugin_name, ABANDONED_CART_FOR_WOOCOMMERCE_DIR_URL . 'admin/src/scss/abandoned-cart-for-woocommerce-admin.scss', array(), $this->version, 'all' );
		}

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 * @param    string $hook      The plugin page slug.
	 */
	public function acfw_admin_enqueue_scripts( $hook ) {

		$screen = get_current_screen();
		if ( isset( $screen->id ) && 'makewebbetter_page_abandoned_cart_for_woocommerce_menu' == $screen->id ) {
			wp_enqueue_script( 'mwb-acfw-select2', ABANDONED_CART_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/select-2/abandoned-cart-for-woocommerce-select2.js', array( 'jquery' ), time(), false );

			wp_enqueue_script( 'mwb-acfw-metarial-js', ABANDONED_CART_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/material-design/material-components-web.min.js', array(), time(), false );
			wp_enqueue_script( 'mwb-acfw-metarial-js2', ABANDONED_CART_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/material-design/material-components-v5.0-web.min.js', array(), time(), false );
			wp_enqueue_script( 'mwb-acfw-metarial-lite', ABANDONED_CART_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/material-design/material-lite.min.js', array(), time(), false );

			wp_register_script( $this->plugin_name . 'admin-js', ABANDONED_CART_FOR_WOOCOMMERCE_DIR_URL . 'admin/src/js/abandoned-cart-for-woocommerce-admin.js', array( 'jquery', 'mwb-acfw-select2', 'mwb-acfw-metarial-js', 'mwb-acfw-metarial-js2', 'mwb-acfw-metarial-lite' ), $this->version, false );

			wp_localize_script(
				$this->plugin_name . 'admin-js',
				'acfw_admin_param',
				array(
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
					'reloadurl' => admin_url( 'admin.php?page=abandoned_cart_for_woocommerce_menu' ),
					'acfw_gen_tab_enable' => get_option( 'acfw_radio_switch_demo' ),
				)
			);

			wp_enqueue_script( $this->plugin_name . 'admin-js' );
		}
	}

	/**
	 * Adding settings menu for Abandoned Cart for WooCommerce.
	 *
	 * @since    1.0.0
	 */
	public function acfw_options_page() {
		global $submenu;
		if ( empty( $GLOBALS['admin_page_hooks']['mwb-plugins'] ) ) {
			add_menu_page( __( 'MakeWebBetter', 'abandoned-cart-for-woocommerce' ), __( 'MakeWebBetter', 'abandoned-cart-for-woocommerce' ), 'manage_options', 'mwb-plugins', array( $this, 'mwb_plugins_listing_page' ), ABANDONED_CART_FOR_WOOCOMMERCE_DIR_URL . 'admin/src/images/mwb-logo.png', 15 );
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
	 * Abandoned Cart for WooCommerce acfw_admin_submenu_page.
	 *
	 * @since 1.0.0
	 * @param array $menus Marketplace menus.
	 */
	public function acfw_admin_submenu_page( $menus = array() ) {
		$menus[] = array(
			'name'            => __( 'Abandoned Cart for WooCommerce', 'abandoned-cart-for-woocommerce' ),
			'slug'            => 'abandoned_cart_for_woocommerce_menu',
			'menu_link'       => 'abandoned_cart_for_woocommerce_menu',
			'instance'        => $this,
			'function'        => 'acfw_options_menu_html',
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
	public function acfw_options_menu_html() {

		include_once ABANDONED_CART_FOR_WOOCOMMERCE_DIR_PATH . 'admin/partials/abandoned-cart-for-woocommerce-admin-dashboard.php';
	}


	/**
	 * Abandoned Cart for WooCommerce admin menu page.
	 *
	 * @since    1.0.0
	 * @param array $acfw_settings_general Settings fields.
	 */
	public function acfw_admin_general_settings_page( $acfw_settings_general ) {

		$acfw_settings_general = array(
			array(
				'title' => __( 'Enable plugin', 'abandoned-cart-for-woocommerce' ),
				'type'  => 'radio-switch',
				'description'  => __( 'Enable plugin to start the functionality.', 'abandoned-cart-for-woocommerce' ),
				'id'    => 'acfw_radio_switch_demo',
				'value' => get_option( 'acfw_radio_switch_demo' ),
				'class' => 'acfw-radio-switch-class',
				'options' => array(
					'yes' => __( 'YES', 'abandoned-cart-for-woocommerce' ),
					'no' => __( 'NO', 'abandoned-cart-for-woocommerce' ),
				),
			),

			array(
				'type'  => 'button',
				'id'    => 'acfw_button_demo',
				'button_text' => __( 'Button Demo', 'abandoned-cart-for-woocommerce' ),
				'class' => 'acfw-button-class',
			),
		);
		// print_r($acfw_settings_general);die;
		return $acfw_settings_general;
	}

	/**
	 * Abandoned Cart for WooCommerce admin menu page.
	 *
	 * @since    1.0.0
	 * @param array $acfw_settings_template Settings fields.
	 */
	public function acfw_admin_template_settings_page( $acfw_settings_template ) {
		$acfw_settings_template = array(
			array(
				'title' => __( 'Text Field Demo', 'abandoned-cart-for-woocommerce' ),
				'type'  => 'text',
				'description'  => __( 'This is text field demo follow same structure for further use.', 'abandoned-cart-for-woocommerce' ),
				'id'    => 'acfw_text_demo',
				'value' => '',
				'class' => 'acfw-text-class',
				'placeholder' => __( 'Text Demo', 'abandoned-cart-for-woocommerce' ),
			),
			array(
				'title' => __( 'Number Field Demo', 'abandoned-cart-for-woocommerce' ),
				'type'  => 'number',
				'description'  => __( 'This is number field demo follow same structure for further use.', 'abandoned-cart-for-woocommerce' ),
				'id'    => 'acfw_number_demo',
				'value' => '',
				'class' => 'acfw-number-class',
				'placeholder' => '',
			),
			array(
				'title' => __( 'Password Field Demo', 'abandoned-cart-for-woocommerce' ),
				'type'  => 'password',
				'description'  => __( 'This is password field demo follow same structure for further use.', 'abandoned-cart-for-woocommerce' ),
				'id'    => 'acfw_password_demo',
				'value' => '',
				'class' => 'acfw-password-class',
				'placeholder' => '',
			),
			array(
				'title' => __( 'Textarea Field Demo', 'abandoned-cart-for-woocommerce' ),
				'type'  => 'textarea',
				'description'  => __( 'This is textarea field demo follow same structure for further use.', 'abandoned-cart-for-woocommerce' ),
				'id'    => 'acfw_textarea_demo',
				'value' => '',
				'class' => 'acfw-textarea-class',
				'rows' => '5',
				'cols' => '10',
				'placeholder' => __( 'Textarea Demo', 'abandoned-cart-for-woocommerce' ),
			),
			array(
				'title' => __( 'Select Field Demo', 'abandoned-cart-for-woocommerce' ),
				'type'  => 'select',
				'description'  => __( 'This is select field demo follow same structure for further use.', 'abandoned-cart-for-woocommerce' ),
				'id'    => 'acfw_select_demo',
				'value' => '',
				'class' => 'acfw-select-class',
				'placeholder' => __( 'Select Demo', 'abandoned-cart-for-woocommerce' ),
				'options' => array(
					'' => __( 'Select option', 'abandoned-cart-for-woocommerce' ),
					'INR' => __( 'Rs.', 'abandoned-cart-for-woocommerce' ),
					'USD' => __( '$', 'abandoned-cart-for-woocommerce' ),
				),
			),
			array(
				'title' => __( 'Multiselect Field Demo', 'abandoned-cart-for-woocommerce' ),
				'type'  => 'multiselect',
				'description'  => __( 'This is multiselect field demo follow same structure for further use.', 'abandoned-cart-for-woocommerce' ),
				'id'    => 'acfw_multiselect_demo',
				'value' => '',
				'class' => 'acfw-multiselect-class mwb-defaut-multiselect',
				'placeholder' => '',
				'options' => array(
					'default' => __( 'Select currency code from options', 'abandoned-cart-for-woocommerce' ),
					'INR' => __( 'Rs.', 'abandoned-cart-for-woocommerce' ),
					'USD' => __( '$', 'abandoned-cart-for-woocommerce' ),
				),
			),
			array(
				'title' => __( 'Checkbox Field Demo', 'abandoned-cart-for-woocommerce' ),
				'type'  => 'checkbox',
				'description'  => __( 'This is checkbox field demo follow same structure for further use.', 'abandoned-cart-for-woocommerce' ),
				'id'    => 'acfw_checkbox_demo',
				'value' => '',
				'class' => 'acfw-checkbox-class',
				'placeholder' => __( 'Checkbox Demo', 'abandoned-cart-for-woocommerce' ),
			),

			array(
				'title' => __( 'Radio Field Demo', 'abandoned-cart-for-woocommerce' ),
				'type'  => 'radio',
				'description'  => __( 'This is radio field demo follow same structure for further use.', 'abandoned-cart-for-woocommerce' ),
				'id'    => 'acfw_radio_demo',
				'value' => '',
				'class' => 'acfw-radio-class',
				'placeholder' => __( 'Radio Demo', 'abandoned-cart-for-woocommerce' ),
				'options' => array(
					'yes' => __( 'YES', 'abandoned-cart-for-woocommerce' ),
					'no' => __( 'NO', 'abandoned-cart-for-woocommerce' ),
				),
			),
			array(
				'title' => __( 'Enable', 'abandoned-cart-for-woocommerce' ),
				'type'  => 'radio-switch',
				'description'  => __( 'This is switch field demo follow same structure for further use.', 'abandoned-cart-for-woocommerce' ),
				'id'    => 'acfw_radio_switch_demo',
				'value' => '',
				'class' => 'acfw-radio-switch-class',
				'options' => array(
					'yes' => __( 'YES', 'abandoned-cart-for-woocommerce' ),
					'no' => __( 'NO', 'abandoned-cart-for-woocommerce' ),
				),
			),

			array(
				'type'  => 'button',
				'id'    => 'acfw_button_demo',
				'button_text' => __( 'Button Demo', 'abandoned-cart-for-woocommerce' ),
				'class' => 'acfw-button-class',
			),
		);
		return $acfw_settings_template;
	}


	/**
	 * Abandoned Cart for WooCommerce support page tabs.
	 *
	 * @since    1.0.0
	 * @param    Array $mwb_acfw_support Settings fields.
	 * @return   Array  $mwb_acfw_support
	 */
	public function acfw_admin_support_settings_page( $mwb_acfw_support ) {
		$mwb_acfw_support = array(
			array(
				'title' => __( 'User Guide', 'abandoned-cart-for-woocommerce' ),
				'description' => __( 'View the detailed guides and documentation to set up your plugin.', 'abandoned-cart-for-woocommerce' ),
				'link-text' => __( 'VIEW', 'abandoned-cart-for-woocommerce' ),
				'link' => '',
			),
			array(
				'title' => __( 'Free Support', 'abandoned-cart-for-woocommerce' ),
				'description' => __( 'Please submit a ticket , our team will respond within 24 hours.', 'abandoned-cart-for-woocommerce' ),
				'link-text' => __( 'SUBMIT', 'abandoned-cart-for-woocommerce' ),
				'link' => '',
			),
		);

		return apply_filters( 'mwb_acfw_add_support_content', $mwb_acfw_support );
	}

	/**
	* Abandoned Cart for WooCommerce save tab settings.
	*
	* @since 1.0.0
	*/
	public function acfw_admin_save_tab_settings() {
		global $acfw_mwb_acfw_obj;
		if ( isset( $_POST['acfw_button_demo'] ) ) {
			$mwb_acfw_gen_flag = false;
			$acfw_genaral_settings = apply_filters( 'acfw_general_settings_array', array() );
			$acfw_button_index = array_search( 'submit', array_column( $acfw_genaral_settings, 'type' ) );
			if ( isset( $acfw_button_index ) && ( null == $acfw_button_index || '' == $acfw_button_index ) ) {
				$acfw_button_index = array_search( 'button', array_column( $acfw_genaral_settings, 'type' ) );
			}
			if ( isset( $acfw_button_index ) && '' !== $acfw_button_index ) {
				unset( $acfw_genaral_settings[$acfw_button_index] );
				if ( is_array( $acfw_genaral_settings ) && ! empty( $acfw_genaral_settings ) ) {
					foreach ( $acfw_genaral_settings as $acfw_genaral_setting ) {
						if ( isset( $acfw_genaral_setting['id'] ) && '' !== $acfw_genaral_setting['id'] ) {
							if ( isset( $_POST[$acfw_genaral_setting['id']] ) ) {
								update_option( $acfw_genaral_setting['id'], $_POST[$acfw_genaral_setting['id']] );
							} else {
								update_option( $acfw_genaral_setting['id'], '' );
							}
						}else{
							$mwb_acfw_gen_flag = true;
						}
					}
				}
				if ( $mwb_acfw_gen_flag ) {
					$mwb_acfw_error_text = esc_html__( 'Id of some field is missing', 'abandoned-cart-for-woocommerce' );
					$acfw_mwb_acfw_obj->mwb_acfw_plug_admin_notice( $mwb_acfw_error_text, 'error' );
				}else{
					$mwb_acfw_error_text = esc_html__( 'Settings saved !', 'abandoned-cart-for-woocommerce' );
					$acfw_mwb_acfw_obj->mwb_acfw_plug_admin_notice( $mwb_acfw_error_text, 'success' );
				}
			}
		}
	}
}
