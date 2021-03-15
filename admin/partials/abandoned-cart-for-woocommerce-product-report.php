<?php
/**
 * Provide woocommerce reports of abandoned carts product-wise.
 *
 * This file is used to show reports ad details of the abandoned carts.
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

	?>
	<h3>Reports By product wise</h3>
	<?php

		global $wpdb;
			$arr = array();
			$result  = $wpdb->get_results( 'SELECT *  FROM mwb_abandoned_cart ' );
			foreach ( $result as $k=> $value ){
				$cart = $value->cart;
				$decode_cart = json_decode( $cart );
				foreach ( $decode_cart as $key => $val ) {
					$id = $val->product_id;
					if ( array_key_exists( $id, $arr ) ) {
						$arr[ $id ] = $arr[ $id ] +1;
					} else {
						$arr[ $id ] = 1;
					}
				}
			}
			?>
			<table>
			<tr>
			<th>
			<h6><?php esc_html_e( 'Product Name', 'abandoned-cart-for-woocommerce' ); ?></h6>
			</th>
			<th>
			<h6><?php esc_html_e( 'Abandoned time', 'abandoned-cart-for-woocommerce' ); ?></h6>
			</th>
			</tr>
			<?php
			foreach ( $arr as $key=>$val ) {
				$product = wc_get_product( $key );?>
				<tr>
				<td>
				<?php	echo $product->get_title();?>
				</td>
				<td>
				<?php echo esc_html(  $val );?>
				</td>
				</tr>

			   <?php
			}
			?>

			</table>
			<?php

