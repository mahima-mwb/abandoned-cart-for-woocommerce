<?php
/**
 * Provide analytics for abandoned carts
 *
 * This file is used to show analytics of the abandoned carts.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Abandoned_Cart_For_Woocommerce
 * @subpackage Abandoned_Cart_For_Woocommerce/admin/partials
 */


if ( ! defined( 'ABSPATH' ) ) {

	exit(); // Exit if accessed directly.
}
/**
 * Class name Analytics_Of_Abandoned_Carts
 */
class Analytics_Of_Abandoned_Carts {
	/**
	 * Function name mwb_show_month_count
	 *
	 * @return void
	 */
	public function mwb_show_month_count() {
	global $wpdb;

		// $result  = $wpdb->get_results( "SELECT * FROM mwb_abandoned_cart WHERE ( email LIKE '%$search_item%' OR cart LIKE '%$search_item%' ) " );
		$present_month = $wpdb->get_results( "SELECT count(id) as id from mwb_abandoned_cart WHERE cart_status = 1 AND  MONTH(time) = MONTH(CURRENT_DATE)" );
		// $sql = "SELECT monthname(time) as MONTHNAME,count(id) from mwb_abandoned_cart  group by monthname(time) ";
		// $sql = "SELECT monthname(time) as MONTHNAME,count(id) from mwb_abandoned_cart  WHERE cart_status = 1 group by monthname(time)";
		// echo '<pre>'; print_r( $result ); echo '</pre>';
		$mwb_abandoned_present_month = $present_month[0]->id;

		echo esc_html_e( 'Abandoned Carts this Month', 'abandoned-cart-for-woocommerce' ) ; 
		echo esc_html( $mwb_abandoned_present_month );
		
		$all_abandoned_count = $wpdb->get_results( "SELECT count(id) as id from mwb_abandoned_cart " );
		$mwb_abandoned_all = $all_abandoned_count[0]->id;
		echo"<br>";
		echo esc_html_e( 'Abandoned Carts till Now', 'abandoned-cart-for-woocommerce' ) ;
		echo esc_html( $mwb_abandoned_all );
		

		echo"<br>";

		$mwb_recovered_present_month = $wpdb->get_results( "SELECT count(id) as id from mwb_abandoned_cart WHERE cart_status = 2 AND  MONTH(time) = MONTH(CURRENT_DATE)" );
		$mwb_present_month_recovered = $mwb_recovered_present_month[0]->id;

		echo esc_html_e( 'Recovered Carts this Month', 'abandoned-cart-for-woocommerce' ) ; 
		echo esc_html( $mwb_present_month_recovered );
		echo"<br>";

		$mwb_recovered_all = $wpdb->get_results( "SELECT count(id) as id from mwb_abandoned_cart WHERE cart_status = 2" );
		$mwb_all_recovered_carts = $mwb_recovered_all[0]->id;

		echo esc_html_e( 'Recovered Carts till Now', 'abandoned-cart-for-woocommerce' ) ; 
		echo esc_html( $mwb_all_recovered_carts );
		echo"<br>";

		$mwb_all_covered_money = $wpdb->get_results( "SELECT sum(total) as money from mwb_abandoned_cart WHERE cart_status = 2" );
		// echo '<pre>'; print_r( $mwb_all_covered_money ); echo '</pre>';
		$mwb_all_money_recovered = $mwb_all_covered_money[0]->money;
		echo esc_html_e( 'Recovered Money', 'abandoned-cart-for-woocommerce' ) ; 
		echo esc_html( "$" . $mwb_all_money_recovered );

		echo"<br>";

		$mwb_abandoned_money = $wpdb->get_results( "SELECT sum(total) as money from mwb_abandoned_cart WHERE cart_status = 1" );
		// echo '<pre>'; print_r( $mwb_all_covered_money ); echo '</pre>';
		$mwb_all_money_abandoned = $mwb_abandoned_money[0]->money;
		echo esc_html_e( 'Money That Can Be recovered', 'abandoned-cart-for-woocommerce' ) ; 
		echo esc_html( "$" . $mwb_all_money_abandoned );


		// // foreach ( $result as $k => $val ) {
		// // 	echo '<pre>'; print_r( $val ); echo '</pre>';
		// // }
		// echo '<pre>'; print_r( $mwb_abandoned_present_month ); echo '</pre>';
		die;

	}
}

$obj = new Analytics_Of_Abandoned_Carts();
$obj->mwb_show_month_count();
