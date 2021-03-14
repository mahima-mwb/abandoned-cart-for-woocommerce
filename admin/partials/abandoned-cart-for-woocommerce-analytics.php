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
}?>


<?php

		global $wpdb;
?>
	<!--==================================
	= CARD SECTION =
	===================================-->

		<div class="mwb-card">
		<ul class="mwb-card__list">
		<li class="mwb-card__list-item">
		<?php
		$present_month = $wpdb->get_results( 'SELECT count(id) as id from mwb_abandoned_cart WHERE cart_status = 1 AND  MONTH(time) = MONTH(CURRENT_DATE)' );
		$mwb_abandoned_present_month = $present_month[0]->id;
		?>
		<h3 class="mwb-card__list-title">
		<?php echo esc_html_e( 'Abandoned Carts this Month', 'abandoned-cart-for-woocommerce' ); ?>
		</h3>
		<div class="mwb-card__list-digit">
		<span><?php echo esc_html( $mwb_abandoned_present_month ); ?></span>
		</div>
	</li>
		<?php

		$all_abandoned_count = $wpdb->get_results( 'SELECT count(id) as id from mwb_abandoned_cart WHERE cart_status = 1 ' );
		$mwb_abandoned_all = $all_abandoned_count[0]->id;
		?>
		<li class="mwb-card__list-item">
		<h3 class="mwb-card__list-title">
			<?php esc_html_e( 'Abandoned Carts till Now', 'abandoned-cart-for-woocommerce' ); ?>
		</h3>
		<div class="mwb-card__list-digit">
		<span><?php echo esc_html( $mwb_abandoned_all ); ?></span>
		</div>
	</li>

		<?php

		$mwb_recovered_present_month = $wpdb->get_results( 'SELECT count(id) as id from mwb_abandoned_cart WHERE cart_status = 2 AND  MONTH(time) = MONTH(CURRENT_DATE)' );
		$mwb_present_month_recovered = $mwb_recovered_present_month[0]->id;
		?>
	<li class="mwb-card__list-item">
		
		<h3 class="mwb-card__list-title">
		<?php esc_html_e( 'Recovered Carts this Month', 'abandoned-cart-for-woocommerce' ); ?>
		</h3>
		<div class="mwb-card__list-digit">
		<span><?php echo esc_html( $mwb_present_month_recovered ); ?></span>
		</div>
	</li>

		
		<?php

		$mwb_recovered_all = $wpdb->get_results( 'SELECT count(id) as id from mwb_abandoned_cart WHERE cart_status = 2' );
		$mwb_all_recovered_carts = $mwb_recovered_all[0]->id;
		?>
		<li class="mwb-card__list-item">
			
			<h3 class="mwb-card__list-title">
			<?php esc_html_e( 'Recovered Carts till Now', 'abandoned-cart-for-woocommerce' ); ?>
			</h3>
			<div class="mwb-card__list-digit">
			<span><?php echo esc_html( $mwb_all_recovered_carts ); ?></span>
			</div>
		</li>
	
			
		<?php
		$mwb_all_covered_money = $wpdb->get_results( 'SELECT sum(total) as money from mwb_abandoned_cart WHERE cart_status = 2' );
		$mwb_all_money_recovered = $mwb_all_covered_money[0]->money;
		?>
		<li class="mwb-card__list-item">
			
			<h3 class="mwb-card__list-title">
			<?php esc_html_e( 'Recovered Money', 'abandoned-cart-for-woocommerce' ); ?>
			</h3>
			<div class="mwb-card__list-digit">
			<span><?php
			$currency = get_option( 'woocommerce_currency' );
			if ( ! empty( $mwb_all_money_recovered ) ) {
				echo esc_html( $currency . $mwb_all_money_recovered );
			}
			else {
				echo esc_html( $currency .  0 );

			}
			  ?></span>
			</div>
		</li>
		<?php
		$mwb_abandoned_money = $wpdb->get_results( 'SELECT sum(total) as money from mwb_abandoned_cart WHERE cart_status = 1' );
		$mwb_all_money_abandoned = $mwb_abandoned_money[0]->money;
		?>
		<li class="mwb-card__list-item">
			
			<h3 class="mwb-card__list-title">
			<?php esc_html_e( 'Money That Can Be recovered', 'abandoned-cart-for-woocommerce' ); ?>
			</h3>
			<div class="mwb-card__list-digit">
			<span><?php 
				$currency = get_option( 'woocommerce_currency' );
			
			if ( ! empty( $mwb_all_money_abandoned ) ) {
				echo esc_html( $currency . $mwb_all_money_abandoned );
			}
			else {
				echo esc_html( $currency .  0 );

			}?></span>
			</div>
		</li>
		</ul>
</div>
<!--==== End of CARD SECTION ====-->

<div class="mwb-card__list-digit">
			<span><?php echo esc_html( 'Graph of Abandoned Carts' ); ?></span>
			</div>
<canvas id="myChart" width="400" height="100">
</canvas>
<script>
jQuery(document).ready(function($){
	$.ajax({
		type: 'GET',
		url: acfw_admin_param.ajaxurl,
		data: { action: 'get_some'
			},
		success: function(response) {
			// alert(response);
			var res = response;
			var decoded = JSON.parse(res);
			console.log(decoded);
			var labelarr = [];
			var dataarr  = [];
			for ( var i = 0; i< decoded.length; i++ ) {
				labelarr[i] = decoded[i].MONTHNAME;
				dataarr[i]  = decoded[i].count;
			}
			var ctx = document.getElementById('myChart').getContext('2d');
			var myChart = new Chart(ctx, {
				type: 'line',
				data: {
					labels: labelarr,
					datasets: [{
						label: 'Monthly Data:',
						data: dataarr,
						backgroundColor: [
							'rgba(255, 99, 132, 0.2)',
							'rgba(54, 162, 235, 0.2)',
							'rgba(255, 206, 86, 0.2)',
							'rgba(75, 192, 192, 0.2)',
							'rgba(153, 102, 255, 0.2)',
							'rgba(255, 159, 64, 0.2)'
						],
						borderColor: [
							'rgba(255, 99, 132, 1)',
							'rgba(54, 162, 235, 1)',
							'rgba(255, 206, 86, 1)',
							'rgba(75, 192, 192, 1)',
							'rgba(153, 102, 255, 1)',
							'rgba(255, 159, 64, 1)'
						],
						borderWidth: 1
					}]
				},
				options: {
				scales: {scales:{yAxes: [{beginAtZero: false}],   beginAtZero: true, xAxes: [{autoskip: true, maxTicketsLimit: 50}]}},
				// tooltips:{mode: 'index'},
				legend:{display: true, position: 'top', labels: {fontColor: 'rgb(255,255,255)', fontSize: 16}}
		}
		});
		}
	});
});

</script>
