<?php
// /**
//  * Exit if accessed directly
//  *
//  * @package    woo-gift-cards-lite
//  * @since             1.0.0
//  */

// if ( ! defined( 'ABSPATH' ) ) {
// 	exit();
// }

// /**
//  * Return the default suggested privacy policy content.
//  *
//  * @since             1.0.0
//  * @return string The default policy content.
//  * @name mwb_wgm_plugin_get_default_privacy_content
//  * @author makewebbetter<ticket@makewebbetter.com>
//  * @link https://www.makewebbetter.com/
//  */
// function mwb_acwf_plugin_get_privacy_content() {
// 	return '<h2>' . __( 'Stored Recipient Details for tracking Abandoned Carts', '' ) . '</h2>' .
// 	'<p>' . __( "We store your recipient's email address, recipient's ip address so that we can send them email with details of the Cart", 'woo-gift-cards-lite' ) . '</p>';
// }

// /**
//  * Add the suggested privacy policy text to the policy postbox.
//  *
//  * @since             1.0.0
//  * @name mwb_acwf_plugin_add_suggested_privacy_content
//  * @author makewebbetter<@makewebbetter.com>
//  * @link https://www.makewebbetter.com/
//  */
// function mwb_acwf_plugin_add_suggested_privacy_content() {
// 	$content = mwb_acwf_plugin_get_privacy_content();
// 	wp_add_privacy_policy_content( __( 'ABANDONED CART FOR WOOCOMMERCE', 'woo-gift-cards-lite' ), $content );
// }

// add_action( 'admin_init', 'mwb_acwf_plugin_add_suggested_privacy_content', 20 );

// // Export Personal Data.

// /**
//  * Register exporter for Plugin user data.
//  *
//  * @since             1.0.0
//  * @see https://github.com/allendav/wp-privacy-requests/blob/master/EXPORT.md
//  * @param array $exporters Details of all the exporters.
//  * @return array
//  * @name mwb_wgm_plugin_register_exporters
//  * @author makewebbetter<ticket@makewebbetter.com>
//  * @link https://www.makewebbetter.com/
//  */
// function mwb_acwf_plugin_register_exporters( $exporters ) {
// 	$exporters[] = array(
// 		'exporter_friendly_name' => __( 'Recipient Details', 'woo-gift-cards-lite' ),
// 		'callback'               => 'mwb_acfw_plugin_user_data_exporter',
// 	);
// 	return $exporters;
// }

// add_filter( 'wp_privacy_personal_data_exporters', 'mwb_acwf_plugin_register_exporters' );


// /**
//  * Exporter for Plugin user data.
//  *
//  * @since             1.0.0
//  * @see https://github.com/allendav/wp-privacy-requests/blob/master/EXPORT.md
//  * @param string $email_address Contains Email Addresss.
//  * @param int    $page contains page.
//  * @return array
//  * @name mwb_acfw_plugin_user_data_exporter
//  * @author makewebbetter<ticket@makewebbetter.com>
//  * @link https://www.makewebbetter.com/
//  */
// function mwb_acfw_plugin_user_data_exporter( $email_address, $page = 1 ) {
// 	$export_data = array();
// 	$user = get_user_by( 'email', $email_address );
    
// 	if ( $user && $user->ID ) {

// 		$item_id = "mwb-wgm-recipient-details-{$user->ID}";

// 		$group_id = 'mwb-wgm-recipient-details';

// 		$group_label = __( 'Gift Card Details', 'woo-gift-cards-lite' );

// 		// Plugins can add as many items in the item data array as they want.
// 		$data = array();
//         $export_data[] = array(
//             'group_id'    => $group_id,
//             'group_label' => $group_label,
//             'item_id'     => $item_id,
//             'data'        => $data,
//         );

// 		// Add the user's recipient's details, and along with user itself.

// 	}
// 	// Returns an array of exported items for this pass, but also a boolean whether this exporter is finished.
// 	// If not it will be called again with $page increased by 1.
// 	return array(
// 		'data' => $export_data,
// 		'done' => true,
// 	);
// }

// // Delete Personal Data.

// /**
//  * Register eraser for Plugin user data.
//  *
//  * @since             1.0.0
//  * @param array $erasers contains erased data.
//  * @return array
//  * @name mwb_wgm_plugin_register_erasers
//  * @author makewebbetter<ticket@makewebbetter.com>
//  * @link https://www.makewebbetter.com/
//  */
// function mwb_wgm_plugin_register_erasers( $erasers = array() ) {
// 	$erasers[] = array(
// 		'eraser_friendly_name' => __( 'Recipient Details', 'woo-gift-cards-lite' ),
// 		'callback'               => 'mwb_wgm_plugin_user_data_eraser',
// 	);
// 	return $erasers;
// }

// add_filter( 'wp_privacy_personal_data_erasers', 'mwb_wgm_plugin_register_erasers' );

// /**
//  * Eraser for Plugin user data.
//  *
//  * @since             1.0.0
//  * @param string $email_address contains email address.
//  * @param int    $page conains page.
//  * @return array
//  * @name mwb_wgm_plugin_user_data_eraser
//  * @author makewebbetter<ticket@makewebbetter.com>
//  * @link https://www.makewebbetter.com/
//  */
// function mwb_wgm_plugin_user_data_eraser( $email_address, $page = 1 ) {
// 	if ( empty( $email_address ) ) {
// 		return array(
// 			'items_removed'  => false,
// 			'items_retained' => false,
// 			'messages'       => array(),
// 			'done'           => true,
// 		);
// 	}
// 	$user = get_user_by( 'email', $email_address );
// 	$messages = array();
// 	$items_removed  = false;
// 	$items_retained = false;
// 	if ( $user && $user->ID ) {
// 		// Delete their order meta keys.

// 		$customer_orders = get_posts(
// 			array(
// 				'numberposts' => -1,
// 				'meta_key'    => '_customer_user',
// 				'meta_value'  => $user->ID,
// 				'post_type'   => wc_get_order_types(),
// 				'post_status' => array_keys( wc_get_order_statuses() ),
// 			)
// 		);
// 		if ( isset( $customer_orders ) && ! empty( $customer_orders ) ) {
// 			foreach ( $customer_orders as $order_key => $orders ) {
// 				$order_id = $orders->ID;
// 				if ( isset( $order_id ) && ! empty( $order_id ) ) {
// 					$order = wc_get_order( $order_id );
// 					foreach ( $order->get_items() as $item_id => $item ) {
// 						$item_meta_data = $item->get_meta_data();
// 						$to = '';
// 						$from = '';
// 						$gift_msg = '';
// 						if ( ! empty( $item_meta_data ) ) {
// 							foreach ( $item_meta_data as $key => $value ) {
// 								if ( isset( $value->key ) && 'To' == $value->key && ! empty( $value->value ) ) {
// 									$status = woocommerce_delete_order_item_meta( $item_id, $value->key, $value->value, true );
// 									if ( $status ) {
// 										$items_removed  = true;
// 									} else {
// 										$messages[] = __( 'Removed key "TO"', 'woo-gift-cards-lite' );
// 										$items_retained = true;
// 									}
// 								}
// 								if ( isset( $value->key ) && 'From' == $value->key && ! empty( $value->value ) ) {
// 									$status = woocommerce_delete_order_item_meta( $item_id, $value->key, $value->value, true );
// 									if ( $status ) {
// 										$items_removed  = true;
// 									} else {
// 										$messages[] = __( 'Removed key "From"', 'woo-gift-cards-lite' );
// 										$items_retained = true;
// 									}
// 								}
// 								if ( isset( $value->key ) && 'Message' == $value->key && ! empty( $value->value ) ) {
// 									$status = woocommerce_delete_order_item_meta( $item_id, $value->key, $value->value, true );
// 									if ( $status ) {
// 										$items_removed  = true;
// 									} else {
// 										$messages[] = __( 'Removed key "Message"', 'woo-gift-cards-lite' );
// 										$items_retained = true;
// 									}
// 								}
// 							}
// 						} else {
// 							$items_removed  = true;
// 						}
// 					}
// 				}
// 			}
// 		}
// 	}
// 	// Returns an array of exported items for this pass, but also a boolean whether this exporter is finished.
// 	// If not it will be called again with $page increased by 1.
// 	return array(
// 		'items_removed'  => $items_removed,
// 		'items_retained' => $items_retained,
// 		'messages'       => $messages,
// 		'done'           => true,
// 	);
// }
