<?php
/**
 * Fired during plugin activation
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Abandoned_Cart_For_Woocommerce
 * @subpackage Abandoned_Cart_For_Woocommerce/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Abandoned_Cart_For_Woocommerce
 * @subpackage Abandoned_Cart_For_Woocommerce/includes
 * @author     makewebbetter <webmaster@makewebbetter.com>
 */
class Abandoned_Cart_For_Woocommerce_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function abandoned_cart_for_woocommerce_activate() {
		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();
		$char2 = $wpdb->get_charset_collate();
		$char3 = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE mwb_abandoned_cart (
		id INT NOT NULL AUTO_INCREMENT,
		u_id INT,
		email varchar(50),
		cart text,
		time datetime NOT NULL,
		total varchar(50),
		cart_status INT(9) NOT NULL,
		workflow_sent varchar(50),
		cron_status boolean,
		mail_count INT,
		ip_address varchar(300),
		mwb_abandon_key varchar(100),
		coupon_code varchar(100),
		left_page varchar(100),
		PRIMARY KEY  (id)
		) $charset_collate;";

		$sql1 = "CREATE TABLE mwb_email_workflow (
			ew_id INT(9) NOT NULL AUTO_INCREMENT,
			ew_enable varchar(10),
			ew_mail_subject varchar(200),
			ew_content varchar(50000),
			ew_initiate_time varchar(5),
			PRIMARY KEY  (ew_id)
		) $char2;";

		$sql2 = "CREATE TABLE mwb_cart_recovery (
			cr_id INT(9) NOT NULL AUTO_INCREMENT,
			ac_id INT,
			ew_id INT,
			time datetime,
			PRIMARY KEY  (cr_id)
		) $char3;";

		require ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql );
		dbDelta( $sql1 );
		dbDelta( $sql2 );


	}

}
